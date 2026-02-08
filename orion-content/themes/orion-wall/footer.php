</div> <!-- End container -->

<footer class="bg-slate-950 text-slate-400 mt-12 py-8 border-t border-slate-800">
    <div class="container mx-auto px-4 text-center">
        <?php
        if (has_nav_menu('footer')) {
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container' => false,
                'menu_class' => 'flex justify-center space-x-4 mb-4 text-sm list-none',
                'link_class' => 'text-slate-400 hover:text-white transition'
            ));
        }
        ?>
        <p>&copy; <?php echo date('Y'); ?> Orion Wall. Powered by Orion CMS.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
