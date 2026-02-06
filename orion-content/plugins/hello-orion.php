<?php
/*
Plugin Name: Hello Orion
Description: This is a simple plugin to demonstrate the Orion CMS Hook system.
Version: 1.0
Author: Orion Team
*/

function hello_orion_footer_message() {
    echo '<div style="position: fixed; bottom: 10px; right: 10px; background: #2563eb; color: white; padding: 10px; border-radius: 5px; z-index: 9999; font-family: sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        Hello from Orion Plugin! 🚀
    </div>';
}

// Hook into the footer
add_action('wp_footer', 'hello_orion_footer_message');
