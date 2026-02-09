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
    // Error handling if config missing
    die("orion-config.php missing.");
}

// Load Security Module
if ( file_exists( ABSPATH . 'orion-includes/security.php' ) ) {
    require_once( ABSPATH . 'orion-includes/security.php' );
}
