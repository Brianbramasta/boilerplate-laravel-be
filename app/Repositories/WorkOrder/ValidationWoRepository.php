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

    public function showMasterVariables()
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
            ->map(function ($assessment) use ($criteriaByAssessment, $variable) {
                $assessmentItems = $criteriaByAssessment->get($assessment->id, collect());

                $categories = $assessmentItems
                    ->groupBy('assessment_category_id')
                    ->map(function ($categoryItems) use ($variable) {
                        $category = $categoryItems->first()->category;

                        // Tambahkan step_default_values pada setiap step
                        $items = $categoryItems->values()->map(function ($item) use ($variable) {
                            // Pastikan steps sudah di-load
                            if ($item->relationLoaded('steps')) {
                                $item->steps->each(function ($step) use ($variable) {
                                    // Pastikan $variable tersedia dalam scope
                                    $matchingVariable = $variable->first(function ($v) use ($step) {
                                        return (int)($v->id) === (int)($step->id);
                                    });
                                    $step->step_default_values = $matchingVariable->default_component ?? [];
                                });
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

    public function storeWo(array $data, ?string $id)
    {
        return AssessmentTransaction::upsert(
            $data,
            ['id'],
            [
                'pppoe_secret',
                'assessment_code_id',
                'work_order_id',
                'current_pic_id',
                'company_code',
                'system_code',
                'started_at',
                'completed_at',
                'status_id',
                'result_id',
                'is_had_draft',
            ]
        );
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
        return AssessmentTransactionDetail::upsert(
            $data,
            ['id'],
            [
                'assessment_transaction_id',
                'assessment_id',
                'assessment_name',
                'assessment_code_id',
                'total_score'
            ]
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
        return AssessmentTransactionDetailCriteria::upsert(
            $data,
            ['id'],
            [
                'assessment_detail_id',
                'criteria_id',
                'criteria_name',
                'category_id',
                'category_name',
                'criteria_max_score',
                'criteria_type_id',
                'criteria_type_name',
                'operation_id',
                'score_type_id',
                'max_score',
                'obtained_score',
                'calculated_value',
                'description'
            ]
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
        return AssessmentTransactionDetailRule::upsert(
            $data,
            ['id'],
            [
                'criteria_transaction_id',
                'rule_id',
                'rule_name',
                'expected_value',
                'actual_value',
                'is_pass',
            ]
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
        return AssessmentTransactionStepValue::upsert(
            $data,
            ['id'],
            [
                'criteria_transaction_id',
                'order',
                'step_code',
                'variable_code',
                'variable_name',
            ]
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
        return StepInputValue::upsert(
            $data,
            ['id'],
            [
                'assessment_step_values_id',
                'variable_name',
                'variable_code',
                'order',
                'type_data',
                'value',
            ]
        );
    }
}
