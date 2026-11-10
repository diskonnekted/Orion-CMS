<?php

define('ABSPATH', rtrim(str_replace('\\', '/', __DIR__), '/') . '/');

function orion_download_guess_base_url() {
    $protocol = "http://";
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = "https://";
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

    $basePath = '';
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $absPath = rtrim(str_replace('\\', '/', realpath(ABSPATH)), '/');
        if ($docRoot && $absPath && strpos($absPath, $docRoot) === 0) {
            $sub = trim(substr($absPath, strlen($docRoot)), '/');
            if ($sub !== '') $basePath = '/' . $sub;
        }
    }

    if ($basePath === '') {
        $script_name = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
        $dir = rtrim(str_replace('\\', '/', dirname($script_name)), '/');
        if ($dir && $dir !== '/') $basePath = $dir;
    }

    return rtrim($protocol . $host . $basePath, '/');
}

function orion_download_dir_latest_mtime($path) {
    $path = (string) $path;
    if ($path === '' || !file_exists($path)) return 0;
    if (is_file($path)) {
        $t = @filemtime($path);
        return $t ? (int) $t : 0;
    }
    $max = 0;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $fi) {
        if ($fi->isDir()) continue;
        $t = $fi->getMTime();
        if ($t > $max) $max = $t;
    }
    $root_t = @filemtime($path);
    if ($root_t && $root_t > $max) $max = (int) $root_t;
    return (int) $max;
}

function orion_download_zip_needs_rebuild($zip_path, $source_path) {
    if (!file_exists($zip_path)) return true;
    $zip_mtime = @filemtime($zip_path);
    if (!$zip_mtime) return true;
    $src_mtime = orion_download_dir_latest_mtime($source_path);
    return $src_mtime > (int) $zip_mtime;
}

function orion_download_create_zip_recursively($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));
    if (!$source) return false;

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);
            $file = realpath($file);
            if (!$file) continue;
            $file = str_replace('\\', '/', $file);
            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

$file = isset($_GET['file']) ? basename((string) $_GET['file']) : '';
if ($file === '' || !preg_match('/^[a-zA-Z0-9._-]+\\.zip$/', $file)) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'invalid file';
    exit;
}

$download_dir = ABSPATH . 'download/';
$zip_path = $download_dir . $file;

// Only serve archives that already exist. Building them on request turned this
// endpoint into an unauthenticated write primitive (and let anyone repackage a
// theme's PHP source). Archives are produced by installer-creator.php instead.
if (!is_file($zip_path)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'not found';
    exit;
}

$base = orion_download_guess_base_url();
header('Location: ' . $base . '/download/' . rawurlencode($file));
exit;

