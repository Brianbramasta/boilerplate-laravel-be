# Fix: Update Penyesuiaan Repository Pattern - Karyawan

## Tanggal
20 Oktober 2026

## Waktu
15:10 - 15:30 WIB

## Ringkasan
Memperbaiki error `Call to a member function format() on string` dan meng-refactor `EmployeeController.php` serta `ApproverController.php` agar sesuai dengan pattern Repository (Service → Interface → Repository → Model → Database).

## Masalah yang Ditemukan

### 1. Error Runtime
```
{
  "meta": {
    "status": 500,
    "message": "Call to a member function format() on string"
  }
}
```

Penyebab: Laravel 9+ sudah tidak support properti `$dates` di Model. Dates harus di `$casts` dengan format `datetime`.

### 2. Arsitektur Controller
Beberapa controller belum mengikuti pattern:
- `EmployeeController.php` - masih query langsung ke database
- `ApproverController.php` - sudah perbaiki tapi belum pakai Service

## Solusi yang Diterapkan

### Perbaikan Model (Error Utama)
**File:** `app/Models/Employee/LeaveRequest.php`

```php
// sebelum
protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

// sesudah
protected $casts = [
    'total_days' => 'integer',
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

### Perbaikan Resource
**File:** `app/Http/Resources/Approver/LeaveRequestResource.php`
```php
// tambah null-safe operator
'start_date' => $this->start_date?->format('Y-m-d'),
'end_date' => $this->end_date?->format('Y-m-d'),
'created_at' => $this->created_at?->toIso8601String(),
```

**File:** `app/Http/Resources/Employee/LeaveRequestResource.php`
- Sama seperti di atas
- Tambah: `'leave_type_id'`, `'reason'`

### Refactor EmployeeController
**File:** `app/Http/Controllers/Employee/EmployeeController.php`

Sekarang pakai Service:
```php
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
        $leaveTypes = $this->leaveTypeService->getPaginated($perPage, $page);
        // pakai Resource
    }

    public function leaveBalances(Request $request)
    {
        $balances = $this->employeeService->getLeaveBalances($user->id, ...);
        // pakai Resource
    }

    public function leaveRequests(Request $request)
    {
        $requests = $this->employeeService->getLeaveRequestsByUser(...);
        // pakai Resource
    }

    public function createLeaveRequest(CreateLeaveRequest $request)
    {
        $leaveRequest = $this->employeeService->createLeaveRequest(...);
        return new LeaveRequestResource($leaveRequest); // pakai Resource
    }

    public function cancelLeaveRequest($id)
    {
        $result = $this->employeeService->cancelLeaveRequest($id, ...);
        // handle result
    }
}
```

### Service yang Diperluas

**File:** `app/Services/Employee/EmployeeService.php`
- `getLeaveRequestsByUser($userId, $status, $perPage, $page)`
- `getLeaveBalances($userId, $perPage, $page)`
- `cancelLeaveRequest($id, $userId)`

**File:** `app/Services/Employee/LeaveTypeService.php` (baru)
- `getPaginated($perPage, $page)`

### Interface & Repository

**Interface:**
- `app/Contracts/Employee/LeaveRequestInterface.php`
- `app/Contracts/Employee/LeaveBalanceInterface.php`
- `app/Contracts/Employee/ApprovalHistoryInterface.php`
- `app/Contracts/Employee/LeaveTypeInterface.php`

**Repository:**
- `app/Repositories/Employee/LeaveRequestRepository.php`
- `app/Repositories/Employee/LeaveBalanceRepository.php`
- `app/Repositories/Employee/ApprovalHistoryRepository.php`
- `app/Repositories/Employee/LeaveTypeRepository.php`

### Provider Binding
**File:** `app/Providers/AppServiceProvider.php`

```php
$this->app->bind(LeaveRequestInterface::class, LeaveRequestRepository::class);
$this->app->bind(LeaveBalanceInterface::class, LeaveBalanceRepository::class);
$this->app->bind(ApprovalHistoryInterface::class, ApprovalHistoryRepository::class);
$this->app->bind(LeaveTypeInterface::class, LeaveTypeRepository::class);
```

## Flow yang Benar
```
Controller → Service → Interface → Repository → Model → Database
```

## File yang Dibuat
1. `app/Contracts/Employee/LeaveTypeInterface.php`
2. `app/Repositories/Employee/LeaveTypeRepository.php`
3. `app/Services/Employee/LeaveTypeService.php`
4. `app/Http/Services/Employee/ApproverService.php` (baru)

## File yang Diubah
1. `app/Models/Employee/LeaveRequest.php`
2. `app/Services/Employee/EmployeeService.php`
3. `app/Http/Controllers/Employee/EmployeeController.php`
4. `app/Http/Controllers/Approver/ApproverController.php`
5. `app/Providers/AppServiceProvider.php`
6. `app/Http/Resources/Employee/LeaveRequestResource.php`

## Keuntungan
1. Error `format() on string` sudah teratasi
2. Semua controller pakai Service → Repository pattern
3. Dependency Injection yang benar
4. Pagination server-side lebih efisien
5. Kode lebih testable (mock repository)
6. Konsisten dengan arsitektur yang ditentukan

## Checklist untuk Junior
- [x] Ganti `$dates` ke `$casts` di semua model yang punya datetime
- [x] Tambah `?->` di semua resource yang panggil method datetime
- [x] Pagination di repository, bukan controller
- [x] Service butuh Interface bukan concrete class
- [x] Controller hanya "transport" request/response
- [x] Semua endpoint pakai Resource untuk response