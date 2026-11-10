<?php
/**
 * Orion One Theme Functions
 */

function orion_one_setup() {
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_one_setup');

/**
 * Visitor Tracking System
 */
function orion_one_track_visitor() {
    global $orion_db, $table_prefix;
    
    // Ensure table exists (Lazy check)
    $table_name = $table_prefix . 'visitor_log';
    
    // Auto-create table if not exists to prevent errors
    $db_type = defined('DB_TYPE') ? strtolower((string) DB_TYPE) : 'mysql';
    if ($db_type === 'sqlite') {
        $orion_db->query("CREATE TABLE IF NOT EXISTS $table_name (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ip_address TEXT NOT NULL,
            visit_date TEXT DEFAULT CURRENT_TIMESTAMP,
            user_agent TEXT DEFAULT NULL
        )");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$table_name}_visit_date ON $table_name(visit_date)");
    } else {
        $sql_create = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ip_address varchar(50) NOT NULL,
            visit_date datetime DEFAULT CURRENT_TIMESTAMP,
            user_agent varchar(255) DEFAULT NULL,
            PRIMARY KEY (id),
            KEY visit_date (visit_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $orion_db->query($sql_create);
    }
    
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
add_action('wp_head', 'orion_one_track_visitor');

/**
 * Get Visitor Stats
 */
function orion_one_get_stats() {
    global $orion_db, $table_prefix;
    $table_name = $table_prefix . 'visitor_log';
    
    $stats = array('today' => 0, 'total' => 0);
    
    // Check if table exists first
    $db_type = defined('DB_TYPE') ? strtolower((string) DB_TYPE) : 'mysql';
    if ($db_type === 'sqlite') {
        $check = $orion_db->query("SELECT name FROM sqlite_master WHERE type='table' AND name = '$table_name' LIMIT 1");
    } else {
        $check = $orion_db->query("SHOW TABLES LIKE '$table_name'");
    }
    if ($check && $check->num_rows > 0) {
        // Total
        $res_total = $orion_db->query("SELECT COUNT(*) as count FROM $table_name");
        if ($res_total && $row = $res_total->fetch_object()) {
            $stats['total'] = $row->count;
        }
        
        // Today
        $today = date('Y-m-d');
        $res_today = $orion_db->query("SELECT COUNT(*) as count FROM $table_name WHERE DATE(visit_date) = '$today'");
        if ($res_today && $row = $res_today->fetch_object()) {
            $stats['today'] = $row->count;
        }
    }
    
    return $stats;
}
