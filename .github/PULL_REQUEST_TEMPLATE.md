<!--
Cara pakai:
1. Pilih 1 jenis PR di bawah ini dengan mencentang checkbox-nya.
2. Isi HANYA bagian (## ...) yang sesuai dengan jenis PR tersebut.
3. Hapus 2 bagian lain yang tidak relevan sebelum submit PR.
-->

## Jenis PR

- [ ] `feature/* → dev`
- [ ] `dev → staging`
- [ ] `staging → main`

---

## 🚀 `feature/* → dev`

### Deskripsi
<!-- Jelaskan secara singkat fitur/perbaikan apa yang dikerjakan di branch ini -->

### Perubahan
<!-- List perubahan utama, contoh:
- Tambah endpoint X
- Perbaiki validasi Y
-->

### Referensi
<!-- Link issue/ticket/task terkait, jika ada -->

### Checklist
- [ ] Kode sudah di-test secara lokal
- [ ] Sudah menjalankan `php artisan test` dan semua test lulus
- [ ] Tidak ada perubahan yang breaking terhadap kode/endpoint yang sudah ada
- [ ] Migration baru (jika ada) sudah dicek `up()` dan `down()`-nya
- [ ] Tidak ada `dd()`, `dump()`, atau kode debug yang tertinggal

---

## 🧪 `dev → staging`

### Deskripsi
<!-- Ringkasan kumpulan fitur/perbaikan yang masuk ke staging pada PR ini -->

### Daftar Perubahan
<!-- List PR/fitur yang sudah masuk ke dev dan akan dibawa ke staging -->

### Checklist
- [ ] Semua fitur di branch `dev` sudah lulus review masing-masing
- [ ] Sudah dilakukan regression test (manual/otomatis) di environment dev
- [ ] Migration sudah dicoba jalan dari kondisi database staging saat ini
- [ ] Environment variable baru (jika ada) sudah didokumentasikan & disiapkan di staging
- [ ] Tidak ada konflik/merge issue tersisa

---

## 🏁 `staging → main`

### Deskripsi
<!-- Ringkasan rilis: fitur/perbaikan apa saja yang akan naik ke production -->

### Daftar Perubahan (Release Notes)
<!-- List final fitur/perbaikan untuk rilis ini -->

### Checklist
- [ ] Sudah di-QA/UAT di environment staging dan disetujui
- [ ] Environment variable production sudah disiapkan (jika ada yang baru)
- [ ] Migration sudah aman dijalankan di production (sudah dicek `down()`/rollback plan)
- [ ] Sudah ada persetujuan (approval) dari reviewer/PIC terkait
- [ ] Rencana rollback sudah jelas jika terjadi masalah setelah rilis
