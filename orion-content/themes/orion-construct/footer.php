    </main>
    <footer class="border-t border-slate-800 bg-slate-950">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-slate-400 mb-2">Perusahaan</p>
                    <p class="text-lg font-bold text-slate-50 mb-2"><?php echo htmlspecialchars(get_option('blogname', 'Konstruksi Cakrawala')); ?></p>
                    <p class="text-slate-300 mb-3">Kontraktor spesialis gedung, infrastruktur, dan renovasi dengan standar keselamatan kerja tinggi.</p>
                    <p class="text-xs text-slate-500">Membangun dengan presisi, tepat waktu, dan transparansi biaya.</p>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-slate-400 mb-2">Kontak</p>
                    <p class="text-slate-300">Email: <a href="mailto:info@konstruksicakrawala.id" class="text-amber-400 hover:text-amber-300">info@konstruksicakrawala.id</a></p>
                    <p class="text-slate-300 mt-1">Telepon: <a href="tel:+622112345678" class="text-amber-400 hover:text-amber-300">+62 21 1234 5678</a></p>
                    <p class="text-slate-300 mt-3 whitespace-pre-line">Alamat Kantor:
Jl. Pembangunan No. 88
Jakarta</p>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-slate-400 mb-2">Navigasi</p>
                    <div class="space-y-2">
                        <a href="<?php echo site_url(); ?>" class="block text-slate-300 hover:text-white">Beranda</a>
                        <a href="<?php echo site_url(); ?>/?page=tentang" class="block text-slate-300 hover:text-white">Tentang Perusahaan</a>
                        <a href="<?php echo site_url(); ?>/?page=layanan" class="block text-slate-300 hover:text-white">Layanan</a>
                        <a href="<?php echo site_url(); ?>/?page=proyek" class="block text-slate-300 hover:text-white">Proyek</a>
                        <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="block text-amber-400 hover:text-amber-300">Minta Penawaran</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>© <?php echo date('Y'); ?> <?php echo htmlspecialchars(get_option('blogname', 'Konstruksi Cakrawala')); ?>. Semua hak cipta dilindungi.</p>
                <p>Powered by Orion CMS</p>
            </div>
        </div>
        <?php wp_footer(); ?>
    </footer>
</body>
</html>

