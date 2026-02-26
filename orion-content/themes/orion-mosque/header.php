<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($post) ? $post->post_title . ' - ' : ''; ?>Orion Mosque</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mosque: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#ACE1AF',
                            300: '#93C572',
                            400: '#98FB98',
                            500: '#50C878',
                            600: '#006A4E',
                            700: '#355E3B',
                            800: '#556B2F',
                            900: '#022c22'
                        },
                        hunter: '#386641',
                        'sage-green': '#6a994e',
                        'yellow-green': '#a7c957',
                        'vanilla-cream': '#f2e8cf',
                        'blushed-brick': '#bc4749'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-mosque-50 text-slate-900 antialiased flex flex-col min-h-screen">

<header class="bg-white/90 backdrop-blur shadow-sm sticky top-0 z-40 border-b border-mosque-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl overflow-hidden bg-white flex items-center justify-center shadow-lg">
                    <img src="<?php echo site_url('/orion-content/themes/orion-mosque/logo.png'); ?>" alt="Orion Mosque" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-mosque-700">Orion Mosque</h1>
                    <p class="text-xs text-slate-500 tracking-[0.18em] uppercase">Masjid Modern & Informatif</p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <a href="<?php echo site_url(); ?>" class="text-slate-600 hover:text-mosque-600 transition">Beranda</a>
                <a href="index.php#jadwal" class="text-slate-600 hover:text-mosque-600 transition">Jadwal Shalat</a>
                <a href="<?php echo site_url('/orion-content/themes/orion-mosque/doa-harian.php'); ?>" class="text-slate-600 hover:text-mosque-600 transition">Doa Harian</a>
                <a href="<?php echo site_url('/orion-content/themes/orion-mosque/quran.php'); ?>" class="text-slate-600 hover:text-mosque-600 transition">Al-Qur'an</a>
                <a href="index.php#kegiatan" class="text-slate-600 hover:text-mosque-600 transition">Kegiatan</a>
                <a href="index.php#berita" class="text-slate-600 hover:text-mosque-600 transition">Berita</a>
                <a href="index.php#donasi" class="inline-flex items-center px-4 py-2 rounded-full bg-mosque-600 text-white shadow-md hover:bg-mosque-500 transition">
                    Donasi
                </a>
            </nav>

            <button class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full border border-mosque-200 text-mosque-700 focus:outline-none" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav" class="md:hidden hidden border-t border-mosque-100 bg-white">
        <div class="px-4 py-4 space-y-3 text-sm font-semibold">
            <a href="<?php echo site_url(); ?>" class="block text-slate-700">Beranda</a>
            <a href="index.php#jadwal" class="block text-slate-700">Jadwal Shalat</a>
            <a href="<?php echo site_url('/orion-content/themes/orion-mosque/doa-harian.php'); ?>" class="block text-slate-700">Doa Harian</a>
            <a href="<?php echo site_url('/orion-content/themes/orion-mosque/quran.php'); ?>" class="block text-slate-700">Al-Qur'an</a>
            <a href="index.php#kegiatan" class="block text-slate-700">Kegiatan</a>
            <a href="index.php#berita" class="block text-slate-700">Berita</a>
            <a href="index.php#donasi" class="inline-flex mt-2 items-center justify-center w-full px-4 py-2 rounded-full bg-mosque-600 text-white shadow-md">
                Donasi
            </a>
        </div>
    </div>
</header>

<main class="flex-1">
