<?php
/**
 * Theme Settings Page
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

// Get current theme
$current_theme = get_option('template', 'orion-default');
$theme_settings_file = ABSPATH . 'orion-content/themes/' . $current_theme . '/settings.php';
$theme_name_display = ucwords(str_replace(['orion-', '-'], ['', ' '], $current_theme));

// Check if theme has settings
if (!file_exists($theme_settings_file)) {
    wp_die('Current theme does not support settings.');
}

// Handle Form Submission
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_theme_settings'])) {
    // Verify user permissions (basic check)
    if (!current_user_can('administrator')) {
        wp_die('Unauthorized access');
    }

    // Handle File Uploads
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
            if ($file['error'] == 0) {
                $upload_dir = ABSPATH . 'orion-content/uploads/' . date('Y/m');
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $filename = time() . '_' . sanitize_title(basename($file['name'])) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $target_file = $upload_dir . '/' . $filename;
                
                // Basic validation
                $check = getimagesize($file["tmp_name"]);
                if($check !== false) {
                    if (move_uploaded_file($file["tmp_name"], $target_file)) {
                        $file_url = site_url('/orion-content/uploads/' . date('Y/m') . '/' . $filename);
                        update_option($key, $file_url);
                    } else {
                        $error .= "Failed to upload file for $key. ";
                    }
                } else {
                     $error .= "File $key is not an image. ";
                }
            }
        }
    }

    // Save Text Options
    foreach ($_POST as $key => $value) {
        // Skip system fields
        if (in_array($key, ['save_theme_settings', 'submit'])) {
            continue;
        }
        update_option($key, stripslashes($value));
    }

    if (empty($error)) {
        $message = 'Theme settings saved successfully.';
    }
}

require_once( 'admin-header.php' );
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800"><?php echo $theme_name_display; ?> Settings</h1>
    </div>

    <?php if ($message): ?>
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline"><?php echo $message; ?></span>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        
        <?php 
        // Include the theme settings fields
        require_once( $theme_settings_file ); 
        ?>

        <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
            <button type="submit" name="save_theme_settings" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                Save Changes
            </button>
        </div>
    </form>
</div>

<?php
require_once( 'admin-footer.php' );
?>