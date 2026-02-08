<?php
get_header(); 
global $orion_query, $post;

// Determine if we are viewing a single book
$is_single = (isset($_GET['p']) && $_GET['p']);
?>

<div class="container mx-auto">
    
    <?php if ($orion_query->have_posts()): ?>
        
        <?php if ($is_single): ?>
            <?php $orion_query->the_post(); ?>
            <article class="bg-white shadow-xl rounded-lg overflow-hidden border border-libre-200">
                <div class="p-8">
                    <div class="mb-6 border-b border-libre-200 pb-4">
                        <span class="text-libre-500 font-bold uppercase tracking-wider text-sm">Book Details</span>
                        <h1 class="text-4xl font-serif font-bold text-libre-900 mt-2"><?php echo $post->post_title; ?></h1>
                        <p class="text-gray-500 mt-2">Added on <?php echo date('F j, Y', strtotime($post->post_date)); ?></p>
                    </div>

                    <div class="mb-8">
                        <div class="prose max-w-none text-gray-700 leading-relaxed font-serif">
                            <h3 class="text-xl font-bold text-libre-800 border-b border-libre-200 pb-2 mb-4">Synopsis</h3>
                            <?php echo nl2br($post->post_content); ?>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-serif font-bold text-libre-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Reading Room
                        </h3>
                        
                        <?php 
                        $pdf_url = orion_libre_get_pdf($post->ID);
                        if ($pdf_url): 
                        ?>
                            <?php echo orion_pdf_viewer($pdf_url); ?>
                        <?php else: ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No PDF book attached to this entry.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        <?php else: ?>
            
            <?php 
            $post_count = 0;
            while($orion_query->have_posts()): $orion_query->the_post(); 
                $post_count++;
                if ($post_count == 1):
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
                                    <img src="<?php echo orion_libre_get_book_cover($post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="relative w-full rounded-lg shadow-2xl transform transition duration-500 hover:-translate-y-2 hover:rotate-1 z-10 object-cover aspect-[2/3]">
                                </div>
                            </div>
                            
                            <!-- Featured Details -->
                            <div class="w-full md:w-2/3 lg:w-3/4 text-center md:text-left">
                                <div class="inline-block px-4 py-1 rounded-full bg-libre-100 text-libre-700 font-bold text-xs tracking-widest uppercase mb-4 border border-libre-200">Featured Book</div>
                                <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-libre-900 mb-4 leading-tight">
                                    <a href="index.php?p=<?php echo $post->ID; ?>" class="hover:text-libre-700 transition duration-300"><?php echo $post->post_title; ?></a>
                                </h2>
                                <div class="flex items-center justify-center md:justify-start gap-4 text-gray-500 mb-6 font-serif">
                                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <?php echo date('F Y', strtotime($post->post_date)); ?></span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span>Digital Library</span>
                                </div>
                                <div class="text-gray-600 text-lg mb-8 leading-relaxed line-clamp-3 font-serif max-w-3xl">
                                    <?php echo strip_tags($post->post_content); ?>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                    <a href="index.php?p=<?php echo $post->ID; ?>" class="px-8 py-4 bg-libre-900 text-white rounded-full font-bold shadow-lg hover:bg-libre-800 hover:shadow-xl transform hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        Start Reading
                                    </a>
                                    <a href="index.php?p=<?php echo $post->ID; ?>" class="px-8 py-4 bg-white text-libre-900 border-2 border-libre-100 rounded-full font-bold hover:border-libre-300 hover:bg-libre-50 transition duration-300 flex items-center justify-center">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Section (Moved here) -->
                <?php 
                $categories = get_terms('category');
                if ($categories && !empty($categories)): 
                ?>
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-serif font-bold text-libre-900">Explore Categories</h3>
                            <p class="text-gray-500 text-sm mt-1">Find your next favorite book by genre</p>
                        </div>
                        <a href="#" class="text-libre-600 hover:text-libre-800 text-sm font-bold flex items-center gap-1 group">
                            View All <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        <?php foreach($categories as $cat): ?>
                        <a href="index.php?cat=<?php echo $cat->term_id; ?>" class="group block bg-white rounded-xl shadow-sm hover:shadow-md border border-libre-100 p-6 transition duration-300 text-center hover:-translate-y-1">
                            <div class="w-14 h-14 bg-gradient-to-br from-libre-50 to-libre-100 text-libre-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:from-libre-600 group-hover:to-libre-800 group-hover:text-white transition duration-300 shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h4 class="font-bold text-gray-800 group-hover:text-libre-700 truncate"><?php echo $cat->name; ?></h4>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Other Books Grid Title -->
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-serif font-bold text-libre-900">More to Discover</h2>
                    <div class="h-px bg-libre-200 flex-grow ml-6"></div>
                </div>

                <!-- 6 Column Grid Start -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-12">
            
            <?php else: // Start of grid items (post_count > 1) ?>
            
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

            <?php endif; // End check for first post ?>
            
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
