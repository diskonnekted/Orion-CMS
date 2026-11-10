<?php
/**
 * Plugin Name: Orion Bakery Menu
 * Description: Manages daily bakery menu availability and displays it in an attractive way.
 * Version: 1.0.0
 * Author: Orion Team
 */

// Register Admin Menu
function orion_bakery_menu_admin_menu() {
    add_menu_page(
        'Menu Hari Ini',
        'Menu Roti',
        'manage_options',
        'orion-bakery-menu',
        'orion_bakery_menu_page',
        'dashicons-cart',
        25
    );
}
add_action('admin_menu', 'orion_bakery_menu_admin_menu');

// Admin Page Callback
function orion_bakery_menu_page() {
    require_once plugin_dir_path(__FILE__) . 'manager.php';
}

// Frontend Display Logic
function orion_bakery_menu_frontend() {
    if (isset($_GET['page']) && $_GET['page'] === 'daily-menu') {
        // Load the special template
        $template = plugin_dir_path(__FILE__) . 'template-daily-menu.php';
        if (file_exists($template)) {
            include $template;
            exit;
        }
    }
}
// Use 'init' or 'wp' hook instead of 'template_redirect' if 'template_redirect' is not firing correctly in this environment
// However, in Orion CMS structure, template loading often happens in index.php via require.
// If index.php loads theme/index.php BEFORE hooks fire, we might be too late.
// Let's check where template_redirect is fired. It is likely NOT fired in the custom index.php of Orion.
// Orion CMS uses query strings ?page=xyz.
// We can hook into 'init' and check $_GET['page'] manually and load template.

add_action('init', 'orion_bakery_menu_frontend');

// Helper to check if a product is available today
function orion_bakery_is_available_today($post_id) {
    $daily_menu = get_option('orion_bakery_daily_products', array());
    return in_array($post_id, $daily_menu);
}

// Shortcode to display daily menu anywhere
function orion_bakery_daily_menu_shortcode($atts) {
    ob_start();
    include plugin_dir_path(__FILE__) . 'template-daily-menu-part.php';
    return ob_get_clean();
}

function orion_bakery_register_shortcodes() {
    add_shortcode('bakery_daily_menu', 'orion_bakery_daily_menu_shortcode');
}
add_action('init', 'orion_bakery_register_shortcodes');
