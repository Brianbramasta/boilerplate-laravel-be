Tentu, berdasarkan dokumen "database-convention-standards.pptx.pdf", berikut adalah rekapitulasi standar penulisan *database* yang harus diterapkan:

### 1. Aturan Penamaan (Naming Convention)



* **Gunakan format `snake_case`:** Semua kata dipisah dengan *underscore* (garis bawah). Contoh: `purchase_order_items`.


* **Gunakan Bahasa Inggris:** Seluruh penamaan harus dalam bahasa Inggris untuk menjaga konsistensi. Contoh: `departments`, bukan `departemen`.


* **Nama Tabel Plural (Jamak):** Nama tabel harus menggambarkan bahwa tabel tersebut menyimpan kumpulan baris data (*record*). Contoh: `employees`, `companies`, `purchase_orders` (Bukan `employee`, `company`, `purchase_order`).


* **Huruf Kecil Semua:** Tidak boleh ada huruf kapital sama sekali dalam penamaan skema. Contoh: `mt_employees`, bukan `MT_Employees`.



### 2. Klasifikasi Tabel (Table Classification / Prefix)



Setiap nama tabel wajib menggunakan *prefix* (awalan) yang langsung menjelaskan jenis tabel tersebut:

* `mt_`: **Master** (Data utama, sumber kebenaran yang stabil, contoh: `mt_employees`).


* `tr_`: **Transaction** (Data operasional alur bisnis yang terus bertambah, contoh: `tr_leave_requests`).


* `dt_`: **Detail** (Baris anak dari sebuah data transaksi, contoh: `dt_purchase_order_items`).


* `ref_`: **Reference** (Data opsi pilihan/lookup, contoh: `ref_religions`).


* `log_`: **Log** (Jejak riwayat perubahan/audit, contoh: `log_activity_logs`).


* `cfg_`: **Configuration** (Parameter sistem).


* `tmp_`: **Temporary** (Data impor/sementara).



### 3. Aturan Kolom (Column Convention)



* **Primary Key:** Selalu gunakan nama `id`.


* **Foreign Key:** Gunakan format `<nama_tabel_singular>_id`. Contoh: `employee_id`, `department_id`, `company_id`.


* **Kolom Audit & Timestamps:** Wajib ada untuk melacak aktivitas. Contoh: `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`.



**Urutan Standar Kolom dalam Tabel:**

1. **Primary Key** (`id`) selalu di urutan pertama.


2. **Foreign Keys** diletakkan tepat di bawah primary key.


3. **Business Columns** (Data utama tabel).


4. **Audit Columns** (`created_by`, `updated_by`, `deleted_by`).


5. **Timestamps** (`created_at`, `updated_at`, `deleted_at`).



### 4. Strategi Desain Tambahan



* **Reference Table (`ref_`):** Gunakan tabel referensi jika sebuah opsi bisnis memiliki lebih dari 3 pilihan atau bisa bertambah di masa depan (contoh: Status Karyawan, Jenis Kontrak). Jangan di- *hardcode* jika opsi mungkin berubah.


* **Logging Strategy (`log_`):** Strategi pencatatan log (riwayat) disesuaikan dengan kebutuhan bisnis. Pilihannya adalah *Full JSON Only* (untuk kebutuhan *rollback*/snapshot), *Field History Only* (untuk *timeline* per kolom), atau gabungan keduanya untuk data yang sangat kritis.



Prinsip utamanya adalah **"Konsistensi di atas preferensi pribadi"** agar *database* mudah dibaca dan dirawat oleh semua *developer*.