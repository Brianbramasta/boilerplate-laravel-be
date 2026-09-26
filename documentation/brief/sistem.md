### Project Brief: Employee Leave Management System

**1. Deskripsi Proyek**
Sistem ini adalah layanan backend API terpusat untuk mengelola siklus pengajuan cuti karyawan perusahaan. Sistem ini dirancang untuk menggantikan pencatatan manual dengan otomatisasi validasi tanggal, kalkulasi hari kerja, pemotongan kuota cuti, hingga pencatatan jejak audit (riwayat persetujuan). Proyek ini juga berfungsi sebagai *single business case* yang akan Anda kembangkan secara iteratif untuk menguasai kompetensi *Backend Development* berbasis Laravel.

**2. Aktor & Hak Akses (User Roles)**
Sistem menerapkan *Role-Based Access Control* (RBAC) dengan pemisahan kewenangan yang tegas:

* **Employee:** Hanya dapat melihat sisa saldo cutinya sendiri, membuat pengajuan cuti, memantau status pengajuannya, dan membatalkan pengajuan yang masih berstatus `SUBMITTED`.


* **Approver:** Menerima notifikasi pengajuan baru dan memiliki kewenangan penuh untuk menyetujui (*Approve*) atau menolak (*Reject*) cuti dari bawahannya.


* **HR:** Memiliki akses pengawasan global untuk mencari, memfilter, dan mengunduh laporan analitik terkait total penggunaan cuti seluruh karyawan.


* **Administrator:** Bertanggung jawab di balik layar untuk mendaftarkan akun pengguna baru, mengatur *role*, menentukan atasan (*manager*), serta mengatur jenis-jenis cuti yang tersedia.



**3. Aturan Bisnis & Fitur Utama (Core Logic)**

* **Manajemen Saldo Cuti:** Sistem harus melacak jatah cuti (kuota awal, terpakai, sisa) untuk setiap karyawan. Saldo **tidak boleh bernilai negatif** dan hanya boleh dipotong setelah pengajuan resmi disetujui (status `APPROVED`).


* **Validasi Anti-Bentrok:** Sistem akan otomatis menolak pengajuan jika tanggal mulai melewati tanggal selesai, atau jika tanggal cuti *overlapping* (tumpang tindih) dengan pengajuan cuti karyawan tersebut yang sudah ada.


* **Transisi Status (State Machine):** Siklus cuti bergerak dari `DRAFT` $\rightarrow$ `SUBMITTED` $\rightarrow$ `APPROVED` / `REJECTED` / `CANCELLED`. Status akhir tidak dapat diedit kembali oleh siapapun.


* **Audit Trail:** Setiap kali status berubah, sistem wajib mencatat *Approval History* (siapa yang mengubah, kapan, status sebelum/sesudah, dan alasan penolakan/persetujuan).


* **Notifikasi Otomatis:** Sistem akan menyebarkan pemberitahuan secara *real-time* atau asinkron (*background job*) ke pihak terkait saat ada aksi baru.



**4. Standar Teknis & Arsitektur (Technical Targets)**

* **Tech Stack:** Laravel (PHP), MySQL, dengan antarmuka dokumentasi API menggunakan Scribe dan Scalar.


* **Arsitektur Bersih:** Pemisahan logika secara modular (*Controller, Service, Repository, Model*) agar kode mudah dirawat (*maintainable*).


* **Keamanan & Performa:** Sistem wajib mengimplementasikan perlindungan terhadap celah keamanan (seperti manipulasi ID orang lain), mencegah *race condition* saat pemotongan saldo menggunakan *Database Transaction*, dan mengoptimalkan kecepatan *query* database.