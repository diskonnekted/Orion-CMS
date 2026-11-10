<?php
require_once dirname(__DIR__) . '/orion-load.php';

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

// CSRF guard: reject POST payloads without a valid nonce.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['orion_nonce']) || !orion_verify_nonce($_POST['orion_nonce'], 'orion_installer_build'))) {
    $_POST = array();
}

// CSRF guard for the ZIP download endpoint.
if (isset($_GET['download']) && (!isset($_GET['orion_nonce']) || !orion_verify_nonce($_GET['orion_nonce'], 'orion_installer_download'))) {
    unset($_GET['download']);
}

if (isset($_GET['download'])) {
    $file = basename((string) $_GET['download']);
    $file = preg_replace('/[^a-zA-Z0-9._-]/', '', $file);
    if ($file !== '' && substr($file, -4) === '.zip') {
        $abs = ABSPATH . 'download/' . $file;
        if (is_file($abs)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            header('Content-Length: ' . filesize($abs));
            header('X-Content-Type-Options: nosniff');
            readfile($abs);
            exit;
        }
    }
    http_response_code(404);
    echo 'File tidak ditemukan.';
    exit;
}

function orion_installer_creator_safe_slug($value) {
    $value = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $value);
    return $value !== '' ? $value : 'custom';
}

function orion_installer_creator_read_file_head($path, $max_bytes = 8192) {
    $fh = @fopen($path, 'rb');
    if (!$fh) return '';
    $data = @fread($fh, $max_bytes);
    @fclose($fh);
    return is_string($data) ? $data : '';
}

function orion_installer_creator_parse_header($content, $field) {
    $pattern = '/^\s*' . preg_quote($field, '/') . '\s*:\s*(.+)$/mi';
    if (!preg_match($pattern, $content, $m)) return '';
    return trim((string) $m[1]);
}

function orion_installer_creator_list_themes() {
    $dir = ABSPATH . 'orion-content/themes/';
    $out = array();
    if (!is_dir($dir)) return $out;
    foreach (scandir($dir) as $name) {
        if ($name === '.' || $name === '..') continue;
        if ($name === 'index.php') continue;
        $slug = orion_installer_creator_safe_slug($name);
        if ($slug === '') continue;
        $path = $dir . $slug;
        if (!is_dir($path)) continue;
        $style = $path . '/style.css';
        $label = $slug;
        if (is_file($style)) {
            $head = orion_installer_creator_read_file_head($style);
            $theme_name = orion_installer_creator_parse_header($head, 'Theme Name');
            if ($theme_name !== '') $label = $theme_name;
        }
        $out[$slug] = array('slug' => $slug, 'label' => $label);
    }
    ksort($out);
    return $out;
}

function orion_installer_creator_list_plugins() {
    $dir = ABSPATH . 'orion-content/plugins/';
    $out = array();
    if (!is_dir($dir)) return $out;
    foreach (scandir($dir) as $name) {
        if ($name === '.' || $name === '..') continue;
        if ($name === 'index.php') continue;
        if (substr($name, -4) === '.zip') continue;

        $full = $dir . $name;
        if (is_file($full)) {
            if (substr($name, -4) !== '.php') continue;
            $head = orion_installer_creator_read_file_head($full);
            $plugin_name = orion_installer_creator_parse_header($head, 'Plugin Name');
            if ($plugin_name === '') continue;
            $desc = orion_installer_creator_parse_header($head, 'Description');
            $out[$name] = array(
                'key' => $name,
                'active_path' => $name,
                'copy_kind' => 'file',
                'copy_path' => $name,
                'name' => $plugin_name,
                'description' => $desc
            );
            continue;
        }

        if (!is_dir($full)) continue;
        $slug = orion_installer_creator_safe_slug($name);
        if ($slug === '') continue;
        $php_files = glob($full . '/*.php');
        if (!$php_files) continue;

        $main = '';
        $plugin_name = '';
        $desc = '';
        foreach ($php_files as $php) {
            $head = orion_installer_creator_read_file_head($php);
            $n = orion_installer_creator_parse_header($head, 'Plugin Name');
            if ($n === '') continue;
            $main = basename($php);
            $plugin_name = $n;
            $desc = orion_installer_creator_parse_header($head, 'Description');
            break;
        }
        if ($main === '') continue;

        $active_path = $slug . '/' . $main;
        $out[$active_path] = array(
            'key' => $active_path,
            'active_path' => $active_path,
            'copy_kind' => 'dir',
            'copy_path' => $slug,
            'name' => $plugin_name,
            'description' => $desc
        );
    }
    ksort($out);
    return $out;
}

