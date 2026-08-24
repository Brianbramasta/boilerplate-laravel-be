<?php

namespace App\Services\Master\PsbVariable;

use App\Interface\Master\PsbVariable\PsbWoStepInterface;

class PsbWoStepService
{
    public function __construct(private PsbWoStepInterface $psbWoStepInterface)
    {
    }

    public function index($params = [], $perPage)
    {
        return $this->psbWoStepInterface->index($params, $perPage);
    }

    public function show(string $id)
    {
        return $this->psbWoStepInterface->show($id);
    }

    public function update(string $id, array $data)
    {
        return $this->psbWoStepInterface->update($id, $data);
    }
}
