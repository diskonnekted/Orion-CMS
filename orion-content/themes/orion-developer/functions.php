<?php
/**
 * Orion Developer Theme Functions
 */

function orion_developer_setup() {
    register_nav_menus(array(
        'primary' => 'Menu Utama',
        'footer'  => 'Menu Footer'
    ));
}
add_action('after_setup_theme', 'orion_developer_setup');

/**
 * Handle Theme Settings
 */
function orion_developer_settings_init() {
    // Default WhatsApp
    if (!get_option('orion_dev_whatsapp')) {
        update_option('orion_dev_whatsapp', '081328128315');
    }
    
    // Default Package 1
    if (!get_option('orion_dev_pkg1_name')) {
        update_option('orion_dev_pkg1_name', 'Express Starter');
        update_option('orion_dev_pkg1_price', '500rb');
        update_option('orion_dev_pkg1_features', "Domain & Hosting 1 Thn
Template Modern
Integrasi WhatsApp
3 Halaman
Express: 2 Hari Jadi");
    }
    
    // Default Package 2
    if (!get_option('orion_dev_pkg2_name')) {
        update_option('orion_dev_pkg2_name', 'Professional Agency');
        update_option('orion_dev_pkg2_price', '1.5jt');
        update_option('orion_dev_pkg2_features', "Domain & Hosting High Spec
Desain Custom (Modern)
SEO On-Page & Speed Up
Landing Page + 5 Halaman
Email Bisnis");
    }
    
    // Default Package 3
    if (!get_option('orion_dev_pkg3_name')) {
        update_option('orion_dev_pkg3_name', 'Custom Enterprise');
        update_option('orion_dev_pkg3_price', 'Mulai 3jt');
        update_option('orion_dev_pkg3_features', "Desain Eksklusif
Sistem / CMS Custom
Advanced SEO & Analytics
Halaman Tak Terbatas
Priority Support 24/7");
    }
}
add_action('init', 'orion_developer_settings_init');

/**
 * Include settings page
 */
require_once( dirname( __FILE__ ) . '/settings.php' );

/**
 * Helper: Parse features into list
 */
function orion_dev_get_features($key) {
    $raw = get_option($key, '');
    if (empty($raw)) return array();
    return array_filter(array_map('trim', explode("
", $raw)));
}

/**
 * WhatsApp Link Generator
 */
function orion_dev_wa_link($package_name = '') {
    $wa = get_option('orion_dev_whatsapp', '081328128315');
    // Sanitize number: remove non-numeric
    $wa = preg_replace('/[^0-9]/', '', $wa);
    if (substr($wa, 0, 1) === '0') {
        $wa = '62' . substr($wa, 1);
    }
    
    $text = "Halo Orion Developer, saya tertarik dengan jasa pembuatan website.";
    if ($package_name) {
        $text .= " Paket yang saya minati: " . $package_name;
    }
    
    return "https://wa.me/{$wa}?text=" . urlencode($text);
}
