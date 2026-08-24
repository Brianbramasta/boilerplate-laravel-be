<?php

namespace App\Interface\Master\PsbVariable;

interface PsbWoStepInterface
{
    public function index($params = [], $perPage);
    public function show(string $id);
    public function update(string $id, array $data);
}