function orion_installer_creator_decode_state($raw) {
    $raw = (string) $raw;
    if ($raw === '') return array();
    $decoded = base64_decode($raw, true);
    if ($decoded === false) return array();
    $json = json_decode($decoded, true);
    return is_array($json) ? $json : array();
}

function orion_installer_creator_encode_state($state) {
    return base64_encode(json_encode($state));
}

function orion_installer_creator_rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $fileinfo) {
        $path = $fileinfo->getPathname();
        if ($fileinfo->isDir()) @rmdir($path);
        else @unlink($path);
    }
    @rmdir($dir);
}

function orion_installer_creator_mkdirp($dir) {
    if (is_dir($dir)) return true;
    return @mkdir($dir, 0755, true);
}

function orion_installer_creator_copy_file($src, $dst) {
    $dst_dir = dirname($dst);
    if (!is_dir($dst_dir)) {
        if (!orion_installer_creator_mkdirp($dst_dir)) return false;
    }
    return @copy($src, $dst);
}

function orion_installer_creator_write_json($path, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return @file_put_contents($path, $json !== false ? $json : '{}') !== false;
}

function orion_installer_creator_zip_dos_time($timestamp) {
    $d = getdate((int) $timestamp);
    $year = (int) $d['year'];
    if ($year < 1980) $year = 1980;
    $month = (int) $d['mon'];
    $day = (int) $d['mday'];
    $hour = (int) $d['hours'];
    $min = (int) $d['minutes'];
    $sec = (int) $d['seconds'];
    $dos_time = (($hour & 0x1f) << 11) | (($min & 0x3f) << 5) | (intdiv($sec, 2) & 0x1f);
    $dos_date = ((($year - 1980) & 0x7f) << 9) | (($month & 0x0f) << 5) | ($day & 0x1f);
    return array($dos_time, $dos_date);
}

function orion_installer_creator_zip_create_store($source_dir, $zip_path) {
    $source_dir = rtrim(str_replace('\\', '/', (string) $source_dir), '/') . '/';
    $zip_path = (string) $zip_path;
    if ($source_dir === '/' || $zip_path === '') return false;
    $base = realpath($source_dir);
    if (!$base) return false;
    $base = rtrim(str_replace('\\', '/', $base), '/') . '/';

    $fh = @fopen($zip_path, 'wb');
    if (!$fh) return false;

    $central = '';
    $entries = 0;
    $offset = 0;

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($it as $fileinfo) {
        if ($fileinfo->isDir()) continue;
        $path = str_replace('\\', '/', $fileinfo->getPathname());
        $real = realpath($path);
        if (!$real) continue;
        $real = str_replace('\\', '/', $real);
        if (strpos($real, $base) !== 0) continue;
        $rel = substr($real, strlen($base));
        if ($rel === '' || $rel === false) continue;
        $rel = str_replace('\\', '/', $rel);

        $data = @file_get_contents($real);
        if (!is_string($data)) {
            @fclose($fh);
            return false;
        }

        $crc = crc32($data);
        if ($crc < 0) $crc += 4294967296;
        $size = strlen($data);
        $mtime = @filemtime($real);
        if (!$mtime) $mtime = time();
        list($dos_time, $dos_date) = orion_installer_creator_zip_dos_time($mtime);

        $name = $rel;
        $name_len = strlen($name);

        $local = pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 0, $dos_time, $dos_date, $crc, $size, $size, $name_len, 0);
        $local .= $name;

        $written = fwrite($fh, $local);
        if ($written === false) {
            @fclose($fh);
            return false;
        }
        $offset += strlen($local);

        $written = fwrite($fh, $data);
        if ($written === false) {
            @fclose($fh);
            return false;
        }

        $local_header_offset = $offset - strlen($local);
        $offset += $size;

        $cd = pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 0, $dos_time, $dos_date, $crc, $size, $size, $name_len, 0, 0, 0, 0, 0, $local_header_offset);
        $cd .= $name;
        $central .= $cd;
        $entries++;
    }

    $cd_offset = $offset;
    $cd_size = strlen($central);
    if ($cd_size > 0) {
        if (fwrite($fh, $central) === false) {
            @fclose($fh);
            return false;
        }
        $offset += $cd_size;
    }

    $eocd = pack('VvvvvVVv', 0x06054b50, 0, 0, $entries, $entries, $cd_size, $cd_offset, 0);
    if (fwrite($fh, $eocd) === false) {
        @fclose($fh);
        return false;
    }

    @fclose($fh);
    return true;
}

