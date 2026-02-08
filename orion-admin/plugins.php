<?php
require_once '../orion-load.php';

$message = '';
$error = '';

// Handle Actions
if (isset($_GET['action']) && isset($_GET['plugin'])) {
    $plugin = $_GET['plugin'];
    $active_plugins = get_option('active_plugins', array());
    if (!is_array($active_plugins)) $active_plugins = array();
    
    if ($_GET['action'] == 'activate') {
        if (!in_array($plugin, $active_plugins)) {
            $active_plugins[] = $plugin;
            update_option('active_plugins', $active_plugins);
        }
    } elseif ($_GET['action'] == 'deactivate') {
        $key = array_search($plugin, $active_plugins);
        if ($key !== false) {
            unset($active_plugins[$key]);
            update_option('active_plugins', array_values($active_plugins));
        }
    }
    
    header("Location: plugins.php");
    exit;
}

// Handle Plugin Upload
if (isset($_POST['upload_plugin']) && !empty($_FILES['plugin_zip'])) {
    $file = $_FILES['plugin_zip'];
    $plugins_dir = ABSPATH . 'orion-content/plugins/';
    
    // Validate file extension
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($file_ext === 'zip') {
        $zip = new ZipArchive;
        if ($zip->open($file['tmp_name']) === TRUE) {
            // Create a temporary directory for extraction
            $temp_dir = $plugins_dir . 'temp_' . uniqid() . '/';
            if (!file_exists($temp_dir)) {
                mkdir($temp_dir, 0755, true);
            }
            
            $zip->extractTo($temp_dir);
            $zip->close();
            
            // Analyze the extracted content
            $extracted_files = scandir($temp_dir);
            $extracted_files = array_diff($extracted_files, array('.', '..'));
            
            $final_source = '';
            $final_dest = '';
            
            // Case 1: The zip contains a single folder (Standard WP Plugin)
            if (count($extracted_files) == 1 && is_dir($temp_dir . reset($extracted_files))) {
                $folder_name = reset($extracted_files);
                $final_source = $temp_dir . $folder_name;
                $final_dest = $plugins_dir . $folder_name;
            } 
            // Case 2: The zip contains loose files or multiple folders
            else {
                $folder_name = pathinfo($file['name'], PATHINFO_FILENAME);
                $final_source = $temp_dir;
                $final_dest = $plugins_dir . $folder_name;
            }
            
            // Move to final destination
            if (file_exists($final_dest)) {
                $error = "A plugin with the same folder name ($folder_name) already exists.";
                // Cleanup temp
                // Recursive delete function for temp dir
                _delete_recursive($temp_dir);
            } else {
                if (rename($final_source, $final_dest)) {
                    $message = "Plugin installed successfully.";
                    // Cleanup temp if it was Case 1 (source was moved, but temp dir remains empty)
                    // If Case 2, source was temp_dir itself, but rename handles it? 
                    // rename() on directory works.
                    // If Case 1: $temp_dir still exists and is empty.
                    if (is_dir($temp_dir)) {
                        @rmdir($temp_dir);
                    }
                } else {
                    $error = "Failed to move plugin files.";
                    _delete_recursive($temp_dir);
                }
            }
            
        } else {
            $error = "Failed to open ZIP file.";
        }
    } else {
        $error = "Please upload a valid ZIP file.";
    }
}

