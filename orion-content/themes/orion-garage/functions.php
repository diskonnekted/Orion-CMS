<?php

function orion_garage_setup() {
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_garage_setup');

function orion_garage_get_page_id_by_title($title) {
    global $orion_db, $table_prefix;
    $title_safe = $orion_db->real_escape_string($title);
    $table = $table_prefix . 'posts';
    $sql = "SELECT ID FROM $table WHERE post_title = '$title_safe' AND post_type = 'page' AND post_status = 'publish' LIMIT 1";
    $res = $orion_db->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_object();
        return (int) $row->ID;
    }
    return 0;
}

function orion_garage_is_shop_manager_active() {
    $plugins = get_option('active_plugins', array());
    if (!is_array($plugins)) {
        return false;
    }
    return in_array('orion-shop-manager/orion-shop-manager.php', $plugins, true);
}
