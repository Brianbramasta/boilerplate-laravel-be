<?php
namespace App\Services\RolePermission\Account;
use App\Interface\RolePermission\Account\MasterAccountInterface;

class MasterAccountService
{
    public function __construct(private MasterAccountInterface $masterAccountInterface)
    {
    }
    public function index($params = [], $perPage)
    {
        return $this->masterAccountInterface->index($params, $perPage);
    }

    public function store(array $data)
    {
        return $this->masterAccountInterface->store($data);
    }

    public function show(string $id)
    {
        return $this->masterAccountInterface->show($id);
    }

    public function update(string $id, array $data)
    {
        return $this->masterAccountInterface->update($id, $data);
    }

    public function getRolesDropdown()
    {
        return $this->masterAccountInterface->getRolesDropdown();
    }
}
