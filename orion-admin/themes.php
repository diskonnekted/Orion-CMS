<?php
require_once '../orion-load.php';

// Helper function to recursively delete a directory
function delete_theme_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!delete_theme_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

function sanitize_theme_slug($value) {
    $value = strtolower((string) $value);
    $value = preg_replace('/[^a-z0-9_-]/', '-', $value);
    $value = preg_replace('/-+/', '-', $value);
    $value = trim($value, '-');
    return $value;
}

function copy_theme_directory($src, $dst) {
    if (!is_dir($src)) {
        return false;
    }
    if (!file_exists($dst)) {
        if (!mkdir($dst, 0755, true)) {
            return false;
        }
    }
    $items = scandir($src);
    if (!is_array($items)) {
        return false;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $from = $src . DIRECTORY_SEPARATOR . $item;
        $to = $dst . DIRECTORY_SEPARATOR . $item;
        if (is_dir($from)) {
            if (!copy_theme_directory($from, $to)) return false;
        } else {
            if (!copy($from, $to)) return false;
        }
    }
    return true;
}

function find_theme_root_dir($base_dir) {
    $base_dir = rtrim((string) $base_dir, DIRECTORY_SEPARATOR);
    if ($base_dir === '' || !is_dir($base_dir)) return '';

    $root_style = $base_dir . DIRECTORY_SEPARATOR . 'style.css';
    if (file_exists($root_style)) {
        $root_index = $base_dir . DIRECTORY_SEPARATOR . 'index.php';
        if (file_exists($root_index)) return $base_dir;
    }

    $candidates = array();
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($base_dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($it as $fi) {
        if (!$fi->isDir()) continue;
        $dir = $fi->getPathname();
        $style = $dir . DIRECTORY_SEPARATOR . 'style.css';
        if (!file_exists($style)) continue;
        $index = $dir . DIRECTORY_SEPARATOR . 'index.php';
        $depth = substr_count(str_replace('\\', '/', $dir), '/') - substr_count(str_replace('\\', '/', $base_dir), '/');
        $has_index = file_exists($index);
        $candidates[] = array('dir' => $dir, 'depth' => $depth, 'has_index' => $has_index);
    }

    if (empty($candidates)) return '';

    usort($candidates, function ($a, $b) {
        if ($a['has_index'] !== $b['has_index']) return $a['has_index'] ? -1 : 1;
        if ($a['depth'] !== $b['depth']) return $a['depth'] - $b['depth'];
        return strcmp((string) $a['dir'], (string) $b['dir']);
    });

    return (string) $candidates[0]['dir'];
}

// Get current theme
$current_theme = get_option('template', 'orion-default');

// Handle Actions
if (isset($_POST['upload_theme']) && isset($_FILES['theme_zip'])) {
    // CSRF protection
    if (!isset($_POST['orion_nonce']) || !orion_verify_nonce($_POST['orion_nonce'], 'orion_theme_upload')) {
        header("Location: themes.php?error=bad_nonce");
        exit;
    }

    $file = $_FILES['theme_zip'];
    
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header("Location: themes.php?error=upload_failed");
        exit;
    }
    
    // Check file type
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'zip') {
        header("Location: themes.php?error=invalid_file_type");
        exit;
    }
    
    $themes_root = ABSPATH . 'orion-content/themes/';
    $base_slug = sanitize_theme_slug(pathinfo($file['name'], PATHINFO_FILENAME));
    if ($base_slug === '') $base_slug = 'theme';

    $tmp_dir = ABSPATH . 'tmp/theme-install-' . time() . '-' . mt_rand(1000, 9999);
    if (!file_exists(dirname($tmp_dir))) {
        @mkdir(dirname($tmp_dir), 0755, true);
    }
    if (!mkdir($tmp_dir, 0755, true)) {
        header("Location: themes.php?error=tmp_failed");
        exit;
    }

    $zip = new ZipArchive;
    if ($zip->open($file['tmp_name']) !== TRUE) {
        delete_theme_directory($tmp_dir);
        header("Location: themes.php?error=unzip_failed");
        exit;
    }
    if (!orion_zip_entries_are_safe($zip)) {
        $zip->close();
        delete_theme_directory($tmp_dir);
        header("Location: themes.php?error=unzip_failed");
        exit;
    }
    $zip->extractTo($tmp_dir);
    $zip->close();

    $src_theme_dir = find_theme_root_dir($tmp_dir);
    if ($src_theme_dir === '') {
        delete_theme_directory($tmp_dir);
        header("Location: themes.php?error=no_theme_found");
        exit;
    }

    $dest_slug = sanitize_theme_slug(basename($src_theme_dir));
    $src_real = realpath($src_theme_dir);
    $tmp_real = realpath($tmp_dir);
    if (($src_real && $tmp_real && $src_real === $tmp_real) || strpos($dest_slug, 'theme-install-') === 0) {
        $dest_slug = $base_slug;
    }
    if ($dest_slug === '' || $dest_slug === 'tmp') {
        $dest_slug = $base_slug;
    }

    $dest_dir = $themes_root . $dest_slug;
    if (file_exists($dest_dir)) {
        delete_theme_directory($tmp_dir);
        header("Location: themes.php?error=theme_exists&theme=" . urlencode($dest_slug));
        exit;
    }

    if (!copy_theme_directory($src_theme_dir, $dest_dir)) {
        delete_theme_directory($tmp_dir);
        header("Location: themes.php?error=install_failed");
        exit;
    }

    delete_theme_directory($tmp_dir);
    header("Location: themes.php?uploaded=true&theme=" . urlencode($dest_slug));
    exit;
}

