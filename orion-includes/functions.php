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
    
    // Check if exists
    $existing = get_option($option, null);
    
    // Serialize if array/object
    if (is_array($value) || is_object($value)) {
        $value = serialize($value);
    }
    
    $escaped_value = $orion_db->real_escape_string($value);
    
    if ($existing !== null) {
        // Update
        $sql = "UPDATE $table SET option_value = '$escaped_value' WHERE option_name = '$option'";
    } else {
        // Insert
        $sql = "INSERT INTO $table (option_name, option_value, autoload) VALUES ('$option', '$escaped_value', 'yes')";
    }
    
    return $orion_db->query($sql);
}

/**
 * Sanitize Hex Color
 * 
 * @param string $color
 * @return string
 */
function sanitize_hex_color($color) {
    if ('' === $color) {
        return '';
    }

    // 3 or 6 hex digits, or the empty string.
    if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
        return $color;
    }

    return '';
}

/**
 * Get first image from content
 * 
 * @param string $content
 * @return string|false Image URL or false
 */
function get_first_image_from_content($content) {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
    $first_img = false;
    if (isset($matches[1][0])) {
        $first_img = $matches[1][0];
    }
    return $first_img;
}



/**
 * Delete option
 */
function delete_option($option) {
    global $orion_db, $table_prefix;
    $table = $table_prefix . 'options';
    
    $orion_db->query("DELETE FROM $table WHERE option_name = '$option'");
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

function site_url($path = '') {
    $db_url = get_option('siteurl');
    if ($db_url) {
        return rtrim($db_url, '/') . $path;
    }

    $protocol = "http://";
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = "https://";
    }

    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    
    $basePath = '';

    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $absPath = rtrim(str_replace('\\', '/', realpath(ABSPATH)), '/');
        if ($docRoot && $absPath && strpos($absPath, $docRoot) === 0) {
            $sub = trim(substr($absPath, strlen($docRoot)), '/');
            if ($sub !== '') {
                $basePath = '/' . $sub;
            }
        }
    }

    if ($basePath === '') {
        $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $dir = rtrim(str_replace('\\', '/', dirname($script_name)), '/');
        if ($dir && $dir !== '/') {
            $basePath = $dir;
        }
    }

    $base = $protocol . $host . $basePath;

    return rtrim($base, '/') . $path;
}

function orion_normalize_internal_url($url) {
    if (!$url) {
        return $url;
    }

    $parts = @parse_url($url);
    if ($parts === false || !isset($parts['path'])) {
        return $url;
    }

    $path = $parts['path'];

    if (strpos($path, '/orion-content/') === 0 || strpos($path, '/assets/') === 0) {
        $new = rtrim(site_url(), '/') . $path;
        if (isset($parts['query'])) {
            $new .= '?' . $parts['query'];
        }
        if (isset($parts['fragment'])) {
            $new .= '#' . $parts['fragment'];
        }
        return $new;
    }

    return $url;
}

function orion_normalize_content_urls($content) {
    if (!$content) {
        return $content;
    }

    $content = preg_replace_callback('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', function ($matches) {
        $original = $matches[0];
        $src = $matches[1];
        $normalized = orion_normalize_internal_url($src);
        if ($normalized === $src) {
            return $original;
        }
        return str_replace($src, htmlspecialchars($normalized, ENT_QUOTES, 'UTF-8'), $original);
    }, $content);

    return $content;
}
add_filter('the_content', 'orion_normalize_content_urls', 5, 1);

