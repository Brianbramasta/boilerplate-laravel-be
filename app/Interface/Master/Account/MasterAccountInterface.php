<?php

namespace App\Interface\Master\Account;

interface MasterAccountInterface
{
    public function index($params = [], $perPage);
    public function store(array $data);
    public function show(string $id);
    public function update(string $id, array $data);
    public function getRolesDropdown();
}
