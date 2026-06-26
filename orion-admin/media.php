<?php
/**
 * Media Library
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

// Check capability
if (!current_user_can('administrator')) {
    echo '<div class="p-6 text-red-500">You do not have permission to view this page.</div>';
    require_once( 'admin-footer.php' );
    exit;
}

$upload_dir_rel = 'assets/';
$upload_dir = ABSPATH . $upload_dir_rel;
$upload_url = site_url('/' . $upload_dir_rel);

// Ensure upload directory exists
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Add or update .htaccess to prevent PHP execution in uploads
$htaccess_path = $upload_dir . '.htaccess';
$htaccess_content = "# Prevent PHP execution in uploads directory\n<FilesMatch \".php$|\.php5$|\.php7$|\.phtml$|\.pht$\">\nOrder Allow,Deny\nDeny from all\n</FilesMatch>";
file_put_contents($htaccess_path, $htaccess_content);

$message = '';

// Define allowed file types
$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip');
$allowed_mime_types = array(
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf',
    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'text/plain', 'application/zip'
);

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file']) && isset($_GET['_wpnonce'])) {
    $file_param = $_GET['file'];
    // Use the full path from the parameter (sanitized)
    $file_path = $upload_dir . ltrim($file_param, '/');
    // Make sure the file is inside upload directory
    $real_upload_dir = realpath($upload_dir);
    $real_file_path = realpath($file_path);
    if (orion_verify_nonce($_GET['_wpnonce'], 'delete-file-' . $file_param) && $real_file_path && strpos($real_file_path, $real_upload_dir) === 0) {
        if (file_exists($real_file_path) && is_file($real_file_path)) {
            if (unlink($real_file_path)) {
                $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">File deleted successfully.</div>';
            } else {
                $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Failed to delete file.</div>';
            }
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">File not found.</div>';
        }
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Security check failed.</div>';
    }
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['media_files']) && isset($_POST['_wpnonce'])) {
    if (orion_verify_nonce($_POST['_wpnonce'], 'upload-media')) {
        $count = 0;
        $errors = 0;
        foreach($_FILES['media_files']['name'] as $key => $val) {
            if ($_FILES['media_files']['error'][$key] === UPLOAD_ERR_OK) {
                $file_name_raw = $_FILES['media_files']['name'][$key];
                $file_extension = strtolower(pathinfo($file_name_raw, PATHINFO_EXTENSION));
                $file_tmp = $_FILES['media_files']['tmp_name'][$key];
                
                // Validate extension
                if (in_array($file_extension, $allowed_extensions)) {
                    // Validate MIME type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $file_mime = $finfo->file($file_tmp);
                    if (in_array($file_mime, $allowed_mime_types)) {
                        // Sanitize filename
                        $safe_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $file_name_raw);
                        $target_file = $upload_dir . $safe_filename;
                        
                        if (move_uploaded_file($file_tmp, $target_file)) {
                            $count++;
                        } else {
                            $errors++;
                        }
                    } else {
                        $errors++;
                    }
                } else {
                    $errors++;
                }
            }
        }
        if ($count > 0) {
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">'.$count.' file(s) uploaded successfully.'.($errors>0?' '.$errors.' file(s) failed.':'').'</div>';
        } else if ($errors >0) {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">No files uploaded. Invalid file type(s).</div>';
        }
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Security check failed.</div>';
    }
}

// Function to get all files recursively
function orion_get_all_files($dir, $base_dir, $base_url) {
    $files = array();
    if (!is_dir($dir)) return $files;
    
    $scandir = scandir($dir);
    foreach($scandir as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $full_path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($full_path)) {
            // Recursively get files from subdirectories
            $sub_files = orion_get_all_files($full_path, $base_dir, $base_url);
            $files = array_merge($files, $sub_files);
        } else {
            // Calculate relative path
            $rel_path = str_replace($base_dir, '', $full_path);
            $rel_path = str_replace(DIRECTORY_SEPARATOR, '/', $rel_path);
            
            $files[] = array(
                'name' => $file,
                'path' => $full_path,
                'url' => $base_url . $rel_path,
                'time' => filemtime($full_path),
                'is_image' => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)
            );
        }
    }
    return $files;
}

// Get Files
$files = orion_get_all_files($upload_dir, $upload_dir, $upload_url);

// Sort by time desc
usort($files, function($a, $b) {
    return $b['time'] - $a['time'];
});

require_once( 'admin-header.php' );
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Media Library</h1>
    <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow">
        Add New Media
    </button>
</div>

<?php echo $message; ?>

<!-- File Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
    <?php if (empty($files)): ?>
        <div class="col-span-full text-center py-10 text-gray-500">
            No files found. Upload some!
        </div>
    <?php else: ?>
        <?php foreach($files as $file): ?>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden group relative border hover:border-orion-500 transition">
            <div class="aspect-square bg-gray-100 relative flex items-center justify-center overflow-hidden">
                <?php if ($file['is_image']): ?>
                    <img src="<?php echo $file['url']; ?>" alt="<?php echo $file['name']; ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="text-gray-400 flex flex-col items-center">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="text-xs uppercase font-bold"><?php echo pathinfo($file['name'], PATHINFO_EXTENSION); ?></span>
                    </div>
                <?php endif; ?>
                
                <!-- Overlay Actions -->
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                    <a href="<?php echo esc_url($file['url']); ?>" target="_blank" class="text-white hover:text-orion-300" title="View">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                    <button onclick="copyToClipboard('<?php echo esc_js($file['url']); ?>')" class="text-white hover:text-orion-300" title="Copy URL">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    </button>
                    <a href="?action=delete&file=<?php echo urlencode(str_replace($upload_dir, '', $file['path'])); ?>&_wpnonce=<?php echo urlencode(orion_generate_nonce('delete-file-' . str_replace($upload_dir, '', $file['path']))); ?>" data-orion-confirm="Are you sure you want to delete this file?" class="text-white hover:text-red-400" title="Delete">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </a>
                </div>
            </div>
            <div class="p-2 bg-gray-50 text-xs text-gray-600 truncate text-center border-t">
                <?php echo $file['name']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4">
        <h2 class="text-xl font-bold mb-4">Upload New Media</h2>
        <form method="POST" enctype="multipart/form-data">
            <?php orion_nonce_field('upload-media'); ?>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Files</label>
                <input type="file" name="media_files[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orion-50 file:text-orion-700 hover:file:bg-orion-100">
                <p class="text-xs text-gray-500 mt-2">You can select multiple files. Allowed types: jpg, jpeg, png, gif, webp, pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip.</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('URL copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

<?php require_once( 'admin-footer.php' ); ?>
