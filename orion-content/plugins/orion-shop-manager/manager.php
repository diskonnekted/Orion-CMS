<?php
/**
 * Orion Shop Manager Dashboard
 */

// Bootstrap Orion Core
if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die("Orion CMS Core not found.");
    }
}

// Authentication Check
if (!is_user_logged_in()) {
    header("Location: " . site_url('/orion-admin/'));
    exit;
}

$current_user = wp_get_current_user();
$message = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_product') {
    $post_id = (int)$_POST['post_id'];
    $price = (int)$_POST['price'];
    $stock = $_POST['stock'];
    
    update_post_meta($post_id, 'shop_price', $price);
    update_post_meta($post_id, 'shop_stock_status', $stock);
    
    $message = "Product #$post_id updated successfully.";
}

// Get all products (posts)
global $orion_db, $table_prefix;
$posts_query = $orion_db->query("SELECT * FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager - Orion CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="text-gray-500 hover:text-gray-900 transition">
                    &larr; Back to Admin
                </a>
                <h1 class="text-xl font-bold text-gray-900">Shop Manager</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">Logged in as <b><?php echo $current_user->user_login; ?></b></span>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
        <?php if ($message): ?>
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline"><?php echo $message; ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Product Inventory</h2>
                <a href="<?php echo site_url('/orion-admin/post-new.php'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded transition">
                    Add New Product
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (IDR)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($posts_query && $posts_query->num_rows > 0): ?>
                            <?php while($post = $posts_query->fetch_object()): 
                                $price = orion_shop_get_price($post->ID);
                                $stock = orion_shop_get_stock_status($post->ID);
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <div class="text-sm font-medium text-gray-900"><?php echo $post->post_title; ?></div>
                                            <div class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($post->post_date)); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <form method="POST" class="contents">
                                    <input type="hidden" name="action" value="update_product">
                                    <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="price" value="<?php echo $price; ?>" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-1 border" placeholder="0">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="stock" class="mt-1 block w-full py-1 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                                            <option value="in_stock" <?php echo $stock === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                                            <option value="out_of_stock" <?php echo $stock === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 font-semibold">Update</button>
                                        <span class="text-gray-300 mx-2">|</span>
                                        <a href="<?php echo site_url('/orion-admin/post-new.php?id=' . $post->ID); ?>" class="text-gray-500 hover:text-gray-700">Edit Post</a>
                                    </td>
                                </form>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No products found. Add some posts first.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
