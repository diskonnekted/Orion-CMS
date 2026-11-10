<?php
require_once '../orion-load.php';

if (!is_user_logged_in()) {
    header('Location: ' . site_url('/login.php'));
    exit;
}
if (!current_user_can('administrator')) {
    http_response_code(403);
    die('Anda tidak punya akses.');
}

require_once 'theme-details.php';

$themes_dir = ABSPATH . 'orion-content/themes/';
$themes = array();
if (is_dir($themes_dir)) {
    if ($dh = opendir($themes_dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file === '.' || $file === '..') continue;
            if (!is_dir($themes_dir . $file)) continue;
            $style_css = $themes_dir . $file . '/style.css';
            if (!file_exists($style_css)) continue;
            $css_data = file_get_contents($style_css);
            preg_match('/Theme Name:(.*)$/m', $css_data, $name_match);
            $themes[$file] = isset($name_match[1]) ? trim($name_match[1]) : $file;
        }
        closedir($dh);
    }
}
ksort($themes, SORT_NATURAL | SORT_FLAG_CASE);

$path = orion_admin_theme_details_json_path();
$dir = dirname($path);
if (!is_dir($dir)) {
    @mkdir($dir, 0755, true);
}

function orion_admin_theme_catalog_safe_slug($value) {
    $value = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $value);
    return $value;
}

function orion_admin_theme_catalog_read_json($path) {
    if (!file_exists($path)) return array();
    $raw = file_get_contents($path);
    if (!is_string($raw) || trim($raw) === '') return array();
    $json = json_decode($raw, true);
    return is_array($json) ? $json : array();
}

function orion_admin_theme_catalog_write_json($path, $data) {
    $tmp = $path . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (!is_string($json)) return false;
    $ok = file_put_contents($tmp, $json);
    if ($ok === false) return false;
    return rename($tmp, $path);
}

$message = '';
$error = '';

$overrides = orion_admin_theme_catalog_read_json($path);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_theme_details']) && isset($_POST['orion_nonce']) && orion_verify_nonce($_POST['orion_nonce'], 'orion_theme_catalog_save')) {
    $slug = orion_admin_theme_catalog_safe_slug(isset($_POST['theme_slug']) ? $_POST['theme_slug'] : '');
    if ($slug === '' || !isset($themes[$slug])) {
        $error = 'Tema tidak valid.';
    } else {
        $title = isset($_POST['title']) ? trim((string) $_POST['title']) : '';
        $summary = isset($_POST['summary']) ? trim((string) $_POST['summary']) : '';
        $highlights = isset($_POST['highlights']) ? (string) $_POST['highlights'] : '';
        $ideal_for = isset($_POST['ideal_for']) ? (string) $_POST['ideal_for'] : '';
        $plugins = isset($_POST['plugins']) && is_array($_POST['plugins']) ? $_POST['plugins'] : array();
        $screenshot = isset($_POST['screenshot']) ? trim((string) $_POST['screenshot']) : '';

        $plugins_clean = array();
        foreach ($plugins as $p) {
            $p = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $p);
            if ($p !== '') $plugins_clean[] = $p;
        }

        $overrides[$slug] = array(
            'title' => $title,
            'summary' => $summary,
            'highlights' => $highlights,
            'ideal_for' => $ideal_for,
            'plugins' => $plugins_clean,
            'screenshot' => $screenshot
        );

        if ($title === '' && $summary === '' && trim($highlights) === '' && trim($ideal_for) === '' && empty($plugins_clean) && $screenshot === '') {
            unset($overrides[$slug]);
        }

        if (orion_admin_theme_catalog_write_json($path, $overrides)) {
            $message = 'Detail tema tersimpan.';
        } else {
            $error = 'Gagal menyimpan. Pastikan folder uploads bisa ditulis.';
        }
    }
}

$current_theme = get_option('template', 'orion-default');
$selected = isset($_GET['theme']) ? orion_admin_theme_catalog_safe_slug($_GET['theme']) : $current_theme;
if (!isset($themes[$selected])) {
    $selected = $current_theme;
}
if (!isset($themes[$selected]) && !empty($themes)) {
    $keys = array_keys($themes);
    $selected = $keys[0];
}

$base_data = orion_admin_theme_details_data();
$plugins_catalog = isset($base_data['_plugins_catalog']) ? $base_data['_plugins_catalog'] : array();

$current = isset($overrides[$selected]) && is_array($overrides[$selected]) ? $overrides[$selected] : array();
$title_val = isset($current['title']) ? (string) $current['title'] : '';
$summary_val = isset($current['summary']) ? (string) $current['summary'] : '';
$highlights_val = isset($current['highlights']) ? (string) $current['highlights'] : '';
$ideal_for_val = isset($current['ideal_for']) ? (string) $current['ideal_for'] : '';
$plugins_val = isset($current['plugins']) && is_array($current['plugins']) ? $current['plugins'] : array();
$screenshot_val = isset($current['screenshot']) ? (string) $current['screenshot'] : '';

