    </main>
<?php
$store_name = get_option('orion_garage_store_name', 'Orion Garage');
$store_address = get_option('orion_garage_store_address', "Jl. Otomotif Raya No. 123\nKota Anda, Indonesia");
$store_phone = get_option('orion_garage_store_phone', '+62 812‑3456‑7890');
$store_whatsapp = get_option('orion_garage_store_whatsapp', '6281234567890');
$store_hours = get_option('orion_garage_store_hours', 'Buka setiap hari 08.00–17.00');
?>

    <footer class="border-t border-white/10 bg-slate-900/95 mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <div>
                    <h3 class="text-sm font-semibold text-white mb-3 tracking-wide uppercase"><?php echo htmlspecialchars($store_name); ?></h3>
                    <p class="text-gray-300 mb-3">Bengkel servis mobil lengkap untuk perawatan rutin, perbaikan mesin, dan upgrade performa.</p>
                    <p class="text-gray-400 text-xs whitespace-pre-line"><?php echo nl2br(htmlspecialchars($store_hours)); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white mb-3 tracking-wide uppercase">Kontak</h3>
                    <p class="text-gray-300 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($store_address)); ?></p>
                    <p class="text-gray-300 mt-2">Telepon: <a href="tel:<?php echo htmlspecialchars($store_phone); ?>" class="text-garage-200 hover:text-garage-100"><?php echo htmlspecialchars($store_phone); ?></a></p>
                    <p class="text-gray-300">WhatsApp: <a href="<?php echo 'https://wa.me/' . htmlspecialchars($store_whatsapp); ?>" class="text-garage-200 hover:text-garage-100">Chat Sekarang</a></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white mb-3 tracking-wide uppercase">Navigasi</h3>
                    <div class="space-y-2">
                        <a href="<?php echo site_url(); ?>" class="block text-gray-300 hover:text-white">Beranda</a>
                        <a href="<?php echo site_url(); ?>/?page=Layanan" class="block text-gray-300 hover:text-white">Layanan</a>
                        <a href="<?php echo site_url(); ?>/?page=Promo" class="block text-gray-300 hover:text-white">Promo</a>
                        <a href="<?php echo site_url(); ?>/?page=Kontak" class="block text-gray-300 hover:text-white">Kontak</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                <p>© <?php echo date('Y'); ?> Orion Garage. Semua hak cipta dilindungi.</p>
                <p>Powered by Orion CMS</p>
            </div>
        </div>
        <?php wp_footer(); ?>
    </footer>
</body>
</html>
