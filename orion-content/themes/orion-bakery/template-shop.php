<?php
/**
 * Katalog Roti (shop) template.
 * Rendered by index.php when ?page=shop is requested.
 * NOTE: get_header() is already output by index.php before this file is included.
 */
?>
<div class="py-16 bg-bakery-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-14">
            <span class="inline-block py-1 px-4 rounded-full bg-bakery-200 text-bakery-800 text-sm font-bold tracking-wide uppercase mb-4 shadow-sm transform rotate-[-2deg]">
                🍞 Semua Varian Kami
            </span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-bakery-950 mb-4">Katalog Roti</h1>
            <p class="text-lg text-bakery-700 max-w-2xl mx-auto font-light">
                Temukan roti favorit Anda dari koleksi lengkap kami. Semua dipanggang segar setiap pagi.
            </p>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $args = array(
                'post_type'      => 'download',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $shop_query = new WP_Query($args);

            if ($shop_query->have_posts()):
                while ($shop_query->have_posts()): $shop_query->the_post();
                    $price = '';
                    if (function_exists('edd_get_download_price')) {
                        $price = edd_currency_filter(edd_format_amount(edd_get_download_price(get_the_ID())));
                    }
                    $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    if (!$img) $img = 'https://via.placeholder.com/600x600?text=Roti+Segar';
            ?>
            <div class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-bakery-100 group relative flex flex-col">
                <!-- Image -->
                <div class="h-64 overflow-hidden relative bg-bakery-100">
                    <img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <?php if ($price): ?>
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-bakery-800 font-bold px-3 py-1 rounded-full shadow-md text-sm">
                        <?php echo $price; ?>
                    </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <a href="<?php the_permalink(); ?>" class="px-6 py-2 bg-white text-bakery-800 rounded-full font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300 hover:bg-bakery-50">
                            Lihat Detail
                        </a>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-7 flex-1 flex flex-col">
                    <h2 class="text-xl font-serif font-bold text-bakery-900 mb-3 leading-tight">
                        <a href="<?php the_permalink(); ?>" class="hover:text-bakery-600 transition"><?php the_title(); ?></a>
                    </h2>
                    <p class="text-bakery-600 text-sm mb-6 line-clamp-2 flex-1 leading-relaxed">
                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                    </p>

                    <div class="mt-auto pt-4 border-t border-bakery-100">
                        <?php if (function_exists('edd_get_purchase_link')): ?>
                            <?php echo edd_get_purchase_link(array(
                                'download_id' => get_the_ID(),
                                'class' => 'w-full block text-center py-3 px-6 bg-bakery-600 hover:bg-bakery-700 text-white rounded-xl font-bold transition shadow-lg shadow-bakery-600/20',
                                'text' => 'Pesan Sekarang'
                            )); ?>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="w-full block text-center py-3 px-6 bg-bakery-600 hover:bg-bakery-700 text-white rounded-xl font-bold transition shadow-lg shadow-bakery-600/20">
                                Lihat Detail
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
            ?>
            <div class="col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-bakery-100">
                <div class="text-6xl mb-4">🥐</div>
                <h3 class="text-2xl font-bold text-bakery-800 mb-2">Belum Ada Produk</h3>
                <p class="text-bakery-600">Katalog roti sedang disiapkan. Silakan kembali lagi nanti.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
