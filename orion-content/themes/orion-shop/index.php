<?php
get_header();
global $orion_query, $post;

// Determine if we are viewing a single product
$is_single = (isset($_GET['p']) && $_GET['p']);
$page_slug = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : '';

// Filtering Logic
$search_query = isset($_GET['s']) ? $_GET['s'] : '';
$category_filter = isset($_GET['cat']) ? $_GET['cat'] : (isset($_GET['category']) ? $_GET['category'] : ''); // Allow string slug or ID
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Setup Query
if ($is_single) {
    // Single Product Query
    $args = array(
        'p' => (int)$_GET['p'],
        'post_type' => 'post',
        'post_status' => 'publish'
    );
    $orion_query = new WP_Query($args);
} else {
    // List Query
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
        'taxonomy' => 'product_cat'
    );

    if ($search_query) {
        $args['s'] = $search_query;
    }

    if ($category_filter) {
        // If it's numeric, use category ID, else assume it's a slug or something else (simplified for demo)
        if (is_numeric($category_filter)) {
            $args['category'] = $category_filter;
            $args['taxonomy'] = 'product_cat';
        }
    }

    // Default Sort (by date)
    if ($sort_order == 'oldest') {
        $args['order'] = 'ASC';
    } else {
        $args['order'] = 'DESC';
    }

    // Re-run query
    $orion_query = new WP_Query($args);

    // Manual Price Sorting
    if ($sort_order == 'price_asc' || $sort_order == 'price_low') {
        usort($orion_query->posts, function($a, $b) {
            $price_a = function_exists('orion_shop_get_price') ? orion_shop_get_price($a->ID) : 0;
            $price_b = function_exists('orion_shop_get_price') ? orion_shop_get_price($b->ID) : 0;
            return $price_a - $price_b;
        });
    } elseif ($sort_order == 'price_desc' || $sort_order == 'price_high') {
        usort($orion_query->posts, function($a, $b) {
            $price_a = function_exists('orion_shop_get_price') ? orion_shop_get_price($a->ID) : 0;
            $price_b = function_exists('orion_shop_get_price') ? orion_shop_get_price($b->ID) : 0;
            return $price_b - $price_a;
        });
    }
}

// Get Categories for Filter
// Prefer our custom helper for the sidebar to get icons
$categories = function_exists('orion_shop_get_categories') ? orion_shop_get_categories() : array();

// Helper for Flash Sale (Random 5 products)
$flash_products = array();
if (!$is_single && !$search_query && !$category_filter) {
    $flash_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => 5,
        'orderby' => 'rand',
        'taxonomy' => 'product_cat'
    );
    $flash_products = get_posts($flash_args);
}
?>

