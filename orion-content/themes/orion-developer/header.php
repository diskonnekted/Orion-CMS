<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_option('blogname', 'Orion Developer'); ?> - <?php echo get_option('blogdescription', 'Website Murah & Express'); ?></title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orion: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        garage: {
                            100: '#fef3c7',
                            200: '#fde68a',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Theme Style -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50 antialiased'); ?>>

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-xl font-black text-slate-900 tracking-tight">ORION<span class="text-indigo-600">DEV</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#services" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Layanan</a>
                <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Harga</a>
                <a href="#about" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Testimoni</a>
            </div>
            
            <div>
                <a href="<?php echo orion_dev_wa_link('Konsultasi Awal'); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-indigo-600/20 transition transform hover:-translate-y-0.5">Mulai Sekarang</a>
            </div>
        </div>
    </nav>
    
    <div class="pt-20"> <!-- Spacer for fixed navbar -->
