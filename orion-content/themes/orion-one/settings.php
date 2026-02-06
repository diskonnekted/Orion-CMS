<?php
// Settings for Orion One Theme

// Get current values
$banner_image = get_option('orion_one_banner_image', '');
$banner_link = get_option('orion_one_banner_link', '#');
?>

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Sidebar Banner Settings</h2>
    
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="orion_one_banner_image">
            Banner Image
        </label>
        <?php if ($banner_image): ?>
        <div class="mb-2">
            <img src="<?php echo $banner_image; ?>" class="h-32 object-contain border rounded p-1">
        </div>
        <?php endif; ?>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="orion_one_banner_image" type="file" name="orion_one_banner_image">
        <p class="text-gray-500 text-xs italic mt-1">Upload an image for the sidebar banner.</p>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="orion_one_banner_link">
            Banner Link URL
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="orion_one_banner_link" type="text" name="orion_one_banner_link" value="<?php echo htmlspecialchars($banner_link); ?>" placeholder="https://example.com/promo">
    </div>
</div>
