<?php
/**
 * Orion Bakery Theme Functions
 */

// Add theme support
add_theme_support('post-thumbnails');

// Register navigation menus
function orion_bakery_register_menus() {
    register_nav_menus(array(
        'primary' => 'Main Menu',
        'footer' => 'Footer Menu'
    ));
}
add_action('init', 'orion_bakery_register_menus');

// Helper to check if EDD is active
function orion_bakery_is_edd_active() {
    return class_exists('Easy_Digital_Downloads');
}

// Add custom body class
function orion_bakery_body_class($classes) {
    $classes[] = 'font-sans text-amber-950 bg-amber-50 selection:bg-amber-200 selection:text-amber-900';
    return $classes;
}
add_filter('body_class', 'orion_bakery_body_class');

// Register scripts and styles
function orion_bakery_scripts() {
    // Tailwind and Fonts are loaded via CDN in header.php
}
add_action('wp_enqueue_scripts', 'orion_bakery_scripts');

/**
 * Fallback for get_the_excerpt() if not available in core
 */
if (!function_exists('get_the_excerpt')) {
    function get_the_excerpt($post_id = null) {
        $post = get_post($post_id);
        if (!$post) return '';
        
        if (!empty($post->post_excerpt)) {
            return $post->post_excerpt;
        }
        
        // Fallback to content
        $content = $post->post_content;
        $content = strip_shortcodes($content);
        $content = strip_tags($content);
        
        return $content;
    }
}
