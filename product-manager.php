<?php
/**
 * Product Manager for Orion Shop Theme
 */

// Bootstrap Orion Core
// Path: product-manager.php -> orion-load.php (in same root directory)
$bootstrap_path = __DIR__ . '/orion-load.php';
if (file_exists($bootstrap_path)) {
    require_once $bootstrap_path;
} else {
    die("Orion CMS Core not found at $bootstrap_path");
}

// Authentication Check
// if (!function_exists('is_user_logged_in') || !is_user_logged_in()) {
//    header("Location: " . site_url('/orion-admin/'));
//    exit;
// }

$message = '';
$error = '';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // DEBUG: Log request
    file_put_contents('debug_log.txt', print_r($_POST, true) . "\n" . print_r($_FILES, true), FILE_APPEND);

    // Delete Product
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['post_id'])) {
        $post_id = (int)$_POST['post_id'];
        if (wp_delete_post($post_id)) {
            $message = "Produk berhasil dihapus.";
        } else {
            $error = "Gagal menghapus produk.";
        }
    }

    // Add/Edit Product
    if (isset($_POST['action']) && ($_POST['action'] === 'create' || $_POST['action'] === 'update')) {
        $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : '';
        $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : '';
        $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
        $stock_status = isset($_POST['stock_status']) ? $_POST['stock_status'] : 'in_stock';
        $image_url = isset($_POST['existing_image_url']) ? trim($_POST['existing_image_url']) : '';
        $category_id = isset($_POST['category']) ? (int)$_POST['category'] : 0;
        
        // New Fields
        $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
        $series = isset($_POST['series']) ? trim($_POST['series']) : '';
        $short_description = isset($_POST['short_description']) ? trim($_POST['short_description']) : '';
        $specifications = isset($_POST['specifications']) ? trim($_POST['specifications']) : '';
        $variants = isset($_POST['variants']) ? trim($_POST['variants']) : '';
        
        $gallery_images = array();
        // Handle Existing Gallery Images (hidden inputs)
        if (isset($_POST['existing_gallery_images']) && is_array($_POST['existing_gallery_images'])) {
            $gallery_images = $_POST['existing_gallery_images'];
        }
        
        // Handle New Gallery Images Upload
        if (isset($_FILES['gallery_images'])) {
            $upload_dir = __DIR__ . '/orion-content/uploads/products/';
             if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = "Gagal membuat folder upload.";
                }
            }
            
            if (empty($error)) {
                $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                
                // Loop through uploaded files
                foreach ($_FILES['gallery_images']['name'] as $key => $name) {
                    // Check if file was uploaded
                    if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($file_ext, $allowed_exts)) {
                             $new_filename = uniqid('prod_gallery_') . '.' . $file_ext;
                             $target_file = $upload_dir . $new_filename;
                             
                             if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $target_file)) {
                                $gallery_images[] = site_url('/orion-content/uploads/products/' . $new_filename);
                            } else {
                                $error = "Gagal menyimpan file: " . htmlspecialchars($name);
                            }
                        } else {
                            $error = "Format file tidak didukung: " . htmlspecialchars($name);
                        }
                    } elseif ($_FILES['gallery_images']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                        // Capture upload errors (size limit, etc)
                        $error = "Error upload pada file " . htmlspecialchars($name) . " (Kode: " . $_FILES['gallery_images']['error'][$key] . ")";
                        // DEBUG: Log upload error
                        file_put_contents('debug_upload_error.txt', $error . "\n", FILE_APPEND);
                    }
                }
            }
        }

        // Handle File Upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/orion-content/uploads/products/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = "Gagal membuat folder upload utama.";
                }
            }
            
            if (empty($error)) {
                $file_ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
                $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                
                if (in_array($file_ext, $allowed_exts)) {
                    $new_filename = uniqid('prod_') . '.' . $file_ext;
                    $target_file = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                        $image_url = site_url('/orion-content/uploads/products/' . $new_filename);
                    } else {
                        $error = "Gagal mengupload gambar utama.";
                    }
                } else {
                    $error = "Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.";
                }
            }
        } elseif (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
             $error = "Error upload gambar utama (Kode: " . $_FILES['product_image']['error'] . ")";
        }

        if ($title && empty($error)) {
            $post_data = array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'post'
            );

            if ($_POST['action'] === 'update' && isset($_POST['post_id'])) {
                $post_data['ID'] = (int)$_POST['post_id'];
                $post_id = wp_insert_post($post_data); // Updates if ID exists
                $message = "Produk berhasil diperbarui.";
            } else {
                $post_id = wp_insert_post($post_data);
                $message = "Produk berhasil ditambahkan.";
            }

            if ($post_id) {
                // Save Meta
                update_post_meta($post_id, 'shop_price', $price);
                update_post_meta($post_id, 'shop_stock_status', $stock_status);
                
                // Save New Meta Fields
                update_post_meta($post_id, 'shop_brand', $brand);
                update_post_meta($post_id, 'shop_series', $series);
                update_post_meta($post_id, 'shop_short_description', $short_description);
                update_post_meta($post_id, 'shop_specifications', $specifications);
                update_post_meta($post_id, 'shop_variants', $variants);
                
                // Handle Image
                if ($image_url) {
                    update_post_meta($post_id, '_thumbnail_url', $image_url); // Main image
                }
                
                // Handle Gallery (Merge main image if not present, but user logic might differ)
                // We keep _gallery_images as the full list of additional images + maybe main image if desired
                // But typically gallery is separate. Let's save the array.
                
                if (!empty($gallery_images)) {
                    update_post_meta($post_id, '_gallery_images', json_encode(array_values($gallery_images)));
                } else {
                     // If empty, maybe clear it? Or keep it if we didn't touch it?
                     // Logic above handles "existing", so if user deleted all, it's empty.
                     update_post_meta($post_id, '_gallery_images', json_encode(array()));
                }

                // Handle Category
                if ($category_id > 0) {
                    wp_set_object_terms($post_id, array($category_id), 'product_cat');
                }
            } else {
                $error = "Gagal menyimpan produk.";
            }
        } else {
            $error = "Judul produk wajib diisi.";
        }
    }

    // Update Hero Settings
    if (isset($_POST['action']) && $_POST['action'] === 'update_hero') {
        $hero_settings = get_option('orion_shop_hero_settings', array());
        if (!is_array($hero_settings)) $hero_settings = array();

        // Helper to handle upload
        $handle_upload = function($file_key) {
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/orion-content/uploads/banners/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $file_ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
                $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                if (in_array($file_ext, $allowed_exts)) {
                    $new_filename = uniqid('banner_') . '.' . $file_ext;
                    $target_file = $upload_dir . $new_filename;
                    if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $target_file)) {
                        return site_url('/orion-content/uploads/banners/' . $new_filename);
                    }
                }
            }
            return null;
        };

        // Main Banner
        $hero_settings['main'] = array(
            'title' => isset($_POST['main_title']) ? $_POST['main_title'] : '',
            'subtitle' => isset($_POST['main_subtitle']) ? $_POST['main_subtitle'] : '',
            'btn_text' => isset($_POST['main_btn_text']) ? $_POST['main_btn_text'] : '',
            'btn_link' => isset($_POST['main_btn_link']) ? $_POST['main_btn_link'] : '',
            'image' => isset($_POST['existing_main_image']) ? $_POST['existing_main_image'] : ''
        );
        if ($url = $handle_upload('main_image')) {
            $hero_settings['main']['image'] = $url;
        }

        // Side 1
        $hero_settings['side1'] = array(
            'title' => isset($_POST['side1_title']) ? $_POST['side1_title'] : '',
            'subtitle' => isset($_POST['side1_subtitle']) ? $_POST['side1_subtitle'] : '',
            'image' => isset($_POST['existing_side1_image']) ? $_POST['existing_side1_image'] : ''
        );
        if ($url = $handle_upload('side1_image')) {
            $hero_settings['side1']['image'] = $url;
        }

        // Side 2
        $hero_settings['side2'] = array(
            'title' => isset($_POST['side2_title']) ? $_POST['side2_title'] : '',
            'subtitle' => isset($_POST['side2_subtitle']) ? $_POST['side2_subtitle'] : '',
            'image' => isset($_POST['existing_side2_image']) ? $_POST['existing_side2_image'] : ''
        );
        if ($url = $handle_upload('side2_image')) {
            $hero_settings['side2']['image'] = $url;
        }

        update_option('orion_shop_hero_settings', $hero_settings);
        $message = "Pengaturan Hero berhasil diperbarui.";
    }
}

