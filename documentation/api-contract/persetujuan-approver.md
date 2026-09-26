Modul ini dikhususkan bagi pengguna dengan peran *Approver* untuk meninjau dan memberikan keputusan terhadap pengajuan cuti dari bawahan mereka. Semua endpoint wajib menyertakan header `Authorization: Bearer {token}`.

### 1. Melihat Daftar Pengajuan Cuti Bawahan (Paginated)

Menampilkan daftar pengajuan cuti yang masuk dan menjadi kewenangan *Approver* tersebut. Secara default, biasanya difilter untuk status `SUBMITTED`.

- **URL:** `/api/v1/approvals/leave-requests`
- **Method:** `GET`
- **Query Parameters (Opsional):**
    - `page` (integer) - Halaman yang ingin diakses
    - `per_page` (integer) - Jumlah data per halaman
    - `status` (string) - Filter status (misal: `SUBMITTED`, `APPROVED`)

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Daftar pengajuan cuti bawahan berhasil diambil"
  },
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 102,
        "employee_name": "Jane Doe",
        "leave_type_name": "Cuti Tahunan",
        "start_date": "2026-10-10",
        "end_date": "2026-10-12",
        "total_days": 3,
        "reason": "Acara keluarga di luar kota",
        "status": "SUBMITTED",
        "created_at": "2026-09-26T08:00:00Z"
      }
    ],
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

### 2. Menyetujui Pengajuan Cuti (Approve)

Digunakan oleh *Approver* untuk menyetujui pengajuan cuti. Status akan berubah menjadi `APPROVED`, sistem akan memotong saldo cuti *Employee*, dan mencatat riwayat persetujuan (*Approval History*).

- **URL:** `/api/v1/approvals/leave-requests/{id}/approve`
- **Method:** `PATCH`

**Request Body:**
Catatan dari *approver* (opsional, untuk dicatat di *Approval History*).

JSON

```json
{
  "notes": "Pekerjaan sudah di-handover dengan baik, silakan cuti."
}
```

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Pengajuan cuti berhasil disetujui"
  },
  "data": {
    "id": 102,
    "status": "APPROVED"
  }
}
```

**Negative Flows (Errors):**

- **Error: Data Tidak Ditemukan / Bukan Kewenangan (404 Not Found)**
Terjadi jika ID tidak ada atau *Approver* mencoba mengakses pengajuan yang bukan bawahan langsungnya (akses di luar kewenangan).

JSON

```json
{
  "meta": {
    "status": 404,
    "message": "Data pengajuan cuti tidak ditemukan atau Anda tidak memiliki akses."
  },
  "data": null
}
```

- **Error: Status Tidak Valid (400 Bad Request)**
Terjadi jika pengajuan sudah berstatus `APPROVED`, `REJECTED`, atau `CANCELLED` (tidak dapat diubah kembali).

JSON

```json
{
  "meta": {
    "status": 400,
    "message": "Pengajuan tidak dapat disetujui karena status saat ini sudah APPROVED."
  },
  "data": null
}
```

- **Error: Saldo Cuti Tidak Mencukupi (400 Bad Request)**
Sistem wajib mengecek saldo sebelum *approval* akhir. Terjadi jika tiba-tiba saldo cuti bawahan tidak cukup saat akan di-*approve*.

JSON

```json
{
  "meta": {
    "status": 400,
    "message": "Gagal menyetujui. Saldo cuti karyawan tidak mencukupi."
  },
  "data": null
}
```

### 3. Menolak Pengajuan Cuti (Reject)

Digunakan oleh *Approver* untuk menolak pengajuan cuti. Status akan berubah menjadi `REJECTED` dan riwayat persetujuan dicatat. Saldo cuti tidak akan terpotong.

- **URL:** `/api/v1/approvals/leave-requests/{id}/reject`
- **Method:** `PATCH`

**Request Body:**
Untuk penolakan, catatan (*notes*) biasanya diwajibkan sebagai alasan bagi *Employee*.

JSON

```json
{
  "notes": "Saat ini tim sedang kekurangan orang karena rilis project, mohon reschedule."
}
```

**Success Response (200 OK):**

JSON

```json
{
  "meta": {
    "status": 200,
    "message": "Pengajuan cuti berhasil ditolak"
  },
  "data": {
    "id": 102,
    "status": "REJECTED"
  }
}
```

**Negative Flows (Errors):**

- **Error: Validasi Catatan Penolakan Gagal (422 Unprocessable Entity)**
Terjadi jika catatan kosong (bila aturan sistem mewajibkan alasan penolakan).

JSON

```json
{
  "meta": {
    "status": 422,
    "message": "Validasi gagal"
  },
  "data": {
    "notes": [
      "Catatan/alasan penolakan wajib diisi."
    ]
  }
}
```

- **Error: Status Tidak Valid (400 Bad Request)**
Terjadi jika pengajuan sudah tidak berstatus `SUBMITTED`.

JSON

```json
{
  "meta": {
    "status": 400,
    "message": "Pengajuan tidak dapat ditolak karena status saat ini sudah CANCELLED."
  },
  "data": null
}
```