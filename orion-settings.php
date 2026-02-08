<?php
/**
 * Used to set up and fix common variables and include
 * the Orion procedural and class library.
 *
 * @package Orion
 */

// Define standard WordPress constants if not present
if (!defined('OBJECT')) define('OBJECT', 'OBJECT');
if (!defined('ARRAY_A')) define('ARRAY_A', 'ARRAY_A');
if (!defined('ARRAY_N')) define('ARRAY_N', 'ARRAY_N');

/**
 * Include the Plugin API (Action/Filter system)
 */
require_once ABSPATH . 'orion-includes/plugin.php';

/**
 * Include the Main Functions
 */
require_once ABSPATH . 'orion-includes/functions.php';

/**
 * Include User API
 */
require_once ABSPATH . 'orion-includes/user.php';

/**
 * Include WordPress Compatibility Layer
 */
require_once ABSPATH . 'orion-includes/wp-compat.php';

// Initialize the database connection
orion_db_connect();

/**
 * Load Post API
 */
require_once ABSPATH . 'orion-includes/post.php';

/**
 * Load Taxonomy API
 */
require_once ABSPATH . 'orion-includes/taxonomy.php';

/**
 * Load Schema and Check Installation
 */
require_once ABSPATH . 'orion-includes/schema.php';
if ( !orion_is_installed() ) {
    orion_install();
}

// Initialize the main Query object
$query_args = array();

// Basic Router / Query Setup
if (isset($_GET['p'])) {
    $query_args['p'] = (int) $_GET['p'];
    $query_args['post_type'] = 'any'; // Allow finding any post type by ID
} elseif (isset($_GET['page_id'])) {
    $query_args['page_id'] = (int) $_GET['page_id'];
    $query_args['post_type'] = 'page';
} elseif (isset($_GET['cat'])) {
    $query_args['category'] = (int) $_GET['cat'];
    $query_args['post_type'] = 'post';
    if (isset($_GET['taxonomy'])) {
        $query_args['taxonomy'] = $_GET['taxonomy'];
    }
} else {
    // Check for Front Page setting
    if (get_option('show_on_front') == 'page') {
        $page_on_front = get_option('page_on_front');
        if ($page_on_front) {
            $query_args['page_id'] = $page_on_front;
            $query_args['post_type'] = 'page';
        }
    }
}

$wp_query = new WP_Query($query_args);
$orion_query = $wp_query; // Alias

// Default Taxonomy Logic based on Active Theme
// This ensures that if we are in Shop theme, we default to product_cat,
// and if we are in Magazine/other, we default to category.
if ( (!isset($query_args['post_type']) || $query_args['post_type'] == 'post') && !isset($query_args['category']) && !isset($query_args['taxonomy']) && !isset($query_args['p']) && !isset($query_args['page_id']) ) {
    $current_theme = get_option('template', 'orion-default');
    if ( $current_theme == 'orion-shop' ) {
        // Force product_cat for Shop main loop
        $wp_query->query(array_merge($wp_query->query_vars, array('taxonomy' => 'product_cat')));
    } else {
        // Force category for others (Magazine, etc)
        $wp_query->query(array_merge($wp_query->query_vars, array('taxonomy' => 'category')));
    }
}


// Load active plugins
$active_plugins = get_option('active_plugins');
if (is_array($active_plugins)) {
    foreach ($active_plugins as $plugin) {
        $plugin_file = ABSPATH . 'orion-content/plugins/' . $plugin;
        if (file_exists($plugin_file)) {
            include_once $plugin_file;
        }
    }
} else {
    // Fallback/Default if no option set yet (optional, maybe empty)
    // include_once ABSPATH . 'orion-content/plugins/hello-orion.php';
}

// Load Active Theme Functions
$active_theme_dir = get_template_directory();
if ( file_exists( $active_theme_dir . '/functions.php' ) ) {
    include_once( $active_theme_dir . '/functions.php' );
}

// Fire Theme Setup Hook
do_action( 'after_setup_theme' );

// Auto-register primary menu if theme didn't register any
if ( function_exists('get_registered_nav_menus') && function_exists('register_nav_menus') ) {
    $menus = get_registered_nav_menus();
    if ( empty($menus) ) {
        // Optional: register_nav_menus(array('primary' => 'Primary Menu'));
    }
}
