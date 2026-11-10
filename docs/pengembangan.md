# Rekomendasi Pengembangan Orion CMS

Dokumen ini merangkum beberapa saran pengembangan untuk meningkatkan kemampuan, keamanan, dan kenyamanan penggunaan Orion CMS.

---

## 1. Keamanan dan Otentikasi

- Tambah flow reset password bawaan:
  - Halaman “lupa password” dengan token sekali pakai (disimpan di usermeta).
  - Form memasukkan email/username → kirim link reset → set password baru.
- Perkuat sanitasi konten:
  - Implementasi `wp_kses_post()` dan filter HTML untuk konten yang bisa diinput user.
  - Batasi tag dan atribut yang diizinkan untuk mencegah XSS.
- Proteksi CSRF pada form admin:
  - Tambah nonce/token pada form di `/orion-admin/` (posts, pages, settings, users).
  - Validasi token sebelum eksekusi operasi tulis/hapus di server.
- Hardening login:
  - Manfaatkan modul security yang sudah ada untuk:
    - Menambah delay progresif untuk login gagal berulang.
    - Menyimpan log login (sukses dan gagal) ke DB agar bisa dipantau lewat admin.

---

## 2. Mesin Templating dan Routing

- Tambah hierarki template dasar:
  - Dukungan otomatis untuk:
    - `single.php` untuk single post.
    - `page.php` untuk halaman statis.
    - `archive.php` atau `category.php` untuk listing.
  - Core melakukan routing dasar:
    - Jika `is_single()` → coba `single.php`, fallback ke `index.php`.
    - Jika `is_page()` → coba `page.php`, fallback ke `index.php`.
- Perkuat dukungan featured image:
  - Implementasi `has_post_thumbnail()` dan `the_post_thumbnail()` di `wp-compat`.
  - Gunakan meta `_thumbnail_url` sebagai sumber utama, sehingga tema tidak perlu menulis ulang logika meta di setiap tempat.
- Permalink lebih rapi (opsional):
  - Tambah generator `.htaccess` sederhana dari admin untuk:
    - Memetakan `/post/slug` ke `index.php?p=ID`.
    - Memetakan `/page/slug` ke `index.php?page_id=ID`.
  - Tambah helper untuk menghasilkan URL tersebut dari core.

---

## 3. API Konten dan Taksonomi

- Custom post type yang lebih formal:
  - Tambah API `register_post_type()` gaya WordPress, minimal mendukung:
    - Label.
    - `public` / `show_in_menu`.
    - `supports` (title, editor, thumbnail, dsb.).
  - Gunakan ini untuk memisahkan:
    - `post` (artikel).
    - `product` (produk e-commerce).
    - `member` (data anggota).
- API taxonomy deklaratif:
  - Tambah `register_taxonomy()` untuk mendefinisikan taxonomy baru (misal `product_cat`, `member_group`).
  - Integrasikan dengan sistem terms yang sudah ada (`terms`, `term_taxonomy`, `term_relationships`).

---

## 4. Panel Admin dan Pengalaman Pengguna

- Filter dan pencarian di layar konten:
  - Tambah filter kategori dan search box di:
    - `posts.php`, `pages.php`, dan layar khusus (produk, member).
  - Perluasan query `get_posts()` untuk menangani parameter kategori dan teks pencarian.
- Editor konten yang lebih nyaman:
  - Integrasi WYSIWYG/editor markdown ringan pada form konten:
    - Menggantikan textarea polos di halaman tambah/edit post dan page.
  - Tetap simpan konten sebagai HTML di DB agar kompatibel dengan tema.
- Notifikasi dan PRG konsisten:
  - Terapkan pola Post–Redirect–Get (PRG) di semua form admin:
    - Setelah POST sukses, redirect ke URL `?updated=...` atau `?message=...`.
    - Tampilkan notifikasi berdasarkan parameter query.

---

## 5. Plugin dan Ekstensi

- Lifecycle plugin:
  - Tambah hook:
    - `register_activation_hook($file, $callback)`.
    - `register_deactivation_hook($file, $callback)`.
  - Dipanggil saat plugin diaktifkan/nonaktifkan dari admin, agar plugin dapat:
    - Membuat tabel baru.
    - Menambah option default.
    - Membersihkan data jika diperlukan.
- Dokumentasi hooks:
  - Buat daftar action/filter utama di dokumentasi:
    - `wp_head`, `wp_footer`, `get_header`, `get_footer`.
    - Hook pada proses simpan post, hapus user, update settings, dsb.

---

## 6. Performa dan Skalabilitas

- Caching `get_option()`:
  - Tambah cache in-memory (static array) per request:
    - Jika option sudah diambil sekali, kembalikan dari cache tanpa query DB.
- Optimasi index database:
  - Pastikan index tepat untuk kolom yang sering dipakai:
    - `posts.post_type`, `posts.post_status`, `posts.post_date`.
    - `postmeta.post_id`, `postmeta.meta_key`.
  - Ini akan mempercepat `get_posts()` dan `get_post_meta()` pada data besar.
- Asset bundling untuk admin:
  - Pertimbangkan build lokal Tailwind/Alpine (CSS/JS yang di-minify) untuk produksi:
    - Mengurangi ketergantungan pada CDN.
    - Mempercepat loading dan meningkatkan kontrol versi.

---

## 7. Developer Experience

- Installer CLI:
  - Script PHP CLI sederhana untuk:
    - Cek koneksi database.
    - Menjalankan `orion_install()`.
    - Membuat user admin awal dengan kredensial yang ditentukan.
- Mode debug yang terstruktur:
  - Gunakan konstanta `ORION_DEBUG` untuk:
    - Mengatur level error reporting.
    - Mengaktifkan logging query lambat ke file log.
- Testing dasar:
  - Tambah beberapa tes otomatis minimal (bisa dengan PHPUnit atau script custom) untuk:
    - `get_posts()`, `get_post_meta()`.
    - `wp_create_user()`, `wp_authenticate()`.
  - Membantu menjaga stabilitas saat core berkembang.

