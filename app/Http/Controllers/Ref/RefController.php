<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\RefDocs;
use App\Http\Resources\Ref\CalculationOperationResource;
use App\Http\Resources\Ref\CriteriaTypeResource;
use App\Models\Ref\CalculationOperation;
use App\Models\Ref\CriteriaType;
use App\Services\Ref\RefService;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Ref', 'Endpoint baca data referensi/lookup (dipakai lintas fitur, mis. dropdown pembuatan master penilaian).')]
class RefController extends BaseController
{
    #[Response(RefDocs::CRITERIA_TYPES_SUCCESS, status: 200, description: 'Berhasil mengambil data criteria types')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function criteriaTypes()
    {
        try {
            $data = CriteriaType::where('is_active', true)->orderBy('name')->get();

            return $this->successResponse(
                CriteriaTypeResource::collection($data),
                'Berhasil mengambil data criteria types'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(RefDocs::CALCULATION_OPERATIONS_SUCCESS, status: 200, description: 'Berhasil mengambil data calculation operations')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function calculationOperations()
    {
        try {
            $data = CalculationOperation::where('is_active', true)->orderBy('name')->get();

            return $this->successResponse(
                CalculationOperationResource::collection($data),
                'Berhasil mengambil data calculation operations'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    protected RefService $refService;

    public function __construct(RefService $refService)
    {
        $this->refService = $refService;
    }

    #[Response(RefDocs::BRANCHES_SUCCESS, status: 200, description: 'Berhasil mengambil data branch')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function listBranch()
    {
        try {
            $data = $this->refService->getBranches();

            return $this->successResponse($data, 'Berhasil mengambil data branch');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(RefDocs::KATIMS_SUCCESS, status: 200, description: 'Berhasil mengambil data katim')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function listKatim()
    {
        try {
            $data = $this->refService->getKatims();

            return $this->successResponse($data, 'Berhasil mengambil data katim');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
