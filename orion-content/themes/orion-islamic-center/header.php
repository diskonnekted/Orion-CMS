<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('blogname', 'Islamic Center')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ic: {
                            green: {
                                50: '#f0fdf4',
                                100: '#dcfce7',
                                200: '#bbf7d0',
                                300: '#86efac',
                                400: '#4ade80',
                                500: '#22c55e',
                                600: '#16a34a',
                                700: '#15803d',
                                800: '#166534',
                                900: '#14532d',
                            },
                            yellow: {
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
                            }
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-ic-green-50 text-slate-900 font-sans antialiased'); ?>>

<?php
$logo_url = get_option('orion_ic_logo', '');
$page_profile_id = (int) get_option('orion_ic_page_profile_id', 0);
$page_program_id = (int) get_option('orion_ic_page_program_id', 0);
$page_registration_id = (int) get_option('orion_ic_page_registration_id', 0);
$page_schedule_id = (int) get_option('orion_ic_page_schedule_id', 0);
$page_gallery_id = (int) get_option('orion_ic_page_gallery_id', 0);
$page_announcements_id = (int) get_option('orion_ic_page_announcements_id', 0);
$page_contact_id = (int) get_option('orion_ic_page_contact_id', 0);
?>

<div class="bg-ic-green-900 text-white text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-ic-yellow-300"></span>
                Portal Pesantren / Islamic Center
            </span>
            <span class="hidden sm:inline text-white/40">•</span>
            <span class="hidden sm:inline"><?php echo date('d M Y'); ?></span>
        </div>
        <div class="flex items-center gap-4">
            <a class="hover:text-ic-yellow-200 transition" href="<?php echo site_url('/orion-admin/'); ?>">Admin</a>
        </div>
    </div>
</div>

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-ic-green-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                <?php if ($logo_url): ?>
                    <img src="<?php echo $logo_url; ?>" alt="Logo" class="h-12 w-auto">
                <?php else: ?>
                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-ic-green-600 to-ic-yellow-300 shadow-md flex items-center justify-center text-white font-bold">
                        IC
                    </div>
                <?php endif; ?>
                <div class="leading-tight">
                    <div class="text-lg sm:text-xl font-bold text-slate-900 group-hover:text-ic-green-700 transition">
                        <?php echo get_option('blogname', 'Islamic Center'); ?>
                    </div>
                    <div class="text-xs text-slate-500">
                        <?php echo get_option('blogdescription', 'Ilmu • Adab • Ibadah'); ?>
                    </div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo site_url(); ?>">Beranda</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_profile_id ? site_url('/?page_id=' . $page_profile_id) : '#'; ?>">Profil</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_program_id ? site_url('/?page_id=' . $page_program_id) : '#'; ?>">Program</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_schedule_id ? site_url('/?page_id=' . $page_schedule_id) : '#'; ?>">Jadwal</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_gallery_id ? site_url('/?page_id=' . $page_gallery_id) : '#'; ?>">Galeri</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_announcements_id ? site_url('/?page_id=' . $page_announcements_id) : '#'; ?>">Pengumuman</a>
                <a class="text-slate-700 hover:text-ic-green-700 transition" href="<?php echo $page_contact_id ? site_url('/?page_id=' . $page_contact_id) : '#'; ?>">Kontak</a>
                <a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ic-green-700 hover:bg-ic-green-800 text-white shadow-sm transition" href="<?php echo $page_registration_id ? site_url('/?page_id=' . $page_registration_id) : '#'; ?>">
                    Pendaftaran Santri
                </a>
            </nav>

            <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-xl border border-ic-green-200 text-ic-green-800 hover:bg-ic-green-50" id="ic-mobile-btn">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div class="md:hidden hidden border-t border-ic-green-100 bg-white" id="ic-mobile-menu">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 grid gap-2 text-sm font-semibold">
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo site_url(); ?>">Beranda</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_profile_id ? site_url('/?page_id=' . $page_profile_id) : '#'; ?>">Profil</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_program_id ? site_url('/?page_id=' . $page_program_id) : '#'; ?>">Program</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_schedule_id ? site_url('/?page_id=' . $page_schedule_id) : '#'; ?>">Jadwal</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_gallery_id ? site_url('/?page_id=' . $page_gallery_id) : '#'; ?>">Galeri</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_announcements_id ? site_url('/?page_id=' . $page_announcements_id) : '#'; ?>">Pengumuman</a>
            <a class="px-3 py-2 rounded-lg hover:bg-ic-green-50 text-slate-700 hover:text-ic-green-800" href="<?php echo $page_contact_id ? site_url('/?page_id=' . $page_contact_id) : '#'; ?>">Kontak</a>
            <a class="px-3 py-2 rounded-lg bg-ic-green-700 text-white" href="<?php echo $page_registration_id ? site_url('/?page_id=' . $page_registration_id) : '#'; ?>">Pendaftaran Santri</a>
        </div>
    </div>
</header>

<script>
    const icBtn = document.getElementById('ic-mobile-btn');
    const icMenu = document.getElementById('ic-mobile-menu');
    if (icBtn && icMenu) {
        icBtn.addEventListener('click', () => icMenu.classList.toggle('hidden'));
    }
</script>

<main class="min-h-screen">
