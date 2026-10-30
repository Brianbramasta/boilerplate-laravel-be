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
- [[http]] - Cara Controller menggunakan Service melalui Provider
- [[interface]] - Kontrak yang di-binding di container
- [[models]] - Model yang di-instansiasi oleh Repository
- [[exceptions]] - Exception handler yang di-register
- [[helper]] - Helper class yang mungkin di-bind di container
- [[events]] - Event dispatcher service provider

## FAQ

#### Apakah padaproviders ini nantinya bisa lebih dari satu file? atau cukup AppServiceProvider?

Meskipun Laravel secara _default_ sudah menyediakan `AppServiceProvider` sebagai tempat utama, mengandalkan _hanya_ satu file tersebut untuk semua hal di aplikasi yang besar akan membuat file tersebut menjadi _fat file_ (menumpuk banyak kode dan sulit di-_maintenance_).

Berikut adalah penjelasan mengenai kapan dan bagaimana kamu bisa membuat _ServiceProvider_ tambahan:

### 1. Kapan harus membuat _ServiceProvider_ baru?

Kamu bisa membuat _Provider_ terpisah ketika:
  
- **Modul/Fitur sudah semakin kompleks:** Misalnya, kamu membuat modul khusus seperti _HRIS Module_, _Payment Gateway_, atau _Audit Trail_ yang masing-masing butuh _binding interface_ atau konfigurasi event tersendiri.
- **Memisahkan Tanggung Jawab (_Separation of Concerns_):**
    - `AppServiceProvider`: Hanya untuk _binding_ service/repository utama aplikasi secara general.
    - `RouteServiceProvider`: Untuk konfigurasi rute (meskipun di Laravel versi baru rute sudah dipisah langsung di folder `routes/`).
    - `EventServiceProvider`: Khusus untuk mendaftarkan _Event_ dan _Listener_.
    - `AuthServiceProvider`: Khusus untuk konfigurasi _Gate_ atau _Policies_ hak akses user.
### 2. Bagaimana cara membuat _ServiceProvider_ baru?

Kamu bisa memanfaatkan Artisan CLI bawaan Laravel untuk membuat file provider baru dengan cepat di folder `app/Providers/`:

``` bash
php artisan make:provider RepositoryServiceProvider
```

Setelah itu, kamu bisa memindahkan _binding_ repository yang sebelumnya ada di `AppServiceProvider` ke provider baru tersebut, contohnya:

``` php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AuthInterface;
use App\Repositories\AuthRepository;
use App\Interfaces\MasterAccountInterface;
use App\Repositories\MasterAccountRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(MasterAccountInterface::class, MasterAccountRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
```

### 3. Apakah perlu didaftarkan secara manual?

Jika kamu membuat _ServiceProvider_ baru, jangan lupa untuk mendaftarkannya ke dalam daftar _providers_ di file konfigurasi **`bootstrap/providers.php`** (atau `config/app.php` tergantung versi Laravel yang kamu gunakan) agar dibaca oleh sistem:

``` php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class, // Daftarkan di sini
];
```

**Kesimpulan:** Untuk project skala kecil atau menengah, cukup gunakan `AppServiceProvider`. Namun, untuk project besar seperti HRIS yang punya banyak modul, **sangat disarankan** memecahnya menjadi beberapa _ServiceProvider_ agar kodenya tetap modular dan rapi!
