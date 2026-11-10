<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('blogname', 'Orion Bakery')); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bakery: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                            950: '#451a03',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['DM Serif Display', 'serif'],
                        hand: ['Caveat', 'cursive'],
                    },
                    backgroundImage: {
                        'pattern-bread': "url('https://www.transparenttextures.com/patterns/food.png')",
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=DM+Serif+Display&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
    <style>
        .text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .text-shadow-sm { text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }
        .clip-wavy-bottom { clip-path: polygon(0 0, 100% 0, 100% 90%, 50% 100%, 0 90%); }
        .floating-bread { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0px); } }
    </style>
</head>
<body <?php body_class('bg-bakery-50 text-bakery-950 font-sans antialiased'); ?>>

<!-- Announcement Bar -->
<div class="bg-bakery-900 text-bakery-100 text-xs font-medium py-2 text-center tracking-wide">
    <span class="inline-flex items-center gap-2">
        <svg class="w-4 h-4 text-bakery-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
        Gratis Ongkir untuk Pemesanan di Atas Rp 100.000!
    </span>
</div>

<!-- Main Header -->
<header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-bakery-200 shadow-sm transition-all duration-300" id="main-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                    <?php 
                        $logo_url = get_option('orion_bakery_logo', ''); 
                        if($logo_url): 
                    ?>
                        <img class="h-12 w-auto transform group-hover:rotate-6 transition duration-300" src="<?php echo $logo_url; ?>" alt="Logo">
                    <?php else: ?>
                        <div class="h-10 w-10 bg-bakery-500 rounded-full flex items-center justify-center text-white font-serif font-bold text-xl shadow-lg transform group-hover:scale-110 transition duration-300">
                            B
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-col">
                        <span class="text-2xl font-serif font-bold text-bakery-900 leading-none group-hover:text-bakery-600 transition">
                            <?php echo get_option('blogname', 'Orion Bakery'); ?>
                        </span>
                        <span class="text-sm font-hand font-bold text-bakery-600 -mt-1 transform -rotate-2">
                            Freshly Baked with Love
                        </span>
                    </div>
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="<?php echo site_url(); ?>" class="text-bakery-900 hover:text-bakery-600 font-medium text-base transition relative group">
                    Beranda
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bakery-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="<?php echo site_url('/?page=daily-menu'); ?>" class="text-bakery-900 hover:text-bakery-600 font-medium text-base transition relative group">
                    Menu Hari Ini
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bakery-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="<?php echo site_url('/?page=shop'); ?>" class="text-bakery-900 hover:text-bakery-600 font-medium text-base transition relative group">
                    Katalog Roti
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bakery-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="<?php echo site_url('/?page=about'); ?>" class="text-bakery-900 hover:text-bakery-600 font-medium text-base transition relative group">
                    Tentang Kami
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bakery-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="<?php echo site_url('/?page=contact'); ?>" class="text-bakery-900 hover:text-bakery-600 font-medium text-base transition relative group">
                    Kontak
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bakery-500 transition-all group-hover:w-full"></span>
                </a>
                
                <!-- Cart Icon (if EDD active) -->
                <?php if(function_exists('edd_get_cart_quantity')): ?>
                <a href="<?php echo site_url('/checkout'); ?>" class="relative p-2 text-bakery-900 hover:text-bakery-600 transition group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <?php if(edd_get_cart_quantity() > 0): ?>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full group-hover:bg-red-500 transition">
                        <?php echo edd_get_cart_quantity(); ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <a href="<?php echo site_url('/?page=shop'); ?>" class="px-5 py-2.5 bg-bakery-600 hover:bg-bakery-500 text-white rounded-full font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                    Pesan Sekarang
                </a>
            </nav>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center gap-4">
                <?php if(function_exists('edd_get_cart_quantity')): ?>
                <a href="<?php echo site_url('/checkout'); ?>" class="relative p-2 text-bakery-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <?php if(edd_get_cart_quantity() > 0): ?>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                        <?php echo edd_get_cart_quantity(); ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-bakery-700 hover:text-bakery-900 hover:bg-bakery-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-bakery-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden bg-white border-t border-bakery-100 absolute w-full shadow-xl" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?php echo site_url(); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-bakery-900 hover:bg-bakery-50 hover:text-bakery-700">Beranda</a>
            <a href="<?php echo site_url('/?page=daily-menu'); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-bakery-900 hover:bg-bakery-50 hover:text-bakery-700">Menu Hari Ini</a>
            <a href="<?php echo site_url('/?page=shop'); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-bakery-900 hover:bg-bakery-50 hover:text-bakery-700">Katalog Roti</a>
            <a href="<?php echo site_url('/?page=about'); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-bakery-900 hover:bg-bakery-50 hover:text-bakery-700">Tentang Kami</a>
            <a href="<?php echo site_url('/?page=contact'); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-bakery-900 hover:bg-bakery-50 hover:text-bakery-700">Kontak</a>
            <a href="<?php echo site_url('/?page=shop'); ?>" class="block w-full text-center mt-4 px-5 py-3 rounded-lg font-bold text-white bg-bakery-600 hover:bg-bakery-500 shadow-md">
                Pesan Sekarang
            </a>
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
