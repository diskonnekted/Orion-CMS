<?php
require_once '../orion-load.php';

// Handle Actions
$message = '';
$error = '';
$current_menu_id = isset($_GET['menu']) ? (int)$_GET['menu'] : 0;

// Get all menus
$menus = get_terms('nav_menu', array('hide_empty' => false));
if (empty($menus) && !empty($_POST['create-menu'])) {
    // Will be created below
} elseif (!empty($menus) && $current_menu_id == 0) {
    $current_menu_id = $menus[0]->term_id;
}

// 1. Create Menu
if (isset($_POST['create-menu']) && !empty($_POST['menu-name'])) {
    $menu_name = trim($_POST['menu-name']);
    $res = wp_insert_term($menu_name, 'nav_menu');
    
    // Debug: Ensure is_wp_error exists
    if (!function_exists('is_wp_error')) {
        // Try to load it explicitly
        if (file_exists('../orion-includes/wp-compat.php')) {
            require_once '../orion-includes/wp-compat.php';
        }
    }
    
    if (!function_exists('is_wp_error')) {
        die("Fatal Error: is_wp_error function is still missing after explicit include attempt.");
    }

    if (!is_wp_error($res)) { // Assuming wp_insert_term returns array or ID, need to check my implementation
        $message = "Menu created.";
        $current_menu_id = $res['term_id'];
        // Refresh menus
        $menus = get_terms('nav_menu', array('hide_empty' => false));
    } else {
        $error = "Error creating menu.";
    }
}

// 2. Add Menu Item
if (isset($_POST['add-menu-item']) && $current_menu_id > 0) {
    $type = $_POST['menu-item-type']; // 'post', 'custom'
    
    $items_to_add = array();
    
    if ($type == 'post') {
        if (!empty($_POST['post_ids'])) {
            foreach ($_POST['post_ids'] as $post_id) {
                $p = get_post($post_id);
                $items_to_add[] = array(
                    'title' => $p->post_title,
                    'url' => get_permalink($p->ID),
                    'object_id' => $p->ID,
                    'object' => 'post',
                    'type' => 'post_type'
                );
            }
        }
    } elseif ($type == 'custom') {
        $items_to_add[] = array(
            'title' => $_POST['custom-link-text'],
            'url' => $_POST['custom-link-url'],
            'object_id' => 0,
            'object' => 'custom',
            'type' => 'custom'
        );
    }
    
    foreach ($items_to_add as $item) {
        // Create nav_menu_item post
        $post_data = array(
            'post_title' => $item['title'],
            'post_status' => 'publish',
            'post_type' => 'nav_menu_item'
        );
        
        $item_id = wp_insert_post($post_data);
        if ($item_id) {
            // Assign to menu term
            wp_set_object_terms($item_id, $current_menu_id, 'nav_menu');
            
            // Save meta
            update_post_meta($item_id, '_menu_item_type', $item['type']);
            update_post_meta($item_id, '_menu_item_object_id', $item['object_id']);
            update_post_meta($item_id, '_menu_item_object', $item['object']);
            update_post_meta($item_id, '_menu_item_url', $item['url']);
            // Menu order
            update_post_meta($item_id, '_menu_item_menu_item_parent', 0);
            // Simple ordering: append to end? We need a way to store order.
            // WP uses 'menu_order' field in posts table.
        }
    }
    $message = "Items added.";
}

// 3. Save Menu Structure (Order/Remove) - Simplified for now: just Remove
if (isset($_GET['action']) && $_GET['action'] == 'delete-item' && isset($_GET['item_id'])) {
    $item_id = (int)$_GET['item_id'];
    wp_delete_post($item_id); // Assuming we have this, or use raw query
    // Redirect to avoid resubmission
    header("Location: nav-menus.php?menu=$current_menu_id");
    exit;
}

// 4. Save Locations
if (isset($_POST['save-locations'])) {
    $locations = $_POST['menu_locations']; // array(location => term_id)
    update_option('nav_menu_locations', $locations); // Need update_option
    $message = "Locations updated.";
}

