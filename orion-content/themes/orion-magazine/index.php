<?php get_header(); ?>

<?php
// Handle Page Views - Only if NOT front page
if (!is_front_page() && is_page()) {
    while (have_posts()) : the_post();
?>
    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm p-8 md:p-12">
            <h1 class="text-4xl font-bold text-slate-800 mb-8 font-serif border-b pb-4"><?php the_title(); ?></h1>
            <div class="prose prose-lg prose-emerald max-w-none text-slate-600">
                <?php the_content(); ?>
            </div>
        </div>
    </main>
<?php
    endwhile;
    get_footer();
    return; // Stop execution
}

// Handle Single Post Views - Only if NOT front page
if (!is_front_page() && is_single()) {
    while (have_posts()) : the_post();
        // Get Category
        $categories = get_the_terms($post->ID, 'category');
        $cat_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'News';
        $bg_image = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
?>
    <article>
        <!-- Article Header -->
        <header class="relative h-[400px] md:h-[500px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo $bg_image; ?>');"></div>
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
            <div class="relative z-10 container mx-auto px-4 text-center max-w-4xl">
                <span class="inline-block bg-emerald-500 text-white text-sm font-bold px-4 py-1.5 rounded-full mb-6 uppercase tracking-wider">
                    <?php echo $cat_name; ?>
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 font-serif leading-tight">
                    <?php the_title(); ?>
                </h1>
                <div class="flex items-center justify-center text-slate-300 text-sm md:text-base">
                    <span class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Orion Editor
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo date('F j, Y', strtotime($post->post_date)); ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Article Content -->
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 prose prose-lg prose-emerald max-w-none text-slate-700 leading-relaxed">
                        <?php the_content(); ?>
                    </div>
                </div>
                <aside class="lg:col-span-4 space-y-8">
                    <!-- Share Widget -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                        <h4 class="font-bold text-slate-800 mb-4 font-serif">Share this article</h4>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">Facebook</button>
                            <button class="flex-1 bg-sky-500 text-white py-2 rounded-lg text-sm font-bold hover:bg-sky-600 transition">Twitter</button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>
<?php
    endwhile;
    get_footer();
    return; // Stop execution
}
?>

