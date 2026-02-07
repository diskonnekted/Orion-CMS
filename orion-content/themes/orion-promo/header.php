<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?> - <?php echo get_bloginfo('description'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-slate-800 antialiased'); ?>>

<!-- Navigation -->
<nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="<?php echo site_url(); ?>" class="text-2xl font-bold text-brand-700 flex items-center gap-2">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/CMS-ORION-ONE.png" alt="Orion CMS Logo" class="h-14 w-auto">
        </a>
        
        <div class="hidden md:flex space-x-8 items-center">
            <a href="<?php echo site_url(); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Beranda</a>
            <a href="<?php echo site_url('?page=download'); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Download</a>
            <a href="<?php echo site_url(); ?>#features" class="text-slate-600 hover:text-brand-600 font-medium transition">Fitur</a>
            <a href="<?php echo site_url(); ?>#news" class="text-slate-600 hover:text-brand-600 font-medium transition">Berita</a>
            <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-5 py-2 bg-brand-600 text-white rounded-full font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-500/30">
                Login Admin
            </a>
        </div>
        
        <!-- Mobile Menu Button (Simple implementation) -->
        <button class="md:hidden text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
</nav>
