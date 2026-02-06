</div> <!-- End container -->

<footer class="bg-gray-800 text-white mt-12 py-8">
    <div class="container mx-auto px-4 text-center">
        <?php
        if (has_nav_menu('footer')) {
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container' => false,
                'menu_class' => 'flex justify-center space-x-4 mb-4 text-sm list-none',
                'link_class' => 'text-gray-400 hover:text-white transition'
            ));
        }
        ?>
        <p>&copy; <?php echo date('Y'); ?> Orion CMS. Built with PHP Native & Tailwind.</p>
        <p class="text-gray-500 text-sm mt-2">Meniru struktur WordPress.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
