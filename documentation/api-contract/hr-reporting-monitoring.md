Berikut adalah API Contract untuk **Modul HR (Reporting & Monitoring)**.

Modul ini dikhususkan bagi pengguna dengan peran *HR* yang bertanggung jawab untuk memonitor, mengelola data cuti seluruh karyawan, serta melihat laporan. Semua endpoint ini wajib menyertakan header `Authorization: Bearer {token}` dan sistem harus memastikan bahwa *user* yang mengakses benar-benar memiliki *role* `hr` (jika bukan, kembalikan error `403 Forbidden`).

### 1. Melihat Semua Daftar Pengajuan Cuti (Filtering, Searching, Sorting, Pagination)

Sesuai aturan bisnis, endpoint ini harus mendukung paginasi, pencarian, dan berbagai filter untuk memudahkan HR melacak data.

- **URL:** `/api/v1/hr/leave-requests`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman
    - `search` (string) - Pencarian berdasarkan nama karyawan
    - `status` (string) - Filter berdasarkan status (misal: `APPROVED`)
    - `start_date` & `end_date` (date) - Filter berdasarkan periode pengajuan
    - `sort_by` (string) - Kolom yang diurutkan (misal: `created_at`)
    - `sort_dir` (string) - Arah urutan (`asc` atau `desc`)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Daftar seluruh pengajuan cuti berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 102,
        "employee_name": "Jane Doe",
        "department": "Engineering",
        "leave_type_name": "Cuti Tahunan",
        "start_date": "2026-10-10",
        "end_date": "2026-10-12",
        "status": "APPROVED",
        "created_at": "2026-09-26T08:00:00Z"
      }
    ],
    "per_page": 10,
    "to": 1,
    "total": 50
  }
}
```

### 2. Melihat Detail Pengajuan Cuti & Riwayat Persetujuan

HR berhak melihat detail lengkap dari satu pengajuan cuti, termasuk menelusuri riwayat persetujuan (*approval history*).

- **URL:** `/api/v1/hr/leave-requests/{id}`
- **Method:** `GET`

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Detail pengajuan cuti berhasil diambil"
  },
  "data": {
    "id": 102,
    "employee_name": "Jane Doe",
    "leave_type_name": "Cuti Tahunan",
    "start_date": "2026-10-10",
    "end_date": "2026-10-12",
    "total_days": 3,
    "reason": "Acara keluarga di luar kota",
    "status": "APPROVED",
    "approval_histories": [
      {
        "actor_name": "Jane Doe",
        "previous_status": "DRAFT",
        "next_status": "SUBMITTED",
        "notes": null,
        "created_at": "2026-09-26T08:00:00Z"
      },
      {
        "actor_name": "John Smith (Manager)",
        "previous_status": "SUBMITTED",
        "next_status": "APPROVED",
        "notes": "Pekerjaan sudah di-handover, silakan cuti.",
        "created_at": "2026-09-26T14:30:00Z"
      }
    ]
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
    "message": "Data pengajuan cuti tidak ditemukan."
  },
  "data": null
}
```

### 3. Laporan Rekapitulasi Cuti (Leave Summary Report)

Menampilkan metrik agregasi pengajuan cuti (jumlah pengajuan, approved, rejected, cancelled) berdasarkan periode tertentu.

- **URL:** `/api/v1/hr/reports/leave-summary`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `start_date` (date) - Awal periode laporan
    - `end_date` (date) - Akhir periode laporan

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Laporan rekapitulasi cuti berhasil diambil"
  },
  "data": {
    "period_start": "2026-01-01",
    "period_end": "2026-12-31",
    "total_requests": 150,
    "total_approved": 120,
    "total_rejected": 20,
    "total_cancelled": 10
  }
}
```

### 4. Laporan Penggunaan Berdasarkan Jenis Cuti

Menampilkan data analitik penggunaan cuti berdasarkan jenisnya (*Annual, Sick, Special, dll*).

- **URL:** `/api/v1/hr/reports/usage-by-type`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `year` (integer) - Tahun laporan (misal: 2026)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Laporan penggunaan berdasarkan jenis cuti berhasil diambil"
  },
  "data": [
    {
      "leave_type_name": "Cuti Tahunan",
      "total_requests": 100,
      "total_days_used": 250
    },
    {
      "leave_type_name": "Cuti Sakit",
      "total_requests": 50,
      "total_days_used": 75
    }
  ]
}
```

### *Negative Flows Tambahan (Global untuk Modul HR)*

**Error: Akses Ditolak / Bukan Kewenangan (403 Forbidden)**
Karena modul ini eksklusif untuk HR, jika *Employee* biasa atau *Approver* mencoba mengakses endpoint `/api/v1/hr/...`, sistem wajib memblokirnya (Authorization Requirement).

JSON

```json
{
  "meta": {
    "status": 403,
    "message": "Forbidden. Anda tidak memiliki akses ke modul ini."
  },
  "data": null
}
```

### 5. Laporan Penggunaan Berdasarkan Karyawan (Usage by Employee)

Menampilkan daftar karyawan beserta total hari cuti yang sudah mereka gunakan (status `APPROVED`), biasanya difilter berdasarkan tahun.

- **URL:** `/api/v1/hr/reports/usage-by-employee`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman
    - `search` (string) - Pencarian nama karyawan
    - `year` (integer) - Filter berdasarkan tahun (misal: `2026`)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Laporan penggunaan cuti berdasarkan karyawan berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "employee_id": 5,
        "employee_name": "Jane Doe",
        "department": "Engineering",
        "total_requests": 3,
        "total_days_used": 5,
        "leave_details": [
          {
            "leave_type_name": "Cuti Tahunan",
            "days_used": 3
          },
          {
            "leave_type_name": "Cuti Sakit",
            "days_used": 2
          }
        ]
      },
      {
        "employee_id": 8,
        "employee_name": "John Smith",
        "department": "Marketing",
        "total_requests": 1,
        "total_days_used": 2,
        "leave_details": [
          {
            "leave_type_name": "Cuti Tahunan",
            "days_used": 2
          }
        ]
      }
    ],
    "per_page": 10,
    "to": 2,
    "total": 150
  }
}
```

**Negative Flows (Errors):**

- **Error: Akses Ditolak / Bukan Kewenangan (403 Forbidden)**
Sama seperti endpoint HR lainnya, jika yang mengakses API ini bukan user dengan *role* `hr`, maka akses otomatis ditolak.

JSON

```json
{
  "meta": {
    "status": 403,
    "message": "Forbidden. Anda tidak memiliki akses ke laporan ini."
  },
  "data": null
}
```