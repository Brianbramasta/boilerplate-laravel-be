<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Employee\CreateLeaveRequest;
use App\Http\Resources\Employee\LeaveBalanceResource;
use App\Http\Resources\Employee\LeaveRequestResource;
use App\Http\Resources\Employee\LeaveTypeResource;
use App\Models\Employee\LeaveRequest;
use App\Services\Employee\EmployeeService;
use App\Services\Employee\LeaveTypeService;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    protected $employeeService;
    protected $leaveTypeService;

    public function __construct(EmployeeService $employeeService, LeaveTypeService $leaveTypeService)
    {
        $this->employeeService = $employeeService;
        $this->leaveTypeService = $leaveTypeService;
    }

    public function leaveTypes(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $leaveTypes = $this->leaveTypeService->getPaginated($perPage, $page);

        return $this->successResponse(
            data: [
                'current_page' => $leaveTypes->currentPage(),
                'data' => LeaveTypeResource::collection($leaveTypes->items()),
                'per_page' => $leaveTypes->perPage(),
                'to' => $leaveTypes->lastItem(),
                'total' => $leaveTypes->total(),
            ],
            message: 'Daftar jenis cuti berhasil diambil',
            code: 200
        );
    }

    public function leaveBalances(Request $request)
    {
        $user = auth()->user();

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $balances = $this->employeeService->getLeaveBalances($user->id, $perPage, $page);

        return $this->successResponse(
            data: [
                'current_page' => $balances->currentPage(),
                'data' => LeaveBalanceResource::collection($balances->items()),
                'per_page' => $balances->perPage(),
                'to' => $balances->lastItem(),
                'total' => $balances->total(),
            ],
            message: 'Saldo cuti berhasil diambil',
            code: 200
        );
    }

    public function leaveRequests(Request $request)
    {
        $user = auth()->user();

        $status = $request->get('status');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $requests = $this->employeeService->getLeaveRequestsByUser($user->id, $status, $page, $perPage);

        return $this->successResponse(
            data: [
                'current_page' => $requests->currentPage(),
                'data' => LeaveRequestResource::collection($requests->items()),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ],
            message: 'Daftar pengajuan cuti berhasil diambil',
            code: 200
        );
    }

    public function createLeaveRequest(CreateLeaveRequest $request)
    {
        $leaveRequest = $this->employeeService->createLeaveRequest(
            $request->validated(),
            auth()->id()
        );

        return $this->successResponse(
            data: new LeaveRequestResource($leaveRequest),
            message: 'Pengajuan cuti berhasil disubmit',
            code: 201
        );
    }

    public function cancelLeaveRequest($id)
    {
        $user = auth()->user();

        $result = $this->employeeService->cancelLeaveRequest($id, $user->id);

        if ($result === null) {
            return $this->errorResponse('Data pengajuan cuti tidak ditemukan.', 404);
        }

        if ($result === false) {
            return $this->errorResponse('Pengajuan tidak dapat dibatalkan karena sudah disetujui atau ditolak.', 400);
        }

        return $this->successResponse(
            data: [
                'id' => $result->id,
                'status' => $result->status,
            ],
            message: 'Pengajuan cuti berhasil dibatalkan',
            code: 200
        );
    }
}