<main class="container mx-auto px-4 py-8">

    <!-- Hero Section (First Post) -->
    <?php 
    // Custom query for the first post
    $args = array(
        'numberposts' => 1,
        'taxonomy' => 'category'
    );
    $hero_query = new WP_Query($args);
    $hero_post_id = 0;
    
    if ($hero_query->have_posts()) : 
        while ($hero_query->have_posts()) : $hero_query->the_post();
            $hero_post_id = $post->ID;
            // High quality hero image
            $bg_image = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
            
            // Get Category
            $categories = get_the_terms($post->ID, 'category');
            $hero_cat_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'Featured';

            // Get Author
            $author_name = 'Orion Editor';
            // In a real scenario: $author_name = get_the_author(); 
    ?>
    <section class="mb-12 rounded-2xl overflow-hidden relative shadow-xl group h-[400px] md:h-[500px]">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105" style="background-image: url('<?php echo $bg_image; ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent opacity-90"></div>
        
        <div class="relative z-10 p-8 md:p-16 flex flex-col justify-end h-full max-w-5xl mx-auto">
            <span class="inline-block bg-emerald-500 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6 w-fit uppercase tracking-wider shadow-lg ring-2 ring-emerald-500/30">
                <?php echo $hero_cat_name; ?>
            </span>
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight drop-shadow-md font-serif">
                <a href="<?php the_permalink(); ?>" class="hover:text-emerald-400 transition-colors">
                    <?php the_title(); ?>
                </a>
            </h2>
            <div class="flex items-center text-slate-300 text-sm md:text-base mb-8 font-medium">
                <span class="mr-6 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center mr-3 border border-slate-600">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <?php echo $author_name; ?>
                </span>
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php echo date('F j, Y', strtotime($post->post_date)); ?>
                </span>
            </div>
            <p class="text-slate-200 text-lg md:text-xl line-clamp-2 md:line-clamp-3 font-light leading-relaxed max-w-3xl">
                <?php echo wp_trim_words($post->post_content, 35); ?>
            </p>
        </div>
    </section>
    <?php 
        endwhile; 
    endif; 
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Main Content Column -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-10 border-b border-slate-200 pb-4">
                <h3 class="text-3xl font-bold text-slate-800 font-serif relative">
                    Latest Stories
                    <span class="absolute bottom-[-17px] left-0 w-1/2 h-1 bg-emerald-500 rounded-full"></span>
                </h3>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800 flex items-center group">
                    View All 
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php 
                // Image pool for variety
                $story_images = [
                    'https://images.unsplash.com/photo-1495020689067-958852a7765e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1523995462485-3d171b5c8fa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-15036949786-1600f9b2c07e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
                ];

                // Custom Query for Latest Stories
                $latest_args = array(
                    'numberposts' => 7,
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'taxonomy' => 'category'
                );
                $latest_query = new WP_Query($latest_args);

                if ( $latest_query->have_posts() ) : 
                    $i = 0;
                    while ( $latest_query->have_posts() ) : $latest_query->the_post(); 
                        // Skip the post already shown in Hero section
                        if ($post->ID == $hero_post_id) continue;

                        $img_index = $i % count($story_images);
                        $current_img = $story_images[$img_index];
                        $i++;

                        // Get Category
                        $categories = get_the_terms($post->ID, 'category');
                        $cat_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'News';
                ?>
                <article class="flex flex-col group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 h-full">
                    <div class="relative overflow-hidden h-56">
                        <a href="<?php the_permalink(); ?>" class="block h-full">
                            <img src="<?php echo $current_img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </a>
                        <span class="absolute top-4 left-4 bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md uppercase tracking-wider">
                            <?php echo $cat_name; ?>
                        </span>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-3 flex items-center text-xs text-slate-400">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?php echo date('M j, Y', strtotime($post->post_date)); ?>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-700 transition-colors font-serif leading-snug">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-3 leading-relaxed flex-grow">
                            <?php echo wp_trim_words($post->post_content, 20); ?>
                        </p>
                        <div class="pt-4 border-t border-slate-50 mt-auto">
                            <a href="<?php the_permalink(); ?>" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800 flex items-center group/link">
                                Read More
                                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>
                <?php 
                    endwhile; 
                else: 
                ?>
                <div class="text-center py-20 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <p class="text-slate-500 font-medium">No posts found.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-10 pl-0 lg:pl-8 border-l border-slate-100/0 lg:border-slate-100">
            
            <!-- Newsletter Widget -->
            <div class="bg-slate-900 p-8 rounded-2xl shadow-xl text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-emerald-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 bg-blue-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <h4 class="font-bold text-white mb-2 font-serif text-xl">Newsletter</h4>
                    <p class="text-slate-400 text-sm mb-6 leading-relaxed">Subscribe to get the latest news and updates delivered to your inbox.</p>
                    <form class="space-y-3">
                        <input type="email" placeholder="Your email address" class="w-full px-4 py-3 rounded-lg border border-slate-700 bg-slate-800 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-500 transition-all">
                        <button type="submit" class="w-full bg-emerald-500 text-white font-bold py-3 rounded-lg hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-500/25 transform hover:-translate-y-0.5">Subscribe Now</button>
                    </form>
                </div>
            </div>

            <!-- Categories Widget -->
            <div>
                <h4 class="font-bold text-slate-800 mb-6 text-lg border-l-4 border-emerald-500 pl-4 uppercase tracking-wider text-xs">Explore Topics</h4>
                <div class="flex flex-wrap gap-2">
                    <?php 
                    $categories = get_categories();
                    if ($categories) :
                        foreach($categories as $cat):
                    ?>
                    <a href="<?php echo get_category_link($cat); ?>" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all shadow-sm">
                        <?php echo $cat->name; ?> 
                        <span class="text-slate-400 text-xs ml-1">(<?php echo $cat->count; ?>)</span>
                    </a>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <span class="text-slate-500 text-sm italic">No categories found.</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular News Widget -->
            <div>
                <h4 class="font-bold text-slate-800 mb-6 text-lg border-l-4 border-emerald-500 pl-4 uppercase tracking-wider text-xs">Trending Now</h4>
                <div class="space-y-6">
                    <?php
                    $popular_args = array(
                        'numberposts' => 4,
                        'orderby' => 'rand',
                        'taxonomy' => 'category'
                    );
                    $popular_posts = get_posts($popular_args);
                    
                    if ($popular_posts) :
                        $p_i = 0;
                        foreach ($popular_posts as $post) : setup_postdata($post);
                            $p_img_index = ($p_i + 2) % count($story_images); // Offset to be different from main loop
                            $p_current_img = $story_images[$p_img_index];
                            $p_i++;
                    ?>
                    <div class="flex gap-4 group cursor-pointer">
                        <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden bg-slate-100 shadow-sm relative">
                            <img src="<?php echo $p_current_img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-xs font-bold text-emerald-600 mb-1">Trending</span>
                            <h5 class="font-bold text-slate-800 text-sm leading-snug mb-2 group-hover:text-emerald-700 transition-colors font-serif line-clamp-2">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <span class="text-xs text-slate-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php echo date('M j, Y', strtotime($post->post_date)); ?>
                            </span>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                        wp_reset_postdata();
                    else:
                    ?>
                    <p class="text-sm text-slate-500">No popular news available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ad / Banner Placeholder -->
            <div class="bg-gradient-to-br from-slate-100 to-slate-200 h-80 rounded-2xl flex flex-col items-center justify-center text-center p-8 border border-slate-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/50 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Advertisement</span>
                <h5 class="font-serif text-xl font-bold text-slate-700 mb-4">Space Available</h5>
                <p class="text-sm text-slate-500 mb-6">Contact us to place your ad here and reach our audience.</p>
                <button class="px-6 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-700 transition-colors">Contact Us</button>
            </div>

        </aside>

    </div>
</main>

<?php get_footer(); ?>