if (isset($_GET['action']) && isset($_GET['theme'])) {
    // CSRF protection
    if (!isset($_GET['orion_nonce']) || !orion_verify_nonce($_GET['orion_nonce'], 'orion_theme_action')) {
        header("Location: themes.php?error=bad_nonce");
        exit;
    }

    $theme = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['theme']);
    $theme_dir = ABSPATH . 'orion-content/themes/' . $theme;

    // Activation
    if ($_GET['action'] == 'activate') {
        if (file_exists($theme_dir)) {
            update_option('template', $theme);
            header("Location: themes.php?activated=true");
            exit;
        }
    }
    
    // Deletion
    if ($_GET['action'] == 'delete') {
        // Security check: cannot delete active theme
        if ($theme == $current_theme) {
            header("Location: themes.php?error=active_theme");
            exit;
        }

        if (file_exists($theme_dir)) {
            if (delete_theme_directory($theme_dir)) {
                header("Location: themes.php?deleted=true");
                exit;
            } else {
                header("Location: themes.php?error=delete_failed");
                exit;
            }
        }
    }
}

// Scan themes directory
$themes_dir = ABSPATH . 'orion-content/themes/';
$themes = array();
if (is_dir($themes_dir)) {
    if ($dh = opendir($themes_dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && is_dir($themes_dir . $file)) {
                // Read style.css
                $style_css = $themes_dir . $file . '/style.css';
                if (file_exists($style_css)) {
                    $css_data = file_get_contents($style_css);
                    preg_match('/Theme Name:(.*)$/m', $css_data, $name_match);
                    preg_match('/Description:(.*)$/m', $css_data, $desc_match);
                    preg_match('/Author:(.*)$/m', $css_data, $author_match);
                    preg_match('/Version:(.*)$/m', $css_data, $version_match);
                    
                    $screenshot = 'https://via.placeholder.com/600x400?text=' . $file;
                    $screenshot_files = array('screenshot.png', 'screenshot.jpg', 'screenshot.jpeg', 'screenshot.JPG', 'screenshot.JPEG');
                    
                    foreach ($screenshot_files as $sf) {
                        if (file_exists($themes_dir . $file . '/' . $sf)) {
                            $screenshot = site_url('/orion-content/themes/' . $file . '/' . $sf);
                            break;
                        }
                    }

                    $theme_card_dir = ABSPATH . 'assets/img/theme-card/';
                    $theme_card_url = site_url('/assets/img/theme-card/');
                    if (is_dir($theme_card_dir)) {
                        $matches = glob($theme_card_dir . $file . '.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
                        if (is_array($matches) && !empty($matches)) {
                            $screenshot = $theme_card_url . rawurlencode(basename($matches[0]));
                        }
                    }
                    
                    // If still placeholder, try to find any image in the theme root (e.g. construction.jpg, garahe.jpg)
                    if (strpos($screenshot, 'via.placeholder.com') !== false) {
                        $root_files = scandir($themes_dir . $file);
                        foreach ($root_files as $rf) {
                            if ($rf == '.' || $rf == '..') continue;
                            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $rf) && !preg_match('/(favicon|logo)\./i', $rf)) {
                                $screenshot = site_url('/orion-content/themes/' . $file . '/' . $rf);
                                break;
                            }
                        }
                    }

                    $themes[$file] = array(
                        'name' => isset($name_match[1]) ? trim($name_match[1]) : $file,
                        'description' => isset($desc_match[1]) ? trim($desc_match[1]) : '',
                        'author' => isset($author_match[1]) ? trim($author_match[1]) : '',
                        'version' => isset($version_match[1]) ? trim($version_match[1]) : '',
                        'screenshot' => $screenshot
                    );
                }
            }
        }
        closedir($dh);
    }
}

