<?php
/**
 * Main Orion API
 *
 * @package Orion
 */

/**
 * Connect to the database
 */
/**
 * Resolve the base URL path (e.g. "/orion") for this installation.
 *
 * Primary source is the DOCUMENT_ROOT <-> ABSPATH comparison. Only when that
 * is unavailable do we fall back to SCRIPT_NAME, and only if it is a real
 * URL-style path (starts with "/" and is not an absolute filesystem path).
 *
 * @return string Base path, always "" or starting with "/".
 */
function orion_resolve_base_path() {
    $basePath = '';

    // Primary: derive from DOCUMENT_ROOT vs ABSPATH.
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = @realpath($_SERVER['DOCUMENT_ROOT']);
        $absPath = @realpath(ABSPATH);
        if ($docRoot !== false && $absPath !== false) {
            $docRoot = rtrim(str_replace('\\', '/', $docRoot), '/');
            $absPath = rtrim(str_replace('\\', '/', $absPath), '/');
            if ($docRoot !== '' && strpos($absPath, $docRoot) === 0) {
                $sub = trim(substr($absPath, strlen($docRoot)), '/');
                $basePath = $sub === '' ? '' : '/' . $sub;
            }
        }
    }

    // Fallback: infer from SCRIPT_NAME, but only for URL-style relative paths.
    if ($basePath === '') {
        $script_name = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
        if ($script_name !== '' && $script_name[0] === '/' && strpos($script_name, ':') === false) {
            $dir = dirname($script_name);
            $dir = str_replace('\\', '/', $dir);
            $dir = rtrim($dir, '/');
            if ($dir === '.') $dir = '';

            $strip = array('/orion-admin', '/installer');
            foreach ($strip as $needle) {
                $len = strlen($needle);
                if ($dir !== '' && substr($dir, -$len) === $needle) {
                    $dir = substr($dir, 0, -$len);
                } elseif (($pos = strpos($dir, $needle . '/')) !== false) {
                    $dir = substr($dir, 0, $pos);
                }
            }
            if ($dir !== '' && $dir !== '/') $basePath = $dir;
        }
    }

    if ($basePath !== '' && $basePath[0] !== '/') {
        $basePath = '/' . $basePath;
    }

    return $basePath;
}

/**
 * Resolve the site protocol and host.
 */
function orion_resolve_site_origin() {
    $protocol = "http://";
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = "https://";
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $host = rtrim($host, '/');
    if ($host === '') $host = 'localhost';
    return $protocol . $host;
}

function orion_guess_root_url() {
    return rtrim(orion_resolve_site_origin() . orion_resolve_base_path(), '/');
}

