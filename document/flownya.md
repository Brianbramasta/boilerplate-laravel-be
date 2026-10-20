
## Flow Data Backend ( dari Database ke Frontend)

### ✅ **Benar sebagian** - tapi kurang tepat:

### Alur yang BENAR:

```
Frontend
    ↓ (POST/GET dengan token)
Middleware (JWT Auth → Role Check)
    ↓
Controller (Terima request, validasi data)
    ↓
Service (Logika bisnis, transformasi data)
    ↓
Repository (Query ke Database)
    ↓
Model (Eloquent - representasi tabel)
    ↓
Database
    ↓ (kembali naik)
Repository → Service → Controller → Resource → Frontend
```

### Penjelasan Detail:

**1. Request Flow (Frontend → Database):**
- **Controller** menerima request, memanggil **Service**
- **Service** memanggil **Repository** untuk ambil data
- **Repository** pakai **Model** untuk query database

**2. Response Flow (Database → Frontend):**
- **Repository** kembalikan data mentah (Collection/Model/array)
- **Service** transformasi data (hitung, grouping, mapping)
- **Controller** bungkus dengan **Resource** + **ApiResponse trait**
- **Resource** format data JSON yang rapi (sembunyikan field sensitif)
- Kirim response ke frontend

**3. Validasi Data:**
- **Request** bukan di Controller langsung - dipisah ke class Request
- Controller menerima object Request yang sudah tervalidasi

**Tidak ada proses "mapping di controller"** - controller hanya:
1. Terima request
2. Panggil service
3. Terima hasil service
4. Bungkus dengan resource

---

Contoh spesifik:
```php
// Controller
public function show(ShowUserRequest $request, string $id)
{
    $data = $this->userService->findById($id);
    return $this->successResponse(new UserResource($data));
}

// Service  
public function findById(string $id)
{
    $user = $this->authInterface->findById($id); // Repository
    // transformasi / logika bisnis
    return $user;
}

// Repository
public function findById(string $id)
{
    return User::find($id); // Model query database
}

Jadi: Database → Repository → Service → Controller → Resource → Frontend
```


Fe -> api -> controller -> validation (request) -> service -> interface -> repo ->service -> controller -> resource-> traits (opsional)->return data yang sudah di mappaing