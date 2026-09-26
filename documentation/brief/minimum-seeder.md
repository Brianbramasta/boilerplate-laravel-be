Untuk dapat menguji keseluruhan alur aplikasi pada **Fase 3 (Database & Eloquent)**, Anda memerlukan beberapa *Seeder* (data awal) agar sistem memiliki data referensi (master data) dan pengguna (*user*) untuk login.

Berdasarkan aturan bisnis pada dokumen **Backend Assessment Case**, berikut adalah minimal *seeder* yang harus Anda siapkan:

### 1. `RoleSeeder` / `UserSeeder`

Sistem memiliki 4 jenis peran pengguna dengan hak akses yang berbeda. Anda minimal harus membuat 4 atau 5 *user dummy* untuk mewakili masing-masing peran agar bisa menguji fitur *Authorization* nanti:

* **1 Akun Administrator:** Untuk menguji manajemen *user* dan konfigurasi.
* **1 Akun HR:** Untuk menguji akses laporan dan *monitoring*.
* **1 Akun Approver (Manager):** Berperan sebagai atasan.
* **1 atau 2 Akun Employee:** Berperan sebagai bawahan. Pastikan kolom `manager_id` pada akun Employee ini diisi dengan ID dari akun *Approver* di atas.

### 2. `LeaveTypeSeeder` (Seeder Jenis Cuti)

Sistem memiliki beberapa jenis cuti yang aturan dan kuotanya bisa berbeda. Masukkan minimal data yang dicontohkan di dokumen:

* **Annual Leave (Cuti Tahunan):** Memotong saldo cuti (kuota misal 12 hari/tahun).
* **Sick Leave (Cuti Sakit):** Memotong saldo cuti atau memiliki kuota terpisah (misal 14 hari/tahun).
* **Special Leave (Cuti Khusus):** Cuti menikah, melahirkan, dll., yang mungkin memiliki kuota spesifik.

### 3. `LeaveBalanceSeeder` (Seeder Saldo Cuti)

Setiap *Employee* wajib memiliki saldo cuti sebelum bisa melakukan pengajuan. Jika data ini kosong, sistem akan selalu menolak pengajuan (karena saldo tidak mencukupi).

* Buat *seeder* yang secara otomatis memberikan saldo awal (*total quota*) untuk *Employee* pada tahun berjalan (misal: Tahun 2026, Kuota 12, Terpakai 0).

### 4. `HolidaySeeder` (Opsional tapi Direkomendasikan)

Karena ada aturan bahwa *"weekend dan holiday dapat memengaruhi perhitungan jumlah hari cuti"* (Bab 2.6), sangat disarankan Anda membuat tabel dan *seeder* yang berisi daftar hari libur nasional (misal: Tahun Baru, Idul Fitri, Hari Kemerdekaan) pada tahun berjalan. Ini akan sangat berguna saat Anda membuat algoritma kalkulasi `total_days`.

Dengan menyiapkan 3 atau 4 *seeder* di atas, ketika Anda menjalankan `php artisan migrate --seed`, database Anda sudah langsung siap digunakan untuk mengetes *endpoint-endpoint* yang sudah dirancang sebelumnya.