<?php
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/orion-admin/'));
    exit;
}

require_once( ABSPATH . 'orion-admin/admin-header.php' );

global $orion_db, $table_prefix;
$table_products = $table_prefix . 'orion_products';

$message = '';
$error = '';

function orion_shop_admin_resolve_media_url($value) {
    $value = trim((string) $value);
    if ($value === '') return '';
    if (preg_match('#^https?://#i', $value)) return $value;
    return site_url('/' . ltrim($value, '/'));
}

function orion_shop_admin_db_fetch_one($sql) {
    global $orion_db;
    $res = $orion_db->query($sql);
    if (!$res || !isset($res->num_rows) || $res->num_rows <= 0) return null;
    return $res->fetch_object();
}

function orion_shop_admin_local_products_upload_abs_dir() {
    return ABSPATH . 'orion-content/uploads/products/';
}

function orion_shop_admin_delete_local_product_image_if_owned($value) {
    $value = trim((string) $value);
    if ($value === '') return;
    if (strpos($value, 'orion-content/uploads/products/') !== 0) return;
    $filename = basename($value);
    if ($filename === '' || $filename === '.' || $filename === '..') return;
    $abs = orion_shop_admin_local_products_upload_abs_dir() . $filename;
    if (is_file($abs)) {
        @unlink($abs);
    }
}

function orion_shop_admin_gd_available() {
    return function_exists('getimagesize') && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled');
}

function orion_shop_admin_resize_and_compress($src_abs, $dest_abs, $ext, $max_side = 1600) {
    if (!orion_shop_admin_gd_available()) {
        return array('ok' => false, 'error' => 'GD library tidak tersedia untuk resize/compress.');
    }

    $info = @getimagesize($src_abs);
    if (!$info || !isset($info[0]) || !isset($info[1])) {
        return array('ok' => false, 'error' => 'Gagal membaca ukuran gambar.');
    }

    $w = (int) $info[0];
    $h = (int) $info[1];
    if ($w <= 0 || $h <= 0) {
        return array('ok' => false, 'error' => 'Ukuran gambar tidak valid.');
    }

    $ext = strtolower((string) $ext);

    $src = null;
    if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('imagecreatefromjpeg')) {
        $src = @imagecreatefromjpeg($src_abs);
    } elseif ($ext === 'png' && function_exists('imagecreatefrompng')) {
        $src = @imagecreatefrompng($src_abs);
    } elseif ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
        $src = @imagecreatefromwebp($src_abs);
    } elseif ($ext === 'gif' && function_exists('imagecreatefromgif')) {
        $src = @imagecreatefromgif($src_abs);
    }

    if (!$src) {
        return array('ok' => false, 'error' => 'Format gambar tidak bisa diproses untuk resize/compress.');
    }

    $scale = 1.0;
    $max_dim = max($w, $h);
    if ($max_dim > $max_side) {
        $scale = $max_side / $max_dim;
    }

    $new_w = (int) max(1, round($w * $scale));
    $new_h = (int) max(1, round($h * $scale));

    $dst = imagecreatetruecolor($new_w, $new_h);
    if ($ext === 'png' || $ext === 'gif') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $new_w, $new_h, $transparent);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

    $ok = false;
    if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('imagejpeg')) {
        $ok = imagejpeg($dst, $dest_abs, 82);
    } elseif ($ext === 'png' && function_exists('imagepng')) {
        $ok = imagepng($dst, $dest_abs, 7);
    } elseif ($ext === 'webp' && function_exists('imagewebp')) {
        $ok = imagewebp($dst, $dest_abs, 80);
    } elseif ($ext === 'gif' && function_exists('imagegif')) {
        $ok = imagegif($dst, $dest_abs);
    }

    imagedestroy($dst);
    imagedestroy($src);

    if (!$ok) {
        return array('ok' => false, 'error' => 'Gagal menyimpan hasil compress.');
    }

    return array('ok' => true);
}

