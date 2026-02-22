<?php

$bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';

if (file_exists($bootstrap_path)) {
    require_once $bootstrap_path;
} else {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php';
    } else {
        die('Orion CMS Core not found.');
    }
}

if (!function_exists('is_user_logged_in') || !is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

require_once __DIR__ . '/doa-harian.php';