function orion_installer_creator_build_zip($opts) {
    $theme = isset($opts['theme']) ? orion_installer_creator_safe_slug($opts['theme']) : '';
    $plugins = isset($opts['plugins']) && is_array($opts['plugins']) ? $opts['plugins'] : array();
    $site_title = isset($opts['site_title']) ? trim((string) $opts['site_title']) : 'Orion CMS';
    $admin_user = isset($opts['admin_user']) ? trim((string) $opts['admin_user']) : 'admin';
    $admin_email = isset($opts['admin_email']) ? trim((string) $opts['admin_email']) : 'admin@example.com';
    $table_prefix = isset($opts['table_prefix']) ? trim((string) $opts['table_prefix']) : 'orion_';
    $db_path = isset($opts['db_path']) ? trim((string) $opts['db_path']) : 'orion-content/database/orion.sqlite';

    if ($theme === '') return array('ok' => false, 'error' => 'Tema wajib dipilih.');
    $theme_dir = ABSPATH . 'orion-content/themes/' . $theme . '/';
    if (!is_dir($theme_dir)) return array('ok' => false, 'error' => 'Tema tidak ditemukan.');

    $plugin_catalog = orion_installer_creator_list_plugins();
    $plugins_active = array();
    $plugins_copy = array();
    foreach ($plugins as $p) {
        $p = str_replace('\\', '/', (string) $p);
        if (!isset($plugin_catalog[$p])) continue;
        $plugins_active[] = $plugin_catalog[$p]['active_path'];
        $plugins_copy[] = $plugin_catalog[$p];
    }

    $slug = 'orion-pack-' . $theme . '-' . date('Ymd-His');
    $out_dir = ABSPATH . 'download/' . $slug . '/';
    $out_zip = ABSPATH . 'download/' . $slug . '.zip';

    orion_installer_creator_rrmdir($out_dir);
    if (!orion_installer_creator_mkdirp($out_dir)) return array('ok' => false, 'error' => 'Tidak bisa membuat folder output.');

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

    $allow_theme_prefix = 'orion-content/themes/' . $theme . '/';
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
        if ($rel === 'orion-admin/installer-creator.php') continue;
        if ($rel === 'orion-admin/coba-gratis-submissions.php') continue;
        if (substr($rel, -4) === '.zip') continue;
        if (strpos($rel, 'installer/build-') === 0) continue;
        if ($rel === 'download.php') continue;

        if ($fileinfo->isDir()) continue;

        $skip = false;
        foreach ($exclude_prefixes as $pfx) {
            if (strpos($rel, $pfx) === 0) {
                if ($pfx === 'orion-content/themes/') {
                    $skip = !(strpos($rel, $allow_theme_prefix) === 0);
                    break;
                }
                if ($pfx === 'orion-content/plugins/') {
                    $skip = true;
                    break;
                }
                $skip = true;
                break;
            }
        }
        if ($skip) continue;

        $dst = $out_dir . $rel;
        if (!orion_installer_creator_copy_file($real, $dst)) {
            return array('ok' => false, 'error' => 'Gagal menyalin file: ' . $rel);
        }
    }

    @file_put_contents($out_dir . 'orion-content/plugins/index.php', "<?php\n");
    @file_put_contents($out_dir . 'orion-content/themes/index.php', "<?php\n");
    orion_installer_creator_mkdirp($out_dir . 'orion-content/uploads/products/');
    @file_put_contents($out_dir . 'orion-content/uploads/index.html', '');
    @file_put_contents($out_dir . 'orion-content/uploads/products/index.html', '');

    foreach ($plugins_copy as $p) {
        if ($p['copy_kind'] === 'file') {
            $src = ABSPATH . 'orion-content/plugins/' . $p['copy_path'];
            $dst = $out_dir . 'orion-content/plugins/' . $p['copy_path'];
            if (!orion_installer_creator_copy_file($src, $dst)) {
                return array('ok' => false, 'error' => 'Gagal menyalin plugin: ' . $p['copy_path']);
            }
        } else {
            $src_dir = ABSPATH . 'orion-content/plugins/' . $p['copy_path'] . '/';
            $dst_dir = $out_dir . 'orion-content/plugins/' . $p['copy_path'] . '/';
            orion_installer_creator_mkdirp($dst_dir);
            $itp = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($src_dir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($itp as $fi) {
                if ($fi->isDir()) continue;
                $src = str_replace('\\', '/', $fi->getPathname());
                $relp = substr(str_replace('\\', '/', $src), strlen(str_replace('\\', '/', $src_dir)));
                $dst = $dst_dir . $relp;
                if (!orion_installer_creator_copy_file($src, $dst)) {
                    return array('ok' => false, 'error' => 'Gagal menyalin plugin dir: ' . $p['copy_path']);
                }
            }
        }
    }

    $pack_slug = 'custom';
    $packs_dir = $out_dir . 'installer/packs/' . $pack_slug . '/';
    orion_installer_creator_mkdirp($packs_dir);
    orion_installer_creator_write_json($packs_dir . 'manifest.json', array(
        'slug' => $pack_slug,
        'name' => 'Custom Pack',
        'template' => $theme,
        'plugins' => array_values($plugins_active),
        'description' => 'Generated by Installer Creator'
    ));

    orion_installer_creator_write_json($out_dir . 'installer/presets.json', array(
        'package' => $pack_slug,
        'site_title' => $site_title,
        'admin_user' => $admin_user,
        'admin_email' => $admin_email,
        'table_prefix' => $table_prefix,
        'db_path' => $db_path
    ));

    $readme = array();
    $readme[] = '# Orion Pack';
    $readme[] = '';
    $readme[] = 'Paket minimal Orion CMS berisi:';
    $readme[] = '- Tema: ' . $theme;
    $readme[] = '- Plugin: ' . (empty($plugins_active) ? '(tidak ada)' : implode(', ', $plugins_active));
    $readme[] = '';
    $readme[] = '## Instalasi';
    $readme[] = '1) Extract ZIP ini ke web root (contoh: htdocs/olshop).';
    $readme[] = '2) Buka /installer/ lalu ikuti wizard.';
    $readme[] = '3) Login admin: /login.php atau /orion-admin/';
    $readme[] = '';
    @file_put_contents($out_dir . 'README.md', implode("\n", $readme));

    $zip_ok = false;
    if (class_exists('ZipArchive') && extension_loaded('zip')) {
        $zip = new ZipArchive();
        if (!$zip->open($out_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            return array('ok' => false, 'error' => 'Tidak bisa membuat ZIP output.');
        }

        $base2 = str_replace('\\', '/', realpath($out_dir));
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
        $zip_ok = $zip->close();
    } else {
        $zip_ok = orion_installer_creator_zip_create_store($out_dir, $out_zip);
    }

    if (!$zip_ok) {
        return array('ok' => false, 'error' => 'Gagal membuat ZIP. Pastikan ekstensi zip aktif, atau gunakan PHP yang mendukung pembuatan ZIP.');
    }

    return array('ok' => true, 'folder' => $out_dir, 'zip' => $out_zip, 'zip_basename' => basename($out_zip));
}

$themes = orion_installer_creator_list_themes();
$plugins = orion_installer_creator_list_plugins();

$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;
if ($step < 1) $step = 1;
if ($step > 5) $step = 5;

$state = array();
if (isset($_GET['state'])) {
    $state = orion_installer_creator_decode_state($_GET['state']);
}
if (isset($_POST['state'])) {
    $state = orion_installer_creator_decode_state($_POST['state']);
}

$errors = array();
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        $theme = isset($_POST['theme']) ? orion_installer_creator_safe_slug($_POST['theme']) : '';
        if ($theme === '' || !isset($themes[$theme])) {
            $errors['theme'] = 'Tema wajib dipilih.';
        } else {
            $state['theme'] = $theme;
        }
        if (empty($errors)) {
            header('Location: ' . site_url('/orion-admin/installer-creator.php?step=2&state=' . rawurlencode(orion_installer_creator_encode_state($state))));
            exit;
        }
    } elseif ($step === 2) {
        $selected = isset($_POST['plugins']) && is_array($_POST['plugins']) ? $_POST['plugins'] : array();
        $selected2 = array();
        foreach ($selected as $p) {
            $p = str_replace('\\', '/', (string) $p);
            if (isset($plugins[$p])) $selected2[] = $p;
        }
        $state['plugins'] = $selected2;
        header('Location: ' . site_url('/orion-admin/installer-creator.php?step=3&state=' . rawurlencode(orion_installer_creator_encode_state($state))));
        exit;
    } elseif ($step === 3) {
        $state['site_title'] = isset($_POST['site_title']) ? trim((string) $_POST['site_title']) : '';
        if ($state['site_title'] === '') $errors['site_title'] = 'Nama website wajib diisi.';
        if (empty($errors)) {
            header('Location: ' . site_url('/orion-admin/installer-creator.php?step=4&state=' . rawurlencode(orion_installer_creator_encode_state($state))));
            exit;
        }
    } elseif ($step === 4) {
        $state['admin_user'] = isset($_POST['admin_user']) ? trim((string) $_POST['admin_user']) : '';
        $state['admin_email'] = isset($_POST['admin_email']) ? trim((string) $_POST['admin_email']) : '';
        $state['table_prefix'] = isset($_POST['table_prefix']) ? trim((string) $_POST['table_prefix']) : '';
        $state['db_path'] = isset($_POST['db_path']) ? trim((string) $_POST['db_path']) : '';

        if ($state['admin_user'] === '') $errors['admin_user'] = 'Username admin wajib diisi.';
        if ($state['admin_email'] !== '' && !filter_var($state['admin_email'], FILTER_VALIDATE_EMAIL)) $errors['admin_email'] = 'Email tidak valid.';
        if ($state['table_prefix'] === '') $errors['table_prefix'] = 'Table prefix wajib diisi.';
        if ($state['db_path'] === '') $errors['db_path'] = 'DB path wajib diisi.';

        if (empty($errors)) {
            header('Location: ' . site_url('/orion-admin/installer-creator.php?step=5&state=' . rawurlencode(orion_installer_creator_encode_state($state))));
            exit;
        }
    } elseif ($step === 5 && isset($_POST['build_zip'])) {
        $result = orion_installer_creator_build_zip(array(
            'theme' => isset($state['theme']) ? $state['theme'] : '',
            'plugins' => isset($state['plugins']) ? $state['plugins'] : array(),
            'site_title' => isset($state['site_title']) ? $state['site_title'] : 'Orion CMS',
            'admin_user' => isset($state['admin_user']) ? $state['admin_user'] : 'admin',
            'admin_email' => isset($state['admin_email']) ? $state['admin_email'] : 'admin@example.com',
            'table_prefix' => isset($state['table_prefix']) ? $state['table_prefix'] : 'orion_',
            'db_path' => isset($state['db_path']) ? $state['db_path'] : 'orion-content/database/orion.sqlite',
        ));
        if (!$result['ok']) {
            $errors['global'] = $result['error'];
        } else {
            if (isset($result['zip_basename']) && (string) $result['zip_basename'] !== '') {
                $state['last_zip_basename'] = (string) $result['zip_basename'];
            }
            if (isset($result['folder']) && (string) $result['folder'] !== '') {
                $state['last_zip_folder'] = (string) $result['folder'];
            }
            header('Location: ' . site_url('/orion-admin/installer-creator.php?step=5&state=' . rawurlencode(orion_installer_creator_encode_state($state))));
            exit;
        }
    }
}

