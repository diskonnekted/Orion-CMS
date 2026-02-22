<?php
/*
Plugin Name: Orion Classic Editor
Description: Tambahan tombol untuk menyisipkan gambar dari Media ke tengah konten.
Version: 1.0
Author: Orion Dev
*/

if (!defined('ABSPATH')) {
    exit;
}

function orion_classic_editor_admin_head() {
    return;
}

add_action('orion_admin_head', 'orion_classic_editor_admin_head');
