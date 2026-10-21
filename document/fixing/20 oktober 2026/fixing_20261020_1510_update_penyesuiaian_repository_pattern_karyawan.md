# Fix: Update Penyesuiaan Repository Pattern - Karyawan

## Tanggal
20 Oktober 2026

## Waktu
15:10 - 15:30 WIB

## Ringkasan
Memperbaiki error `Call to a member function format() on string` dan meng-refactor `EmployeeController.php` serta `ApproverController.php` agar sesuai dengan pattern Repository.

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

Penyebab: Laravel 9+ sudah tidak support `$dates` property di Model.

### 2. Arsitektur Controller
Beberapa controller belum pakai pattern Service → Repository.

## Solusi

### 1. Fix Model
`app/Models/Employee/LeaveRequest.php`:
```php
protected $casts = [
    'total_days' => 'integer',
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

### 2. Resource Pakai Null-Safe
- `LeaveRequestResource.php` (Approver & Employee)
- Tambah: `'leave_type_id'`, `'reason'`

### 3. Buat Service & Repository

**Service Baru:**
- `app/Services/Employee/LeaveTypeService.php`

**Repository Baru:**
- `app/Repositories/Employee/LeaveTypeRepository.php`

### 4. Update Controller
`EmployeeController.php` sekarang pakai Service + Resource.

## Flow yang Benar
```
Controller → Service → Interface → Repository → Model → Database
```

## File yang Dibuat
1. `app/Contracts/Employee/LeaveTypeInterface.php`
2. `app/Repositories/Employee/LeaveTypeRepository.php`
3. `app/Services/Employee/LeaveTypeService.php`
4. `app/Services/Employee/ApproverService.php`

## File yang Diubah
1. `app/Models/Employee/LeaveRequest.php`
2. `app/Services/Employee/EmployeeService.php`
3. `app/Http/Controllers/Employee/EmployeeController.php`
4. `app/Http/Controllers/Approver/ApproverController.php`
5. `app/Providers/AppServiceProvider.php`
6. `app/Http/Resources/Employee/LeaveRequestResource.php`