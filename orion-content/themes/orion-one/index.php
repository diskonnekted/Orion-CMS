<?php get_header(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="md:col-span-2">
        <?php 
        $post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
        $page_id = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
        $view_id = $post_id > 0 ? $post_id : $page_id;
        
        if ($view_id > 0) {
            // SINGLE POST / PAGE VIEW
            $post = get_post($view_id);
            if ($post) {
                $thumb_url = get_the_post_thumbnail_url($post->ID);
                $categories = get_the_terms($post->ID, 'category');
                $gallery = get_post_meta($post->ID, '_gallery_images', true);
                $attachments = get_post_meta($post->ID, '_attachments', true);
                ?>
                <article class="bg-white p-8 rounded-lg shadow-sm">
                    <div class="mb-6">
                        <?php if ($categories): ?>
                        <div class="flex gap-2 mb-3">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-orion-100 text-orion-800 text-xs px-2 py-1 rounded-full font-semibold uppercase tracking-wide"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($post->post_title); ?></h1>
                        
                        <div class="flex items-center text-gray-500 text-sm mb-6 border-b pb-6">
                            <span><?php echo date('d F Y', strtotime($post->post_date)); ?></span>
                            <span class="mx-2">•</span>
                            <span>By Admin</span>
                        </div>

                        <?php if ($thumb_url): ?>
                        <div class="mb-8 rounded-lg overflow-hidden shadow-md">
                            <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-auto">
                        </div>
                        <?php endif; ?>

                        <div class="prose max-w-none text-gray-800 leading-relaxed mb-8">
                            <?php echo $post->post_content; // WYSIWYG content is usually HTML safe ?>
                        </div>

                        <!-- Gallery Section -->
                        <?php if ($gallery): 
                            $gallery_images = json_decode($gallery, true);
                            if (!empty($gallery_images)):
                        ?>
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold mb-4 border-l-4 border-orion-500 pl-4">Galeri Foto</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach($gallery_images as $img): ?>
                                <a href="<?php echo $img; ?>" target="_blank" class="block aspect-square overflow-hidden rounded-lg hover:opacity-90 transition">
                                    <img src="<?php echo $img; ?>" class="w-full h-full object-cover">
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; endif; ?>

                        <!-- Attachments Section -->
                        <?php if ($attachments): 
                            $att_files = json_decode($attachments, true);
                            if (!empty($att_files)):
                        ?>
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-xl font-bold mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Lampiran Dokumen
                            </h3>
                            <ul class="space-y-3">
                                <?php foreach($att_files as $att): ?>
                                <li class="flex items-center justify-between bg-white p-3 rounded border hover:shadow-sm transition">
                                    <span class="font-medium text-gray-700 truncate mr-4"><?php echo htmlspecialchars($att['name']); ?></span>
                                    <a href="<?php echo $att['url']; ?>" download class="text-orion-600 hover:text-orion-800 text-sm font-bold flex items-center">
                                        Download
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; endif; ?>

                        <div class="mt-8 pt-8 border-t">
                            <a href="index.php" class="inline-flex items-center text-gray-600 hover:text-orion-600 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Kembali ke Berita Utama
                            </a>
                        </div>
                    </div>
                </article>
                <?php
            } else {
                echo '<div class="bg-red-100 text-red-700 p-4 rounded">Post not found.</div>';
            }
        } else {
            // LIST VIEW
            ?>
            <h1 class="text-3xl font-bold mb-6 border-b pb-2 border-gray-200">Berita Terbaru</h1>
            <?php
            $paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
            $posts_per_page = 5;
            $offset = ($paged - 1) * $posts_per_page;
            
            $posts = get_posts(array(
                'numberposts' => $posts_per_page,
                'offset' => $offset
            ));
            
            if (!empty($posts)):
                foreach ($posts as $post): 
                    $thumb_url = get_the_post_thumbnail_url($post->ID);
                    $categories = get_the_terms($post->ID, 'category');
            ?>
                <article class="mb-8 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition group">
                    <?php if ($thumb_url): ?>
                    <div class="h-64 bg-gray-200 rounded-lg mb-4 overflow-hidden relative">
                        <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php if ($categories): ?>
                        <div class="absolute top-4 left-4 flex gap-1">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-white/90 backdrop-blur text-gray-800 text-xs px-2 py-1 rounded font-bold shadow-sm"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                        <!-- No Image Placeholder but with Category -->
                         <?php if ($categories): ?>
                        <div class="mb-2 flex gap-1">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <h2 class="text-2xl font-bold mb-2 text-gray-800">
                        <a href="?p=<?php echo $post->ID; ?>" class="hover:text-orion-600 transition"><?php echo htmlspecialchars($post->post_title); ?></a>
                    </h2>
                    <div class="text-sm text-gray-500 mb-4">Diposting pada <?php echo date('d F Y', strtotime($post->post_date)); ?></div>
                    <p class="text-gray-600 mb-4">
                        <?php 
                        $content = strip_tags($post->post_content);
                        if (strlen($content) > 150) {
                            echo substr($content, 0, 150) . '...';
                        } else {
                            echo $content;
                        }
                        ?>
                    </p>
                    <a href="?p=<?php echo $post->ID; ?>" class="inline-block text-orion-600 font-semibold hover:underline">Baca Selengkapnya &rarr;</a>
                </article>
            <?php 
                endforeach;

                // Pagination UI
                $count_posts = wp_count_posts();
                $total_posts = $count_posts->publish;
                $total_pages = ceil($total_posts / $posts_per_page);
                
                if ($total_pages > 1):
            ?>
                <div class="flex justify-center mt-8 space-x-2">
                    <?php if ($paged > 1): ?>
                        <a href="?paged=<?php echo $paged - 1; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 text-gray-700 font-medium transition">Previous</a>
                    <?php endif; ?>

                    <?php 
                    // Simple pagination range
                    $start_page = max(1, $paged - 2);
                    $end_page = min($total_pages, $paged + 2);
                    
                    if ($start_page > 1) echo '<span class="px-2 py-2 text-gray-400">...</span>';
                    
                    for ($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                        <a href="?paged=<?php echo $i; ?>" class="px-4 py-2 border <?php echo ($paged == $i) ? 'bg-orion-600 text-white border-orion-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'; ?> rounded font-medium transition">
                            <?php echo $i; ?>
                        </a>
                    <?php 
                    endfor; 
                    
                    if ($end_page < $total_pages) echo '<span class="px-2 py-2 text-gray-400">...</span>';
                    ?>

                    <?php if ($paged < $total_pages): ?>
                        <a href="?paged=<?php echo $paged + 1; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 text-gray-700 font-medium transition">Next</a>
                    <?php endif; ?>
                </div>
            <?php
                endif;

            else:
            ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Belum ada berita yang diposting. Silakan tambahkan berita dari Admin Panel.</p>
                        </div>
                    </div>
                </div>
            <?php endif; 
        } // End List View
        ?>
    </div>

    <!-- Sidebar -->
    <aside class="md:col-span-1 space-y-8">
        
        <!-- Search Form -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Cari Berita</h3>
            <form action="" method="get">
                <div class="flex">
                    <input type="text" name="s" class="w-full border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:border-orion-500" placeholder="Kata kunci..." value="<?php echo isset($_GET['s']) ? htmlspecialchars($_GET['s']) : ''; ?>">
                    <button type="submit" class="bg-orion-600 text-white px-4 py-2 rounded-r hover:bg-orion-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Banner Card -->
        <?php 
        $banner_image = get_option('orion_one_banner_image', '');
        $banner_link = get_option('orion_one_banner_link', '#');
        if ($banner_image):
        ?>
        <div class="rounded-lg shadow-sm overflow-hidden">
            <a href="<?php echo htmlspecialchars($banner_link); ?>" target="_blank">
                <img src="<?php echo htmlspecialchars($banner_image); ?>" alt="Banner" class="w-full h-auto hover:opacity-90 transition">
            </a>
        </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Kategori</h3>
            <?php 
            $cats = get_categories();
            if ($cats):
            ?>
            <ul class="space-y-2">
                <?php foreach($cats as $cat): ?>
                <li>
                    <a href="<?php echo get_category_link($cat); ?>" class="group flex justify-between items-center text-gray-600 hover:text-orion-600 transition">
                        <span><?php echo htmlspecialchars($cat->name); ?></span> 
                        <span class="bg-gray-100 group-hover:bg-orion-100 text-gray-500 group-hover:text-orion-600 px-2 py-0.5 rounded-full text-xs font-medium transition"><?php echo $cat->count; ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p class="text-gray-500 text-sm italic">Belum ada kategori.</p>
            <?php endif; ?>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Berita Populer</h3>
            <div class="space-y-4">
                <?php
                $popular_args = array(
                    'numberposts' => 5,
                    'orderby' => 'rand'
                );
                $popular_posts = get_posts($popular_args);
                
                if ($popular_posts) :
                    foreach ($popular_posts as $post) : 
                        // setup_postdata($post) is not strictly needed if we access properties directly, 
                        // but good for template tags if we used them. 
                        // Here we access $post object directly.
                        $thumb_url = get_the_post_thumbnail_url($post->ID);
                ?>
                <div class="flex gap-3 group">
                    <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden">
                        <?php if ($thumb_url): ?>
                        <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover group-hover:opacity-80 transition">
                        <?php else: ?>
                        <!-- Fallback/Placeholder -->
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold leading-tight mb-1 line-clamp-2">
                            <a href="?p=<?php echo $post->ID; ?>" class="hover:text-orion-600 transition"><?php echo htmlspecialchars($post->post_title); ?></a>
                        </h4>
                        <span class="text-xs text-gray-400"><?php echo date('d M Y', strtotime($post->post_date)); ?></span>
                    </div>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                <p class="text-gray-500 text-sm italic">Belum ada berita populer.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="bg-orion-600 text-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b border-orion-500 pb-2">Newsletter</h3>
            <p class="text-sm mb-4 text-orion-100">Dapatkan berita terbaru langsung di inbox Anda.</p>
            <form action="" method="post" onsubmit="alert('Terima kasih telah berlangganan!'); return false;">
                <input type="email" placeholder="Alamat Email" class="w-full px-4 py-2 rounded text-gray-800 mb-2 focus:outline-none" required>
                <button type="submit" class="w-full bg-orion-800 hover:bg-orion-900 text-white font-bold py-2 px-4 rounded transition">Langganan</button>
            </form>
        </div>

        <!-- Social Media -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Ikuti Kami</h3>
            <div class="flex gap-4 justify-center">
                <!-- Facebook -->
                <a href="#" class="text-blue-600 hover:scale-110 transition"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <!-- Twitter -->
                <a href="#" class="text-sky-500 hover:scale-110 transition"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                <!-- Instagram -->
                <a href="#" class="text-pink-600 hover:scale-110 transition"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
            </div>
        </div>

        <!-- Stats -->
        <?php 
        $stats = orion_one_get_stats();
        ?>
        <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-orion-500">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Statistik Pengunjung</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Hari Ini:</span>
                    <span class="font-bold text-orion-700 bg-orion-100 px-2 py-1 rounded"><?php echo number_format($stats['today']); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-bold text-orion-700 bg-orion-100 px-2 py-1 rounded"><?php echo number_format($stats['total']); ?></span>
                </div>
            </div>
        </div>
    </aside>
</div>

<?php get_footer(); ?>