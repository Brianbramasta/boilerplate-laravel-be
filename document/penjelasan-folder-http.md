# Penjelasan Folder `app\Http` untuk Fullstack Developer

Folder `app\Http` di Laravel berisi komponen-komponen yang berhubungan langsung dengan menangani request HTTP dari frontend. Berikut penjelasan tiap foldernya:

---

## 1. Controllers

### Fungsi Utama
Controller adalah "pokok utama" yang menangani request masuk. Mereka menerima request dari pengguna, memproses data, lalu mengirimkan response kembali ke frontend.

### Struktur Folder

```
Controllers/
├── Controller.php          → Base controller dasar (abstrak)
├── BaseController.php      → Base controller yang sudah punya trait ApiResponse
├── Auth/
│   └── AuthController.php → Endpoint untuk login, register, register ulang
├── Dashboard/
│   └── DashboardController.php → Endpoint untuk data dashboard (grafik, ringkasan)
├── Master/
│   ├── Account/
│   │   └── MasterAccountController.php → CRUD untuk akun pengguna
│   ├── Dropdown/
│   │   └── MasterDropdownController.php → Data dropdown (opsipilihan)
│   ├── PsbAssessment/
│   │   └── PsbAssessmentController.php → CRUD untuk penilaian PSB
│   └── PsbVariable/
│       └── PsbWoStepController.php → Langkah-langkah penilaian
├── Ref/
│   └── RefController.php → Referensi data statis
└── WorkOrder/
    └── ValidationController.php → Endpoint untuk validasi work order
```

### Contoh Kode Controller
```php
class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        // Proses login...
        return $this->successResponse($data, 'Berhasil login');
    }
}
```

---

## 2. Middleware

### Fungsi Utama
Middleware adalah lapisan yang memantau semua request sebelum sampai controller. Ini berguna untuk:
- **Autentikasi**: Pastikan user sudah login (JWT)
- **Otorisasi**: Pastikan user punya hak akses yang sesuai
- **API Key**: Validasi API key untuk service-to-service

### File yang Ada

| File | Fungsi |
|------|--------|
| `JwtMiddleware.php` | Cek token JWT, setujuk user sudah autentikasi |
| `RoleMiddleware.php` | Cek role user (admin, user, dll) |
| `ApiKeyMiddleware.php` | Validasi API key eksternal |
| `OptionalJwtMiddleware.php` | JWT opsional (bisa lewat atau tidak) |

### Contoh Penggunaan di Controller
```php
#[Middleware('auth:api')]  // Pakai JWT
#[Middleware(['role:admin'])]  // Hanya admin yang boleh akses
public function adminOnly()
{
    return $this->successResponse($data);
}
```

---

## 3. Requests

### Fungsi Utama
Request class handles validasi data yang datang dari frontend. Ini memisahkan logika validasi dari controller sehingga kode lebih bersih.

### Struktur
```
Requests/
├── BaseRequest.php          → Base class dengan format response error standard
├── Auth/
│   ├── LoginRequest.php     → Validasi email & password saat login
│   ├── RegisterRequest.php  → Validasi nama, email, password saat daftar
│   └── ChangePasswordRequest.php → Validasi password lama & baru
├── Dashboard/
│   └── DashboardRequest.php → Validasi parameter tanggal, branch_id
├── Master/
│   ├── Account/
│   │   ├── StoreMasterAccountRequest.php
│   │   └── UpdateMasterAccountRequest.php
│   ├── PsbAssessment/
│   │   ├── StorePsbAssessmentRequest.php
│   │   └── UpdatePsbAssessmentRequest.php
│   └── PsbVariable/
│       └── UpdatePsbWoStepRequest.php
└── WorkOrder/
    ├── IndexWorkOrderRequest.php
    ├── StoreWorkOrderRequest.php
    └── UpdateStatusValidationWorkOrderRequest.php
```

### Contoh Request
```php
class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
```

Di controller:
```php
public function login(LoginRequest $request)
{
    $data = $request->validated(); // Data sudah otomatis tervalidasi
    // ...
}
```

---

## 4. Resources

### Fungsi Utama
Resource class mengubah data dari database/model menjadi format JSON yang konsisten untuk dikirim ke frontend. Ini memberi kontrol penuh atas apa yang akan dikirim.

