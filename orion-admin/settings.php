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

    // Redirect to avoid form resubmission
    if (!isset($error)) {
        header("Location: settings.php?updated=true");
        exit;
    }
}

require_once 'admin-header.php';

// Check for success message
if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
    $message = "Settings saved successfully.";
}

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
    <button type="submit" name="submit" form="settings-form" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow transition-colors duration-200">
        Save Changes
    </button>
</div>

<?php if (isset($message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-6">
    <form id="settings-form" method="POST" action="settings.php" enctype="multipart/form-data">
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
                <h2 class="text-xl font-semibold mb-1 text-gray-800 border-b pb-2 flex items-center gap-2">
                    <span>Appearance Settings</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orion-50 text-orion-700 border border-orion-100">Live Preview</span>
                </h2>
                <p class="text-xs text-gray-500 mb-4">Pilih kombinasi warna khas Tailwind untuk seluruh tampilan dashboard.</p>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Admin Color Scheme</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php 
                        $schemes = function_exists('orion_get_color_schemes') ? orion_get_color_schemes() : [];
                        foreach ($schemes as $key => $scheme): 
                            $isActive = ($admin_color_scheme == $key);
                            $slateColor = isset($scheme['slate']['900']) ? $scheme['slate']['900'] : '#0f172a';
                            $primaryLight = isset($scheme['orion']['200']) ? $scheme['orion']['200'] : '#bfdbfe';
                            $primary = isset($scheme['orion']['500']) ? $scheme['orion']['500'] : '#3b82f6';
                            $primaryDark = isset($scheme['orion']['700']) ? $scheme['orion']['700'] : '#1d4ed8';
                        ?>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="admin_color_scheme" value="<?php echo $key; ?>" class="peer sr-only" <?php echo $isActive ? 'checked' : ''; ?>>
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 hover:shadow-lg bg-gradient-to-br from-white via-slate-50 to-slate-100 <?php echo $isActive ? 'border-orion-500 ring-2 ring-orion-200' : 'border-gray-200 hover:border-orion-300'; ?>">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="block font-semibold text-gray-900 text-sm"><?php echo isset($scheme['name']) ? $scheme['name'] : ucfirst(str_replace('_', ' ', $key)); ?></span>
                                            <span class="block text-[11px] uppercase tracking-wide text-gray-400 mt-0.5"><?php echo strtoupper(str_replace('_', ' ', $key)); ?></span>
                                        </div>
                                        <?php if ($isActive): ?>
                                            <svg class="w-5 h-5 text-orion-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex h-9 rounded-md overflow-hidden border border-gray-100 shadow-sm">
                                            <div class="w-1/4 h-full" style="background-color: <?php echo $slateColor; ?>"></div>
                                            <div class="w-3/4 h-full bg-gradient-to-r" style="background-image: linear-gradient(to right, <?php echo $primaryLight; ?>, <?php echo $primary; ?>, <?php echo $primaryDark; ?>);"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-gray-500">
                                            <span>Slate: <?php echo strtoupper($slateColor); ?></span>
                                            <span>Primary: <?php echo strtoupper($primary); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Skema warna ini akan memengaruhi sidebar, tombol utama, dan highlight di seluruh admin.</p>
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

            <div class="border-t pt-6 border-slate-200">
                <button type="submit" name="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-colors duration-200">
                    Save Changes
                </button>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[name="admin_color_scheme"]');
    
    colorInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Reset all
            document.querySelectorAll('input[name="admin_color_scheme"]').forEach(inp => {
                const container = inp.nextElementSibling;
                const checkIcon = container.querySelector('svg');
                
                // Remove active classes
                container.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200', 'bg-blue-50/10');
                
                // Add inactive classes
                container.classList.add('border-gray-200', 'hover:border-blue-300');
                
                // Hide check icon
                if (checkIcon) checkIcon.remove();
            });
            
            // Set active
            const activeContainer = this.nextElementSibling;
            activeContainer.classList.remove('border-gray-200', 'hover:border-blue-300');
            activeContainer.classList.add('border-blue-500', 'ring-2', 'ring-blue-200', 'bg-blue-50/10');
            
            // Add check icon if not exists
            if (!activeContainer.querySelector('svg')) {
                const titleContainer = activeContainer.querySelector('.flex.items-center.justify-between');
                const iconHtml = '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                titleContainer.insertAdjacentHTML('beforeend', iconHtml);
            }
        });
    });
});
</script>

<?php require_once 'admin-footer.php'; ?>
