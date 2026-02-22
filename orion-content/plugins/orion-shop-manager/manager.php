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

// Handle create/update
global $orion_db, $table_prefix;
$table_products = $table_prefix . 'orion_products';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save_product') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $sku = $orion_db->real_escape_string(trim($_POST['sku']));
        $name = $orion_db->real_escape_string(trim($_POST['name']));
        $description = $orion_db->real_escape_string(trim($_POST['description']));
        $price = (int)$_POST['price'];
        $sale_price = isset($_POST['sale_price']) && $_POST['sale_price'] !== '' ? (int)$_POST['sale_price'] : null;
        $type = in_array($_POST['type'], array('physical','digital','service')) ? $_POST['type'] : 'physical';
        $stock_status = in_array($_POST['stock_status'], array('in_stock','out_of_stock','preorder')) ? $_POST['stock_status'] : 'in_stock';
        $stock_quantity = isset($_POST['stock_quantity']) && $_POST['stock_quantity'] !== '' ? (int)$_POST['stock_quantity'] : null;
        $unit = $orion_db->real_escape_string(trim($_POST['unit']));
        $category = $orion_db->real_escape_string(trim($_POST['category']));
        $image_url = isset($_POST['image']) ? trim($_POST['image']) : '';

        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/products/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_exts, true)) {
                $new_filename = 'shop-' . time() . '-' . mt_rand(1000, 9999) . '.' . $file_ext;
                $target_file = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
                    $image_url = site_url('/orion-content/uploads/products/' . $new_filename);
                }
            }
        }

        $image = $orion_db->real_escape_string($image_url);

        if ($id > 0) {
            $stmt = $orion_db->prepare("UPDATE $table_products SET sku=?, name=?, description=?, price=?, sale_price=?, type=?, stock_status=?, stock_quantity=?, unit=?, category=?, image=? WHERE id=?");
            $stmt->bind_param('sssisssisssi', $sku, $name, $description, $price, $sale_price, $type, $stock_status, $stock_quantity, $unit, $category, $image, $id);
        } else {
            $stmt = $orion_db->prepare("INSERT INTO $table_products (sku, name, description, price, sale_price, type, stock_status, stock_quantity, unit, category, image) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssisssisss', $sku, $name, $description, $price, $sale_price, $type, $stock_status, $stock_quantity, $unit, $category, $image);
        }

        if ($stmt && $stmt->execute()) {
            $message = $id > 0 ? "Product #$id updated successfully." : "Product created successfully.";
        } else {
            $message = "Failed to save product.";
        }
    } elseif ($_POST['action'] === 'delete_product' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $orion_db->query("DELETE FROM $table_products WHERE id = $id");
        $message = "Product #$id deleted.";
    }
}

