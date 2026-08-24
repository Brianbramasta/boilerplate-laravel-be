<?php

namespace App\Services\Master\PsbAssessment;

use App\Interface\Master\PsbAssessment\PsbAssessmentInterface;

class PsbAssessmentService
{
    public function __construct(private PsbAssessmentInterface $psbAssessmentInterface)
    {
    }

    public function index($params = [])
    {
        return $this->psbAssessmentInterface->index($params);
    }

    public function show(string $id)
    {
        return $this->psbAssessmentInterface->show($id);
    }

    public function store(array $data)
    {
        return $this->psbAssessmentInterface->store($data);
    }

    public function update(string $id, array $data)
    {
        return $this->psbAssessmentInterface->update($id, $data);
    }
}
