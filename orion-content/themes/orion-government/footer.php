</main>

<footer class="bg-slate-900 text-slate-300 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Col 1: Identity -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <?php 
                        $logo_url = get_option('orion_gov_logo', ''); 
                        if($logo_url): 
                    ?>
                        <img class="h-10 w-auto" src="<?php echo $logo_url; ?>" alt="Logo">
                    <?php else: ?>
                        <div class="h-8 w-8 bg-gov-600 rounded flex items-center justify-center text-white font-bold">
                            G
                        </div>
                    <?php endif; ?>
                    <span class="text-xl font-bold text-white"><?php echo get_option('blogname', 'PEMERINTAH KOTA'); ?></span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Website Resmi Pemerintah. Menyediakan informasi publik yang akurat, transparan, dan akuntabel untuk masyarakat.
                </p>
                <div class="flex space-x-4">
                    <!-- Social Icons -->
                    <a href="#" class="text-slate-400 hover:text-white transition">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-white transition">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Links -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Layanan Publik</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-gov-400 transition">Perizinan Online</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Cek Pajak PBB</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Layanan Kependudukan</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Pengaduan Masyarakat</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Jadwal Pelayanan</a></li>
                </ul>
            </div>

            <!-- Col 3: Transparansi -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Transparansi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-gov-400 transition">APBD Tahun Ini</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Laporan Keuangan</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Produk Hukum</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Pengadaan Barang & Jasa</a></li>
                    <li><a href="#" class="hover:text-gov-400 transition">Statistik Daerah</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Kontak Kami</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gov-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Jl. Jenderal Sudirman No. 1<br>Kota Pemerintahan, 12345</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gov-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>(021) 123-4567</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gov-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@pemerintah.go.id</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12 border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; <?php echo date('Y'); ?> Pemerintah Kota. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white transition">Aksesibilitas</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
