<?php

namespace App\Interface\WorkOrder;

// Disesuaikan dengan metode-metode di WoRepository.php
interface WoInterface
{

    public function checkStatusCode(string $code);
    public function checkAssessmentCode(string $code);
    // Work Order utama
    public function storeWo(array $data, ?string $id);

    // Assessment Information
    public function showAssessmentInformation(string $id);
    public function upsertAssessmentInformation(array $data, ?string $id);

    // Assessment Technical Information
    public function showAssessmentInformationTechnical(string $id);
    public function upsertAssessmentInformationTechnical(array $data, ?string $id);

    // Assessment Wo PSB Information
    public function showAssessmentWoPsbInformation(string $id);
    public function upsertAssessmentWoPsbInformation(array $data, ?string $id);

    // Assessment PIC Information
    public function showAssessmentPicInformation(string $id);
    public function upsertAssessmentPicInformation(array $data, ?string $id);

    // Assessment Step
    public function showAssessmentStep(string $id);
    public function upsertAssessmentStep(array $data, ?string $id);

    // Assessment Step Value
    public function showAssessmentStepValue(string $id);
    public function upsertAssessmentStepValue(array $data, ?string $id);
}
