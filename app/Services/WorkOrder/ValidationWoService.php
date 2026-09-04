<?php

namespace App\Services\WorkOrder;

use App\Interface\WorkOrder\ValidationWoInterface;
use App\Interface\WorkOrder\WoInterface;
use Illuminate\Support\Facades\DB;

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
            return null;
        }
        $variables = $this->validationWoInterface->showMasterVariables($transaction->information->id);

        // Build response sesuai struktur request
        $response = [
            'details' => $transaction->details,
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
            'assessments' => $variables
        ];

        return $response;
    }

    public function storeWo(array $data, ?string $id = null)
    {
        $statusAssessmentCode = $this->woInterface->checkStatusCode('WAITING');
        if (!$statusAssessmentCode) {
            throw new \Exception('Kode assessment tidak ditemukan atau tidak valid.');
        }

        $data['status_id'] = $statusAssessmentCode->id;

        // Cek apakah assessment_code valid melalui WoInterface::checkAssessmentCode
        if (isset($data['assessment_type'])) {
            $assessmentCode = $this->woInterface->checkAssessmentCode($data['assessment_type']);
            if (!$assessmentCode) {
                throw new \Exception('Kode assessment tidak ditemukan atau tidak valid.');
            }
            // set assessment_code_id jika kosong dari validasi kode
            $data['assessment_code_id'] = $assessmentCode->id;
        }

        return DB::transaction(function () use ($data, $id) {
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
                'cust_extra' => $data['cust_extra'] ?? null,
                'erp_extra' => $data['erp_extra'] ?? null,
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
                'tech_extra' => $data['tech_extra'] ?? null,
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
                'psb_extra' => $data['psb_extra'] ?? null,
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
                    'is_primary' => $pic['is_primary'] ?? ($index === 0 ? 1 : 0),
                ];
                $pics[] = $this->woInterface->upsertAssessmentPicInformation($picData, null);
            }

            // 6. Proses Assessment Step
            $steps = [];
            foreach ($data['step'] as $stepData) {
                $stepPayload = [
                    'assessment_information_id' => $assessmentId,
                    'step_id' => $stepData['step_id'] ?? null,
                    'step_code' => $stepData['step_code'] ?? null,
                    'step_name' => $stepData['step_name'] ?? null,
                    'order_number' => $stepData['order_number'] ?? 0,
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

    public function updateWo(array $data, ?string $id = null)
    {
        return DB::transaction(function () use ($data, $id) {
            // 1. Update main transaction
            $assessmentTransactionData = [
                'id' => $id,
                // 'status_id' => $data['status'] ?? null,
                // 'notes' => $data['notes'] ?? null,
            ];

            // Konversi status code menjadi status id
            if (isset($data['status']) && is_string($data['status'])) {
                $statusObj = $this->woInterface->checkStatusCode($data['status']);
                if ($statusObj && isset($statusObj->id)) {
                    $assessmentTransactionData['status_id'] = $statusObj->id;
                } else {
                    throw new \Exception('Status code tidak ditemukan atau tidak valid.');
                }
            }

            $this->validationWoInterface->storeWo([$assessmentTransactionData], $id);

            foreach ($data['assessments'] as $assessmentData) {
                // 1. Store assessment detail right away and get its ID
                $assessmentDetailPayload = [
                    'id' => $assessmentData['assessment']['id'] ?? null,
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
                        'id' => $categoryData['category']['id'] ?? null,
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

                    // 3. Items
                    foreach ($categoryData['items'] as $itemData) {
                        // 4. Rules
                        foreach ($itemData['value']['rules'] as $ruleData) {
                            $rulePayload = [
                                'id' => $ruleData['id'],
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
                        if (isset($itemData['value']['criterias']) && is_array($itemData['value']['criterias'])) {
                            foreach ($itemData['value']['criterias'] as $stepData) {
                                $stepPayload = [
                                    'id' => $stepData['id'] ?? null,
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
                                    'id' => $stepId,
                                    'assessment_step_values_id' => $stepId,
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
            }

            // 4. Return the updated Work Order
            return $this->validationWoInterface->showWoWithRelations($id);
        });
    }
}
