<?php get_header(); ?>

<?php 
$post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
$cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$search_query = isset($_GET['s']) ? $_GET['s'] : '';

// --- SINGLE WALLPAPER VIEW ---
if ($post_id > 0) {
    $post = get_post($post_id);
    if ($post) {
        $image_url = get_the_post_thumbnail_url($post->ID);
        $cats = get_the_terms($post->ID, 'category');
        $album_name = $cats ? $cats[0]->name : 'Uncategorized';
        $album_link = $cats ? '?cat=' . $cats[0]->term_id : '#';
        ?>
        <div class="max-w-5xl mx-auto">
            <div class="mb-6">
                <a href="<?php echo site_url(); ?>" class="text-slate-400 hover:text-white transition">&larr; Back to Gallery</a>
            </div>

            <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-2xl border border-slate-700">
                <!-- Image Preview -->
                <div class="relative bg-black flex justify-center items-center p-4 min-h-[400px]">
                    <?php if ($image_url): ?>
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="max-h-[80vh] w-auto object-contain shadow-lg rounded">
                    <?php else: ?>
                        <div class="text-slate-500">No Preview Available</div>
                    <?php endif; ?>
                </div>

                <!-- Info & Download -->
                <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2"><?php echo htmlspecialchars($post->post_title); ?></h1>
                        <div class="flex items-center gap-4 text-sm text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Album: <a href="<?php echo $album_link; ?>" class="text-orion-500 hover:underline"><?php echo htmlspecialchars($album_name); ?></a>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php echo date('d M Y', strtotime($post->post_date)); ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($image_url): ?>
                    <a href="<?php echo htmlspecialchars($image_url); ?>" download="<?php echo htmlspecialchars($post->post_title); ?>" class="bg-orion-600 hover:bg-orion-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Wallpaper
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="text-center py-20 text-slate-400">Wallpaper not found.</div>';
    }

// --- GALLERY / ALBUM VIEW ---
} else {
    // Determine Title
    $page_title = 'Latest Wallpapers';
    if ($cat_id > 0) {
        $term = get_term($cat_id); // Assuming this function exists or similar
        // Fallback if get_term is strict
        global $orion_db, $table_prefix;
        $res = $orion_db->query("SELECT name FROM {$table_prefix}terms WHERE term_id = $cat_id");
        if ($res && $row = $res->fetch_object()) {
            $page_title = 'Album: ' . $row->name;
        }
    } elseif ($search_query) {
        $page_title = 'Search: ' . htmlspecialchars($search_query);
    }

    // Pagination & Query
    $paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
    $posts_per_page = 12; // 4x3 grid
    $offset = ($paged - 1) * $posts_per_page;
    
    $args = array(
        'numberposts' => $posts_per_page,
        'offset' => $offset,
        'post_status' => 'publish',
        'post_type' => 'post'
    );

    if ($cat_id > 0) $args['category'] = $cat_id;
    if ($search_query) $args['s'] = $search_query;
    
    $wallpapers = get_posts($args);
    ?>

    <div class="mb-8 flex justify-between items-end border-b border-slate-700 pb-4">
        <h1 class="text-3xl font-bold text-white"><?php echo $page_title; ?></h1>
        
        <!-- Search Form -->
        <form action="" method="GET" class="flex gap-2">
            <input type="text" name="s" placeholder="Search wallpapers..." value="<?php echo htmlspecialchars($search_query); ?>" class="bg-slate-800 border border-slate-700 text-white px-4 py-2 rounded focus:outline-none focus:border-orion-500">
            <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <?php if ($wallpapers): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($wallpapers as $wp): 
                $thumb = get_the_post_thumbnail_url($wp->ID);
                $cats = get_the_terms($wp->ID, 'category');
                $album = $cats ? $cats[0]->name : '';
            ?>
                <div class="group relative bg-slate-800 rounded-xl overflow-hidden shadow-lg border border-slate-700 hover:border-orion-500 transition duration-300">
                    <div class="aspect-[9/16] overflow-hidden bg-slate-900">
                        <?php if ($thumb): ?>
                            <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($wp->post_title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-600">No Image</div>
                        <?php endif; ?>
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-4">
                            <a href="?p=<?php echo $wp->ID; ?>" class="absolute inset-0"></a>
                            <h3 class="text-white font-bold truncate"><?php echo htmlspecialchars($wp->post_title); ?></h3>
                            <?php if ($album): ?>
                                <span class="text-xs text-orion-400"><?php echo htmlspecialchars($album); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php
        // Total count logic (simplified)
        global $orion_db, $table_prefix;
        $sql_count = "SELECT COUNT(*) as count FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish'";
        if ($cat_id > 0) {
            // This is complex without proper join in raw SQL if terms are separate.
            // Assuming get_posts handles it, but for count we need to be careful.
            // For now, let's skip precise pagination count for categories or assume simpler structure.
            // Or use a helper if available. 
            // Since this is a custom CMS, I'll rely on basic next/prev if strict count is hard.
        }
        // ... (Skipping complex count query for now, implementing simple Next/Prev)
        
        echo '<div class="mt-12 flex justify-center gap-4">';
        if ($paged > 1) {
            $prev_link = "?paged=" . ($paged - 1) . ($cat_id ? "&cat=$cat_id" : "") . ($search_query ? "&s=$search_query" : "");
            echo '<a href="' . $prev_link . '" class="px-6 py-2 bg-slate-800 border border-slate-700 rounded hover:bg-slate-700 transition">Previous Page</a>';
        }
        // Always show Next button "blindly" or check if we got full page
        if (count($wallpapers) == $posts_per_page) {
            $next_link = "?paged=" . ($paged + 1) . ($cat_id ? "&cat=$cat_id" : "") . ($search_query ? "&s=$search_query" : "");
            echo '<a href="' . $next_link . '" class="px-6 py-2 bg-slate-800 border border-slate-700 rounded hover:bg-slate-700 transition">Next Page</a>';
        }
        echo '</div>';
        ?>

    <?php else: ?>
        <div class="text-center py-20 bg-slate-800 rounded-xl border border-slate-700">
            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-xl text-slate-400">No wallpapers found.</p>
        </div>
    <?php endif; ?>

<?php } ?>

<?php get_footer(); ?>
