<?php

namespace App\Interface\WorkOrder;

interface ValidationWoInterface
{
    public function index(array $data);

    public function showStatuses();
    public function showMasterVariables(string $id);
    public function showWoWithRelations(string $id);
    public function storeWo(array $data, ?string $id);

    public function showAssessmentWo(string $id);
    public function upsertAssessmentWo(array $data, ?string $id);

    public function showAssessmentDetailCriteriaWo(string $id);
    public function upsertAssessmentDetailCriteriaWo(array $data, ?string $id);

    public function showAssessmentCriteriaRuleWo(string $id);
    public function upsertAssessmentCriteriaRuleWo(array $data, ?string $id);

    public function showAssessmentCriteriaStepWo(string $id);
    public function upsertAssessmentCriteriaStepWo(array $data, ?string $id);

    public function showAssessmentCriteriaStepValueWo(string $id);
    public function upsertAssessmentCriteriaStepValueWo(array $data, ?string $id);
}
