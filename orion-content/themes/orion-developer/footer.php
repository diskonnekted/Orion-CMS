    <footer class="bg-slate-900 py-16 text-slate-400">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-6">
                    <a href="<?php echo site_url(); ?>" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="text-xl font-black text-white tracking-tight italic uppercase">ORION<span class="text-indigo-600">DEV</span></span>
                    </a>
                    <p class="text-sm leading-relaxed">
                        Layanan pembuatan website profesional, cepat, dan terjangkau untuk semua kebutuhan bisnis anda.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Navigation</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#services" class="hover:text-indigo-400 transition">Layanan</a></li>
                        <li><a href="#pricing" class="hover:text-indigo-400 transition">Paket Harga</a></li>
                        <li><a href="#about" class="hover:text-indigo-400 transition">Tentang Kami</a></li>
                        <li><a href="#contact" class="hover:text-indigo-400 transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Quick Links</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-indigo-400 transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Sitemap</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Newsletter</h4>
                    <p class="text-sm mb-4">Dapatkan tips digital marketing gratis setiap minggunya.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email address" class="bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500 w-full">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">Join</button>
                    </div>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-medium">
                <p>&copy; <?php echo date('Y'); ?> Orion Developer. All rights reserved. Created with Orion CMS.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">LinkedIn</a>
                    <a href="#" class="hover:text-white transition">Instagram</a>
                    <a href="#" class="hover:text-white transition">Twitter</a>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
