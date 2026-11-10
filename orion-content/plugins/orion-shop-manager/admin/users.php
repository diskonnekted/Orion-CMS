<?php
/**
 * Halaman User plugin Orion Shop Manager.
 *
 * Plugin ini tidak menyediakan halaman manajemen user sendiri.
 * Manajemen user sepenuhnya ditangani oleh CMS inti (orion-admin).
 * Halaman ini hanya meneruskan ke sistem user inti milik CMS.
 */
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/orion-admin/'));
    exit;
}

header('Location: ' . site_url('/orion-admin/users.php'));
exit;
