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

// Get current theme
$current_theme = get_option('template', 'orion-default');

// Handle Actions
if (isset($_POST['upload_theme']) && isset($_FILES['theme_zip'])) {
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
    
    // Process Zip
    $zip = new ZipArchive;
    if ($zip->open($file['tmp_name']) === TRUE) {
        $extract_path = ABSPATH . 'orion-content/themes/';
        $zip->extractTo($extract_path);
        $zip->close();
        header("Location: themes.php?uploaded=true");
        exit;
    } else {
        header("Location: themes.php?error=unzip_failed");
        exit;
    }
}

if (isset($_GET['action']) && isset($_GET['theme'])) {
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
                    
                    $themes[$file] = array(
                        'name' => isset($name_match[1]) ? trim($name_match[1]) : $file,
                        'description' => isset($desc_match[1]) ? trim($desc_match[1]) : '',
                        'author' => isset($author_match[1]) ? trim($author_match[1]) : '',
                        'version' => isset($version_match[1]) ? trim($version_match[1]) : '',
                        'screenshot' => file_exists($themes_dir . $file . '/screenshot.png') ? site_url('/orion-content/themes/' . $file . '/screenshot.png') : 'https://via.placeholder.com/600x400?text=' . $file
                    );
                }
            }
        }
        closedir($dh);
    }
}

include 'admin-header.php';
?>

<div class="flex justify-between items-center mb-6" x-data="{ showUpload: false }">
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
                    else echo 'An unknown error occurred.';
                    ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($themes as $slug => $theme): ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border <?php echo ($slug == $current_theme) ? 'border-orion-600 ring-2 ring-orion-600' : 'border-slate-200'; ?> hover:shadow-md transition-shadow">
            <div class="h-48 relative overflow-hidden group">
                <?php if (strpos($theme['screenshot'], 'via.placeholder.com') === false): ?>
                    <img src="<?php echo $theme['screenshot']; ?>" alt="<?php echo $theme['name']; ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
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
            <div class="p-5">
                <div class="mb-3">
                    <h3 class="text-lg font-bold text-slate-800"><?php echo $theme['name']; ?></h3>
                    <div class="text-xs text-slate-500 mt-1">
                        v<?php echo $theme['version']; ?> by <span class="text-slate-700 font-medium"><?php echo $theme['author']; ?></span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 mb-5 h-10 overflow-hidden leading-relaxed"><?php echo $theme['description']; ?></p>
                
                <div class="flex justify-between items-center pt-4 border-t border-slate-100 gap-3">
                    <?php if ($slug != $current_theme): ?>
                        <a href="themes.php?action=activate&theme=<?php echo $slug; ?>" class="flex-1 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-orion-600 px-3 py-2 rounded-lg text-sm font-medium transition text-center">
                            Activate
                        </a>
                        <a href="themes.php?action=delete&theme=<?php echo $slug; ?>" data-orion-confirm="Are you sure you want to delete this theme? This action cannot be undone." class="flex-none bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center" title="Delete Theme">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <button disabled class="w-full bg-slate-100 text-slate-400 px-3 py-2 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Activated
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'admin-footer.php'; ?>
