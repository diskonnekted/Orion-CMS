<?php
/**
 * Orion Wall Theme Functions
 */

function orion_wall_setup() {
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_wall_setup');

/**
 * Visitor Tracking System (Copied from Orion One)
 */
function orion_wall_track_visitor() {
    global $orion_db, $table_prefix;
    
    // Ensure table exists (Lazy check)
    $table_name = $table_prefix . 'visitor_log';
    
    // Auto-create table if not exists to prevent errors
    $sql_create = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        ip_address varchar(50) NOT NULL,
        visit_date datetime DEFAULT CURRENT_TIMESTAMP,
        user_agent varchar(255) DEFAULT NULL,
        PRIMARY KEY (id),
        KEY visit_date (visit_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $orion_db->query($sql_create);
    
    if (!isset($_COOKIE['orion_visitor_tracked'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $orion_db->real_escape_string($_SERVER['HTTP_USER_AGENT']) : '';
        
        $sql = "INSERT INTO $table_name (ip_address, user_agent) VALUES ('$ip', '$ua')";
        // Suppress error if table doesn't exist (or handle it)
        $orion_db->query($sql);
        
        // Set cookie for 1 hour to prevent spamming stats
        setcookie('orion_visitor_tracked', '1', time() + 3600, '/');
    }
}
add_action('wp_head', 'orion_wall_track_visitor');
