<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_option('blogname', 'Orion CMS'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-200" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <?php $logo = get_option('site_logo'); ?>
                    <?php if ($logo): ?>
                        <img src="<?php echo $logo; ?>" alt="Logo" class="h-10 w-auto">
                    <?php else: ?>
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">Portfolio.</span>
                    <?php endif; ?>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center">
                    <?php 
                    if (function_exists('wp_nav_menu') && has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'flex space-x-8',
                            'link_class'     => 'text-slate-600 hover:text-blue-600 font-medium transition block',
                            'fallback_cb'    => false
                        ));
                    } else {
                    ?>
                    <div class="flex space-x-8">
                        <a href="index.php" class="text-slate-600 hover:text-blue-600 font-medium transition">Home</a>
                        <a href="index.php#projects" class="text-slate-600 hover:text-blue-600 font-medium transition">Projects</a>
                        <a href="index.php#about" class="text-slate-600 hover:text-blue-600 font-medium transition">About</a>
                    </div>
                    <?php } ?>
                    
                    <a href="index.php#contact" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 transition shadow-sm ml-8">Contact Me</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="text-slate-600 hover:text-blue-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenu" class="md:hidden bg-white border-t border-slate-100">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="index.php" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-md">Home</a>
                <a href="index.php#projects" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-md">Projects</a>
                <a href="index.php#about" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-md">About</a>
                <a href="index.php#contact" class="block px-3 py-2 text-base font-medium text-blue-600 hover:bg-blue-50 rounded-md">Contact Me</a>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed header -->
    <div class="h-20"></div>
