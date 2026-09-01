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

        // Build response sesuai struktur request
        $response = [
            'status' => $transaction->status->code,
            'no_wo' => $transaction->information->no_wo,
            'customer_name' => $transaction->information->customer_name,
            'branch_name' => $transaction->information->branch_name,
            'pppoe' => $transaction->pppoe_secret,
            'katim' => $transaction->status_id,
            'team' => $transaction->status_id,
            'access_erp' => $transaction->status_id,
            'start' => $transaction->information->start_work_order,
            'end' => $transaction->information->end_work_order,
            'duration' => $transaction->information->duration_minute,
            'distance_sales' => $transaction->information->woPsbInformation->sales_distance,
            'distance_team' => $transaction->information->woPsbInformation->technician_distance,
            'dropcore' => $transaction->information->woPsbInformation->dropcore,
            'notes' => $transaction->notes ?? '',
            'conclusion' => [
                'score' => 100,
                'score_name' => 'Normal',
                'score_installation_odp_quality' => 100,
                'score_installation_odp_good' => 100,
                'score_installation_customer_quality' => 100,
                'score_installation_customer_good' => 100,
                'score_installation_cable_quality' => 100,
                'score_installation_cable_good' => 100,
            ],
            'assessments' => []
        ];

        foreach ($transaction->details as $assessment) {
            $assessmentData = [
                'assessment' => [
                    'id' => $assessment->assessment_id,
                    'name' => $assessment->assessment_name
                ],
                'categories' => []
            ];

            foreach ($assessment->detailCriteria as $criteria) {
                // Build items dari criteria
                $item = [
                    'id' => $criteria->criteria_id,
                    'code' => null, // Tidak tersedia di tabel criteria
                    'name' => $criteria->criteria_name,
                    'criteria_type' => $criteria->criteria_type_name ?? 'Single Source',
                    'score' => $criteria->obtained_score ?? 0,
                    'status' => 'Aktif', // Default
                    'value' => [
                        'criterias' => [],
                        'rules' => [],
                        'notes' => $criteria->description
                    ]
                ];

                // Build criterias dari stepValues -> inputValues
                foreach ($criteria->stepValues as $step) {
                    foreach ($step->inputValues as $stepValue) {
                        $item['value']['criterias'][] = [
                            'variable_key' => $stepValue->variable_code,
                            'value' => $stepValue->value
                        ];
                    }
                }

                // Jika tidak ada stepValues, gunakan data dari criteria
                if (empty($item['value']['criterias'])) {
                    $item['value']['criterias'][] = [
                        'variable_key' => $criteria->criteria_id,
                        'value' => $criteria->obtained_score ?? 0
                    ];
                }

                // Build rules dari detailRules
                foreach ($criteria->detailRules as $rule) {
                    $item['value']['rules'][] = [
                        'id' => $rule->rule_id,
                        'rule' => $rule->rule_name,
                        'is_pass' => $rule->is_pass ? 1 : 0
                    ];
                }

                // Jika tidak ada rules, beri array kosong
                if (empty($item['value']['rules'])) {
                    $item['value']['rules'] = [];
                }

                $categoryData = [
                    'category' => [
                        'id' => $criteria->category_id,
                        'name' => $criteria->category_name
                    ],
                    'total_weight_active' => $criteria->criteria_max_score,
                    'items' => [$item]
                ];

                $assessmentData['categories'][] = $categoryData;
            }

            $response['assessments'][] = $assessmentData;
        }

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
                'status_id' => $data['status'] ?? null,
                'notes' => $data['notes'] ?? null,
            ];
            $this->validationWoInterface->storeWo([$assessmentTransactionData], $id);

            // 2. Prepare all data
            $assessmentDetails = [];
            $criteriaDetails = [];
            $ruleDetails = [];
            $stepDetails = [];
            $stepValueDetails = [];

            foreach ($data['assessments'] as $assessmentData) {
                $assessmentId = $assessmentData['assessment']['id'];

                // Assessment detail
                $assessmentDetails[] = [
                    'id' => $assessmentId,
                    'assessment_transaction_id' => $id,
                    'assessment_id' => $assessmentData['assessment']['id'],
                    'assessment_name' => $assessmentData['assessment']['name'],
                    'assessment_code_id' => null,
                    'total_score' => 0
                ];

                // Categories
                foreach ($assessmentData['categories'] as $categoryData) {
                    $categoryId = $categoryData['category']['id'];

                    // Criteria
                    $criteriaDetails[] = [
                        'id' => $categoryId,
                        'assessment_detail_id' => $assessmentId,
                        'criteria_id' => $categoryData['category']['id'],
                        'criteria_name' => $categoryData['category']['name'],
                        'category_id' => $categoryData['category']['id'],
                        'category_name' => $categoryData['category']['name'],
                        'criteria_max_score' => $categoryData['total_weight_active'],
                        'criteria_type_id' => null,
                        'criteria_type_name' => null,
                        'operation_id' => null,
                        'score_type_id' => null,
                        'max_score' => $categoryData['total_weight_active'],
                        'obtained_score' => 0,
                        'calculated_value' => null,
                        'description' => null
                    ];

                    // Items
                    foreach ($categoryData['items'] as $itemData) {
                        // Rules
                        foreach ($itemData['value']['rules'] as $ruleData) {
                            $ruleDetails[] = [
                                'id' => $ruleData['id'],
                                'criteria_transaction_id' => $categoryId,
                                'rule_id' => $ruleData['id'],
                                'rule_name' => $ruleData['rule'],
                                'expected_value' => null,
                                'actual_value' => $ruleData['is_pass'],
                                'is_pass' => (bool) $ruleData['is_pass']
                            ];
                        }

                        // Steps (if exists)
                        if (isset($itemData['value']['steps']) && is_array($itemData['value']['steps'])) {
                            foreach ($itemData['value']['steps'] as $stepData) {
                                $stepDetails[] = [
                                    'id' => $stepData['id'] ?? null,
                                    'criteria_transaction_id' => $categoryId,
                                    'order' => $stepData['order'] ?? 0,
                                    'step_code' => $stepData['step_code'] ?? null,
                                    'variable_code' => $stepData['variable_code'] ?? null,
                                    'variable_name' => $stepData['variable_name'] ?? null,
                                ];

                                // Step Values
                                if (isset($stepData['values']) && is_array($stepData['values'])) {
                                    foreach ($stepData['values'] as $valueData) {
                                        $stepValueDetails[] = [
                                            'id' => $valueData['id'] ?? null,
                                            'assessment_step_values_id' => $stepData['id'] ?? null,
                                            'variable_name' => $valueData['variable_name'] ?? null,
                                            'variable_code' => $valueData['variable_code'] ?? null,
                                            'order' => $valueData['order'] ?? 0,
                                            'type_data' => $valueData['type_data'] ?? null,
                                            'value' => $valueData['value'] ?? null,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // 3. Execute upserts in sequence
            if (!empty($assessmentDetails)) {
                foreach ($assessmentDetails as $detail) {
                    $this->validationWoInterface->upsertAssessmentWo([$detail], $detail['id']);
                }
            }

            if (!empty($criteriaDetails)) {
                foreach ($criteriaDetails as $criteria) {
                    $this->validationWoInterface->upsertAssessmentDetailCriteriaWo([$criteria], $criteria['id']);
                }
            }

            if (!empty($ruleDetails)) {
                foreach ($ruleDetails as $rule) {
                    $this->validationWoInterface->upsertAssessmentCriteriaRuleWo([$rule], $rule['id']);
                }
            }

            if (!empty($stepDetails)) {
                foreach ($stepDetails as $step) {
                    $this->validationWoInterface->upsertAssessmentCriteriaStepWo([$step], $step['id']);
                }
            }

            if (!empty($stepValueDetails)) {
                foreach ($stepValueDetails as $stepValue) {
                    $this->validationWoInterface->upsertAssessmentCriteriaStepValueWo([$stepValue], $stepValue['id']);
                }
            }

            // 4. Return the updated Work Order
            return $this->validationWoInterface->showWoWithRelations($id);
        });
    }
}
