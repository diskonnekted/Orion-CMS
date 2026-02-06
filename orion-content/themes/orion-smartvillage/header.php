<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_option('site_title', 'Desa Digital'); ?> - <?php echo get_option('site_tagline', 'Membangun Desa, Membangun Bangsa'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <!-- Top Bar (Informasi Kontak & Jam Layanan) -->
    <div class="bg-emerald-900 text-emerald-100 text-xs py-2 hidden md:block">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex space-x-6">
                <span class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> <?php echo get_option('smartvillage_phone', '(021) 1234-5678'); ?></span>
                <span class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> <?php echo get_option('smartvillage_email', 'info@desa-digital.go.id'); ?></span>
            </div>
            <div class="flex space-x-4">
                <span>Senin - Jumat: 08:00 - 16:00 WIB</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <?php if (get_option('site_logo')): ?>
                        <img src="<?php echo get_option('site_logo'); ?>" alt="Logo" class="h-10 w-auto">
                    <?php else: ?>
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-emerald-800 leading-none">DESA DIGITAL</span>
                        <span class="text-xs text-slate-500 font-medium tracking-wide">KABUPATEN ORION</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center">
                    <?php 
                    if (function_exists('wp_nav_menu') && has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'flex space-x-1',
                            'link_class'     => 'px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition block',
                            'fallback_cb'    => false
                        ));
                    } else {
                    ?>
                    <div class="flex space-x-1">
                        <a href="index.php" class="px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition">Beranda</a>
                        <a href="index.php?p=5" class="px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition">Tentang Kami</a>
                        <a href="index.php?view=archive" class="px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition">Berita</a>
                        <a href="index.php?view=services" class="px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition">Layanan</a>
                        <a href="index.php?p=6" class="px-4 py-2 text-slate-600 hover:text-emerald-600 font-medium transition">Kontak</a>
                    </div>
                    <?php } ?>
                    
                    <!-- Fixed CTA Button -->
                    <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition ml-2 shadow-sm">Layanan Mandiri</a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </nav>
