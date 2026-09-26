### 1. Login

- **URL:** `/api/v1/auth/login`
- **Method:** `POST`

**Request Body:**

JSON

```json
{
  "email": "employee@dasaria.com",
  "password": "password123"
}
```

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Login berhasil"
  },
  "data": {
    "token": "1|LaravelSanctumTokenExample...",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "employee@dasaria.com",
      "role": "employee"
    }
  }
}
```

### 2. Get Profile (Me)

- **URL:** `/api/v1/auth/me`
- **Method:** `GET`
- **Headers:** `Authorization: Bearer {token}`

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Profil berhasil diambil"
  },
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "employee@dasaria.com",
    "role": "employee",
    "manager_id": 5,
    "created_at": "2026-09-10T10:00:00Z"
  }
}
```

### 3. Logout

- **URL:** `/api/v1/auth/logout`
- **Method:** `POST`
- **Headers:** `Authorization: Bearer {token}`

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Logout berhasil"
  },
  "data": null
}
```

### *Tambahan: Contoh Format Error (Bad Request / Validation Error)*

Jika terjadi *error* (misalnya validasi gagal), strukturnya akan tetap konsisten. Biasanya, detail *error* diletakkan di dalam objek `data` atau ditambahkan properti `errors`.

**Contoh Error Response (422 Unprocessable Entity) saat Login:**

JSON

```json
{
  "meta": {
    "status": 422,
    "message": "Validasi gagal"
  },
  "data": {
    "email": [
      "Format email tidak valid.",
      "Email wajib diisi."
    ]
  }
}
```

- negative flow
    
    **1. Login (`POST /api/v1/auth/login`)**
    
    *Negative flow* pada endpoint ini mencakup kesalahan validasi input, kredensial yang tidak cocok, hingga batasan jumlah percobaan (*Rate Limiting*) sesuai standar keamanan.
    
    **Error: Validasi Gagal (422 Unprocessable Entity)**
    Terjadi jika *request body* kosong, format email salah, atau syarat input tidak terpenuhi.
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 422,
        "message": "Validasi gagal"
      },
      "data": {
        "email": [
          "Email wajib diisi.",
          "Format email tidak valid."
        ],
        "password": [
          "Password wajib diisi."
        ]
      }
    }
    ```
    
    **Error: Kredensial Salah (401 Unauthorized)**
    
    Terjadi jika kombinasi email dan password tidak ditemukan di database.
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 401,
        "message": "Email atau password salah"
      },
      "data": null
    }
    ```
    
    **Error: Terlalu Banyak Percobaan (429 Too Many Requests)**
    Terjadi jika user mencoba login gagal berkali-kali dalam waktu singkat (pencegahan *brute-force*).
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 429,
        "message": "Terlalu banyak percobaan login. Silakan coba lagi dalam 60 detik."
      },
      "data": null
    }
    ```
    
    **2. Get Profile (`GET /api/v1/auth/me`)**
    
    Skenario gagal pada pengambilan profil difokuskan pada validasi token akses.
    
    **Error: Tidak Ada Akses / Token Tidak Valid (401 Unauthorized)**
    
    Terjadi jika *header* `Authorization` tidak dikirim, token sudah *expired*, atau token salah.
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 401,
        "message": "Unauthenticated. Token tidak valid atau tidak ditemukan."
      },
      "data": null
    }
    ```
    
    **3. Logout (`POST /api/v1/auth/logout`)**
    
    Meskipun ini adalah proses keluar, sistem tetap harus memvalidasi token sebelum mencabut aksesnya.
    
    **Error: Tidak Ada Akses / Token Tidak Valid (401 Unauthorized)**
    
    Terjadi jika user mencoba *logout* tanpa menyertakan token yang valid, atau token sudah dicabut (*revoked*) sebelumnya.
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 401,
        "message": "Unauthenticated. Sesi tidak ditemukan."
      },
      "data": null
    }
    ```
    
    **4. Error Server Global (500 Internal Server Error)**
    
    Ini adalah *negative flow* bawaan (*Exception Handling*) yang berlaku untuk semua endpoint jika terjadi kegagalan di sisi *backend* (misalnya database mati atau *syntax error*).
    
    JSON
    
    ```json
    {
      "meta": {
        "status": 500,
        "message": "Terjadi kesalahan pada server."
      },
      "data": {
        "error_detail": "Connection refused (opsional, disembunyikan di production)"
      }
    }
    ```