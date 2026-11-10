<?php get_header(); ?>

<div class="bg-bakery-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-4 rounded-full bg-bakery-200 text-bakery-800 text-sm font-bold tracking-wide uppercase mb-4 transform rotate-[-2deg] shadow-sm">
                🥖 Fresh From The Oven
            </span>
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-bakery-950 mb-4">Menu Hari Ini</h1>
            <p class="text-xl text-bakery-700 max-w-2xl mx-auto font-light">
                Nikmati roti yang baru saja dipanggang pagi ini. Ketersediaan terbatas setiap harinya, pesan sebelum kehabisan!
            </p>
            <div class="mt-8 flex justify-center gap-4 text-sm font-medium text-bakery-600">
                <span class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Buka: 07:00 - 20:00
                </span>
                <span class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                    <span class="w-2 h-2 bg-bakery-500 rounded-full"></span> Stok Terbatas
                </span>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $daily_ids = get_option('orion_bakery_daily_products', array());
            
            if (!empty($daily_ids)) {
                $args = array(
                    'post_type' => 'download',
                    'post__in' => $daily_ids,
                    'posts_per_page' => -1,
                    'orderby' => 'post__in' // Maintain selection order if possible, or just standard
                );
                $query = new WP_Query($args);
                
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $price = function_exists('edd_get_download_price') ? edd_currency_filter(edd_format_amount(edd_get_download_price(get_the_ID()))) : '';
                        $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        if (!$img) $img = 'https://via.placeholder.com/600x600?text=Roti+Segar';
            ?>
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-bakery-100 group relative">
                    <!-- Image -->
                    <div class="h-64 overflow-hidden relative">
                        <img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                                READY HARI INI
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="bg-white text-bakery-900 font-bold px-4 py-2 rounded-full shadow-lg text-lg">
                                <?php echo $price; ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <h2 class="text-2xl font-serif font-bold text-bakery-900 mb-3 group-hover:text-bakery-600 transition">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="text-bakery-600 mb-6 line-clamp-2 leading-relaxed">
                            <?php echo wp_trim_words(get_the_content(), 15); ?>
                        </p>
                        
                        <!-- Action -->
                        <div class="flex items-center justify-between gap-4 mt-auto">
                            <?php if(function_exists('edd_get_purchase_link')): ?>
                                <?php echo edd_get_purchase_link(array(
                                    'download_id' => get_the_ID(), 
                                    'class' => 'flex-1 py-3 px-6 bg-bakery-600 hover:bg-bakery-700 text-white rounded-xl font-bold text-center shadow-lg shadow-bakery-600/30 transition transform hover:-translate-y-0.5',
                                    'text' => 'Pesan Sekarang'
                                )); ?>
                            <?php else: ?>
                                <a href="<?php the_permalink(); ?>" class="flex-1 py-3 px-6 bg-bakery-600 hover:bg-bakery-700 text-white rounded-xl font-bold text-center shadow-lg shadow-bakery-600/30 transition">
                                    Lihat Detail
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="p-3 bg-bakery-100 text-bakery-600 rounded-xl hover:bg-bakery-200 transition" title="Lihat Detail">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
                    echo '<div class="col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-bakery-100">';
                    echo '<div class="text-6xl mb-4">🥐</div>';
                    echo '<h3 class="text-2xl font-bold text-bakery-800 mb-2">Maaf, Menu Hari Ini Habis</h3>';
                    echo '<p class="text-bakery-600">Silakan cek kembali besok pagi untuk menu segar lainnya.</p>';
                    echo '</div>';
                endif;
            } else {
                echo '<div class="col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-bakery-100">';
                echo '<div class="text-6xl mb-4">👨‍🍳</div>';
                echo '<h3 class="text-2xl font-bold text-bakery-800 mb-2">Menu Sedang Disiapkan</h3>';
                echo '<p class="text-bakery-600">Chef kami sedang menyiapkan daftar menu spesial untuk hari ini.</p>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- CTA -->
        <div class="mt-20 bg-bakery-900 rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-10 bg-pattern-bread"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-bakery-50 mb-6">Pesan Lebih Awal untuk Besok?</h2>
                <p class="text-bakery-200 mb-8 max-w-2xl mx-auto">
                    Jangan sampai kehabisan menu favorit Anda. Kami menerima pemesanan H-1 untuk acara spesial atau sarapan keluarga.
                </p>
                <a href="https://wa.me/6281234567890" class="inline-flex items-center gap-2 px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-full font-bold shadow-lg transition transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