require_once 'theme-details.php';
$theme_detail_data = orion_admin_theme_details_data();
$theme_plugins_catalog = isset($theme_detail_data['_plugins_catalog']) ? $theme_detail_data['_plugins_catalog'] : array();

include 'admin-header.php';
?>

<div x-data="{ showUpload: false, detailOpen: false, detailSlug: '', openDetail(slug){ this.detailSlug = slug; this.detailOpen = true; }, closeDetail(){ this.detailOpen = false; } }">
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Themes</h1>
    <div class="relative">
        <button @click="showUpload = !showUpload" class="bg-orion-600 hover:bg-orion-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Theme
        </button>
        
        <!-- Upload Modal/Area -->
        <div x-show="showUpload" @click.away="showUpload = false" class="absolute top-12 right-0 z-50 w-80 bg-white rounded-xl shadow-xl border border-slate-200 p-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Upload Theme</h3>
            <form action="themes.php" method="POST" enctype="multipart/form-data">
            <?php echo orion_field_nonce('orion_theme_upload'); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Zip File</label>
                    <input type="file" name="theme_zip" accept=".zip" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orion-50 file:text-orion-700 hover:file:bg-orion-100">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showUpload = false" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" name="upload_theme" class="bg-orion-600 hover:bg-orion-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Install Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['activated'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Success</p>
                <p class="text-sm">Theme activated successfully.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Success</p>
                <p class="text-sm">Theme deleted successfully.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Error</p>
                <p class="text-sm">
                    <?php 
                    if ($_GET['error'] == 'active_theme') echo 'Cannot delete the active theme.';
                    else if ($_GET['error'] == 'delete_failed') echo 'Failed to delete theme files. Check permissions.';
                    else if ($_GET['error'] == 'upload_failed') echo 'Upload failed. Please try again.';
                    else if ($_GET['error'] == 'invalid_file_type') echo 'Invalid file type. Please upload a ZIP.';
                    else if ($_GET['error'] == 'unzip_failed') echo 'Failed to extract ZIP. Ensure the ZIP is valid.';
                    else if ($_GET['error'] == 'tmp_failed') echo 'Failed to create temporary directory.';
                    else if ($_GET['error'] == 'no_theme_found') echo 'Theme not found in ZIP. Make sure it contains style.css.';
                    else if ($_GET['error'] == 'theme_exists') echo 'Theme folder already exists. Delete it first or rename the ZIP.';
                    else if ($_GET['error'] == 'install_failed') echo 'Failed to install theme files. Check permissions.';
                    else echo 'An unknown error occurred.';
                    ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($themes as $slug => $theme): ?>
        <?php
        $detail = isset($theme_detail_data[$slug]) ? $theme_detail_data[$slug] : null;
        $detail_title = $detail && isset($detail['title']) ? $detail['title'] : $theme['name'];
        $detail_summary = $detail && isset($detail['summary']) ? $detail['summary'] : ($theme['description'] ?: 'Tema siap pakai untuk Orion CMS.');
        $detail_highlights = $detail && isset($detail['highlights']) && is_array($detail['highlights']) ? $detail['highlights'] : array();
        $detail_ideal_for = $detail && isset($detail['ideal_for']) && is_array($detail['ideal_for']) ? $detail['ideal_for'] : array();
        $detail_plugins = $detail && isset($detail['plugins']) && is_array($detail['plugins']) ? $detail['plugins'] : array();

        $theme_card_dir = ABSPATH . 'assets/img/theme-card/';
        $theme_card_urls = array();
        if (is_dir($theme_card_dir)) {
            $matches = glob($theme_card_dir . $slug . '.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
            if (is_array($matches)) {
                foreach ($matches as $m) {
                    $theme_card_urls[] = site_url('/assets/img/theme-card/' . rawurlencode(basename($m)));
                }
            }
        }
        if (empty($theme_card_urls)) {
            $theme_card_urls[] = $theme['screenshot'];
        }
        ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border <?php echo ($slug == $current_theme) ? 'border-orion-600 ring-2 ring-orion-600' : 'border-slate-200'; ?> hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="relative overflow-hidden group bg-slate-100 aspect-[4/3]">
                <?php if (strpos($theme['screenshot'], 'via.placeholder.com') === false): ?>
                    <img src="<?php echo $theme['screenshot']; ?>" alt="<?php echo $theme['name']; ?>"
                         class="absolute inset-0 w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-110"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=<?php echo urlencode($theme['name']); ?>';">
                <?php else: ?>
                    <?php if (strpos($slug, 'portfolio') !== false): ?>
                        <div class="absolute inset-0 bg-purple-50 flex items-center justify-center overflow-hidden">
                            <!-- Large Faded Background Icon -->
                            <div class="absolute -right-4 -bottom-8 text-purple-200 opacity-20 transform rotate-12">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <!-- Foreground Icon -->
                            <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-purple-600 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                    <?php elseif (strpos($slug, 'smartvillage') !== false): ?>
                        <div class="absolute inset-0 bg-emerald-100 flex items-center justify-center overflow-hidden">
                            <!-- Large Faded Background Icon -->
                            <div class="absolute -right-4 -bottom-8 text-emerald-200 opacity-20 transform rotate-12">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <!-- Foreground Icon -->
                            <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-emerald-700 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>
                    <?php elseif (strpos($slug, 'magazine') !== false): ?>
                        <div class="absolute inset-0 bg-pink-50 flex items-center justify-center overflow-hidden">
                            <!-- Large Faded Background Icon -->
                            <div class="absolute -right-4 -bottom-8 text-pink-200 opacity-20 transform rotate-12">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <!-- Foreground Icon -->
                            <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-pink-600 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        </div>
                    <?php elseif (strpos($slug, 'one') !== false): ?>
                        <div class="absolute inset-0 bg-blue-50 flex items-center justify-center overflow-hidden">
                            <!-- Large Faded Background Icon -->
                            <div class="absolute -right-4 -bottom-8 text-blue-200 opacity-20 transform rotate-12">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                            </div>
                            <!-- Foreground Icon -->
                            <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="absolute inset-0 bg-emerald-50 flex items-center justify-center overflow-hidden">
                            <!-- Large Faded Background Icon -->
                            <div class="absolute -right-4 -bottom-8 text-emerald-200 opacity-20 transform rotate-12">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <!-- Foreground Icon -->
                            <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-emerald-600 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if ($slug == $current_theme): ?>
                    <div class="absolute top-2 right-2 z-10">
                        <span class="bg-orion-600 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">Active</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <div class="flex-1">
                    <div class="mb-3">
                        <h3 class="text-lg font-bold text-slate-800 leading-snug"><?php echo $theme['name']; ?></h3>
                        <div class="text-xs text-slate-500 mt-1">
                            v<?php echo $theme['version']; ?> by <span class="text-slate-700 font-medium"><?php echo $theme['author']; ?></span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 mb-5 min-h-[2.5rem] max-h-[3.75rem] overflow-hidden leading-relaxed"><?php echo $theme['description']; ?></p>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100 gap-3 mt-auto">
                    <button type="button" @click="openDetail('<?php echo $slug; ?>')" class="flex-1 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-orion-600 px-3 py-2 rounded-lg text-sm font-medium transition text-center">
                        Detail
                    </button>
                    <?php if ($slug != $current_theme): ?>
                        <a href="themes.php?action=activate&theme=<?php echo $slug; ?>&orion_nonce=<?php echo urlencode(orion_create_nonce('orion_theme_action')); ?>" class="flex-1 bg-orion-600 hover:bg-orion-700 text-white px-3 py-2 rounded-lg text-sm font-bold transition text-center">
                            Pasang
                        </a>
                        <a href="themes.php?action=delete&theme=<?php echo $slug; ?>&orion_nonce=<?php echo urlencode(orion_create_nonce('orion_theme_action')); ?>" data-orion-confirm="Are you sure you want to delete this theme? This action cannot be undone." class="flex-none bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center" title="Delete Theme">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <button disabled class="flex-1 bg-slate-100 text-slate-400 px-3 py-2 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Terpasang
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div x-cloak x-show="detailOpen && detailSlug === '<?php echo $slug; ?>'" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-slate-900/60" @click="closeDetail()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden" @click.stop>
                    <div class="flex items-start justify-between gap-4 p-6 border-b border-slate-200">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Detail Tema</div>
                            <div class="mt-2 text-2xl font-extrabold text-slate-900"><?php echo htmlspecialchars($detail_title); ?></div>
                            <div class="mt-2 text-slate-600"><?php echo htmlspecialchars($detail_summary); ?></div>
                        </div>
                        <button class="h-10 w-10 rounded-xl border border-slate-200 hover:bg-slate-50 flex items-center justify-center" @click="closeDetail()">
                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6">
                        <div class="lg:col-span-6">
                            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                                <img src="<?php echo htmlspecialchars($theme_card_urls[0]); ?>" class="w-full h-72 object-cover object-top" alt="<?php echo htmlspecialchars($theme['name']); ?>">
                            </div>
                            <?php if (count($theme_card_urls) > 1): ?>
                                <div class="mt-4 grid grid-cols-4 gap-3">
                                    <?php foreach (array_slice($theme_card_urls, 0, 8) as $img): ?>
                                        <a href="<?php echo htmlspecialchars($img); ?>" target="_blank" rel="noopener" class="rounded-xl overflow-hidden border border-slate-200 bg-white aspect-square">
                                            <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover object-top" alt="Preview">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="lg:col-span-6 space-y-6">
                            <?php if (!empty($detail_highlights)): ?>
                                <div class="rounded-2xl border border-slate-200 p-5 bg-white">
                                    <div class="text-sm font-extrabold text-slate-900 mb-3">Manfaat Utama</div>
                                    <ul class="space-y-2 text-sm text-slate-700">
                                        <?php foreach ($detail_highlights as $h): ?>
                                            <li class="flex gap-2">
                                                <span class="mt-1 h-2 w-2 rounded-full bg-orion-600 flex-shrink-0"></span>
                                                <span><?php echo htmlspecialchars($h); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($detail_ideal_for)): ?>
                                <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                                    <div class="text-sm font-extrabold text-slate-900 mb-3">Cocok Untuk</div>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($detail_ideal_for as $x): ?>
                                            <span class="px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700"><?php echo htmlspecialchars($x); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="rounded-2xl border border-slate-200 p-5 bg-white">
                                <div class="text-sm font-extrabold text-slate-900 mb-3">Plugin Relevan</div>
                                <?php if (empty($detail_plugins)): ?>
                                    <div class="text-sm text-slate-600">Tidak ada rekomendasi khusus untuk tema ini.</div>
                                <?php else: ?>
                                    <div class="space-y-3">
                                        <?php foreach ($detail_plugins as $pid): ?>
                                            <?php $p = isset($theme_plugins_catalog[$pid]) ? $theme_plugins_catalog[$pid] : null; ?>
                                            <?php if ($p): ?>
                                                <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                                                    <div class="font-extrabold text-slate-900"><?php echo htmlspecialchars($p['name']); ?></div>
                                                    <div class="mt-1 text-sm text-slate-600"><?php echo htmlspecialchars($p['desc']); ?></div>
                                                    <?php if (isset($p['links']) && is_array($p['links']) && !empty($p['links'])): ?>
                                                        <div class="mt-3 flex flex-wrap gap-2">
                                                            <?php foreach ($p['links'] as $lnk): ?>
                                                                <?php
                                                                $url = isset($lnk['url']) ? (string) $lnk['url'] : '';
                                                                $label = isset($lnk['label']) ? (string) $lnk['label'] : 'Buka';
                                                                $href = preg_match('#^https?://#', $url) ? $url : $url;
                                                                ?>
                                                                <a href="<?php echo htmlspecialchars($href); ?>" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-extrabold text-slate-700 hover:bg-slate-50"><?php echo htmlspecialchars($label); ?></a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="rounded-2xl border border-slate-200 p-5 bg-white">
                                <div class="text-sm font-extrabold text-slate-900 mb-3">Aksi</div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <?php if ($slug != $current_theme): ?>
                                        <a href="themes.php?action=activate&theme=<?php echo $slug; ?>&orion_nonce=<?php echo urlencode(orion_create_nonce('orion_theme_action')); ?>" class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">
                                            Pasang Tema
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-xl bg-slate-100 text-slate-400 font-extrabold cursor-not-allowed">
                                            Tema Aktif
                                        </button>
                                    <?php endif; ?>
                                    <a href="theme-settings.php" class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-slate-200 font-extrabold text-slate-900 hover:bg-slate-50 transition">
                                        Pengaturan Tema
                                    </a>
                                    <a href="theme-catalog.php?theme=<?php echo $slug; ?>" class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-slate-200 font-extrabold text-slate-900 hover:bg-slate-50 transition">
                                        Edit Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>

<?php include 'admin-footer.php'; ?>
