<?php

namespace App\Http\Controllers\Master\Account;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\MasterAccountDocs;
use App\Http\Requests\Master\Account\StoreMasterAccountRequest;
use App\Http\Requests\Master\Account\UpdateMasterAccountRequest;
use App\Http\Resources\Master\Account\DetailMasterAccountResource;
use App\Http\Resources\Master\Account\IndexMasterAccountResource;
use App\Services\Master\Account\MasterAccountService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Master Account', 'Endpoint CRUD akun (user) beserta pengaturan role-nya.')]
class MasterAccountController extends BaseController
{
    public function __construct(protected MasterAccountService $masterAccountService)
    {
    }

    #[QueryParam('search', description: 'Kata kunci pencarian nama atau email.', required: false, example: 'budi')]
    #[QueryParam('per_page', 'int', description: 'Jumlah data per halaman.', required: false, example: 10)]
    #[Response(MasterAccountDocs::INDEX_SUCCESS, status: 200, description: 'Berhasil mengambil data master account')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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

    #[Response(MasterAccountDocs::STORE_SUCCESS, status: 201, description: 'Account berhasil dibuat')]
    #[Response(MasterAccountDocs::STORE_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field wajib kosong')]
    #[Response(MasterAccountDocs::STORE_EMAIL_TAKEN, status: 422, description: 'Validasi gagal — email sudah terdaftar')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(MasterAccountDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
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

    #[UrlParam('id', 'string', description: 'UUID account (user).', example: '9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab')]
    #[Response(MasterAccountDocs::SHOW_SUCCESS, status: 200, description: 'Berhasil mengambil detail account')]
    #[Response(MasterAccountDocs::ACCOUNT_NOT_FOUND, status: 404, description: 'Account dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(MasterAccountDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
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

    #[UrlParam('id', 'string', description: 'UUID account (user) yang akan diupdate.', example: '9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab')]
    #[Response(MasterAccountDocs::UPDATE_SUCCESS, status: 200, description: 'Account berhasil diupdate')]
    #[Response(MasterAccountDocs::UPDATE_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — role_id tidak valid')]
    #[Response(MasterAccountDocs::ACCOUNT_NOT_FOUND, status: 404, description: 'Account dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(MasterAccountDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
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

    #[Response(MasterAccountDocs::ROLES_DROPDOWN_SUCCESS, status: 200, description: 'Berhasil mengambil list roles')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(MasterAccountDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
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
