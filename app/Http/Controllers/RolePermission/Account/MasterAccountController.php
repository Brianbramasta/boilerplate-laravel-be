<?php

namespace App\Http\Controllers\RolePermission\Account;

use App\Http\Controllers\BaseController;
use App\Http\Requests\RolePermission\Account\StoreMasterAccountRequest;
use App\Http\Requests\RolePermission\Account\UpdateMasterAccountRequest;
use App\Http\Resources\RolePermission\Account\DetailMasterAccountResource;
use App\Http\Resources\RolePermission\Account\IndexMasterAccountResource;
use App\Services\RolePermission\Account\MasterAccountService;
use Illuminate\Http\Request;

class MasterAccountController extends BaseController
{
    public function __construct(protected MasterAccountService $masterAccountService)
    {
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $perPage = $request->query('per_page', 10);
            $data = $this->masterAccountService->index($params, $perPage);

            return $this->successResponse(
                IndexMasterAccountResource::collection($data),
                'Berhasil mengambil data master account'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function store(StoreMasterAccountRequest $request)
    {
        try {
            $data = $this->masterAccountService->store($request->validated());

            return $this->createdResponse(
                new DetailMasterAccountResource($data),
                'Account berhasil dibuat'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function show(string $id)
    {
        try {
            $data = $this->masterAccountService->show($id);

            return $this->successResponse(
                new DetailMasterAccountResource($data),
                'Berhasil mengambil detail account'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function update(UpdateMasterAccountRequest $request, string $id)
    {
        try {
            $data = $this->masterAccountService->update($id, $request->validated());

            return $this->successResponse(
                new DetailMasterAccountResource($data),
                'Account berhasil diupdate'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function rolesDropdown()
    {
        try {
            $data = $this->masterAccountService->getRolesDropdown();

            return $this->successResponse($data, 'Berhasil mengambil list roles');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
