<?php
/**
 * Front to the Orion CMS. This file doesn't do anything, but loads
 * orion-blog-header.php which does and tells Orion to load the theme.
 *
 * @package Orion
 */

/**
 * Tells Orion to load the Orion theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the Orion Environment and Template */
require( dirname( __FILE__ ) . '/orion-load.php' );

if ( WP_USE_THEMES ) {
    $template = get_template_directory() . '/index.php';
    if ( file_exists( $template ) ) {
        require_once( $template );
    } else {
        echo "Theme not found or index.php missing.";
    }
}
