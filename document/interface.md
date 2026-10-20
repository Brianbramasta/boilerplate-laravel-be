# Penjelasan Folder `app\Interface` untuk Fullstack Developer

Folder `Interface` berisi **kontrak (contract)** yang mendefinisikan method yang harus diimplementasikan oleh Repository. Ini adalah pola **Dependency Injection**.

---

## Kenapa Pakai Interface?

**Tanpa Interface (langsung new class):**
```php
class AuthController extends Controller
{
    private $repo;

    public function __construct()
    {
        $this->repo = new AuthRepository(); // Kaku, susah ganti
    }
}
```

**Dengan Interface:**
```php
class AuthController extends Controller
{
    public function __construct(private AuthInterface $authRepo)
    {
        // Laravel inject otomatis AuthRepository lewat container
    }
}
```

**Keuntungan:**
- Mudah ganti implementasi database
- Testing mock data
- Kode tidak terkait concrete class

---

## Struktur Folder

```
Interface/
├── Auth/
│   └── AuthInterface.php           → Login, register, JWT
├── Master/
│   ├── Account/
│   │   └── MasterAccountInterface.php
│   ├── PsbAssessment/
│   │   └── PsbAssessmentInterface.php
│   └── PsbVariable/
│       └── PsbWoStepInterface.php
├── Ref/
│   └── RefErpInterface.php
└── WorkOrder/
    ├── DashboardInterface.php      → Query dashboard
    ├── ValidationWoInterface.php
    └── WoInterface.php
```

---

## AuthInterface.php (Contoh Interface)

```php
interface AuthInterface
{
    public function register(array $data);
    public function findById(string $id);
    public function findByEmail(string $email);
    public function updatePassword(string $userId, string $password);
}
```

---

## DashboardInterface.php (Contoh Interface Kompleks)

```php
interface DashboardInterface
{
    public function getTotalWoMasuk(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);
    public function getWoMenungguValidasi(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);
    public function getDashboardData(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);
    public function getRankingKatim(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);
    // ... dst
}
```

---

## Cara Kerja: Service → Interface → Repository

1. **Service** tetap depend pada Interface:
   ```php
   class AuthService
   {
       public function __construct(protected AuthInterface $authInterface) {}
   }
   ```

2. **Provider** mengaitkan Interface ke Repository:
   ```php
   // app/Providers/AppServiceProvider.php
   $this->app->bind(AuthInterface::class, AuthRepository::class);
   ```

3. **Laravel Container** otomatis inject Repository:
   ```
   new AuthService(AuthRepository::class)
   ```

---

## Flow Dependency Injection

```
┌─────────────┐
│ Controller  │
│ (AuthController)
└──────┬──────┘
       │ pakein AuthService
       ▼
┌─────────────┐
│  Service    │
│ (AuthService)
└──────┬──────┘
       │ pakein AuthInterface
       ▼
┌─────────────┐     bind di AppServiceProvider
│   Interface │───────────────────────────────┐
│ (AuthInterface)                             │
└──────┬──────┘                               │
       │ implementasi oleh                  │
       ▼                                      │
┌─────────────┐                               │
│ Repository  │────────────────────────────────┘
│ (AuthRepository)
└─────────────┘
```

---

## Switching Driver (API vs JSON)

Contoh `PsbWoStepInterface` bisa pakai 2 implementasi:

```php
$this->app->bind(PsbWoStepInterface::class, function ($app) {
    return match (config('services.psb_wo_step.driver', 'json')) {
        'api' => $app->make(PsbIntegratedRepository::class),  // Data dari API eksternal
        default => $app->make(PsbJsonRepository::class),       // Data dari file JSON lokal
    };
});
```

---

## Tips untuk Frontend Developer

1. **Interface = Kontrak Backend** - Ini apa yang tersedia untuk dipanggil
2. **Repository = Implementasi** - Cara data diakses (MySQL, MongoDB, API)
3. **Provider = Konfigurasi Glue** - Hubungkan Interface ke Repository

---

## Navigasi Cepat ⟶
- [[services]] - Service yang pakai interface ini
- [[repositories]] - Concrete class yang implementasinya
- [[providers]] - Binding antara interface dan repository
- [[http]] - Controller yang pakai service yang pakai interface
- [[repositories]] - Concrete implementation yang dipanggil service
- [[services]] - Service yang depend pada interface
- [[model]] - Model yang diakses repository
- [[exceptions]] - Exception yang throw oleh repository/service