$state_encoded = orion_installer_creator_encode_state($state);

function orion_installer_creator_step_badge($n, $active) {
    $base = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-extrabold border ';
    if ($active) return $base . 'bg-orion-600 border-orion-600 text-white';
    return $base . 'bg-white border-slate-200 text-slate-600';
}

function orion_installer_creator_step_url($step, $state_encoded) {
    $step = (int) $step;
    if ($step < 1) $step = 1;
    if ($step > 5) $step = 5;
    $url = site_url('/orion-admin/installer-creator.php?step=' . $step);
    if (is_string($state_encoded) && $state_encoded !== '') {
        $url .= '&state=' . rawurlencode($state_encoded);
    }
    return $url;
}

require_once ABSPATH . 'orion-admin/admin-header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Tools</div>
        <h1 class="mt-2 text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Installer Creator (Wizard)</h1>
        <p class="mt-2 text-slate-600">Buat paket ZIP minimal Orion: pilih tema, plugin, setting dasar, lalu unduh ZIP siap instal.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-7 py-6 border-b border-slate-100 bg-slate-50">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="<?php echo orion_installer_creator_step_badge(1, $step === 1); ?>">1</div>
                    <div class="text-sm font-extrabold text-slate-800">Tema</div>
                </div>
                <div class="text-slate-300">→</div>
                <div class="flex items-center gap-3">
                    <div class="<?php echo orion_installer_creator_step_badge(2, $step === 2); ?>">2</div>
                    <div class="text-sm font-extrabold text-slate-800">Plugin</div>
                </div>
                <div class="text-slate-300">→</div>
                <div class="flex items-center gap-3">
                    <div class="<?php echo orion_installer_creator_step_badge(3, $step === 3); ?>">3</div>
                    <div class="text-sm font-extrabold text-slate-800">Website</div>
                </div>
                <div class="text-slate-300">→</div>
                <div class="flex items-center gap-3">
                    <div class="<?php echo orion_installer_creator_step_badge(4, $step === 4); ?>">4</div>
                    <div class="text-sm font-extrabold text-slate-800">Admin & DB</div>
                </div>
                <div class="text-slate-300">→</div>
                <div class="flex items-center gap-3">
                    <div class="<?php echo orion_installer_creator_step_badge(5, $step === 5); ?>">5</div>
                    <div class="text-sm font-extrabold text-slate-800">Finish</div>
                </div>
            </div>
        </div>

        <div class="p-7">
            <?php if (isset($errors['global'])): ?>
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 font-semibold">
                    <?php echo htmlspecialchars($errors['global']); ?>
                </div>
            <?php endif; ?>

            <?php if ($step === 1): ?>
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_installer_build'); ?>
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state_encoded); ?>">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">Pilih Tema</div>
                        <div class="mt-2 text-sm text-slate-600">Hanya tema terpilih yang akan dimasukkan ke ZIP.</div>
                        <?php if (isset($errors['theme'])): ?><div class="mt-3 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['theme']); ?></div><?php endif; ?>
                        <div class="mt-4">
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Tema</label>
                            <select name="theme" class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-4 focus:ring-orion-200/60 focus:border-orion-400">
                                <option value="">-- Pilih Tema --</option>
                                <?php foreach ($themes as $t): ?>
                                    <?php $selected = isset($state['theme']) && (string) $state['theme'] === (string) $t['slug']; ?>
                                    <option value="<?php echo htmlspecialchars($t['slug']); ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($t['label'] . ' (' . $t['slug'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="mt-2 text-xs text-slate-500">Tip: gunakan Ctrl+F di dropdown untuk mencari (tergantung browser).</div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-6 py-3 rounded-2xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Lanjut</button>
                    </div>
                </form>
            <?php elseif ($step === 2): ?>
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_installer_build'); ?>
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state_encoded); ?>">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">Pilih Plugin</div>
                        <div class="mt-2 text-sm text-slate-600">Plugin terpilih akan otomatis aktif setelah instalasi.</div>
                        <div class="mt-4 grid grid-cols-1 gap-3">
                            <?php foreach ($plugins as $p): ?>
                                <?php
                                $selected = isset($state['plugins']) && is_array($state['plugins']) && in_array($p['active_path'], $state['plugins'], true);
                                ?>
                                <label class="flex items-start gap-3 p-4 rounded-2xl border <?php echo $selected ? 'border-orion-300 bg-orion-50' : 'border-slate-200 bg-white hover:bg-slate-50'; ?> cursor-pointer">
                                    <input type="checkbox" name="plugins[]" value="<?php echo htmlspecialchars($p['active_path']); ?>" <?php echo $selected ? 'checked' : ''; ?> class="mt-1">
                                    <span class="block">
                                        <span class="block font-extrabold text-slate-900"><?php echo htmlspecialchars($p['name']); ?></span>
                                        <?php if (trim((string) $p['description']) !== ''): ?>
                                            <span class="block mt-1 text-sm text-slate-600"><?php echo htmlspecialchars($p['description']); ?></span>
                                        <?php endif; ?>
                                        <span class="block text-xs text-slate-500 mt-2 font-bold">Active path: <span class="font-mono text-slate-800"><?php echo htmlspecialchars($p['active_path']); ?></span></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <a href="<?php echo orion_installer_creator_step_url(1, $state_encoded); ?>" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Kembali</a>
                        <button class="px-6 py-3 rounded-2xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Lanjut</button>
                    </div>
                </form>
            <?php elseif ($step === 3): ?>
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_installer_build'); ?>
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state_encoded); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Nama Website</label>
                            <input name="site_title" value="<?php echo htmlspecialchars(isset($state['site_title']) ? (string) $state['site_title'] : 'Orion CMS'); ?>" class="w-full px-4 py-3 rounded-2xl border <?php echo isset($errors['site_title']) ? 'border-rose-300' : 'border-slate-200'; ?> bg-white">
                            <?php if (isset($errors['site_title'])): ?><div class="mt-2 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['site_title']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <a href="<?php echo orion_installer_creator_step_url(2, $state_encoded); ?>" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Kembali</a>
                        <button class="px-6 py-3 rounded-2xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Lanjut</button>
                    </div>
                </form>
            <?php elseif ($step === 4): ?>
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_installer_build'); ?>
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state_encoded); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Admin Username</label>
                            <input name="admin_user" value="<?php echo htmlspecialchars(isset($state['admin_user']) ? (string) $state['admin_user'] : 'admin'); ?>" class="w-full px-4 py-3 rounded-2xl border <?php echo isset($errors['admin_user']) ? 'border-rose-300' : 'border-slate-200'; ?> bg-white">
                            <?php if (isset($errors['admin_user'])): ?><div class="mt-2 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['admin_user']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Admin Email</label>
                            <input name="admin_email" value="<?php echo htmlspecialchars(isset($state['admin_email']) ? (string) $state['admin_email'] : 'admin@example.com'); ?>" class="w-full px-4 py-3 rounded-2xl border <?php echo isset($errors['admin_email']) ? 'border-rose-300' : 'border-slate-200'; ?> bg-white">
                            <?php if (isset($errors['admin_email'])): ?><div class="mt-2 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['admin_email']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Table Prefix</label>
                            <input name="table_prefix" value="<?php echo htmlspecialchars(isset($state['table_prefix']) ? (string) $state['table_prefix'] : 'orion_'); ?>" class="w-full px-4 py-3 rounded-2xl border <?php echo isset($errors['table_prefix']) ? 'border-rose-300' : 'border-slate-200'; ?> bg-white font-mono text-sm">
                            <?php if (isset($errors['table_prefix'])): ?><div class="mt-2 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['table_prefix']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">SQLite DB Path (relative)</label>
                            <input name="db_path" value="<?php echo htmlspecialchars(isset($state['db_path']) ? (string) $state['db_path'] : 'orion-content/database/orion.sqlite'); ?>" class="w-full px-4 py-3 rounded-2xl border <?php echo isset($errors['db_path']) ? 'border-rose-300' : 'border-slate-200'; ?> bg-white font-mono text-xs">
                            <?php if (isset($errors['db_path'])): ?><div class="mt-2 text-xs text-rose-700 font-extrabold"><?php echo htmlspecialchars($errors['db_path']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <a href="<?php echo orion_installer_creator_step_url(3, $state_encoded); ?>" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Kembali</a>
                        <button class="px-6 py-3 rounded-2xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Lanjut</button>
                    </div>
                </form>
            <?php else: ?>
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_installer_build'); ?>
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state_encoded); ?>">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="text-sm font-extrabold text-slate-900">Ringkasan</div>
                        <div class="mt-3 grid gap-2 text-sm text-slate-700">
                            <div>Tema: <span class="font-mono text-slate-900"><?php echo htmlspecialchars(isset($state['theme']) ? (string) $state['theme'] : '-'); ?></span></div>
                            <div>Plugin: <span class="font-mono text-slate-900"><?php echo htmlspecialchars(isset($state['plugins']) && is_array($state['plugins']) ? implode(', ', $state['plugins']) : '-'); ?></span></div>
                            <div>Nama Website: <span class="font-semibold text-slate-900"><?php echo htmlspecialchars(isset($state['site_title']) ? (string) $state['site_title'] : '-'); ?></span></div>
                            <div>Admin: <span class="font-mono text-slate-900"><?php echo htmlspecialchars(isset($state['admin_user']) ? (string) $state['admin_user'] : '-'); ?></span></div>
                            <div>DB Path: <span class="font-mono text-slate-900"><?php echo htmlspecialchars(isset($state['db_path']) ? (string) $state['db_path'] : '-'); ?></span></div>
                        </div>
                    </div>

                    <?php
                    $zip_basename = '';
                    $zip_folder = '';
                    if ($result && isset($result['ok']) && $result['ok']) {
                        if (isset($result['zip_basename'])) $zip_basename = (string) $result['zip_basename'];
                        if (isset($result['folder'])) $zip_folder = (string) $result['folder'];
                    } else {
                        if (isset($state['last_zip_basename'])) $zip_basename = (string) $state['last_zip_basename'];
                        if (isset($state['last_zip_folder'])) $zip_folder = (string) $state['last_zip_folder'];
                    }
                    ?>

                    <?php if ($zip_basename !== '' || $zip_folder !== ''): ?>
                        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                            <div class="text-sm font-extrabold text-emerald-800">Berhasil</div>
                            <div class="mt-2 text-sm text-emerald-900 font-semibold">ZIP siap diunduh dan siap dipindahkan untuk instalasi baru.</div>
                            <div class="mt-3 text-sm text-emerald-900">
                                Folder: <span class="font-mono"><?php echo htmlspecialchars($zip_folder); ?></span>
                            </div>
                            <?php if ($zip_basename !== ''): ?>
                                <div class="mt-3">
                                    <a class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition" href="<?php echo site_url('/orion-admin/installer-creator.php?download=' . rawurlencode($zip_basename) . '&orion_nonce=' . urlencode(orion_create_nonce('orion_installer_download'))); ?>">Download ZIP</a>
                                </div>
                            <?php endif; ?>
                            <div class="mt-5 text-sm text-emerald-900">
                                Instruksi:
                                <ol class="list-decimal pl-5 mt-2 space-y-1">
                                    <li>Extract ZIP ke web root (contoh: <span class="font-mono">htdocs/olshop</span>).</li>
                                    <li>Buka <span class="font-mono">/installer/</span> lalu ikuti wizard instal.</li>
                                    <li>Login admin: <span class="font-mono">/login.php</span> atau <span class="font-mono">/orion-admin/</span>.</li>
                                </ol>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between">
                        <a href="<?php echo orion_installer_creator_step_url(4, $state_encoded); ?>" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Kembali</a>
                        <button type="submit" name="build_zip" value="1" class="px-6 py-3 rounded-2xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Buat ZIP</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ABSPATH . 'orion-admin/admin-footer.php'; ?>
