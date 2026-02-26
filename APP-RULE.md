# ORION CMS – APP RULE

Dokumen ini berisi aturan baku untuk:
- Pembuatan **tema**
- Pembuatan **plugin**
- **Konfigurasi tema & plugin**
- **Manajemen aktivasi/non-aktivasi** tema dan plugin

Semua tema dan plugin baru **wajib** mengikuti aturan di bawah ini.

---

## 1. Aturan Global

- **Tidak ada auto-hapus data**
  - Mengganti tema atau menonaktifkan plugin **tidak boleh** menghapus:
    - baris data di tabel khusus (mis. `orion_products`)
    - opsi konfigurasi (`get_option` / `update_option`)
  - Penghapusan data hanya boleh dilakukan melalui aksi eksplisit admin (mis. tombol “hapus data” dengan konfirmasi).

- **Pola PRG (Post–Redirect–Get) di Admin**
  - Semua form di admin **wajib**:
    - memproses `POST`
    - menyimpan ke database
    - lalu `header('Location: ...')` dan `exit;`
  - Redirect harus terjadi **sebelum** ada output HTML (termasuk spasi).

- **Upload file**
  - Semua upload (gambar/logo/hero) menggunakan direktori di bawah:
    - `orion-content/uploads/...` (untuk konten situs & tema)
    - `orion-content/uploads/settings/...` (untuk pengaturan tampilan)
  - Ekstensi gambar yang diizinkan minimal: `jpg`, `jpeg`, `png`, `gif`, `webp`.
  - Nama file disarankan mengandung prefix konteks + timestamp (mis. `garage-hero-<time>.jpg`).

- **Keamanan dasar**
  - Selalu validasi nilai enum, tipe, dan angka sebelum disimpan.
  - Gunakan `real_escape_string` / prepared statement untuk query manual.

---

## 2. Aturan Plugin

### 2.1 Struktur dasar plugin

- Plugin utama ditempatkan di:
  - `orion-content/plugins/<slug>/<slug>.php`
- Tambahan file admin / manager:
  - `orion-content/plugins/<slug>/manager.php` (atau nama serupa) untuk UI admin khusus.

### 2.2 Aktivasi plugin → menu di sidebar

- Setiap plugin yang diaktifkan **wajib**:
  - Menambahkan satu entri menu di sidebar admin (bagian plugin dinamis).
  - Tautan sidebar mengarah ke halaman manajemen plugin (mis. Shop Manager).

- Sistem inti akan membaca daftar plugin aktif dari:
  - `get_option('active_plugins', array())`  
  - Jika plugin memiliki `manager.php`, maka:
    - Sidebar menampilkan link ke `manager.php`.

### 2.3 Perilaku saat plugin non-aktif

- Saat plugin dinonaktifkan:
  - Menu sidebar plugin **hilang otomatis** (berdasarkan `active_plugins`).
  - Semua data di tabel khusus plugin (mis. `orion_products`) **tetap ada**.
  - Semua konfigurasi plugin yang disimpan via `update_option` **tetap ada**.

- Plugin **tidak boleh**:
  - menghapus tabel pada saat deactivation (kecuali ada perintah eksplisit user).
  - menghapus option konfigurasi tanpa konfirmasi user.

### 2.4 Contoh pola plugin katalog (Shop Manager)

Plugin dengan katalog (produk/jasa) harus:

- Memiliki tabel khusus, misalnya:
  - `{$table_prefix}orion_products`
- Menyimpan field minimal:
  - `sku`, `name`, `description`, `price`, `sale_price`, `type`, `stock_status`, `stock_quantity`, `unit`, `category`, `image`.
- Menyediakan fungsi helper untuk front-end:
  - `orion_shop_get_products($limit)`
  - `orion_shop_get_price($product_id)`
  - `orion_shop_get_stock($product_id)`
  - `orion_shop_get_whatsapp_url($product_id)`

---

## 3. Aturan Tema

### 3.1 Struktur dasar tema

- Tema berada di:
  - `orion-content/themes/<slug>/`
- File standar:
  - `style.css` (meta tema + CSS)
  - `index.php` (template utama)
  - `header.php`, `footer.php`
  - `functions.php` (fungsi tema)

### 3.2 Tema aktif → menu pengaturan di submenu Tema

- Tema yang menyediakan pengaturan khusus harus:
  - Menggunakan halaman admin global: `orion-admin/theme-settings.php`
  - `theme-settings.php` akan menampilkan form pengaturan **berdasarkan tema aktif** (`get_option('template')`).

- Aturan:
  - **Tema aktif** wajib menambahkan blok pengaturan khususnya di `theme-settings.php`.
  - Tema lain yang tidak punya pengaturan khusus akan menampilkan pesan standar:
    - “Tema saat ini belum memiliki halaman pengaturan khusus.”

