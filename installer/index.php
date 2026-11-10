<?php

define('ORION_INSTALLER', true);
define('ABSPATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/');

function orion_installer_base_url() {
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
        $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $dir = rtrim(str_replace('\\', '/', dirname($script_name)), '/');
        $installer_dir = '/installer';
        $len = strlen($installer_dir);
        if (substr($dir, -$len) === $installer_dir) {
            $dir = substr($dir, 0, -$len);
        } elseif (($pos = strpos($dir, $installer_dir . '/')) !== false) {
            $dir = substr($dir, 0, $pos);
        }
        if ($dir && $dir !== '/') $basePath = $dir;
    }

    return rtrim($protocol . $host . $basePath, '/');
}

/**
 * True when the request originates from the machine running the server.
 */
function orion_installer_is_local_request() {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $local = array('127.0.0.1', '::1', '::ffff:127.0.0.1');

    $server = isset($_SERVER['SERVER_ADDR']) ? (string) $_SERVER['SERVER_ADDR'] : '';
    if ($server !== '' && $ip !== '' && $ip === $server) {
        return true;
    }

    return in_array($ip, $local, true);
}

function orion_installer_safe_prefix($value) {    $value = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $value);
    if ($value === '') return 'orion_';
    if (substr($value, -1) !== '_') $value .= '_';
    return $value;
}

function orion_installer_safe_db_path($value) {
    $value = str_replace('\\', '/', trim((string) $value));
    if ($value === '') $value = 'orion-content/database/orion.sqlite';
    $value = ltrim($value, '/');
    if (strpos($value, '../') !== false) $value = 'orion-content/database/orion.sqlite';
    return ABSPATH . $value;
}

