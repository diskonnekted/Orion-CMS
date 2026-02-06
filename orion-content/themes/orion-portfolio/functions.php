<?php
/**
 * Orion Portfolio Theme Functions
 */

function orion_portfolio_setup() {
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => 'Primary Menu'
    ));
}
add_action('after_setup_theme', 'orion_portfolio_setup');