function orion_render_installation_error($title, $message, $details = array()) {
    $root = orion_guess_root_url();
    $installer = $root . '/installer/';
    $reinstall = $installer . '?force=1';
    $admin = $root . '/orion-admin/';
    $home = $root . '/';

    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlspecialchars($title); ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <div class="max-w-3xl mx-auto p-6">
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-extrabold shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-rose-600"></span>
                    Orion
                </div>
                <h1 class="mt-5 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight"><?php echo htmlspecialchars($title); ?></h1>
                <p class="mt-3 text-slate-600 leading-relaxed"><?php echo htmlspecialchars($message); ?></p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-900/5 overflow-hidden">
                <div class="p-7 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Langkah Cepat</div>
                    <div class="mt-2 text-xl font-extrabold text-slate-900">Perbaiki instalasi</div>
                </div>
                <div class="p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?php echo htmlspecialchars($installer); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition">Buka Installer</a>
                        <a href="<?php echo htmlspecialchars($reinstall); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold transition">Reinstall (force=1)</a>
                        <a href="<?php echo htmlspecialchars($admin); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Coba Admin</a>
                        <a href="<?php echo htmlspecialchars($home); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Coba Home</a>
                    </div>

                    <?php if (!empty($details)): ?>
                        <details class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <summary class="cursor-pointer font-extrabold text-slate-900">Detail teknis</summary>
                            <div class="mt-3 space-y-2 text-sm text-slate-700">
                                <?php foreach ($details as $k => $v): ?>
                                    <div class="flex gap-2">
                                        <div class="w-32 text-slate-500 font-bold"><?php echo htmlspecialchars((string) $k); ?></div>
                                        <div class="flex-1 font-mono break-all"><?php echo htmlspecialchars((string) $v); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function orion_db_connect() {
    global $orion_db;
    $GLOBALS['orion_db_connect_error'] = '';

    $db_type = defined('DB_TYPE') ? (string) DB_TYPE : 'mysql';
    if (strtolower($db_type) === 'sqlite') {
        require_once ABSPATH . 'orion-includes/db-sqlite.php';
        $db_file = defined('DB_FILE') ? (string) DB_FILE : (ABSPATH . 'orion-content/database/orion.sqlite');
        if (strpos($db_file, 'ABSPATH') !== false) {
            $db_file = ABSPATH . 'orion-content/database/orion.sqlite';
        }
        $orion_db = new OrionSQLiteDB($db_file);
        if ($orion_db->connect_error) {
            $GLOBALS['orion_db_connect_error'] = (string) $orion_db->connect_error;
            return false;
        }
        return true;
    }
    
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
                 $GLOBALS['orion_db_connect_error'] = (string) $conn->connect_error;
                 return false;
             }
             // Create database
             if ($conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
                 $conn->close();
                 // Retry connection
                 $orion_db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
             } else {
                 $GLOBALS['orion_db_connect_error'] = (string) $conn->error;
                 return false;
             }
        }
        
        if ($orion_db->connect_error) {
            $GLOBALS['orion_db_connect_error'] = (string) $orion_db->connect_error;
            return false;
        }
    }
    return true;
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
    $escaped_option = $orion_db->real_escape_string($option);
    
    $result = $orion_db->query("SELECT option_value FROM $table WHERE option_name = '$escaped_option' LIMIT 1");
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
    
    $escaped_option = $orion_db->real_escape_string($option);
    $escaped_value = $orion_db->real_escape_string($value);
    
    if ($existing !== null) {
        // Update
        $sql = "UPDATE $table SET option_value = '$escaped_value' WHERE option_name = '$escaped_option'";
    } else {
        // Insert
        $sql = "INSERT INTO $table (option_name, option_value, autoload) VALUES ('$escaped_option', '$escaped_value', 'yes')";
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
    $escaped_option = $orion_db->real_escape_string($option);
    
    $orion_db->query("DELETE FROM $table WHERE option_name = '$escaped_option'");
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

    $base = orion_resolve_site_origin() . orion_resolve_base_path();


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

function orion_seo_current_url() {
    $root = function_exists('orion_guess_root_url') ? orion_guess_root_url() : rtrim(site_url(), '/');
    $uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
    return $root . $uri;
}

function orion_seo_canonical_url() {
    $url = orion_seo_current_url();
    $parts = parse_url($url);
    if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])) return $url;

    $path = isset($parts['path']) ? $parts['path'] : '/';
    $query = '';
    if (isset($parts['query']) && $parts['query'] !== '') {
        parse_str($parts['query'], $params);
        $drop = array('utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'fbclid', 'gclid');
        foreach ($drop as $k) {
            if (isset($params[$k])) unset($params[$k]);
        }
        if (!empty($params)) {
            ksort($params);
            $query = http_build_query($params);
        }
    }

    $out = $parts['scheme'] . '://' . $parts['host'];
    if (isset($parts['port'])) $out .= ':' . $parts['port'];
    $out .= $path;
    if ($query !== '') $out .= '?' . $query;
    return $out;
}

function orion_seo_text_excerpt($text, $limit) {
    $text = trim((string) $text);
    if ($text === '') return '';
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/', ' ', strip_tags($text));
    if ($limit <= 0 || strlen($text) <= $limit) return $text;
    $cut = substr($text, 0, $limit);
    $space = strrpos($cut, ' ');
    if ($space !== false && $space > 40) {
        $cut = substr($cut, 0, $space);
    }
    return rtrim($cut, " \t\n\r\0\x0B") . '...';
}