function orion_installer_pdo($file) {
    $dir = dirname($file);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $pdo = new PDO('sqlite:' . $file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    return $pdo;
}

function orion_installer_exec_all($pdo, $statements) {
    foreach ($statements as $sql) {
        $pdo->exec($sql);
    }
}

function orion_installer_insert_option($pdo, $table, $name, $value, $autoload = 'yes') {
    $stmt = $pdo->prepare("INSERT INTO $table (option_name, option_value, autoload) VALUES (:n, :v, :a)");
    $stmt->execute(array(':n' => $name, ':v' => $value, ':a' => $autoload));
}

function orion_installer_insert_usermeta($pdo, $table, $user_id, $key, $value) {
    $stmt = $pdo->prepare("INSERT INTO $table (user_id, meta_key, meta_value) VALUES (:uid, :k, :v)");
    $stmt->execute(array(':uid' => $user_id, ':k' => $key, ':v' => $value));
}

function orion_installer_load_packages() {
    $packs_dir = ABSPATH . 'installer/packs/';
    if (!is_dir($packs_dir)) return array();

    $packs = array();
    $glob = glob($packs_dir . '*/manifest.json');
    if (!$glob) return array();

    foreach ($glob as $file) {
        $raw = @file_get_contents($file);
        if ($raw === false) continue;
        $json = json_decode($raw, true);
        if (!is_array($json)) continue;

        $slug = isset($json['slug']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $json['slug']) : '';
        $name = isset($json['name']) ? trim((string) $json['name']) : '';
        $template = isset($json['template']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $json['template']) : '';
        $plugins = isset($json['plugins']) && is_array($json['plugins']) ? $json['plugins'] : array();
        $desc = isset($json['description']) ? trim((string) $json['description']) : '';

        if ($slug === '' || $name === '' || $template === '') continue;

        $theme_dir = ABSPATH . 'orion-content/themes/' . $template . '/';
        if (!is_dir($theme_dir)) continue;

        $plugins_clean = array();
        foreach ($plugins as $p) {
            $p = trim((string) $p);
            if ($p === '') continue;
            $p = str_replace('\\', '/', $p);
            if (strpos($p, '../') !== false) continue;
            $plugins_clean[] = $p;
        }

        $packs[$slug] = array(
            'slug' => $slug,
            'name' => $name,
            'template' => $template,
            'plugins' => $plugins_clean,
            'description' => $desc
        );
    }

    ksort($packs);
    return $packs;
}

function orion_installer_run($data) {
    $prefix = orion_installer_safe_prefix($data['table_prefix']);
    $db_file = orion_installer_safe_db_path($data['db_path']);
    $site_title = trim((string) $data['site_title']);
    $admin_user = trim((string) $data['admin_user']);
    $admin_email = trim((string) $data['admin_email']);
    $admin_pass = (string) $data['admin_pass'];
    $template = isset($data['template']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $data['template']) : 'orion-one';
    $active_plugins = isset($data['active_plugins']) && is_array($data['active_plugins']) ? $data['active_plugins'] : array();

    $pdo = orion_installer_pdo($db_file);

    $posts_table = $prefix . 'posts';
    $postmeta_table = $prefix . 'postmeta';
    $terms_table = $prefix . 'terms';
    $term_taxonomy_table = $prefix . 'term_taxonomy';
    $term_relationships_table = $prefix . 'term_relationships';
    $options_table = $prefix . 'options';
    $users_table = $prefix . 'users';
    $usermeta_table = $prefix . 'usermeta';

    $schema = array(
        "CREATE TABLE IF NOT EXISTS $posts_table (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            post_author INTEGER NOT NULL DEFAULT 0,
            post_date TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            post_content TEXT NOT NULL,
            post_title TEXT NOT NULL,
            post_status TEXT NOT NULL DEFAULT 'publish',
            post_type TEXT NOT NULL DEFAULT 'post',
            post_modified TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS $postmeta_table (
            meta_id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL DEFAULT 0,
            meta_key TEXT DEFAULT NULL,
            meta_value TEXT
        )",
        "CREATE TABLE IF NOT EXISTS $terms_table (
            term_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL DEFAULT '',
            slug TEXT NOT NULL DEFAULT '',
            term_group INTEGER NOT NULL DEFAULT 0
        )",
        "CREATE TABLE IF NOT EXISTS $term_taxonomy_table (
            term_taxonomy_id INTEGER PRIMARY KEY AUTOINCREMENT,
            term_id INTEGER NOT NULL DEFAULT 0,
            taxonomy TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL,
            parent INTEGER NOT NULL DEFAULT 0,
            count INTEGER NOT NULL DEFAULT 0
        )",
        "CREATE TABLE IF NOT EXISTS $term_relationships_table (
            object_id INTEGER NOT NULL DEFAULT 0,
            term_taxonomy_id INTEGER NOT NULL DEFAULT 0,
            term_order INTEGER NOT NULL DEFAULT 0,
            PRIMARY KEY (object_id, term_taxonomy_id)
        )",
        "CREATE TABLE IF NOT EXISTS $options_table (
            option_id INTEGER PRIMARY KEY AUTOINCREMENT,
            option_name TEXT NOT NULL DEFAULT '',
            option_value TEXT NOT NULL,
            autoload TEXT NOT NULL DEFAULT 'yes'
        )",
        "CREATE TABLE IF NOT EXISTS $users_table (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            user_login TEXT NOT NULL DEFAULT '',
            user_pass TEXT NOT NULL DEFAULT '',
            user_nicename TEXT NOT NULL DEFAULT '',
            user_email TEXT NOT NULL DEFAULT '',
            user_registered TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_status INTEGER NOT NULL DEFAULT 0,
            display_name TEXT NOT NULL DEFAULT ''
        )",
        "CREATE TABLE IF NOT EXISTS $usermeta_table (
            umeta_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL DEFAULT 0,
            meta_key TEXT DEFAULT NULL,
            meta_value TEXT
        )",
        "CREATE UNIQUE INDEX IF NOT EXISTS {$options_table}_option_name ON $options_table(option_name)",
        "CREATE INDEX IF NOT EXISTS {$postmeta_table}_post_id ON $postmeta_table(post_id)",
        "CREATE INDEX IF NOT EXISTS {$postmeta_table}_meta_key ON $postmeta_table(meta_key)",
        "CREATE UNIQUE INDEX IF NOT EXISTS {$term_taxonomy_table}_term_id_taxonomy ON $term_taxonomy_table(term_id, taxonomy)",
        "CREATE INDEX IF NOT EXISTS {$term_taxonomy_table}_taxonomy ON $term_taxonomy_table(taxonomy)",
        "CREATE INDEX IF NOT EXISTS {$terms_table}_slug ON $terms_table(slug)",
        "CREATE INDEX IF NOT EXISTS {$users_table}_user_login ON $users_table(user_login)",
        "CREATE INDEX IF NOT EXISTS {$users_table}_user_email ON $users_table(user_email)",
        "CREATE INDEX IF NOT EXISTS {$usermeta_table}_user_id ON $usermeta_table(user_id)",
        "CREATE INDEX IF NOT EXISTS {$usermeta_table}_meta_key ON $usermeta_table(meta_key)"
    );

    $pdo->beginTransaction();
    try {
        orion_installer_exec_all($pdo, $schema);

        $pass_hash = password_hash($admin_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO $users_table (user_login, user_pass, user_nicename, user_email, display_name) VALUES (:l, :p, :n, :e, :d)");
        $stmt->execute(array(
            ':l' => $admin_user,
            ':p' => $pass_hash,
            ':n' => $admin_user,
            ':e' => $admin_email,
            ':d' => 'Administrator'
        ));
        $user_id = (int) $pdo->lastInsertId();
        orion_installer_insert_usermeta($pdo, $usermeta_table, $user_id, 'orion_capabilities', 'a:1:{s:13:"administrator";b:1;}');
        orion_installer_insert_usermeta($pdo, $usermeta_table, $user_id, 'orion_user_level', '10');

        $base_url = orion_installer_base_url();
        orion_installer_insert_option($pdo, $options_table, 'template', $template !== '' ? $template : 'orion-one');
        if (!empty($active_plugins)) {
            orion_installer_insert_option($pdo, $options_table, 'active_plugins', serialize(array_values($active_plugins)));
        }
        orion_installer_insert_option($pdo, $options_table, 'blogname', $site_title !== '' ? $site_title : 'Orion CMS');
        orion_installer_insert_option($pdo, $options_table, 'siteurl', $base_url);

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }

    $database_dir = dirname($db_file);
    $index_path = rtrim($database_dir, '/') . '/index.html';
    if (!file_exists($index_path)) @file_put_contents($index_path, '');

    $config_path = ABSPATH . 'orion-config.php';
    if (file_exists($config_path)) {
        $bak = ABSPATH . 'orion-config.php.bak_' . date('Ymd_His');
        @copy($config_path, $bak);
    }

    $db_file_export = str_replace('\\', '/', $db_file);
    $prefix_export = addslashes($prefix);
    $config = "<?php\n";
    $config .= "define('DB_TYPE', 'sqlite');\n";
    $config .= "define('DB_FILE', '" . addslashes($db_file_export) . "');\n";
    $config .= "define('DB_NAME', '');\n";
    $config .= "define('DB_USER', '');\n";
    $config .= "define('DB_PASSWORD', '');\n";
    $config .= "define('DB_HOST', '');\n";
    $config .= "\$table_prefix = '" . $prefix_export . "';\n";
    $config .= "define('ORION_DEBUG', true);\n";
    $config .= "if ( ! defined('ABSPATH') ) define('ABSPATH', '" . addslashes(ABSPATH) . "');\n";
    $config .= "require_once ABSPATH . 'orion-settings.php';\n";

    if (@file_put_contents($config_path, $config) === false) {
        throw new RuntimeException('Gagal menulis orion-config.php. Pastikan folder root dapat ditulis.');
    }

    return array(
        'db_file' => $db_file,
        'admin_user' => $admin_user,
        'admin_email' => $admin_email,
        'base_url' => $base_url
    );
}

