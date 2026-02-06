<?php
/**
 * Profile Administration Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

$user = wp_get_current_user();
if ( $user->ID ) {
    header( 'Location: user-new.php?user_id=' . $user->ID );
    exit;
} else {
    header( 'Location: ../login.php' );
    exit;
}
