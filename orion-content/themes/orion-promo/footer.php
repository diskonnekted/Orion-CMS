<footer class="bg-slate-900 text-slate-300 py-12">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="mb-6 md:mb-0 text-left">
                <img src="<?php echo site_url('/assets/img/orion-light.png'); ?>" alt="Orion CMS" class="h-16 mb-4 block">
                <h3 class="text-2xl font-bold text-white mb-2">Orion CMS</h3>
                <p class="text-slate-400 max-w-xs">Platform manajemen konten modern yang dirancang super ringan dan difokuskan pada kecepatan.</p>
            </div>
            <div class="flex space-x-6">
                <a href="<?php echo site_url('/?page=documentation'); ?>" class="hover:text-brand-400 transition">Dokumentasi</a>
                <a href="https://github.com/diskonnekted/Orion-CMS" class="hover:text-brand-400 transition">GitHub</a>
            </div>
        </div>
        <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm text-slate-500">
            &copy; <?php echo date('Y'); ?> Orion CMS. All rights reserved. ❤️ From <a href="https://www.clasnet.co.id" class="text-brand-400 hover:text-brand-300 transition">Clasnet</a> to You.
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
