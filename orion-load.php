<?php
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the orion-config.php file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

if ( file_exists( ABSPATH . 'orion-config.php') ) {
	require_once( ABSPATH . 'orion-config.php' );
} else {
    if (!defined('ORION_INSTALLER') && file_exists(ABSPATH . 'installer/index.php')) {
        $script = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
        $strip = array('/orion-admin', '/installer');
        foreach ($strip as $needle) {
            $len = strlen($needle);
            if (substr($dir, -$len) === $needle) {
                $dir = substr($dir, 0, -$len);
            } elseif (($pos = strpos($dir, $needle . '/')) !== false) {
                $dir = substr($dir, 0, $pos);
            }
        }
        $installer_path = ($dir === '' ? '' : $dir) . '/installer/';
        header('Location: ' . $installer_path);
        exit;
    }
    die('orion-config.php missing.');
}

// Load Security Module
if ( file_exists( ABSPATH . 'orion-includes/security.php' ) ) {
    require_once( ABSPATH . 'orion-includes/security.php' );
}
