<?php
/**
 * Documentation Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );
require_once( 'admin-header.php' );
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800">Manual & Dokumentasi</h1>
    <p class="text-slate-600 mt-2">Panduan lengkap penggunaan dan pengembangan Orion CMS.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-6">
            <nav class="flex flex-col">
                <a href="#konsep" class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 text-slate-700 font-medium transition-colors">Konsep Dasar</a>
                <a href="#desain" class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 text-slate-700 font-medium transition-colors">Desain & UI</a>
                <a href="#instalasi" class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 text-slate-700 font-medium transition-colors">Instalasi</a>
                <a href="#tema" class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 text-slate-700 font-medium transition-colors">Tema</a>
                <a href="#plugin" class="px-4 py-3 hover:bg-slate-50 text-slate-700 font-medium transition-colors">Plugin</a>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <div class="lg:col-span-3 space-y-8">
        
        <!-- Konsep Dasar -->
        <section id="konsep" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 scroll-mt-24">
            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Konsep Dasar</h2>
            <div class="prose max-w-none text-slate-600">
                <p class="mb-4">
                    Orion CMS dibangun dengan filosofi <strong>"Lightweight & Modular"</strong>. Tidak seperti CMS tradisional yang memuat ribuan fitur yang jarang digunakan, Orion CMS memulai dengan core yang sangat kecil dan cepat.
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Native PHP:</strong> Dibangun di atas PHP murni tanpa framework berat, memastikan performa maksimal dan kompatibilitas tinggi.</li>
                    <li><strong>Modular:</strong> Fitur tambahan seperti Form, SEO, atau E-commerce ditambahkan melalui sistem Plugin.</li>
                    <li><strong>Database Efisien:</strong> Struktur tabel yang sederhana namun fleksibel untuk menangani Post, Page, dan Metadata.</li>
                </ul>
            </div>
        </section>

        <!-- Desain -->
        <section id="desain" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 scroll-mt-24">
            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Desain & UI</h2>
            <div class="prose max-w-none text-slate-600">
                <p class="mb-4">
                    Antarmuka Admin Orion CMS menggunakan <strong>Tailwind CSS</strong> dan <strong>Alpine.js</strong> untuk menciptakan pengalaman pengguna yang modern, responsif, dan interaktif tanpa bloat JavaScript framework besar.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-slate-700 mb-2">Admin Dashboard</h4>
                        <p class="text-sm">Layout sidebar yang bersih, navigasi intuitif, dan skema warna yang dapat disesuaikan (mendukung mode gelap/terang melalui tema admin).</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-slate-700 mb-2">Frontend</h4>
                        <p class="text-sm">Tema frontend memiliki kebebasan penuh menggunakan framework CSS apapun (Bootstrap, Tailwind, Bulma, atau CSS murni).</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Instalasi -->
        <section id="instalasi" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 scroll-mt-24">
            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Cara Instalasi</h2>
            <div class="prose max-w-none text-slate-600">
                <ol class="list-decimal pl-5 space-y-3">
                    <li>
                        <strong>Persiapan:</strong> Pastikan server Anda menjalankan PHP 7.4+ dan MySQL 5.7+.
                    </li>
                    <li>
                        <strong>Unduh & Ekstrak:</strong> Letakkan file Orion CMS di direktori root web server Anda (misalnya <code>htdocs/orion</code>).
                    </li>
                    <li>
                        <strong>Database:</strong> Buat database baru di phpMyAdmin (misalnya <code>orion_db</code>).
                    </li>
                    <li>
                        <strong>Konfigurasi:</strong> Salin file <code>orion-config-sample.php</code> menjadi <code>orion-config.php</code> dan sesuaikan detail database:
                        <pre class="bg-slate-800 text-slate-200 p-3 rounded-md mt-2 text-sm overflow-x-auto">
define('DB_NAME', 'orion_db');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');</pre>
                    </li>
                    <li>
                        <strong>Selesai:</strong> Buka website di browser dan login dengan akun default (jika disediakan) atau ikuti wizard instalasi.
                    </li>
                </ol>
            </div>
        </section>

        <!-- Tema -->
        <section id="tema" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 scroll-mt-24">
            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Pengembangan Tema</h2>
            <div class="prose max-w-none text-slate-600">
                <p class="mb-4">
                    Tema di Orion CMS terletak di folder <code>orion-content/themes/</code>. Struktur tema standar meliputi:
                </p>
                <ul class="space-y-2 font-mono text-sm bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <li><span class="text-blue-600">style.css</span> - Informasi tema (meta data) dan style utama</li>
                    <li><span class="text-blue-600">index.php</span> - Template utama</li>
                    <li><span class="text-blue-600">header.php</span> - Bagian kepala (head, nav)</li>
                    <li><span class="text-blue-600">footer.php</span> - Bagian kaki (scripts, copyright)</li>
                    <li><span class="text-blue-600">functions.php</span> - Fungsi tambahan tema (opsional)</li>
                </ul>
                <p class="mt-4">
                    Gunakan fungsi bawaan seperti <code>get_header()</code>, <code>get_footer()</code>, dan Loop standar untuk menampilkan konten.
                </p>
            </div>
        </section>

        <!-- Plugin -->
        <section id="plugin" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 scroll-mt-24">
            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Pengembangan Plugin</h2>
            <div class="prose max-w-none text-slate-600">
                <p class="mb-4">
                    Plugin memungkinkan Anda menambah fitur tanpa mengubah core. Plugin berada di <code>orion-content/plugins/</code>.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <strong>Tips:</strong> Gunakan Hooks (Actions dan Filters) untuk mengintervensi proses eksekusi CMS.
                    </p>
                </div>
                <p>
                    Setiap plugin harus memiliki file utama dengan komentar header standar agar terbaca oleh sistem:
                </p>
                <pre class="bg-slate-800 text-slate-200 p-3 rounded-md mt-2 text-sm overflow-x-auto">
/**
 * Plugin Name: Nama Plugin Anda
 * Description: Deskripsi singkat fungsi plugin.
 * Version: 1.0
 * Author: Nama Anda
 */</pre>
            </div>
        </section>

    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>