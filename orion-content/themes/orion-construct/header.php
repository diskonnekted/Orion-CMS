<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('blogname', 'Orion Construction')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-950 text-slate-50 antialiased'); ?>>
<header class="border-b border-slate-800 bg-slate-950/95 backdrop-blur">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-6">
        <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center">
                <img src="<?php echo site_url('/assets/img/logo.png'); ?>" alt="<?php echo htmlspecialchars(get_option('blogname', 'Orion Construction')); ?>" class="max-h-8 max-w-full">
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold tracking-wide uppercase text-slate-50"><?php echo htmlspecialchars(get_option('blogname', 'Orion Construction')); ?></span>
                <span class="text-xs text-slate-300">Kontraktor sipil dan bangunan profesional</span>
            </div>
        </a>
        <nav class="hidden md:flex items-center gap-6 text-xs font-medium">
            <a href="<?php echo site_url(); ?>" class="text-slate-300 hover:text-white transition">Beranda</a>
            <a href="<?php echo site_url(); ?>/?page=tentang" class="text-slate-300 hover:text-white transition">Tentang Perusahaan</a>
            <a href="<?php echo site_url(); ?>/?page=layanan" class="text-slate-300 hover:text-white transition">Layanan</a>
            <a href="<?php echo site_url(); ?>/?page=proyek" class="text-slate-300 hover:text-white transition">Proyek</a>
            <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold shadow-lg shadow-amber-500/40 transition">
                Minta Penawaran
            </a>
        </nav>
        <button id="construct-menu-toggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-slate-700 text-slate-100 hover:bg-slate-800 focus:outline-none">
            <svg id="construct-menu-open" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg id="construct-menu-close" class="h-5 w-5 hidden" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div id="construct-mobile-menu" class="md:hidden hidden border-t border-slate-800 bg-slate-950/98">
        <div class="max-w-6xl mx-auto px-4 py-3 space-y-2 text-sm">
            <a href="<?php echo site_url(); ?>" class="block px-2 py-2 rounded-lg text-slate-200 hover:bg-slate-800">Beranda</a>
            <a href="<?php echo site_url(); ?>/?page=tentang" class="block px-2 py-2 rounded-lg text-slate-200 hover:bg-slate-800">Tentang Perusahaan</a>
            <a href="<?php echo site_url(); ?>/?page=layanan" class="block px-2 py-2 rounded-lg text-slate-200 hover:bg-slate-800">Layanan</a>
            <a href="<?php echo site_url(); ?>/?page=proyek" class="block px-2 py-2 rounded-lg text-slate-200 hover:bg-slate-800">Proyek</a>
            <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="block px-2 py-2 rounded-lg text-amber-400 hover:bg-slate-800">Minta Penawaran</a>
        </div>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){var t=document.getElementById('construct-menu-toggle');if(!t)return;var e=document.getElementById('construct-mobile-menu');var n=document.getElementById('construct-menu-open');var o=document.getElementById('construct-menu-close');t.addEventListener('click',function(){var s=e.classList.contains('hidden');if(s){e.classList.remove('hidden');n.classList.add('hidden');o.classList.remove('hidden');}else{e.classList.add('hidden');n.classList.remove('hidden');o.classList.add('hidden');}});});
</script>
<main class="min-h-screen">
