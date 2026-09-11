<?php

namespace App\Services\WorkOrder;

use App\Events\WorkOrderUpdated;
use App\Interface\WorkOrder\ValidationWoInterface;
use App\Interface\WorkOrder\WoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $this->revertInProgressStatusesIfExpired();
        return $this->validationWoInterface->index($data);
    }

    public function showStatuses()
    {
        return $this->validationWoInterface->showStatuses();
    }

    /* =========================================================
     *  SHOW WO + FORMAT RESPONSE (logika bisnis di service)
     * ========================================================= */

    public function showWo(string $id)
    {

        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        $this->revertInProgressStatusesIfExpired();

        if (!$transaction) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }

        $assessments = $this->buildMasterVariables($transaction->information->id);
        $details = $transaction->details;

        // ✅ Build lookup maps sekali (O(n))
        $detailsByAssessment = $details->keyBy('assessment_id');
        $criteriaMapByDetail = [];
        $rulesMapByCriteria = [];
        $stepsMapByCriteria = [];

        foreach ($details as $detail) {
            $criteriaMapByDetail[$detail->id] = collect($detail->detailCriteria ?? [])->keyBy('criteria_id');

            foreach ($detail->detailCriteria as $criteria) {
                $rulesMapByCriteria[$criteria->id] = collect($criteria->detailRules ?? [])->keyBy('rule_id');
                $stepsMapByCriteria[$criteria->id] = collect($criteria->stepValues ?? [])->keyBy('step_code');
            }
        }

        $detailFormatted = [];

        if ($details->isEmpty()) {
            // Collection kosong
            $detailFormatted = $assessments;
        } else {
            foreach ($assessments as $assessment) {
                $assessmentId = $assessment['assessment']['id'];
                $matchingDetail = $detailsByAssessment->get($assessmentId);

                if (!$matchingDetail) {
                    continue;
                }

                $criteriaMap = $criteriaMapByDetail[$matchingDetail->id] ?? collect();

                $formattedDetail = [
                    'assessment' => $assessment['assessment'],
                    'detail_id' => $matchingDetail->id,
                    'total_score' => $matchingDetail->total_score ?? 0,
                    'categories' => [],
                ];

                foreach ($assessment['categories'] as $categoryData) {
                    $formattedCategory = [
                        'category' => $categoryData['category'],
                        'total_weight_active' => $categoryData['total_weight_active'] ?? null,
                        'items' => [],
                    ];

                    foreach ($categoryData['items'] as $item) {
                        $criteriaId = $item['id'];
                        $detailCriteria = $criteriaMap->get($criteriaId);

                        if (!$detailCriteria) {
                            continue;
                        }

                        $formattedItem = $item;
                        $formattedItem['detail_criteria_id'] = $detailCriteria->id ?? null;
                        $formattedItem['obtained_score'] = $detailCriteria->obtained_score ?? 0;
                        $formattedItem['calculated_value'] = $detailCriteria->calculated_value ?? null;
                        $formattedItem['description'] = $detailCriteria->description ?? null;

                        // Rules
                        if (isset($formattedItem['rules'])) {
                            $ruleMap = $rulesMapByCriteria[$detailCriteria->id] ?? collect();
                            foreach ($formattedItem['rules'] as &$rule) {
                                $matchingRule = $ruleMap->get($rule['id']);
                                $rule['actual_value'] = $matchingRule->actual_value ?? null;
                                $rule['is_pass'] = $matchingRule->is_pass ?? null;
                                $rule['detail_rule_id'] = $matchingRule->id ?? null;
                            }
                            unset($rule);
                        }

                        // Steps
                        if (isset($formattedItem->steps)) {
                            $stepMap = $stepsMapByCriteria[$detailCriteria->id] ?? collect();

                            foreach ($formattedItem->steps as $key => $step) {
                                $matchingStep = $stepMap->get($step['step_code']);

                                $stepValues = [];
                                if ($matchingStep && isset($matchingStep->inputValues)) {
                                    $stepDefaultMap = [];
                                    if (isset($step->stepValues)) {
                                        foreach ($step->stepValues as $stepVal) {
                                            if (isset($stepVal['variable_code'])) {
                                                $stepDefaultMap[$stepVal['variable_code']] = [
                                                    'is_default' => $stepVal['is_default'] ?? null,
                                                    'timestamp' => $stepVal['timestamp'] ?? null,
                                                ];
                                            }
                                        }
                                    }

                                    $stepValues = $matchingStep->inputValues->map(function ($input) use ($stepDefaultMap) {
                                        $defaultInfo = $stepDefaultMap[$input->variable_code] ?? [];
                                        return [
                                            'id' => $input->id ?? null,
                                            'id_step_values' => $input->id ?? null,
                                            'assessment_step_values_id' => $input->assessment_step_values_id ?? null,
                                            'variable_name' => $input->variable_name ?? null,
                                            'variable_code' => $input->variable_code ?? null,
                                            'order' => $input->order ?? null,
                                            'type_data' => $input->type_data ?? null,
                                            'value' => $input->value ?? null,
                                            'is_default' => $defaultInfo['is_default'] ?? null,
                                            'timestamp' => $defaultInfo['timestamp'] ?? null,
                                        ];
                                    })->toArray();
                                }

                                $formattedItem->steps[$key]['step_value_assessments'] = $stepValues;
                            }
                        }

                        $formattedCategory['items'][] = $formattedItem;
                    }

                    if (!empty($formattedCategory)) {
                        $formattedDetail['categories'][] = $formattedCategory;
                    }
                }

                if (!empty($formattedDetail)) {
                    $detailFormatted[] = $formattedDetail;
                }
            }
        }

        return [
            'status' => $transaction->status->code,
            'no_wo' => $transaction->information->no_wo,
            'customer_name' => $transaction->information->customer_name,
            'branch_name' => $transaction->information->branch_name,
            'pppoe' => $transaction->pppoe_secret,
            'katim' => $transaction->information->picInformations[0]->full_name ?? null,
            'team' => $transaction->information->woPsbInformation->team_name ?? null,
            'access_erp' => $transaction->information->technicalInformation->splitter ?? null,
            'start' => $transaction->information->start_work_order,
            'end' => $transaction->information->end_work_order,
            'duration' => $transaction->information->duration_minute,
            'distance_sales' => $transaction->information->woPsbInformation->sales_distance ?? null,
            'distance_team' => $transaction->information->woPsbInformation->technician_distance ?? null,
            'dropcore' => $transaction->information->woPsbInformation->dropcore ?? null,
            'notes' => $transaction->notes ?? '',
            'status_by' => $transaction->logsNewest->byUser->name ?? null,
            'status_at' => $transaction->logsNewest->created_at ?? null,
            'newest' => $details->isEmpty(),
            'assessments' => $detailFormatted,
        ];
    }

    /**
     * ✅ Semua logika build master variables ada di service.
     */
    private function buildMasterVariables(string $assessmentInformationId): \Illuminate\Support\Collection
    {
        $variable = collect($this->validationWoInterface->getMasterVariablesJson());

        $stepValues = $this->validationWoInterface->getStepValuesKeyedByStepId($assessmentInformationId);
        $stepValueIds = $stepValues->pluck('id')->all();
        $dataValueMap = $this->validationWoInterface->getStepDataValuesMap($stepValueIds);

        $criteriaByAssessment = $this->validationWoInterface->getCriteriaGroupedByAssessment();
        $activeAssessments = $this->validationWoInterface->getActiveAssessmentsWithCriteria();

        return $activeAssessments
            ->map(function ($assessment) use ($criteriaByAssessment, $variable, $stepValues, $dataValueMap) {
                $assessmentItems = $criteriaByAssessment->get($assessment->id, collect());

                $categories = $assessmentItems
                    ->groupBy('assessment_category_id')
                    ->map(function ($categoryItems) use ($variable, $stepValues, $dataValueMap) {
                        $category = $categoryItems->first()->category;

                        $items = $categoryItems->values()->map(function ($item) use ($variable, $stepValues, $dataValueMap) {
                            if ($item->relationLoaded('steps')) {
                                $item->steps->each(function ($step) use ($variable, $stepValues, $dataValueMap) {
                                    $matchingVariable = $variable->first(
                                        fn($v) => (int) ($v->id) === (int) ($step->step_id)
                                    );

                                    $stepValueModel = $stepValues->get($step->step_id);
                                    $stepValueId = $stepValueModel->id ?? null;

                                    $stepDefaultValues = [];
                                    $defaultComponent = $matchingVariable->default_component ?? [];

                                    foreach ($defaultComponent as $componentItem) {
                                        $itemWithValue = (array) $componentItem;
                                        $code = $itemWithValue['key'] ?? null;

                                        if ($stepValueId) {
                                            $itemWithValue['value'] = $dataValueMap[$stepValueId][$code] ?? null;
                                            $itemWithValue['timestamp'] = $dataValueMap[$stepValueId]['timestamp']
                                                ?? $stepValueModel->updated_at;
                                        } else {
                                            $itemWithValue['value'] = null;
                                        }

                                        $stepDefaultValues[] = (object) $itemWithValue;
                                    }
                                    $step->step_default_values = $stepDefaultValues;

                                    if ($step->relationLoaded('stepValues')) {
                                        foreach ($step->stepValues as $stepValue) {
                                            $isDefault = false;
                                            foreach ($step->step_default_values as $defaultValue) {
                                                if (
                                                    isset($defaultValue->key, $stepValue->variable_code) &&
                                                    $stepValue->variable_code === $defaultValue->key
                                                ) {
                                                    $isDefault = true;
                                                    break;
                                                }
                                            }

                                            if ($stepValueId) {
                                                $code = $stepValue->variable_code ?? null;
                                                $stepValue->value = $dataValueMap[$stepValueId][$code] ?? null;
                                                $stepValue->timestamp = $dataValueMap[$stepValueId]['timestamp']
                                                    ?? $stepValueModel->updated_at;
                                            } else {
                                                $stepValue->value = null;
                                            }

                                            $stepValue->is_default = $isDefault;
                                        }
                                    }
                                });
                            }

                            $item['operation_name'] = $item->operation->name ?? null;
                            $item['operation_code'] = $item->operation->code ?? null;
                            $item['operation_symbol'] = $item->operation->symbol ?? null;

                            $item['score_name'] = $item->scoreType->name ?? null;
                            $item['score_code'] = $item->scoreType->code ?? null;
                            $item['score_symbol'] = $item->scoreType->symbol ?? null;

                            $item['criteria_type_name'] = $item->criteriaType->name ?? null;
                            $item['criteria_type_code'] = $item->criteriaType->code ?? null;

                            return $item;
                        });

                        return [
                            'category' => $category,
                            'total_weight_active' => (float) $categoryItems->where('is_active', true)->sum('score'),
                            'items' => $items,
                        ];
                    })
                    ->values();

                return [
                    'assessment' => $assessment,
                    'categories' => $categories,
                ];
            });
    }

    /* =========================================================
     *  STORE WO (logika bisnis tetap di service)
     * ========================================================= */

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

        if (isset($data['assessment_type'])) {
            $assessmentCode = $this->woInterface->checkAssessmentCode($data['assessment_type']);
            if (!$assessmentCode) {
                throw new HttpException(400, 'Kode assessment tidak ditemukan atau tidak valid.');
            }
            $data['assessment_code_id'] = $assessmentCode->id;
        }

        $pppoeSecret = $data['customer']['pppoe_secret'] ?? null;
        if ($pppoeSecret) {
            $existingWo = $this->woInterface->findByPppoeSecret($pppoeSecret);
            if ($existingWo) {
                throw new HttpException(400, 'Work order dengan PPPoE Secret tersebut sudah terdaftar.');
            }
        }

        return DB::transaction(function () use ($data, $id, $erpExtra, $custExtra, $techExtra, $psbExtra) {
            $transactionData = [
                'pppoe_secret' => $data['customer']['pppoe_secret'] ?? null,
                'assessment_code_id' => $data['assessment_code_id'] ?? null,
                'work_order_id' => $data['work_order']['wo_id'] ?? null,
                'current_pic_id' => $data['current_pic_id'] ?? null,
                'started_at' => $data['work_order']['start_work_order'] ?? null,
                'completed_at' => $data['work_order']['end_work_order'] ?? null,
                'status_id' => $data['status_id'] ?? null,
                'result_id' => $data['result_id'] ?? null,
                'is_had_draft' => $data['is_had_draft'] ?? false,
            ];

            $transaction = $this->woInterface->storeWo($transactionData, $id);
            $transactionId = $transaction[0]['id'] ?? $transaction->id ?? null;

            $logData = [
                'assessment_transaction_id' => $transactionId,
                'from_status_id' => null,
                'to_status_id' => $transactionData['status_id'] ?? null,
                'changed_by' => 'APIKEY',
                'changed_at' => now(),
                'remark' => null,
            ];
            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);

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

            $steps = [];
            foreach ($data['step'] as $index => $stepData) {
                $stepPayload = [
                    'assessment_information_id' => $assessmentId,
                    'step_id' => $stepData['step_id'] ?? null,
                    'step_code' => $stepData['step_code'] ?? null,
                    'step_name' => $stepData['step_name'] ?? null,
                    'order_number' => $stepData['order_number'] ?? $index ?? 0,
                    'created_at' => $stepData['submitted_at'] ?? null,
                    'updated_at' => $stepData['submitted_at'] ?? null,
                ];

                $step = $this->woInterface->upsertAssessmentStep($stepPayload, null);
                $stepId = $step[0]['id'] ?? $step->id ?? null;

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

            $this->publishReverb($transactionId, 'WAITING');

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
                    'assessment_type' => $data['assessment_type'] ?? null,
                ],
            ];
        });
    }

    private function publishReverb($id, $action)
    {
        // ✅ Invalidate cache count supaya fresh
        $this->validationWoInterface->forgetWoNotCompleteCache();

        $countWo = $this->validationWoInterface->countWoNotComplete();

        broadcast(new WorkOrderUpdated($id, $action, $countWo));
    }

    public function getWoNotComplete()
    {
        return $this->validationWoInterface->countWoNotComplete();
    }

    /* =========================================================
     *  UPDATE WO
     *  ✅ Semua logika bisnis di service:
     *     - Generate UUID untuk row baru
     *     - Generate step ID dulu sebelum step values
     *     - Pisahkan insert & update batch
     *     - Hitung score & tentukan result
     * ========================================================= */

    public function updateWo(array $data, ?string $id = null)
    {
        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        if (empty($transaction) || (is_array($transaction) && empty($transaction[0]))) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }

        $statusId = is_object($transaction)
            ? ($transaction->status_id ?? null)
            : ($transaction[0]['status_id'] ?? null);

        $statusResult = $this->validationWoInterface->getResultStatuses();
        $categoryCount = $this->validationWoInterface->getTotalCategoryAssessmentGrouped() ?? 0;

        return DB::transaction(function () use ($data, $id, $statusId, $categoryCount, $statusResult) {
            $sumScore = 0;

            $assessmentTransactionData = [
                'id' => $id,
                'status_id' => null,
                'result_id' => null,
            ];

            if (isset($data['status']) && is_string($data['status'])) {
                $statusObj = $this->woInterface->checkStatusCode($data['status']);
                if ($statusObj && isset($statusObj->id)) {
                    $assessmentTransactionData['status_id'] = $statusObj->id;
                } else {
                    throw new HttpException(400, 'Status code tidak ditemukan atau tidak valid.');
                }
            }

            $logData = [
                'assessment_transaction_id' => $id,
                'from_status_id' => $statusId,
                'to_status_id' => $assessmentTransactionData['status_id'] ?? null,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
                'remark' => null,
            ];
            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);

            // ✅ Kumpulkan payload di service
            $rulesToInsert = [];
            $rulesToUpdate = [];
            $stepsToInsert = [];
            $stepsToUpdate = [];
            $stepValuesToInsert = [];
            $stepValuesToUpdate = [];

            foreach ($data['assessments'] as $assessmentData) {
                $assessmentDetailPayload = [
                    'id' => $assessmentData['assessment']['detail_id'] ?? null,
                    'assessment_transaction_id' => $id,
                    'assessment_id' => $assessmentData['assessment']['id'] ?? null,
                    'assessment_name' => $assessmentData['assessment']['name'] ?? null,
                    'assessment_code_id' => $assessmentData['assessment']['code'] ?? null,
                    'total_score' => $assessmentData['assessment']['total_score'] ?? 0,
                ];
                $sumScore += $assessmentDetailPayload['total_score'];

                $assessmentDetailModel = $this->validationWoInterface
                    ->upsertAssessmentWo($assessmentDetailPayload, $assessmentDetailPayload['id']);

                $assessmentDetailId = is_array($assessmentDetailModel) && isset($assessmentDetailModel[0]['id'])
                    ? $assessmentDetailModel[0]['id']
                    : ($assessmentDetailModel->id ?? $assessmentDetailPayload['id']);

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
                        'description' => $categoryData['category']['description'] ?? null,
                    ];

                    $criteriaModel = $this->validationWoInterface
                        ->upsertAssessmentDetailCriteriaWo($criteriaPayload, $criteriaPayload['id']);

                    $criteriaTransactionId = is_array($criteriaModel) && isset($criteriaModel[0]['id'])
                        ? $criteriaModel[0]['id']
                        : ($criteriaModel->id ?? $criteriaPayload['id']);

                    // ✅ RULES - pisahkan insert vs update
                    if (isset($categoryData['item']['rules']) && is_array($categoryData['item']['rules'])) {
                        foreach ($categoryData['item']['rules'] as $ruleData) {
                            $row = [
                                'id' => !empty($ruleData['detail_rule_id'])
                                    ? $ruleData['detail_rule_id']
                                    : (string) Str::orderedUuid(),
                                'criteria_transaction_id' => $criteriaTransactionId,
                                'rule_id' => $ruleData['id'],
                                'rule_name' => $ruleData['rule'],
                                'expected_value' => false,
                                'actual_value' => $ruleData['is_checked'],
                                'is_pass' => (bool) $ruleData['is_checked'],
                            ];

                            if (!empty($ruleData['detail_rule_id'])) {
                                $row['updated_at'] = now();
                                $rulesToUpdate[] = $row;
                            } else {
                                $row['created_at'] = now();
                                $row['updated_at'] = now();
                                $rulesToInsert[] = $row;
                            }
                        }
                    }

                    // ✅ STEPS + STEP VALUES
                    if (isset($categoryData['item']['criterias']) && is_array($categoryData['item']['criterias'])) {
                        foreach ($categoryData['item']['criterias'] as $stepData) {
                            $isNewStep = empty($stepData['id_step']);
                            $stepId = $isNewStep
                                ? (string) Str::orderedUuid()
                                : $stepData['id_step'];

                            $stepRow = [
                                'id' => $stepId,
                                'criteria_transaction_id' => $criteriaTransactionId,
                                'order' => $stepData['order'] ?? 0,
                                'step_code' => $stepData['step_code'] ?? null,
                                'variable_code' => $stepData['variable_key'] ?? null,
                                'variable_name' => $stepData['variable_name'] ?? null,
                            ];

                            if ($isNewStep) {
                                $stepRow['created_at'] = now();
                                $stepRow['updated_at'] = now();
                                $stepsToInsert[] = $stepRow;
                            } else {
                                $stepRow['updated_at'] = now();
                                $stepsToUpdate[] = $stepRow;
                            }

                            $isNewStepValue = empty($stepData['id_step_input']);
                            $stepValueRow = [
                                'id' => $isNewStepValue
                                    ? (string) Str::orderedUuid()
                                    : $stepData['id_step_input'],
                                'assessment_step_values_id' => $stepId, // FK ke step yang baru dibuat
                                'variable_name' => $stepData['variable_name'] ?? null,
                                'variable_code' => $stepData['variable_key'] ?? null,
                                'order' => $stepData['order'] ?? 0,
                                'type_data' => $stepData['type_data'] ?? null,
                                'value' => $stepData['value'] ?? null,
                            ];

                            if ($isNewStepValue) {
                                $stepValueRow['created_at'] = now();
                                $stepValueRow['updated_at'] = now();
                                $stepValuesToInsert[] = $stepValueRow;
                            } else {
                                $stepValueRow['updated_at'] = now();
                                $stepValuesToUpdate[] = $stepValueRow;
                            }
                        }
                    }
                }
            }

            // ✅ Batch insert (row baru dengan UUID yang sudah di-generate di service)
            $this->validationWoInterface->insertBatchAssessmentDetailRules($rulesToInsert);
            $this->validationWoInterface->insertBatchAssessmentSteps($stepsToInsert);
            $this->validationWoInterface->insertBatchStepInputValues($stepValuesToInsert);

            // ✅ Batch update (row existing)
            $this->validationWoInterface->updateBatchAssessmentDetailRules($rulesToUpdate);
            $this->validationWoInterface->updateBatchAssessmentSteps($stepsToUpdate);
            $this->validationWoInterface->updateBatchStepInputValues($stepValuesToUpdate);

            // ✅ Hitung skor & tentukan result (logika bisnis di service)
            $resultScore = ($sumScore != 0 && $categoryCount != 0) ? $sumScore / $categoryCount : 0;
            $resultCode = $resultScore >= 100 ? 'NORMAL' : 'ABNORMAL';
            $statusResultItem = collect($statusResult)->firstWhere('code', $resultCode);

            if ($statusResultItem) {
                $assessmentTransactionData['result_id'] = $statusResultItem->id;
            }

            $this->validationWoInterface->updateValidasiWo($assessmentTransactionData, $id);
            $this->publishReverb($id, $data['status']);

            return $this->validationWoInterface->showWoWithRelations($id);
        });
    }

    public function updateStatusProgress(string $id, string $statusCode)
    {
        $transaction = $this->validationWoInterface->showWoWithRelations($id);

        if (empty($transaction) || (is_array($transaction) && empty($transaction[0]))) {
            throw new HttpException(400, 'Work Order tidak ditemukan atau tidak valid.');
        }

        $statusId = is_object($transaction)
            ? ($transaction->status_id ?? null)
            : ($transaction[0]['status_id'] ?? null);

        $statusObj = $this->woInterface->checkStatusCode($statusCode);
        if (!$statusObj || !isset($statusObj->id)) {
            throw new HttpException(400, 'Status code tidak ditemukan atau tidak valid.');
        }

        return DB::transaction(function () use ($id, $statusId, $statusObj, $statusCode) {
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

            $this->publishReverb($id, $statusCode);

            $this->validationWoInterface->upsertLogAssessmentTransactional($logData, null);
        });
    }

    /**
     * Revert status IN_PROGRESS yang sudah melebihi 15 menit (expired).
     *
     * @return int Jumlah assessment yang direvert
     */
    private function revertInProgressStatusesIfExpired(): int
    {
        // Waktu acuan eksplisit WIB, konsisten dengan query
        $nowWib = now('Asia/Jakarta');

        // 1 query ambil semua log expired (threshold & timezone sudah di-handle di sana)
        $expiredLogs = $this->validationWoInterface->getExpiredInProgressLogs();

        if ($expiredLogs->isEmpty()) {
            return 0;
        }

        // Siapkan data untuk batch update & batch insert
        $statusMapping = [];
        $logsToInsert  = [];

        foreach ($expiredLogs as $log) {
            $assessmentId = $log->assessment_transaction_id;

            $logCreatedAtWib = \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta');
            $diffMinutes = $logCreatedAtWib->diffInMinutes($nowWib, false);

            // echo "log->created_at (WIB): {$logCreatedAtWib}, nowWib: {$nowWib}, selisih menit: {$diffMinutes}\n";

            if ($diffMinutes >= 10) {
                // Mapping untuk update tr_assessments
                $statusMapping[$assessmentId] = $log->from_status_id;

                // Siapkan log revert
                $logsToInsert[] = [
                    'id'                        => Str::uuid()->toString(),
                    'assessment_transaction_id' => $assessmentId,
                    'from_status_id'            => $log->to_status_id,
                    'to_status_id'              => $log->from_status_id,
                    'changed_by'                => $log->changed_by,
                    'changed_at'                => $nowWib,
                    'remark'                    => 'Status direvert otomatis karena IN_PROGRESS lebih dari 15 menit',
                    'created_at'                => $nowWib,
                    'updated_at'                => $nowWib,
                ];
                // Your logic here (if needed)
            }
        }

        // 2. Jalankan dalam transaction
        DB::transaction(function () use ($statusMapping, $logsToInsert) {
            $this->validationWoInterface->batchUpdateAssessmentStatuses($statusMapping);
            $this->validationWoInterface->batchInsertLogs($logsToInsert);
        });

        return count($statusMapping);
    }
}
