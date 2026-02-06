<?php
/**
 * Main Orion API
 *
 * @package Orion
 */

/**
 * Connect to the database
 */
function orion_db_connect() {
    global $orion_db;
    
    // Suppress warning for initial connection attempt and handle manually
    $driver = new mysqli_driver();
    $mode = $driver->report_mode;
    $driver->report_mode = MYSQLI_REPORT_OFF;

    $orion_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Restore report mode
    $driver->report_mode = $mode;

    if ($orion_db->connect_error) {
        // Error 1049 is "Unknown database"
        if ($orion_db->connect_errno == 1049 && defined('ORION_DEBUG') && ORION_DEBUG) {
             $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
             if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
             }
             // Create database
             if ($conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
                 $conn->close();
                 // Retry connection
                 $orion_db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
             } else {
                 die("Failed to create database: " . $conn->error);
             }
        }
        
        if ($orion_db->connect_error) {
            die("Connection failed: " . $orion_db->connect_error);
        }
    }
}

/**
 * Mock WP_Query class for compatibility
 */
class WP_Query {
    public $query_vars = array();
    public $posts = array();
    public $post_count = 0;
    public $current_post = -1;
    public $post;
    
    public function __construct($args = array()) {
        $this->query_vars = $args;
        $this->query($args);
    }
    
    public function query($args) {
        // Simplified query logic
        $this->posts = get_posts($args); // Reuse existing get_posts
        $this->post_count = count($this->posts);
        
        // If this is main query, set global posts
        if (!isset($args['suppress_filters']) || !$args['suppress_filters']) {
             // In real WP this is complex, here we just assume
        }
    }
    
    public function have_posts() {
        if ($this->current_post + 1 < $this->post_count) {
            return true;
        } elseif ($this->current_post + 1 == $this->post_count && $this->post_count > 0) {
            do_action('loop_end');
            // Rewind? WP doesn't auto rewind usually
        }
        return false;
    }
    
    public function the_post() {
        global $post;
        $this->current_post++;
        $this->post = $this->posts[$this->current_post];
        $post = $this->post;
        setup_postdata($post);
    }
}

function setup_postdata($post) {
    global $id, $authordata, $currentday, $currentmonth, $page, $pages, $multipage, $more, $numpages;
    $id = $post->ID;
    // Stub for other globals
}

/**
 * Get header
 */
function get_header($name = null) {
    do_action('get_header', $name);
    
    $templates = array();
    $name = (string) $name;
    if ( '' !== $name ) {
        $templates[] = "header-{$name}.php";
    }

    $templates[] = 'header.php';

    locate_template($templates, true);
}

/**
 * Get footer
 */
function get_footer($name = null) {
    do_action('get_footer', $name);

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name ) {
        $templates[] = "footer-{$name}.php";
    }

    $templates[] = 'footer.php';

    locate_template($templates, true);
}

/**
 * Locate template
 */
function locate_template($template_names, $load = false, $require_once = true) {
    $located = '';
    foreach ( (array) $template_names as $template_name ) {
        if ( ! $template_name ) {
            continue;
        }
        if ( file_exists( get_template_directory() . '/' . $template_name ) ) {
            $located = get_template_directory() . '/' . $template_name;
            break;
        }
        // Fallback for WP theme partials which might assume root relative but we check theme root
    }

    if ( $load && '' != $located ) {
        load_template( $located, $require_once );
    }

    return $located;
}

/**
 * Load template
 */
function load_template( $_template_file, $require_once = true ) {
    global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

    if ( is_array( $wp_query->query_vars ) ) {
        extract( $wp_query->query_vars, EXTR_SKIP );
    }

    if ( $require_once ) {
        require_once( $_template_file );
    } else {
        require( $_template_file );
    }
}

/**
 * Get option
 */
function get_option($option, $default = false) {
    global $orion_db, $table_prefix;
    $table = $table_prefix . 'options';
    
    $result = $orion_db->query("SELECT option_value FROM $table WHERE option_name = '$option' LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_object();
        $value = $row->option_value;
        
        // Handle serialized data
        if (is_string($value) && preg_match('/^[aOsibd]:/', $value)) {
            $unserialized = @unserialize($value);
            if ($unserialized !== false || $value === 'b:0;') {
                return $unserialized;
            }
        }
        
        return $value;
    }
    return $default;
}

