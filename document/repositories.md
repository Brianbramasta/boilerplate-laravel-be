# Penjelasan Folder `app\Repositories` untuk Fullstack Developer

Folder `Repositories` adalah lapisan **data access layer** yang bertanggung jawab untuk berkomunikasi dengan database. Repository memisahkan logika akses data dari service.

---

## Kenapa Pakai Repository?

Tanpa Repository:
```php
// Controller langsung ambil data dari database (BAD)
$users = User::with('role')->where('status', 'active')->get();
```

Dengan Repository:
```php
// Service → Repository → Database
$users = $this->accountRepository->index($params, 10);
```

**Keuntungan:**
- Controller/Service tidak perlu tahu query SQL apa
- Mudah ganti database (misal: ke MongoDB nanti)
- Test unit memudahkan (mock repository)

---

## Struktur Folder

```
Repositories/
├── Auth/
│   └── AuthRepository.php           → Login, register, ganti password
├── Dashboard/
│   └── DashboardRepository.php      → Query untuk dashboard (grafik)
├── Employee/
│   └── ...                          → Data karyawan
├── Master/
│   ├── Account/
│   │   └── MasterAccountRepository.php  → CRUD akun, pagination
│   ├── PsbAssessment/
│   │   └── PsbAssessmentRepository.php  → Data penilaian PSB
│   └── PsbVariable/
│       ├── PsbIntegratedRepository.php
│       ├── PsbJsonRepository.php
│       └── Concerns/
│           └── MergesPsbStepComponents.php
├── Ref/
│   ├── RefIntegratedRepository.php  → Ref data terintegrasi
│   └── RefJsonRepository.php        → Ref data dari file JSON
└── WorkOrder/
    ├── WoRepository.php
    └── ValidationWoRepository.php   → Validasi work order
```

---

## Contoh Penggunaan

### AuthRepository.php
```php
class AuthRepository implements AuthInterface
{
    public function register(array $data)
    {
        // Hash password otomatis
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Buat token JWT
        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
}
```

### MasterAccountRepository.php (CRUD & Pagination)
```php
class MasterAccountRepository implements MasterAccountInterface
{
    public function index($params = [], $perPage)
    {
        $query = User::with('role')
            ->whereHas('role', function ($q) {
                $q->where('is_system', false);  // Hindari akun system
            })
            ->when(isset($params['search']), function ($q) use ($params) {
                $search = $params['search'];
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            });

        return $query->paginate($perPage ?: 10);
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function show(string $id)
    {
        $user = User::with('role')->find($id);
        if (!$user) {
            throw new HttpException(404, 'Account tidak ditemukan');
        }
        return $user;
    }

    public function update(string $id, array $data)
    {
        $user = User::find($id);
        if (!$user) {
            throw new HttpException(404, 'Account tidak ditemukan');
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user->fresh('role');
    }
}
```

### DashboardRepository.php (Query Kompleks)
```php
class DashboardRepository implements DashboardInterface
{
    public function getTotalWoMasuk($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', '!=', 'COMPLETE');

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->count();
    }
}
```

---

## Flow Data di Backend

```
Controller
    ↓
Service (logika bisnis)
    ↓
Repository (query database)
    ↓
Model ( Eloquent )
    ↓
Database
```

---

## Tips untuk Frontend Developer
1. **Jangan panggil database langsung** - selalu lewat service → repository
2. **Pagination** - gunakan `$perPage` di repository, hasilnya `LengthAwarePaginator`
3. **Eager Loading** - pakai `with('relation')` agar data lengkap
4. **Error Handling** - repository lempar exception, controller tangani di catch

---

## Interface (Opsional)

Setiap repository biasanya punya interface-nya di `app\Interface`:
```php
interface AuthInterface
{
    public function register(array $data);
    public function findByEmail(string $email);
    public function findById(string $id);
}
```

**Mengapa interface?** Service bisa depend pada interface, bukan concrete class. Ini memudahkan:
- Ganti implementasi tanpa ubah service
- Testing dengan mock object

---

## Navigasi Cepat ⟶
- [[services]] - Cara Service memakai Repository
- [[providers]] - Cara Repository diregister di container
- [[penjelasan-folder-http]] - Ringkasan semua komponen HTTP layer