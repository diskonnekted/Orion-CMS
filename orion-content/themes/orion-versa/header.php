<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        versa: {
                            50: 'var(--alabaster-grey)',
                            100: 'var(--white)',
                            200: 'var(--orange)',
                            300: 'var(--orange)',
                            400: 'var(--orange)',
                            500: 'var(--orange)',
                            600: 'var(--orange)',
                            700: 'var(--prussian-blue)',
                            800: 'var(--prussian-blue)',
                            900: 'var(--black)',
                            950: 'var(--black)'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-versa-50 text-versa-700 antialiased'); ?>>

<?php
$brand = get_option('orion_versa_brand_name', 'Orion Versa');
$pricing_id = (int) get_option('orion_versa_page_pricing_id', 0);
$booking_id = (int) get_option('orion_versa_page_booking_id', 0);
$ongkos_id = (int) get_option('orion_versa_page_ongkos_id', 0);
$shop_id = (int) get_option('orion_versa_page_shop_id', 0);
$nav = array(
    array('label' => 'Produk', 'href' => '#produk'),
    array('label' => 'Konsep', 'href' => '#konsep'),
    array('label' => 'Paket', 'href' => '#paket'),
    array('label' => 'Kontak', 'href' => '#kontak'),
);
?>

<header class="sticky top-0 z-50 bg-white/85 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between gap-4">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black tracking-tight">
                    OR
                </div>
                <div class="leading-tight">
                    <div class="font-extrabold tracking-tight"><?php echo htmlspecialchars($brand); ?></div>
                    <div class="text-[11px] uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars(get_option('blogdescription', 'fast.flexible.free')); ?></div>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-7">
                <?php foreach ($nav as $item): ?>
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition" href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                <?php endforeach; ?>
                <?php if ($pricing_id): ?>
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition" href="<?php echo site_url('/?page_id=' . $pricing_id); ?>">Harga</a>
                <?php endif; ?>
                <?php if ($ongkos_id): ?>
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition" href="<?php echo site_url('/?page_id=' . $ongkos_id); ?>">Ongkos</a>
                <?php endif; ?>
                <?php if ($shop_id): ?>
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition" href="<?php echo site_url('/?page_id=' . $shop_id); ?>">Shop</a>
                <?php endif; ?>
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 font-bold hover:bg-slate-50 transition">
                    WhatsApp
                </a>
                <?php if ($booking_id): ?>
                    <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-4 py-2 rounded-xl bg-versa-500 hover:bg-versa-400 text-slate-900 font-extrabold shadow-lg shadow-versa-500/20 transition">
                        Booking Instalasi
                    </a>
                <?php endif; ?>
            </div>

            <button id="versaMobileBtn" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-50 transition" type="button" aria-label="Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div id="versaMobileMenu" class="hidden lg:hidden pb-4">
            <div class="grid gap-2 pt-3">
                <?php foreach ($nav as $item): ?>
                    <a class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                <?php endforeach; ?>
                <?php if ($pricing_id): ?>
                    <a class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?php echo site_url('/?page_id=' . $pricing_id); ?>">Harga</a>
                <?php endif; ?>
                <?php if ($ongkos_id): ?>
                    <a class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?php echo site_url('/?page_id=' . $ongkos_id); ?>">Ongkos</a>
                <?php endif; ?>
                <?php if ($shop_id): ?>
                    <a class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?php echo site_url('/?page_id=' . $shop_id); ?>">Shop</a>
                <?php endif; ?>
                <?php if ($booking_id): ?>
                    <a class="px-3 py-2 rounded-xl text-sm font-extrabold bg-versa-500 text-slate-900 text-center" href="<?php echo site_url('/?page_id=' . $booking_id); ?>">Booking Instalasi</a>
                <?php endif; ?>
                <a class="px-3 py-2 rounded-xl text-sm font-bold border border-slate-200 text-center" href="<?php echo orion_versa_whatsapp_url(); ?>">WhatsApp</a>
            </div>
        </div>
    </div>
</header>

<main>