function orion_shop_admin_upload_product_image($file, $product_id) {
    $product_id = (int) $product_id;
    if ($product_id <= 0) return array('ok' => false, 'error' => 'Produk tidak valid.');
    if (!isset($file) || !is_array($file)) return array('ok' => false, 'error' => 'File upload tidak ditemukan.');
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) return array('ok' => false, 'error' => 'Gagal upload file.');
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return array('ok' => false, 'error' => 'File upload tidak valid.');

    $max_bytes = 10 * 1024 * 1024;
    if (isset($file['size']) && (int) $file['size'] > $max_bytes) {
        return array('ok' => false, 'error' => 'Ukuran file terlalu besar. Maksimal 10MB.');
    }

    $original_name = isset($file['name']) ? (string) $file['name'] : '';
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed = array('jpg' => true, 'jpeg' => true, 'png' => true, 'webp' => true, 'gif' => true);
    if ($ext === '' || !isset($allowed[$ext])) {
        return array('ok' => false, 'error' => 'Format file tidak didukung. Gunakan JPG/PNG/WEBP/GIF.');
    }

    $upload_dir_abs = orion_shop_admin_local_products_upload_abs_dir();
    if (!is_dir($upload_dir_abs)) {
        @mkdir($upload_dir_abs, 0755, true);
    }
    if (!is_dir($upload_dir_abs)) {
        return array('ok' => false, 'error' => 'Folder upload tidak bisa dibuat.');
    }

    $filename = 'shop-' . $product_id . '-' . time() . '-' . random_int(1000, 9999) . '.' . $ext;
    $dest_abs = $upload_dir_abs . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest_abs)) {
        return array('ok' => false, 'error' => 'Gagal memindahkan file upload.');
    }

    $need_process = false;
    if (isset($file['size']) && (int) $file['size'] > (2 * 1024 * 1024)) {
        $need_process = true;
    } else {
        $info = @getimagesize($dest_abs);
        if ($info && isset($info[0]) && isset($info[1])) {
            $max_dim = max((int) $info[0], (int) $info[1]);
            if ($max_dim > 1600) {
                $need_process = true;
            }
        }
    }

    if ($need_process) {
        $processed_abs = $upload_dir_abs . 'processed-' . $filename;
        $r = orion_shop_admin_resize_and_compress($dest_abs, $processed_abs, $ext, 1600);
        if ($r['ok']) {
            @unlink($dest_abs);
            @rename($processed_abs, $dest_abs);
        } else {
            if (isset($file['size']) && (int) $file['size'] > (5 * 1024 * 1024) && !orion_shop_admin_gd_available()) {
                @unlink($dest_abs);
                return array('ok' => false, 'error' => 'Server belum mendukung auto-resize/compress (GD library belum aktif). Gunakan file < 5MB atau aktifkan ekstensi GD.');
            }
            @unlink($processed_abs);
        }
    }

    return array('ok' => true, 'value' => 'orion-content/uploads/products/' . $filename);
}

function orion_shop_admin_db_delete_product($table_products, $product_id) {
    global $orion_db;
    $product_id = (int) $product_id;
    if ($product_id <= 0) return false;
    return (bool) $orion_db->query("DELETE FROM $table_products WHERE id = $product_id");
}