### 3.3 Integrasi plugin dengan tema

- Tema boleh bergantung pada plugin tertentu (mis. Shop Manager), tetapi:
  - Selalu cek dengan `function_exists()` sebelum memanggil fungsi plugin.
  - Tidak boleh fatal error jika plugin nonaktif.

- Contoh:
  - Untuk memeriksa plugin Shop Manager:
    - Gunakan helper seperti `orion_garage_is_shop_manager_active()` yang membaca `active_plugins`.
  - Menu “Produk dan Layanan” di header hanya tampil jika:
    - Halaman `Shop` ada, dan
    - Plugin Shop Manager aktif.

---

## 4. Aturan Konfigurasi Tema

### 4.1 Penyimpanan pengaturan tema

- Semua pengaturan tema harus disimpan via `update_option()` dengan prefix jelas, misalnya:
  - `orion_garage_front_title`
  - `orion_garage_front_subtitle`
  - `orion_garage_front_cta_text`
  - `orion_garage_front_cta_url`
  - `orion_garage_front_hero_image`
  - `orion_garage_store_name`
  - `orion_garage_store_address`
  - `orion_garage_store_phone`
  - `orion_garage_store_whatsapp`
  - `orion_garage_store_hours`
  - `orion_garage_map_embed`

- Pengaturan ini **tetap tersimpan** ketika:
  - Tema diganti.
  - Plugin terkait dinonaktifkan.

### 4.2 Upload gambar dari pengaturan tema

- Gunakan pola kombinasi:
  - Input teks **URL gambar**.
  - Input file untuk **upload dari PC/HP**.
- Jika file diupload:
  - Simpan file ke `orion-content/uploads/settings/`.
  - Isi option URL gambar dengan `site_url('/orion-content/uploads/settings/<filename>')`.

### 4.3 Snapshot konfigurasi & produk

- Tema yang terintegrasi dengan plugin katalog (mis. Orion Garage + Shop Manager) **dianjurkan** menyediakan:
  - Tombol “Simpan Konfigurasi & Produk” di pengaturan tema.

- Tombol ini:
  - Mengambil semua option tema terkait.
  - Mengambil seluruh baris produk dari tabel plugin.
  - Menyimpan ke satu option snapshot, contoh:
    - `orion_garage_backup_snapshot`.

- Snapshot ini:
  - Tidak terhapus otomatis ketika tema/plugin berubah.
  - Hanya boleh dihapus melalui aksi eksplisit admin.

---

## 5. Aturan Tampilan & UX Admin

- **Tata letak pengaturan tema**
  - Gunakan gaya yang konsisten dengan admin:
    - Card `bg-white rounded-xl shadow p-6`.
    - Grid `grid grid-cols-1 md:grid-cols-2 gap-6` untuk field.
  - Label dan bantuan:
    - Setiap field memiliki `<label>` jelas + teks bantu kecil (`text-xs text-gray-500`).

- **Tombol aksi**
  - Warna utama admin mengikuti skema `admin_color_scheme`.
  - Untuk aksi utama:
    - `bg-orion-600 hover:bg-orion-700 text-white`.
  - Untuk aksi pendukung:
    - `bg-slate-800 hover:bg-slate-900 text-white` atau border variant.

- **Preview & live feedback**
  - Untuk pengaturan warna / tampilan admin gunakan:
    - Radio card dengan preview warna (lihat implementasi `admin_color_scheme`).

---

## 6. Aturan Manajemen Data & Hapus Data

- **Default: aman, tidak menghapus apa pun**
  - Secara default, tema dan plugin **tidak boleh** menghapus:
    - tabel,
    - baris data,
    - option konfigurasi.

- **Jika perlu fitur “Buang data”**
  - Harus:
    - Ditempatkan di halaman pengaturan plugin/tema.
    - Meminta konfirmasi eksplisit (mis. konfirmasi teks).
    - Mendokumentasikan dengan jelas apa yang akan dihapus:
      - Hanya snapshot?
      - Juga tabel produk?

---

## 7. Ringkasan

- Tema dan plugin mengikuti prinsip:
  - **Data-first**: konfigurasi & produk tidak hilang hanya karena tema/plugin diganti.
  - **Integrasi lunak**: tema dan plugin saling memeriksa dengan `function_exists` dan `active_plugins` sebelum dipakai.
  - **Pengaturan terpusat**: pengaturan tema melalui `orion-admin/theme-settings.php`, plugin melalui halaman managernya masing-masing.
  - **UX konsisten**: tampilan admin mengikuti komponen Tailwind yang sudah digunakan.

Dokumen ini menjadi rujukan resmi ketika membuat atau mengembangkan tema dan plugin baru di Orion CMS.

