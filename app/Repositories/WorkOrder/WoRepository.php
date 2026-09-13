<?php

namespace App\Repositories\WorkOrder;

use App\Interface\WorkOrder\WoInterface;
use App\Models\AssessmentPSB\AssessmentInformation;
use App\Models\AssessmentPSB\AssessmentPicInformation;
use App\Models\AssessmentPSB\AssessmentStepDataValue;
use App\Models\AssessmentPSB\AssessmentStepValue;
use App\Models\AssessmentPSB\AssessmentTechnicalInformation;
use App\Models\AssessmentPSB\AssessmentTransaction;
use App\Models\AssessmentPSB\AssessmentWoPsbInformation;
use App\Models\Ref\AssessmentCode;
use App\Models\Ref\AssessmentStatus;

class WoRepository implements WoInterface
{

    public function __construct() {}

    public function checkAssessmentCode(string $code)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentCode;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.code", $code);

        return $query->first();
    }

    public function checkStatusCode(string $code)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentStatus;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.code", $code);

        return $query->first();
    }

    public function findByPppoeSecret(string $pppoeSecret)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTransaction;
        $baseTable = $baseModel->getTable();

        // Query dasar dengan where clause sesuai pppoe_secret
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.pppoe_secret", $pppoeSecret);

        return $query->first();
    }

    public function storeWo(array $data, ?string $id)
    {
        return AssessmentTransaction::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentInformation(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentInformation;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentInformation(array $data, ?string $id)
    {
        return AssessmentInformation::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentInformationTechnical(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentTechnicalInformation;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentInformationTechnical(array $data, ?string $id)
    {
        return AssessmentTechnicalInformation::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentWoPsbInformation(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentWoPsbInformation;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentWoPsbInformation(array $data, ?string $id)
    {
        return AssessmentWoPsbInformation::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentPicInformation(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentPicInformation;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentPicInformation(array $data, ?string $id)
    {
        return AssessmentPicInformation::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentStep(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentStepValue;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentStep(array $data, ?string $id)
    {
        return AssessmentStepValue::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }

    public function showAssessmentStepValue(string $id)
    {
        // Inisialisasi model dan tabel
        $baseModel = new AssessmentStepDataValue;
        $baseTable = $baseModel->getTable();

        // Query dasar mt_component dengan where clause
        $query = $baseModel->newQuery()
            ->addSelect("{$baseTable}.*");

        $query->where("{$baseTable}.id", $id);

        return $query->get();
    }

    public function upsertAssessmentStepValue(array $data, ?string $id)
    {
        return AssessmentStepDataValue::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }
}
