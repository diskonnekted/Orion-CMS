# Manual KAih (Sekolah) — Orion CMS

Dokumen ini menjelaskan cara instalasi, konfigurasi, dan penggunaan **KAih (Aplikasi Anak Hebat)** di Orion CMS, untuk peran:

- Admin (Operator/Administrator)
- Guru (akun khusus KAih)
- Siswa (aplikasi siswa)
- Orang Tua (akses dashboard ortu via PIN per siswa)

KAih pada versi ini dirancang agar **tetap berjalan walaupun jenis database berubah**, karena progres utama siswa disimpan sebagai **file JSON** di `orion-content/uploads/kaih/`. Namun, untuk kebutuhan sekolah (master data, relasi guru–siswa, PIN ortu), KAih juga membuat **tabel tambahan** di database Orion.

---

## 1) Ringkasan Arsitektur

### 1.1 Storage yang digunakan

1) **File JSON (utama untuk progres dan data game)**

- Lokasi: `orion-content/uploads/kaih/students/<student_id>.json`
- Isi: total bintang, daftar misi, status pending/approved, inventory, hadiah fisik, dsb.
- Kelebihan: independen dari perubahan jenis database (karena file-based).

2) **Database Orion (master data dan kontrol akses)**

- Tabel:
  - `orion_kaih_students` (master siswa + hash PIN ortu)
  - `orion_kaih_teacher_students` (relasi guru → siswa)
- Dipakai untuk:
  - Membatasi guru hanya mengelola siswa yang di-assign
  - PIN ortu per siswa (bukan hardcode)
  - Daftar siswa terhubung di dashboard guru

> Catatan: Prefix tabel menyesuaikan `$table_prefix` instalasi Anda, biasanya `orion_`.

---

## 2) Instalasi & Aktivasi Plugin

### 2.1 Lokasi plugin

- Folder plugin: `orion-content/plugins/orion-kaih/`
- File utama plugin: `orion-content/plugins/orion-kaih/orion-kaih.php`

### 2.2 Aktivasi

1. Login Admin Orion: `http(s)://<domain>/login.php`
2. Buka: `http(s)://<domain>/orion-admin/plugins.php`
3. Cari plugin: **Orion KAih (Sekolah)**
4. Klik **Activate**

### 2.3 Setelah aktif (otomatis)

Plugin akan otomatis:

- Membuat **2 halaman CMS**:
  - `KAih Siswa`
  - `KAih Guru`
- Menyimpan ID halaman ke option:
  - `orion_kaih_page_student_id`
  - `orion_kaih_page_teacher_id`
- Menambahkan menu item “KAih Siswa” dan “KAih Guru” ke menu navigasi utama (best-effort).
- Membuat tabel DB (jika belum ada):
  - `*_kaih_students`
  - `*_kaih_teacher_students`

---

## 3) URL Penting

KAih bisa diakses dengan 2 cara (keduanya valid):

### 3.1 Akses via query `kaih=...`

- Siswa: `/?kaih=siswa`
- Guru: `/?kaih=guru`

### 3.2 Akses via Page CMS (disarankan)

Setelah plugin aktif, Anda dapat membuka:

- `/?page_id=<ID page “KAih Siswa”>`
- `/?page_id=<ID page “KAih Guru”>`

Kelebihan: bisa ditambahkan ke menu tanpa mengetik URL.

---

## 4) Admin: Kelola Data Siswa/Guru/Ortu dan Relasi

### 4.1 Buka menu Admin KAih

Di dashboard admin, sidebar akan menampilkan item plugin KAih (karena plugin memiliki `manager.php`).

Buka:

- `http(s)://<domain>/orion-content/plugins/orion-kaih/manager.php`

Syarat:

- Harus login
- Harus role **administrator**

### 4.2 Tab “Siswa” (CRUD siswa + PIN ortu)

#### Tambah siswa

Isi:

- **ID Siswa**: gunakan format stabil, contoh `NIS`, `NISN`, atau kode internal (maks 64 karakter; hanya `A-Z a-z 0-9 _ -`)
- **Nama Anak**
- **PIN Ortu** (opsional saat tambah, bisa diset kemudian)

#### Edit siswa

Di daftar siswa:

- Ubah **Nama Anak**
- Isi **PIN baru** jika ingin mengganti PIN ortu

PIN disimpan sebagai **hash** (bukan teks asli).

#### Hapus siswa

- Menghapus master siswa + relasi guru-siswa untuk siswa tersebut.
- File progres JSON di `uploads/kaih/students/` tidak otomatis dihapus (supaya aman dari kehilangan data tidak sengaja).

### 4.3 Tab “Guru” (buat akun guru)

Di tab ini Admin dapat membuat akun “Guru” khusus KAih.

Isi:

- Username
- Email
- Nama Guru (display name)
- Password

Hasil:

- User Orion dibuat dengan capability/role `kaih_teacher`.

Guru login menggunakan URL:

- `http(s)://<domain>/login.php`

### 4.4 Tab “Relasi” (assign guru → siswa)

Di tab Relasi:

- Pilih **Guru**
- Pilih **Siswa**
- Klik **Tambah Relasi**

Fungsi:

- Guru hanya bisa membuka/mengelola siswa yang di-assign.
- Dashboard guru juga akan menampilkan dropdown “Siswa Terdaftar (Anda)” berdasarkan relasi ini.

---

## 5) Siswa: Cara Menggunakan KAih Siswa

### 5.1 Masuk aplikasi siswa

Siswa membuka:

