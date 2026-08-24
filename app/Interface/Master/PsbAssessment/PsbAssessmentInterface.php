<?php

namespace App\Interface\Master\PsbAssessment;

interface PsbAssessmentInterface
{
    public function index($params = []);
    public function store(array $data);
}