function orion_seo_meta_pack() {
    $site_title = (string) get_option('blogname', 'Orion CMS');
    $tagline = (string) get_option('blogdescription', '');
    $site_desc = (string) get_option('site_meta_description', '');
    $site_keys = (string) get_option('site_meta_keywords', '');
    if (trim($site_keys) === '') {
        $site_keys = 'website gratis, buat website gratis, bikin website gratis, cara membuat website gratis, website gratis tanpa coding, website builder, website builder gratis, builder website tanpa coding, cms gratis, free cms, fre cms, free website, web gratis, web gr4atis, web developer profesional, website developer pro, jasa pembuatan website, jasa bikin website, landing page builder, orion cms, orion cms free';
    }

    $canonical = orion_seo_canonical_url();
    $image = (string) get_option('site_logo', site_url('/assets/img/orion-light.png'));
    $type = 'website';
    $title = $site_title;
    $desc = $site_desc !== '' ? $site_desc : ($tagline !== '' ? $tagline : $site_title);

    if (function_exists('is_single') && is_single()) {
        global $post;
        if (is_object($post)) {
            $type = 'article';
            $title = trim((string) $post->post_title) !== '' ? ((string) $post->post_title . ' - ' . $site_title) : $site_title;
            $desc = orion_seo_text_excerpt($post->post_content, 160);
            $thumb = function_exists('get_the_post_thumbnail_url') ? get_the_post_thumbnail_url($post) : '';
            if (!$thumb) {
                $thumb = function_exists('get_first_image_from_content') ? get_first_image_from_content(html_entity_decode((string) $post->post_content, ENT_QUOTES, 'UTF-8')) : '';
            }
            if ($thumb) $image = $thumb;
        }
    } elseif (function_exists('is_page') && is_page()) {
        global $post;
        if (is_object($post)) {
            $type = 'website';
            $title = trim((string) $post->post_title) !== '' ? ((string) $post->post_title . ' - ' . $site_title) : $site_title;
            $desc = orion_seo_text_excerpt($post->post_content, 160);
            $thumb = function_exists('get_the_post_thumbnail_url') ? get_the_post_thumbnail_url($post) : '';
            if (!$thumb) {
                $thumb = function_exists('get_first_image_from_content') ? get_first_image_from_content(html_entity_decode((string) $post->post_content, ENT_QUOTES, 'UTF-8')) : '';
            }
            if ($thumb) $image = $thumb;
        }
    } else {
        if ($tagline !== '') {
            $title = $site_title . ' - ' . $tagline;
        }
    }

    return array(
        'title' => $title,
        'description' => $desc,
        'keywords' => $site_keys,
        'canonical' => $canonical,
        'image' => $image,
        'type' => $type,
        'site_name' => $site_title
    );
}

function orion_seo_render_meta_tags() {
    $p = orion_seo_meta_pack();
    $title = htmlspecialchars((string) $p['title'], ENT_QUOTES, 'UTF-8');
    $desc = htmlspecialchars((string) $p['description'], ENT_QUOTES, 'UTF-8');
    $keys = htmlspecialchars((string) $p['keywords'], ENT_QUOTES, 'UTF-8');
    $canon = htmlspecialchars((string) $p['canonical'], ENT_QUOTES, 'UTF-8');
    $img = htmlspecialchars((string) $p['image'], ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars((string) $p['type'], ENT_QUOTES, 'UTF-8');
    $site = htmlspecialchars((string) $p['site_name'], ENT_QUOTES, 'UTF-8');

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => ($type === 'article') ? 'Article' : 'WebSite',
        'name' => html_entity_decode((string) $p['title'], ENT_QUOTES, 'UTF-8'),
        'url' => (string) $p['canonical'],
        'description' => html_entity_decode((string) $p['description'], ENT_QUOTES, 'UTF-8')
    );
    if ($type === 'article') {
        global $post;
        if (is_object($post)) {
            $schema['headline'] = html_entity_decode((string) $post->post_title, ENT_QUOTES, 'UTF-8');
            $schema['datePublished'] = isset($post->post_date) ? (string) $post->post_date : '';
            $schema['dateModified'] = isset($post->post_modified) ? (string) $post->post_modified : '';
            $schema['mainEntityOfPage'] = (string) $p['canonical'];
            $schema['image'] = (string) $p['image'];
        }
    }

    $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $favicon = htmlspecialchars((string) site_url('/assets/img/favicon.png'), ENT_QUOTES, 'UTF-8');

    $out = "";
    $out .= "<meta name=\"orion-seo\" content=\"1\" />\n";
    $out .= "<meta name=\"generator\" content=\"Orion CMS\" />\n";
    $out .= "<link rel=\"icon\" type=\"image/png\" href=\"{$favicon}\" />\n";
    $out .= "<link rel=\"apple-touch-icon\" href=\"{$favicon}\" />\n";
    $out .= "<title>{$title}</title>\n";
    $out .= "<meta name=\"description\" content=\"{$desc}\" />\n";
    $out .= "<meta name=\"keywords\" content=\"{$keys}\" />\n";
    $out .= "<link rel=\"canonical\" href=\"{$canon}\" />\n";
    $out .= "<meta property=\"og:title\" content=\"{$title}\" />\n";
    $out .= "<meta property=\"og:description\" content=\"{$desc}\" />\n";
    $out .= "<meta property=\"og:image\" content=\"{$img}\" />\n";
    $out .= "<meta property=\"og:url\" content=\"{$canon}\" />\n";
    $out .= "<meta property=\"og:type\" content=\"{$type}\" />\n";
    $out .= "<meta property=\"og:site_name\" content=\"{$site}\" />\n";
    $out .= "<meta name=\"twitter:card\" content=\"summary_large_image\" />\n";
    $out .= "<meta name=\"twitter:title\" content=\"{$title}\" />\n";
    $out .= "<meta name=\"twitter:description\" content=\"{$desc}\" />\n";
    $out .= "<meta name=\"twitter:image\" content=\"{$img}\" />\n";
    if ($jsonld) {
        $out .= "<script type=\"application/ld+json\">{$jsonld}</script>\n";
    }
    return $out;
}