<!-- Single Product View -->
<?php if ($is_single): ?>
    <div id="shop-products" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 bg-gray-50">
        <?php if ($orion_query->have_posts()): $orion_query->the_post(); 
            // Retrieve additional meta data
            $brand = get_post_meta($post->ID, 'shop_brand', true);
            $series = get_post_meta($post->ID, 'shop_series', true);
            $short_desc = get_post_meta($post->ID, 'shop_short_description', true);
            $specs = get_post_meta($post->ID, 'shop_specifications', true);
            $variants = get_post_meta($post->ID, 'shop_variants', true);
            $gallery = get_post_meta($post->ID, '_gallery_images', true);
            $gallery_images = !empty($gallery) ? json_decode($gallery, true) : array();
        ?>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="md:flex">
                    <div class="md:flex-shrink-0 md:w-1/2 bg-gray-50 flex flex-col">
                        <div class="relative flex-grow">
                            <img id="main-product-image" class="h-full w-full object-cover" src="<?php echo orion_shop_get_image($post->ID); ?>" alt="<?php echo $post->post_title; ?>">
                            <div class="absolute top-4 left-4">
                                <a href="index.php" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-lg hover:bg-white transition text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                </a>
                            </div>
                        </div>
                        <?php if (!empty($gallery_images)): ?>
                            <div class="p-4 grid grid-cols-5 gap-2 bg-white border-t border-gray-100">
                                <div class="cursor-pointer border-2 border-indigo-500 rounded-lg overflow-hidden aspect-w-1 aspect-h-1" onclick="document.getElementById('main-product-image').src='<?php echo orion_shop_get_image($post->ID); ?>'">
                                    <img src="<?php echo orion_shop_get_image($post->ID); ?>" class="w-full h-full object-cover">
                                </div>
                                <?php foreach ($gallery_images as $g_img): ?>
                                    <div class="cursor-pointer border border-gray-200 hover:border-indigo-500 rounded-lg overflow-hidden aspect-w-1 aspect-h-1 transition" onclick="document.getElementById('main-product-image').src='<?php echo htmlspecialchars($g_img); ?>'">
                                        <img src="<?php echo htmlspecialchars($g_img); ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-8 md:p-12 md:w-1/2 flex flex-col justify-center">
                        <div class="flex justify-between items-center mb-2">
                            <div class="uppercase tracking-wide text-sm text-shop-600 font-bold">Produk Detail</div>
                            <?php if (function_exists('is_user_logged_in') && is_user_logged_in() && function_exists('current_user_can') && current_user_can('administrator')): ?>
                                <a href="product-manager.php?action=edit&id=<?php echo $post->ID; ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors duration-200">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Produk
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if ($brand || $series): ?>
                            <div class="text-sm font-medium text-indigo-600 mb-1">
                                <?php echo $brand ? htmlspecialchars($brand) : ''; ?>
                                <?php echo ($brand && $series) ? ' <span class="text-gray-300">|</span> ' : ''; ?>
                                <?php echo $series ? htmlspecialchars($series) : ''; ?>
                            </div>
                        <?php endif; ?>

                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 leading-tight"><?php echo $post->post_title; ?></h1>
                        
                        <?php if ($short_desc): ?>
                            <p class="text-gray-500 text-lg mb-4"><?php echo nl2br(htmlspecialchars($short_desc)); ?></p>
                        <?php endif; ?>

                        <div class="flex items-center mb-6">
                            <?php 
                            $price = 0;
                            if (function_exists('orion_shop_get_price')) {
                                $price = orion_shop_get_price($post->ID);
                                echo '<span class="text-4xl font-bold text-shop-600">' . orion_shop_format_price($price) . '</span>';
                            }
                            ?>
                        </div>

                        <div class="flex items-center text-sm font-medium text-gray-500 mb-6 bg-gray-50 p-3 rounded-lg w-max">
                            <svg class="flex-shrink-0 mr-2 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Stok: <span class="text-gray-900 ml-1">
                            <?php 
                            if (function_exists('orion_shop_get_stock')) {
                                echo orion_shop_get_stock($post->ID); 
                            } else {
                                echo '0';
                            }
                            ?>
                            </span>
                        </div>

                        <div class="prose prose-lg prose-indigo text-gray-600 mb-8">
                            <?php echo nl2br($post->post_content); ?>
                        </div>

                        <?php if ($specs || $variants): ?>
                            <div class="bg-gray-50 rounded-xl p-6 mb-8 space-y-6">
                                <?php if ($specs): ?>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Spesifikasi Produk</h3>
                                        <div class="prose prose-sm text-gray-600">
                                            <?php echo nl2br(htmlspecialchars($specs)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($variants): ?>
                                    <?php if ($specs) echo '<div class="border-t border-gray-200"></div>'; ?>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Pilihan Varian</h3>
                                        <div class="prose prose-sm text-gray-600">
                                            <?php echo nl2br(htmlspecialchars($variants)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-auto">
                            <?php 
                            if (function_exists('orion_shop_get_whatsapp_url')) {
                                $wa_url = orion_shop_get_whatsapp_url($post->ID);
                                ?>
                                <a href="<?php echo $wa_url; ?>" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/30 transition transform hover:-translate-y-1">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Beli via WhatsApp
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Lainnya</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php
                    $current_id = $post->ID;
                    $related_args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'numberposts' => 5
                    );
                    $related_query = new WP_Query($related_args);
                    $count = 0;
                    while ($related_query->have_posts()): $related_query->the_post();
                        if ($post->ID == $current_id) continue;
                        if ($count >= 4) break;
                        $count++;
                    ?>
                        <a href="index.php?p=<?php echo $post->ID; ?>" class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden block">
                            <div class="relative h-64 bg-gray-100 overflow-hidden">
                                <img src="<?php echo orion_shop_get_image($post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-1 truncate group-hover:text-shop-600 transition"><?php echo $post->post_title; ?></h3>
                                <div class="text-shop-600 font-bold">
                                    <?php echo function_exists('orion_shop_get_price') ? orion_shop_format_price(orion_shop_get_price($post->ID)) : ''; ?>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php elseif ($page_slug): ?>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php 
            // Sanitize slug to prevent directory traversal
            $safe_slug = preg_replace('/[^a-zA-Z0-9-]/', '', $page_slug);
            $page_file = __DIR__ . '/pages/' . $safe_slug . '.php';
            
            if (file_exists($page_file)) {
                include $page_file;
            } else {
                echo '<div class="bg-white rounded-xl shadow-sm p-10 text-center">';
                echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">Halaman Tidak Ditemukan</h1>';
                echo '<p class="text-gray-500 mb-6">Maaf, halaman yang Anda cari tidak tersedia.</p>';
                echo '<a href="index.php" class="inline-block bg-shop-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-shop-700 transition">Kembali ke Beranda</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

<?php else: ?>

    <!-- LANDING PAGE LAYOUT -->
    
    <!-- 1. Hero & Sidebar Section -->
    <?php if (!$search_query): ?>
        <?php 
        $hero = get_option('orion_shop_hero_settings', array());
        $main = isset($hero['main']) ? $hero['main'] : array();
        
        // Defaults
        $main_img = !empty($main['image']) ? $main['image'] : 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80';
        $main_title = !empty($main['title']) ? $main['title'] : 'Big Sale Ramadan';
        $main_subtitle = !empty($main['subtitle']) ? $main['subtitle'] : 'Diskon hingga 70%';
        $main_btn_text = !empty($main['btn_text']) ? $main['btn_text'] : 'Cek Sekarang';
        $main_btn_link = !empty($main['btn_link']) ? $main['btn_link'] : '#flash-sale';
        ?>
        
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex gap-6 py-4">
                    
                    <!-- Sidebar (Categories) - Hidden on Mobile -->
                    <div class="w-64 flex-shrink-0 hidden md:block">
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b font-bold text-gray-700">Kategori</div>
                            <ul class="divide-y divide-gray-100">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach (array_slice($categories, 0, 10) as $cat): ?>
                                    <li>
                                        <a href="index.php?cat=<?php echo isset($cat['id']) ? $cat['id'] : ''; ?>&taxonomy=product_cat" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-shop-50 hover:text-shop-600 transition flex items-center justify-between group">
                                            <span class="flex items-center gap-2">
                                                <?php if (isset($cat['icon'])): ?>
                                                    <span class="text-gray-400 group-hover:text-shop-500"><?php echo $cat['icon']; ?></span>
                                                <?php endif; ?>
                                                <?php echo $cat['name']; ?>
                                            </span>
                                            <svg class="w-3 h-3 text-gray-300 group-hover:text-shop-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="px-4 py-4 text-sm text-gray-500 text-center">Belum ada kategori</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Main Hero Slider -->
                    <div class="flex-1 min-w-0">
                        <div class="relative rounded-xl overflow-hidden shadow-md h-[300px] md:h-[400px] group">
                            <img src="<?php echo htmlspecialchars($main_img); ?>" alt="Hero" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex flex-col justify-center px-8 md:px-12">
                                <span class="text-yellow-400 font-bold uppercase tracking-wider mb-2 text-sm md:text-base animate-pulse"><?php echo htmlspecialchars($main_subtitle); ?></span>
                                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight max-w-lg"><?php echo htmlspecialchars($main_title); ?></h2>
                                <a href="<?php echo htmlspecialchars($main_btn_link); ?>" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-1 w-max">
                                    <?php echo htmlspecialchars($main_btn_text); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Promo Column - Hidden on Mobile/Tablet -->
                    <div class="w-64 flex-shrink-0 hidden lg:block space-y-4">
                        <?php 
                        $side1 = isset($hero['side1']) ? $hero['side1'] : array();
                        $side2 = isset($hero['side2']) ? $hero['side2'] : array();
                        ?>

                        <!-- Side Banner 1 -->
                        <?php if (!empty($side1['image'])): ?>
                             <div class="relative rounded-xl overflow-hidden shadow-sm h-[192px] group">
                                <img src="<?php echo htmlspecialchars($side1['image']); ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex flex-col justify-center px-4">
                                    <h4 class="text-white font-bold text-lg leading-tight mb-1"><?php echo htmlspecialchars(isset($side1['title']) ? $side1['title'] : ''); ?></h4>
                                    <p class="text-white/90 text-xs"><?php echo htmlspecialchars(isset($side1['subtitle']) ? $side1['subtitle'] : ''); ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Default User Welcome -->
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm h-[192px] flex flex-col justify-center">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="bg-gray-100 p-2 rounded-full">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Selamat Datang!</p>
                                        <p class="text-sm font-bold text-gray-800">di Orion Shop</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="#" class="flex-1 bg-shop-50 text-shop-600 text-center text-xs font-bold py-2 rounded-lg hover:bg-shop-100 transition">Masuk</a>
                                    <a href="#" class="flex-1 bg-orange-50 text-orange-600 text-center text-xs font-bold py-2 rounded-lg hover:bg-orange-100 transition">Daftar</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Side Banner 2 -->
                        <?php if (!empty($side2['image'])): ?>
                             <div class="relative rounded-xl overflow-hidden shadow-sm h-[192px] group">
                                <img src="<?php echo htmlspecialchars($side2['image']); ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex flex-col justify-center px-4">
                                    <h4 class="text-white font-bold text-lg leading-tight mb-1"><?php echo htmlspecialchars(isset($side2['title']) ? $side2['title'] : ''); ?></h4>
                                    <p class="text-white/90 text-xs"><?php echo htmlspecialchars(isset($side2['subtitle']) ? $side2['subtitle'] : ''); ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Default Mini Promo -->
                            <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 text-center h-[192px] flex flex-col justify-center">
                                <h4 class="font-bold text-orange-800 mb-1">Voucher Baru</h4>
                                <p class="text-xs text-orange-600 mb-3">Dapatkan potongan 50rb</p>
                                <div class="border-2 border-dashed border-orange-300 bg-white py-1 px-2 rounded text-xs font-mono font-bold text-gray-600">ORION50</div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Features Bar -->
        <div class="bg-white border-b mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center space-x-3 justify-center md:justify-start">
                        <div class="text-shop-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div><div class="font-bold text-gray-900 text-sm">100% Original</div><div class="text-xs text-gray-500">Garansi uang kembali</div></div>
                    </div>
                    <div class="flex items-center space-x-3 justify-center md:justify-start">
                        <div class="text-shop-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                        <div><div class="font-bold text-gray-900 text-sm">Pengiriman Cepat</div><div class="text-xs text-gray-500">Estimasi 1-3 hari</div></div>
                    </div>
                    <div class="flex items-center space-x-3 justify-center md:justify-start">
                        <div class="text-shop-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                        <div><div class="font-bold text-gray-900 text-sm">Pembayaran Aman</div><div class="text-xs text-gray-500">Transfer & E-Wallet</div></div>
                    </div>
                    <div class="flex items-center space-x-3 justify-center md:justify-start">
                        <div class="text-shop-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div><div class="font-bold text-gray-900 text-sm">Layanan 24/7</div><div class="text-xs text-gray-500">Hubungi kami kapan saja</div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Sale Section -->
        <?php if (!$category_filter): ?>
        <div id="flash-sale" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-red-50">
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-extrabold text-red-600 uppercase italic">Flash Sale</h3>
                        <div id="flash-sale-timer" class="flex items-center gap-1 text-sm font-bold bg-gray-900 text-white px-3 py-1 rounded">
                            <span>00</span>:<span>00</span>:<span>00</span>
                        </div>
                    </div>
                    <a href="#" class="text-sm font-semibold text-red-600 hover:text-red-700">Lihat Semua ></a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <?php foreach ($flash_products as $fp): ?>
                            <a href="index.php?p=<?php echo $fp->ID; ?>" class="group block border border-gray-100 rounded-lg p-3 hover:shadow-md transition">
                                <div class="relative w-full aspect-square bg-gray-100 rounded mb-3 overflow-hidden">
                                    <img src="<?php echo orion_shop_get_image($fp->ID); ?>" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    <div class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">-50%</div>
                                </div>
                                <div class="text-lg font-bold text-red-600">
                                    <?php echo function_exists('orion_shop_get_price') ? orion_shop_format_price(orion_shop_get_price($fp->ID)) : 'Rp 0'; ?>
                                </div>
                                <div class="text-xs text-gray-400 line-through mb-1">Rp 9.999.000</div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                    <div class="bg-red-500 h-1.5 rounded-full" style="width: <?php echo rand(40, 90); ?>%"></div>
                                </div>
                                <div class="text-[10px] text-gray-500">Terjual <?php echo rand(10, 100); ?></div>
                            </a>
                        <?php endforeach; ?>
                        <?php if (empty($flash_products)): ?>
                            <p class="col-span-full text-center text-gray-500 py-4">Belum ada produk Flash Sale.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Main Products Grid (Just For You) -->
    <div id="main-products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Rekomendasi Untukmu</h3>
            
            <?php if (!$is_single): ?>
                <div class="flex space-x-2">
                    <a href="?sort=latest" class="px-3 py-1 text-sm rounded-full <?php echo $sort_order == 'latest' ? 'bg-shop-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">Terbaru</a>
                    <a href="?sort=price_asc" class="px-3 py-1 text-sm rounded-full <?php echo $sort_order == 'price_asc' ? 'bg-shop-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">Termurah</a>
                    <a href="?sort=price_desc" class="px-3 py-1 text-sm rounded-full <?php echo $sort_order == 'price_desc' ? 'bg-shop-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>">Termahal</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($orion_query->have_posts()): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <?php while ($orion_query->have_posts()): $orion_query->the_post(); ?>
                    <a href="index.php?p=<?php echo $post->ID; ?>" class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                        <div class="relative w-full aspect-square bg-gray-100 overflow-hidden">
                            <img src="<?php echo orion_shop_get_image($post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <?php if (rand(0, 1)): ?>
                                <div class="absolute bottom-0 left-0 bg-shop-600 text-white text-[10px] px-2 py-0.5 font-bold">Mall</div>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 group-hover:text-shop-600 transition"><?php echo $post->post_title; ?></h3>
                            <div class="mt-auto">
                                <div class="text-base font-bold text-shop-600 mb-1">
                                    <?php echo function_exists('orion_shop_get_price') ? orion_shop_format_price(orion_shop_get_price($post->ID)) : ''; ?>
                                </div>
                                <div class="flex items-center text-[10px] text-gray-500 space-x-2">
                                    <div class="flex items-center text-yellow-400">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-gray-600 ml-1"><?php echo function_exists('orion_shop_get_rating') ? orion_shop_get_rating($post->ID) : '4.5'; ?></span>
                                    </div>
                                    <span>•</span>
                                    <span><?php echo function_exists('orion_shop_get_sold_count') ? orion_shop_get_sold_count($post->ID) : '100+'; ?> Terjual</span>
                                </div>
                                <div class="flex items-center mt-2 text-[10px] text-gray-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <?php echo function_exists('orion_shop_get_location') ? orion_shop_get_location($post->ID) : 'Jakarta'; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-500">Coba kata kunci lain atau cek kategori lainnya.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    // Flash Sale Timer
    function startTimer(duration, display) {
        var timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.innerHTML = "<span>" + hours + "</span>:<span>" + minutes + "</span>:<span>" + seconds + "</span>";

            if (--timer < 0) {
                timer = duration;
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveHours = 60 * 60 * 5;
        var display = document.querySelector('#flash-sale-timer');
        if (display) {
            startTimer(fiveHours, display);
        }
    };
</script>

<?php get_footer(); ?>