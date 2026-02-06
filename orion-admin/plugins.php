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
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Plugins</h1>
</div>

<?php if($message): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
  <span class="block sm:inline"><?php echo $message; ?></span>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
  <span class="block sm:inline"><?php echo $error; ?></span>
</div>
<?php endif; ?>

<!-- Add New Plugin Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Import Plugin</h2>
    <form action="" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-end gap-4">
        <div class="flex-grow w-full">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="plugin_zip">
                Upload Plugin (.zip)
            </label>
            <input class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-orion-50 file:text-orion-700
                hover:file:bg-orion-100
                border border-gray-300 rounded cursor-pointer" 
                id="plugin_zip" name="plugin_zip" type="file" accept=".zip" required>
            <p class="text-xs text-gray-500 mt-1">Upload a .zip file compatible with WordPress plugins.</p>
        </div>
        <button class="w-full md:w-auto bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" type="submit" name="upload_plugin">
            Install Now
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plugin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($plugins)): ?>
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No plugins found.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($plugins as $path => $plugin): ?>
                <?php $is_active = in_array($path, $active_plugins); ?>
                <tr class="<?php echo $is_active ? 'bg-blue-50' : ''; ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($plugin['Name']); ?></div>
                        <div class="text-xs text-gray-500">Version <?php echo htmlspecialchars($plugin['Version']); ?> | By <?php echo htmlspecialchars($plugin['Author']); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($plugin['Description']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php if ($is_active): ?>
                            <a href="plugins.php?action=deactivate&plugin=<?php echo urlencode($path); ?>" class="text-red-600 hover:text-red-900">Deactivate</a>
                        <?php else: ?>
                            <a href="plugins.php?action=activate&plugin=<?php echo urlencode($path); ?>" class="text-blue-600 hover:text-blue-900">Activate</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'admin-footer.php'; ?>
