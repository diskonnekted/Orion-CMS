<?php
/**
 * WordPress Compatibility Layer for Orion CMS
 * 
 * This file provides mock functions and compatibility wrappers to allow
 * standard WordPress themes to function within Orion CMS.
 */

// Global version
$GLOBALS['wp_version'] = '6.0';

/**
 * Error Handling
 */
class WP_Error {
    public $errors = array();
    public $error_data = array();

    public function __construct($code = '', $message = '', $data = '') {
        if (empty($code)) {
            return;
        }
        $this->add($code, $message, $data);
    }

    public function add($code, $message, $data = '') {
        $this->errors[$code][] = $message;
        if (!empty($data)) {
            $this->error_data[$code] = $data;
        }
    }

    public function get_error_message($code = '') {
        if (empty($code)) {
            $code = $this->get_error_code();
        }
        $messages = $this->get_error_messages($code);
        if (empty($messages)) {
            return '';
        }
        return $messages[0];
    }

    public function get_error_messages($code = '') {
        // Simple implementation
        if (empty($code)) {
            $all_messages = array();
            foreach ($this->errors as $code => $messages) {
                $all_messages = array_merge($all_messages, $messages);
            }
            return $all_messages;
        }

        if (isset($this->errors[$code])) {
            return $this->errors[$code];
        }

        return array();
    }

    public function get_error_code() {
        $codes = array_keys($this->errors);
        if (empty($codes)) {
            return '';
        }
        return $codes[0];
    }
}

function is_wp_error($thing) {
    return ($thing instanceof WP_Error);
}

/**
 * Translation Functions
 */
function __($text, $domain = 'default') {
    return $text;
}

function _e($text, $domain = 'default') {
    echo $text;
}

