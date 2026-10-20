# Penjelasan Folder `app\Services` untuk Fullstack Developer

Folder `Services` adalah **Business Logic Layer** - tempat logika bisnis berada. Service memutar antara Controller dan Repository.

---

## Kenapa Pakai Service?

Tanpa Service:
```php
// Controller langsung proses logika (BAD)
public function register(Request $request)
{
    $user = User::create([...]);
    $token = JWTAuth::fromUser($user);
    Cache::put('user_count', User::count(), 3600);
    return response()->json([...]);
}
```

Dengan Service:
```php
// Controller → Service → Repository
public function register(RegisterRequest $request)
{
    $result = $this->authService->register($request->validated());
    return $this->createdResponse($result);
}
```

**Keuntungan:**
- Controller tetap bersih/fokus handling request/response
- Logika bisnis tersembunyi di Service
- Service reusable oleh beberapa Controller
- Mudah test unit (mock Service)

---

## Struktur Folder

```
Services/
├── Auth/
│   └── AuthService.php                   → Login, register, JWT, role access
├── Dashboard/
│   └── DashboardService.php              → Query dashboard, format data
├── Master/
│   ├── Account/
│   │   └── MasterAccountService.php      → CRUD akun
│   ├── PsbAssessment/
│   │   └── PsbAssessmentService.php
│   └── PsbVariable/
│       └── PsbWoStepService.php
├── Ref/
│   └── RefService.php
└── WorkOrder/
    └── ValidationWoService.php           → Logika validasi work order
```

---

## AuthService.php (Contoh Service dengan Logika Kompleks)

Service yang paling kompleks karena mengurus JWT, role access, hash password:

```php
class AuthService
{
    public function __construct(protected AuthInterface $authInterface) {}

    public function login(array $credential)
    {
        // 1. Transaksi database
        return DB::transaction(function () use ($credential) {
            
            // 2. Coba login lewat JWT
            $token = JWTAuth::attempt($credential);
            if (!$token) {
                throw new HttpException(401, 'Email atau password salah');
            }

            // 3. Cek status akun aktif
            $user = JWTAuth::user();
            if (!$user->is_active) {
                JWTAuth::setToken($token)->invalidate();
                throw new HttpException(403, 'Akun tidak aktif');
            }

            // 4. Buat role + access mapping
            [$role, $access] = $this->resolveRoleAccess($user);

            return [
                'user' => $user,
                'role' => $role,
                'access' => $access,
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ];
        });
    }

    // Helper: mapping role dan menu access
    protected function resolveRoleAccess($user): array
    {
        $user->load('role.roleMenus.menu');

        $role = null;
        $access = [];

        if ($user->role) {
            $role = [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug,
            ];

            foreach ($user->role->roleMenus as $rm) {
                $access[$rm->menu->code] = [
                    'view' => $rm->can_view,
                    'create' => $rm->can_create,
                    'update' => $rm->can_update,
                    'delete' => $rm->can_delete,
                ];
            }
        }

        return [$role, $access];
    }

    public function changePassword(array $data): void
    {
        $user = JWTAuth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new HttpException(400, 'Password lama salah');
        }

        $this->authInterface->updatePassword(
            $user->id,
            Hash::make($data['password'])
        );
    }
}
```

---

## DashboardService.php (Contoh Service dengan Transformasi Data)

Service mensafort data mentah dari repository menjadi format yang diminta frontend:

```php
class DashboardService
{
    public function getClusterWorkOrder($startDate = null, $endDate = null, $branchId = null)
    {
        return [
            'total_wo_masuk' => $this->dashboardInterface->getTotalWoMasuk(...),
            'menunggu_validasi' => $this->dashboardInterface->getWoMenungguValidasi(...),
            'draft_validasi' => $this->dashboardInterface->getDraftValidasi(...),
        ];
    }

    public function getAverageQcScoreMatrix($startDate = null, $endDate = null, $branchId = null)
    {
        // Ambil data mentah
        $results = $this->dashboardInterface->getAverageQcScore(...);

        // Transformasi ke format matrix yang diminta frontend
        $matrix = [];
        foreach ($results as $row) {
            // ... proses data menjadi matrix
        }

        return $result;
    }
}
```

---

## MasterAccountService.php (Contoh Service Sederhana)

Service yang hanya memanggil repository:

```php
class MasterAccountService
{
    public function __construct(private MasterAccountInterface $masterAccountInterface) {}

    public function index($params = [], $perPage)
    {
        return $this->masterAccountInterface->index($params, $perPage);
    }

    public function store(array $data)
    {
        return $this->masterAccountInterface->store($data);
    }

    public function update(string $id, array $data)
    {
        return $this->masterAccountInterface->update($id, $data);
    }
}
```

---

## Flow Data dari Frontend ke Database

```
Controller
    ↓ (terima request, validasi)
Service (logika bisnis)
    ↓
Repository (query database)
    ↓
Database
```

---

## Peran Service vs Repository

| Layer | Tanggung Jawab | Contoh |
|-------|---------------|--------|
| **Controller** | Terima request, kirim response | Login, pagination |
| **Service** | Logika bisnis | Hitung score, buat JWT, mapping role |
| **Repository** | Query database | SELECT, INSERT, UPDATE, DELETE |

---

## Tips untuk Frontend Developer

1. **Service = Aturan Bisnis** - Semua aturan aplikasi ada di sini (misal: hitung discount, cek hak akses)
2. **Repository = Query Database** - Hanya ambil/berikan data
3. **Controller = Penghubung** - Ringkas, tidak bikin logika kompleks

---

## Contoh Response dari Service

Service biasanya mengembalikan:

```php
// Array data
[
    'user' => User object,
    'role' => ['id'=>1, 'name'=>'Admin'],
    'access' => ['master-account' => ['view'=>true, 'create'=>true]],
]

// atau hasil paginasi
LengthAwarePaginator object

// atau hasil transformasi
[
    ['category_name' => 'Kualitas', 'instalasi_odp' => 8.5, 'rata_rata' => 8.2],
]
```

---

## Navigasi Cepat ⟶
- [[repositories]] - Data yang di-query oleh Service
- [[providers]] - Cara Service & Repository "sambung" satu sama lain
- [[http]] - Controller yang memanggil Service
- [[models]] - Model yang diproses oleh Service
- [[interface]] - Interface yang dipakai Service
- [[exceptions]] - Exception yang dilempar Service
- [[helper]] - Helper yang dipakai Service untuk transformasi data