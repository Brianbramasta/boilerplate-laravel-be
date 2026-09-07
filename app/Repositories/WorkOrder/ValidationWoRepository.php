<?php

namespace App\Repositories\WorkOrder;

use App\Helpers\QueryFilterHelper;
use App\Interface\WorkOrder\ValidationWoInterface;
use App\Models\AssessmentPSB\AssessmentTransaction;
use App\Models\AssessmentPSB\AssessmentTransactionDetail;
use App\Models\AssessmentPSB\AssessmentTransactionDetailCriteria;
use App\Models\AssessmentPSB\AssessmentTransactionDetailRule;
use App\Models\AssessmentPSB\AssessmentTransactionStepValue;
use App\Models\AssessmentPSB\StepInputValue;
use App\Models\Master\Assessment;
use App\Models\Master\AssessmentCriteria;
use App\Models\Ref\AssessmentStatus;
use Illuminate\Support\Facades\Storage;

class ValidationWoRepository implements ValidationWoInterface
{
    protected string $path = 'psb-wo-steps.json';

    public function __construct() {}

    public function index(array $data)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransaction;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.id")
            ->addSelect("{$baseTable}.pppoe_secret")
            ->addSelect("ai.work_order_date as wo_date")
            ->addSelect("ai.no_wo")
            ->addSelect("ai.branch_name")
            ->addSelect("ai.pppoe_secret")
            ->addSelect("ai.customer_name")
            ->addSelect("ati.splitter")
            ->addSelect("atipsb.team_name")
            ->addSelect("pic.full_name as katim")
            ->addSelect('rs.code as status')
            ->addSelect('rs.name as status_name');

        // Kolom yang bisa dicari (searchable)
        $searchableColumns = [
            "{$baseTable}.pppoe_secret",
            "{$baseTable}.company_code",
        ];

        $query->leftjoin('ref_assessment_statuses as rs', "{$baseTable}.status_id", '=', 'rs.id');
        $query->leftjoin('dt_assessment_informations as ai', "{$baseTable}.id", '=', 'ai.assessment_transaction_id');
        $query->leftjoin('dt_assessment_technical_informations as ati', "ai.id", '=', 'ati.assessment_information_id');
        $query->leftjoin('dt_assessment_wo_psb_informations as atipsb', "ai.id", '=', 'atipsb.assessment_information_id');
        $query->leftjoin('dt_assessment_pic_informations as pic', function ($join) {
            $join->on("ai.id", '=', 'pic.assessment_information_id')
                ->where('pic.is_primary', 1);
        });

        // Jika ada 'status' di $data, lakukan join dengan tabel ref_status dan where pada status.code
        if (isset($data['status']) && $data['status'] !== null && $data['status'] !== '') {
            $query->where('rs.code', $data['status']);
        }

        QueryFilterHelper::applyDynamicFilters(
            $query,
            $data,
            $searchableColumns
        );

        // Pagination handling
        $page = isset($data['page']) ? (int) $data['page'] : null;
        $perPage = isset($data['per_page']) ? (int) $data['per_page'] : 20;