function esc_html__($text, $domain = 'default') {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_html_e($text, $domain = 'default') {
    echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_attr__($text, $domain = 'default') {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_attr_e($text, $domain = 'default') {
    echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_html($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_attr($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_url($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); // Simplified
}

function esc_js($text) {
    return json_encode($text, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}

function _x($text, $context, $domain = 'default') {
    return $text;
}

function _n($single, $plural, $number, $domain = 'default') {
    return ($number === 1) ? $single : $plural;
}

/**
 * Theme Support & Features
 */
function add_theme_support($feature, $args = null) {
    // Stub
}

global $_wp_registered_nav_menus;
$_wp_registered_nav_menus = array();

function register_nav_menus($locations = array()) {
    global $_wp_registered_nav_menus;
    $_wp_registered_nav_menus = array_merge((array) $_wp_registered_nav_menus, $locations);
}

function get_registered_nav_menus() {
    global $_wp_registered_nav_menus;
    return $_wp_registered_nav_menus;
}

function has_nav_menu($location) {
    $locations = get_option('nav_menu_locations');
    return ( isset($locations[$location]) && $locations[$location] > 0 );
}

function wp_nav_menu($args = array()) {
    $defaults = array(
        'menu'            => '',
        'container'       => 'div',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => false, // We'll just skip if empty
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'item_spacing'    => 'preserve',
        'depth'           => 0,
        'walker'          => '',
        'theme_location'  => '',
    );
    
    $args = array_merge($defaults, $args);
    
    $menu_id = 0;
    
    // 1. Get menu by location
    if ($args['theme_location']) {
        $locations = get_option('nav_menu_locations');
        if (isset($locations[$args['theme_location']])) {
            $menu_id = $locations[$args['theme_location']];
        }
    }
    
    // 2. If no location or not found, try 'menu' arg (ID or slug or name)
    if (!$menu_id && $args['menu']) {
        // ... (simplified: assume it's ID for now if integer, or slug)
        // For now, let's rely on location.
    }
    
    if (!$menu_id) {
        return; // Nothing to show
    }
    
    // 3. Get menu items
    // Custom query to get nav_menu_items for this term
    global $orion_db, $table_prefix;
    $sql = "SELECT p.* FROM {$table_prefix}posts p 
            INNER JOIN {$table_prefix}term_relationships tr ON p.ID = tr.object_id
            INNER JOIN {$table_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.term_id = $menu_id AND p.post_type = 'nav_menu_item' AND p.post_status = 'publish'
            ORDER BY p.ID ASC"; // Should be menu_order
            
    $result = $orion_db->query($sql);
    $menu_items = array();
    if ($result) {
        while ($row = $result->fetch_object()) {
            $row->url = get_post_meta($row->ID, '_menu_item_url', true);
            $menu_items[] = $row;
        }
    }
    
    if (empty($menu_items)) return;
    
    // 4. Build Output
    $nav_menu = '';
    $nav_menu .= $args['items_wrap'];
    
    $items_html = '';
    foreach ($menu_items as $item) {
        $url = $item->url;
        $title = $item->post_title;
        $classes = array('menu-item'); // Simplified
        $class_names = join(' ', $classes);
        
        $link_class = isset($args['link_class']) ? ' class="' . $args['link_class'] . '"' : '';

        $items_html .= '<li class="' . $class_names . '">';
        $items_html .= '<a href="' . $url . '"' . $link_class . '>';
        $items_html .= $args['link_before'] . $title . $args['link_after'];
        $items_html .= '</a>';
        $items_html .= '</li>';
    }
    
    $nav_menu = sprintf($nav_menu, $args['menu_id'], $args['menu_class'], $items_html);
    
    if ($args['container']) {
        $nav_menu = '<' . $args['container'] . ' class="' . $args['container_class'] . '">' . $nav_menu . '</' . $args['container'] . '>';
    }
    
    if ($args['echo']) {
        echo $nav_menu;
    } else {
        return $nav_menu;
    }
}

function get_nav_menu_locations() {
    $locations = get_option('nav_menu_locations');
    if (empty($locations) || !is_array($locations)) {
        return array();
    }
    return $locations;
}

function wp_get_nav_menu_items($menu) {
    // $menu can be object, slug, or ID
    $menu_id = 0;
    if (is_object($menu)) {
        $menu_id = $menu->term_id;
    } elseif (is_numeric($menu)) {
        $menu_id = $menu;
    } else {
        $term = get_term_by('slug', $menu, 'nav_menu');
        if ($term) {
            $menu_id = $term->term_id;
        } else {
             $term = get_term_by('name', $menu, 'nav_menu');
             if ($term) $menu_id = $term->term_id;
        }
    }

    if (!$menu_id) return false;

    global $orion_db, $table_prefix;
    $sql = "SELECT p.* FROM {$table_prefix}posts p 
            INNER JOIN {$table_prefix}term_relationships tr ON p.ID = tr.object_id
            INNER JOIN {$table_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.term_id = $menu_id AND p.post_type = 'nav_menu_item' AND p.post_status = 'publish'
            ORDER BY p.ID ASC"; 
            
    $result = $orion_db->query($sql);
    $items = array();
    if ($result) {
        while ($row = $result->fetch_object()) {
            $row->url = get_post_meta($row->ID, '_menu_item_url', true);
            $row->title = $row->post_title; // WP compatibility
            $items[] = $row;
        }
    }
    return $items;
}

function get_theme_mod($name, $default = false) {
    return $default;
}

function set_post_thumbnail_size($width, $height, $crop = false) {
    // Stub
}

/**
 * Template Tags
 */
function get_template_part($slug, $name = null) {
    $templates = array();
    $name = (string) $name;
    if ( '' !== $name ) {
        $templates[] = "{$slug}-{$name}.php";
    }
    $templates[] = "{$slug}.php";

    locate_template($templates, true, false);
}

function is_home() {
    global $wp_query;
    // If showing posts on front
    if (get_option('show_on_front') == 'posts' || !get_option('show_on_front')) {
        return is_front_page();
    }
    // If static page is front, is_home is false (unless we have a posts page set, not supported yet)
    return false;
}

function is_front_page() {
    global $wp_query;
    $show_on_front = get_option('show_on_front');
    $page_on_front = get_option('page_on_front');

    if ($show_on_front == 'page' && $page_on_front) {
        if (isset($wp_query->query_vars['page_id']) && $wp_query->query_vars['page_id'] == $page_on_front) {
            return true;
        }
    } else {
        // Default: posts on front
        if (empty($wp_query->query_vars)) {
            return true;
        }
    }
    return false;
}

function is_single() {
    global $wp_query;
    return isset($wp_query->query_vars['p']);
}

function is_page() {
    global $wp_query;
    return isset($wp_query->query_vars['page_id']) || isset($wp_query->query_vars['pagename']);
}

/**
 * Utility Functions
 */
function wp_parse_args($args, $defaults = '') {
    if (is_object($args)) {
        $r = get_object_vars($args);
    } elseif (is_array($args)) {
        $r = &$args;
    } else {
        wp_parse_str($args, $r);
    }

    if (is_array($defaults)) {
        return array_merge($defaults, $r);
    }
    return $r;
}

function wp_parse_str($string, &$array) {
    parse_str($string, $array);
    return $array;
}

function stripslashes_deep($value) {
    if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = stripslashes_deep($data);
        }
    } elseif (is_string($value)) {
        $value = stripslashes($value);
    }
    return $value;
}

/**
 * Category Functions
 */
function get_categories($args = '') {
    $defaults = array('taxonomy' => 'category');
    $args = wp_parse_args($args, $defaults);
    return get_terms('category', $args);
}

function get_category_link($category) {
    if (is_object($category)) {
        $category = $category->term_id;
    }
    return home_url("?cat=" . $category);
}

function home_url($path = '') {
    return site_url($path);
}

function is_archive() {
    return false; // Stub
}

function is_search() {
    return isset($_GET['s']);
}

function single_post_title($prefix = '', $display = true) {
    global $post;
    $title = '';
    if ( is_object($post) ) {
        $title = $post->post_title;
    }
    
    if ($display) {
        echo $prefix . $title;
    }
    return $title;
}

function wp_body_open() {
    do_action('wp_body_open');
}

/**
 * Enqueue Scripts & Styles
 */
function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {
    if ($src) {
        // Handle relative paths
        if (strpos($src, 'http') === false && strpos($src, '//') === false) {
             // Do nothing for now
        }
        echo "<link rel='stylesheet' id='$handle' href='$src' type='text/css' media='$media' />\n";
    }
}

function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {
    if ($src) {
        echo "<script src='$src'></script>\n";
    }
}

function wp_register_style($handle, $src, $deps = array(), $ver = false, $media = 'all') {
    // Stub
}

function wp_register_script($handle, $src, $deps = array(), $ver = false, $in_footer = false) {
    // Stub
}

function wp_style_add_data($handle, $key, $value) {
    // Stub
}

function wp_localize_script($handle, $name, $data) {
    // Stub
}

function get_stylesheet_directory_uri() {
    return get_template_directory_uri();
}

function get_stylesheet_directory() {
    return get_template_directory();
}

function get_theme_file_uri($file = '') {
    $file = ltrim($file, '/');
    return get_stylesheet_directory_uri() . '/' . $file;
}

function get_theme_file_path($file = '') {
    $file = ltrim($file, '/');
    return get_stylesheet_directory() . '/' . $file;
}

/**
 * Formatting
 */
function wp_kses_post($data) {
    return $data; // SECURITY RISK: Bypass for now
}

function wp_kses($data, $allowed_html) {
    return $data;
}

/**
 * Misc
 */
/*
function body_class($class = '') {
    // Already defined in functions.php
}
*/

function language_attributes($doctype = 'html') {
    echo 'lang="en-US"';
}

function bloginfo($show = '') {
    switch ($show) {
        case 'charset':
            echo 'UTF-8';
            break;
        case 'name':
            echo 'Orion CMS';
            break;
        case 'description':
            echo 'Just another Orion site';
            break;
        case 'url':
            echo site_url();
            break;
        case 'template_url':
        case 'template_directory':
            echo get_template_directory_uri();
            break;
        case 'stylesheet_url':
            echo get_stylesheet_uri();
            break;
        default:
            echo '';
    }
}

function get_stylesheet_uri() {
    return get_template_directory_uri() . '/style.css';
}

function get_bloginfo($show = '', $filter = 'raw') {
    ob_start();
    bloginfo($show);
    return ob_get_clean();
}

function wp_title($sep = '&raquo;', $display = true, $seplocation = '') {
    $title = "Home";
    if ($display) {
        echo $title;
    }
    return $title;
}

/**
 * Posts
 */
function post_class($class = '', $post_id = null) {
    echo 'class="post ' . (is_array($class) ? implode(' ', $class) : $class) . '"';
}

function the_ID() {
    global $post;
    echo isset($post->ID) ? $post->ID : 0;
}

function get_the_ID() {
    global $post;
    return isset($post->ID) ? $post->ID : 0;
}

function get_the_date($format = 'd M Y', $post_id = 0) {
    $post_obj = null;
    
    if (empty($post_id)) {
        global $post;
        $post_obj = $post;
    } elseif (is_numeric($post_id)) {
        $post_obj = get_post($post_id);
    } else {
        $post_obj = $post_id;
    }
    
    if ( ! $post_obj || ! isset($post_obj->post_date) ) {
        return '';
    }
    return date($format, strtotime($post_obj->post_date));
}

function the_date($format = 'd M Y', $before = '', $after = '', $echo = true) {
    $date = get_the_date($format);
    if ($echo) {
        echo $before . $date . $after;
    }
    return $date;
}

function get_the_author() {
    global $post;
    if (empty($post->post_author)) {
        return '';
    }
    $user = get_user_by('id', $post->post_author);
    return $user ? $user->display_name : '';
}

function the_author() {
    echo get_the_author();
}

function get_the_title($post = 0) {
    $post_obj = null;
    if (empty($post)) {
        global $post;
        $post_obj = $post;
    } elseif (is_numeric($post)) {
        $post_obj = get_post($post);
    } else {
        $post_obj = $post;
    }
    
    $title = isset($post_obj->post_title) ? $post_obj->post_title : '';
    return html_entity_decode($title, ENT_QUOTES, 'UTF-8');
}

function the_title($before = '', $after = '', $echo = true) {
    $title = get_the_title();
    if ($echo) {
        echo $before . $title . $after;
    }
    return $before . $title . $after;
}

function get_the_content($more_link_text = null, $strip_teaser = false, $post_id = 0) {
    $post_obj = null;
    if (empty($post_id)) {
        global $post;
        $post_obj = $post;
    } elseif (is_numeric($post_id)) {
        $post_obj = get_post($post_id);
    } else {
        $post_obj = $post_id;
    }

    $content = isset($post_obj->post_content) ? $post_obj->post_content : '';
    if ($content !== '') {
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    }
    if (function_exists('apply_filters')) {
        $content = apply_filters('the_content', $content);
    }
    return $content;
}

function the_content($more_link_text = null, $strip_teaser = false) {
    echo get_the_content($more_link_text, $strip_teaser);
}

function the_excerpt() {
    global $post;
    echo isset($post->post_content) ? substr(strip_tags(html_entity_decode($post->post_content)), 0, 150) . '...' : '';
}

function the_permalink() {
    echo get_permalink();
}

function get_permalink($post = 0, $leavename = false) {
    $post_id = 0;
    
    if ($post === 0) {
        global $post;
        $post_obj = $post;
    } elseif (is_object($post)) {
        $post_obj = $post;
    } else {
        $post_obj = get_post($post);
    }
    
    if (is_object($post_obj)) {
        $post_id = $post_obj->ID;
        if (isset($post_obj->post_type) && $post_obj->post_type == 'page') {
            return site_url('/?page_id=' . $post_id);
        }
    } else {
        // Fallback if passing ID directly and get_post not available or fails
        if (is_numeric($post) && $post > 0) {
            $post_id = $post;
        }
    }
    
    return site_url('/?p=' . $post_id);
}

function get_the_permalink($post = 0) {
    return get_permalink($post);
}

function has_post_thumbnail($post = null) {
    return false; // Stub
}

function the_post_thumbnail($size = 'post-thumbnail', $attr = '') {
    // Stub
}

function comments_open($post_id = null) {
    return false;
}

function get_post_format($post = null) {
    return false;
}

function is_sticky($post_id = null) {
    return false;
}

function wp_reset_postdata() {
    global $wp_query, $post;
    if ( isset( $wp_query ) && ! empty( $wp_query->post ) ) {
        $post = $wp_query->post;
        setup_postdata( $post );
    }
}

function wp_link_pages($args = '') {
    // Stub
}

function edit_post_link($text = null, $before = '', $after = '', $id = 0, $class = 'post-edit-link') {
    // Stub
}

/**
 * Sanitization
 */
function sanitize_text_field($str) {
    return trim(strip_tags($str));
}

function wp_strip_all_tags($string, $remove_breaks = false) {
    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
    $string = strip_tags($string);
    if ( $remove_breaks ) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }
    return trim( $string );
}

function wp_trim_words( $text, $num_words = 55, $more = null ) {
    if ( null === $more ) {
        $more = '&hellip;';
    }
    $text = wp_strip_all_tags( $text );
    $words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
    if ( count( $words_array ) > $num_words ) {
        array_pop( $words_array );
        $text = implode( ' ', $words_array );
        $text = $text . $more;
    } else {
        $text = implode( ' ', $words_array );
    }
    return $text;
}

function sanitize_title($title) {
    return strtolower(str_replace(' ', '-', $title));
}

function sanitize_html_class($class) {
    return $class;
}

function absint($maybeint) {
    return abs(intval($maybeint));
}

function wptexturize($text) {
    return $text;
}

function is_admin() {
    // Check if URL contains orion-admin
    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'orion-admin') !== false) {
        return true;
    }
    return false;
}

function is_rtl() {
    return false;
}

/**
 * Shortcode API (Mock Implementation)
 */
global $shortcode_tags;
$shortcode_tags = array();

function add_shortcode($tag, $callback) {
    global $shortcode_tags;
    if ( '' === trim( $tag ) ) {
        // Warning?
        return;
    }
    $shortcode_tags[ $tag ] = $callback;
}

function remove_shortcode($tag) {
    global $shortcode_tags;
    unset($shortcode_tags[$tag]);
}

function remove_all_shortcodes() {
    global $shortcode_tags;
    $shortcode_tags = array();
}

function shortcode_exists( $tag ) {
    global $shortcode_tags;
    return array_key_exists( $tag, $shortcode_tags );
}

function has_shortcode( $content, $tag ) {
    if ( false === strpos( $content, '[' ) ) {
        return false;
    }

    if ( shortcode_exists( $tag ) ) {
        preg_match_all( '/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER );
        if ( empty( $matches ) )
            return false;

        foreach ( $matches as $shortcode ) {
            if ( $tag === $shortcode[2] ) {
                return true;
            } elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
                return true;
            }
        }
    }
    return false;
}

function do_shortcode($content, $ignore_html = false) {
    global $shortcode_tags;

    if (false === strpos($content, '[')) {
        return $content;
    }

    if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
        return $content;
    }

    // Find all registered tag names in $content.
    preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
    $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

    if ( empty( $tagnames ) ) {
        return $content;
    }

    $content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

    $pattern = get_shortcode_regex( $tagnames );
    $content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

    // Always unescape line breaks in shortcodes.
    $content = unescape_invalid_shortcodes( $content );

    return $content;
}

function get_shortcode_regex( $tagnames = null ) {
    global $shortcode_tags;

    if ( empty( $tagnames ) ) {
        $tagnames = array_keys( $shortcode_tags );
    }
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

    // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
    // Also, see shortcode_unautop() and shortcode.js.
    return
          '\\['                              // Opening bracket
        . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
        . "($tagregexp)"                     // 2: Shortcode name
        . '(?![\\w-])'                       // Not followed by word character or hyphen
        . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
        .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
        .     '(?:'
        .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
        .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
        .     ')*?'
        . ')'
        . '(?:'
        .     '(\\/)'                        // 4: Self closing tag ...
        .     '\\]'                          // ... and closing bracket
        . '|'
        .     '\\]'                          // Closing bracket
        .     '(?:'
        .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
        .             '[^\\[]*+'             // Not an opening bracket
        .             '(?:'
        .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
        .                 '[^\\[]*+'         // Not an opening bracket
        .             ')*+'
        .         ')'
        .         '\\[\\/\\2\\]'             // Closing shortcode tag
        .     ')?'
        . ')'
        . '(\\]?)';                          // 6: Optional second closing bracket for escaping shortcodes: [[tag]]
}

function do_shortcode_tag( $m ) {
    global $shortcode_tags;

    // Allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }

    $tag = $m[2];
    $attr = shortcode_parse_atts( $m[3] );

    if ( ! is_callable( $shortcode_tags[ $tag ] ) ) {
        return $m[0];
    }

    $content = isset( $m[5] ) ? $m[5] : null;

    $output = $m[1] . call_user_func( $shortcode_tags[ $tag ], $attr, $content, $tag ) . $m[6];

    return $output;
}

function shortcode_parse_atts( $text ) {
    $atts = array();
    $pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    $text = preg_replace("/[\x{00a0}\x{000b}]+/u", " ", $text);
    if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
        foreach ($match as $m) {
            if (!empty($m[1]))
                $atts[strtolower($m[1])] = stripcslashes($m[2]);
            elseif (!empty($m[3]))
                $atts[strtolower($m[3])] = stripcslashes($m[4]);
            elseif (!empty($m[5]))
                $atts[strtolower($m[5])] = stripcslashes($m[6]);
            elseif (isset($m[7]) && strlen($m[7]))
                $atts[] = stripcslashes($m[7]);
            elseif (isset($m[8]))
                $atts[] = stripcslashes($m[8]);
        }

        // Reject any unclosed HTML elements
        foreach( $atts as &$value ) {
            if ( false !== strpos( $value, '<' ) ) {
                if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                    $value = '';
                }
            }
        }
    } else {
        $atts = ltrim($text);
    }
    return $atts;
}

