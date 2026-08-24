<?php

namespace App\Interface\Master\PsbAssessment;

interface PsbAssessmentInterface
{
    public function index($params = []);
    public function show(string $id);
    public function store(array $data);
    public function update(string $id, array $data);
}
