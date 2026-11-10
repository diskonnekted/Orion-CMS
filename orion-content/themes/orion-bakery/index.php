<?php get_header(); ?>

<?php
// PAGE ROUTING — dedicated templates for ?page=shop / about / contact
if ( isset($_GET['page']) ) {
    $page = $_GET['page'];
    $templates = [
        'shop'   => 'template-shop.php',
        'about'  => 'template-about.php',
        'contact' => 'template-contact.php',
    ];
    if ( isset($templates[$page]) ) {
        $template_file = get_template_directory() . '/' . $templates[$page];
        if ( file_exists($template_file) ) {
            include $template_file;
            exit;
        }
    }
}
?>
<?php
// HOME PAGE
if ( !is_single() && !isset($_GET['page_id']) && !isset($_GET['p']) && !isset($_GET['cat']) && !isset($_GET['s']) ):
?>

    <!-- Hero Section -->
    <section class="relative bg-bakery-100 overflow-hidden pt-12 pb-24 lg:pt-20 lg:pb-32">
        <div class="absolute inset-0 opacity-20 bg-pattern-bread"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-bakery-300 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-bakery-200 rounded-full blur-3xl opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <span class="inline-block py-1 px-4 rounded-full bg-bakery-200 text-bakery-800 text-sm font-bold tracking-wide uppercase mb-6 shadow-sm transform rotate-[-2deg]">
                        ✨ Resep Warisan Sejak 1995
                    </span>
                    <h1 class="text-5xl lg:text-7xl font-serif font-bold text-bakery-950 leading-tight mb-6">
                        Kehangatan <span class="text-bakery-600">Roti Segar</span> <br>Setiap Pagi
                    </h1>
                    <p class="text-xl text-bakery-800 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed font-light">
                        Nikmati sensasi roti lembut dengan bahan premium yang dipanggang dengan penuh cinta. Sempurna untuk menemani kopi pagi Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="<?php echo site_url('/?page=shop'); ?>" class="px-8 py-4 bg-bakery-600 hover:bg-bakery-700 text-white rounded-full font-bold shadow-lg shadow-bakery-600/30 transition transform hover:-translate-y-1">
                            Lihat Menu Kami
                        </a>
                        <a href="#featured" class="px-8 py-4 bg-white hover:bg-bakery-50 text-bakery-800 border-2 border-bakery-200 rounded-full font-bold transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-bakery-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Terlaris Minggu Ini
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <?php 
                        $hero_img = get_option('orion_bakery_hero_img', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80'); 
                    ?>
                    <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
                        <img src="<?php echo $hero_img; ?>" alt="Fresh Bread" class="w-full h-auto object-cover">
                    </div>
                    <!-- Decorative Elements -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-lg transform -rotate-6 z-20">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1" alt="User">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2" alt="User">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=3" alt="User">
                            </div>
                            <div class="text-xs font-bold text-bakery-900">
                                500+ Pelanggan <br>Puas Hari Ini
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 text-9xl opacity-20 floating-bread">🥖</div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 bg-white clip-wavy-bottom"></div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 rounded-2xl hover:bg-bakery-50 transition duration-300">
                    <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🌾</div>
                    <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Bahan Premium</h3>
                    <p class="text-bakery-700">Menggunakan tepung gandum pilihan dan mentega berkualitas tinggi tanpa pengawet.</p>
                </div>
                <div class="p-6 rounded-2xl hover:bg-bakery-50 transition duration-300">
                    <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🔥</div>
                    <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Dipanggang Segar</h3>
                    <p class="text-bakery-700">Roti kami dipanggang setiap pagi pukul 4 untuk memastikan kesegaran maksimal saat Anda beli.</p>
                </div>
                <div class="p-6 rounded-2xl hover:bg-bakery-50 transition duration-300">
                    <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🛵</div>
                    <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Pengiriman Cepat</h3>
                    <p class="text-bakery-700">Pesan online dan kami antar ke depan pintu Anda dalam kondisi masih hangat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products (EDD Integration) -->
    <section id="featured" class="py-20 bg-bakery-50 relative">
        <div class="absolute inset-0 opacity-5 bg-pattern-bread"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <span class="text-bakery-600 font-bold tracking-widest uppercase text-sm">Menu Favorit</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-bakery-900 mt-2">Pilihan Pelanggan</h2>
                <div class="w-24 h-1 bg-bakery-400 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                // Check if EDD is active
                if (function_exists('edd_get_downloads')) {
                    $args = array(
                        'post_type'      => 'download',
                        'posts_per_page' => 4,
                        'orderby'        => 'rand', // Random for variety
                    );
                    $products = new WP_Query($args);
                } else {
                    // Fallback to standard posts
                    $products = new WP_Query(['posts_per_page' => 4]);
                }

                if ($products->have_posts()):
                    while ($products->have_posts()): $products->the_post();
                        $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        if(!$img) $img = 'https://via.placeholder.com/400x400?text=Roti+Enak';
                        
                        // Price logic
                        $price = 'Rp 0';
                        if (function_exists('edd_get_download_price')) {
                            $price = edd_currency_filter(edd_format_amount(edd_get_download_price(get_the_ID())));
                        }
                ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm product-card border border-bakery-100 group">
                    <div class="relative h-64 overflow-hidden bg-bakery-100">
                        <img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <?php if (function_exists('edd_get_download_price')): ?>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-bakery-800 font-bold px-3 py-1 rounded-full shadow-md text-sm">
                            <?php echo $price; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Quick Add Button Overlay -->
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <a href="<?php the_permalink(); ?>" class="px-6 py-2 bg-white text-bakery-800 rounded-full font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300 hover:bg-bakery-50">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="text-xs text-bakery-500 mb-2 uppercase tracking-wide font-bold">Roti Manis</div>
                        <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2 leading-tight">
                            <a href="<?php the_permalink(); ?>" class="hover:text-bakery-600 transition"><?php the_title(); ?></a>
                        </h3>
                        <p class="text-bakery-600 text-sm mb-4 line-clamp-2"><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                        
                        <?php if(function_exists('edd_get_purchase_link')): ?>
                            <div class="mt-2">
                                <?php echo edd_get_purchase_link(array('download_id' => get_the_ID(), 'class' => 'w-full block py-2 px-4 bg-bakery-100 hover:bg-bakery-200 text-bakery-800 rounded-lg font-bold transition text-sm')); ?>
                            </div>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="inline-block w-full py-2 px-4 bg-bakery-100 hover:bg-bakery-200 text-bakery-800 rounded-lg font-bold transition text-sm">
                                Baca Selengkapnya
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    endwhile; 
                    wp_reset_postdata();
                else:
                ?>
                    <div class="col-span-4 text-center py-10">
                        <p class="text-bakery-500">Belum ada produk roti yang ditambahkan.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo site_url('/?page=shop'); ?>" class="inline-flex items-center gap-2 text-bakery-800 font-bold hover:text-bakery-600 transition border-b-2 border-bakery-400 hover:border-bakery-600 pb-1">
                    Lihat Semua Menu Roti <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Promo / CTA Section -->
    <section class="py-24 bg-bakery-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-pattern-bread"></div>
        <div class="absolute -left-20 top-1/2 w-80 h-80 bg-bakery-600 rounded-full blur-[100px] opacity-30"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-bakery-50 mb-6">Diskon 20% Untuk Member Baru!</h2>
            <p class="text-xl text-bakery-200 mb-10 max-w-2xl mx-auto font-light">
                Daftar sekarang dan dapatkan potongan harga untuk pembelian pertama Anda. Nikmati roti hangat dengan harga lebih hemat.
            </p>
            <form class="max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="Masukkan email Anda..." class="flex-1 px-6 py-4 rounded-full border-none focus:ring-2 focus:ring-bakery-400 bg-white/10 text-white placeholder-bakery-300 backdrop-blur">
                <button type="button" class="px-8 py-4 bg-bakery-500 hover:bg-bakery-400 text-bakery-950 font-bold rounded-full transition shadow-lg shadow-bakery-500/30">
                    Gabung
                </button>
            </form>
            <p class="text-xs text-bakery-400 mt-4">*Syarat dan ketentuan berlaku. Promo terbatas.</p>
        </div>
    </section>

<?php 
// SINGLE POST / PRODUCT
elseif ( is_single() || isset($_GET['page_id']) ): 
    $post_id = isset($_GET['p']) ? $_GET['p'] : $_GET['page_id'];
    $post = get_post($post_id);
    if ($post):
?>
    <div class="py-12 bg-bakery-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-bakery-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Image Side -->
                    <div class="h-96 md:h-auto relative bg-bakery-100">
                        <?php 
                        $img = get_the_post_thumbnail_url($post->ID, 'large'); 
                        if(!$img) $img = 'https://via.placeholder.com/800x800?text=Orion+Bakery';
                        ?>
                        <img src="<?php echo $img; ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Content Side -->
                    <div class="p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-4">
                             <?php if (function_exists('edd_get_download_price') && get_post_type($post->ID) == 'download'): ?>
                                <span class="inline-block px-3 py-1 bg-bakery-100 text-bakery-800 rounded-full text-xs font-bold uppercase tracking-wide mb-2">Produk</span>
                                <div class="text-3xl font-bold text-bakery-600 mb-2">
                                    <?php echo edd_currency_filter(edd_format_amount(edd_get_download_price($post->ID))); ?>
                                </div>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 bg-bakery-100 text-bakery-800 rounded-full text-xs font-bold uppercase tracking-wide mb-2">Artikel</span>
                            <?php endif; ?>
                            
                            <h1 class="text-4xl md:text-5xl font-serif font-bold text-bakery-950 leading-tight mb-6">
                                <?php echo $post->post_title; ?>
                            </h1>
                        </div>
                        
                        <div class="prose prose-bakery text-bakery-700 mb-8 leading-relaxed">
                            <?php echo get_the_content(null, false, $post->ID); ?>
                        </div>
                        
                        <?php if (function_exists('edd_get_purchase_link') && get_post_type($post->ID) == 'download'): ?>
                        <div class="mt-auto pt-8 border-t border-bakery-100">
                            <?php echo edd_get_purchase_link(array('download_id' => $post->ID, 'class' => 'w-full block text-center py-4 px-6 bg-bakery-600 hover:bg-bakery-500 text-white rounded-xl font-bold text-lg shadow-lg shadow-bakery-600/20 transition transform hover:-translate-y-1')); ?>
                            <p class="text-center text-xs text-bakery-400 mt-3 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Jaminan Fresh from Oven
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 
    else:
        echo '<div class="py-20 text-center text-2xl font-bold text-bakery-800">Konten tidak ditemukan.</div>';
    endif;

// DEFAULT ARCHIVE
else: 
?>
    <div class="py-16 bg-bakery-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-serif font-bold text-bakery-950 mb-12 text-center">Menu & Artikel</h1>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                if (have_posts()):
                    while (have_posts()): the_post();
                        $img = get_the_post_thumbnail_url();
                        if(!$img) $img = 'https://via.placeholder.com/600x400?text=Orion+Bakery';
                ?>
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition border border-bakery-100 flex flex-col">
                    <a href="<?php the_permalink(); ?>" class="h-56 overflow-hidden block relative group">
                        <img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/20 group-hover:opacity-0 transition"></div>
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs text-bakery-400 mb-2 uppercase tracking-wide font-bold"><?php echo get_the_date(); ?></div>
                        <h2 class="text-2xl font-serif font-bold text-bakery-900 mb-3 leading-tight hover:text-bakery-600 transition">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="text-bakery-600 text-sm mb-4 line-clamp-3 flex-1">
                            <?php echo wp_trim_words(get_the_content(), 20); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-bakery-600 font-bold hover:text-bakery-800 transition mt-auto">
                            Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </article>
                <?php 
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