function shortcode_atts( $pairs, $atts, $shortcode = '' ) {
    $atts = (array)$atts;
    $out = array();
    foreach ($pairs as $name => $default) {
        if ( array_key_exists($name, $atts) )
            $out[$name] = $atts[$name];
        else
            $out[$name] = $default;
    }
    
    if ( function_exists( 'apply_filters' ) ) {
        $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
    }
    
    return $out;
}

function strip_shortcodes( $content ) {
    global $shortcode_tags;

    if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
        return $content;
    }

    $pattern = get_shortcode_regex();

    return preg_replace_callback( "/$pattern/", 'strip_shortcode_tag', $content );
}

function strip_shortcode_tag( $m ) {
    // Allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }
    return $m[1] . $m[6];
}

function do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames ) {
    // Stub
    return $content;
}

function unescape_invalid_shortcodes( $content ) {
    return $content; // Stub
}

// Hook do_shortcode to the_content
if (function_exists('add_filter')) {
    add_filter('the_content', 'do_shortcode', 11); // Priority 11 to run after wpautop if it existed
}

if ( ! function_exists( 'trailingslashit' ) ) {
function trailingslashit( $string ) {
return rtrim( $string, '/\\' ) . '/';
}
}
if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return trailingslashit( str_replace( '\\', '/', dirname( $file ) ) );
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		$path    = plugin_dir_path( $file );
		$plugins = '';
		if ( defined( 'ABSPATH' ) ) {
			$plugins = trailingslashit( ABSPATH . 'orion-content/plugins' );
		}
		if ( $plugins !== '' && strpos( $path, $plugins ) === 0 ) {
			$url = site_url( '/orion-content/plugins/' . substr( $path, strlen( $plugins ) ) );
		} else {
			$base = rtrim( str_replace( '\\', '/', dirname( dirname( dirname( dirname( $file ) ) ) ) ), '/' );
			$url  = str_replace( $base, site_url(), $path );
		}
		return $url;
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	function plugin_basename( $file ) {
		$file    = str_replace( '\\', '/', $file );
		$plugins = '';
		if ( defined( 'ABSPATH' ) ) {
			$plugins = trim( str_replace( '\\', '/', trailingslashit( ABSPATH . 'orion-content/plugins' ) ), '/' );
		}
		if ( $plugins !== '' ) {
			$pos = strpos( $file, $plugins . '/' );
			if ( $pos !== false ) {
				return trim( substr( $file, $pos + strlen( $plugins ) + 1 ), '/' );
			}
		}
		if ( preg_match( '#([^/]+/[^/]+\.php)$#', $file, $m ) ) {
			return $m[1];
		}
		return basename( $file );
	}
}

if ( ! function_exists( 'plugins_url' ) ) {
	function plugins_url( $path = '', $plugin = '' ) {
		if ( $plugin !== '' ) {
			$url = plugin_dir_url( $plugin );
			if ( $path !== '' ) {
				return $url . ltrim( $path, '/' );
			}
			return rtrim( $url, '/' );
		}
		$base = '';
		if ( defined( 'ABSPATH' ) ) {
			$base = site_url( '/orion-content/plugins' );
		}
		if ( $path !== '' ) {
			return $base . '/' . ltrim( $path, '/' );
		}
		return $base;
	}
}
