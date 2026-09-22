<?php

namespace App\Repositories\Master\PsbAssessment;

use App\Interface\Master\PsbAssessment\PsbAssessmentInterface;
use App\Models\AssessmentPSB\AssessmentCriteriaStepValue;
use App\Models\Master\Assessment;
use App\Models\Master\AssessmentCategory;
use App\Models\Master\AssessmentCriteria;
use App\Models\Master\AssessmentCriteriaRule;
use App\Models\Master\AssessmentCriteriaStep;
use App\Models\Ref\CriteriaType;
use App\Models\Ref\ScoreType;
use App\Services\Master\PsbVariable\PsbWoStepService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PsbAssessmentRepository implements PsbAssessmentInterface
{
    protected const DETAIL_RELATIONS = ['assessment', 'category', 'criteriaType', 'operation', 'steps.stepValues', 'rules'];

    public function __construct(private PsbWoStepService $psbWoStepService)
    {
    }

    public function index($params = [])
    {
        // Dikelompokkan per assessment_id dulu, supaya proses (mt_assessments)
        // yang belum punya item sama sekali tetap bisa "diisi" jadi grup kosong
        // di bawah, bukan hilang dari response.
        $criteriaByAssessment = AssessmentCriteria::with(['category', 'criteriaType'])
            ->when(!empty($params['search']), function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params['search'] . '%');
            })
            ->when(!empty($params['criteria_type_id']), function ($q) use ($params) {
                $q->where('criteria_type_id', $params['criteria_type_id']);
            })
            ->when(isset($params['is_active']) && $params['is_active'] !== '', function ($q) use ($params) {
                $q->where('is_active', filter_var($params['is_active'], FILTER_VALIDATE_BOOLEAN));
            })
            ->orderBy('criteria_code')
            ->get()
            ->groupBy('assessment_id');

        return Assessment::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($assessment) use ($criteriaByAssessment) {
                $assessmentItems = $criteriaByAssessment->get($assessment->id, collect());

                $categories = $assessmentItems
                    ->groupBy('assessment_category_id')
                    ->map(function ($categoryItems) {
                        $category = $categoryItems->first()->category;

                        return [
                            'category' => $category,
                            'total_weight_active' => (float) $categoryItems->where('is_active', true)->sum('score'),
                            'items' => $categoryItems->values(),
                        ];
                    })
                    ->values();

                return [
                    'assessment' => $assessment,
                    'categories' => $categories,
                ];
            });
    }

    public function show(string $id)
    {
        return $this->findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $criteriaType = CriteriaType::findOrFail($data['criteria_type_id']);
            $isCalculate = $criteriaType->code === 'CALCULATE';

            $mappingComponents = $this->resolveMappingComponents($data['mapping'], $isCalculate);

            $criteria = AssessmentCriteria::create([
                'assessment_id' => $data['assessment_id'],
                'assessment_category_id' => $data['assessment_category_id'],
                'name' => $data['name'],
                'criteria_code' => $this->generateCriteriaCode(),
                'criteria_type_id' => $criteriaType->id,
                'operation_id' => $isCalculate ? $data['operation_id'] : null,
                'score_type_id' => $this->defaultScoreType()->id,
                'is_active' => true,
            ]);

            $this->replaceMapping($criteria, $mappingComponents);
            $this->syncRules($criteria, $data['rules'] ?? []);

            return $criteria->load(self::DETAIL_RELATIONS);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $criteria = $this->findOrFail($id);

            $criteriaType = CriteriaType::findOrFail($data['criteria_type_id']);
            $isCalculate = $criteriaType->code === 'CALCULATE';

            $mappingComponents = $this->resolveMappingComponents($data['mapping'], $isCalculate);

            $criteria->update([
                'assessment_id' => $data['assessment_id'],
                'assessment_category_id' => $data['assessment_category_id'],
                'name' => $data['name'],
                'criteria_type_id' => $criteriaType->id,
                'operation_id' => $isCalculate ? $data['operation_id'] : null,
                'is_active' => $data['is_active'],
            ]);

            $this->replaceMapping($criteria, $mappingComponents);
            $this->syncRules($criteria, $data['rules'] ?? []);

            return $criteria->fresh(self::DETAIL_RELATIONS);
        });
    }

    /**
     * "Atur Bobot": update score sekaligus untuk seluruh item AKTIF dalam
     * satu grup (assessment_id + assessment_category_id). Payload wajib
     * berisi PERSIS set item aktif grup itu (tidak boleh kurang/lebih), dan
     * total score-nya harus tepat 100 — kalau tidak, ditolak (422) dan tidak
     * ada yang tersimpan.
     */
    public function updateWeights(array $data)
    {
        return DB::transaction(function () use ($data) {
            $activeCriteria = AssessmentCriteria::where('assessment_id', $data['assessment_id'])
                ->where('assessment_category_id', $data['assessment_category_id'])
                ->where('is_active', true)
                ->get();

            if ($activeCriteria->isEmpty()) {
                throw new HttpException(404, 'Tidak ada item aktif pada grup penilaian ini.');
            }

            $activeIds = $activeCriteria->pluck('id')->sort()->values();
            $incomingIds = collect($data['weights'])->pluck('id')->sort()->values();

            if ($activeIds->all() !== $incomingIds->all()) {
                throw new HttpException(422, 'Daftar item tidak sesuai dengan item aktif pada grup penilaian ini — muat ulang data sebelum menyimpan.');
            }

            $total = collect($data['weights'])->sum('score');

            if (abs($total - 100) > 0.01) {
                $totalLabel = rtrim(rtrim(number_format($total, 2), '0'), '.');
                throw new HttpException(422, "Total bobot harus tepat 100%. Saat ini: {$totalLabel}%.");
            }

            foreach ($data['weights'] as $weight) {
                AssessmentCriteria::where('id', $weight['id'])->update(['score' => $weight['score']]);
            }

            $items = AssessmentCriteria::with('criteriaType')
                ->whereIn('id', $activeIds)
                ->orderBy('criteria_code')
                ->get();

            return [
                'assessment' => Assessment::findOrFail($data['assessment_id']),
                'category' => AssessmentCategory::findOrFail($data['assessment_category_id']),
                'total_weight_active' => (float) $items->sum('score'),
                'items' => $items,
            ];
        });
    }

    protected function findOrFail(string $id): AssessmentCriteria
    {
        $criteria = AssessmentCriteria::with(self::DETAIL_RELATIONS)->find($id);

        if (!$criteria) {
            throw new HttpException(404, 'Item penilaian tidak ditemukan');
        }

        return $criteria;
    }

    /**
     * Mapping selalu diganti total (bukan di-diff per slot): step lama
     * di-soft-delete beserta step value-nya, lalu dibuat baru dari payload.
     * Ini yang paling sederhana mengingat jumlah slot bisa berubah antara
     * Single (1) dan Kalkulasi (2), dan riwayat lama tetap tersimpan lewat
     * soft delete (bukan hard delete).
     */
    protected function replaceMapping(AssessmentCriteria $criteria, array $mappingComponents): void
    {
        foreach ($criteria->steps as $oldStep) {
            $oldStep->stepValues()->delete();
            $oldStep->delete();
        }

        foreach ($mappingComponents as $sequence => $component) {
            $step = AssessmentCriteriaStep::create([
                'assessment_criteria_id' => $criteria->id,
                'sequence' => $sequence + 1,
                'step_id' => (string) $component['step']->id,
                'step_code' => $component['step']->code,
                'step_name' => $component['step']->name,
            ]);

            AssessmentCriteriaStepValue::create([
                'criteria_step_id' => $step->id,
                'variable_name' => $component['variable']['key'],
                'variable_code' => $component['variable']['key'],
                'order' => 1,
                'type_data' => $component['variable']['type_data'] ?? 'text',
                'is_active' => true,
            ]);
        }
    }

    /**
     * rules: diff-based lewat `id`. Item dengan `id` yang cocok diupdate;
     * tanpa `id` dibuat baru; item lama yang tidak disebut lagi di payload
     * di-soft-delete (bukan dihapus permanen) — sama seperti pola
     * extend_component di psb-variable.
     */
    protected function syncRules(AssessmentCriteria $criteria, array $rules): void
    {
        $existingIds = $criteria->rules()->pluck('id');
        $incomingIds = collect($rules)->pluck('id')->filter()->values();

        AssessmentCriteriaRule::whereIn('id', $existingIds->diff($incomingIds))->delete();

        foreach ($rules as $rule) {
            if (!empty($rule['id'])) {
                AssessmentCriteriaRule::where('id', $rule['id'])
                    ->where('assessment_criteria_id', $criteria->id)
                    ->update([
                        'rule' => $rule['rule'],
                        'is_active' => $rule['is_active'] ?? true,
                    ]);

                continue;
            }

            AssessmentCriteriaRule::create([
                'assessment_criteria_id' => $criteria->id,
                'rule_code' => $this->nextRuleCode($criteria),
                'rule' => $rule['rule'],
                'is_active' => $rule['is_active'] ?? true,
            ]);
        }
    }

    protected function nextRuleCode(AssessmentCriteria $criteria): string
    {
        $count = AssessmentCriteriaRule::withTrashed()
            ->where('assessment_criteria_id', $criteria->id)
            ->count();

        return $criteria->criteria_code . '-R' . ($count + 1);
    }

    /**
     * Menerjemahkan tiap slot mapping (Single = 1 slot, Kalkulasi = slot A & B)
     * menjadi data step + komponen yang sudah divalidasi, dengan membaca
     * langsung dari PsbWoStepService (bukan API baru — sesuai arahan: step &
     * komponen memakai master/psb-variable yang sudah ada).
     */
    protected function resolveMappingComponents(array $mapping, bool $isCalculate): array
    {
        $expectedCount = $isCalculate ? 2 : 1;

        if (count($mapping) !== $expectedCount) {
            throw new HttpException(422, $isCalculate
                ? 'Kalkulasi membutuhkan 2 mapping (Input A dan Input B).'
                : 'Single Source membutuhkan 1 mapping.');
        }

        $resolved = [];

        foreach ($mapping as $slot) {
            $step = $this->psbWoStepService->show((string) $slot['step_id']);

            // Cast ke array supaya konsisten baik komponen berasal dari
            // PsbJsonRepository (stdClass) maupun PsbIntegratedRepository (array).
            $variables = collect(array_merge((array) ($step->default_component ?? []), (array) ($step->extend_component ?? [])))
                ->map(fn ($variable) => (array) $variable);

            $variable = $variables->first(fn ($variable) => ($variable['key'] ?? null) === $slot['variable_key']);

            if (!$variable) {
                throw new HttpException(422, "Komponen '{$slot['variable_key']}' tidak ditemukan pada step {$step->code}.");
            }

            // if ($isCalculate && !in_array($variable['type_data'] ?? null, ['number', 'timestamp'], true)) {
            //     $type = $variable['type_data'] ?? 'tidak diketahui';
            //     throw new HttpException(422, "Komponen '{$slot['variable_key']}' bertipe {$type}, tidak dapat dikalkulasi (hanya number/timestamp).");
            // }

            $resolved[] = ['step' => $step, 'variable' => $variable];
        }

        return $resolved;
    }

    protected function generateCriteriaCode(): string
    {
        $max = AssessmentCriteria::where('criteria_code', 'like', 'PN-%')
            ->pluck('criteria_code')
            ->map(fn ($code) => (int) substr($code, 3))
            ->max();

        return 'PN-' . str_pad((string) (($max ?? 0) + 1), 4, '0', STR_PAD_LEFT);
    }

    protected function defaultScoreType(): ScoreType
    {
        return ScoreType::where('code', 'PERCENT')->firstOrFail();
    }
}