- `/?kaih=siswa` atau halaman CMS “KAih Siswa”

### 5.2 ID Siswa (penting untuk integrasi dengan admin)

KAih siswa saat ini membuat `studentId` otomatis di browser (localStorage).

Langkah praktis:

1. Buka tab **Profil**
2. Salin **ID Siswa** yang ditampilkan
3. Berikan ID tersebut kepada Admin untuk didaftarkan di tab **Siswa** (agar bisa set PIN ortu dan assign guru)

> Rekomendasi sekolah: gunakan `ID Siswa` yang sama dengan NIS/NISN agar mudah.

### 5.3 Misi Harian

Di tab **Misi**:

- Ketuk salah satu misi → upload bukti (foto/video) → kirim laporan
- Status misi menjadi **Menunggu Persetujuan**
- Setelah Guru menyetujui, status menjadi **Selesai** dan bintang ditambahkan (oleh guru)

Catatan:

- Bukti upload maksimal 5MB per file.

### 5.4 Toko dan Hadiah

Di tab **Toko**:

- Belanja item virtual dengan bintang
- Tukar hadiah fisik (jika admin/ortu menambah daftar hadiah)

### 5.5 Profil

Di tab **Profil**:

- Ubah nama anak
- Lihat ID Siswa
- Lihat inventory

### 5.6 Dashboard Orang Tua (PIN per siswa)

Di tab **Ortu**:

- Masukkan PIN
- PIN diverifikasi ke server sesuai yang diset Admin di master siswa.

Jika muncul:

- “PIN belum diset oleh admin.” → Admin belum mengisi PIN untuk siswa itu
- “PIN salah.” → PIN tidak cocok

---

## 6) Guru: Cara Menggunakan KAih Guru (Approve/Reject)

### 6.1 Login guru

1. Login: `/login.php` menggunakan akun guru dari Admin KAih
2. Buka KAih Guru:
   - `/?kaih=guru` atau page “KAih Guru”

Jika belum login, akan diarahkan kembali ke `/login.php`.

### 6.2 Pilih siswa

Di bagian atas:

- Jika relasi sudah dibuat di Admin, akan muncul dropdown:
  - **Siswa Terdaftar (Anda)**
- Pilih siswa → data otomatis dimuat

Jika relasi belum dibuat:

- Admin harus assign di tab **Relasi**.

### 6.3 Persetujuan misi (Pending Requests)

Di bagian “Permintaan Persetujuan Misi”:

- Klik **Lihat Bukti** untuk memeriksa foto/video
- Klik **Setujui**:
  - Misi menjadi `approved`
  - Bintang siswa bertambah sesuai poin misi
- Klik **Tolak**:
  - Pending dibatalkan
  - Bukti dihapus dari state (file tetap ada di server)

### 6.4 Kelola misi & hadiah

Guru dapat:

- Menambah misi baru
- Mengedit/hapus misi
- Menambah/hapus hadiah fisik

> Catatan: perubahan ini akan masuk ke state siswa (file JSON).

---

## 7) Keamanan & Aturan Akses

### 7.1 Akses dashboard guru

- Hanya user login dengan role:
  - `administrator`, atau
  - `kaih_teacher`

### 7.2 Pembatasan siswa untuk guru

- Jika role `administrator`: boleh mengakses semua siswa
- Jika role `kaih_teacher`: hanya boleh akses siswa yang ada di relasi `teacher_students`

### 7.3 PIN Orang Tua

- PIN disimpan sebagai hash (`password_hash`)
- Verifikasi dilakukan via endpoint API `parent_pin_verify`

---

## 8) Data & Backup

### 8.1 Backup minimal

Untuk backup KAih, simpan:

1. Folder:
   - `orion-content/uploads/kaih/`
2. Database tabel:
   - `*_kaih_students`
   - `*_kaih_teacher_students`

### 8.2 Migrasi domain

Jika pindah domain:

- File JSON tetap aman
- Pastikan `siteurl` Orion sudah benar (karena URL API dan proof memakai base URL dari `siteurl`)

---

## 9) Troubleshooting (Masalah Umum)

### 9.1 Guru tidak bisa membuka KAih Guru

Penyebab umum:

- Belum login → login dulu ke `/login.php`
- Akun bukan `kaih_teacher` → buat akun dari admin KAih atau set capability di user meta
- Guru belum punya relasi siswa → buat relasi di tab **Relasi**

### 9.2 “PIN belum diset oleh admin”

Solusi:

- Admin buka tab **Siswa**
- Edit siswa → isi PIN Ortu → Simpan

### 9.3 Menu KAih mengarah ke URL yang salah

Jika menu pernah terbentuk salah, plugin akan berusaha memperbaiki URL menu otomatis saat init.

Solusi cepat:

- Refresh halaman depan sekali
- Cek kembali menu di `Appearance → Menus`

### 9.4 Data siswa tidak sinkron

KAih menggunakan polling untuk sinkronisasi ringan.

Solusi:

- Refresh halaman siswa/guru
- Pastikan koneksi server stabil

---

## 10) Catatan Pengembangan Lanjutan (Opsional)

Jika Anda ingin alur “sekolah penuh”:

- Siswa tidak lagi memakai ID random dari browser
- Siswa login memakai NIS/NISN
- Guru hanya bisa melihat kelas/rombel tertentu
- Orang tua punya akun sendiri (bukan PIN)

KAih sudah punya fondasi master data + relasi. Tinggal dilanjutkan ke:

- “Mode Login Siswa”
- “Manajemen kelas”
- “Hak akses per kelas”