// Helper to delete directory recursively
function _delete_recursive($dir) {
    if (!is_dir($dir)) return;
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? _delete_recursive("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

require_once 'admin-header.php';

// Get Plugins
$plugins_dir = ABSPATH . 'orion-content/plugins/';
$plugins = array();

// Helper to parse header
function get_plugin_data($file) {
    $content = file_get_contents($file, false, null, 0, 8192); // Read first 8kb
    $data = array(
        'Name' => '',
        'Description' => '',
        'Version' => '',
        'Author' => '',
    );
    
    if (preg_match('/Plugin Name:(.*)$/mi', $content, $matches)) $data['Name'] = trim($matches[1]);
    if (preg_match('/Description:(.*)$/mi', $content, $matches)) $data['Description'] = trim($matches[1]);
    if (preg_match('/Version:(.*)$/mi', $content, $matches)) $data['Version'] = trim($matches[1]);
    if (preg_match('/Author:(.*)$/mi', $content, $matches)) $data['Author'] = trim($matches[1]);
    
    return $data;
}

// Scan dir
$files = glob($plugins_dir . '*.php');
if ($files) {
    foreach ($files as $file) {
        $data = get_plugin_data($file);
        if (!empty($data['Name'])) {
            $plugins[basename($file)] = $data;
        }
    }
}

// Scan subdirs (1 level)
$dirs = glob($plugins_dir . '*', GLOB_ONLYDIR);
if ($dirs) {
    foreach ($dirs as $dir) {
        $files = glob($dir . '/*.php');
        if ($files) {
            foreach ($files as $file) {
                $data = get_plugin_data($file);
                if (!empty($data['Name'])) {
                    $plugins[basename($dir) . '/' . basename($file)] = $data;
                }
            }
        }
    }
}

$active_plugins = get_option('active_plugins', array());
if (!is_array($active_plugins)) $active_plugins = array();

// Count stats
$total_plugins = count($plugins);
$active_count = count($active_plugins);
$inactive_count = $total_plugins - $active_count;
?>

<!-- Header Section -->
<div class="mb-8 animate-fade-in-down">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Plugins Manager</h1>
            <p class="text-slate-500 text-sm mt-1">Extend functionality with powerful plugins</p>
        </div>
        
        <!-- Stats Badges -->
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-slate-200 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                <span class="text-xs font-semibold text-slate-600">Total: <?php echo $total_plugins; ?></span>
            </div>
            <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-slate-200 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <span class="text-xs font-semibold text-slate-600">Active: <?php echo $active_count; ?></span>
            </div>
        </div>
    </div>
</div>

<?php if($message): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative mb-6 flex items-center shadow-sm animate-fade-in-up" role="alert">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="block sm:inline font-medium"><?php echo $message; ?></span>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-6 flex items-center shadow-sm animate-fade-in-up" role="alert">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="block sm:inline font-medium"><?php echo $error; ?></span>
</div>
<?php endif; ?>

<!-- Upload Section -->
<div x-data="{ isDragging: false }" 
     class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8 transition-all duration-300 hover:shadow-md">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <div class="bg-blue-50 p-4 rounded-full text-blue-600 shrink-0">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
        </div>
        <div class="flex-grow w-full">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Upload New Plugin</h2>
            <p class="text-slate-500 text-sm mb-4">Upload a .zip file to install a new plugin.</p>
            
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow group">
                    <input class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                        file:cursor-pointer file:transition-colors
                        border border-slate-200 rounded-lg cursor-pointer bg-slate-50/50
                        focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all" 
                        id="plugin_zip" name="plugin_zip" type="file" accept=".zip" required>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap" type="submit" name="upload_plugin">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Install Now
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Plugins Grid -->
<?php if (empty($plugins)): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="bg-slate-50 p-4 rounded-full inline-block mb-4">
            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-lg font-medium text-slate-900 mb-2">No plugins installed</h3>
        <p class="text-slate-500">Upload your first plugin using the form above.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($plugins as $path => $plugin): ?>
        <?php 
            $is_active = in_array($path, $active_plugins); 
            $border_class = $is_active ? 'border-orion-600 ring-2 ring-orion-600' : 'border-slate-200';
        ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border <?php echo $border_class; ?> hover:shadow-md transition-shadow">
            <div class="h-48 relative overflow-hidden group">
                <!-- Background -->
                <div class="absolute inset-0 bg-indigo-50 flex items-center justify-center overflow-hidden">
                    <!-- Large Faded Background Icon -->
                    <div class="absolute -right-4 -bottom-8 text-indigo-200 opacity-20 transform rotate-12">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    </div>
                    <!-- Foreground Icon -->
                    <div class="relative z-10 w-24 h-24 bg-white rounded-full flex items-center justify-center text-indigo-600 shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    </div>
                </div>

                <?php if ($is_active): ?>
                    <div class="absolute top-2 right-2 z-10">
                        <span class="bg-orion-600 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">Active</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="p-5">
                <div class="mb-3">
                    <h3 class="text-lg font-bold text-slate-800 truncate" title="<?php echo htmlspecialchars($plugin['Name']); ?>"><?php echo htmlspecialchars($plugin['Name']); ?></h3>
                    <div class="text-xs text-slate-500 mt-1">
                        v<?php echo htmlspecialchars($plugin['Version']); ?> by <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($plugin['Author']); ?></span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 mb-5 h-10 overflow-hidden leading-relaxed line-clamp-2"><?php echo htmlspecialchars($plugin['Description']); ?></p>
                
                <div class="flex justify-between items-center pt-4 border-t border-slate-100 gap-3">
                    <?php if ($is_active): ?>
                        <a href="plugins.php?action=deactivate&plugin=<?php echo urlencode($path); ?>" class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-medium transition text-center">
                            Deactivate
                        </a>
                    <?php else: ?>
                        <a href="plugins.php?action=activate&plugin=<?php echo urlencode($path); ?>" class="w-full bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-orion-600 px-3 py-2 rounded-lg text-sm font-medium transition text-center">
                            Activate
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'admin-footer.php'; ?>
