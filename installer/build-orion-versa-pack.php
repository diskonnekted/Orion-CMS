<?php

define('ABSPATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/');

function orion_pack_rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $fileinfo) {
        $path = $fileinfo->getPathname();
        if ($fileinfo->isDir()) {
            @rmdir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

function orion_pack_mkdirp($dir) {
    if (is_dir($dir)) return true;
    return @mkdir($dir, 0755, true);
}

function orion_pack_copy_file($src, $dst) {
    $dst_dir = dirname($dst);
    if (!is_dir($dst_dir)) {
        if (!orion_pack_mkdirp($dst_dir)) return false;
    }
    return @copy($src, $dst);
}

$out_base = ABSPATH . 'download/orion-versa-pack/';
$out_zip = ABSPATH . 'download/orion-versa-pack.zip';

orion_pack_rrmdir($out_base);
orion_pack_mkdirp($out_base);

$versa_theme_dir = ABSPATH . 'orion-content/themes/orion-versa/';
$shop_plugin_dir = ABSPATH . 'orion-content/plugins/orion-shop-manager/';

if (!is_dir($versa_theme_dir)) {
    http_response_code(500);
    echo 'theme orion-versa not found';
    exit;
}
if (!is_dir($shop_plugin_dir)) {
    http_response_code(500);
    echo 'plugin orion-shop-manager not found';
    exit;
}

$exclude_prefixes = array(
    'download/',
    'tmp/',
    '_dist/',
    '_tmp_guard/',
    'docs/',
    'assets/uploads/',
    'assets/img/theme-card/',
    'assets/img/legacy/',
    'orion-content/uploads/',
    'orion-content/database/',
    'orion-content/themes/',
    'orion-content/plugins/',
    '.git/',
    'node_modules/',
);

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
    if ($rel === 'orion-admin/installer-creator.php') {
        continue;
    }
    if ($rel === 'orion-admin/coba-gratis-submissions.php') {
        continue;
    }

    if ($fileinfo->isDir()) {
        continue;
    }
    if (substr($rel, -4) === '.zip') {
        continue;
    }
    if (strpos($rel, 'installer/build-') === 0) {
        continue;
    }
    if ($rel === 'download.php') {
        continue;
    }

    $skip = false;
    foreach ($exclude_prefixes as $pfx) {
        if (strpos($rel, $pfx) === 0) {
            if ($pfx === 'orion-content/themes/') {
                $skip = !((strpos($rel, 'orion-content/themes/orion-versa/') === 0) || ($rel === 'orion-content/themes/orion-versa'));
                break;
            }
            if ($pfx === 'orion-content/plugins/') {
                $skip = !((strpos($rel, 'orion-content/plugins/orion-shop-manager/') === 0) || ($rel === 'orion-content/plugins/index.php') || ($rel === 'orion-content/plugins/orion-shop-manager'));
                break;
            }
            $skip = true;
            break;
        }
    }
    if ($skip) continue;

    $dst = $out_base . $rel;
    if (!orion_pack_copy_file($real, $dst)) {
        http_response_code(500);
        echo 'copy failed: ' . $rel;
        exit;
    }
}

orion_pack_mkdirp($out_base . 'orion-content/uploads/products/');
@file_put_contents($out_base . 'orion-content/uploads/index.html', '');
@file_put_contents($out_base . 'orion-content/uploads/products/index.html', '');
@file_put_contents($out_base . 'orion-content/plugins/index.php', "<?php\n");
@file_put_contents($out_base . 'orion-content/themes/index.php', "<?php\n");

$readme = array();
$readme[] = '# Orion Versa Pack';
$readme[] = '';
$readme[] = 'Paket minimal Orion CMS berisi:';
$readme[] = '- Tema: orion-versa';
$readme[] = '- Plugin: orion-shop-manager';
$readme[] = '';
$readme[] = 'Tidak menyertakan tema/plugin lain.';
$readme[] = '';
$readme[] = '## Instalasi';
$readme[] = '1) Salin folder ini ke web root (contoh: D:\\\\xampp\\\\htdocs\\\\orion-versa-pack).';
$readme[] = '2) Pastikan PHP punya ekstensi: pdo_sqlite, sqlite3, mysqli, gd (opsional untuk compress upload).';
$readme[] = '3) Buka installer: /installer/';
$readme[] = '4) Selesai, login admin: /login.php';
$readme[] = '';
$readme[] = '## Catatan';
$readme[] = '- Installer menggunakan wizard paket. Paket yang tersedia ada di installer/packs/.';
$readme[] = '- Upload gambar produk disimpan di orion-content/uploads/products/.';
$readme[] = '';
@file_put_contents($out_base . 'README.md', implode("\n", $readme));

if (extension_loaded('zip')) {
    $zip = new ZipArchive();
    if ($zip->open($out_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        $base2 = str_replace('\\', '/', realpath($out_base));
        $base2 = rtrim($base2, '/') . '/';

        $it2 = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($base2, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($it2 as $f) {
            if ($f->isDir()) continue;
            $path = str_replace('\\', '/', $f->getPathname());
            $rel = substr($path, strlen($base2));
            $zip->addFile($path, $rel);
        }
        $zip->close();
    }
}

header('Content-Type: text/plain; charset=UTF-8');
echo "ok\n";
echo "folder: " . $out_base . "\n";
echo "zip: " . $out_zip . "\n";