// Get Product for Edit Mode
$edit_post = null;
$edit_price = 0;
$edit_stock = 'in_stock';
$edit_image = '';
$edit_cat = 0;
$edit_brand = '';
$edit_series = '';
$edit_short_desc = '';
$edit_specs = '';
$edit_variants = '';
$edit_gallery = array();

if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_post = get_post($_GET['id']);
    if ($edit_post) {
        $edit_price = function_exists('orion_shop_get_price') ? orion_shop_get_price($edit_post->ID) : 0;
        $edit_stock = function_exists('orion_shop_get_stock_status') ? orion_shop_get_stock_status($edit_post->ID) : 'in_stock';
        $edit_image = function_exists('orion_shop_get_image') ? orion_shop_get_image($edit_post->ID) : '';
        
        // Get new meta
        $edit_brand = get_post_meta($edit_post->ID, 'shop_brand', true);
        $edit_series = get_post_meta($edit_post->ID, 'shop_series', true);
        $edit_short_desc = get_post_meta($edit_post->ID, 'shop_short_description', true);
        $edit_specs = get_post_meta($edit_post->ID, 'shop_specifications', true);
        $edit_variants = get_post_meta($edit_post->ID, 'shop_variants', true);
        
        $gallery_json = get_post_meta($edit_post->ID, '_gallery_images', true);
        if ($gallery_json) {
            $edit_gallery = json_decode($gallery_json, true);
            if (!is_array($edit_gallery)) $edit_gallery = array();
        }
        
        // Get category
        $cats = get_the_terms($edit_post->ID, 'product_cat'); 
    }
}