// Get all products
$products_query = $orion_db->query("SELECT * FROM $table_products ORDER BY created_at DESC");

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
                <h2 class="text-lg font-medium text-gray-900">Product Catalog</h2>
                <button onclick="orionNewProduct()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded transition">
                    Add New Product / Service
                </button>
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
                        <?php if ($products_query && $products_query->num_rows > 0): ?>
                            <?php while($product = $products_query->fetch_object()): 
                                $price = orion_shop_get_price($product->id);
                                $stock = orion_shop_get_stock_status($product->id);
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($product->sku); ?> • <?php echo htmlspecialchars($product->type); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo orion_shop_format_price($price); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700"><?php echo orion_shop_get_stock($product->id); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    <button
                                        type="button"
                                        class="text-blue-600 hover:text-blue-900 font-semibold"
                                        onclick="orionEditProduct(this)"
                                        data-id="<?php echo (int)$product->id; ?>"
                                        data-sku="<?php echo htmlspecialchars($product->sku, ENT_QUOTES); ?>"
                                        data-name="<?php echo htmlspecialchars($product->name, ENT_QUOTES); ?>"
                                        data-price="<?php echo (int)$product->price; ?>"
                                        data-sale_price="<?php echo $product->sale_price !== null ? (int)$product->sale_price : ''; ?>"
                                        data-type="<?php echo htmlspecialchars($product->type, ENT_QUOTES); ?>"
                                        data-stock_status="<?php echo htmlspecialchars($product->stock_status, ENT_QUOTES); ?>"
                                        data-stock_quantity="<?php echo $product->stock_quantity !== null ? (int)$product->stock_quantity : ''; ?>"
                                        data-unit="<?php echo htmlspecialchars($product->unit, ENT_QUOTES); ?>"
                                        data-category="<?php echo htmlspecialchars($product->category, ENT_QUOTES); ?>"
                                        data-image="<?php echo htmlspecialchars($product->image, ENT_QUOTES); ?>"
                                        data-description="<?php echo htmlspecialchars($product->description, ENT_QUOTES); ?>"
                                    >
                                        Edit
                                    </button>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="id" value="<?php echo (int)$product->id; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No products found. Add your first product or service.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="orion-product-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">
        <button type="button" class="absolute top-3 right-3 text-slate-500 hover:text-slate-700" onclick="document.getElementById('orion-product-modal').classList.add('hidden')">
            ✕
        </button>
        <h2 class="text-xl font-semibold text-gray-900 mb-4" id="orion-product-modal-title">Add Product / Service</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_product">
            <input type="hidden" name="id" id="orion-product-id" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" id="orion-product-sku" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk / Jasa</label>
                    <input type="text" name="name" id="orion-product-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal (IDR)</label>
                    <input type="number" name="price" id="orion-product-price" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Promo (opsional)</label>
                    <input type="number" name="sale_price" id="orion-product-sale-price" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" id="orion-product-type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="physical">Barang Fisik</option>
                        <option value="digital">Produk Digital</option>
                        <option value="service">Jasa / Layanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Stok</label>
                    <select name="stock_status" id="orion-product-stock-status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="preorder">Pre-order</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok</label>
                    <input type="number" name="stock_quantity" id="orion-product-stock-quantity" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan (pcs, layanan, paket, dll)</label>
                    <input type="text" name="unit" id="orion-product-unit" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" id="orion-product-category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Contoh: Oli, Ban, Tune Up, Jasa Service">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                        <input type="text" name="image" id="orion-product-image" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="https://...">
                        <p class="text-xs text-gray-500 mt-1">Bisa isi URL gambar langsung jika sudah ada di hosting lain.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar dari PC / HP</label>
                        <input type="file" name="image_file" accept="image/*" class="block w-full text-sm text-gray-700">
                        <p class="text-xs text-gray-500 mt-1">Jika diupload, gambar ini akan dipakai dan URL di atas akan diabaikan.</p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="orion-product-description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 text-sm" onclick="document.getElementById('orion-product-modal').classList.add('hidden')">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function orionResetProductForm() {
    document.getElementById('orion-product-id').value = '';
    document.getElementById('orion-product-sku').value = '';
    document.getElementById('orion-product-name').value = '';
    document.getElementById('orion-product-price').value = '';
    document.getElementById('orion-product-sale-price').value = '';
    document.getElementById('orion-product-type').value = 'physical';
    document.getElementById('orion-product-stock-status').value = 'in_stock';
    document.getElementById('orion-product-stock-quantity').value = '';
    document.getElementById('orion-product-unit').value = '';
    document.getElementById('orion-product-category').value = '';
    document.getElementById('orion-product-image').value = '';
    document.getElementById('orion-product-description').value = '';
}

function orionNewProduct() {
    orionResetProductForm();
    document.getElementById('orion-product-modal-title').textContent = 'Add Product / Service';
    document.getElementById('orion-product-modal').classList.remove('hidden');
}

function orionEditProduct(button) {
    var id = button.getAttribute('data-id');
    document.getElementById('orion-product-id').value = id;
    document.getElementById('orion-product-sku').value = button.getAttribute('data-sku') || '';
    document.getElementById('orion-product-name').value = button.getAttribute('data-name') || '';
    document.getElementById('orion-product-price').value = button.getAttribute('data-price') || '';
    document.getElementById('orion-product-sale-price').value = button.getAttribute('data-sale_price') || '';

    var type = button.getAttribute('data-type') || 'physical';
    var allowedTypes = ['physical','digital','service'];
    document.getElementById('orion-product-type').value = allowedTypes.indexOf(type) !== -1 ? type : 'physical';

    var stockStatus = button.getAttribute('data-stock_status') || 'in_stock';
    var allowedStatus = ['in_stock','out_of_stock','preorder'];
    document.getElementById('orion-product-stock-status').value = allowedStatus.indexOf(stockStatus) !== -1 ? stockStatus : 'in_stock';

    document.getElementById('orion-product-stock-quantity').value = button.getAttribute('data-stock_quantity') || '';
    document.getElementById('orion-product-unit').value = button.getAttribute('data-unit') || '';
    document.getElementById('orion-product-category').value = button.getAttribute('data-category') || '';
    document.getElementById('orion-product-image').value = button.getAttribute('data-image') || '';
    document.getElementById('orion-product-description').value = button.getAttribute('data-description') || '';

    document.getElementById('orion-product-modal-title').textContent = 'Edit Product / Service #' + id;
    document.getElementById('orion-product-modal').classList.remove('hidden');
}
</script>

</body>
</html>