### Contoh Response JSON
Tanpa Resource:
```json
{
    "id": 1,
    "name": "John",
    "password": "secret123",  // Raw password tetap ada!
    "created_at": "2024-01-01 10:00:00"
}
```

Dengan Resource:
```php
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
        // password tidak dikirim!
    }
}
```

### Struktur
```
Resources/
├── Auth/
│   └── UserResource.php                  → Format data user
├── Master/
│   ├── Account/
│   │   ├── IndexMasterAccountResource.php → Daftar akun (ringkas)
│   │   └── DetailMasterAccountResource.php → Detail akun (lengkap)
│   ├── Dropdown/
│   │   ├── AssessmentCategoryOptionResource.php
│   │   └── AssessmentOptionResource.php
│   ├── PsbAssessment/
│   │   ├── PsbAssessmentDetailResource.php
│   │   └── PsbAssessmentItemResource.php
│   └── PsbVariable/
│       ├── IndexPsbWoStepResource.php
│       └── DetailPsbWoStepResource.php
├── Ref/
│   ├── CalculationOperationResource.php
│   └── CriteriaTypeResource.php
└── WorkOrder/
    ├── ListWoValidationResource.php
    └── WoValidationResource.php
```

---

## 5. Docs

### Fungsi Utama
Folder `Docs` berisi konstanta-konstanta JSON contoh response untuk dokumentasi API (menggunakan library Scribe). Ini membantu frontend mengerti format response apa yang akan diterima.

### Contoh
```php
class AuthDocs
{
    public const LOGIN_SUCCESS = <<<'JSON'
    {
        "meta": {
            "status": 200,
            "message": "User berhasil login"
        },
        "data": {
            "user": {...},
            "token": "..."
        }
    }
    JSON;
}
```

Di controller dipakai seperti:
```php
use Knuckles\Scribe\Attributes\Response;

#[Response(AuthDocs::LOGIN_SUCCESS, status: 200)]
public function login() { ... }
```

---

## 6. Traits

### ApiResponse.php (Wajib Diketahil)

Trait `ApiResponse` memberikan method helper untuk membuat response standar:

| Method | Status Code | Fungsi |
|--------|-------------|--------|
| `successResponse($data, $message)` | 200 | Response berhasil |
| `createdResponse($data, $message)` | 201 | Resource baru dibuat |
| `failResponse($message, $code)` | 400 | Response error umum |
| `unauthorizedResponse()` | 401 | Token tidak valid |
| `forbiddenResponse()` | 403 | Akses ditolak |
| `notFoundResponse()` | 404 | Data tidak ditemukan |
| `unprocessableResponse($errors)` | 422 | Validasi gagal |
| `serverErrorResponse()` | 500 | Error server |
| `paginatedResponse($data)` | 200 | Response hasil paginasi |

### Contoh Penggunaan di Controller
```php
use App\Http\Traits\ApiResponse;

class MyController extends BaseController
{
    use ApiResponse;

    public function index()
    {
        $users = User::all();
        return $this->successResponse($users, 'Berhasil ambil data user');
    }
}
```

---

## Alur Request dari Frontend ke Backend

```
Frontend (React/Vue)
    ↓ (fetch/axios dengan token)
Middleware (JWT Auth → Role Check)
    ↓
Controller (menerima request, panggil service)
    ↓
Service (logika bisnis)
    ↓
Model (query database)
    ↓
Resource (format data JSON)
    ↓
Response ke Frontend
```

### Timeline Request:
1. Frontend kirim request dengan token di header
2. Middleware cek token JWT valid atau tidak
3. Middleware cek role user sesuai permission endpoint
4. Controller terima request, panggil service
5. Service proses data, kembalikan hasil
6. Controller bungkus hasil pakai Resource + ApiResponse
7. Response JSON dikirim ke frontend

---

## Format Response Standar

Semua response mengikuti format ini:

```json
{
    "meta": {
        "status": 200,
        "message": "Pesan sukses/error"
    },
    "data": {...},  // null jika error
    "errors": {...}  // hanya ada saat validasi gagal (422)
}
```

Ini membuat frontend mudah menerima dan memproses semua response secara konsisten.

---

## Navigasi Cepat ⟶
**Layer Architecture:**
- [[services]] - Business logic (Auth, Dashboard, CRUD)
- [[repositories]] - Data access layer
- [[providers]] - Dependency injection config
- [[models]] - Eloquent models
- [[interface]] - Contract interfaces
- [[exceptions]] - Error handling