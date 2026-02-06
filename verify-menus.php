<?php
// Verify Menu System Integrity
define('ABSPATH', __DIR__ . '/');
require_once 'orion-load.php';

echo "=== Menu System Verification ===\n";

// 1. Check Menu Term
$menus = get_terms('nav_menu', ['hide_empty' => false]);

echo "=== Pages ===\n";
$pages = get_posts(['post_type' => 'page', 'numberposts' => -1]);
if ($pages) {
    foreach ($pages as $p) {
        echo " - " . $p->post_title . " (ID: " . $p->ID . ")\n";
    }
} else {
    echo "[WARN] No pages found.\n";
}

if (empty($menus)) {
    echo "[FAIL] No menus found.\n";
} else {
    echo "[PASS] Menus found: " . count($menus) . "\n";
    foreach ($menus as $menu) {
        echo " - " . $menu->name . " (ID: " . $menu->term_id . ")\n";
    }
}

// 2. Check Menu Locations
$locations = get_nav_menu_locations();
echo "\n=== Menu Locations ===\n";
if (empty($locations)) {
    echo "[WARN] No menu locations set.\n";
} else {
    foreach ($locations as $loc => $term_id) {
        echo "[PASS] Location '$loc' assigned to Menu ID $term_id\n";
    }
}

// 3. Check Menu Items
$locations = get_nav_menu_locations();
$menu_id = isset($locations['primary']) ? $locations['primary'] : 0;

if ($menu_id) {
    echo "\n=== Items in Primary Menu (ID $menu_id) ===\n";
    $items = wp_get_nav_menu_items($menu_id);
    if ($items) {
        foreach ($items as $item) {
            echo " - " . $item->title . " (URL: " . $item->url . ")\n";
        }
    } else {
        echo "[WARN] No items in menu ID $menu_id.\n";
        
        // Debug
        global $orion_db, $table_prefix;
        $tr = $table_prefix . 'term_relationships';
        $tt = $table_prefix . 'term_taxonomy';
        $sql = "SELECT tr.*, tt.term_id FROM $tr tr JOIN $tt tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id = $menu_id";
        $res = $orion_db->query($sql);
        echo "Debug: Rows in TR for term_id $menu_id: " . ($res ? $res->num_rows : 'Error') . "\n";
        
        $sql2 = "SELECT * FROM $tt WHERE term_id = $menu_id";
        $res2 = $orion_db->query($sql2);
        if ($res2 && $row = $res2->fetch_object()) {
            echo "Debug: TT entry for ID $menu_id: taxonomy=" . $row->taxonomy . ", tt_id=" . $row->term_taxonomy_id . "\n";
        } else {
            echo "Debug: No TT entry for ID $menu_id\n";
        }
    }
} else {
    echo "[WARN] No primary menu assigned.\n";
}

// 4. Test Rendering
echo "\n=== Rendering Test (Primary) ===\n";
ob_start();
wp_nav_menu(['theme_location' => 'primary', 'fallback_cb' => false]);
$output = ob_get_clean();

if (!empty($output)) {
    echo "[PASS] HTML generated (" . strlen($output) . " bytes)\n";
    echo substr($output, 0, 150) . "...\n";
} else {
    echo "[FAIL] No HTML generated for primary menu.\n";
}
