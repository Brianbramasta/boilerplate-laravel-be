<?php

namespace App\Repositories\Master\PsbAssessment;

use App\Interface\Master\PsbAssessment\PsbAssessmentInterface;
use App\Models\AssessmentPSB\AssessmentCriteriaStepValue;
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
    public function __construct(private PsbWoStepService $psbWoStepService)
    {
    }

    public function index($params = [])
    {
        $criteria = AssessmentCriteria::with(['assessment', 'category', 'criteriaType'])
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
            ->get();

        return $criteria
            ->groupBy('assessment_id')
            ->map(function ($assessmentItems) {
                $assessment = $assessmentItems->first()->assessment;

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
            })
            ->values();
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

            foreach ($data['rules'] ?? [] as $index => $rule) {
                AssessmentCriteriaRule::create([
                    'assessment_criteria_id' => $criteria->id,
                    'rule_code' => $criteria->criteria_code . '-R' . ($index + 1),
                    'rule' => $rule['rule'],
                    'is_active' => $rule['is_active'] ?? true,
                ]);
            }

            return $criteria->load(['assessment', 'category', 'criteriaType', 'operation', 'steps.stepValues', 'rules']);
        });
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

            if ($isCalculate && !in_array($variable['type_data'] ?? null, ['number', 'timestamp'], true)) {
                $type = $variable['type_data'] ?? 'tidak diketahui';
                throw new HttpException(422, "Komponen '{$slot['variable_key']}' bertipe {$type}, tidak dapat dikalkulasi (hanya number/timestamp).");
            }

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
