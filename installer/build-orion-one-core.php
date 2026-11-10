<?php

define('ABSPATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/');

$out_dir = ABSPATH . 'download/';
$out_file = $out_dir . 'orion-one-core.zip';

if (!extension_loaded('zip')) {
    http_response_code(500);
    echo 'zip extension not available';
    exit;
}

if (!is_dir($out_dir)) {
    @mkdir($out_dir, 0755, true);
}

$one_theme_dir = ABSPATH . 'orion-content/themes/orion-one/';
if (!is_dir($one_theme_dir)) {
    http_response_code(500);
    echo 'theme orion-one not found';
    exit;
}

$exclude_prefixes = array(
    'download/',
    'tmp/',
    '_dist/',
    '_tmp_guard/',
    'orion-content/uploads/',
    'orion-content/database/',
    'orion-content/themes/',
    'orion-content/plugins/',
    '.git/',
    'node_modules/',
);

$zip = new ZipArchive();
if (!$zip->open($out_file, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    http_response_code(500);
    echo 'cannot open zip output';
    exit;
}

$base = str_replace('\\', '/', realpath(ABSPATH));
$base = rtrim($base, '/') . '/';

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($it as $fileinfo) {
    $full = str_replace('\\', '/', $fileinfo->getPathname());
    $real = realpath($full);
    if (!$real) continue;
    $real = str_replace('\\', '/', $real);
    if (strpos($real, $base) !== 0) continue;
    $rel = substr($real, strlen($base));
    if ($rel === '' || $rel === false) continue;
    $rel = ltrim(str_replace('\\', '/', (string) $rel), '/');

    if ($rel === 'orion-config.php' || $rel === 'orion-config.php.bak' || strpos($rel, 'orion-config.php.bak_') === 0) {
        continue;
    }

    $is_dir = is_dir($real);

    $skip = false;
    foreach ($exclude_prefixes as $pfx) {
        if (strpos($rel, $pfx) === 0) {
            if ($pfx === 'orion-content/themes/') {
                $skip = !((strpos($rel, 'orion-content/themes/orion-one/') === 0) || ($rel === 'orion-content/themes/orion-one'));
                break;
            }
            if ($pfx === 'orion-content/plugins/') {
                $skip = $rel !== 'orion-content/plugins/index.php';
                break;
            }
            $skip = true;
            break;
        }
    }
    if ($skip) continue;
    if ($is_dir) continue;

    $zip->addFile($real, $rel);
}

$zip->close();

header('Content-Type: text/plain; charset=UTF-8');
echo 'ok: ' . $out_file;
