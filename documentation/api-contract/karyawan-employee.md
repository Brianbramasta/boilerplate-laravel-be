Berikut adalah API Contract untuk **Modul Karyawan (Employee)** menggunakan format standar beserta *positive* dan *negative flow*-nya. Modul ini mengakomodasi kewenangan Employee sesuai aturan bisnis.

Semua endpoint di bawah ini wajib menyertakan header `Authorization: Bearer {token}`. Jika token tidak ada atau tidak valid, sistem akan mengembalikan *error* `401 Unauthorized`.

### 1. Mendapatkan Daftar Jenis Cuti (Paginated)

- **URL:** `/api/v1/leave-types`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses (default: 1)
    - `per_page` (integer) - Jumlah data per halaman (default: 10)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Daftar jenis cuti berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "code": "ANNUAL",
        "name": "Cuti Tahunan",
        "is_deduct_balance": true
      },
      {
        "id": 2,
        "code": "SICK",
        "name": "Cuti Sakit",
        "is_deduct_balance": true
      }
    ],
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

### 2. Melihat Saldo Cuti (Paginated)

- **URL:** `/api/v1/my-leave-balances`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Saldo cuti berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "leave_type_id": 1,
        "leave_type_name": "Cuti Tahunan",
        "year": 2026,
        "total_quota": 12,
        "used_quota": 2,
        "remaining_balance": 10
      }
    ],
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

### 3. Melihat Daftar Pengajuan Cuti (Paginated)

- **URL:** `/api/v1/my-leave-requests`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Daftar pengajuan cuti berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 101,
        "leave_type_name": "Cuti Tahunan",
        "start_date": "2026-10-10",
        "end_date": "2026-10-12",
        "total_days": 3,
        "status": "APPROVED",
        "created_at": "2026-09-01T10:00:00Z"
      },
      {
        "id": 100,
        "leave_type_name": "Cuti Sakit",
        "start_date": "2026-08-05",
        "end_date": "2026-08-05",
        "total_days": 1,
        "status": "APPROVED",
        "created_at": "2026-08-04T08:30:00Z"
      }
    ],
    "per_page": 10,
    "to": 2,
    "total": 15
  }
}
```

### 4. Membuat Pengajuan Cuti (Submit Request)

Endpoint untuk membuat pengajuan cuti baru. Pengajuan akan berstatus `SUBMITTED`.

- **URL:** `/api/v1/my-leave-requests`
- **Method:** `POST`

**Request Body:**
Sesuai kebutuhan minimal data pengajuan.

JSON

```json
{
  "leave_type_id": 1,
  "start_date": "2026-10-10",
  "end_date": "2026-10-12",
  "reason": "Acara keluarga di luar kota"
}
```

**Success Response (201 Created):**

JSON

```json
{
  "meta": {
    "status": 201,
    "message": "Pengajuan cuti berhasil disubmit"
  },
  "data": {
    "id": 102,
    "leave_type_id": 1,
    "start_date": "2026-10-10",
    "end_date": "2026-10-12",
    "total_days": 3,
    "reason": "Acara keluarga di luar kota",
    "status": "SUBMITTED"
  }
}
```

**Negative Flows (Errors):**

- **Error: Validasi Input Gagal & Validasi Tanggal (422 Unprocessable Entity)**
Terjadi jika form tidak lengkap atau tanggal mulai lebih besar dari tanggal selesai.

JSON

```json
{
  "meta": {
    "status": 422,
    "message": "Validasi gagal"
  },
  "data": {
    "start_date": [
      "Tanggal mulai tidak boleh setelah tanggal selesai."
    ],
    "reason": [
      "Alasan cuti wajib diisi."
    ]
  }
}
```

- **Error: Overlapping Leave / Tanggal Bentrok (422 Unprocessable Entity)**
Terjadi jika terdapat overlapping dengan cuti lain yang sudah ada.

JSON

```json
{
  "meta": {
    "status": 422,
    "message": "Periode cuti bentrok dengan pengajuan Anda yang lain."
  },
  "data": null
}
```

- **Error: Saldo Tidak Mencukupi (400 Bad Request)**
Terjadi jika kalkulasi `total_days` melebihi `remaining_balance`.

JSON

```json
{
  "meta": {
    "status": 400,
    "message": "Saldo cuti tidak mencukupi."
  },
  "data": null
}
```

### 5. Membatalkan Pengajuan Cuti (Cancel Request)

Karyawan dapat membatalkan pengajuan yang berstatus `SUBMITTED`. Status akan berubah menjadi `CANCELLED`.

- **URL:** `/api/v1/my-leave-requests/{id}/cancel`
- **Method:** `PATCH`

**Request Body:** *(Kosong)*

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Pengajuan cuti berhasil dibatalkan"
  },
  "data": {
    "id": 102,
    "status": "CANCELLED"
  }
}
```

**Negative Flows (Errors):**

- **Error: Data Tidak Ditemukan (404 Not Found)**
Terjadi jika ID cuti tidak ada, atau ID tersebut bukan milik karyawan yang sedang login (akses lintas kewenangan).

JSON

```json
{
  "meta": {
    "status": 404,
    "message": "Data pengajuan cuti tidak ditemukan."
  },
  "data": null
}
```

- **Error: Tidak Bisa Dibatalkan (400 Bad Request)**
Terjadi jika karyawan mencoba membatalkan pengajuan yang statusnya sudah `APPROVED`, `REJECTED`, atau `CANCELLED`.

JSON

```json
{
  "meta": {
    "status": 400,
    "message": "Pengajuan tidak dapat dibatalkan karena status saat ini adalah APPROVED."
  },
  "data": null
}
```