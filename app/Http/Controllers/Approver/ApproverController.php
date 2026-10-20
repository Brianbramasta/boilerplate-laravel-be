<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Approver\ApproveLeaveRequest;
use App\Http\Requests\Approver\RejectLeaveRequest;
use App\Http\Resources\Approver\LeaveRequestResource as ApproverLeaveRequestResource;
use App\Services\Employee\ApproverService;
use Illuminate\Http\Request;

class ApproverController extends BaseController
{
    protected $approverService;

    public function __construct(ApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    protected function ensureApprover()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['approver', 'hr', 'admin'])) {
            return $this->errorResponse('Anda tidak memiliki akses untuk menyetujui cuti.', 403);
        }
        return null;
    }

    public function leaveRequests(Request $request)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $status = $request->get('status');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $requests = $this->approverService->getLeaveRequestsByManager(
            auth()->user(),
            $status,
            $page,
            $perPage
        );

        return $this->successResponse(
            data: [
                'current_page' => $requests->currentPage(),
                'data' => ApproverLeaveRequestResource::collection($requests->items()),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ],
            message: 'Daftar pengajuan cuti bawahan berhasil diambil'
        );
    }

    public function approve(ApproveLeaveRequest $request, $id)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $result = $this->approverService->approve(
            $id,
            auth()->id(),
            $request->get('notes')
        );

        if (!$result) {
            return $this->errorResponse('Pengajuan tidak ditemukan, tidak dapat disetujui, atau saldo cuti tidak mencukupi.', 404);
        }

        return $this->successResponse(
            data: [
                'id' => $result->id,
                'status' => 'APPROVED',
            ],
            message: 'Pengajuan cuti berhasil disetujui'
        );
    }

    public function reject(RejectLeaveRequest $request, $id)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $result = $this->approverService->reject(
            $id,
            auth()->id(),
            $request->get('notes')
        );

        if (!$result) {
            return $this->errorResponse('Pengajuan tidak ditemukan atau tidak dapat ditolak.', 404);
        }

        return $this->successResponse(
            data: [
                'id' => $result->id,
                'status' => 'REJECTED',
            ],
            message: 'Pengajuan cuti berhasil ditolak'
        );
    }
}