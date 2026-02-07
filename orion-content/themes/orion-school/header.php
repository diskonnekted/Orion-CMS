<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Orion School</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
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
                            950: '#172554',
                        },
                        secondary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Hero Pattern */
        .hero-pattern {
            background-color: #1e3a8a;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>

    <?php if (function_exists('wp_head')) wp_head(); ?>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

<!-- Top Bar -->
<div class="bg-primary-900 text-white py-2 text-sm border-b border-primary-800">
    <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2">
        <div class="flex items-center space-x-6">
            <span class="flex items-center hover:text-primary-200 transition cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <?php echo get_option('orion_school_phone', '(021) 1234-5678'); ?>
            </span>
            <span class="flex items-center hover:text-primary-200 transition cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <?php echo get_option('orion_school_email', 'info@orionschool.sch.id'); ?>
            </span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="#" class="hover:text-primary-200 transition">Portal Siswa</a>
            <span class="text-primary-700">|</span>
            <a href="#" class="hover:text-primary-200 transition">Portal Guru</a>
            <span class="text-primary-700">|</span>
            <a href="<?php echo site_url('/orion-admin/'); ?>" class="bg-primary-700 hover:bg-primary-600 px-3 py-1 rounded text-xs font-semibold transition">Login Admin</a>
        </div>
    </div>
</div>

<!-- Header / Navigation -->
<header class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-slate-100 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-20 transition-all duration-300" :class="{ 'h-16': scrolled, 'h-20': !scrolled }">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-800 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary-500/30 group-hover:scale-105 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none group-hover:text-primary-600 transition tracking-tight">Orion<span class="text-primary-600">School</span></h1>
                        <p class="text-[10px] text-slate-500 tracking-[0.2em] uppercase font-semibold mt-0.5">Unggul & Berkarakter</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="<?php echo site_url(); ?>" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition uppercase tracking-wide <?php echo !isset($_GET['page']) && !isset($_GET['p']) ? 'text-primary-600' : ''; ?>">Beranda</a>
                
                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center text-sm font-semibold text-slate-600 hover:text-primary-600 transition uppercase tracking-wide focus:outline-none <?php echo isset($_GET['page']) && in_array($_GET['page'], ['sejarah', 'visi-misi', 'sambutan']) ? 'text-primary-600' : ''; ?>">
                        Profil
                        <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 border border-slate-100 ring-1 ring-black ring-opacity-5 z-50" 
                         style="display: none;">
                        <div class="absolute -top-2 left-6 w-4 h-4 bg-white transform rotate-45 border-l border-t border-slate-100"></div>
                        <div class="relative bg-white rounded-xl overflow-hidden">
                            <a href="?page=sejarah" class="block px-4 py-3 text-sm text-slate-600 hover:bg-primary-50 hover:text-primary-700 transition flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-400 mr-2"></span> Sejarah
                            </a>
                            <a href="?page=visi-misi" class="block px-4 py-3 text-sm text-slate-600 hover:bg-primary-50 hover:text-primary-700 transition flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-400 mr-2"></span> Visi & Misi
                            </a>
                            <a href="?page=sambutan" class="block px-4 py-3 text-sm text-slate-600 hover:bg-primary-50 hover:text-primary-700 transition flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-400 mr-2"></span> Sambutan Kepala Sekolah
                            </a>
                        </div>
                    </div>
                </div>

                <a href="?page=guru" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition uppercase tracking-wide <?php echo isset($_GET['page']) && $_GET['page'] == 'guru' ? 'text-primary-600' : ''; ?>">Guru & Staf</a>
                <a href="?page=berita" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition uppercase tracking-wide <?php echo isset($_GET['page']) && $_GET['page'] == 'berita' ? 'text-primary-600' : ''; ?>">Berita</a>
                <a href="?page=kontak" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition uppercase tracking-wide <?php echo isset($_GET['page']) && $_GET['page'] == 'kontak' ? 'text-primary-600' : ''; ?>">Kontak</a>
                
                <a href="?page=ppdb" class="px-5 py-2.5 bg-gradient-to-r from-secondary-600 to-secondary-500 hover:from-secondary-700 hover:to-secondary-600 text-white text-sm font-bold rounded-full shadow-lg shadow-secondary-500/30 transition transform hover:-translate-y-0.5">
                    PPDB Online
                </a>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="text-slate-600 hover:text-primary-600 focus:outline-none p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                
                <!-- Mobile Drawer -->
                <div x-show="open" class="absolute top-20 left-0 w-full bg-white shadow-lg border-b border-slate-100 py-4 px-4 flex flex-col space-y-4" @click.away="open = false" style="display: none;">
                    <a href="<?php echo site_url(); ?>" class="text-slate-700 font-medium hover:text-primary-600">Beranda</a>
                    <div class="border-t border-slate-100 pt-2">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-2">Profil</p>
                        <a href="?page=sejarah" class="block pl-4 py-2 text-slate-600 hover:text-primary-600">Sejarah</a>
                        <a href="?page=visi-misi" class="block pl-4 py-2 text-slate-600 hover:text-primary-600">Visi & Misi</a>
                        <a href="?page=sambutan" class="block pl-4 py-2 text-slate-600 hover:text-primary-600">Sambutan Kepala Sekolah</a>
                    </div>
                    <a href="?page=guru" class="text-slate-700 font-medium hover:text-primary-600">Guru & Staf</a>
                    <a href="?page=berita" class="text-slate-700 font-medium hover:text-primary-600">Berita</a>
                    <a href="?page=kontak" class="text-slate-700 font-medium hover:text-primary-600">Kontak</a>
                    <a href="?page=ppdb" class="text-center bg-secondary-600 text-white py-2 rounded-lg font-bold">PPDB Online</a>
                </div>
            </div>
        </div>
    </div>
</header>
