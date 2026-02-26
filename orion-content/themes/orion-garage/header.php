<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('blogname', 'Orion Garage')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        garage: {
                            50: '#f3f4f6',
                            100: '#e5e7eb',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c'
                        },
                        slate: {
                            900: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-900 text-white antialiased'); ?>>
<?php
$store_phone = get_option('orion_garage_store_phone', '+62 812‑3456‑7890');
?>
<header class="bg-slate-900/95 backdrop-blur sticky top-0 z-40 border-b border-white/10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-garage-500 to-garage-700 flex items-center justify-center shadow-lg shadow-garage-500/40">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 13l2.5-6A2 2 0 017.4 5h9.2a2 2 0 011.9 1.3L21 13m-18 0h18M6 17h2m8 0h2M7 17a2 2 0 11-4 0m18 0a2 2 0 11-4 0" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold tracking-wide uppercase text-garage-100">Orion Garage</span>
                    <span class="text-xs text-gray-300">Bengkel Servis Mobil Profesional</span>
                </div>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <?php
                $layanan_id = function_exists('orion_garage_get_page_id_by_title') ? orion_garage_get_page_id_by_title('Layanan') : 0;
                $promo_id = function_exists('orion_garage_get_page_id_by_title') ? orion_garage_get_page_id_by_title('Promo') : 0;
                $kontak_id = function_exists('orion_garage_get_page_id_by_title') ? orion_garage_get_page_id_by_title('Kontak') : 0;
                $shop_id = function_exists('orion_garage_get_page_id_by_title') ? orion_garage_get_page_id_by_title('Shop') : 0;

                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'flex gap-6 list-none',
                        'fallback_cb' => false,
                        'link_class' => 'text-gray-300 hover:text-white transition'
                    ));
                    if ($shop_id && function_exists('orion_garage_is_shop_manager_active') && orion_garage_is_shop_manager_active()) {
                        ?>
                        <a href="<?php echo site_url('/?page_id=' . $shop_id); ?>" class="text-gray-300 hover:text-white transition">Produk dan Layanan</a>
                        <?php
                    }
                } else {
                    ?>
                    <a href="<?php echo site_url(); ?>" class="text-gray-300 hover:text-white transition">Beranda</a>
                    <?php if ($layanan_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $layanan_id); ?>" class="text-gray-300 hover:text-white transition">Layanan</a>
                    <?php endif; ?>
                    <?php if ($promo_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $promo_id); ?>" class="text-gray-300 hover:text-white transition">Promo</a>
                    <?php endif; ?>
                    <?php if ($kontak_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $kontak_id); ?>" class="text-gray-300 hover:text-white transition">Kontak</a>
                    <?php endif; ?>
                    <?php if ($shop_id && function_exists('orion_garage_is_shop_manager_active') && orion_garage_is_shop_manager_active()): ?>
                        <a href="<?php echo site_url('/?page_id=' . $shop_id); ?>" class="text-gray-300 hover:text-white transition">Produk dan Layanan</a>
                    <?php endif; ?>
                    <?php
                }
                ?>
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="inline-flex items-center rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold px-4 py-2 shadow-lg shadow-garage-600/40 transition">
                    <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a2 2 0 012-2h8m-4-8h2a2 2 0 012 2v3m-6-5h-2a2 2 0 00-2 2v1m0 8h10a2 2 0 002-2v-3m-6 5v2m-4 0h8" />
                    </svg>
                    Login Admin
                </a>
            </nav>
            <button id="garage-mobile-toggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-full border border-white/20 text-gray-200 hover:bg-white/10 focus:outline-none">
                <svg id="garage-mobile-icon-open" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="garage-mobile-icon-close" class="h-5 w-5 hidden" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <div id="garage-mobile-menu" class="md:hidden border-t border-white/10 bg-slate-900/98 backdrop-blur hidden">
        <div class="max-w-6xl mx-auto px-4 py-3 space-y-2 text-sm">
            <a href="<?php echo site_url(); ?>" class="block px-2 py-2 rounded-lg text-gray-200 hover:bg-white/10">Beranda</a>
            <?php if ($layanan_id): ?>
                <a href="<?php echo site_url('/?page_id=' . $layanan_id); ?>" class="block px-2 py-2 rounded-lg text-gray-200 hover:bg-white/10">Layanan</a>
            <?php endif; ?>
            <?php if ($promo_id): ?>
                <a href="<?php echo site_url('/?page_id=' . $promo_id); ?>" class="block px-2 py-2 rounded-lg text-gray-200 hover:bg-white/10">Promo</a>
            <?php endif; ?>
            <?php if ($kontak_id): ?>
                <a href="<?php echo site_url('/?page_id=' . $kontak_id); ?>" class="block px-2 py-2 rounded-lg text-gray-200 hover:bg-white/10">Kontak</a>
            <?php endif; ?>
            <?php if ($shop_id && function_exists('orion_garage_is_shop_manager_active') && orion_garage_is_shop_manager_active()): ?>
                <a href="<?php echo site_url('/?page_id=' . $shop_id); ?>" class="block px-2 py-2 rounded-lg text-gray-200 hover:bg-white/10">Produk dan Layanan</a>
            <?php endif; ?>
            <a href="tel:<?php echo htmlspecialchars($store_phone); ?>" class="block px-2 py-2 rounded-lg text-garage-100 hover:bg-white/10">Hotline Bengkel: <?php echo htmlspecialchars($store_phone); ?></a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('garage-mobile-toggle');
        var menu = document.getElementById('garage-mobile-menu');
        var iconOpen = document.getElementById('garage-mobile-icon-open');
        var iconClose = document.getElementById('garage-mobile-icon-close');
        if (toggle && menu && iconOpen && iconClose) {
            toggle.addEventListener('click', function () {
                var isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    iconOpen.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        }
    });
</script>

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
