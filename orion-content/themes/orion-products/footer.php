<?php
$brand_name = orion_products_get_text_option('orion_products_brand_name', 'Orion Products');
$email = get_option('orion_products_email', 'hello@orionproducts.id');
$address = orion_products_get_text_option('orion_products_address', 'Jl. Contoh No. 10, Jakarta');
$instagram = get_option('orion_products_instagram', '');
$whatsapp = get_option('orion_products_whatsapp', '628123456789');
$cta_url = $whatsapp ? ('https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp)) : '#';
?>

<footer id="kontak" class="bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <div class="text-2xl font-extrabold"><?php echo htmlspecialchars($brand_name); ?></div>
                <p class="mt-3 text-white/70 max-w-sm">Perawatan dan kosmetik yang diformulasikan untuk kenyamanan dan hasil yang konsisten.</p>
                <div class="mt-6 flex gap-3">
                    <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center h-11 px-5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold transition">Chat WhatsApp</a>
                    <?php if ($instagram): ?>
                        <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center h-11 px-4 rounded-xl border border-white/20 hover:border-white/40 hover:bg-white/5 transition">Instagram</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="text-sm font-bold text-white/80 uppercase tracking-wider">Kontak</div>
                <div class="text-white/70"><?php echo htmlspecialchars($email); ?></div>
                <div class="text-white/70"><?php echo nl2br(htmlspecialchars($address)); ?></div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="text-sm font-bold text-white/80 uppercase tracking-wider">Menu</div>
                <a href="#produk" class="block text-white/70 hover:text-white transition">Produk</a>
                <a href="#manfaat" class="block text-white/70 hover:text-white transition">Manfaat</a>
                <a href="#galeri" class="block text-white/70 hover:text-white transition">Galeri</a>
                <a href="#testimoni" class="block text-white/70 hover:text-white transition">Testimoni</a>
                <a href="#faq" class="block text-white/70 hover:text-white transition">FAQ</a>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs text-white/50">
            <div>© <?php echo date('Y'); ?> <?php echo htmlspecialchars($brand_name); ?>. All rights reserved.</div>
            <div class="flex gap-4">
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="hover:text-white/80 transition">Admin</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
