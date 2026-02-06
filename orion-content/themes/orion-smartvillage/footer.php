    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-auto">
        <!-- Main Footer Content -->
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About Column -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-emerald-600 rounded flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-white">DESA DIGITAL</span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400 mb-6">
                        Website resmi Desa Digital. Media komunikasi dan transparansi Pemerintah Desa kepada masyarakat. Mewujudkan desa yang maju, mandiri, dan sejahtera.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-8 h-8 bg-slate-800 rounded flex items-center justify-center hover:bg-emerald-600 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="w-8 h-8 bg-slate-800 rounded flex items-center justify-center hover:bg-emerald-600 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.269.156 5.023 1.938 5.18 5.181.06 1.264.072 1.645.072 4.849 0 3.205-.012 3.584-.072 4.849-.157 3.269-1.939 5.023-5.18 5.18-1.265.06-1.644.072-4.85.072-3.204 0-3.584-.012-4.85-.072-3.269-.156-5.023-1.938-5.18-5.181-.06-1.264-.072-1.644-.072-4.849 0-3.204.012-3.584.072-4.849.156-3.269 1.938-5.023 5.18-5.181 1.264-.06 1.645-.072 4.849-.072zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        <a href="#" class="w-8 h-8 bg-slate-800 rounded flex items-center justify-center hover:bg-emerald-600 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                    </div>
                </div>

                <!-- Links Column -->
                <div>
                    <h3 class="text-white font-bold mb-4">Tautan Cepat</h3>
                    <?php 
                    if (function_exists('wp_nav_menu') && has_nav_menu('footer')) {
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'space-y-2 text-sm',
                            'link_class'     => 'hover:text-emerald-500 transition',
                            'fallback_cb'    => false
                        ));
                    } else {
                    ?>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-emerald-500 transition">Profil Desa</a></li>
                        <li><a href="#" class="hover:text-emerald-500 transition">Struktur Pemerintahan</a></li>
                        <li><a href="#" class="hover:text-emerald-500 transition">Layanan Administrasi</a></li>
                        <li><a href="#" class="hover:text-emerald-500 transition">Berita & Pengumuman</a></li>
                        <li><a href="#" class="hover:text-emerald-500 transition">Produk Hukum</a></li>
                        <li><a href="#" class="hover:text-emerald-500 transition">Peta Desa</a></li>
                    </ul>
                    <?php } ?>
                </div>

                <!-- Contact Column -->
                <div>
                    <h3 class="text-white font-bold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span><?php echo get_option('smartvillage_address', 'Jl. Raya Desa No. 123, Kecamatan Orion, Kabupaten Orion, Jawa Barat 40000'); ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span><?php echo get_option('smartvillage_phone', '(021) 1234-5678'); ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span><?php echo get_option('smartvillage_email', 'sekretariat@desa-digital.go.id'); ?></span>
                        </li>
                    </ul>
                </div>

                <!-- Emergency Column -->
                <div>
                    <h3 class="text-white font-bold mb-4">Layanan Darurat</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="#" class="bg-slate-800 p-3 rounded text-center hover:bg-red-900 hover:text-red-100 transition group">
                            <svg class="w-6 h-6 mx-auto mb-1 text-red-500 group-hover:text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                            <span class="text-xs font-bold">Pemadam</span>
                        </a>
                        <a href="#" class="bg-slate-800 p-3 rounded text-center hover:bg-blue-900 hover:text-blue-100 transition group">
                            <svg class="w-6 h-6 mx-auto mb-1 text-blue-500 group-hover:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span class="text-xs font-bold">Polisi</span>
                        </a>
                        <a href="#" class="bg-slate-800 p-3 rounded text-center hover:bg-green-900 hover:text-green-100 transition group">
                            <svg class="w-6 h-6 mx-auto mb-1 text-green-500 group-hover:text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-xs font-bold">Ambulans</span>
                        </a>
                        <a href="#" class="bg-slate-800 p-3 rounded text-center hover:bg-yellow-900 hover:text-yellow-100 transition group">
                            <svg class="w-6 h-6 mx-auto mb-1 text-yellow-500 group-hover:text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="text-xs font-bold">PLN</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-slate-950 py-6 border-t border-slate-800">
            <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; <?php echo date('Y'); ?> Pemerintah Desa Digital. All rights reserved.</p>
                <div class="mt-2 md:mt-0">
                    Powered by <a href="#" class="text-slate-400 hover:text-white">Orion CMS</a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Alpine-like minimal interactions if needed
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                // Smooth scroll logic could go here
            });
        });
    </script>
</body>
</html>
