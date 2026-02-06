<footer class="bg-slate-900 text-slate-300 py-12 mt-12 border-t-4 border-emerald-500">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-1">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-emerald-500 rounded flex items-center justify-center text-white font-bold">O</div>
                    <span class="text-xl font-bold text-white"><?php echo get_bloginfo('name'); ?></span>
                </a>
                <p class="text-sm text-slate-400 mb-6">
                    Delivering the latest news and insights on technology, design, and culture.
                </p>
                <div class="flex space-x-4">
                    <!-- Social Icons Mock -->
                    <a href="#" class="text-slate-400 hover:text-white transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>

            <!-- Links -->
            <div class="col-span-1">
                <h5 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Company</h5>
                <?php
                if ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'space-y-2 text-sm',
                        'container'      => false,
                    ) );
                } else {
                    ?>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Contact</a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <!-- Links -->
            <div class="col-span-1">
                <h5 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Categories</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Technology</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Design</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Business</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Lifestyle</a></li>
                </ul>
            </div>

            <!-- Subscribe -->
            <div class="col-span-1">
                 <h5 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Stay Updated</h5>
                 <p class="text-xs text-slate-500 mb-4">Subscribe to our weekly newsletter.</p>
                 <button class="bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-emerald-900 px-4 py-2 rounded text-sm w-full transition-colors">Subscribe</button>
            </div>
        </div>

        <div class="border-t border-slate-800 mt-12 pt-8 text-center text-xs text-slate-600">
            &copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved. Powered by Orion CMS.
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