function orion_normalize_meta_value($value) {
    if (!is_string($value) || $value === '') {
        return $value;
    }

    if (strpos($value, '/orion-content/') === false && strpos($value, '/assets/') === false) {
        return $value;
    }

    $decoded = json_decode($value, true);
    if (is_array($decoded)) {
        $changed = false;
        foreach ($decoded as $key => $item) {
            if (is_string($item)) {
                $new = orion_normalize_internal_url($item);
                if ($new !== $item) {
                    $decoded[$key] = $new;
                    $changed = true;
                }
            } elseif (is_array($item) && isset($item['url']) && is_string($item['url'])) {
                $new = orion_normalize_internal_url($item['url']);
                if ($new !== $item['url']) {
                    $decoded[$key]['url'] = $new;
                    $changed = true;
                }
            }
        }
        if ($changed) {
            return json_encode($decoded);
        }
        return $value;
    }

    return orion_normalize_internal_url($value);
}

function orion_normalize_html_output($html) {
    if ($html === '' || $html === null) {
        return $html;
    }

    $base = rtrim(site_url(), '/');

    $html = preg_replace_callback(
        '#https?://[^"\'\s]+(/(orion-content|assets)/[^"\'\s]+)#i',
        function ($matches) use ($base) {
            return $base . $matches[1];
        },
        $html
    );

    return $html;
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
    echo '<meta name="generator" content="Orion CMS by Diskonnekted" />' . "\n";
    
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

/**
 * Get available color schemes
 */
function orion_get_color_schemes() {
    return [
        'default' => [
            'name' => 'Orion Blue (Default)',
            'slate' => ['800' => '#1e293b', '900' => '#0f172a'],
            'orion' => ['50' => '#eff6ff', '100' => '#dbeafe', '200' => '#bfdbfe', '300' => '#93c5fd', '400' => '#60a5fa', '500' => '#3b82f6', '600' => '#2563eb', '700' => '#1d4ed8', '800' => '#1e40af', '900' => '#1e3a8a']
        ],
        'emerald_forest' => [
            'name' => 'Emerald Forest',
            'slate' => ['800' => '#022c22', '900' => '#01140f'],
            'orion' => ['50' => '#ecfdf5', '100' => '#d1fae5', '200' => '#a7f3d0', '300' => '#6ee7b7', '400' => '#34d399', '500' => '#10b981', '600' => '#059669', '700' => '#047857', '800' => '#065f46', '900' => '#064e3b']
        ],
        'amber_sunset' => [
            'name' => 'Amber Sunset',
            'slate' => ['800' => '#451a03', '900' => '#1f0a02'],
            'orion' => ['50' => '#fffbeb', '100' => '#fef3c7', '200' => '#fde68a', '300' => '#fcd34d', '400' => '#fbbf24', '500' => '#f59e0b', '600' => '#d97706', '700' => '#b45309', '800' => '#92400e', '900' => '#78350f']
        ],
        'rose_mauve' => [
            'name' => 'Rose Mauve',
            'slate' => ['800' => '#4a044e', '900' => '#2b022e'],
            'orion' => ['50' => '#fff1f2', '100' => '#ffe4e6', '200' => '#fecdd3', '300' => '#fda4af', '400' => '#fb7185', '500' => '#f43f5e', '600' => '#e11d48', '700' => '#be123c', '800' => '#9f1239', '900' => '#881337']
        ],
        'teal_cyan' => [
            'name' => 'Teal Cyan Mix',
            'slate' => ['800' => '#042f2e', '900' => '#022121'],
            'orion' => ['50' => '#ecfeff', '100' => '#cffafe', '200' => '#a5f3fc', '300' => '#67e8f9', '400' => '#22d3ee', '500' => '#06b6d4', '600' => '#0891b2', '700' => '#0e7490', '800' => '#115e59', '900' => '#134e4a']
        ],
        'olive_leaf' => [
            'name' => 'Olive Leaf',
            'slate' => ['800' => '#262b16', '900' => '#13160b'],
            'orion' => ['50' => '#e2e7d1', '100' => '#c5d0a3', '200' => '#aab87a', '300' => '#9ba86a', '400' => '#88994f', '500' => '#606c38', '600' => '#4c562c', '700' => '#394121', '800' => '#262b16', '900' => '#13160b']
        ],
        'deep_space_blue' => [
            'name' => 'Deep Space Blue',
            'slate' => ['800' => '#01131c', '900' => '#00090e'],
            'orion' => ['50' => '#e0f2fe', '100' => '#bae6fd', '200' => '#7dd3fc', '300' => '#38bdf8', '400' => '#0ea5e9', '500' => '#0284c7', '600' => '#0369a1', '700' => '#075985', '800' => '#0c4a6e', '900' => '#082f49']
        ],
        'thistle' => [
            'name' => 'Thistle Pastel',
            'slate' => ['800' => '#57346b', '900' => '#2b1a36'],
            'orion' => ['50' => '#f5f0f8', '100' => '#ebe1f0', '200' => '#e0d1e6', '300' => '#dccae6', '400' => '#d6c2e2', '500' => '#cdb4db', '600' => '#a87ec1', '700' => '#824ea1', '800' => '#57346b', '900' => '#2b1a36']
        ]
    ];
}

/**
 * Get current color scheme
 */
function orion_get_current_scheme() {
    $schemes = orion_get_color_schemes();
    $current = get_option('admin_color_scheme', 'default');
    return isset($schemes[$current]) ? $schemes[$current] : $schemes['default'];
}

global $wp_post_types;
if (!isset($wp_post_types) || !is_array($wp_post_types)) {
    $wp_post_types = array();
}

function register_post_type($post_type, $args = array()) {
    global $wp_post_types;
    if (!is_string($post_type) || $post_type === '') {
        return false;
    }
    $defaults = array(
        'label' => ucfirst($post_type),
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array()
    );
    if (function_exists('wp_parse_args')) {
        $merged = wp_parse_args($args, $defaults);
    } else {
        $merged = array_merge($defaults, (array) $args);
    }
    $merged['name'] = $post_type;
    $wp_post_types[$post_type] = (object) $merged;
    return $wp_post_types[$post_type];
}

function get_post_types($args = array(), $output = 'names', $operator = 'and') {
    global $wp_post_types;
    if (!is_array($wp_post_types)) {
        $wp_post_types = array();
    }
    $types = $wp_post_types;
    if ($output === 'objects') {
        return $types;
    }
    return array_keys($types);
}

function get_post_type_object($post_type) {
    global $wp_post_types;
    if (isset($wp_post_types[$post_type])) {
        return $wp_post_types[$post_type];
    }
    return null;
}

global $wp_taxonomies;
if (!isset($wp_taxonomies) || !is_array($wp_taxonomies)) {
    $wp_taxonomies = array();
}

function register_taxonomy($taxonomy, $object_type, $args = array()) {
    global $wp_taxonomies;
    if (!is_string($taxonomy) || $taxonomy === '') {
        return false;
    }
    if (!is_array($object_type)) {
        $object_type = array($object_type);
    }
    $defaults = array(
        'label' => ucfirst(str_replace('_', ' ', $taxonomy)),
        'public' => true,
        'hierarchical' => true
    );
    if (function_exists('wp_parse_args')) {
        $merged = wp_parse_args($args, $defaults);
    } else {
        $merged = array_merge($defaults, (array) $args);
    }
    $merged['name'] = $taxonomy;
    $merged['object_type'] = $object_type;
    $wp_taxonomies[$taxonomy] = (object) $merged;
    return $wp_taxonomies[$taxonomy];
}

function get_taxonomies($args = array(), $output = 'names', $operator = 'and') {
    global $wp_taxonomies;
    if (!is_array($wp_taxonomies)) {
        $wp_taxonomies = array();
    }
    $tax = $wp_taxonomies;
    if ($output === 'objects') {
        return $tax;
    }
    return array_keys($tax);
}

function get_taxonomy($taxonomy) {
    global $wp_taxonomies;
    if (isset($wp_taxonomies[$taxonomy])) {
        return $wp_taxonomies[$taxonomy];
    }
    return null;
}
