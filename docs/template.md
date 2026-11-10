# Orion CMS – Dokumentasi Sistem Templating

Dokumen ini menjelaskan cara kerja sistem templating di Orion CMS, termasuk alur request hingga tema dirender dan bagaimana tema berinteraksi dengan core.

---

## 1. Gambaran Umum

- Orion CMS memakai arsitektur mirip WordPress, tetapi dipangkas dan disederhanakan.
- Entry point front‑end ada di `index.php` root, yang akan:
  - Mem‑bootstrap core via `orion-load.php`.
  - Menentukan tema aktif.
  - Me‑require `index.php` milik tema aktif.
- Tema bertanggung jawab penuh untuk membedakan tampilan (beranda, single post, dsb.) melalui fungsi kondisional seperti `is_single()`.

---

## 2. Alur Eksekusi Templating

Diagram alur teks dari request hingga tema dirender:

1. **Request Masuk**
   - Browser mengakses:
     - `http://domain.test/` → `index.php`
     - `http://domain.test/?p=123` → `index.php?p=123`

2. **Entry Point: `index.php` (root)**
   - Mendefinisikan:
     - `define('WP_USE_THEMES', true);`
   - Memuat environment:
     - `require __DIR__ . '/orion-load.php';`

3. **Bootstrap Core: `orion-load.php`**
   - Memuat:
     - `orion-config.php` (konfigurasi DB & konstanta).
     - File inti di `orion-includes/`:
       - `functions.php` (API utama + template engine).
       - `post.php` (API konten).
       - `user.php` (user & auth).
       - `schema.php` (install DB, user default).
       - `plugin.php` (sistem hooks).
       - `wp-compat.php` (lapisan kompatibilitas WordPress).
       - `security.php` (proteksi dasar).
   - Menyiapkan koneksi database dan struktur global yang dibutuhkan tema.

4. **Resolve Tema Aktif**
   - Jika `WP_USE_THEMES` bernilai `true`, `index.php` root akan:
     - Memanggil `get_template_directory()` dari `orion-includes/functions.php`.
     - Fungsi ini:
       - Membaca option `template` dari tabel `options`.
       - Memastikan folder tema dan `index.php` ada.
       - Jika tidak valid → fallback ke `orion-default`.

5. **Load Template Utama Tema**
   - Path yang dihasilkan:
     - `<ABSPATH>/orion-content/themes/<nama-tema>/index.php`
   - `index.php` root me‑`require_once` file tersebut.

6. **Di Dalam `index.php` Tema**
   - Tema memanggil:
     - `get_header();` → memuat `header.php` tema.
     - `get_footer();` → memuat `footer.php` tema.
   - Tema menggunakan fungsi kondisional untuk memilih layout:
     - `if (is_single()) { ... } else { ... }`
   - Tema menggunakan Loop untuk menampilkan konten:
     - `have_posts()`, `the_post()`, `the_title()`, `the_content()`, dsb.

7. **Header / Footer & Hooks**
   - `header.php` biasanya berisi:
     - Tag `<head>` + `<body>`.
     - Panggilan `wp_head()` sebelum `</head>` untuk memberi kesempatan plugin/core menyuntik script/style.
   - `footer.php` biasanya berisi:
     - Panggilan `wp_footer()` sebelum `</body>` untuk hook plugin.

Ringkasnya dalam bentuk panah:

```text
Request HTTP
  ↓
root/index.php
  ↓ (require orion-load.php)
Bootstrap core (config, DB, API, hooks, wp-compat)
  ↓
get_template_directory() → resolve tema aktif
  ↓
require theme/index.php
  ↓
get_header()  → locate header.php + wp_head() hooks
  ↓
Loop & template tags (have_posts(), the_post(), the_title(), ...)
  ↓
get_footer()  → locate footer.php + wp_footer() hooks
  ↓
HTML terkirim ke browser
```

---

## 3. Engine Templating Core

### 3.1. `get_header()` dan `get_footer()`

- Didefinisikan di `orion-includes/functions.php`.
- Pola kerja:
  - Trigger action:
    - `do_action('get_header', $name);`
    - `do_action('get_footer', $name);`
  - Susun daftar kandidat template:
    - Untuk header:
      - `header-{$name}.php` (jika `$name` diberikan).
      - `header.php`.
    - Untuk footer:
      - `footer-{$name}.php`.
      - `footer.php`.
  - Memanggil `locate_template()` untuk mencari file di direktori tema aktif, lalu `load_template()` untuk me‑require file tersebut.

### 3.2. `locate_template()` dan `load_template()`

- `locate_template($templates, $load, $require_once)`:
  - Loop semua kandidat nama file.
  - Cek `file_exists( get_template_directory() . '/' . $template_name )`.
  - Jika ketemu, kembalikan path tersebut dan (jika `$load === true`) langsung me‑require.
- `load_template($_template_file, $require_once)`:
  - Menyiapkan beberapa variabel global (mis. `$wp_query->query_vars` diekstrak).
  - Menjalankan `require` / `require_once` terhadap file template.

### 3.3. `get_template_directory()` & `get_template_directory_uri()`

- `get_template_directory()`:
  - Baca option `template`.
  - Validasi folder dan `index.php`.
  - Fallback ke `orion-default` jika tidak valid.
  - Mengembalikan path absolut ke direktori tema aktif.
- `get_template_directory_uri()`:
  - Logika sama, tetapi mengembalikan URL (untuk `<link>` dan `<script>`).

---

## 4. Layer Kompatibilitas WordPress (wp-compat)

Untuk memudahkan porting tema WordPress, Orion menyediakan **compat layer** di `orion-includes/wp-compat.php`:

- Kelas dan helper:
  - `WP_Error`, `is_wp_error()`.
- Fungsi terjemahan:
  - `__()`, `_e()`, `esc_html__()`, `esc_attr__()`, dll.
- Theme support & menu:
  - `add_theme_support()`, `register_nav_menus()`, `has_nav_menu()`, `wp_nav_menu()`.
- Template part:
  - `get_template_part($slug, $name = null)` → menggunakan `locate_template()` di core.
- Kondisional:
  - `is_single()`, `is_page()`, `is_search()`, `is_archive()` (sebagian masih stub).
- Posts & loop helpers:
  - `the_title()`, `the_content()`, `the_excerpt()`, `the_permalink()`, `get_permalink()`, `post_class()`, dll.

Dengan layer ini, tema bisa memakai hampir semua pola template WordPress dasar, selama tidak mengandalkan fungsi-fungsi yang belum di‑implementasi.

---

## 5. Data Konten di Template

API konten inti ada di `orion-includes/post.php`:

- `get_posts($args)`:
  - Query tabel `posts` dengan filter:
    - `post_type`, `post_status`, limit, offset, order.
  - Mengembalikan array objek post.
- `get_post_meta($post_id, $meta_key, $single)`:
  - Query tabel `postmeta`.
  - Mendukung pengambilan single value ataupun array.
- `get_the_post_thumbnail_url($post)`:
  - Membaca meta `_thumbnail_url` dari post.

