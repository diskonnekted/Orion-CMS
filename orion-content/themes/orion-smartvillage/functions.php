<?php
/**
 * Orion SmartVillage Theme Functions
 */

function orion_smartvillage_setup() {
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => 'Main Menu',
        'footer'  => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_smartvillage_setup');
