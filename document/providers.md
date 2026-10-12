# Penjelasan Folder `app\Providers` untuk Fullstack Developer

Folder `Providers` adalah lapisan **Dependency Injection** Laravel. Provider mengatur cara object-object dibuat dan dihubungkan satu sama lain.

---

## Kenapa Pakai Service Provider?

Tanpa Provider (langsung `new Class()`):
```php
class LoginController extends Controller
{
    private $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository(); // Kaku, susah ganti
    }
}
```

Dengan Provider:
```php
class LoginController extends Controller
{
    public function __construct(AuthInterface $authRepo)
    {
        $this->authRepository = $authRepo; // Fleksibel, bisa diganti lewat Provider
    }
}
```

**Keuntungan:**
- Kode tidak terkait concrete class
- Mudah ganti implementasi (misal: ganti database)
- Mocking data untuk testing

---

## AppServiceProvider.php

File utama yang mengatur semua dependency binding:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Exception Handler (untuk error API)
        $this->app->singleton('api.exception', function () {
            return new ApiExceptionHandler();
        });

        // 2. Interface → Repository Binding
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(MasterAccountInterface::class, MasterAccountRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        // ... dst
    }

    public function boot(): void
    {
        // 3. Load migration dari folder terpisah
        $this->loadMigrationsFrom([
            database_path('migrations/audit'),
            database_path('migrations/role_permission'),
            database_path('migrations/master'),
            // ... dst
        ]);
    }
}
```

---

## Binding Interface → Repository

Pattern utama: **Interface** → **Concrete Class**

| Interface | Concrete Class | Fungsi |
|-----------|---------------|--------|
| `AuthInterface` | `AuthRepository` | Login, register, JWT |
| `MasterAccountInterface` | `MasterAccountRepository` | CRUD akun |
| `DashboardInterface` | `DashboardRepository` | Query dashboard |
| `WoInterface` | `WoRepository` | Work Order |

---

## Binding dinamis (Switch Driver)

Contoh `PsbWoStepInterface` bisa pakai 2 jenis implementasi:

```php
$this->app->bind(PsbWoStepInterface::class, function ($app) {
    return match (config('services.psb_wo_step.driver', 'json')) {
        'api' => $app->make(PsbIntegratedRepository::class),  // API eksternal
        default => $app->make(PsbJsonRepository::class),       // JSON lokal (default)
    };
});
```

**Config di `config/services.php`:**
```php
'psb_wo_step' => [
    'driver' => env('PSB_WO_STEP_DRIVER', 'json'),  // 'api' atau 'json'
    // ... api url, dll
],
```

---

## Cara Kerja di Runtime

### Saat Request Masuk:
1. Laravel buat object Controller via resolver
2. Controller minta `AuthInterface $authRepo` lewat constructor
3. Laravel cari di container: `AuthInterface` → `AuthRepository`
4. Laravel buat object `AuthRepository`, inject ke Controller
5. Controller panggil `$authRepo->register()`

### Contoh Controller:
```php
class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService)
    {
        // Di container: AuthService → AuthInterface → AuthRepository
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        return $this->successResponse($result);
    }
}
```

---

## Konfigurasi di .env

Untuk switch driver:

```env
PSB_WO_STEP_DRIVER=api
# atau
PSB_WO_STEP_DRIVER=json
```

---

## Tips untuk Frontend Developer

1. **Service Provider = Konfigurasi Backend** - Ini tempat backend mengatur "glue" antar komponen
2. **Interface = Kontrak** - Frontend tidak perlu tahu concrete class mana yang dipakai
3. **Singleton** - Digunakan untuk object yang hanya dibuat sekali (seperti exception handler)
4. **Binding** - seperti `map()` atau `injectable` di NestJS, Spring Bean Definitions

---

## Struktur Umum Provider

```php
class SomeServiceProvider extends ServiceProvider
{
    // Daftar class yang perlu dibuat
    public function register()
    {
        $this->app->bind(InterfaceA::class, ImplementationA::class);
        $this->app->bind(InterfaceB::class, function($app) {
            return new ImplementationB('config-value');
        });
        $this->app->singleton(SingletonClass::class, function() {
            return new SingletonClass();
        });
    }

    // Konfigurasi tambahan setelah semua service didaftarkan
    public function boot()
    {
        // Publish config
        // Load migrations
        // Register routes
        // Boot traits/libraries
    }
}
```

---

## Navigasi Cepat ⟶
- [[repositories]] - Class yang diregister di Provider
- [[services]] - Service yang menggunakan Repository lewat DI
- [[penjelasan-folder-http]] - Cara Controller menggunakan Service melalui Provider