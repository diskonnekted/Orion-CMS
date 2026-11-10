</main>

<footer class="bg-bakery-900 text-bakery-100 border-t border-bakery-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Col 1: Brand -->
            <div class="space-y-6">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                    <?php 
                        $logo_url = get_option('orion_bakery_logo', ''); 
                        if($logo_url): 
                    ?>
                        <img class="h-10 w-auto transform group-hover:rotate-6 transition duration-300" src="<?php echo $logo_url; ?>" alt="Logo">
                    <?php else: ?>
                        <div class="h-8 w-8 bg-bakery-500 rounded-full flex items-center justify-center text-white font-serif font-bold text-xl shadow-lg transform group-hover:scale-110 transition duration-300">
                            B
                        </div>
                    <?php endif; ?>
                    <span class="text-2xl font-serif font-bold text-bakery-50 leading-none group-hover:text-bakery-300 transition">
                        <?php echo get_option('blogname', 'Orion Bakery'); ?>
                    </span>
                </a>
                <p class="text-bakery-200 text-sm leading-relaxed">
                    Menghadirkan kehangatan roti segar setiap hari. Dibuat dengan bahan-bahan premium dan resep warisan keluarga untuk menemani setiap momen spesial Anda.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-bakery-300 hover:text-white transition transform hover:scale-110">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="text-bakery-300 hover:text-white transition transform hover:scale-110">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.451 2.535c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Menu -->
            <div>
                <h3 class="text-lg font-serif font-bold text-bakery-50 mb-6 border-b border-bakery-700 pb-2 inline-block">Menu Favorit</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-bakery-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-bakery-500 rounded-full"></span> Roti Tawar Premium</a></li>
                    <li><a href="#" class="hover:text-bakery-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-bakery-500 rounded-full"></span> Croissant Butter</a></li>
                    <li><a href="#" class="hover:text-bakery-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-bakery-500 rounded-full"></span> Donat Kampung</a></li>
                    <li><a href="#" class="hover:text-bakery-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-bakery-500 rounded-full"></span> Kue Ulang Tahun</a></li>
                    <li><a href="#" class="hover:text-bakery-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-bakery-500 rounded-full"></span> Pastry Box</a></li>
                </ul>
            </div>

            <!-- Col 3: Hours -->
            <div>
                <h3 class="text-lg font-serif font-bold text-bakery-50 mb-6 border-b border-bakery-700 pb-2 inline-block">Jam Operasional</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between border-b border-bakery-800 pb-2">
                        <span>Senin - Jumat</span>
                        <span class="font-bold text-bakery-300">07:00 - 20:00</span>
                    </li>
                    <li class="flex justify-between border-b border-bakery-800 pb-2">
                        <span>Sabtu</span>
                        <span class="font-bold text-bakery-300">08:00 - 21:00</span>
                    </li>
                    <li class="flex justify-between border-b border-bakery-800 pb-2">
                        <span>Minggu</span>
                        <span class="font-bold text-bakery-300">08:00 - 18:00</span>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Contact -->
            <div>
                <h3 class="text-lg font-serif font-bold text-bakery-50 mb-6 border-b border-bakery-700 pb-2 inline-block">Hubungi Kami</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-bakery-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Jl. Aroma Manis No. 88<br>Kota Kuliner, 54321</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-bakery-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-lg font-bold text-bakery-300">(021) 555-ROTI</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-bakery-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>order@orionbakery.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-16 pt-8 border-t border-bakery-800 flex flex-col md:flex-row justify-between items-center text-sm text-bakery-400">
            <p>&copy; <?php echo date('Y'); ?> Orion Bakery. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
