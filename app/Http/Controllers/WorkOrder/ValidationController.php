<?php

namespace App\Http\Controllers\WorkOrder;

use App\Http\Controllers\BaseController;
use App\Http\Requests\WorkOrder\IndexWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreValidationWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreWorkOrderRequest;
use App\Http\Requests\WorkOrder\UpdateStatusValidationWorkOrderRequest;
use App\Http\Resources\WorkOrder\ListWoValidationResource;
use App\Services\WorkOrder\ValidationWoService;

class ValidationController extends BaseController
{
    public function __construct(protected ValidationWoService $validationWoService) {}

    public function index(IndexWorkOrderRequest $request)
    {
        try {
            $data = $this->validationWoService->index($request->all());
            // return $this->successResponse($data, 'Berhasil mengambil data validasi WO');
            return $this->successResponse(new ListWoValidationResource($data), 'Berhasil mengambil data validasi WO');
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

    public function show(string $id)
    {
        try {
            $data = $this->validationWoService->showWo($id);
            // var_dump($data);
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

    public function updateOnProgress(UpdateStatusValidationWorkOrderRequest $request, string $id)
    {
        try {
            $param = $request->all();
            $data = $this->validationWoService->updateStatusProgress($id, $param['status']);
            return $this->successResponse($data, 'Berhasil update status progress validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function getWoNotComplete()
    {
        try {
            $data = $this->validationWoService->getWoNotComplete();
            return $this->successResponse($data, 'Berhasil update status progress validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
