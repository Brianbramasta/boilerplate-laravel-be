<?php

namespace App\Http\Controllers\WorkOrder;

use App\Http\Controllers\BaseController;
use App\Http\Requests\WorkOrder\IndexWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreValidationWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreWorkOrderRequest;
use App\Http\Resources\WorkOrder\WoValidationResource;
use App\Services\WorkOrder\ValidationWoService;

class ValidationController extends BaseController
{
    public function __construct(protected ValidationWoService $validationWoService) {}

    public function index(IndexWorkOrderRequest $request)
    {
        try {
            $data = $this->validationWoService->index($request->all());
            return $this->successResponse($data, 'Berhasil mengambil data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function listStatus()
    {
        try {
            $data = $this->validationWoService->showStatuses();
            return $this->successResponse($data, 'Berhasil mengambil data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function listBranch()
    {
        try {
            $data = [
                [
                    "id" => 1,
                    "branch_name" => "Temas",
                    "kode_branch" => "G",
                    "address" => "Jl. Dewi Sartika I No.13, Temas, Kec. Batu, Kota Batu, Jawa Timur 65315",
                    "regional" => "Batu",
                    "company_name" => "GriyaNet"
                ],
                [
                    "id" => 7,
                    "branch_name" => "Pasuruan Selatan",
                    "kode_branch" => "PS",
                    "address" => "Jl. Panglima Sudirman No.90, Pasuruan, Jawa Timur",
                    "regional" => "Pasuruan",
                    "company_name" => "Dasarata"
                ]
            ];

            return $this->successResponse($data, 'Berhasil mengambil data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function show(string $id)
    {
        try {
            $data = $this->validationWoService->showWo($id);
            // return $this->successResponse(new WoValidationResource($data), 'Berhasil mengambil detail validasi WO');
            return $this->successResponse($data, 'Berhasil mengambil detail validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function store(StoreWorkOrderRequest $request)
    {
        try {
            $data = $this->validationWoService->storeWo($request);
            return $this->successResponse($data, 'Berhasil menyimpan data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function update(StoreValidationWorkOrderRequest $request, string $id)
    {
        try {
            $data = $this->validationWoService->updateWo($request->all(), $id);
            return $this->successResponse($data, 'Berhasil menyimpan data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