function orion_shop_admin_db_upsert_product($table_products, $data, $id = 0) {
    global $orion_db;
    $id = (int) $id;

    $sku = trim((string) $data['sku']);
    $name = trim((string) $data['name']);
    $description = (string) $data['description'];
    $price = (int) $data['price'];
    $sale_price = $data['sale_price'];
    $type = (string) $data['type'];
    $stock_status = (string) $data['stock_status'];
    $stock_quantity = $data['stock_quantity'];
    $unit = (string) $data['unit'];
    $category = (string) $data['category'];
    $image = (string) $data['image'];

    $sale_price_int = ($sale_price === null || $sale_price === '') ? null : (int) $sale_price;
    $stock_quantity_int = ($stock_quantity === null || $stock_quantity === '') ? null : (int) $stock_quantity;

    if (method_exists($orion_db, 'prepare')) {
        if ($id > 0) {
            $stmt = $orion_db->prepare("UPDATE $table_products SET sku=?, name=?, description=?, price=?, sale_price=?, type=?, stock_status=?, stock_quantity=?, unit=?, category=?, image=? WHERE id=?");
            if (!$stmt) return false;
            $stmt->bind_param('sssisssisssi', $sku, $name, $description, $price, $sale_price_int, $type, $stock_status, $stock_quantity_int, $unit, $category, $image, $id);
            return (bool) $stmt->execute();
        }

        $stmt = $orion_db->prepare("INSERT INTO $table_products (sku, name, description, price, sale_price, type, stock_status, stock_quantity, unit, category, image) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        if (!$stmt) return false;
        $stmt->bind_param('sssisssisss', $sku, $name, $description, $price, $sale_price_int, $type, $stock_status, $stock_quantity_int, $unit, $category, $image);
        return (bool) $stmt->execute();
    }

    $sku_esc = $orion_db->real_escape_string($sku);
    $name_esc = $orion_db->real_escape_string($name);
    $desc_esc = $orion_db->real_escape_string($description);
    $type_esc = $orion_db->real_escape_string($type);
    $stock_status_esc = $orion_db->real_escape_string($stock_status);
    $unit_esc = $orion_db->real_escape_string($unit);
    $category_esc = $orion_db->real_escape_string($category);
    $image_esc = $orion_db->real_escape_string($image);

    $sale_sql = $sale_price_int === null ? 'NULL' : (string) $sale_price_int;
    $stock_sql = $stock_quantity_int === null ? 'NULL' : (string) $stock_quantity_int;

    if ($id > 0) {
        return (bool) $orion_db->query("UPDATE $table_products SET sku='$sku_esc', name='$name_esc', description='$desc_esc', price=$price, sale_price=$sale_sql, type='$type_esc', stock_status='$stock_status_esc', stock_quantity=$stock_sql, unit='$unit_esc', category='$category_esc', image='$image_esc', updated_at=CURRENT_TIMESTAMP WHERE id=$id");
    }

    return (bool) $orion_db->query("INSERT INTO $table_products (sku, name, description, price, sale_price, type, stock_status, stock_quantity, unit, category, image, created_at, updated_at) VALUES ('$sku_esc', '$name_esc', '$desc_esc', $price, $sale_sql, '$type_esc', '$stock_status_esc', $stock_sql, '$unit_esc', '$category_esc', '$image_esc', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
}

function orion_shop_admin_db_update_image($table_products, $product_id, $image) {
    global $orion_db;
    $product_id = (int) $product_id;
    $image = (string) $image;

    if (method_exists($orion_db, 'prepare')) {
        $stmt = $orion_db->prepare("UPDATE $table_products SET image = ? WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param('si', $image, $product_id);
        return (bool) $stmt->execute();
    }

    $img_esc = $orion_db->real_escape_string($image);
    return (bool) $orion_db->query("UPDATE $table_products SET image = '$img_esc' WHERE id = $product_id");
}

function orion_shop_admin_db_clear_image($table_products, $product_id) {
    global $orion_db;
    $product_id = (int) $product_id;

    if (method_exists($orion_db, 'prepare')) {
        $stmt = $orion_db->prepare("UPDATE $table_products SET image = '' WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param('i', $product_id);
        return (bool) $stmt->execute();
    }

    return (bool) $orion_db->query("UPDATE $table_products SET image = '' WHERE id = $product_id");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && (string) $_POST['action'] === 'save_product') {
    $product_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $sku = isset($_POST['sku']) ? sanitize_text_field($_POST['sku']) : '';
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $description = isset($_POST['description']) ? (string) $_POST['description'] : '';
    $description = trim($description);

    $price = isset($_POST['price']) ? (int) $_POST['price'] : 0;
    $sale_price = isset($_POST['sale_price']) && (string) $_POST['sale_price'] !== '' ? (int) $_POST['sale_price'] : null;

    $type = isset($_POST['type']) ? (string) $_POST['type'] : 'physical';
    if (!in_array($type, array('physical', 'digital', 'service'), true)) $type = 'physical';

    $stock_status = isset($_POST['stock_status']) ? (string) $_POST['stock_status'] : 'in_stock';
    if (!in_array($stock_status, array('in_stock', 'out_of_stock', 'preorder'), true)) $stock_status = 'in_stock';

    $stock_quantity = isset($_POST['stock_quantity']) && (string) $_POST['stock_quantity'] !== '' ? (int) $_POST['stock_quantity'] : null;
    $unit = isset($_POST['unit']) ? sanitize_text_field($_POST['unit']) : '';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $image = isset($_POST['image']) ? sanitize_text_field($_POST['image']) : '';

    if (trim($sku) === '') $error = 'SKU wajib diisi.';
    if ($error === '' && trim($name) === '') $error = 'Nama produk wajib diisi.';

    if ($error === '') {
        $has_upload = isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && (string) $_FILES['image_file']['name'] !== '';
        if ($product_id <= 0 && $has_upload) {
            $image = '';
        }

        $ok = orion_shop_admin_db_upsert_product($table_products, array(
            'sku' => $sku,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'sale_price' => $sale_price,
            'type' => $type,
            'stock_status' => $stock_status,
            'stock_quantity' => $stock_quantity,
            'unit' => $unit,
            'category' => $category,
            'image' => $image,
        ), $product_id);

        if ($ok) {
            if ($has_upload) {
                $id_for_upload = $product_id > 0 ? $product_id : (int) (isset($orion_db->insert_id) ? $orion_db->insert_id : 0);
                if ($id_for_upload > 0) {
                    $upload = orion_shop_admin_upload_product_image($_FILES['image_file'], $id_for_upload);
                    if ($upload['ok']) {
                        $image = (string) $upload['value'];
                        orion_shop_admin_db_update_image($table_products, $id_for_upload, $image);
                    } else {
                        $error = $upload['error'];
                    }
                }
            }
            $message = $product_id > 0 ? 'Produk berhasil diupdate.' : 'Produk berhasil ditambahkan.';
        } else {
            $error = 'Gagal menyimpan produk.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && (string) $_POST['action'] === 'delete_product') {
    $product_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($product_id <= 0) {
        $error = 'Produk tidak valid.';
    } else {
        $row = orion_shop_admin_db_fetch_one("SELECT image FROM $table_products WHERE id = " . (int) $product_id . " LIMIT 1");
        $old = $row && isset($row->image) ? (string) $row->image : '';
        if (orion_shop_admin_db_delete_product($table_products, $product_id)) {
            orion_shop_admin_delete_local_product_image_if_owned($old);
            $message = 'Produk berhasil dihapus.';
        } else {
            $error = 'Gagal menghapus produk.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orion_shop_update_image'])) {
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $image = isset($_POST['image']) ? sanitize_text_field($_POST['image']) : '';

    if ($product_id <= 0) {
        $error = 'Produk tidak valid.';
    } else {
        if (isset($_POST['orion_shop_delete_image'])) {
            $old = '';
            $res = $orion_db->query("SELECT image FROM $table_products WHERE id = " . (int) $product_id . " LIMIT 1");
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_object();
                if ($row && isset($row->image)) $old = (string) $row->image;
            }
            if (orion_shop_admin_db_clear_image($table_products, $product_id)) {
                orion_shop_admin_delete_local_product_image_if_owned($old);
                $message = 'Gambar berhasil dihapus.';
            } else {
                $error = 'Gagal menghapus. Coba lagi.';
            }
        } else {
        if (isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && (string) $_FILES['image_file']['name'] !== '') {
            $upload = orion_shop_admin_upload_product_image($_FILES['image_file'], $product_id);
            if (!$upload['ok']) {
                $error = $upload['error'];
            } else {
                $image = (string) $upload['value'];
            }
        }
        if ($error !== '') {
            // no-op
        } else {
        if (orion_shop_admin_db_update_image($table_products, $product_id, $image)) {
            $message = 'Gambar berhasil disimpan.';
        } else {
            $error = 'Gagal menyimpan. Coba lagi.';
        }
        }
        }
    }
}

$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$q = sanitize_text_field($q);
$sql_where = '';
if ($q !== '') {
    $like = '%' . $orion_db->real_escape_string($q) . '%';
    $sql_where = "WHERE name LIKE '$like' OR sku LIKE '$like' OR category LIKE '$like'";
}

$products = array();
$res = $orion_db->query("SELECT id, sku, name, description, price, sale_price, type, stock_status, stock_quantity, unit, category, image, updated_at FROM $table_products $sql_where ORDER BY updated_at DESC LIMIT 200");
if ($res) {
    while ($row = $res->fetch_object()) {
        $products[] = $row;
    }
}
?>

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Orion Shop Manager</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola katalog produk (tambah/edit/hapus) dan gambar untuk halaman Shop (klik Beli → WhatsApp)</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end">
            <form method="GET" class="flex gap-2">
                <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari SKU / nama / kategori" class="w-72 px-4 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orion-200">
                <button class="px-4 py-2 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800 transition" type="submit">Cari</button>
            </form>
            <button type="button" onclick="orionOpenProductModal()" class="px-4 py-2 rounded-lg bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">
                Tambah Produk
            </button>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 font-semibold">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 font-semibold">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <div class="text-sm font-semibold text-slate-700">Produk (maks. 200)</div>
            <div class="text-xs text-slate-500 mt-1">Gambar bisa URL penuh atau path lokal (contoh: <span class="font-mono">orion-content/uploads/products/foto.jpg</span>). Upload akan auto-resize/compress bila terlalu besar.</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white border-b border-slate-200">
                    <tr class="text-left text-slate-600">
                        <th class="px-6 py-4 font-bold">Produk</th>
                        <th class="px-6 py-4 font-bold">Foto</th>
                        <th class="px-6 py-4 font-bold">Image URL</th>
                        <th class="px-6 py-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 font-semibold">Produk belum ada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <?php
                            $price_raw = $p->sale_price !== null && (int) $p->sale_price > 0 ? (int) $p->sale_price : (int) $p->price;
                            $price = 'Rp ' . number_format($price_raw, 0, ',', '.');
                            $img_url = orion_shop_admin_resolve_media_url($p->image);
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-extrabold text-slate-900"><?php echo htmlspecialchars($p->name); ?></div>
                                    <div class="mt-1 text-xs text-slate-500 font-semibold">
                                        SKU: <span class="font-mono"><?php echo htmlspecialchars($p->sku); ?></span>
                                        <?php if (trim((string) $p->category) !== ''): ?>
                                            <span class="mx-2 text-slate-300">|</span>
                                            <?php echo htmlspecialchars($p->category); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-2 inline-flex items-center gap-2">
                                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-black uppercase tracking-widest"><?php echo htmlspecialchars($p->type); ?></span>
                                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-black uppercase tracking-widest"><?php echo htmlspecialchars($p->stock_status); ?></span>
                                        <span class="px-2 py-1 rounded-full bg-orion-50 text-orion-700 text-[11px] font-black tracking-widest"><?php echo htmlspecialchars($price); ?></span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <?php if ($img_url !== ''): ?>
                                        <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($p->name); ?>" class="w-28 h-20 rounded-xl object-cover border border-slate-200 bg-slate-50">
                                    <?php else: ?>
                                        <div class="w-28 h-20 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18v14H3V5zm3 10l3-3 4 4 3-3 2 2"></path></svg>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <form method="POST" class="space-y-2" enctype="multipart/form-data">
                                        <input type="hidden" name="orion_shop_update_image" value="1">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $p->id; ?>">
                                        <input type="text" name="image" value="<?php echo htmlspecialchars((string) $p->image); ?>" placeholder="https://... atau path lokal" class="w-[32rem] max-w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orion-200">
                                        <div class="flex flex-col gap-2">
                                            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" class="block w-[32rem] max-w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-lg cursor-pointer bg-white">
                                            <div class="text-xs text-slate-500">Upload akan disimpan ke <span class="font-mono">orion-content/uploads/products/</span> (maks 10MB).</div>
                                        </div>
                                        <?php if ($img_url !== ''): ?>
                                            <div class="text-xs text-slate-500 break-all">
                                                Preview URL: <a class="text-orion-700 hover:text-orion-800 font-semibold" href="<?php echo htmlspecialchars($img_url); ?>" target="_blank"><?php echo htmlspecialchars($img_url); ?></a>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-xs text-slate-500">Belum ada gambar.</div>
                                        <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 align-top">
                                        <button type="button"
                                            class="mb-2 px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-800 font-extrabold hover:bg-slate-50 transition w-full"
                                            onclick="orionEditProduct(this)"
                                            data-id="<?php echo (int) $p->id; ?>"
                                            data-sku="<?php echo htmlspecialchars((string) $p->sku, ENT_QUOTES); ?>"
                                            data-name="<?php echo htmlspecialchars((string) $p->name, ENT_QUOTES); ?>"
                                            data-description="<?php echo htmlspecialchars((string) $p->description, ENT_QUOTES); ?>"
                                            data-price="<?php echo (int) $p->price; ?>"
                                            data-sale_price="<?php echo $p->sale_price !== null ? (int) $p->sale_price : ''; ?>"
                                            data-type="<?php echo htmlspecialchars((string) $p->type, ENT_QUOTES); ?>"
                                            data-stock_status="<?php echo htmlspecialchars((string) $p->stock_status, ENT_QUOTES); ?>"
                                            data-stock_quantity="<?php echo $p->stock_quantity !== null ? (int) $p->stock_quantity : ''; ?>"
                                            data-unit="<?php echo htmlspecialchars((string) $p->unit, ENT_QUOTES); ?>"
                                            data-category="<?php echo htmlspecialchars((string) $p->category, ENT_QUOTES); ?>"
                                            data-image="<?php echo htmlspecialchars((string) $p->image, ENT_QUOTES); ?>"
                                        >Edit</button>
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Simpan</button>
                                        <?php if (trim((string) $p->image) !== ''): ?>
                                            <button type="submit" name="orion_shop_delete_image" value="1" class="mt-2 px-4 py-2 rounded-lg bg-white border border-rose-200 text-rose-700 font-extrabold hover:bg-rose-50 transition w-full">Hapus gambar</button>
                                        <?php endif; ?>
                                        <button type="submit" form="orion-delete-<?php echo (int) $p->id; ?>" class="mt-2 px-4 py-2 rounded-lg bg-white border border-rose-200 text-rose-700 font-extrabold hover:bg-rose-50 transition w-full" onclick="return confirm('Hapus produk ini?')">Hapus produk</button>
                                    </form>
                                    <form id="orion-delete-<?php echo (int) $p->id; ?>" method="POST">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="id" value="<?php echo (int) $p->id; ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="orion-product-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <div>
                <div class="text-sm font-extrabold text-slate-900" id="orion-product-modal-title">Tambah Produk</div>
                <div class="text-xs text-slate-500">Simpan produk, lalu atur gambar via field URL atau upload.</div>
            </div>
            <button type="button" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500" onclick="orionCloseProductModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form method="POST" class="p-5 space-y-4 overflow-y-auto max-h-[calc(90vh-72px)]" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_product">
            <input type="hidden" name="id" id="orion-product-id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">SKU</label>
                    <input type="text" name="sku" id="orion-product-sku" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Nama</label>
                    <input type="text" name="name" id="orion-product-name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Harga</label>
                    <input type="number" name="price" id="orion-product-price" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Harga Promo</label>
                    <input type="number" name="sale_price" id="orion-product-sale-price" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white" placeholder="Opsional">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Tipe</label>
                    <select name="type" id="orion-product-type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                        <option value="physical">Barang</option>
                        <option value="digital">Digital</option>
                        <option value="service">Jasa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Status Stok</label>
                    <select name="stock_status" id="orion-product-stock-status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="preorder">Preorder</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Jumlah Stok</label>
                    <input type="number" name="stock_quantity" id="orion-product-stock-quantity" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white" placeholder="Opsional">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Satuan</label>
                    <input type="text" name="unit" id="orion-product-unit" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white" placeholder="pcs / paket / layanan">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="category" id="orion-product-category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="orion-product-description" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Image URL (opsional)</label>
                    <input type="text" name="image" id="orion-product-image" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white" placeholder="orion-content/uploads/products/foto.jpg atau https://...">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-extrabold text-slate-700 mb-1">Upload Gambar (opsional)</label>
                    <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-lg cursor-pointer bg-white">
                    <div class="mt-1 text-xs text-slate-500">Auto resize/compress bila file besar, disimpan ke <span class="font-mono">orion-content/uploads/products/</span>.</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end pt-2">
                <button type="button" onclick="orionCloseProductModal()" class="px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-3 rounded-xl bg-orion-600 hover:bg-orion-700 text-white font-extrabold transition">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    function orionOpenProductModal() {
        document.getElementById('orion-product-modal-title').textContent = 'Tambah Produk';
        document.getElementById('orion-product-id').value = '';
        document.getElementById('orion-product-sku').value = '';
        document.getElementById('orion-product-name').value = '';
        document.getElementById('orion-product-description').value = '';
        document.getElementById('orion-product-price').value = '';
        document.getElementById('orion-product-sale-price').value = '';
        document.getElementById('orion-product-type').value = 'physical';
        document.getElementById('orion-product-stock-status').value = 'in_stock';
        document.getElementById('orion-product-stock-quantity').value = '';
        document.getElementById('orion-product-unit').value = '';
        document.getElementById('orion-product-category').value = '';
        document.getElementById('orion-product-image').value = '';
        const modal = document.getElementById('orion-product-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function orionCloseProductModal() {
        const modal = document.getElementById('orion-product-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function orionEditProduct(el) {
        document.getElementById('orion-product-modal-title').textContent = 'Edit Produk';
        document.getElementById('orion-product-id').value = el.dataset.id || '';
        document.getElementById('orion-product-sku').value = el.dataset.sku || '';
        document.getElementById('orion-product-name').value = el.dataset.name || '';
        document.getElementById('orion-product-description').value = el.dataset.description || '';
        document.getElementById('orion-product-price').value = el.dataset.price || '';
        document.getElementById('orion-product-sale-price').value = el.dataset.sale_price || '';
        document.getElementById('orion-product-type').value = el.dataset.type || 'physical';
        document.getElementById('orion-product-stock-status').value = el.dataset.stock_status || 'in_stock';
        document.getElementById('orion-product-stock-quantity').value = el.dataset.stock_quantity || '';
        document.getElementById('orion-product-unit').value = el.dataset.unit || '';
        document.getElementById('orion-product-category').value = el.dataset.category || '';
        document.getElementById('orion-product-image').value = el.dataset.image || '';
        const modal = document.getElementById('orion-product-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('orion-product-modal').addEventListener('click', function (e) {
        if (e.target === this) orionCloseProductModal();
    });
</script>
