<?php

namespace App\Services\WorkOrder;

use App\Events\WorkOrderUpdated;
use App\Interface\WorkOrder\ValidationWoInterface;
use App\Interface\WorkOrder\WoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationWoService
{
    private ValidationWoInterface $validationWoInterface;
    private WoInterface $woInterface;

    public function __construct(
        ValidationWoInterface $validationWoInterface,
        WoInterface $woInterface
    ) {
        $this->validationWoInterface = $validationWoInterface;
        $this->woInterface = $woInterface;
    }

    public function index(array $data = [])
    {
        return $this->validationWoInterface->index($data);
    }

    public function showStatuses()
    {
        return $this->validationWoInterface->showStatuses();
    }

    public function showWo(string $id)
    {
        // Gunakan eager loading dengan relasi yang sudah didefinisikan
        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        if (!$transaction) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }


        $variables = $this->validationWoInterface->showMasterVariables($transaction->information->id);
        $assessments = $variables;
        $details = $transaction->details;
        // Format details berdasarkan urutan assessments

        $detailFormated = [];

        foreach ($assessments as $assessment) {
            // Cari detail yang sesuai dengan assessment ini
            $matchingDetail = collect($details)->firstWhere('assessment_id', $assessment['assessment']['id']);

            if (!$matchingDetail) {
                // Jika tidak ada detail, skip assessment ini
                continue;
            }
            // var_dump('masuk', $assessment['assessment']['id']);

            // Format ulang structure
            $formattedDetail = [
                'assessment' => $assessment['assessment'],
                'detail_id' => $matchingDetail->id,
                'total_score' => $matchingDetail->total_score ?? 0,
                'categories' => []
            ];

            // Mapping categories dan items dari assessments ke details
            foreach ($assessment['categories'] as $categoryData) {
                $categoryId = $categoryData['category']['id'];

                $formattedCategory = [
                    'category' => $categoryData['category'],
                    'total_weight_active' => $categoryData['total_weight_active'] ?? null,
                    'items' => []
                ];

                // Untuk setiap item di category, cari detail criteria yang sesuai
                foreach ($categoryData['items'] as $item) {
                    $criteriaId = $item['id'];

                    $detailCriteria = collect($matchingDetail->detailCriteria ?? [])
                        ->firstWhere('criteria_id', $criteriaId);

                    if (!$detailCriteria) {
                        continue;
                    }

                    // Format item dengan data dari detail
                    $formattedItem = $item;
                    $formattedItem['detail_criteria_id'] = $detailCriteria->id ?? null;
                    $formattedItem['obtained_score'] = $detailCriteria->obtained_score ?? 0;
                    $formattedItem['calculated_value'] = $detailCriteria->calculated_value ?? null;
                    $formattedItem['description'] = $detailCriteria->description ?? null;
                    // var_dump('tes', $formattedItem['detail_criteria_id'], $formattedItem['description']);

                    // Update rules dengan actual_value dan is_pass dari detail
                    if (isset($formattedItem['rules']) && $detailCriteria) {
                        foreach ($formattedItem['rules'] as &$rule) {
                            $matchingRule = collect($detailCriteria->detailRules ?? [])
                                ->firstWhere('rule_id', $rule['id']);
                            if ($matchingRule) {
                                // var_dump('masuk',$matchingRule->id);
                                $rule['actual_value'] = $matchingRule->actual_value;
                                $rule['is_pass'] = $matchingRule->is_pass;
                                $rule['detail_rule_id'] = $matchingRule->id;
                            } else {
                                // Jika tidak ada detail rule, set null
                                $rule['actual_value'] = null;
                                $rule['is_pass'] = null;
                                $rule['detail_rule_id'] = null;
                            }
                        }
                    }

                    // Karena formattedItem adalah object, akses sebagai object
                    if (isset($formattedItem->steps) && $detailCriteria) {
                        // Iterasi dengan key agar bisa dimodifikasi
                        foreach ($formattedItem->steps as $key => $step) {
                            $matchingStep = $detailCriteria->stepValues->firstWhere('step_code', $step['step_code']);

                            $stepValues = [];
                            if ($matchingStep && isset($matchingStep->inputValues)) {
                                $stepValues = $matchingStep->inputValues->map(function ($input) use ($step) {
                                    // Cari default value dari step (bukan dari matching input)
                                    $is_default = null;
                                    $timestamp = null;
                                    if (isset($step->stepValues)) {
                                        // Cari key step_values yang variable_code/value_code/value_name sama dengan input
                                        foreach ($step->stepValues as $stepVal) {
                                            // Cocokkan variable_code pada step_values dan input
                                            if (
                                                (isset($input->variable_code) && isset($stepVal['variable_code']) && $input->variable_code === $stepVal['variable_code'])
                                            ) {
                                                $is_default = $stepVal['is_default'] ?? null;
                                                $timestamp = $stepVal['timestamp'] ?? null;
                                                break;
                                            }
                                        }
                                    }

                                    return [
                                        'id' => $input->id ?? null,
                                        'id_step_values' => $input->id ?? null,
                                        'assessment_step_values_id' => $input->assessment_step_values_id ?? null,
                                        'variable_name' => $input->variable_name ?? null,
                                        'variable_code' => $input->variable_code ?? null,
                                        'order' => $input->order ?? null,
                                        'type_data' => $input->type_data ?? null,
                                        'value' => $input->value ?? null,
                                        'is_default' => $is_default,
                                        'timestamp' => $timestamp ?? null
                                    ];
                                })->toArray();
                            }

                            // Assign langsung ke collection dengan key
                            $formattedItem->steps[$key]['step_value_assessments'] = $stepValues;
                        }
                    }
                    $formattedCategory['items'][] = $formattedItem;
                }

                // Hanya tambahkan category jika memiliki items
                // var_dump($formattedCategory['items']);
                if (!empty($formattedCategory)) {
                    $formattedDetail['categories'][] = $formattedCategory;
                }
            }

            // Hanya tambahkan detail jika memiliki categories
            if (!empty($formattedDetail)) {
                $detailFormated[] = $formattedDetail;
            }
        }

        if ($transaction->status->code === 'WAITING') {
            # code...
            $detailFormated = $variables;
        }

        // Build response sesuai struktur request
        $response = [
            'status' => $transaction->status->code,
            'no_wo' => $transaction->information->no_wo,
            'customer_name' => $transaction->information->customer_name,
            'branch_name' => $transaction->information->branch_name,
            'pppoe' => $transaction->pppoe_secret,
            'katim' => $transaction->information->picInformations[0]->full_name,
            'team' => $transaction->information->woPsbInformation->team_name,
            'access_erp' => $transaction->information->technicalInformation->splitter,
            'start' => $transaction->information->start_work_order,
            'end' => $transaction->information->end_work_order,
            'duration' => $transaction->information->duration_minute,
            'distance_sales' => $transaction->information->woPsbInformation->sales_distance,
            'distance_team' => $transaction->information->woPsbInformation->technician_distance,
            'dropcore' => $transaction->information->woPsbInformation->dropcore,
            'notes' => $transaction->notes ?? '',
            'status_by' => $transaction->logsNewest->byUser->name ?? null,
            'status_at' => $transaction->logsNewest->created_at ?? null,
            'assessments' => $detailFormated,
            // 'details' => $detailFormated,
        ];

        return $response;
    }

    public function storeWo(Request $request, ?string $id = null)
    {
        $data = $request->all();
        $erpExtra = $request->getErpExtraData() ?? null;
        $custExtra = $request->getCustExtraData() ?? null;
        $techExtra = $request->getTechExtraData() ?? null;
        $psbExtra = $request->getPsbExtraData() ?? null;

        $statusAssessmentCode = $this->woInterface->checkStatusCode('WAITING');
        if (!$statusAssessmentCode) {
            throw new HttpException(400, 'Kode assessment tidak ditemukan atau tidak valid.');
        }

        $data['status_id'] = $statusAssessmentCode->id;

        // Cek apakah assessment_code valid melalui WoInterface::checkAssessmentCode
        if (isset($data['assessment_type'])) {
            $assessmentCode = $this->woInterface->checkAssessmentCode($data['assessment_type']);
            if (!$assessmentCode) {
                throw new HttpException(400, 'Kode assessment tidak ditemukan atau tidak valid.');
            }
            // set assessment_code_id jika kosong dari validasi kode
            $data['assessment_code_id'] = $assessmentCode->id;
        }

        // Cek dedup berdasarkan pppoe_secret sebelum melakukan proses selanjutnya
        $pppoeSecret = $data['customer']['pppoe_secret'] ?? null;
        if ($pppoeSecret) {
            $existingWo = $this->woInterface->findByPppoeSecret($pppoeSecret);
            if ($existingWo) {
                throw new HttpException(400, 'Work order dengan PPPoE Secret tersebut sudah terdaftar.');
            }
        }

        return DB::transaction(function () use ($data, $id, $erpExtra, $custExtra, $techExtra, $psbExtra) {
            // 1. Proses Assessment Transaction (Work Order utama)
            $transactionData = [
                'pppoe_secret' => $data['customer']['pppoe_secret'] ?? null,
                'assessment_code_id' => $data['assessment_code_id'] ?? null,
                'work_order_id' => $data['work_order']['wo_id'] ?? null,
                'current_pic_id' => $data['current_pic_id'] ?? null,
                // 'company_code' => $data['company_code'] ?? 'griya',
                // 'system_code' => $data['system_code'] ?? null,
                'started_at' => $data['work_order']['start_work_order'] ?? null,
                'completed_at' => $data['work_order']['end_work_order'] ?? null,
                'status_id' => $data['status_id'] ?? null,
                'result_id' => $data['result_id'] ?? null,
                'is_had_draft' => $data['is_had_draft'] ?? false,
            ];

            $transaction = $this->woInterface->storeWo($transactionData, $id);

            $logData = [
                'assessment_transaction_id' => $transaction[0]['id'] ?? $transaction->id ?? null,
                'from_status_id' => null,
                'to_status_id' => $transactionData['status_id'] ?? null,
                'changed_by' => 'APIKEY',
                'changed_at' => now(),
                'remark' => null,
            ];

            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);

            // Ambil ID transaction yang baru dibuat atau diupdate
            $transactionId = $transaction[0]['id'] ?? $transaction->id ?? null;

            // 2. Proses Assessment Information (Customer)
            $assessmentData = [
                'assessment_transaction_id' => $transactionId,
                'branch_id' => $data['customer']['branch_id'] ?? null,
                'branch_name' => $data['customer']['branch_name'] ?? null,
                'regional_name' => $data['customer']['regional_name'] ?? null,
                'pppoe_secret' => $data['customer']['pppoe_secret'] ?? null,
                'wo_id' => $data['work_order']['wo_id'] ?? null,
                'no_wo' => $data['work_order']['wo_id'] ?? null,
                'customer_name' => $data['customer']['customer_name'] ?? null,
                'assessment_code_id' => $data['assessment_code_id'] ?? null,
                'work_order_date' => $data['work_order']['work_order_date'] ?? null,
                'start_work_order' => $data['work_order']['start_work_order'] ?? null,
                'end_work_order' => $data['work_order']['end_work_order'] ?? null,
                'duration_minute' => $data['work_order']['duration_minute'] ?? null,
                'address' => $data['customer']['address'] ?? null,
                'cust_extra' => $data['cust_extra'] ?? $custExtra ?? null,
                'erp_extra' => $data['erp_extra'] ?? $erpExtra ?? null,
            ];

            $assessment = $this->woInterface->upsertAssessmentInformation($assessmentData, null);
            $assessmentId = $assessment[0]['id'] ?? $assessment->id ?? null;
            // 3. Proses Assessment Technical Information
            $technicalData = [
                'assessment_information_id' => $assessmentId,
                'area_id' => $data['technical_data']['area_id'] ?? null,
                'area_name' => $data['technical_data']['area_name'] ?? null,
                'area_code' => $data['technical_data']['area_code'] ?? null,
                'area_primary_id' => $data['technical_data']['area_primary_id'] ?? null,
                'area_primary_name' => $data['technical_data']['area_primary_name'] ?? null,
                'area_primary_code' => $data['technical_data']['area_primary_code'] ?? null,
                'splitter_id' => $data['technical_data']['splitter_id'] ?? null,
                'splitter' => $data['technical_data']['spliter'] ?? null,
                'customer_port' => $data['technical_data']['customer_port'] ?? null,
                'tech_extra' => $data['tech_extra'] ?? $techExtra ?? null,
            ];

            $technical = $this->woInterface->upsertAssessmentInformationTechnical($technicalData, null);
            $technicalId = $technical[0]['id'] ?? $technical->id ?? null;

            // 4. Proses Assessment WO PSB Information
            $woPsbData = [
                'assessment_information_id' => $assessmentId,
                'team_id' => $data['work_order']['detail']['team_id'] ?? null,
                'team_name' => $data['work_order']['detail']['team_name'] ?? null,
                'sales_distance' => $data['work_order']['detail']['sales_distance'] ?? null,
                'technician_distance' => $data['work_order']['detail']['technician_distance'] ?? null,
                'dropcore' => $data['work_order']['detail']['dropcore'] ?? null,
                'cable_status' => $data['work_order']['detail']['cable_status'] ?? null,
                'cable_history_id' => $data['work_order']['detail']['cable_history_id'] ?? null,
                'cable_detail_history_id' => $data['work_order']['detail']['cable_detail_history_id'] ?? null,
                'psb_extra' => $data['psb_extra'] ?? $psbExtra ?? null,
            ];

            $woPsb = $this->woInterface->upsertAssessmentWoPsbInformation($woPsbData, null);

            // 5. Proses Assessment PIC Information
            $pics = [];
            foreach ($data['pic'] as $index => $pic) {
                $picData = [
                    'assessment_information_id' => $assessmentId,
                    'role' => $pic['role'] ?? null,
                    'nip' => $pic['nip'] ?? null,
                    'nick_name' => $pic['nick_name'] ?? null,
                    'full_name' => $pic['full_name'] ?? null,
                    'is_primary' => $pic['is_primary'] ?? ($pic['order'] == 1 ? 1 : 0),

                ];
                $pics[] = $this->woInterface->upsertAssessmentPicInformation($picData, null);
            }

            // 6. Proses Assessment Step
            $steps = [];
            foreach ($data['step'] as $index => $stepData) {
                $stepPayload = [
                    'assessment_information_id' => $assessmentId,
                    'step_id' => $stepData['step_id'] ?? null,
                    'step_code' => $stepData['step_code'] ?? null,
                    'step_name' => $stepData['step_name'] ?? null,
                    'order_number' => $stepData['order_number'] ?? $index ?? 0,

                    // Jika hanya ada submitted_at
                    'created_at' => isset($stepData['submitted_at']) ? $stepData['submitted_at'] : null,
                    'updated_at' => isset($stepData['submitted_at']) ? $stepData['submitted_at'] : null,

                ];

                $step = $this->woInterface->upsertAssessmentStep($stepPayload, null);
                $stepId = $step[0]['id'] ?? $step->id ?? null;
                // 7. Proses Assessment Step Values
                if (isset($stepData['data_values']) && is_array($stepData['data_values'])) {
                    foreach ($stepData['data_values'] as $valueData) {
                        $stepValueData = [
                            'assessment_step_values_id' => $stepId,
                            'data_code' => $valueData['data_code'] ?? null,
                            'data_name' => $valueData['data_name'] ?? null,
                            'type_data' => $valueData['type_data'] ?? null,
                            'value' => $valueData['value'] ?? null,
                        ];
                        $this->woInterface->upsertAssessmentStepValue($stepValueData, null);
                    }
                }

                $steps[] = $step;
            }

            $this->publishReverb($transaction->id, 'WAITING');

            return [
                'success' => true,
                'message' => 'Work Order berhasil ' . ($id ? 'diupdate' : 'disimpan'),
                'data' => [
                    'transaction' => $transaction,
                    'assessment' => $assessment,
                    'technical' => $technical,
                    'wo_psb' => $woPsb,
                    'pics' => $pics,
                    'steps' => $steps,
                    'assessment_type' => $data['assessment_type'] ?? null
                ]
            ];
        });
    }

    private function publishReverb($id, $action)
    {
        $countWo = $this->validationWoInterface->countWoNotComplete();

        broadcast(new WorkOrderUpdated(
            $id,
            $action,
            $countWo
        ));
    }

    public function getWoNotComplete()
    {
        return $this->validationWoInterface->countWoNotComplete();
    }

    public function updateWo(array $data, ?string $id = null)
    {

        // Ambil status_id dari transaction yang telah di-eager load
        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        if (empty($transaction) || (is_array($transaction) && empty($transaction[0]))) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }

        // Dapatkan status_id dari hasil transaction
        if (is_array($transaction)) {
            $statusId = $transaction[0]['status_id'] ?? null;
        } elseif (is_object($transaction)) {
            $statusId = $transaction->status_id ?? null;
        } else {
            $statusId = null;
        }

        return DB::transaction(function () use ($data, $id, $statusId) {
            // 1. Update main transaction
            $assessmentTransactionData = [
                'id' => $id,
                'status_id' => $data['status'] ?? null,
                // 'notes' => $data['notes'] ?? null,
            ];

            // Konversi status code menjadi status id
            if (isset($data['status']) && is_string($data['status'])) {
                $statusObj = $this->woInterface->checkStatusCode($data['status']);
                if ($statusObj && isset($statusObj->id)) {
                    $assessmentTransactionData['status_id'] = $statusObj->id;
                } else {
                    throw new HttpException(400, 'Status code tidak ditemukan atau tidak valid.');
                }
            }

            $this->validationWoInterface->updateValidasiWo($assessmentTransactionData, $id);

            $logData = [
                'assessment_transaction_id' => $id,
                'from_status_id' => $statusId,
                'to_status_id' => $assessmentTransactionData['status_id'] ?? null,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
                'remark' => null,
            ];

            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);

            foreach ($data['assessments'] as $assessmentData) {
                // 1. Store assessment detail right away and get its ID
                $assessmentDetailPayload = [
                    'id' => $assessmentData['assessment']['detail_id'] ?? null,
                    'assessment_transaction_id' => $id,
                    'assessment_id' => $assessmentData['assessment']['id'] ?? null,
                    'assessment_name' => $assessmentData['assessment']['name'] ?? null,
                    'assessment_code_id' => $assessmentData['assessment']['code'] ?? null,
                    'total_score' => $assessmentData['assessment']['total_score'] ?? 0
                ];
                $assessmentDetailModel = $this->validationWoInterface->upsertAssessmentWo($assessmentDetailPayload, $assessmentDetailPayload['id']);
                $assessmentDetailId = is_array($assessmentDetailModel) && isset($assessmentDetailModel[0]['id'])
                    ? $assessmentDetailModel[0]['id']
                    : ($assessmentDetailModel->id ?? $assessmentDetailPayload['id']);

                // 2. Category loop
                foreach ($assessmentData['categories'] as $categoryData) {
                    $criteriaPayload = [
                        'id' => $categoryData['category']['detail_criteria_id'] ?? null,
                        'assessment_detail_id' => $assessmentDetailId,
                        'criteria_id' => $categoryData['category']['criteria_id'] ?? null,
                        'criteria_name' => $categoryData['category']['criteria_name'] ?? null,
                        'category_id' => $categoryData['category']['category_id'] ?? null,
                        'category_name' => $categoryData['category']['category_name'] ?? null,
                        'criteria_type_id' => $categoryData['category']['criteria_type_id'] ?? null,
                        'criteria_type_name' => $categoryData['category']['criteria_type_name'] ?? null,
                        'operation_id' => $categoryData['category']['operation_id'] ?? null,
                        'score_type_id' => $categoryData['category']['score_type_id'] ?? null,
                        'max_score' => $categoryData['category']['max_score'] ?? 0,
                        'obtained_score' => $categoryData['category']['obtained_score'] ?? 0,
                        'calculated_value' => $categoryData['category']['calculate_value'] ?? null,
                        'description' => $categoryData['category']['description'] ?? null
                    ];
                    $criteriaModel = $this->validationWoInterface->upsertAssessmentDetailCriteriaWo($criteriaPayload, $criteriaPayload['id']);
                    $criteriaTransactionId = is_array($criteriaModel) && isset($criteriaModel[0]['id'])
                        ? $criteriaModel[0]['id']
                        : ($criteriaModel->id ?? $criteriaPayload['id']);

                    // 4. Rules
                    foreach ($categoryData['item']['rules'] as $ruleData) {
                        $rulePayload = [
                            'id' => $ruleData['detail_rule_id'],
                            'criteria_transaction_id' => $criteriaTransactionId,
                            'rule_id' => $ruleData['id'],
                            'rule_name' => $ruleData['rule'],
                            'expected_value' => false,
                            'actual_value' => $ruleData['is_checked'],
                            'is_pass' => (bool) $ruleData['is_checked']
                        ];
                        $this->validationWoInterface->upsertAssessmentCriteriaRuleWo($rulePayload, $rulePayload['id']);
                    }

                    // 5. Steps (jika ada)
                    if (isset($categoryData['item']['criterias']) && is_array($categoryData['item']['criterias'])) {
                        foreach ($categoryData['item']['criterias'] as $stepData) {
                            $stepPayload = [
                                'id' => $stepData['id_step'] ?? null,
                                'criteria_transaction_id' => $criteriaTransactionId,
                                'order' => $stepData['order'] ?? 0,
                                'step_code' => $stepData['step_code'] ?? null,
                                'variable_code' => $stepData['variable_key'] ?? null,
                                'variable_name' => $stepData['variable_name'] ?? null,
                            ];
                            $stepModel = $this->validationWoInterface->upsertAssessmentCriteriaStepWo($stepPayload, $stepPayload['id']);
                            $stepId = is_array($stepModel) && isset($stepModel[0]['id'])
                                ? $stepModel[0]['id']
                                : ($stepModel->id ?? $stepPayload['id']);

                            $stepValuePayload = [
                                'id' => $stepData['id_step_input'] ?? null,
                                'assessment_step_values_id' => $stepData['id_step'] ?? $stepId ?? null,
                                'variable_name' => $stepData['variable_name'] ?? null,
                                'variable_code' => $stepData['variable_key'] ?? null,
                                'order' => $stepData['order'] ?? 0,
                                'type_data' => $stepData['type_data'] ?? null,
                                'value' => $stepData['value'] ?? null,
                            ];
                            $this->validationWoInterface->upsertAssessmentCriteriaStepValueWo($stepValuePayload, $stepValuePayload['id']);
                        }
                    }
                }
            }

            // 4. Return the updated Work Order
            return $this->validationWoInterface->showWoWithRelations($id);
        });
    }

    public function updateStatusProgress(string $id, string $statusCode)
    {
        // Ambil status_id dari transaction yang telah di-eager load
        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        if (empty($transaction) || (is_array($transaction) && empty($transaction[0]))) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }

        // Dapatkan status_id dari hasil transaction
        if (is_array($transaction)) {
            $statusId = $transaction[0]['status_id'] ?? null;
        } elseif (is_object($transaction)) {
            $statusId = $transaction->status_id ?? null;
        } else {
            $statusId = null;
        }

        $statusObj = $this->woInterface->checkStatusCode($statusCode);
        if (!$statusObj || !isset($statusObj->id)) {
            throw new HttpException(400, 'Status code tidak ditemukan atau tidak valid.');
        }

        return DB::transaction(function () use ($id, $statusId, $statusObj) {
            $logData = [
                'assessment_transaction_id' => $id,
                'from_status_id' => $statusId,
                'to_status_id' => $statusObj->id ?? null,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
                'remark' => null,
            ];

            $this->validationWoInterface->updateValidasiWo([
                'status_id' => $logData['to_status_id'] ?? null,
            ], $id);

            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);
        });
    }
}
