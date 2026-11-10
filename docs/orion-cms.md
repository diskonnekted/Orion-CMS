# Spesifikasi Teknis Orion CMS

## 1. Ikhtisar Sistem
Orion CMS adalah Content Management System (CMS) ringan yang dibangun menggunakan PHP native dan database MySQL. Sistem ini dirancang dengan arsitektur modular yang menyerupai struktur WordPress, memungkinkan pengembangan tema dan plugin dengan pola yang familiar bagi pengembang WordPress, namun dengan *footprint* yang jauh lebih ringan dan performa yang dioptimalkan.

## 2. Arsitektur Sistem

### 2.1 Struktur Direktori
Sistem file Orion CMS terorganisir sebagai berikut:

*   **`root`**:
    *   `index.php`: Entry point utama untuk front-end.
    *   `orion-load.php`: Bootstrapper sistem, memuat konfigurasi dan library inti.
    *   `orion-config.php`: File konfigurasi database dan environment.
*   **`orion-admin/`**: Panel administrasi backend (Dashboard, Manajemen Konten, Pengaturan).
*   **`orion-includes/`**: Library inti sistem (API, Database Abstraction, Helper Functions).
    *   `functions.php`: Fungsi global dan koneksi database.
    *   `plugin.php`: Sistem Hooks (Action & Filter).
    *   `post.php`: API manajemen konten (CRUD post).
    *   `schema.php`: Definisi struktur database.
*   **`orion-content/`**: Direktori konten pengguna.
    *   `themes/`: Tema website.
    *   `plugins/`: Ekstensi fungsionalitas.
    *   `uploads/`: Penyimpanan media.

### 2.2 Alur Eksekusi (Bootstrapping)
1.  **Request Masuk**: Pengguna mengakses `index.php`.
2.  **Inisialisasi**: `index.php` memuat `orion-load.php`.
3.  **Konfigurasi**: `orion-load.php` memuat `orion-config.php` untuk kredensial DB dan konstanta global.
4.  **Core Loading**: Memuat library inti dari `orion-includes/`.
5.  **Theme Loading**: `index.php` memuat file tema aktif (misal: `orion-content/themes/orion-one/index.php`).

## 3. Basis Data (Schema)
Orion CMS menggunakan struktur tabel relasional yang mirip dengan skema WordPress standar:

*   **`orion_posts`**: Menyimpan konten utama (artikel, halaman).
    *   Kolom: `ID`, `post_author`, `post_date`, `post_content`, `post_title`, `post_status`, `post_type`.
*   **`orion_postmeta`**: Metadata tambahan untuk post.
*   **`orion_users`**: Data pengguna dan otentikasi.
*   **`orion_usermeta`**: Metadata tambahan pengguna (termasuk capabilities).
*   **`orion_options`**: Pengaturan global sistem (key-value store).
*   **`orion_terms`, `orion_term_taxonomy`, `orion_term_relationships`**: Sistem taksonomi untuk kategori dan tagging.

## 4. API & Fitur Utama

### 4.1 Sistem Plugin (Hooks)
Orion mengimplementasikan sistem Event-Driven menggunakan Hooks, memungkinkan modifikasi perilaku inti tanpa mengubah file inti.
*   **Actions**: `add_action($tag, $callback)`, `do_action($tag)`
*   **Filters**: `add_filter($tag, $callback)`, `apply_filters($tag, $value)`
*   Implementasi terdapat di `orion-includes/plugin.php`.

### 4.2 Theme Engine
Tema di Orion CMS menggunakan file PHP standar untuk rendering.
*   **Lokasi**: `orion-content/themes/[nama-tema]/`
*   **Struktur File Standar**:
    *   `index.php`: Template utama.
    *   `header.php` & `footer.php`: Komponen layout parsial.
    *   `functions.php`: Logika khusus tema.
    *   `style.css`: Stylesheet utama.
*   **Fungsi Template**:
    *   `get_header()`: Memuat `header.php`.
    *   `get_footer()`: Memuat `footer.php`.
    *   `get_template_directory()`: Path absolut ke direktori tema aktif.

### 4.3 Content API
Fungsi-fungsi untuk berinteraksi dengan konten database:
*   `get_posts($args)`: Mengambil daftar post berdasarkan kriteria.
*   `get_post($id)`: Mengambil detail satu post.
*   `wp_insert_post($data)`: Membuat atau memperbarui post.
*   `get_option($key)`: Mengambil nilai konfigurasi.

### 4.4 Routing
Orion CMS menggunakan routing berbasis Query String sederhana:
*   Halaman Depan: `index.php`
*   Single Post: `index.php?p=[ID]`
*   Page: `index.php?page_id=[ID]`
*   Pagination: `index.php?paged=[NUMBER]`

## 5. Kompatibilitas
Orion CMS menyediakan lapisan kompatibilitas terbatas dengan fungsi-fungsi WordPress untuk memudahkan porting tema/plugin sederhana:
*   Kelas Mock `WP_Query`.
*   Fungsi standar seperti `get_the_post_thumbnail_url`, `get_the_terms`.
*   Struktur tabel database yang hampir identik.

## 6. Admin Panel
Panel administrasi (`/orion-admin/`) menyediakan antarmuka untuk:
*   **Dashboard**: Statistik ringkas (Total Post, User, Kategori).
*   **Content Management**: Editor post dan page.
*   **User Management**: Pengelolaan pengguna dan role.
*   **Settings**: Konfigurasi situs (Judul, Tema Aktif).
