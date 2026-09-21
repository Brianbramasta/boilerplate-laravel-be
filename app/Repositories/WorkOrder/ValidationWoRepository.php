<?php

namespace App\Repositories\WorkOrder;

use App\Helpers\QueryFilterHelper;
use App\Interface\WorkOrder\ValidationWoInterface;
use App\Models\AssessmentPSB\AssessmentStepDataValue;
use App\Models\AssessmentPSB\AssessmentStepValue;
use App\Models\AssessmentPSB\AssessmentTransaction;
use App\Models\AssessmentPSB\AssessmentTransactionDetail;
use App\Models\AssessmentPSB\AssessmentTransactionDetailCriteria;
use App\Models\AssessmentPSB\AssessmentTransactionDetailRule;
use App\Models\AssessmentPSB\AssessmentTransactionLog;
use App\Models\AssessmentPSB\AssessmentTransactionStepValue;
use App\Models\AssessmentPSB\StepInputValue;
use App\Models\Master\Assessment;
use App\Models\Master\AssessmentCriteria;
use App\Models\Ref\AssessmentResult;
use App\Models\Ref\AssessmentStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ValidationWoRepository implements ValidationWoInterface
{
    protected string $path = 'psb-wo-steps.json';

    public function __construct() {}

    /* =========================================================
     *  INDEX
     * ========================================================= */

    public function index(array $data)
    {
        $baseModel = new AssessmentTransaction;
        $baseTable = $baseModel->getTable();

        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.id")
            ->addSelect("{$baseTable}.pppoe_secret")
            ->addSelect("{$baseTable}.status_id")
            ->addSelect("{$baseTable}.result_id")
            ->addSelect("ai.work_order_date as wo_date")
            ->addSelect("ai.no_wo")
            ->addSelect("ai.branch_name")
            ->addSelect("ai.customer_name")
            ->addSelect("ati.splitter")
            ->addSelect("atipsb.team_name")
            ->addSelect("pic.full_name as katim")
            ->addSelect("rs.code as status")
            ->addSelect("rs.name as status_name")
            ->addSelect("rsr.code as result_code")
            ->addSelect("rsr.name as result_name");

        $query->with([
            'logsNewest:id,assessment_transaction_id,changed_by,created_at',
            'logsNewest.byUser:id,name',
            'scoreAverage',
        ]);

        $searchableColumns = [
            "{$baseTable}.pppoe_secret",
            "{$baseTable}.company_code",
        ];

        $query->leftJoin('ref_assessment_statuses as rs', "{$baseTable}.status_id", '=', 'rs.id');
        $query->leftJoin('ref_assessment_results as rsr', "{$baseTable}.result_id", '=', 'rsr.id');
        $query->leftJoin('dt_assessment_informations as ai', "{$baseTable}.id", '=', 'ai.assessment_transaction_id');
        $query->leftJoin('dt_assessment_technical_informations as ati', 'ai.id', '=', 'ati.assessment_information_id');
        $query->leftJoin('dt_assessment_wo_psb_informations as atipsb', 'ai.id', '=', 'atipsb.assessment_information_id');
        $query->leftJoin('dt_assessment_pic_informations as pic', function ($join) {
            $join->on('ai.id', '=', 'pic.assessment_information_id')
                ->where('pic.is_primary', 1);
        });

        $status = $data['status'] ?? null;
        $hasStatus = $status !== null && $status !== '';

        if (isset($data['is_complete']) && $data['is_complete'] !== null && $data['is_complete'] !== '') {
            $query->where('rs.code', 'COMPLETE');

            if ($hasStatus) {
                $query->where('rsr.code', $status);
            }

            if (!empty($data['status_code']) && is_array($data['status_code'])) {
                $query->whereIn('rs.code', $data['status_code']);
            }
        } else {
            $query->where('rs.code', '!=', 'COMPLETE');

            if ($hasStatus) {
                $query->where("{$baseTable}.status_id", $status);
            }
        }

        if (!empty($data['status_code']) && is_array($data['status_code'])) {
            $query->whereIn('rs.code', $data['status_code']);
        }

        if (!empty($data['branch'])) {
            $query->where('ai.branch_id', $data['branch']);
        }

        if (!empty($data['katim'])) {
            $query->where('pic.full_name', $data['katim']);
        }

        QueryFilterHelper::applyDynamicFilters($query, $data, $searchableColumns);

        $page = isset($data['page']) ? (int) $data['page'] : null;
        $perPage = isset($data['per_page']) ? (int) $data['per_page'] : 20;

        return ($page !== null)
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    /* =========================================================
     *  MASTER VARIABLES (data access + eager load)
     * ========================================================= */

    public function getActiveAssessmentsWithCriteria(): \Illuminate\Support\Collection
    {
        return Assessment::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getCriteriaGroupedByAssessment(): \Illuminate\Support\Collection
    {
        return AssessmentCriteria::with([
            'category',
            'operation',
            'scoreType',
            'criteriaType',
            'rules',
            'steps',
            'steps.stepValues',
        ])
            ->orderBy('criteria_code')
            ->get()
            ->groupBy('assessment_id');
    }

    public function getStepValuesKeyedByStepId(string $assessmentInformationId): \Illuminate\Support\Collection
    {
        return AssessmentStepValue::where('assessment_information_id', $assessmentInformationId)
            ->get()
            ->keyBy('step_id');
    }

    public function getStepDataValuesMap(array $stepValueIds): array
    {
        if (empty($stepValueIds)) {
            return [];
        }

        $map = [];
        $dataValues = AssessmentStepDataValue::whereIn('assessment_step_values_id', $stepValueIds)
            ->get(['assessment_step_values_id', 'data_code', 'value']);

        foreach ($dataValues as $dv) {
            $map[$dv->assessment_step_values_id][$dv->data_code] = $dv->value;
        }

        return $map;
    }

    public function getMasterVariablesJson(): array
    {
        if (!Storage::exists($this->path)) {
            return [];
        }

        return json_decode(Storage::get($this->path) ?: '[]', false) ?? [];
    }

    /* =========================================================
     *  SHOW WO WITH RELATIONS
     * ========================================================= */

    public function showWoWithRelations(string $id)
    {
        return AssessmentTransaction::where('id', $id)
            ->with([
                'details:id,assessment_transaction_id,assessment_id,assessment_name,assessment_code_id,total_score',
                'details.detailCriteria:id,assessment_detail_id,criteria_id,criteria_name,category_id,category_name,criteria_type_id,criteria_type_name,operation_id,score_type_id,max_score,obtained_score,calculated_value,description',
                'details.detailCriteria.detailRules:id,criteria_transaction_id,rule_id,rule_name,actual_value,is_pass',
                'details.detailCriteria.stepValues:id,criteria_transaction_id,order,step_code,variable_code,variable_name',
                'details.detailCriteria.stepValues.inputValues:id,assessment_step_values_id,variable_name,variable_code,order,type_data,value',
                'status:id,code,name',
                'information',
                'information.assessmentCode:id,code',
                'information.technicalInformation:id,assessment_information_id,splitter',
                'information.picInformations:id,assessment_information_id,full_name,is_primary',
                'information.woPsbInformation:id,assessment_information_id,team_name,sales_distance,technician_distance,dropcore',
                'information.stepValues:id,assessment_information_id,step_id,step_code,step_name,order_number,updated_at',
                'logsNewest:id,assessment_transaction_id,changed_by,created_at',
                'logsNewest.byUser:id,name',
            ])
            ->first();
    }

    /* =========================================================
     *  SIMPLE CRUD (tanpa logika bisnis)
     * ========================================================= */

    public function showStatuses()
    {
        return AssessmentStatus::get();
    }

    public function updateValidasiWo(array $data, string $id)
    {
        if (!$id) {
            return null;
        }

        $transaction = AssessmentTransaction::find($id);
        if ($transaction) {
            $transaction->update($data);
            return $transaction;
        }
        return null;
    }

    public function findAssessmentTransaction(string $id)
    {
        return AssessmentTransaction::find($id);
    }

    public function upsertAssessmentWo(array $data, ?string $id)
    {
        return AssessmentTransactionDetail::updateOrCreate(['id' => $id], $data);
    }

    public function upsertAssessmentDetailCriteriaWo(array $data, ?string $id)
    {
        return AssessmentTransactionDetailCriteria::updateOrCreate(['id' => $id], $data);
    }

    public function upsertLogAssessmentTransactional(array $data, ?string $id)
    {
        return AssessmentTransactionLog::updateOrCreate(['id' => $id], $data);
    }

    public function getResultStatuses()
    {
        return Cache::remember('assessment_results', 300, function () {
            return AssessmentResult::get();
        });
    }

    /* =========================================================
     *  BATCH PERSIST (data access murni, TANPA generate UUID)
     *  → UUID sudah di-generate di Service
     * ========================================================= */

    public function insertBatchAssessmentDetailRules(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            AssessmentTransactionDetailRule::insert($chunk);
        }
    }

    public function updateBatchAssessmentDetailRules(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $updateColumns = array_keys((array) $chunk[0]);
            $updateColumns = array_values(array_diff($updateColumns, ['id', 'created_at']));
            AssessmentTransactionDetailRule::upsert($chunk, ['id'], $updateColumns);
        }
    }

    public function insertBatchAssessmentSteps(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            AssessmentTransactionStepValue::insert($chunk);
        }
    }

    public function updateBatchAssessmentSteps(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $updateColumns = array_keys((array) $chunk[0]);
            $updateColumns = array_values(array_diff($updateColumns, ['id', 'created_at']));
            AssessmentTransactionStepValue::upsert($chunk, ['id'], $updateColumns);
        }
    }

    public function insertBatchStepInputValues(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            StepInputValue::insert($chunk);
        }
    }

    public function updateBatchStepInputValues(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $updateColumns = array_keys((array) $chunk[0]);
            $updateColumns = array_values(array_diff($updateColumns, ['id', 'created_at']));
            StepInputValue::upsert($chunk, ['id'], $updateColumns);
        }
    }

    /* =========================================================
     *  COUNTER & CACHE
     * ========================================================= */

    public function countWoNotComplete()
    {
        return Cache::remember('wo_not_complete_count', 10, function () {
            return AssessmentTransaction::join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
                ->where('status.code', '!=', 'COMPLETE')
                ->count();
        });
    }

    public function getTotalCategoryAssessmentGrouped()
    {
        return Cache::remember('total_category_assessment_grouped', 60, function () {
            return AssessmentCriteria::select('assessment_id', 'assessment_category_id')
                ->groupBy('assessment_id', 'assessment_category_id')
                ->get()
                ->count();
        });
    }

    public function forgetWoNotCompleteCache(): void
    {
        Cache::forget('wo_not_complete_count');
    }

    public function getExpiredInProgressLogs()
    {
        // Ambil satu assessment IN_PROGRESS terbaru
        $latestAssessment = DB::table('tr_assessments as tr')
            ->join('ref_assessment_statuses as curr_status', 'tr.status_id', '=', 'curr_status.id')
            ->where('curr_status.code', 'IN_PROGRESS')
            ->whereNotNull('tr.created_at')
            ->orderByDesc('tr.created_at')
            ->first(['tr.id']);

        if (!$latestAssessment) {
            return collect([]);
        }

        // Ambil log IN_PROGRESS terbaru untuk assessment tersebut (tanpa filter waktu)
        $latestLog = AssessmentTransactionLog::query()
            ->join('ref_assessment_statuses as tos', 'log_assessment_transactionals.to_status_id', '=', 'tos.id')
            ->where('tos.code', 'IN_PROGRESS')
            ->where('log_assessment_transactionals.assessment_transaction_id', $latestAssessment->id)
            ->orderByDesc('log_assessment_transactionals.id')
            ->first([
                'log_assessment_transactionals.id',
                'log_assessment_transactionals.assessment_transaction_id',
                'log_assessment_transactionals.from_status_id',
                'log_assessment_transactionals.to_status_id',
                'log_assessment_transactionals.changed_by',
                'log_assessment_transactionals.created_at',
            ]);

        return $latestLog ? collect([$latestLog]) : collect([]);
    }

    /**
     * Batch update status tr_assessments berdasarkan mapping id => status_id.
     *
     * @param array $mapping [assessment_transaction_id => from_status_id]
     * @return int
     */
    public function batchUpdateAssessmentStatuses(array $mapping): int
    {
        if (empty($mapping)) {
            return 0;
        }

        // Group by status_id agar bisa batch update per status
        $grouped = [];
        foreach ($mapping as $assessmentId => $statusId) {
            $grouped[$statusId][] = $assessmentId;
        }

        $totalUpdated = 0;

        foreach ($grouped as $statusId => $assessmentIds) {
            $totalUpdated += AssessmentTransaction::whereIn('id', $assessmentIds)
                ->update(['status_id' => $statusId]);
        }

        return $totalUpdated;
    }

    /**
     * Batch insert log revert.
     *
     * @param array $logs
     * @return bool
     */
    public function batchInsertLogs(array $logs): bool
    {
        if (empty($logs)) {
            return true;
        }

        // Chunk agar tidak terlalu besar
        foreach (array_chunk($logs, 500) as $chunk) {
            AssessmentTransactionLog::insert($chunk);
        }

        return true;
    }
}
