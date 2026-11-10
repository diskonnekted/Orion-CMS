<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('blogname', 'Portal Pemerintah')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gov: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <style>
        .gov-clip-path {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }
    </style>
</head>
<body <?php body_class('bg-slate-50 text-slate-800 font-sans antialiased'); ?>>

<!-- Top Bar -->
<div class="bg-gov-900 text-white text-xs py-2 border-b border-gov-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1">
                <svg class="w-3 h-3 text-gold-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                Portal Resmi Pemerintah
            </span>
            <span class="hidden sm:inline text-gov-400">|</span>
            <span class="hidden sm:inline"><?php echo date('l, d F Y'); ?></span>
        </div>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-gold-400 transition">Kontak</a>
            <a href="#" class="hover:text-gold-400 transition">Peta Situs</a>
            <a href="#" class="hover:text-gold-400 transition">Login Pegawai</a>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white shadow-sm sticky top-0 z-50 border-t-4 border-gold-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                    <?php 
                        $logo_url = get_option('orion_gov_logo', ''); 
                        if($logo_url): 
                    ?>
                        <img class="h-12 w-auto" src="<?php echo $logo_url; ?>" alt="Logo">
                    <?php else: ?>
                        <div class="h-10 w-10 bg-gov-700 rounded-lg flex items-center justify-center text-white font-bold shadow-md group-hover:bg-gov-600 transition">
                            G
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-slate-800 leading-none group-hover:text-gov-700 transition">
                            <?php echo get_option('blogname', 'PEMERINTAH KOTA'); ?>
                        </span>
                        <span class="text-xs text-slate-500 font-medium tracking-wider uppercase mt-1">
                            <?php echo get_option('blogdescription', 'Melayani dengan Hati'); ?>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="<?php echo site_url(); ?>" class="text-slate-600 hover:text-gov-700 font-medium text-sm transition py-2 border-b-2 border-transparent hover:border-gov-500">Beranda</a>
                <a href="<?php echo site_url('/?page=profil'); ?>" class="text-slate-600 hover:text-gov-700 font-medium text-sm transition py-2 border-b-2 border-transparent hover:border-gov-500">Profil</a>
                <a href="<?php echo site_url('/?page=layanan'); ?>" class="text-slate-600 hover:text-gov-700 font-medium text-sm transition py-2 border-b-2 border-transparent hover:border-gov-500">Layanan Publik</a>
                <a href="<?php echo site_url('/?page=berita'); ?>" class="text-slate-600 hover:text-gov-700 font-medium text-sm transition py-2 border-b-2 border-transparent hover:border-gov-500">Berita</a>
                <a href="<?php echo site_url('/?page=transparansi'); ?>" class="text-slate-600 hover:text-gov-700 font-medium text-sm transition py-2 border-b-2 border-transparent hover:border-gov-500">Transparansi</a>
                <a href="<?php echo site_url('/?page=kontak'); ?>" class="px-4 py-2 bg-gov-700 text-white rounded-md text-sm font-medium hover:bg-gov-800 transition shadow-sm hover:shadow-md">
                    Hubungi Kami
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gov-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden bg-white border-t border-slate-100" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="<?php echo site_url(); ?>" class="block pl-3 pr-4 py-2 border-l-4 border-gov-500 text-base font-medium text-gov-700 bg-gov-50">Beranda</a>
            <a href="<?php echo site_url('/?page=profil'); ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800">Profil</a>
            <a href="<?php echo site_url('/?page=layanan'); ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800">Layanan Publik</a>
            <a href="<?php echo site_url('/?page=berita'); ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800">Berita</a>
            <a href="<?php echo site_url('/?page=transparansi'); ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800">Transparansi</a>
            <div class="pt-4 pb-2">
                <a href="<?php echo site_url('/?page=kontak'); ?>" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gov-600 hover:bg-gov-700">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    const btn = document.querySelector("button.mobile-menu-button");
    const menu = document.querySelector("#mobile-menu");

    btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    });
</script>

<main class="min-h-screen">
