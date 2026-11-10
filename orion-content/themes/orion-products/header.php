<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(get_option('site_title', 'Orion Products')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-slate-900">
<?php
$brand_name = orion_products_get_text_option('orion_products_brand_name', 'Orion Products');
$tagline = orion_products_get_text_option('orion_products_tagline', 'Skincare & kosmetik premium untuk rutinitas harian.');
$whatsapp = get_option('orion_products_whatsapp', '628123456789');
$cta_text = orion_products_get_text_option('orion_products_cta_text', 'Konsultasi & Order');
$instagram = get_option('orion_products_instagram', '');
$logo = get_option('orion_products_logo', '');
$cta_url = $whatsapp ? ('https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp)) : '#';
?>

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between gap-4">
            <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-3">
                <?php if ($logo): ?>
                    <img src="<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($brand_name); ?>" class="h-9 w-9 rounded-xl object-cover border border-slate-200">
                <?php else: ?>
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500"></div>
                <?php endif; ?>
                <div class="leading-tight">
                    <div class="font-extrabold text-slate-900"><?php echo htmlspecialchars($brand_name); ?></div>
                    <div class="text-xs text-slate-500 hidden sm:block"><?php echo htmlspecialchars($tagline); ?></div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="<?php echo site_url('/?page=produk'); ?>" class="hover:text-brand-600 transition">Katalog</a>
                <a href="#produk" class="hover:text-brand-600 transition">Unggulan</a>
                <a href="#manfaat" class="hover:text-brand-600 transition">Manfaat</a>
                <a href="#galeri" class="hover:text-brand-600 transition">Galeri</a>
                <a href="#testimoni" class="hover:text-brand-600 transition">Testimoni</a>
                <a href="#faq" class="hover:text-brand-600 transition">FAQ</a>
                <a href="#kontak" class="hover:text-brand-600 transition">Kontak</a>
            </nav>

            <div class="flex items-center gap-2">
                <?php if ($instagram): ?>
                    <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 hover:border-brand-300 hover:bg-brand-50 transition" aria-label="Instagram">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 11-8 0 6 6 0 018 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.5 6.5h.01"></path><rect x="3" y="3" width="18" height="18" rx="5" ry="5" stroke-width="2"></rect></svg>
                    </a>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center h-10 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-sm transition">
                    <?php echo htmlspecialchars($cta_text); ?>
                </a>
                <button id="orion-products-menu" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 hover:bg-slate-50 transition" aria-label="Menu">
                    <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <div id="orion-products-mobile" class="md:hidden hidden border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4 grid grid-cols-2 gap-3 text-sm font-bold text-slate-700">
            <a href="<?php echo site_url('/?page=produk'); ?>" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Katalog</a>
            <a href="#produk" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Unggulan</a>
            <a href="#manfaat" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Manfaat</a>
            <a href="#galeri" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Galeri</a>
            <a href="#testimoni" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Testimoni</a>
            <a href="#faq" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">FAQ</a>
            <a href="#kontak" class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 transition">Kontak</a>
        </div>
    </div>
</header>

<script>
    (function() {
        const btn = document.getElementById('orion-products-menu');
        const panel = document.getElementById('orion-products-mobile');
        if (!btn || !panel) return;
        btn.addEventListener('click', () => {
            panel.classList.toggle('hidden');
        });
    })();
</script>
