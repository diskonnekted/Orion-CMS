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

// Initialize the main Query object
$query_args = array();

// Basic Router / Query Setup
if (isset($_GET['p'])) {
    $query_args['p'] = (int) $_GET['p'];
    $query_args['post_type'] = 'any'; // Allow finding any post type by ID
} elseif (isset($_GET['page_id'])) {
    $query_args['page_id'] = (int) $_GET['page_id'];
    $query_args['post_type'] = 'page';
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

/**
 * Load Schema and Check Installation
 */
require_once ABSPATH . 'orion-includes/schema.php';
if ( !orion_is_installed() ) {
    orion_install();
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
