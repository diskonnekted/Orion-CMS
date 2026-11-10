<?php
/**
 * Orion Government Theme Functions
 */

// Add theme support
add_theme_support('post-thumbnails');

// Register navigation menus
function orion_gov_register_menus() {
    register_nav_menus(array(
        'primary' => 'Main Menu',
        'footer' => 'Footer Menu'
    ));
}
add_action('init', 'orion_gov_register_menus');

// Enqueue styles (optional, as we use CDN in header.php for simplicity in this setup)
// But good practice to have the hook ready
function orion_gov_scripts() {
    // Styles are loaded in header.php via CDN
}
add_action('wp_enqueue_scripts', 'orion_gov_scripts');
