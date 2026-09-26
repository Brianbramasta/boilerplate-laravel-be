Modul ini dikhususkan bagi pengguna dengan peran *Administrator* yang bertanggung jawab untuk mengelola data user, mengatur hak akses (*role*), dan menentukan atasan (*manager/approver*) dari masing-masing karyawan.

Semua endpoint wajib menyertakan header `Authorization: Bearer {token}` dan hanya bisa diakses oleh *role* `admin` (jika bukan, kembalikan error `403 Forbidden`).

### 1. Melihat Daftar Pengguna (Paginated)

Digunakan oleh Admin untuk melihat seluruh pengguna yang terdaftar di sistem, dilengkapi dengan fitur pencarian dan filter *role*.

- **URL:** `/api/v1/admin/users`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman
    - `search` (string) - Pencarian berdasarkan nama atau email
    - `role` (string) - Filter berdasarkan peran (`employee`, `approver`, `hr`, `admin`)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Daftar pengguna berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Super Admin",
        "email": "admin@dasaria.com",
        "role": "admin",
        "manager_id": null,
        "created_at": "2026-01-01T10:00:00Z"
      },
      {
        "id": 5,
        "name": "Jane Doe",
        "email": "jane@dasaria.com",
        "role": "employee",
        "manager_id": 2,
        "manager_name": "John Smith (Approver)",
        "created_at": "2026-02-15T09:00:00Z"
      }
    ],
    "per_page": 10,
    "to": 2,
    "total": 150
  }
}
```

### 2. Membuat Pengguna Baru (Create User)

Digunakan oleh Admin untuk mendaftarkan karyawan baru ke dalam sistem, menentukan *role*-nya, serta mengatur siapa atasannya (`manager_id`).

- **URL:** `/api/v1/admin/users`
- **Method:** `POST`

**Request Body:**

JSON

```json
{
  "name": "Michael Taylor",
  "email": "michael.t@dasaria.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  "manager_id": 2
}
```

**Success Response (201 Created):**

JSON

```json
{
  "meta": {
    "status": 201,
    "message": "Pengguna berhasil dibuat"
  },
  "data": {
    "id": 6,
    "name": "Michael Taylor",
    "email": "michael.t@dasaria.com",
    "role": "employee",
    "manager_id": 2,
    "created_at": "2026-09-26T10:02:30Z"
  }
}
```

**Negative Flows (Errors):**

- **Error: Validasi Gagal (422 Unprocessable Entity)**
    
    Terjadi jika email sudah terdaftar sebelumnya, atau kombinasi password tidak cocok.
    

JSON

```json
{
  "meta": {
    "status": 422,
    "message": "Validasi gagal"
  },
  "data": {
    "email": [
      "Email ini sudah terdaftar di sistem."
    ],
    "role": [
      "Role yang dipilih tidak valid."
    ]
  }
}
```

### 3. Mengubah Data Pengguna (Update User)

Digunakan oleh Admin untuk memperbarui data user, misalnya jika ada promosi sehingga *role*-nya berubah menjadi `approver`, atau perubahan atasan (`manager_id`).

- **URL:** `/api/v1/admin/users/{id}`
- **Method:** `PUT` (atau `PATCH`)

**Request Body:**

*(Password bersifat opsional. Jika tidak diisi, maka password lama tidak diubah)*

JSON

```json
{
  "name": "Jane Doe Updated",
  "email": "jane.updated@dasaria.com",
  "role": "approver",
  "manager_id": null
}
```

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Data pengguna berhasil diperbarui"
  },
  "data": {
    "id": 5,
    "name": "Jane Doe Updated",
    "email": "jane.updated@dasaria.com",
    "role": "approver",
    "manager_id": null,
    "updated_at": "2026-09-26T10:15:00Z"
  }
}
```

**Negative Flows (Errors):**

- **Error: Data Tidak Ditemukan (404 Not Found)**

JSON

```json
{
  "meta": {
    "status": 404,
    "message": "Data pengguna tidak ditemukan."
  },
  "data": null
}
```

### *Negative Flows Tambahan (Global untuk Modul Admin)*

**Error: Akses Ditolak / Bukan Kewenangan (403 Forbidden)**
Sistem wajib membatasi agar endpoint ini tidak bisa ditembus oleh user biasa.

JSON

```json
{
  "meta": {
    "status": 403,
    "message": "Forbidden. Hanya Administrator yang dapat mengakses modul ini."
  },
  "data": null
}
```