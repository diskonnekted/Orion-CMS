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
$db_ok = orion_db_connect();
if (!$db_ok) {
    $db_type = defined('DB_TYPE') ? (string) DB_TYPE : 'mysql';
    $details = array(
        'db_type' => $db_type,
        'error' => isset($GLOBALS['orion_db_connect_error']) ? (string) $GLOBALS['orion_db_connect_error'] : 'Unknown'
    );
    if (strtolower($db_type) === 'sqlite' && defined('DB_FILE')) {
        $details['db_file'] = (string) DB_FILE;
        $details['db_file_exists'] = file_exists((string) DB_FILE) ? 'yes' : 'no';
        $details['db_file_dir_writable'] = is_writable(dirname((string) DB_FILE)) ? 'yes' : 'no';
    }
    orion_render_installation_error('Koneksi database gagal', 'Konfigurasi database ada, tapi database tidak bisa dipakai. Jalankan installer atau periksa file DB.', $details);
    exit;
}

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
    $ok = orion_install();
    if (!$ok) {
        $details = array(
            'db_type' => defined('DB_TYPE') ? (string) DB_TYPE : 'mysql',
            'install_error' => isset($GLOBALS['orion_install_error']) ? (string) $GLOBALS['orion_install_error'] : '',
            'db_error' => isset($orion_db) && isset($orion_db->error) ? (string) $orion_db->error : ''
        );
        orion_render_installation_error('Database belum siap', 'orion-config.php sudah ada, tetapi tabel Orion belum siap atau gagal dibuat. Jalankan installer (force) untuk setup ulang.', $details);
        exit;
    }
}

if (function_exists('orion_seo_start_output_buffer')) {
    orion_seo_start_output_buffer();
}

function orion_migrate_tagline_default() {
    $flag = get_option('orion_tagline_migrated_v1', '0');
    if ($flag === '1') return;

    $current = (string) get_option('blogdescription', '');
    $current_trim = trim($current);
    $defaults = array(
        '',
        'Just another Orion CMS site',
        'Just another Orion CMS site ',
        'Just another Orion CMS Site',
        'Just another Orion CMS',
    );

    if (in_array($current_trim, $defaults, true)) {
        update_option('blogdescription', 'fast.flexible.free');
    }

    update_option('orion_tagline_migrated_v1', '1');
}
orion_migrate_tagline_default();

/**
 * Ensure default information pages exist (About, Manual, Privacy, Terms)
 */
function orion_ensure_default_info_pages() {
    $flag = get_option('orion_default_info_pages_created', '0');
    if ($flag === '1') {
        return;
    }

    global $orion_db, $table_prefix;
    $table = $table_prefix . 'posts';

    $pages = array(
        array(
            'title' => 'About',
            'content' => '<h2>Tentang Orion CMS</h2><p>Halaman ini menjelaskan secara singkat tentang Orion CMS, sistem manajemen konten ringan dan modern yang dirancang untuk kecepatan dan kemudahan pengembangan.</p>'
        ),
        array(
            'title' => 'Manual',
            'content' => '<h2>Manual Pengguna</h2><p>Halaman ini dapat digunakan untuk mendokumentasikan panduan penggunaan situs, alur kerja, dan instruksi penting bagi administrator maupun editor konten.</p>'
        ),
        array(
            'title' => 'Privacy',
            'content' => '<h2>Kebijakan Privasi</h2><p>Gunakan halaman ini untuk menjelaskan bagaimana data pengunjung dikumpulkan, digunakan, dan dilindungi sesuai regulasi yang berlaku.</p>'
        ),
        array(
            'title' => 'Terms',
            'content' => '<h2>Syarat dan Ketentuan</h2><p>Halaman ini berisi syarat dan ketentuan penggunaan layanan atau situs yang perlu disetujui oleh pengguna.</p>'
        ),
    );

    foreach ($pages as $page) {
        $title = $orion_db->real_escape_string($page['title']);
        $sql = "SELECT ID FROM $table WHERE post_title = '$title' AND post_type = 'page' AND post_status = 'publish' LIMIT 1";
        $result = $orion_db->query($sql);

        if (!$result || $result->num_rows === 0) {
            wp_insert_post(array(
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
        }
    }

    update_option('orion_default_info_pages_created', '1');
}

orion_ensure_default_info_pages();

if (function_exists('register_post_type')) {
    register_post_type('post', array(
        'label' => 'Posts',
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('category')
    ));
    register_post_type('page', array(
        'label' => 'Pages',
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'thumbnail')
    ));
    register_post_type('nav_menu_item', array(
        'label' => 'Menu Item',
        'public' => false,
        'show_in_menu' => false,
        'supports' => array('title')
    ));
    register_post_type('product', array(
        'label' => 'Products',
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('product_cat')
    ));
    register_post_type('portfolio', array(
        'label' => 'Portfolio',
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('portfolio_cat')
    ));
}

if (function_exists('register_taxonomy')) {
    register_taxonomy('category', array('post'), array(
        'label' => 'Categories',
        'public' => true,
        'hierarchical' => true
    ));
    register_taxonomy('product_cat', array('post', 'product'), array(
        'label' => 'Product Categories',
        'public' => true,
        'hierarchical' => true
    ));
    register_taxonomy('nav_menu', array('nav_menu_item'), array(
        'label' => 'Navigation Menus',
        'public' => false,
        'hierarchical' => false
    ));
    register_taxonomy('portfolio_cat', array('portfolio'), array(
        'label' => 'Portfolio Categories',
        'public' => true,
        'hierarchical' => true
    ));
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
        if ($_GET['taxonomy'] === 'product_cat') {
            $query_args['post_type'] = array('post', 'product');
        }
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
        $vars = $wp_query->query_vars;
        if (!isset($vars['post_type']) || $vars['post_type'] === 'post' || $vars['post_type'] === 'any') {
            $vars['post_type'] = array('post', 'product');
        }
        $vars['taxonomy'] = 'product_cat';
        $wp_query->query($vars);
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

// Fire init Hook
do_action( 'init' );

// Fire Theme Setup Hook
do_action( 'after_setup_theme' );

// Auto-register primary menu if theme didn't register any
if ( function_exists('get_registered_nav_menus') && function_exists('register_nav_menus') ) {
    $menus = get_registered_nav_menus();
    if ( empty($menus) ) {
        // Optional: register_nav_menus(array('primary' => 'Primary Menu'));
    }
}
