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
    
    // Simple check if table exists to avoid overhead, or just try insert and catch error (not efficient)
    // For this environment, we'll assume setup_stats.php ran or we create it here if needed.
    // Better: Check once per session or use option to flag installed.
    
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
    $check = $orion_db->query("SHOW TABLES LIKE '$table_name'");
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
