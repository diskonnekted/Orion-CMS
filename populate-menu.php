<?php
define('ABSPATH', __DIR__ . '/');
require_once 'orion-load.php';

echo "=== Populating Menu ===\n";

if (!defined('OBJECT')) define('OBJECT', 'OBJECT');

function get_page_by_title_local($title) {
    global $orion_db, $table_prefix;
    $title = $orion_db->real_escape_string($title);
    $table = $table_prefix . 'posts';
    $res = $orion_db->query("SELECT * FROM $table WHERE post_title = '$title' AND post_type = 'page' LIMIT 1");
    if ($res && $res->num_rows > 0) return $res->fetch_object();
    return null;
}

// 1. Get or Create Menu
$menu_name = 'Main Menu';
$menu = get_term_by('name', $menu_name, 'nav_menu');
if (!$menu) {
    echo "Creating '$menu_name'...\n";
    $res = wp_insert_term($menu_name, 'nav_menu');
    if (is_wp_error($res)) {
        die("Error creating menu: " . $res->get_error_message() . "\n");
    }
    $menu_id = $res['term_id'];
} else {
    echo "Menu '$menu_name' exists (ID: " . $menu->term_id . ").\n";
    $menu_id = $menu->term_id;
}

// 2. Create Pages & Add to Menu
$pages_to_create = [
    'Beranda' => '',
    'Berita' => 'Berita Terkini',
    'Tentang' => 'Tentang Kami',
    'Kontak' => 'Hubungi Kami'
];

foreach ($pages_to_create as $title => $content) {
    // Check if page exists
    $page = get_page_by_title_local($title);
    if (!$page) {
        echo "Creating page '$title'...\n";
        $page_id = wp_insert_post([
            'post_title' => $title,
            'post_content' => $content ?: "Content for $title",
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
    } else {
        echo "Page '$title' exists (ID: " . $page->ID . ").\n";
        $page_id = $page->ID;
    }

    // Add to Menu
    // Check if item already exists in menu (simplified check)
    // We'll just add it blindly for this test script to ensure it's there
    echo "Adding '$title' to menu ID $menu_id...\n";
    
    $item_data = [
        'post_title' => $title,
        'post_status' => 'publish',
        'post_type' => 'nav_menu_item'
    ];
    
    $item_id = wp_insert_post($item_data);
    if ($item_id) {
        wp_set_object_terms($item_id, $menu_id, 'nav_menu');
        update_post_meta($item_id, '_menu_item_type', 'post_type');
        update_post_meta($item_id, '_menu_item_object_id', $page_id);
        update_post_meta($item_id, '_menu_item_object', 'page');
        update_post_meta($item_id, '_menu_item_url', get_permalink($page_id));
        echo " - Item added (ID: $item_id)\n";
    } else {
        echo " - Failed to add item.\n";
    }
}

// 3. Set Location
$locations = get_option('nav_menu_locations');
if (!is_array($locations)) $locations = [];
$locations['primary'] = $menu_id;
update_option('nav_menu_locations', $locations);
echo "Menu location 'primary' updated.\n";

echo "Done.\n";
