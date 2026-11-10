<?php
$brand = get_option('orion_versa_brand_name', 'Orion Versa');
$email = get_option('orion_versa_contact_email', '');
$phone = get_option('orion_versa_contact_phone', '');
$address = get_option('orion_versa_contact_address', '');
$shop_id = (int) get_option('orion_versa_page_shop_id', 0);
?>

</main>

<footer class="bg-slate-950 text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
            <div class="md:col-span-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center font-black">OV</div>
                    <div>
                        <div class="font-extrabold tracking-tight text-lg"><?php echo htmlspecialchars($brand); ?></div>
                        <div class="text-xs uppercase tracking-widest text-white/60"><?php echo htmlspecialchars(get_option('blogdescription', 'fast.flexible.free')); ?></div>
                    </div>
                </div>
                <p class="mt-4 text-white/70 leading-relaxed max-w-lg">
                    Landing page siap promosi untuk perangkat IoT brand Orion. Fokus konversi: produk jelas, paket transparan, dan booking pemasangan yang mudah.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-4 py-2 rounded-xl bg-white text-slate-900 font-extrabold hover:bg-white/90 transition">WhatsApp</a>
                    <a href="#paket" class="px-4 py-2 rounded-xl bg-white/10 border border-white/15 font-bold hover:bg-white/15 transition">Lihat Paket</a>
                </div>
            </div>

            <div class="md:col-span-3">
                <div class="text-sm font-extrabold">Navigasi</div>
                <div class="mt-4 grid gap-2 text-sm text-white/75">
                    <a class="hover:text-white" href="<?php echo site_url(); ?>">Beranda</a>
                    <a class="hover:text-white" href="#produk">Produk</a>
                    <a class="hover:text-white" href="#konsep">Konsep</a>
                    <a class="hover:text-white" href="#paket">Paket</a>
                    <a class="hover:text-white" href="#kontak">Kontak</a>
                    <?php if ($shop_id): ?><a class="hover:text-white" href="<?php echo site_url('/?page_id=' . $shop_id); ?>">Shop</a><?php endif; ?>
                </div>
            </div>

            <div class="md:col-span-4">
                <div class="text-sm font-extrabold">Kontak</div>
                <div class="mt-4 space-y-2 text-sm text-white/75">
                    <?php if ($email): ?><div>Email: <span class="text-white"><?php echo htmlspecialchars($email); ?></span></div><?php endif; ?>
                    <?php if ($phone): ?><div>Telepon: <span class="text-white"><?php echo htmlspecialchars($phone); ?></span></div><?php endif; ?>
                    <?php if ($address): ?><div>Alamat: <span class="text-white"><?php echo htmlspecialchars($address); ?></span></div><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row gap-3 md:items-center md:justify-between text-xs text-white/60">
            <div>© <?php echo date('Y'); ?> <?php echo htmlspecialchars($brand); ?>. All rights reserved.</div>
            <div class="flex gap-4">
                <a class="hover:text-white" href="<?php echo htmlspecialchars(site_url('/robots.php')); ?>">robots.txt</a>
                <a class="hover:text-white" href="<?php echo htmlspecialchars(site_url('/sitemap.php')); ?>">sitemap</a>
            </div>
        </div>
    </div>
</footer>

<script>
(() => {
  const btn = document.getElementById('versaMobileBtn');
  const menu = document.getElementById('versaMobileMenu');
  if (!btn || !menu) return;
  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