function orion_seo_inject_into_html_head($html) {
    if (!is_string($html) || $html === '') return $html;
    if (stripos($html, 'name="orion-seo"') !== false || stripos($html, "name='orion-seo'") !== false) return $html;
    $pos = stripos($html, '</head>');
    if ($pos === false) return $html;
    $meta = orion_seo_render_meta_tags();
    $head_end = substr($html, 0, $pos);
    $rest = substr($html, $pos);
    return $head_end . $meta . $rest;
}

function orion_seo_should_inject() {
    if (php_sapi_name() === 'cli') return false;
    $script = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
    if (strpos($script, '/orion-admin/') !== false) return false;
    if (strpos($script, '/installer/') !== false) return false;
    $base = strtolower(basename($script));
    if (in_array($base, array('sitemap.php', 'robots.php', 'download.php', 'ajax.php'), true)) return false;
    return true;
}

function orion_seo_start_output_buffer() {
    if (!orion_seo_should_inject()) return;
    if (!empty($GLOBALS['orion_seo_ob_started'])) return;
    $GLOBALS['orion_seo_ob_started'] = true;
    ob_start('orion_seo_inject_into_html_head');
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

/**
 * Helper nonce (anti-CSRF) — stateless HMAC berbasis waktu.
 * Tidak bergantung pada session, hanya user id + action + window waktu 2 jam.
 */
function orion_nonce_secret() {
    static $secret = null;
    if ($secret !== null) {
        return $secret;
    }

    $stored = function_exists('get_option') ? get_option('orion_secret_key') : '';
    if (!is_string($stored) || strlen($stored) < 32) {
        $stored = '';
    }

    // Fall back to a per-install secret file when the option table is not
    // writable yet (fresh install, DB offline). Without this every request
    // would mint a new secret and no nonce or auth cookie would survive.
    // The file returns the key from PHP so a direct HTTP request prints nothing.
    $secret_file = defined('ABSPATH') ? ABSPATH . 'orion-content/orion-secret.php' : '';

    if ($stored === '' && $secret_file !== '' && is_readable($secret_file)) {
        $file_secret = @include $secret_file;
        if (is_string($file_secret) && strlen($file_secret) >= 32) {
            $stored = $file_secret;
        }
    }

    if ($stored === '') {
        if (function_exists('random_bytes')) {
            $stored = bin2hex(random_bytes(32));
        } else {
            $stored = hash('sha256', uniqid('orion', true) . mt_rand());
        }

        if (function_exists('update_option')) {
            update_option('orion_secret_key', $stored);
        }

        if ($secret_file !== '' && is_dir(dirname($secret_file))) {
            @file_put_contents($secret_file, "<?php\n// Auto-generated install key. Do not edit.\nreturn '" . $stored . "';\n", LOCK_EX);
        }
    }

    $secret = $stored;
    return $secret;
}

function orion_create_nonce($action = -1) {
    $user_id = function_exists('get_current_user_id') ? (int) get_current_user_id() : 0;
    $tick = (int) ceil(time() / 7200); // jendela 2 jam
    $nonce = hash_hmac('sha256', (string) $action . '|' . $user_id . '|' . $tick, orion_nonce_secret());
    return substr($nonce, 0, 16);
}


/**
 * Extensions upload yang diizinkan. Skrip server tidak pernah termasuk,
 * karena file yang bisa dieksekusi di folder upload berarti remote code execution.
 */
function orion_allowed_upload_exts() {
    return array(
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'ico',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'csv', 'zip', 'rar', '7z',
        'mp3', 'wav', 'mp4', 'webm', 'ogg',
    );
}

/**
 * Membersihkan nama file upload.
 *
 * Mengembalikan nama file yang aman, atau false bila ekstensinya tidak
 * diizinkan.
 */
function orion_sanitize_upload_name($name, $allowed = null) {
    $name = basename((string) $name);
    $name = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
    $name = preg_replace('/\.{2,}/', '.', (string) $name);
    $name = ltrim((string) $name, '.');

    if ($name === '') {
        return false;
    }

    $ext = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
    if ($allowed === null) {
        $allowed = orion_allowed_upload_exts();
    }

    if ($ext === '' || !in_array($ext, (array) $allowed, true)) {
        return false;
    }

    return $name;
}

/**
 * Memastikan semua entri ZIP tetap berada di dalam folder tujuan.
 *
 * Tanpa ini, ZIP berisi entri "..\\..\\orion-config.php" bisa menulis file
 * di luar folder plugin/theme (zip-slip) dan mengambil alih instalasi.
 */
function orion_zip_entries_are_safe($zip) {
    if (!is_object($zip) || !isset($zip->numFiles)) {
        return false;
    }

    for ($i = 0; $i < (int) $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if ($name === false) {
            return false;
        }

        $name = str_replace('\\', '/', (string) $name);

        if ($name === '') {
            continue;
        }

        // Absolute path, drive letter, or any ".." segment.
        if ($name[0] === '/' || preg_match('#^[A-Za-z]:#', $name) || preg_match('#(^|/)\.\.(/|$)#', $name)) {
            return false;
        }
    }

    return true;
}
function orion_verify_nonce($nonce, $action = -1) {
    // A crafted ?orion_nonce[]= request would otherwise reach hash_equals()
    // with an array and raise a TypeError.
    $nonce = is_string($nonce) ? $nonce : '';

    if ($nonce === '') {
        return false;
    }
    // Terima nonce dari jendela waktu sekarang & sebelumnya (toleransi 2 jam)
    for ($i = 0; $i < 2; $i++) {
        $expected = substr(hash_hmac('sha256', (string) $action . '|' . (int) get_current_user_id() . '|' . ((int) ceil(time() / 7200) - $i), orion_nonce_secret()), 0, 16);
        if (hash_equals($expected, $nonce)) {
            return 1;
        }
    }
    return false;
}

/**
 * Alias kept for callers that use the WP-style helper name.
 */
function orion_generate_nonce($action = -1) {
    return orion_create_nonce($action);
}

/**
 * Emits a hidden nonce field (WP-style name by default).
 */
function orion_nonce_field($action = -1, $name = '_wpnonce') {
    echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr(orion_create_nonce($action)) . '" />';
}

/**
 * WordPress-style wrappers, backed by the same per-install secret so that
 * bundled plugin code never references an undefined constant.
 */
function wp_create_nonce($action = -1) {
    return orion_create_nonce($action);
}

function wp_verify_nonce($nonce, $action = -1) {
    return orion_verify_nonce($nonce, $action) ? 1 : false;
}
/**
 * Output hidden field nonce untuk form.
 */
function orion_field_nonce($action = -1) {
    echo '<input type="hidden" name="orion_nonce" value="' . esc_attr(orion_create_nonce($action)) . '" />';
}

/**
 * Simple Math Captcha Functions
 */
function orion_generate_captcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['orion_captcha_answer'] = $num1 + $num2;
    return array(
        'num1' => $num1,
        'num2' => $num2
    );
}

function orion_verify_captcha($user_input) {
    if (!isset($_SESSION['orion_captcha_answer'])) {
        return false;
    }
    $is_valid = (intval($user_input) === $_SESSION['orion_captcha_answer']);
    unset($_SESSION['orion_captcha_answer']); // Invalidate after use
    return $is_valid;
}