$theme_card_dir = ABSPATH . 'assets/img/theme-card/';
$theme_card_urls = array();
if (is_dir($theme_card_dir) && $selected) {
    $matches = glob($theme_card_dir . $selected . '.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
    if (is_array($matches)) {
        foreach ($matches as $m) {
            $theme_card_urls[] = site_url('/assets/img/theme-card/' . rawurlencode(basename($m)));
        }
    }
}

include 'admin-header.php';
?>

<div class="max-w-5xl">
    <div class="flex items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Theme Catalog</h1>
            <p class="text-slate-500 mt-1">Edit detail tema yang tampil di tombol Detail pada halaman Themes.</p>
        </div>
        <a href="<?php echo site_url('/orion-admin/themes.php'); ?>" class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">Kembali ke Themes</a>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Tema</label>
                <select name="theme" class="w-full p-3 rounded-xl border border-slate-200 bg-white">
                    <?php foreach ($themes as $slug => $name): ?>
                        <option value="<?php echo htmlspecialchars($slug); ?>" <?php echo $slug === $selected ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($name . ' (' . $slug . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button class="w-full px-4 py-3 rounded-xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold">Buka</button>
            </div>
        </form>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5">
                <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                    <?php if (!empty($screenshot_val)): ?>
                        <img src="<?php echo htmlspecialchars($screenshot_val); ?>" class="w-full h-56 object-cover" alt="Preview">
                    <?php elseif (!empty($theme_card_urls)): ?>
                        <img src="<?php echo htmlspecialchars($theme_card_urls[0]); ?>" class="w-full h-56 object-cover" alt="Preview">
                    <?php else: ?>
                        <div class="w-full h-56 flex items-center justify-center text-slate-400 font-bold">Preview tidak tersedia</div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($theme_card_urls)): ?>
                    <div class="mt-4 text-xs text-slate-500">Sumber gambar: <span class="font-mono">/assets/img/theme-card/<?php echo htmlspecialchars($selected); ?>.*</span></div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-7">
                <form method="POST" class="space-y-6">
                <?php echo orion_field_nonce('orion_theme_catalog_save'); ?>
                    <input type="hidden" name="theme_slug" value="<?php echo htmlspecialchars($selected); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Judul</label>
                            <input name="title" value="<?php echo htmlspecialchars($title_val); ?>" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Judul di modal detail">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ringkasan</label>
                            <textarea name="summary" rows="3" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Ringkasan singkat tema"><?php echo htmlspecialchars($summary_val); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Manfaat Utama</label>
                            <textarea name="highlights" rows="6" class="w-full p-3 rounded-xl border border-slate-200 font-mono text-xs" placeholder="1 manfaat per baris"><?php echo htmlspecialchars($highlights_val); ?></textarea>
                            <div class="mt-1 text-xs text-slate-500">Satu manfaat per baris.</div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Cocok Untuk</label>
                            <textarea name="ideal_for" rows="6" class="w-full p-3 rounded-xl border border-slate-200 font-mono text-xs" placeholder="1 item per baris atau dipisah koma"><?php echo htmlspecialchars($ideal_for_val); ?></textarea>
                            <div class="mt-1 text-xs text-slate-500">Boleh dipisah baris atau koma.</div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Plugin Relevan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php foreach ($plugins_catalog as $pid => $p): ?>
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                                        <input type="checkbox" name="plugins[]" value="<?php echo htmlspecialchars($pid); ?>" class="mt-1" <?php echo in_array($pid, $plugins_val, true) ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-sm"><?php echo htmlspecialchars($p['name']); ?></div>
                                            <div class="text-xs text-slate-600 mt-1"><?php echo htmlspecialchars($p['desc']); ?></div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Override Screenshot URL (opsional)</label>
                            <input name="screenshot" value="<?php echo htmlspecialchars($screenshot_val); ?>" class="w-full p-3 rounded-xl border border-slate-200 text-xs font-mono" placeholder="https://...">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-between gap-3">
                        <div class="text-xs text-slate-500">Kosongkan semua field untuk menghapus override dan kembali ke default.</div>
                        <button type="submit" name="save_theme_details" class="px-6 py-3 rounded-xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-6 text-xs text-slate-500">
        File tersimpan di <span class="font-mono">orion-content/uploads/theme-catalog/theme-details.json</span>
    </div>
</div>

<?php include 'admin-footer.php'; ?>