// Get All Categories
$categories = function_exists('get_terms') ? get_terms('product_cat') : array();

// Get All Products
$args = array(
    'numberposts' => -1,
    'post_type'   => 'post',
    'post_status' => 'publish'
);

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $args['s'] = $_GET['search'];
}

$products = get_posts($args);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Orion Shop</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        shop: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        dark: {
                            800: '#1f2937',
                            900: '#111827',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 antialiased">

<div class="min-h-screen flex flex-col md:flex-row">
    
    <!-- Modern Sidebar -->
    <aside class="w-full md:w-72 bg-slate-900 text-white flex-shrink-0 md:fixed md:h-full md:overflow-y-auto z-20">
        <div class="p-6 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="bg-shop-600 text-white p-2 rounded-lg shadow-lg shadow-shop-500/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Orion Shop</h1>
                    <p class="text-xs text-slate-400 font-medium">Manager Dashboard</p>
                </div>
            </div>
        </div>
        
        <div class="px-4 py-6">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
            <nav class="space-y-1">
                <a href="product-manager.php" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 <?php echo (!isset($_GET['view']) || $_GET['view'] !== 'hero') ? 'bg-shop-600 text-white shadow-lg shadow-shop-900/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <svg class="w-5 h-5 mr-3 <?php echo (!isset($_GET['view']) || $_GET['view'] !== 'hero') ? 'text-white' : 'text-slate-400 group-hover:text-white transition-colors'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Produk
                </a>
                <a href="product-manager.php?view=hero" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 <?php echo (isset($_GET['view']) && $_GET['view'] === 'hero') ? 'bg-shop-600 text-white shadow-lg shadow-shop-900/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <svg class="w-5 h-5 mr-3 <?php echo (isset($_GET['view']) && $_GET['view'] === 'hero') ? 'text-white' : 'text-slate-400 group-hover:text-white transition-colors'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kelola Hero
                </a>
            </nav>

            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-8">Sistem</p>
            <nav class="space-y-1">
                <a href="index.php" target="_blank" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Lihat Toko
                </a>
                <a href="orion-admin/" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Kembali ke Admin
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-slate-800 absolute bottom-0 w-full bg-slate-900/50 backdrop-blur-sm">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-shop-400 to-purple-500 flex items-center justify-center text-white font-bold text-xs">
                    AD
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">Administrator</p>
                    <p class="text-xs text-slate-400">Super Admin</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:ml-72 flex flex-col min-h-screen transition-all duration-300">
        
        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-10">
            <div class="px-8 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">
                    <?php echo (isset($_GET['view']) && $_GET['view'] === 'hero') ? 'Pengaturan Tampilan' : 'Manajemen Produk'; ?>
                </h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        <?php echo date('d M Y'); ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-8 overflow-y-auto">
            
            <?php if (isset($_GET['view']) && $_GET['view'] === 'hero'): ?>
                <?php 
                $hero = get_option('orion_shop_hero_settings', array());
                $main = isset($hero['main']) ? $hero['main'] : array();
                $side1 = isset($hero['side1']) ? $hero['side1'] : array();
                $side2 = isset($hero['side2']) ? $hero['side2'] : array();
                ?>
                
                <div class="max-w-5xl mx-auto">
                    <!-- Notifications -->
                    <?php if ($message): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm" role="alert">
                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium"><?php echo $message; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm" role="alert">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium"><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900">Hero Banner Homepage</h3>
                            <p class="text-sm text-gray-500">Sesuaikan tampilan banner utama toko Anda.</p>
                        </div>
                        
                        <form action="product-manager.php?view=hero" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
                            <input type="hidden" name="action" value="update_hero">
                            
                            <!-- Main Banner Section -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <h4 class="text-base font-semibold text-gray-900 mb-1">Banner Utama</h4>
                                    <p class="text-sm text-gray-500 mb-4">Banner besar di sebelah kiri. Ukuran optimal: 800x600px.</p>
                                    
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar</label>
                                        <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 relative group">
                                            <?php if (isset($main['image']) && $main['image']): ?>
                                                <img src="<?php echo htmlspecialchars($main['image']); ?>" class="w-full h-full object-cover">
                                                <input type="hidden" name="existing_main_image" value="<?php echo htmlspecialchars($main['image']); ?>">
                                            <?php else: ?>
                                                <div class="flex items-center justify-center h-full text-gray-400">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            <?php endif; ?>
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-medium">Ganti Gambar</span>
                                            </div>
                                            <input type="file" name="main_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" title="Klik untuk mengganti gambar">
                                        </div>
                                    </div>
                                </div>
                                <div class="lg:col-span-2 space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama</label>
                                        <input type="text" name="main_title" value="<?php echo htmlspecialchars(isset($main['title']) ? $main['title'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul</label>
                                        <input type="text" name="main_subtitle" value="<?php echo htmlspecialchars(isset($main['subtitle']) ? $main['subtitle'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol</label>
                                            <input type="text" name="main_btn_text" value="<?php echo htmlspecialchars(isset($main['btn_text']) ? $main['btn_text'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Tombol</label>
                                            <input type="text" name="main_btn_link" value="<?php echo htmlspecialchars(isset($main['btn_link']) ? $main['btn_link'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Side 1 -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <h4 class="text-base font-semibold text-gray-900 mb-1">Banner Samping Atas</h4>
                                    <p class="text-sm text-gray-500 mb-4">Banner kecil di kanan atas.</p>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar</label>
                                        <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden border border-gray-200 relative group">
                                            <?php if (isset($side1['image']) && $side1['image']): ?>
                                                <img src="<?php echo htmlspecialchars($side1['image']); ?>" class="w-full h-full object-cover">
                                                <input type="hidden" name="existing_side1_image" value="<?php echo htmlspecialchars($side1['image']); ?>">
                                            <?php else: ?>
                                                <div class="flex items-center justify-center h-full text-gray-400">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="side1_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </div>
                                    </div>
                                </div>
                                <div class="lg:col-span-2 space-y-5 flex flex-col justify-center">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                                        <input type="text" name="side1_title" value="<?php echo htmlspecialchars(isset($side1['title']) ? $side1['title'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul</label>
                                        <input type="text" name="side1_subtitle" value="<?php echo htmlspecialchars(isset($side1['subtitle']) ? $side1['subtitle'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Side 2 -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <h4 class="text-base font-semibold text-gray-900 mb-1">Banner Samping Bawah</h4>
                                    <p class="text-sm text-gray-500 mb-4">Banner kecil di kanan bawah.</p>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar</label>
                                        <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden border border-gray-200 relative group">
                                            <?php if (isset($side2['image']) && $side2['image']): ?>
                                                <img src="<?php echo htmlspecialchars($side2['image']); ?>" class="w-full h-full object-cover">
                                                <input type="hidden" name="existing_side2_image" value="<?php echo htmlspecialchars($side2['image']); ?>">
                                            <?php else: ?>
                                                <div class="flex items-center justify-center h-full text-gray-400">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="side2_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </div>
                                    </div>
                                </div>
                                <div class="lg:col-span-2 space-y-5 flex flex-col justify-center">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                                        <input type="text" name="side2_title" value="<?php echo htmlspecialchars(isset($side2['title']) ? $side2['title'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul</label>
                                        <input type="text" name="side2_subtitle" value="<?php echo htmlspecialchars(isset($side2['subtitle']) ? $side2['subtitle'] : ''); ?>" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-shop-500 focus:ring-shop-500 sm:text-sm px-4 py-2 border">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit" class="bg-shop-600 hover:bg-shop-700 text-white px-8 py-3 rounded-xl shadow-lg shadow-shop-600/30 font-semibold transition-all transform hover:-translate-y-0.5">
                                    Simpan Pengaturan Hero
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                
                <!-- Product List View -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Daftar Produk</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola inventaris dan katalog produk Anda.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <form action="" method="GET" class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-shop-500 focus:border-shop-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Cari produk...">
                        </form>
                        <button onclick="window.location.href='product-manager.php?action=create'" class="bg-shop-600 hover:bg-shop-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-shop-600/30 font-medium transition-all transform hover:-translate-y-0.5 flex items-center justify-center group whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Produk
                        </button>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm" role="alert">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm" role="alert">
                        <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Info Produk</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php foreach ($products as $p): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                                <?php 
                                                $img = function_exists('orion_shop_get_image') ? orion_shop_get_image($p->ID) : ''; 
                                                ?>
                                                <img class="h-full w-full object-cover" src="<?php echo $img ? $img : 'https://via.placeholder.com/150'; ?>" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900"><?php echo $p->post_title; ?></div>
                                                <div class="text-xs text-gray-500 truncate max-w-[200px]"><?php echo substr(strip_tags($p->post_content), 0, 50); ?>...</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo function_exists('orion_shop_get_price') ? orion_shop_format_price(orion_shop_get_price($p->ID)) : 'Rp 0'; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        $product_cats = get_the_terms($p->ID, 'product_cat');
                                        if ($product_cats && !is_wp_error($product_cats)) {
                                            $cat_names = array();
                                            foreach ($product_cats as $pc) {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mr-1">' . $pc->name . '</span>';
                                            }
                                        } else {
                                            echo '<span class="text-gray-400 text-xs">-</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        $status = function_exists('orion_shop_get_stock_status') ? orion_shop_get_stock_status($p->ID) : 'in_stock';
                                        if ($status === 'in_stock'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                                Tersedia
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                                Habis
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="product-manager.php?action=edit&id=<?php echo $p->ID; ?>" class="text-slate-400 hover:text-shop-600 transition-colors p-1 rounded-md hover:bg-shop-50" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="product-manager.php" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="post_id" value="<?php echo $p->ID; ?>">
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-1 rounded-md hover:bg-red-50" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                                                Tidak ada produk yang cocok dengan pencarian "<strong><?php echo htmlspecialchars($_GET['search']); ?></strong>". <a href="product-manager.php" class="text-shop-600 hover:text-shop-700 font-medium">Reset pencarian</a>
                                            <?php else: ?>
                                                Belum ada produk. Silakan tambahkan produk baru.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php endif; ?>

        </main>
    </div>
</div>

<!-- Modal Form (Modernized) -->
<div id="product-form-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 <?php echo $edit_post ? '' : 'hidden'; ?> flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all scale-100">
        <!-- Modal Header -->
        <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-xl font-bold text-gray-900"><?php echo $edit_post ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h3>
                <p class="text-sm text-gray-500 mt-1">Lengkapi informasi produk di bawah ini.</p>
            </div>
            <button onclick="window.location.href='product-manager.php'" class="text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="overflow-y-auto flex-1 p-8">
            <form action="product-manager.php" method="POST" enctype="multipart/form-data" class="space-y-8">
                <input type="hidden" name="action" value="<?php echo $edit_post ? 'update' : 'create'; ?>">
                <?php if ($edit_post): ?>
                    <input type="hidden" name="post_id" value="<?php echo $edit_post->ID; ?>">
                <?php endif; ?>

                <!-- Section: Basic Info -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Dasar</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="post_title" required value="<?php echo $edit_post ? htmlspecialchars($edit_post->post_title) : ''; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="Contoh: Sepatu Lari Nike Air Zoom">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                            <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="Ringkasan produk yang menarik..."><?php echo htmlspecialchars($edit_short_desc); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Details -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Detail & Harga</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (IDR) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" value="<?php echo $edit_price; ?>" class="w-full pl-10 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition bg-white">
                                <option value="">Pilih Kategori</option>
                                <?php 
                                $current_cat_id = 0;
                                if ($edit_post) {
                                    $cats = get_the_terms($edit_post->ID, 'product_cat');
                                    if ($cats && !empty($cats)) {
                                        $current_cat_id = $cats[0]->term_id;
                                    }
                                }
                                foreach ($categories as $cat): 
                                    $selected = ($cat->term_id == $current_cat_id) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $cat->term_id; ?>" <?php echo $selected; ?>><?php echo $cat->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Merk (Brand)</label>
                            <input type="text" name="brand" value="<?php echo htmlspecialchars($edit_brand); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="Contoh: Nike, Adidas">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Stok</label>
                            <select name="stock_status" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition bg-white">
                                <option value="in_stock" <?php echo $edit_stock === 'in_stock' ? 'selected' : ''; ?>>Tersedia (In Stock)</option>
                                <option value="out_of_stock" <?php echo $edit_stock === 'out_of_stock' ? 'selected' : ''; ?>>Habis (Out of Stock)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Media -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Media Produk</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Image -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                            <div class="relative group aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center hover:border-shop-500 transition-colors">
                                <?php if ($edit_image): ?>
                                    <img src="<?php echo htmlspecialchars($edit_image); ?>" class="absolute inset-0 w-full h-full object-cover rounded-xl z-0">
                                <?php else: ?>
                                    <div class="z-10 flex flex-col items-center text-gray-400">
                                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium">Upload Utama</span>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_image); ?>">
                                <input type="file" name="product_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*">
                            </div>
                        </div>
                        
                        <!-- Gallery -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Galeri Foto</label>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="grid grid-cols-4 gap-3 mb-3">
                                    <?php if (!empty($edit_gallery)): ?>
                                        <?php foreach ($edit_gallery as $idx => $g_img): ?>
                                            <div class="relative group aspect-square" id="gallery-item-<?php echo $idx; ?>">
                                                <img src="<?php echo htmlspecialchars($g_img); ?>" class="w-full h-full object-cover rounded-lg shadow-sm border border-gray-200">
                                                <input type="hidden" name="existing_gallery_images[]" value="<?php echo htmlspecialchars($g_img); ?>">
                                                <button type="button" onclick="if(confirm('Hapus foto ini?')) { document.getElementById('gallery-item-<?php echo $idx; ?>').remove(); }" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-transform transform hover:scale-110">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!-- Add New Button Placeholder -->
                                    <div class="aspect-square bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 hover:border-shop-500 hover:text-shop-500 transition-colors relative">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Klik ikon + untuk menambah foto. Bisa pilih banyak sekaligus.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Advanced -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Lengkap</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lengkap</label>
                            <textarea name="post_content" rows="6" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="Jelaskan detail produk secara lengkap..."><?php echo $edit_post ? htmlspecialchars($edit_post->post_content) : ''; ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi</label>
                                <textarea name="specifications" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition font-mono text-sm" placeholder="Bahan: Katun&#10;Ukuran: XL"><?php echo htmlspecialchars($edit_specs); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Varian</label>
                                <textarea name="variants" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-shop-500 focus:border-shop-500 outline-none transition" placeholder="Merah, Biru, Hijau"><?php echo htmlspecialchars($edit_variants); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3 sticky bottom-0 bg-white z-10">
                    <button type="button" onclick="window.location.href='product-manager.php'" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition">Batal</button>
                    <button type="submit" class="bg-shop-600 hover:bg-shop-700 text-white px-8 py-2.5 rounded-xl shadow-lg shadow-shop-600/30 font-semibold transition-all transform hover:-translate-y-0.5">
                        <?php echo $edit_post ? 'Simpan Perubahan' : 'Simpan Produk'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

