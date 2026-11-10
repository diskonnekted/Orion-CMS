# Petunjuk Dasar Orion CMS

Dokumen ini menjelaskan cara:

- Login ke dashboard admin
- Mengelola tema (themes)
- Mengelola plugin

Semua langkah di bawah ini berlaku untuk instalasi Orion CMS standar di server lokal maupun hosting.

---

## 1. Login ke Dashboard Admin

### 1.1. URL Login

- Buka browser dan arahkan ke halaman login:
  - Jika di lokal (sesuai server bawaan): `http://localhost:8000/login.php`
  - Atau, jika domain sudah diset: `https://namadomain-anda.com/login.php`

Halaman ini menggunakan file [`login.php`](file:///d:/xampp/htdocs/orion/login.php) sebagai gerbang masuk ke admin.

### 1.2. Masuk dengan Akun Admin

Di halaman login:

1. Isi **Username or Email Address** dengan username/email admin yang sudah dibuat saat instalasi.
2. Isi **Password** dengan password admin.
3. Centang **Remember Me** jika ingin tetap login lebih lama pada perangkat tersebut.
4. Klik tombol **Sign In**.

Jika login berhasil, Anda akan diarahkan otomatis ke:

- `orion-admin/index.php` → Dashboard utama admin.

### 1.3. Logout

Untuk keluar dari dashboard admin:

1. Dari menu admin (bar atas atau samping), pilih opsi **Log Out** (jika tersedia di tema/admin header).
2. Atau akses langsung:
   - `login.php?action=logout`

Setelah logout, Anda akan kembali ke halaman login.

---

## 2. Mengelola Tema (Themes)

Semua pengelolaan tema dilakukan melalui layar **Themes** di admin.

### 2.1. Membuka Halaman Themes

1. Login ke dashboard admin.
2. Dari menu kiri, pilih **Appearance → Themes** (atau langsung akses):
   - `orion-admin/themes.php`

Halaman ini dibangun oleh file [`orion-admin/themes.php`](file:///d:/xampp/htdocs/orion/orion-admin/themes.php) dan menampilkan:

- Tema aktif saat ini.
- Daftar semua tema yang ada di folder `orion-content/themes/`.

### 2.2. Mengaktifkan Tema

Di grid daftar tema:

1. Cari kartu tema yang ingin Anda aktifkan.
2. Klik tombol **Activate**.
3. Jika berhasil, akan ada notifikasi **Theme activated successfully** dan tema tersebut diberi label **Active**.

Secara teknis, ini akan mengubah opsi `template` di database ke slug tema tersebut sehingga front‑end situs memuat tema baru.

### 2.3. Menghapus Tema

> Penting: Tema yang sedang aktif **tidak bisa** dihapus. Anda harus mengaktifkan tema lain terlebih dahulu.

Langkah:

1. Di halaman **Themes**, pastikan tema yang ingin dihapus **bukan** tema aktif.
2. Pada kartu tema tersebut, klik ikon **Delete** (ikon tempat sampah / tombol delete).
3. Konfirmasi dialog:
   - “Are you sure you want to delete this theme? This action cannot be undone.”
4. Jika berhasil, Anda akan melihat pesan **Theme deleted successfully**.

File tema akan dihapus dari folder:

- `orion-content/themes/nama-tema/`

### 2.4. Meng‑upload / Install Tema Baru (File .zip)

1. Buka **orion-admin/themes.php**.
2. Klik tombol **Add New Theme** di pojok kanan atas.
3. Di panel **Upload Theme**:
   - Klik input file **Select Zip File**.
   - Pilih file ZIP tema dari komputer Anda (misalnya `orion-portfolio.zip`).
4. Klik tombol **Install Now**.
5. Jika proses unzip sukses:
   - Tema akan diekstrak ke `orion-content/themes/`.
   - Anda bisa langsung melihatnya di grid daftar tema dan kemudian menekan **Activate**.

Jika terjadi error (file bukan ZIP, gagal unzip, dsb.), akan ada pesan error di URL (misal `?error=invalid_file_type` atau `?error=unzip_failed`). Pastikan file ZIP valid dan hak akses folder `orion-content/themes/` sudah benar.

---

## 3. Mengelola Plugin

Plugin menambah fitur baru untuk Orion CMS. Pengelolaannya dilakukan melalui halaman **Plugins**.

### 3.1. Membuka Halaman Plugins

1. Login ke dashboard admin.
2. Dari menu kiri, pilih **Plugins** (atau akses langsung):
   - `orion-admin/plugins.php`

Halaman ini dibangun oleh file [`orion-admin/plugins.php`](file:///d:/xampp/htdocs/orion/orion-admin/plugins.php) dan menampilkan:

- Jumlah total plugin, berapa yang aktif.
- Daftar plugin dalam bentuk kartu dengan nama, versi, penulis, dan deskripsi.

### 3.2. Mengaktifkan / Menonaktifkan Plugin

Di grid plugin:

- Untuk **mengaktifkan** plugin:
  1. Temukan plugin yang ingin diaktifkan.
  2. Klik tombol **Activate**.
  3. Plugin akan ditambahkan ke daftar `active_plugins` di database dan otomatis diload saat Orion bootstrap.

- Untuk **menonaktifkan** plugin:
  1. Pada plugin yang sudah aktif (diberi label **Active**), klik tombol **Deactivate**.
  2. Plugin akan dihapus dari daftar `active_plugins`, sehingga tidak lagi diload dan fungsinya berhenti berjalan.

Perubahan status plugin langsung berlaku tanpa perlu restart server.

### 3.3. Meng‑upload / Install Plugin Baru (File .zip)

1. Di halaman **Plugins**, pada bagian **Upload New Plugin** di atas grid:
   - Klik input file, pilih file ZIP plugin (misalnya `orion-shop-manager.zip`).
2. Klik tombol **Install Now**.
3. Sistem akan:
   - Mengekstrak ZIP ke folder sementara.
   - Mendeteksi apakah ZIP berisi satu folder atau banyak file.
   - Memindahkan hasil ekstrak ke `orion-content/plugins/nama-folder/`.
4. Jika sukses, akan muncul pesan **Plugin installed successfully.**
5. Setelah itu, plugin akan muncul di daftar plugin dan bisa Anda **Activate**.

Jika muncul pesan error:

- **“Please upload a valid ZIP file.”** → Pastikan ekstensi file adalah `.zip`.
- **“A plugin with the same folder name already exists.”** → Ubah nama folder di ZIP atau hapus plugin lama terlebih dahulu.

### 3.4. Lokasi File Plugin

Semua plugin disimpan di:

- `orion-content/plugins/`

Struktur umum:

- Plugin single file: `orion-content/plugins/hello-orion.php`
- Plugin dalam folder: `orion-content/plugins/orion-form/orion-form.php`

File utama plugin biasanya berisi header seperti:

```php
/*
Plugin Name: Nama Plugin
Description: Deskripsi singkat plugin.
Version: 1.0.0
Author: Nama Developer
*/
```

Header ini digunakan oleh halaman **Plugins** untuk menampilkan nama, deskripsi, versi, dan penulis.

---

## 4. Tips Keamanan & Praktik Baik

- Jangan membagikan URL login (`/login.php`), username, dan password admin ke pihak yang tidak dipercaya.
- Gunakan password yang kuat dan unik.
- Batasi hak akses plugin dan tema:
  - Hanya install plugin/tema dari sumber yang Anda percaya.
  - Hapus tema dan plugin yang tidak digunakan untuk mengurangi permukaan serangan.
- Saat mem‑upload tema atau plugin di server produksi:
  - Pastikan hak akses folder `orion-content/themes/` dan `orion-content/plugins/` sudah benar (boleh ditulis oleh PHP).
- Untuk debugging lokal, Anda bisa mengaktifkan `ORION_DEBUG` di `orion-config.php`, tetapi hindari mengaktifkannya di server publik.

---

Jika Anda membutuhkan panduan lebih lanjut (misalnya cara membuat plugin sendiri atau cara menyesuaikan tema), kita bisa menambahkan bagian lanjutan di dokumen ini sesuai kebutuhan Anda.

