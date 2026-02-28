<?php
get_header(); 
global $orion_query, $post;

// Determine if we are viewing a single book
$is_single = (isset($_GET['p']) && $_GET['p']);
$page = isset($_GET['page']) ? $_GET['page'] : '';

if ($page === 'about') {
    include 'about.php';
    get_footer();
    exit;
}

if ($page === 'collections') {
    include 'collections.php';
    get_footer();
    exit;
}

if ($page === 'categories') {
    include 'categories-all.php';
    get_footer();
    exit;
}

// Fetch Theme Settings
$featured_book_id = get_option('orion_libre_featured_book', '0');
$cat_title = get_option('orion_libre_cat_title', 'Explore Categories');
$cat_subtitle = get_option('orion_libre_cat_subtitle', 'Find your next favorite book by genre');
$active_cats_raw = get_option('orion_libre_active_cats', '');
$active_cats_ids = !empty($active_cats_raw) ? explode(',', $active_cats_raw) : [];
?>

<div class="container mx-auto">
    
    <?php if ($orion_query->have_posts()): ?>
        
        <?php if ($is_single): ?>
            <?php $orion_query->the_post(); ?>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Main Book Details -->
                <div class="lg:col-span-8 xl:col-span-9">
                    <article class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-libre-100">
                        <!-- Book Header with Cover -->
                        <div class="relative bg-gradient-to-b from-libre-50 to-white p-8 md:p-12 border-b border-libre-100">
                            <div class="flex flex-col md:flex-row gap-12 items-center md:items-start">
                                <!-- Book Cover Column -->
                                <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                                    <div class="relative group mx-auto md:mx-0 max-w-[280px]">
                                        <!-- Book Spine Shadow -->
                                        <div class="absolute inset-0 bg-black/20 rounded-lg transform translate-x-3 translate-y-3 blur-md"></div>
                                        <!-- The Cover -->
                                        <div class="relative aspect-[2/3] rounded-lg overflow-hidden shadow-2xl border-l-4 border-libre-900/20 z-10">
                                            <img src="<?php echo orion_libre_get_book_cover($post->ID); ?>" 
                                                 alt="<?php echo htmlspecialchars($post->post_title); ?>" 
                                                 class="w-full h-full object-cover transform transition duration-700 group-hover:scale-105">
                                            <!-- Glossy overlay effect -->
                                            <div class="absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-black/5 pointer-events-none"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Meta Column -->
                                <div class="flex-grow text-center md:text-left space-y-6">
                                    <div class="space-y-2">
                                        <span class="inline-block px-3 py-1 rounded-full bg-libre-100 text-libre-700 font-bold text-[10px] uppercase tracking-widest border border-libre-200">Digital Collection</span>
                                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-libre-900 leading-tight"><?php echo $post->post_title; ?></h1>
                                        <div class="flex items-center justify-center md:justify-start gap-4 text-gray-500 font-serif">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span><?php echo date('F j, Y', strtotime($post->post_date)); ?></span>
                                            </div>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span>Digital Library</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                        <a href="#reading-room" class="bg-libre-900 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-libre-800 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            Read Book
                                        </a>
                                        <?php 
                                        $cats = get_the_terms($post->ID, 'category');
                                        if ($cats): foreach($cats as $cat):
                                        ?>
                                        <a href="index.php?cat=<?php echo $cat->term_id; ?>" class="px-4 py-3 rounded-full border border-libre-200 bg-white text-gray-600 hover:border-libre-400 hover:text-libre-700 transition font-medium text-sm">
                                            <?php echo htmlspecialchars($cat->name); ?>
                                        </a>
                                        <?php endforeach; endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Content -->
                        <div class="p-8 md:p-12 space-y-12">
                            <div class="max-w-4xl">
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-serif">
                                    <h3 class="text-2xl font-bold text-libre-800 border-b border-libre-100 pb-3 mb-6 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-libre-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Synopsis
                                    </h3>
                                    <div class="bg-libre-50/30 p-6 md:p-8 rounded-2xl border border-libre-100 italic">
                                        <?php 
                                        $content = $post->post_content;
                                        // Robust decoding - 3 levels
                                        $decoded = $content;
                                        for ($i=0; $i<3; $i++) {
                                            $decoded = html_entity_decode($decoded, ENT_QUOTES, 'UTF-8');
                                        }
                                        if (function_exists('apply_filters')) {
                                            echo apply_filters('the_content', $decoded);
                                        } else {
                                            echo $decoded;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div id="reading-room" class="pt-8 border-t border-libre-100">
                                <h3 class="text-2xl font-serif font-bold text-libre-800 mb-8 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-libre-900 text-white rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    Reading Room
                                </h3>
                                
                                <?php 
                                $pdf_url = orion_libre_get_pdf($post->ID);
                                if ($pdf_url): 
                                ?>
                                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-libre-200">
                                        <?php echo orion_pdf_viewer($pdf_url); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-6 rounded-r-2xl">
                                        <div class="flex items-center gap-4">
                                            <svg class="h-8 w-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <div>
                                                <p class="text-amber-800 font-bold">PDF Not Available</p>
                                                <p class="text-sm text-amber-700">This book entry does not have a PDF file attached for online reading.</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar: Other Books -->
                <aside class="lg:col-span-4 xl:col-span-3 space-y-8">
                    <div class="bg-white p-6 rounded-3xl border border-libre-100 shadow-xl">
                        <h3 class="text-xl font-serif font-bold text-libre-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-libre-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Explore More
                        </h3>
                        <div class="space-y-6">
                            <?php 
                            $current_id = $post->ID;
                            $other_books = get_posts(array('numberposts' => 6, 'post_type' => 'post'));
                            foreach($other_books as $ob): 
                                if($ob->ID == $current_id) continue;
                            ?>
                            <a href="index.php?p=<?php echo $ob->ID; ?>" class="flex gap-4 group">
                                <div class="w-16 h-24 flex-shrink-0 rounded-md overflow-hidden shadow-md border border-libre-100">
                                    <img src="<?php echo orion_libre_get_book_cover($ob->ID); ?>" class="w-full h-full object-cover transform transition group-hover:scale-110">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-2 group-hover:text-libre-600 transition leading-tight mb-1"><?php echo $ob->post_title; ?></h4>
                                    <p class="text-[10px] text-gray-400 font-serif"><?php echo date('Y', strtotime($ob->post_date)); ?></p>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-libre-50">
                            <a href="index.php" class="block w-full py-3 text-center rounded-xl bg-libre-50 text-libre-700 font-bold text-xs hover:bg-libre-100 transition uppercase tracking-widest">
                                Back to Library
                            </a>
                        </div>
                    </div>

                    <!-- Library Stats / Info -->
                    <div class="bg-gradient-to-br from-libre-900 to-libre-800 p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
                        <svg class="absolute -bottom-10 -right-10 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.908 7.908 0 003 4c-1.105 0-2.09.425-2.83 1.115-.06.07-.13.14-.17.21V16a1 1 0 001.24.97 6 6 0 015.12 1.41H9V4.804zM11 4.804v12.596h.64a6 6 0 015.12-1.41 1 1 0 001.24-.97V5.325a.19.19 0 00-.01-.04.11.11 0 00-.01-.03c-.01-.02-.01-.03-.02-.05-.01-.01-.01-.02-.02-.03l-.01-.03a.12.11 0 00-.03-.02.1.1 0 00-.02-.03.11.11 0 00-.03-.02.11.11 0 00-.03-.02l-.02-.01-.04-.01c-.02 0-.04 0-.06.01a7.908 7.908 0 00-6-1.115z"></path></svg>
                        <h4 class="text-lg font-serif font-bold mb-2 relative z-10 text-libre-200">Open Library</h4>
                        <p class="text-xs text-libre-300 leading-relaxed relative z-10">Access thousands of digital resources for free. Knowledge should be accessible to everyone.</p>
                    </div>
                </aside>
            </div>

        <?php else: ?>
            
            <?php 
            // Handle Featured Book Logic
            $featured_post = null;
            if ($featured_book_id != '0') {
                $featured_post = get_post($featured_book_id);
            }
            
            // If specific ID not found or set to default, take the latest
            if (!$featured_post) {
                $all_posts = $orion_query->posts;
                $featured_post = !empty($all_posts) ? $all_posts[0] : null;
            }

            if ($featured_post):
            ?>
                <!-- Hero / Featured Book Section -->
                <div class="mb-16">
                    <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden border border-libre-100">
                        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-libre-100 rounded-full blur-3xl opacity-50"></div>
                        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-orange-50 rounded-full blur-3xl opacity-50"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row items-center p-8 md:p-12 gap-10">
                            <!-- Featured Cover -->
                            <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                                <div class="relative group perspective-1000">
                                    <div class="absolute inset-0 bg-libre-900 rounded-lg transform translate-x-2 translate-y-2 opacity-20"></div>
                                    <img src="<?php echo orion_libre_get_book_cover($featured_post->ID); ?>" alt="<?php echo $featured_post->post_title; ?>" class="relative w-full rounded-lg shadow-2xl transform transition duration-500 hover:-translate-y-2 hover:rotate-1 z-10 object-cover aspect-[2/3]">
                                </div>
                            </div>
                            
                            <!-- Featured Details -->
                            <div class="w-full md:w-2/3 lg:w-3/4 text-center md:text-left">
                                <div class="inline-block px-4 py-1 rounded-full bg-libre-100 text-libre-700 font-bold text-xs tracking-widest uppercase mb-4 border border-libre-200">Featured Book</div>
                                <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-libre-900 mb-4 leading-tight">
                                    <a href="index.php?p=<?php echo $featured_post->ID; ?>" class="hover:text-libre-700 transition duration-300"><?php echo $featured_post->post_title; ?></a>
                                </h2>
                                <div class="flex items-center justify-center md:justify-start gap-4 text-gray-500 mb-6 font-serif">
                                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <?php echo date('F Y', strtotime($featured_post->post_date)); ?></span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span>Digital Library</span>
                                </div>
                                <div class="text-gray-600 text-lg mb-8 leading-relaxed line-clamp-3 font-serif max-w-3xl">
                                    <?php 
                                    $content = $featured_post->post_content;
                                    $decoded = $content;
                                    for ($i=0; $i<3; $i++) {
                                        $decoded = html_entity_decode($decoded, ENT_QUOTES, 'UTF-8');
                                    }
                                    echo strip_tags($decoded); 
                                    ?>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                    <a href="index.php?p=<?php echo $featured_post->ID; ?>" class="px-8 py-4 bg-libre-900 text-white rounded-full font-bold shadow-lg hover:bg-libre-800 hover:shadow-xl transform hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        Start Reading
                                    </a>
                                    <a href="index.php?p=<?php echo $featured_post->ID; ?>" class="px-8 py-4 bg-white text-libre-900 border-2 border-libre-100 rounded-full font-bold hover:border-libre-300 hover:bg-libre-50 transition duration-300 flex items-center justify-center">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Categories Section -->
            <?php 
            $all_categories = get_terms('category');
            if ($all_categories && !empty($all_categories)): 
                $display_categories = $all_categories;
                // Filter by active cats if set in settings
                if (!empty($active_cats_ids)) {
                    $display_categories = array_filter($all_categories, function($c) use ($active_cats_ids) {
                        return in_array($c->term_id, $active_cats_ids);
                    });
                }
                
                if (!empty($display_categories)):
            ?>
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-serif font-bold text-libre-900"><?php echo htmlspecialchars($cat_title); ?></h3>
                        <p class="text-gray-500 text-sm mt-1"><?php echo htmlspecialchars($cat_subtitle); ?></p>
                    </div>
                    <a href="index.php?page=categories" class="text-libre-600 hover:text-libre-800 text-sm font-bold flex items-center gap-1 group">
                        View All <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    <?php 
                    $colors = ['from-rose-400 to-orange-300', 'from-amber-400 to-yellow-200', 'from-emerald-400 to-teal-300', 'from-blue-400 to-indigo-300', 'from-violet-400 to-purple-300', 'from-fuchsia-400 to-pink-300'];
                    
                    $icons_map = [
                        'Berita'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>',
                        'Artikel'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                        'Opini'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>',
                        'Teknologi'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>',
                        'Pendidikan'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>',
                        'Bisnis'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
                        'Kesehatan'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.0 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
                        'Hiburan'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 007 9.817v4.366a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'Gaya Hidup'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'Fashion'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>',
                        'Komputer'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
                        'Otomotif'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>',
                        'Default'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'
                    ];

                    $i = 0;
                    foreach($display_categories as $cat): 
                        $grad = $colors[$i % count($colors)];
                        $i++;
                        
                        // Find matching icon
                        $svg_path = $icons_map['Default'];
                        foreach($icons_map as $key => $path) {
                            if (stripos($cat->name, $key) !== false) {
                                $svg_path = $path;
                                break;
                            }
                        }
                    ?>
                    <a href="index.php?cat=<?php echo $cat->term_id; ?>" class="group relative flex flex-col items-center p-8 bg-white rounded-[2.5rem] shadow-sm border border-libre-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 overflow-hidden">
                        <!-- Background Glow -->
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-gradient-to-br <?php echo $grad; ?> opacity-0 group-hover:opacity-20 blur-2xl transition-opacity duration-500"></div>
                        
                        <!-- Floating Icon -->
                        <div class="relative mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br <?php echo $grad; ?> rounded-2xl flex items-center justify-center text-white shadow-lg transform transition-transform duration-500 group-hover:rotate-12 group-hover:scale-110">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo $svg_path; ?>
                                </svg>
                            </div>
                            <!-- Icon Reflection -->
                            <div class="absolute inset-0 bg-gradient-to-br <?php echo $grad; ?> rounded-2xl blur-lg opacity-40 -z-10 group-hover:opacity-60 transition-opacity"></div>
                        </div>

                        <h4 class="relative z-10 font-bold text-gray-800 group-hover:text-libre-900 transition text-center leading-tight"><?php echo $cat->name; ?></h4>
                        <div class="mt-2 text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-libre-600 transition">Explore</div>
                        
                        <!-- Staggered bottom border -->
                        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-gradient-to-r <?php echo $grad; ?> transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                endif;
            endif; 
            ?>

            <!-- Other Books Grid Title -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-serif font-bold text-libre-900">More to Discover</h2>
                <div class="h-px bg-libre-200 flex-grow ml-6"></div>
            </div>

            <!-- 6 Column Grid Start -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-12">
            
            <?php 
            $post_count = 0;
            while($orion_query->have_posts()): $orion_query->the_post(); 
                $post_count++;
                // Skip the featured post in the grid if we are on page 1
                if ($post_count == 1 && $featured_post && $post->ID == $featured_post->ID) {
                    // Optional: If you want to skip it, just don't render. 
                    // But if it was the latest, it would be the first.
                    // For now, let's just render all in the grid.
                }
            ?>
                <article class="bg-white rounded-lg shadow-sm hover:shadow-xl transition duration-300 overflow-hidden border border-libre-100 flex flex-col h-full group hover:-translate-y-1">
                    <div class="relative overflow-hidden w-full" style="aspect-ratio: 2/3;">
                         <img src="<?php echo orion_libre_get_book_cover($post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                         <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                             <a href="index.php?p=<?php echo $post->ID; ?>" class="bg-white text-libre-900 px-4 py-2 rounded-full font-bold text-xs shadow-lg hover:bg-libre-50 transform translate-y-4 group-hover:translate-y-0 transition duration-300">Read Now</a>
                         </div>
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="text-sm font-bold text-gray-900 mb-1 leading-snug line-clamp-2 group-hover:text-libre-700 transition">
                            <a href="index.php?p=<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
                        </h3>
                        <div class="mt-auto pt-2 flex items-center justify-between text-xs text-gray-400 font-serif border-t border-gray-50">
                            <span><?php echo date('Y', strtotime($post->post_date)); ?></span>
                        </div>
                    </div>
                </article>
            
            <?php endwhile; ?>
            
            <?php if ($post_count > 0): ?>
                </div> <!-- Close Grid -->
            <?php endif; ?>

        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-20">
            <h2 class="text-2xl font-bold text-gray-400">The library is currently empty.</h2>
            <p class="text-gray-500 mt-2">Please add some books from the admin panel.</p>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