$errors = array();
$success = null;
$installed = file_exists(ABSPATH . 'orion-config.php');

// A populated database also means the site is in use. Deleting the config file
// must not silently reopen the installer to the whole network.
if (!$installed) {
    $default_db = ABSPATH . 'orion-content/database/orion.sqlite';
    if (is_file($default_db) && filesize($default_db) > 0) {
        $installed = true;
    }
}
$force = isset($_GET['force']) && $_GET['force'] === '1';

// Reinstalling overwrites orion-config.php and the database, so it must never
// be reachable by a remote visitor. Only the machine hosting the installer may
// force a reinstall.
if ($force && !orion_installer_is_local_request()) {
    $force = false;
}
$packages = orion_installer_load_packages();

$form = array(
    'site_title' => 'Orion CMS',
    'admin_user' => 'admin',
    'admin_email' => 'admin@example.com',
    'admin_pass' => '',
    'admin_pass2' => '',
    'table_prefix' => 'orion_',
    'db_path' => 'orion-content/database/orion.sqlite',
    'package' => '',
    'template' => 'orion-one',
    'active_plugins' => array()
);

if (!empty($packages)) {
    $first = array_key_first($packages);
    if ($first) $form['package'] = $first;
}

$presets_file = ABSPATH . 'installer/presets.json';
if (is_file($presets_file)) {
    $raw = @file_get_contents($presets_file);
    $json = $raw !== false ? json_decode($raw, true) : null;
    if (is_array($json)) {
        $keys = array('site_title', 'admin_user', 'admin_email', 'table_prefix', 'db_path', 'package');
        foreach ($keys as $k) {
            if (isset($json[$k]) && is_string($json[$k]) && trim($json[$k]) !== '') {
                $form[$k] = (string) $json[$k];
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $k => $v) {
        if (isset($_POST[$k])) $form[$k] = (string) $_POST[$k];
    }

    if (!extension_loaded('pdo_sqlite')) $errors['global'] = 'Extensi pdo_sqlite tidak tersedia.';
    if ($installed && !$force) $errors['global'] = 'Orion sudah terpasang. Tambahkan ?force=1 untuk reinstall.';
    if (trim($form['admin_user']) === '') $errors['admin_user'] = 'Username admin wajib diisi.';
    if (strlen((string) $form['admin_pass']) < 6) $errors['admin_pass'] = 'Password minimal 6 karakter.';
    if ((string) $form['admin_pass'] !== (string) $form['admin_pass2']) $errors['admin_pass2'] = 'Konfirmasi password tidak sama.';
    if (trim($form['admin_email']) !== '' && !filter_var(trim($form['admin_email']), FILTER_VALIDATE_EMAIL)) $errors['admin_email'] = 'Email tidak valid.';

    if (!empty($packages)) {
        $pkg = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $form['package']);
        if ($pkg === '' || !isset($packages[$pkg])) {
            $errors['package'] = 'Paket wajib dipilih.';
        } else {
            $form['template'] = $packages[$pkg]['template'];
            $form['active_plugins'] = $packages[$pkg]['plugins'];
        }
    } else {
        $form['template'] = 'orion-one';
        $form['active_plugins'] = array();
    }

    if (empty($errors)) {
        try {
            $success = orion_installer_run($form);
        } catch (Throwable $e) {
            $errors['global'] = $e->getMessage();
        }
    }
}

$base_url = orion_installer_base_url();
$admin_url = $base_url . '/orion-admin/';
$home_url = $base_url . '/';

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orion Installer (SQLite)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="max-w-3xl mx-auto p-6">
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-extrabold shadow-sm">
                <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                Orion Installer
            </div>
            <h1 class="mt-5 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Install Orion CMS dengan SQLite</h1>
            <p class="mt-3 text-slate-600">Installer ini membuat database SQLite dan menulis <span class="font-mono text-slate-900">orion-config.php</span>.</p>
        </div>

        <?php if (isset($errors['global'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 font-extrabold"><?php echo htmlspecialchars($errors['global']); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-8 bg-white border border-emerald-200 rounded-3xl p-7 shadow-sm">
                <div class="text-xs font-extrabold uppercase tracking-widest text-emerald-700">Berhasil</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">Instalasi selesai</div>
                <div class="mt-4 text-slate-600">
                    <div>DB: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($success['db_file']); ?></span></div>
                    <div>Admin: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($success['admin_user']); ?></span></div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo htmlspecialchars($admin_url); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition">Masuk Admin</a>
                    <a href="<?php echo htmlspecialchars($home_url); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Buka Website</a>
                </div>
            </div>
        <?php elseif ($installed && !$force): ?>
            <div class="mb-8 bg-white border border-slate-200 rounded-3xl p-7 shadow-sm">
                <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Info</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">Orion sudah terpasang</div>
                <div class="mt-4 text-slate-600">Untuk reinstall, tambahkan <span class="font-mono text-slate-900">?force=1</span> pada URL installer.</div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo htmlspecialchars($admin_url); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition">Masuk Admin</a>
                    <a href="<?php echo htmlspecialchars($home_url); ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Buka Website</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$success && !($installed && !$force)): ?>
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-900/5 overflow-hidden">
            <div class="p-7 md:p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Konfigurasi</div>
                <div class="mt-2 text-xl font-extrabold text-slate-900">Data Instalasi</div>
            </div>

            <div class="p-7 md:p-8">
                <form method="POST" class="space-y-6">
                    <?php if (!empty($packages)): ?>
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <div class="text-sm font-extrabold text-slate-900">Pilih Paket</div>
                            <div class="mt-2 text-sm text-slate-600">Paket menentukan tema dan plugin yang aktif setelah instalasi.</div>
                            <?php if (isset($errors['package'])): ?><div class="mt-3 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['package']); ?></div><?php endif; ?>
                            <div class="mt-4 grid gap-3">
                                <?php foreach ($packages as $pkg): ?>
                                    <label class="flex items-start gap-3 p-4 rounded-2xl border <?php echo ((string) $form['package'] === (string) $pkg['slug']) ? 'border-emerald-300 bg-white' : 'border-slate-200 bg-white hover:bg-slate-50'; ?> cursor-pointer">
                                        <input type="radio" name="package" value="<?php echo htmlspecialchars($pkg['slug']); ?>" <?php echo ((string) $form['package'] === (string) $pkg['slug']) ? 'checked' : ''; ?> class="mt-1">
                                        <span class="block">
                                            <span class="block font-extrabold text-slate-900"><?php echo htmlspecialchars($pkg['name']); ?></span>
                                            <?php if (trim((string) $pkg['description']) !== ''): ?>
                                                <span class="block mt-1 text-sm text-slate-600"><?php echo htmlspecialchars($pkg['description']); ?></span>
                                            <?php endif; ?>
                                            <span class="block mt-2 text-xs text-slate-500 font-bold">Tema: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($pkg['template']); ?></span></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Nama Website</label>
                        <input name="site_title" value="<?php echo htmlspecialchars($form['site_title']); ?>" class="w-full p-3.5 rounded-2xl border border-slate-200 bg-white focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Admin Username</label>
                            <input name="admin_user" value="<?php echo htmlspecialchars($form['admin_user']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['admin_user']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                            <?php if (isset($errors['admin_user'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['admin_user']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Admin Email</label>
                            <input name="admin_email" value="<?php echo htmlspecialchars($form['admin_email']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['admin_email']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                            <?php if (isset($errors['admin_email'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['admin_email']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Password</label>
                            <input type="password" name="admin_pass" value="<?php echo htmlspecialchars($form['admin_pass']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['admin_pass']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                            <?php if (isset($errors['admin_pass'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['admin_pass']); ?></div><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Konfirmasi Password</label>
                            <input type="password" name="admin_pass2" value="<?php echo htmlspecialchars($form['admin_pass2']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['admin_pass2']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                            <?php if (isset($errors['admin_pass2'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['admin_pass2']); ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">Table Prefix</label>
                            <input name="table_prefix" value="<?php echo htmlspecialchars($form['table_prefix']); ?>" class="w-full p-3.5 rounded-2xl border border-slate-200 bg-white font-mono text-sm focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">SQLite DB Path (relative)</label>
                            <input name="db_path" value="<?php echo htmlspecialchars($form['db_path']); ?>" class="w-full p-3.5 rounded-2xl border border-slate-200 bg-white font-mono text-xs focus:outline-none focus:ring-4 focus:ring-emerald-200/50 focus:border-emerald-400">
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="text-sm font-extrabold text-slate-900">Catatan</div>
                        <div class="mt-2 text-sm text-slate-600 leading-relaxed">
                            Instalasi ini menggunakan SQLite dan akan mengaktifkan tema sesuai paket yang Anda pilih.
                        </div>
                        <?php if ($installed && !$force): ?>
                            <div class="mt-3 text-sm text-amber-700 font-extrabold">orion-config.php sudah ada. Gunakan <span class="font-mono">?force=1</span> untuk reinstall.</div>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between pt-2">
                        <div class="text-xs text-slate-500">Base URL terdeteksi: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($base_url); ?></span></div>
                        <button class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold transition shadow-xl shadow-emerald-500/20">
                            Install
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
