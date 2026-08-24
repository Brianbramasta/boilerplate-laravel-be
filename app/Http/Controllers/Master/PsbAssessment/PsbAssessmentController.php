<?php

namespace App\Http\Controllers\Master\PsbAssessment;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\PsbAssessmentDocs;
use App\Http\Requests\Master\PsbAssessment\StorePsbAssessmentRequest;
use App\Http\Requests\Master\PsbAssessment\UpdatePsbAssessmentRequest;
use App\Http\Resources\Master\PsbAssessment\PsbAssessmentDetailResource;
use App\Http\Resources\Master\PsbAssessment\PsbAssessmentItemResource;
use App\Services\Master\PsbAssessment\PsbAssessmentService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Master PSB Assessment', 'Endpoint list, detail, create & update item Master Penilaian CUNI (mt_assessment_criteria beserta rule dan mapping step/komponen-nya).')]
class PsbAssessmentController extends BaseController
{
    public function __construct(protected PsbAssessmentService $psbAssessmentService)
    {
    }

    #[QueryParam('search', description: 'Kata kunci pencarian nama penilaian.', required: false, example: 'redaman')]
    #[QueryParam('criteria_type_id', description: 'Filter berdasarkan cara penilaian (UUID ref_criteria_types).', required: false)]
    #[QueryParam('is_active', 'boolean', description: 'Filter berdasarkan status aktif/nonaktif.', required: false, example: true)]
    #[Response(PsbAssessmentDocs::INDEX_SUCCESS, status: 200, description: 'Berhasil mengambil data master penilaian, dikelompokkan per proses & jenis')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function index(Request $request)
    {
        try {
            $groups = $this->psbAssessmentService->index($request->all());

            $data = $groups->map(function ($group) {
                return [
                    'assessment' => [
                        'id' => $group['assessment']->id,
                        'name' => $group['assessment']->name,
                    ],
                    'categories' => collect($group['categories'])->map(function ($category) {
                        return [
                            'category' => [
                                'id' => $category['category']->id,
                                'name' => $category['category']->name,
                            ],
                            'total_weight_active' => $category['total_weight_active'],
                            'items' => PsbAssessmentItemResource::collection($category['items']),
                        ];
                    }),
                ];
            });

            return $this->successResponse($data, 'Berhasil mengambil data master penilaian');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[UrlParam('id', 'string', description: 'UUID item penilaian.', example: '3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff')]
    #[Response(PsbAssessmentDocs::SHOW_SUCCESS, status: 200, description: 'Berhasil mengambil detail item penilaian')]
    #[Response(PsbAssessmentDocs::NOT_FOUND, status: 404, description: 'Item penilaian dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function show(string $id)
    {
        try {
            $data = $this->psbAssessmentService->show($id);

            return $this->successResponse(
                new PsbAssessmentDetailResource($data),
                'Berhasil mengambil detail item penilaian'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(PsbAssessmentDocs::STORE_SUCCESS, status: 201, description: 'Item penilaian berhasil dibuat')]
    #[Response(PsbAssessmentDocs::STORE_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field wajib kosong')]
    #[Response(PsbAssessmentDocs::STORE_OPERATION_REQUIRED, status: 422, description: 'Validasi gagal — operasi wajib diisi untuk Kalkulasi')]
    #[Response(PsbAssessmentDocs::STORE_MAPPING_MISMATCH, status: 422, description: 'Jumlah mapping tidak sesuai cara penilaian (Single=1, Kalkulasi=2)')]
    #[Response(PsbAssessmentDocs::STORE_COMPONENT_TYPE_INVALID, status: 422, description: 'Komponen yang dipilih tidak bertipe number/timestamp (untuk Kalkulasi)')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(PsbAssessmentDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
    public function store(StorePsbAssessmentRequest $request)
    {
        try {
            $data = $this->psbAssessmentService->store($request->validated());

            return $this->createdResponse(
                new PsbAssessmentDetailResource($data),
                'Item penilaian berhasil dibuat'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[UrlParam('id', 'string', description: 'UUID item penilaian yang akan diupdate.', example: '3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff')]
    #[Response(PsbAssessmentDocs::UPDATE_SUCCESS, status: 200, description: 'Item penilaian berhasil diupdate')]
    #[Response(PsbAssessmentDocs::STORE_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field wajib kosong')]
    #[Response(PsbAssessmentDocs::STORE_OPERATION_REQUIRED, status: 422, description: 'Validasi gagal — operasi wajib diisi untuk Kalkulasi')]
    #[Response(PsbAssessmentDocs::STORE_MAPPING_MISMATCH, status: 422, description: 'Jumlah mapping tidak sesuai cara penilaian (Single=1, Kalkulasi=2)')]
    #[Response(PsbAssessmentDocs::STORE_COMPONENT_TYPE_INVALID, status: 422, description: 'Komponen yang dipilih tidak bertipe number/timestamp (untuk Kalkulasi)')]
    #[Response(PsbAssessmentDocs::NOT_FOUND, status: 404, description: 'Item penilaian dengan id tersebut tidak ditemukan')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    #[Response(PsbAssessmentDocs::FORBIDDEN, status: 403, description: 'Role user tidak memiliki akses (bukan it/superadmin)')]
    public function update(UpdatePsbAssessmentRequest $request, string $id)
    {
        try {
            $data = $this->psbAssessmentService->update($id, $request->validated());

            return $this->successResponse(
                new PsbAssessmentDetailResource($data),
                'Item penilaian berhasil diupdate'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