// Get Menu Items for current menu
$menu_items = array();
if ($current_menu_id > 0) {
    // Get posts with taxonomy term
    // We need a way to get posts by term.
    // get_posts supports 'tax_query' in WP, but my get_posts is simple.
    // I need to write a custom query here or enhance get_posts.
    // Let's use raw query for now for speed.
    global $orion_db, $table_prefix;
    $sql = "SELECT p.* FROM {$table_prefix}posts p 
            INNER JOIN {$table_prefix}term_relationships tr ON p.ID = tr.object_id
            INNER JOIN {$table_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.term_id = $current_menu_id AND p.post_type = 'nav_menu_item' AND p.post_status = 'publish'
            ORDER BY p.ID ASC"; // TODO: Use menu_order
    $result = $orion_db->query($sql);
    if ($result) {
        while ($row = $result->fetch_object()) {
            // Get meta
            $row->url = get_post_meta($row->ID, '_menu_item_url', true);
            $row->type = get_post_meta($row->ID, '_menu_item_type', true);
            $menu_items[] = $row;
        }
    }
}

// Get registered locations (need to implement in functions/compat)
$locations = get_registered_nav_menus();
$assigned_locations = get_option('nav_menu_locations', array());

// Get recent posts for sidebar
$recent_posts = get_posts(array('numberposts' => 10));
$recent_pages = get_posts(array('post_type' => 'page', 'numberposts' => 20));

require_once 'admin-header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Menus</h1>
    </div>
    
    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Sidebar: Add Items -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Pages Accordion -->
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow overflow-hidden">
                <button @click="open = !open" class="w-full px-4 py-3 bg-gray-50 flex justify-between items-center font-medium text-gray-700 border-b">
                    <span>Pages</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-4">
                    <form action="nav-menus.php?menu=<?php echo $current_menu_id; ?>" method="POST">
                        <div class="max-h-60 overflow-y-auto space-y-2 mb-4 border rounded p-2">
                            <?php if (empty($recent_pages)): ?>
                                <p class="text-sm text-gray-500 p-2">No pages found.</p>
                            <?php else: ?>
                                <?php foreach ($recent_pages as $p): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="post_ids[]" value="<?php echo $p->ID; ?>" class="rounded text-blue-500 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700"><?php echo $p->post_title; ?></span>
                                </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="menu-item-type" value="post">
                        <div class="flex justify-end">
                            <button type="submit" name="add-menu-item" value="1" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50" <?php echo $current_menu_id == 0 ? 'disabled' : ''; ?>>Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts Accordion -->
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow overflow-hidden">
                <button @click="open = !open" class="w-full px-4 py-3 bg-gray-50 flex justify-between items-center font-medium text-gray-700 border-b">
                    <span>Posts</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-4">
                    <form action="nav-menus.php?menu=<?php echo $current_menu_id; ?>" method="POST">
                        <div class="max-h-60 overflow-y-auto space-y-2 mb-4 border rounded p-2">
                            <?php foreach ($recent_posts as $p): ?>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="post_ids[]" value="<?php echo $p->ID; ?>" class="rounded text-blue-500 focus:ring-blue-500">
                                <span class="text-sm text-gray-700"><?php echo $p->post_title; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="menu-item-type" value="post">
                        <div class="flex justify-end">
                            <button type="submit" name="add-menu-item" value="1" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50" <?php echo $current_menu_id == 0 ? 'disabled' : ''; ?>>Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Custom Links -->
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow overflow-hidden">
                <button @click="open = !open" class="w-full px-4 py-3 bg-gray-50 flex justify-between items-center font-medium text-gray-700 border-b">
                    <span>Custom Links</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-4">
                    <form action="nav-menus.php?menu=<?php echo $current_menu_id; ?>" method="POST" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">URL</label>
                            <input type="text" name="custom-link-url" value="http://" class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Link Text</label>
                            <input type="text" name="custom-link-text" class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <input type="hidden" name="menu-item-type" value="custom">
                        <div class="flex justify-end">
                            <button type="submit" name="add-menu-item" value="1" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50" <?php echo $current_menu_id == 0 ? 'disabled' : ''; ?>>Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Right Side: Menu Structure -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow p-6">
                
                <!-- Menu Selector / Creator -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 mb-6 gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Select a menu to edit:</label>
                        <select onchange="window.location.href='nav-menus.php?menu='+this.value" class="border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 px-2 py-1 border">
                            <?php if (empty($menus)): ?>
                                <option value="0">-- No menus --</option>
                            <?php else: ?>
                                <?php foreach ($menus as $m): ?>
                                    <option value="<?php echo $m->term_id; ?>" <?php echo $current_menu_id == $m->term_id ? 'selected' : ''; ?>><?php echo $m->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <span class="text-sm text-gray-500">or</span>
                        <a href="nav-menus.php?action=edit&menu=0" class="text-sm text-blue-600 hover:underline">create a new menu</a>
                    </div>
                </div>

                <!-- Create Menu Form (if creating or no menu selected) -->
                <?php if ($current_menu_id == 0): ?>
                    <form action="nav-menus.php" method="POST" class="flex items-end gap-4 mb-6">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Menu Name</label>
                            <input type="text" name="menu-name" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                        </div>
                        <button type="submit" name="create-menu" value="1" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Menu</button>
                    </form>
                <?php else: ?>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold">Menu Structure</h3>
                            <p class="text-sm text-gray-500">Drag each item into the order you prefer.</p>
                        </div>
                        <form action="nav-menus.php?menu=<?php echo $current_menu_id; ?>" method="POST">
                            <input type="text" name="menu-name" value="<?php echo get_term($current_menu_id, 'nav_menu')->name; ?>" class="px-2 py-1 border rounded text-sm w-40">
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Save Menu</button>
                        </form>
                    </div>

                    <!-- Menu Items List -->
                    <div class="space-y-2 mb-8 bg-gray-50 p-4 rounded min-h-[100px]">
                        <?php if (empty($menu_items)): ?>
                            <p class="text-center text-gray-400 py-4">Add menu items from the column on the left.</p>
                        <?php else: ?>
                            <?php foreach ($menu_items as $item): ?>
                                <div class="bg-white border rounded shadow-sm p-3 flex justify-between items-center group">
                                    <div>
                                        <div class="font-medium text-gray-800"><?php echo $item->post_title; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo ucfirst($item->type); ?></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="nav-menus.php?menu=<?php echo $current_menu_id; ?>&action=delete-item&item_id=<?php echo $item->ID; ?>" class="text-red-400 hover:text-red-600" data-orion-confirm="Remove this item?">Remove</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Menu Settings -->
                    <div class="border-t pt-6">
                        <h4 class="font-bold text-gray-800 mb-4">Menu Settings</h4>
                        <form action="nav-menus.php?menu=<?php echo $current_menu_id; ?>" method="POST">
                            <div class="flex items-start gap-8">
                                <div class="w-1/3">
                                    <span class="text-sm text-gray-600 block mb-2">Display location</span>
                                </div>
                                <div class="w-2/3 space-y-2">
                                    <?php if (!empty($locations)): ?>
                                        <?php foreach ($locations as $loc_key => $loc_name): ?>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="menu_locations[<?php echo $loc_key; ?>]" value="<?php echo $current_menu_id; ?>" <?php echo (isset($assigned_locations[$loc_key]) && $assigned_locations[$loc_key] == $current_menu_id) ? 'checked' : ''; ?> class="rounded text-blue-500 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700"><?php echo $loc_name; ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500 italic">No menu locations registered by the theme.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-6 border-t pt-4 flex justify-between items-center">
                                <a href="#" class="text-red-500 text-sm hover:underline">Delete Menu</a>
                                <button type="submit" name="save-locations" value="1" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Menu</button>
                            </div>
                        </form>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>
