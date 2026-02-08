<?php get_header(); ?>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <!-- Main Content -->
    <div class="lg:col-span-8 xl:col-span-9">
        <?php 
        $post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
        $page_id = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
        $view_id = $post_id > 0 ? $post_id : $page_id;
        
        if ($view_id > 0) {
            // SINGLE POST / PAGE VIEW
            $post = get_post($view_id);
            if ($post) {
                // Check if this is the "Berita" page
                if ($post->post_title == 'Berita' && $post->post_type == 'page') {
                    ?>
                    <div class="mb-10">
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 border-b pb-4">Berita Terkini</h1>
                        <?php
                        // Pagination Logic
                        $paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
                        $posts_per_page = 9; // 3 columns x 3 rows
                        $offset = ($paged - 1) * $posts_per_page;
                        
                        $args = array(
                            'numberposts' => $posts_per_page,
                            'offset' => $offset,
                            'post_status' => 'publish',
                            'post_type' => 'post'
                        );
                        
                        $news_posts = get_posts($args);
                        
                        if ($news_posts) {
                            echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-8">';
                            foreach ($news_posts as $np) {
                                $thumb = get_the_post_thumbnail_url($np->ID);
                                $cats = get_the_terms($np->ID, 'category');
                                ?>
                                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                                    <div class="aspect-video relative overflow-hidden group">
                                        <?php if ($thumb): ?>
                                            <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        <?php endif; ?>
                                        <a href="?p=<?php echo $np->ID; ?>" class="absolute inset-0"></a>
                                        <?php if ($cats): ?>
                                        <div class="absolute top-2 left-2">
                                            <span class="bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded text-gray-800"><?php echo htmlspecialchars($cats[0]->name); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-5 flex flex-col flex-grow">
                                        <div class="text-xs text-gray-500 mb-2"><?php echo date('d M Y', strtotime($np->post_date)); ?></div>
                                        <h3 class="font-bold text-lg mb-2 leading-tight hover:text-blue-600 transition">
                                            <a href="?p=<?php echo $np->ID; ?>"><?php echo htmlspecialchars($np->post_title); ?></a>
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                                            <?php echo substr(strip_tags($np->post_content), 0, 100) . '...'; ?>
                                        </p>
                                        <a href="?p=<?php echo $np->ID; ?>" class="text-blue-600 text-sm font-semibold hover:underline mt-auto">Baca Selengkapnya &rarr;</a>
                                    </div>
                                </article>
                                <?php
                            }
                            echo '</div>';
                            
                            // Pagination Controls
                            // We need total posts count for pagination
                            global $orion_db, $table_prefix;
                            $res_count = $orion_db->query("SELECT COUNT(*) as count FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish'");
                            $total_posts = ($res_count) ? $res_count->fetch_object()->count : 0;
                            $total_pages = ceil($total_posts / $posts_per_page);
                            
                            if ($total_pages > 1) {
                                echo '<div class="mt-12 flex justify-center gap-2">';
                                if ($paged > 1) {
                                    echo '<a href="?page_id=' . $post->ID . '&paged=' . ($paged - 1) . '" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">Previous</a>';
                                }
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $active = ($i == $paged) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50';
                                    echo '<a href="?page_id=' . $post->ID . '&paged=' . $i . '" class="px-4 py-2 border rounded ' . $active . '">' . $i . '</a>';
                                }
                                if ($paged < $total_pages) {
                                    echo '<a href="?page_id=' . $post->ID . '&paged=' . ($paged + 1) . '" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">Next</a>';
                                }
                                echo '</div>';
                            }
                            
                        } else {
                            echo '<p>Tidak ada berita.</p>';
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    // STANDARD SINGLE POST / PAGE
                $thumb_url = get_the_post_thumbnail_url($post->ID);
                $categories = get_the_terms($post->ID, 'category');
                $gallery = get_post_meta($post->ID, '_gallery_images', true);
                $attachments = get_post_meta($post->ID, '_attachments', true);
                ?>
                <article class="bg-white p-8 md:p-10 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-8">
                        <?php if ($categories): ?>
                        <div class="flex gap-2 mb-4">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-blue-50 text-blue-600 text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wide hover:bg-blue-100 transition"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight"><?php echo htmlspecialchars($post->post_title); ?></h1>
                        
                        <div class="flex items-center text-gray-500 text-sm mb-8 border-b border-gray-100 pb-8">
                            <div class="flex items-center mr-6">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span><?php echo date('d F Y', strtotime($post->post_date)); ?></span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span>By Admin</span>
                            </div>
                        </div>

                        <?php if ($thumb_url): ?>
                        <div class="mb-10 rounded-xl overflow-hidden shadow-lg">
                            <img src="<?php echo htmlspecialchars($thumb_url); ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-auto object-cover">
                        </div>
                        <?php endif; ?>

                        <div class="prose prose-lg prose-blue max-w-none text-gray-700 leading-loose mb-10">
                            <?php 
                            if (function_exists('apply_filters')) {
                                echo apply_filters('the_content', $post->post_content);
                            } else {
                                echo $post->post_content;
                            }
                            ?>
                        </div>

                        <!-- Gallery Section -->
                        <?php if ($gallery): 
                            $gallery_images = json_decode($gallery, true);
                            if (!empty($gallery_images)):
                        ?>
                        <div class="mb-10">
                            <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                                <span class="w-1 h-8 bg-orion-500 mr-3 rounded-full"></span>
                                Galeri Foto
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach($gallery_images as $img): ?>
                                <a href="<?php echo $img; ?>" target="_blank" class="block aspect-square overflow-hidden rounded-xl hover:shadow-lg transition transform hover:-translate-y-1">
                                    <img src="<?php echo $img; ?>" class="w-full h-full object-cover hover:scale-110 transition duration-500">
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
                        <div class="mb-10 bg-blue-50 p-8 rounded-xl border border-blue-100">
                            <h3 class="text-xl font-bold mb-4 flex items-center text-blue-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Lampiran Dokumen
                            </h3>
                            <ul class="space-y-3">
                                <?php foreach($att_files as $att): ?>
                                <li class="flex items-center justify-between bg-white p-4 rounded-lg border border-blue-100 hover:shadow-md transition">
                                    <span class="font-medium text-gray-700 truncate mr-4"><?php echo htmlspecialchars($att['name']); ?></span>
                                    <a href="<?php echo $att['url']; ?>" download class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-md text-sm font-bold flex items-center transition">
                                        Download
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; endif; ?>

                        <!-- Related Posts Section -->
                        <div class="mt-16 border-t border-gray-100 pt-10">
                            <h3 class="text-2xl font-bold mb-8 text-gray-900">Berita Terkait</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <?php
                                global $orion_db, $table_prefix;
                                $related_posts = [];
                                
                                if ($categories) {
                                    $cat_ids = array();
                                    foreach($categories as $c) $cat_ids[] = $c->term_id;
                                    $cat_ids_str = implode(',', $cat_ids);
                                    
                                    // Manual Query for Related Posts
                                    $sql_related = "SELECT p.* FROM {$table_prefix}posts p 
                                            INNER JOIN {$table_prefix}term_relationships tr ON p.ID = tr.object_id
                                            INNER JOIN {$table_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                                            WHERE tt.taxonomy = 'category' 
                                            AND tt.term_id IN ($cat_ids_str)
                                            AND p.ID != {$post->ID}
                                            AND p.post_status = 'publish'
                                            GROUP BY p.ID
                                            ORDER BY RAND()
                                            LIMIT 3";
                                            
                                    $res_related = $orion_db->query($sql_related);
                                    if ($res_related) {
                                        while ($row = $res_related->fetch_object()) {
                                            $related_posts[] = $row;
                                        }
                                    }
                                }
                                
                                // Fallback: If no related posts (or query failed), get latest posts
                                if (empty($related_posts)) {
                                    // Use get_posts but exclude current
                                    // Since get_posts doesn't support exclude, we just fetch 4 and skip current in loop
                                    $fallback_posts = get_posts(array('numberposts' => 4));
                                    foreach($fallback_posts as $fp) {
                                        if ($fp->ID != $post->ID && count($related_posts) < 3) {
                                            $related_posts[] = $fp;
                                        }
                                    }
                                }
                                
                                foreach($related_posts as $r_post):
                                    $r_thumb = get_the_post_thumbnail_url($r_post->ID);
                                ?>
                                <a href="?p=<?php echo $r_post->ID; ?>" class="group block">
                                    <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-4 relative shadow-sm">
                                        <?php if ($r_thumb): ?>
                                        <img src="<?php echo htmlspecialchars($r_thumb); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="font-bold text-gray-900 leading-snug group-hover:text-orion-600 transition line-clamp-2">
                                        <?php echo htmlspecialchars($r_post->post_title); ?>
                                    </h4>
                                    <span class="text-xs text-gray-500 mt-2 block"><?php echo date('d M Y', strtotime($r_post->post_date)); ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
                } // End Berita check
            } else {
                echo '<div class="bg-red-50 text-red-700 p-6 rounded-xl border border-red-200 text-center font-medium">Post not found.</div>';
            }
        } else {
            // LIST VIEW
            // Hero Section for Homepage
            $paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
            
            // Logic: Page 1 shows 1 Hero + 6 Grid Items (Total 7)
            // Page > 1 shows 6 Grid Items
            
            $posts_per_page = ($paged == 1) ? 7 : 6;
            // Offset calculation needs to account for the extra post on page 1
            if ($paged == 1) {
                $offset = 0;
            } else {
                // (Page - 1) * 6, but add 1 for the hero on page 1
                $offset = 7 + ($paged - 2) * 6;
            }
            
            $posts_args = array(
                'numberposts' => $posts_per_page,
                'offset' => $offset
            );

            // Handle Category Filter
            $current_cat_id = 0;
            if (isset($_GET['cat'])) {
                $current_cat_id = (int) $_GET['cat'];
                $posts_args['category'] = $current_cat_id;
            }

            // Handle Search
            if (isset($_GET['s'])) {
                $posts_args['s'] = $_GET['s'];
            }

            $posts = get_posts($posts_args);
            
            if (!empty($posts)):
                
                // Display Hero Post only on first page and NOT in search/category mode
                if ($paged == 1 && !isset($_GET['cat']) && !isset($_GET['s'])) {
                    $hero_post = array_shift($posts); // Extract first post
                    if ($hero_post):
                        $hero_thumb = get_the_post_thumbnail_url($hero_post->ID);
                        $hero_cats = get_the_terms($hero_post->ID, 'category');
                ?>
                <article class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-xl mb-12 group h-[500px]">
                    <?php if ($hero_thumb): ?>
                    <img src="<?php echo htmlspecialchars($hero_thumb); ?>" alt="<?php echo htmlspecialchars($hero_post->post_title); ?>" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-1000">
                    <?php else: ?>
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-purple-900 opacity-90"></div>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-90"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full lg:w-4/5">
                        <?php if ($hero_cats): ?>
                        <div class="flex gap-2 mb-4">
                            <?php foreach($hero_cats as $cat): ?>
                            <span class="bg-orion-600 text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider shadow-sm"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                            <a href="?p=<?php echo $hero_post->ID; ?>" class="hover:text-blue-200 transition"><?php echo htmlspecialchars($hero_post->post_title); ?></a>
                        </h2>
                        
                        <div class="flex items-center text-gray-300 text-sm mb-6 font-medium">
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <?php echo date('d F Y', strtotime($hero_post->post_date)); ?></span>
                            <span class="mx-3">•</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded text-white text-xs uppercase tracking-wide">Featured</span>
                        </div>
                        
                        <p class="text-gray-200 mb-8 hidden md:block text-lg max-w-2xl leading-relaxed">
                            <?php 
                            $content = strip_tags($hero_post->post_content);
                            echo (strlen($content) > 150) ? substr($content, 0, 150) . '...' : $content;
                            ?>
                        </p>
                        
                        <a href="?p=<?php echo $hero_post->ID; ?>" class="inline-flex items-center bg-white text-gray-900 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition transform hover:-translate-y-1 shadow-lg">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m14-7H3"></path></svg>
                        </a>
                    </div>
                </article>
                <?php 
                    endif;
                }
                
                if (!empty($posts)):
                // Responsive grid: 1 col mobile, 2 col tablet/laptop (Requested: 2 columns)
                echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
                foreach ($posts as $post): 
                    $thumb_url = get_the_post_thumbnail_url($post->ID);
                    $categories = get_the_terms($post->ID, 'category');
            ?>
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full">
                    <?php if ($thumb_url): ?>
                    <div class="aspect-video w-full bg-gray-100 overflow-hidden relative group">
                        <img src="<?php echo htmlspecialchars($thumb_url); ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <?php if ($categories): ?>
                        <div class="absolute top-4 left-4 flex gap-1 z-10">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-white/95 backdrop-blur text-gray-800 text-xs px-2.5 py-1 rounded-md font-bold shadow-sm"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <a href="?p=<?php echo $post->ID; ?>" class="absolute inset-0 z-0"></a>
                    </div>
                    <?php else: ?>
                    <div class="aspect-video w-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative">
                         <?php if ($categories): ?>
                        <div class="absolute top-4 left-4 flex gap-1">
                            <?php foreach($categories as $cat): ?>
                            <span class="bg-white text-gray-600 text-xs px-2 py-1 rounded font-bold shadow-sm"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="text-xs text-gray-500 mb-3 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <?php echo date('d M Y', strtotime($post->post_date)); ?>
                        </div>
                        
                        <h2 class="text-xl font-bold mb-3 text-gray-900 leading-snug line-clamp-2 hover:text-orion-600 transition">
                            <a href="?p=<?php echo $post->ID; ?>"><?php echo htmlspecialchars($post->post_title); ?></a>
                        </h2>
                        
                        <p class="text-gray-600 mb-6 text-sm line-clamp-3 leading-relaxed flex-grow">
                            <?php 
                            $content = strip_tags($post->post_content);
                            echo (strlen($content) > 100) ? substr($content, 0, 100) . '...' : $content;
                            ?>
                        </p>
                        
                        <div class="pt-4 border-t border-gray-50 mt-auto">
                            <a href="?p=<?php echo $post->ID; ?>" class="text-orion-600 font-bold text-sm hover:text-orion-800 transition flex items-center">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php 
                endforeach;
                echo '</div>'; // End Grid
                endif; // End Grid Check

                // Homepage Banner & Opinion Section
                if ($paged == 1 && $view_id == 0 && !isset($_GET['cat'])) {
                    // 1. Horizontal Banner
                    $home_banner_image = get_option('orion_one_home_banner_image', '');
                    $home_banner_link = get_option('orion_one_home_banner_link', '#');
                    
                    if ($home_banner_image) {
                        echo '<div class="mt-12 mb-12 rounded-2xl overflow-hidden shadow-md group">';
                        echo '<a href="' . htmlspecialchars($home_banner_link) . '" target="_blank">';
                        echo '<img src="' . htmlspecialchars($home_banner_image) . '" alt="Banner" class="w-full h-auto object-cover group-hover:scale-105 transition duration-500">';
                        echo '</a>';
                        echo '</div>';
                    }

                    // 2. Opinion Section
                    // Find 'Opini' Category ID
                    $opini_id = 0;
                    $sql_opini = "SELECT t.term_id FROM {$table_prefix}terms t 
                                INNER JOIN {$table_prefix}term_taxonomy tt ON t.term_id = tt.term_id 
                                WHERE tt.taxonomy = 'category' AND t.name LIKE '%Opini%' LIMIT 1";
                    $res_opini = $orion_db->query($sql_opini);
                    if ($res_opini && $res_opini->num_rows > 0) {
                        $opini_row = $res_opini->fetch_object();
                        $opini_id = $opini_row->term_id;
                    }

                    if ($opini_id > 0) {
                        $opini_args = array(
                            'category' => $opini_id,
                            'numberposts' => 6
                        );
                        $opini_posts = get_posts($opini_args);
                        
                        if ($opini_posts) {
                            echo '<div class="mt-16 border-t border-gray-100 pt-10">';
                            echo '<h3 class="text-2xl font-bold mb-2 text-gray-900">Opini kita</h3>';
                            echo '<p class="text-gray-500 mb-8">Kategori opini oleh penulis lepas</p>';
                            
                            echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
                            foreach ($opini_posts as $post) {
                                $thumb_url = get_the_post_thumbnail_url($post->ID);
                                ?>
                                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col">
                                    <?php if ($thumb_url): ?>
                                    <div class="aspect-video w-full bg-gray-100 overflow-hidden relative group">
                                        <img src="<?php echo htmlspecialchars($thumb_url); ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                        <a href="?p=<?php echo $post->ID; ?>" class="absolute inset-0 z-0"></a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="p-5 flex flex-col flex-grow">
                                        <div class="text-xs text-gray-500 mb-2">
                                            <?php echo date('d M Y', strtotime($post->post_date)); ?>
                                        </div>
                                        <h4 class="font-bold text-gray-900 leading-snug mb-3 hover:text-orion-600 transition line-clamp-2">
                                            <a href="?p=<?php echo $post->ID; ?>"><?php echo htmlspecialchars($post->post_title); ?></a>
                                        </h4>
                                        <p class="text-sm text-gray-600 line-clamp-3 mb-4 flex-grow">
                                            <?php 
                                            $content = strip_tags($post->post_content);
                                            echo (strlen($content) > 80) ? substr($content, 0, 80) . '...' : $content;
                                            ?>
                                        </p>
                                        <a href="?p=<?php echo $post->ID; ?>" class="text-orion-600 text-sm font-bold hover:underline mt-auto">Baca Opini &rarr;</a>
                                    </div>
                                </article>
                                <?php
                            }
                            echo '</div>'; // End Grid
                            echo '</div>'; // End Section
                        }
                    }
                }

                // Pagination UI
                if ($current_cat_id > 0) {
                    $cat_obj = get_term($current_cat_id, 'category');
                    $total_posts = $cat_obj ? $cat_obj->count : 0;
                } else {
                    $count_posts = wp_count_posts();
                    $total_posts = $count_posts->publish;
                }
                
                $total_pages = ceil($total_posts / $posts_per_page);
                
                if ($total_pages > 1):
            ?>
                <div class="flex justify-center mt-12 space-x-2">
                    <?php if ($paged > 1): 
                        $prev_link = "?paged=" . ($paged - 1);
                        if ($current_cat_id > 0) $prev_link .= "&cat=" . $current_cat_id;
                    ?>
                        <a href="<?php echo $prev_link; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium transition shadow-sm">Previous</a>
                    <?php endif; ?>

                    <?php 
                    // Simple pagination range
                    $start_page = max(1, $paged - 2);
                    $end_page = min($total_pages, $paged + 2);
                    
                    if ($start_page > 1) echo '<span class="px-2 py-2 text-gray-400">...</span>';
                    
                    for ($i = $start_page; $i <= $end_page; $i++): 
                        $page_link = "?paged=" . $i;
                        if ($current_cat_id > 0) $page_link .= "&cat=" . $current_cat_id;
                    ?>
                        <a href="<?php echo $page_link; ?>" class="px-4 py-2 border <?php echo ($paged == $i) ? 'bg-orion-600 text-white border-orion-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 shadow-sm'; ?> rounded-lg font-medium transition">
                            <?php echo $i; ?>
                        </a>
                    <?php 
                    endfor; 
                    
                    if ($end_page < $total_pages) echo '<span class="px-2 py-2 text-gray-400">...</span>';
                    ?>

                    <?php if ($paged < $total_pages): 
                        $next_link = "?paged=" . ($paged + 1);
                        if ($current_cat_id > 0) $next_link .= "&cat=" . $current_cat_id;
                    ?>
                        <a href="<?php echo $next_link; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium transition shadow-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php
                endif;

            else:
            ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-yellow-800">Tidak ada konten</h3>
                            <p class="text-yellow-700 mt-1">Belum ada berita yang diposting. Silakan tambahkan berita dari Admin Panel.</p>
                        </div>
                    </div>
                </div>
            <?php endif; 
        } // End List View
        ?>
    </div>

    <!-- Sidebar -->
    <aside class="lg:col-span-4 xl:col-span-3">
        <div class="sticky top-8 space-y-8">
            
            <!-- Search Form -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-900">Cari Berita</h3>
                <form action="" method="get" class="relative">
                    <input type="text" name="s" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-orion-500 focus:bg-white transition" placeholder="Kata kunci..." value="<?php echo isset($_GET['s']) ? htmlspecialchars($_GET['s']) : ''; ?>">
                    <button type="submit" class="absolute right-2 top-2 bg-orion-600 text-white p-1.5 rounded-lg hover:bg-orion-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <!-- Banner Card -->
            <?php 
            $banner_image = get_option('orion_one_banner_image', '');
            $banner_link = get_option('orion_one_banner_link', '#');
            if ($banner_image):
            ?>
            <div class="rounded-2xl shadow-md overflow-hidden group">
                <a href="<?php echo htmlspecialchars($banner_link); ?>" target="_blank">
                    <img src="<?php echo htmlspecialchars($banner_image); ?>" alt="Banner" class="w-full h-auto group-hover:scale-105 transition duration-500">
                </a>
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-900 flex items-center justify-between">
                    Kategori
                    <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-1 rounded">Topik</span>
                </h3>
                <?php 
                $cats = get_categories();
                if ($cats):
                ?>
                <ul class="space-y-2">
                    <?php foreach($cats as $cat): ?>
                    <li>
                        <a href="<?php echo get_category_link($cat); ?>" class="group flex justify-between items-center text-gray-600 hover:text-orion-600 transition p-2 rounded hover:bg-gray-50">
                            <span class="font-medium"><?php echo htmlspecialchars($cat->name); ?></span> 
                            <span class="bg-gray-100 group-hover:bg-orion-100 text-gray-500 group-hover:text-orion-600 px-2.5 py-0.5 rounded-full text-xs font-bold transition"><?php echo $cat->count; ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                    <p class="text-gray-500 text-sm italic">Belum ada kategori.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold mb-6 text-gray-900 border-b pb-2">Berita Populer</h3>
                <div class="space-y-5">
                    <?php
                    $popular_args = array(
                        'numberposts' => 5,
                        'orderby' => 'rand'
                    );
                    $popular_posts = get_posts($popular_args);
                    
                    if ($popular_posts) :
                        foreach ($popular_posts as $post) : 
                            $thumb_url = get_the_post_thumbnail_url($post->ID);
                    ?>
                    <div class="flex gap-4 group items-start">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden relative">
                            <?php if ($thumb_url): ?>
                            <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-xs text-orion-600 font-bold mb-1 block uppercase tracking-wide">
                                <?php 
                                $cats = get_the_terms($post->ID, 'category');
                                echo $cats ? $cats[0]->name : 'Berita'; 
                                ?>
                            </span>
                            <h4 class="text-sm font-bold leading-snug text-gray-900 line-clamp-2 group-hover:text-orion-600 transition">
                                <a href="?p=<?php echo $post->ID; ?>"><?php echo htmlspecialchars($post->post_title); ?></a>
                            </h4>
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
            <div class="bg-gradient-to-br from-orion-600 to-blue-700 text-white p-8 rounded-2xl shadow-lg text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-white opacity-5" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIiAvPgo8cGF0aCBkPSJNMCAwTDggOFpNOCAwTDAgOFoiIHN0cm9rZT0iIzAwMCIgc3Ryb2tlLW9wYWNpdHk9IjAuMSIvPjwvc3ZnPg==');"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-2">Newsletter</h3>
                    <p class="text-blue-100 text-sm mb-6">Dapatkan update berita teknologi dan gaya hidup terbaru setiap minggu.</p>
                    <form action="" method="post" onsubmit="alert('Terima kasih telah berlangganan!'); return false;">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-3 rounded-xl text-gray-900 mb-3 focus:outline-none shadow-inner" required>
                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl transition shadow-lg transform hover:-translate-y-0.5">Berlangganan</button>
                    </form>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-6">Ikuti Kami</h3>
                <div class="flex gap-6 justify-center">
                    <a href="#" class="text-gray-400 hover:text-blue-600 hover:scale-110 transition transform"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="#" class="text-gray-400 hover:text-sky-500 hover:scale-110 transition transform"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                    <a href="#" class="text-gray-400 hover:text-pink-600 hover:scale-110 transition transform"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>
    </aside>
</div>

<?php get_footer(); ?>
