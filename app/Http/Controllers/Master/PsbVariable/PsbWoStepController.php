<?php

namespace App\Http\Controllers\Master\PsbVariable;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\PsbWoStepDocs;
use App\Http\Requests\Master\PsbVariable\UpdatePsbWoStepRequest;
use App\Http\Resources\Master\PsbVariable\DetailPsbWoStepResource;
use App\Http\Resources\Master\PsbVariable\IndexPsbWoStepResource;
use App\Services\Master\PsbVariable\PsbWoStepService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Master PSB Variable', 'Endpoint baca & update data PSB WO Step beserta variable-nya. Sebelum integrasi ke sistem BE lain berjalan, data disimpan lokal (JSON) di sistem ini.')]
class PsbWoStepController extends BaseController
{
    public function __construct(protected PsbWoStepService $psbWoStepService)
    {
    }

    #[QueryParam('search', description: 'Kata kunci pencarian code atau nama step.', required: false, example: 'survey')]
    #[QueryParam('per_page', 'int', description: 'Jumlah data per halaman.', required: false, example: 10)]
    #[Response(PsbWoStepDocs::INDEX_SUCCESS, status: 200, description: 'Berhasil mengambil data psb wo step')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $perPage = $request->query('per_page', 10);
            $data = $this->psbWoStepService->index($params, $perPage);

            return $this->successResponse(
                IndexPsbWoStepResource::collection($data),
                'Berhasil mengambil data psb wo step'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[UrlParam('id', 'int', description: 'ID psb wo step.', example: 1)]
    #[Response(PsbWoStepDocs::SHOW_SUCCESS, status: 200, description: 'Berhasil mengambil detail psb wo step')]
    #[Response(PsbWoStepDocs::NOT_FOUND, status: 404, description: 'Psb wo step dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function show(string $id)
    {
        try {
            $data = $this->psbWoStepService->show($id);

            return $this->successResponse(
                new DetailPsbWoStepResource($data),
                'Berhasil mengambil detail psb wo step'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[UrlParam('id', 'int', description: 'ID psb wo step yang akan diupdate.', example: 1)]
    #[Response(PsbWoStepDocs::UPDATE_SUCCESS, status: 200, description: 'Berhasil mengubah data psb wo step')]
    #[Response(PsbWoStepDocs::UPDATE_VALIDATION_ERROR, status: 422, description: 'Validasi gagal')]
    #[Response(PsbWoStepDocs::UPDATE_DEFAULT_COMPONENT_NOT_FOUND, status: 422, description: 'default_component merujuk ke key yang tidak ada pada step ini')]
    #[Response(PsbWoStepDocs::NOT_FOUND, status: 404, description: 'Psb wo step dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(PsbWoStepDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
    public function update(UpdatePsbWoStepRequest $request, string $id)
    {
        try {
            $data = $this->psbWoStepService->update($id, $request->validated());

            return $this->successResponse(
                new DetailPsbWoStepResource($data),
                'Berhasil mengubah data psb wo step'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