        return ($page !== null)
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    public function showMasterVariables(string $assessmentInformationId)
    {
        if (!Storage::exists($this->path)) {
            return collect();
        }

        $variable = collect(json_decode(Storage::get($this->path) ?: '[]', false));

        // echo json_encode($variable->toArray());
        // Dikelompokkan per assessment_id dulu, supaya proses (mt_assessments)
        // yang belum punya item sama sekali tetap bisa "diisi" jadi grup kosong
        // di bawah, bukan hilang dari response.
        $criteriaByAssessment = AssessmentCriteria::with([
            'category',
            'operation',
            'scoreType',
            'criteriaType',
            'rules',
            'steps',
            'steps.stepValues'
        ])
            ->orderBy('criteria_code')
            ->get()
            ->groupBy('assessment_id');

        return Assessment::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($assessment) use ($criteriaByAssessment, $variable, $assessmentInformationId) {
                $assessmentItems = $criteriaByAssessment->get($assessment->id, collect());

                $categories = $assessmentItems
                    ->groupBy('assessment_category_id')
                    ->map(function ($categoryItems) use ($variable, $assessmentInformationId) {
                        $category = $categoryItems->first()->category;

                        // Tambahkan step_default_values pada setiap step,
                        // dan flag is_default pada step_value jika variable_code sama dengan key pada step_default_values
                        $items = $categoryItems->values()->map(function ($item) use ($variable, $assessmentInformationId) {
                            // Pastikan steps sudah di-load
                            if ($item->relationLoaded('steps')) {
                                $item->steps->each(function ($step) use ($variable, $assessmentInformationId) {
                                    // Pastikan $variable tersedia dalam scope
                                    // var_dump($step->toArray());
                                    $matchingVariable = $variable->first(function ($v) use ($step) {
                                        return (int)($v->id) === (int)($step->step_id);
                                    });

                                    // step_default_values: pada setiap item tambahkan property 'value'
                                    $stepDefaultValues = [];
                                    $defaultComponent = $matchingVariable->default_component ?? [];

                                    // var_dump($defaultComponent);

                                    foreach ($defaultComponent as $componentItem) {
                                        $itemWithValue = (array) $componentItem;
                                        // Ambil value dari tabel dt_assessment_step_values berdasarkan step id
                                        $stepValueModel = \App\Models\AssessmentPSB\AssessmentStepValue::where('assessment_information_id', $assessmentInformationId)
                                            ->where('step_id', $step->step_id)
                                            ->first();


                                        if ($stepValueModel) {

                                            $dataValue = \App\Models\AssessmentPSB\AssessmentStepDataValue::where('assessment_step_values_id', $stepValueModel->id)
                                                ->where('data_code', $itemWithValue['key'] ?? null)
                                                ->first();

                                            $itemWithValue['value'] = $dataValue ? $dataValue->value : null;

                                            $dataValue = \App\Models\AssessmentPSB\AssessmentStepDataValue::where('assessment_step_values_id', $stepValueModel->id)
                                                ->where('data_code', 'timestamp')
                                                ->first();
                                            $itemWithValue['timestamp'] = $dataValue && $dataValue->value
                                                ? $dataValue->value
                                                : ($stepValueModel ? $stepValueModel->updated_at : null);
                                        } else {
                                            $itemWithValue['value'] = null;
                                        }

                                        $stepDefaultValues[] = (object)$itemWithValue;
                                    }
                                    $step->step_default_values = $stepDefaultValues;

                                    // Tambahkan flag is_default pada stepValues jika variable_code sama dengan key pada step_default_values
                                    if ($step->relationLoaded('stepValues')) {
                                        foreach ($step->stepValues as $stepValue) {
                                            $isDefault = false;
                                            foreach ($step->step_default_values as $defaultValue) {
                                                if (
                                                    isset($defaultValue->key) &&
                                                    isset($stepValue->variable_code) &&
                                                    $stepValue->variable_code === $defaultValue->key
                                                ) {
                                                    $isDefault = true;
                                                    break;
                                                }
                                            }
                                            // Tambahkan value juga pada stepValue dari tabel dt_assessment_step_data_values jika ada, berdasarkan assessment_step_values_id dan variable_code
                                            $stepValueModel = \App\Models\AssessmentPSB\AssessmentStepValue::where('assessment_information_id', $assessmentInformationId)
                                                ->where('step_id', $step->step_id)
                                                ->first();

                                            if ($stepValueModel) {
                                                $dataValue = \App\Models\AssessmentPSB\AssessmentStepDataValue::where('assessment_step_values_id', $stepValueModel->id)
                                                    ->where('data_code', $stepValue->variable_code ?? null)
                                                    ->first();
                                                $stepValue->value = $dataValue ? $dataValue->value : null;

                                                $dataValue = \App\Models\AssessmentPSB\AssessmentStepDataValue::where('assessment_step_values_id', $stepValueModel->id)
                                                    ->where('data_code', 'timestamp')
                                                    ->first();
                                                $stepValue->timestamp = $dataValue && $dataValue->value
                                                    ? $dataValue->value
                                                    : ($stepValueModel ? $stepValueModel->updated_at : null);
                                            } else {
                                                $stepValue->value = null;
                                            }

                                            $stepValue->is_default = $isDefault;
                                            // $stepValue->id_step_values = '';
                                        }
                                    }
                                });
                            }

                            // tambahkan operation_name dan score_name dalam item
                            if (isset($item['operation_id'])) {
                                $item['operation_name'] = $item->operation->name ?? null;
                                $item['operation_code'] = $item->operation->code ?? null;
                                $item['operation_symbol'] = $item->operation->symbol ?? null;
                            } else {
                                $item['operation_name'] = null;
                                $item['operation_code'] = null;
                                $item['operation_symbol'] = null;
                            }

                            if (isset($item['score_type_id'])) {
                                $item['score_name'] = $item->scoreType->name ?? null;
                                $item['score_code'] = $item->scoreType->code ?? null;
                                $item['score_symbol'] = $item->scoreType->symbol ?? null;
                            } else {
                                $item['score_name'] = null;
                                $item['score_code'] = null;
                                $item['score_symbol'] = null;
                            }

                            if (isset($item['criteria_type_id'])) {
                                $item['criteria_type_name'] = $item->criteriaType->name ?? null;
                                $item['criteria_type_code'] = $item->criteriaType->code ?? null;
                            } else {
                                $item['criteria_type_name'] = null;
                                $item['criteria_type_code'] = null;
                            }

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

    // Di ValidationWoRepository.php - Tambahkan method showWoWithRelations
    public function showWoWithRelations(string $id)
    {
        return AssessmentTransaction::where('id', $id)
            ->with([
                // 'details' => function ($query) {
                //     $query->with([
                //         'detailCriteria' => function ($q) {
                //             $q->with([
                //                 'detailRules',
                //                 'stepValues' => function ($sq) {
                //                     $sq->with('inputValues');
                //                 }
                //             ]);
                //         }
                //     ]);
                // },
                // tambahan with lain, contoh relasi 'assessmentStatus' dan 'assessmentInformation'
                'details',
                'details.detailCriteria',
                'details.detailCriteria.detailRules',
                'details.detailCriteria.stepValues',
                'details.detailCriteria.stepValues.inputValues',
                'status',
                'information',
                'information.assessmentCode',
                'information.technicalInformation',
                'information.picInformations',
                'information.woPsbInformation',
                'information.stepValues',
            ])

            ->first();
    }

    public function showStatuses()
    {
        return AssessmentStatus::get();
    }

    public function updateValidasiWo(array $data, string $id)
    {
        if ($id) {
            $transaction = AssessmentTransaction::find($id);
            if ($transaction) {
                $transaction->update($data);
                return $transaction;
            }
        }
        return null;
    }

    public function showAssessmentWo(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransactionDetail;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentWo(array $data, ?string $id)
    {
        return AssessmentTransactionDetail::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentDetailCriteriaWo(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransactionDetailCriteria;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentDetailCriteriaWo(array $data, ?string $id)
    {
        return AssessmentTransactionDetailCriteria::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentCriteriaRuleWo(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransactionDetailRule;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentCriteriaRuleWo(array $data, ?string $id)
    {
        return AssessmentTransactionDetailRule::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentCriteriaStepWo(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransactionStepValue;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentCriteriaStepWo(array $data, ?string $id)
    {
        return AssessmentTransactionStepValue::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentCriteriaStepValueWo(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new StepInputValue;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentCriteriaStepValueWo(array $data, ?string $id)
    {
        return StepInputValue::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }
}
