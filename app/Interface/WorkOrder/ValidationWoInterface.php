<?php

namespace App\Interface\WorkOrder;

interface ValidationWoInterface
{
    /* Index & master */
    public function index(array $data);
    public function getActiveAssessmentsWithCriteria(): \Illuminate\Support\Collection;
    public function getCriteriaGroupedByAssessment(): \Illuminate\Support\Collection;
    public function getStepValuesKeyedByStepId(string $assessmentInformationId): \Illuminate\Support\Collection;
    public function getStepDataValuesMap(array $stepValueIds): array;
    public function getMasterVariablesJson(): array;

    /* Show */
    public function showWoWithRelations(string $id);
    public function showStatuses();
    public function findAssessmentTransaction(string $id);

    /* Simple CRUD */
    public function updateValidasiWo(array $data, string $id);
    public function upsertAssessmentWo(array $data, ?string $id);
    public function upsertAssessmentDetailCriteriaWo(array $data, ?string $id);
    public function upsertLogAssessmentTransactional(array $data, ?string $id);

    /* Batch INSERT (row baru) */
    public function insertBatchAssessmentDetailRules(array $rows): void;
    public function insertBatchAssessmentSteps(array $rows): void;
    public function insertBatchStepInputValues(array $rows): void;

    /* Batch UPDATE (row existing) */
    public function updateBatchAssessmentDetailRules(array $rows): void;
    public function updateBatchAssessmentSteps(array $rows): void;
    public function updateBatchStepInputValues(array $rows): void;

    /* Counter & cache */
    public function countWoNotComplete();
    public function getTotalCategoryAssessmentGrouped();
    public function getResultStatuses();
    public function forgetWoNotCompleteCache(): void;
}