/**
 * Update option
 */
function update_option($option, $value) {
    global $orion_db, $table_prefix;
    $table = $table_prefix . 'options';
    
    if (is_array($value) || is_object($value)) {
        $value = serialize($value);
    }
    
    $value = $orion_db->real_escape_string($value);
    
    // Check if exists
    $check = $orion_db->query("SELECT option_id FROM $table WHERE option_name = '$option' LIMIT 1");
    
    if ($check && $check->num_rows > 0) {
        $orion_db->query("UPDATE $table SET option_value = '$value' WHERE option_name = '$option'");
    } else {
        $orion_db->query("INSERT INTO $table (option_name, option_value) VALUES ('$option', '$value')");
    }
    return true;
}

/**
 * Get template directory path
 */
function get_template_directory() {
    $theme = get_option('template', 'orion-default');
    // Check if theme directory AND index.php exist
    if ( ! file_exists( ABSPATH . 'orion-content/themes/' . $theme ) || ! file_exists( ABSPATH . 'orion-content/themes/' . $theme . '/index.php' ) ) {
        $theme = 'orion-default';
    }
    return ABSPATH . 'orion-content/themes/' . $theme;
}

/**
 * Get template directory URI
 */
function get_template_directory_uri() {
    $theme = get_option('template', 'orion-default');
    // Basic check if theme exists to avoid 404s if possible, but for URI we usually just return the path
    // We should probably consistency check with directory, but for now simple is fine.
    // However, if directory falls back to default, URI should too.
    if ( ! file_exists( ABSPATH . 'orion-content/themes/' . $theme ) || ! file_exists( ABSPATH . 'orion-content/themes/' . $theme . '/index.php' ) ) {
        $theme = 'orion-default';
    }
    return site_url() . '/orion-content/themes/' . $theme;
}

/**
 * Site URL
 */
function site_url($path = '') {
    // Check DB first
    $db_url = get_option('siteurl');
    if ($db_url) {
        return rtrim($db_url, '/') . $path;
    }

    // Simple protocol check
    $protocol = "http://";
    if ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ) {
        $protocol = "https://";
    }
    
    $domainName = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    
    // Assuming installation at root of domain or handling subdirectory manually for now
    // In real WP, this is fetched from DB options
    $url = $protocol . $domainName . '/clasnet'; 
    return $url . $path;
}

/**
 * Head hook
 */
function wp_head() {
    do_action('wp_head');
}

/**
 * Add Generator Meta Tag for Orion CMS
 */
function orion_cms_generator() {
    echo '<meta name="generator" content="Orion CMS by Clasnet" />' . "\n";
    
    // Add SEO Meta Tags if available
    $meta_desc = get_option('site_meta_description');
    if ($meta_desc) {
        echo '<meta name="description" content="' . htmlspecialchars($meta_desc) . '" />' . "\n";
    }
    
    $meta_keywords = get_option('site_meta_keywords');
    if ($meta_keywords) {
        echo '<meta name="keywords" content="' . htmlspecialchars($meta_keywords) . '" />' . "\n";
    }
}
add_action('wp_head', 'orion_cms_generator');

/**
 * Add Favicon
 */
function orion_favicon() {
    $favicon_url = site_url('/assets/img/favicon.png');
    echo '<link rel="icon" type="image/png" href="' . esc_url($favicon_url) . '" />' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url($favicon_url) . '" />' . "\n";
}
add_action('wp_head', 'orion_favicon');

/**
 * Footer hook
 */
function wp_footer() {
    do_action('wp_footer');
}

/**
 * Body Class
 */
function body_class($class = '') {
    echo 'class="' . $class . '"';
}

/**
 * Mock Loop
 */
function have_posts() {
    global $orion_query;
    return $orion_query->have_posts();
}

function the_post() {
    global $orion_query;
    $orion_query->the_post();
}
