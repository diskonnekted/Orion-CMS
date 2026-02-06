<?php
require_once '../orion-load.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Update options
    update_option('blogname', isset($_POST['blogname']) ? trim($_POST['blogname']) : '');
    update_option('blogdescription', isset($_POST['blogdescription']) ? trim($_POST['blogdescription']) : '');
    update_option('site_lang', isset($_POST['site_lang']) ? trim($_POST['site_lang']) : 'en_US');
    update_option('timezone_string', isset($_POST['timezone_string']) ? trim($_POST['timezone_string']) : 'UTC');
    update_option('admin_color_scheme', isset($_POST['admin_color_scheme']) ? trim($_POST['admin_color_scheme']) : 'default');
    
    // SEO Options
    update_option('site_meta_description', isset($_POST['site_meta_description']) ? trim($_POST['site_meta_description']) : '');
    update_option('site_meta_keywords', isset($_POST['site_meta_keywords']) ? trim($_POST['site_meta_keywords']) : '');

    // Logo Upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = dirname(dirname(__FILE__)) . '/assets/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_tmp = $_FILES['site_logo']['tmp_name'];
        $file_name = basename($_FILES['site_logo']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        
        if (in_array($file_ext, $allowed)) {
            $new_file_name = 'logo-' . time() . '.' . $file_ext;
            $target_file = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $target_file)) {
                $logo_url = site_url('/assets/uploads/' . $new_file_name);
                update_option('site_logo', $logo_url);
            } else {
                $error = "Failed to upload logo.";
            }
        } else {
             $error = "Invalid file type. Allowed: jpg, jpeg, png, gif, svg, webp.";
        }
    }

    $message = "Settings saved successfully.";
}

require_once 'admin-header.php';

// Retrieve current options
$blogname = get_option('blogname', 'Orion Site');
$blogdescription = get_option('blogdescription', 'Just another Orion CMS site');
$site_lang = get_option('site_lang', 'en_US');
$timezone_string = get_option('timezone_string', 'UTC');
$admin_color_scheme = get_option('admin_color_scheme', 'default');
$site_meta_description = get_option('site_meta_description', '');
$site_meta_keywords = get_option('site_meta_keywords', '');
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Settings</h1>
</div>

<?php if (isset($message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow overflow-hidden p-6">
    <form method="POST" action="settings.php" enctype="multipart/form-data">
        <div class="grid grid-cols-1 gap-6">
            
            <!-- Logo Settings -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Logo Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Logo</label>
                        <div class="p-4 bg-gray-100 rounded-md flex items-center justify-center border border-gray-200 h-32">
                            <?php 
                            $current_logo = get_option('site_logo', site_url('/assets/img/orion-light.png'));
                            ?>
                            <img src="<?php echo $current_logo; ?>" alt="Site Logo" class="max-h-full max-w-full object-contain">
                        </div>
                    </div>
                    <div>
                         <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-1">Upload New Logo</label>
                         <input type="file" name="site_logo" id="site_logo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 bg-white">
                         <p class="text-xs text-gray-500 mt-1">Allowed formats: PNG, JPG, GIF, SVG, WEBP.</p>
                         <?php if (isset($error)): ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo $error; ?></p>
                         <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">General Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="blogname" class="block text-sm font-medium text-gray-700 mb-1">Site Title</label>
                        <input type="text" name="blogname" id="blogname" value="<?php echo htmlspecialchars($blogname); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        <p class="text-xs text-gray-500 mt-1">The name of your website.</p>
                    </div>
                    
                    <div>
                        <label for="blogdescription" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                        <input type="text" name="blogdescription" id="blogdescription" value="<?php echo htmlspecialchars($blogdescription); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        <p class="text-xs text-gray-500 mt-1">In a few words, explain what this site is about.</p>
                    </div>
                </div>
            </div>

            <!-- Localization -->
            <div class="mt-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Localization</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_lang" class="block text-sm font-medium text-gray-700 mb-1">Site Language</label>
                        <select name="site_lang" id="site_lang" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                            <option value="en_US" <?php echo $site_lang == 'en_US' ? 'selected' : ''; ?>>English (United States)</option>
                            <option value="id_ID" <?php echo $site_lang == 'id_ID' ? 'selected' : ''; ?>>Bahasa Indonesia</option>
                            <!-- Add more languages as needed -->
                        </select>
                    </div>
                    
                    <div>
                        <label for="timezone_string" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select name="timezone_string" id="timezone_string" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                            <option value="UTC" <?php echo $timezone_string == 'UTC' ? 'selected' : ''; ?>>UTC</option>
                            <option value="Asia/Jakarta" <?php echo $timezone_string == 'Asia/Jakarta' ? 'selected' : ''; ?>>Asia/Jakarta</option>
                            <!-- Add more timezones as needed -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Appearance Settings -->
            <div class="mt-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Appearance Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin_color_scheme" class="block text-sm font-medium text-gray-700 mb-1">Admin Color Scheme</label>
                        <select name="admin_color_scheme" id="admin_color_scheme" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                            <option value="default" <?php echo $admin_color_scheme == 'default' ? 'selected' : ''; ?>>Default (Orion Blue)</option>
                            <option value="olive_leaf" <?php echo $admin_color_scheme == 'olive_leaf' ? 'selected' : ''; ?>>Olive Leaf (Green)</option>
                            <option value="molten_lava" <?php echo $admin_color_scheme == 'molten_lava' ? 'selected' : ''; ?>>Molten Lava (Red)</option>
                            <option value="deep_space_blue" <?php echo $admin_color_scheme == 'deep_space_blue' ? 'selected' : ''; ?>>Deep Space Blue</option>
                            <option value="cornsilk" <?php echo $admin_color_scheme == 'cornsilk' ? 'selected' : ''; ?>>Cornsilk (Yellow)</option>
                            <option value="thistle" <?php echo $admin_color_scheme == 'thistle' ? 'selected' : ''; ?>>Thistle (Purple)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select the color scheme for the admin dashboard.</p>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="mt-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">SEO Settings</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="site_meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="site_meta_description" id="site_meta_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($site_meta_description); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">A brief description of your site for search engines.</p>
                    </div>
                    
                    <div>
                        <label for="site_meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                        <input type="text" name="site_meta_keywords" id="site_meta_keywords" value="<?php echo htmlspecialchars($site_meta_keywords); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        <p class="text-xs text-gray-500 mt-1">Separate keywords with commas (e.g., cms, orion, php).</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" name="submit" class="bg-orion-600 hover:bg-orion-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors">
                    Save Changes
                </button>
            </div>

        </div>
    </form>
</div>

<?php require_once 'admin-footer.php'; ?>
