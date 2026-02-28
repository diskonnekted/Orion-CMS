<div class="space-y-12 pb-20">
    <!-- Page Header -->
    <div class="text-center space-y-4">
        <span class="inline-block px-4 py-1 rounded-full bg-libre-100 text-libre-700 font-bold text-xs tracking-widest uppercase border border-libre-200">Library Catalog</span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-libre-900">Explore Our <span class="text-libre-600 italic">Collections</span></h1>
        <p class="text-gray-500 font-serif max-w-2xl mx-auto">Telusuri seluruh koleksi buku digital kami. Dari literatur klasik hingga panduan teknis modern, temukan pengetahuan yang anda cari.</p>
    </div>

    <!-- Search & Filter Bar (Visual Only for now) -->
    <div class="bg-white p-4 rounded-2xl shadow-md border border-libre-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search books..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-libre-500 outline-none transition">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <select class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 outline-none focus:ring-2 focus:ring-libre-500">
                <option>All Categories</option>
                <?php 
                $categories = get_terms('category');
                foreach($categories as $cat) echo "<option value='{$cat->term_id}'>{$cat->name}</option>";
                ?>
            </select>
            <button class="bg-libre-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-libre-800 transition">Filter</button>
        </div>
    </div>

    <!-- Collections Grid -->
    <?php 
    global $orion_db, $table_prefix;
    $posts_table = $table_prefix . 'posts';
    // Fetch all published posts
    $all_books = get_posts(array('numberposts' => -1, 'post_type' => 'post'));
    
    if ($all_books): 
    ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
        <?php foreach($all_books as $book): ?>
        <article class="group flex flex-col h-full">
            <!-- Book Card -->
            <div class="relative aspect-[2/3] rounded-2xl overflow-hidden shadow-lg border border-libre-100 bg-white transition-all duration-500 group-hover:shadow-2xl group-hover:-translate-y-2">
                <img src="<?php echo orion_libre_get_book_cover($book->ID); ?>" alt="<?php echo $book->post_title; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-libre-900 via-libre-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                    <a href="index.php?p=<?php echo $book->ID; ?>" class="w-full py-3 bg-white text-libre-900 rounded-xl font-bold text-center text-xs shadow-xl transform translate-y-4 group-hover:translate-y-0 transition duration-500 hover:bg-libre-50">
                        View Details
                    </a>
                </div>

                <!-- Category Badge -->
                <?php 
                $cats = get_the_terms($book->ID, 'category');
                if ($cats): 
                ?>
                <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <span class="px-2 py-1 bg-libre-900/80 backdrop-blur text-white text-[8px] font-bold uppercase tracking-widest rounded-md">
                        <?php echo $cats[0]->name; ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Book Info -->
            <div class="mt-4 space-y-1 px-1">
                <h3 class="font-serif font-bold text-libre-900 group-hover:text-libre-600 transition truncate">
                    <a href="index.php?p=<?php echo $book->ID; ?>"><?php echo $book->post_title; ?></a>
                </h3>
                <div class="flex items-center justify-between text-[10px] text-gray-400 font-serif">
                    <span><?php echo date('M Y', strtotime($book->post_date)); ?></span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Digital
                    </span>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-libre-200">
        <p class="text-gray-400 font-serif italic">Belum ada koleksi yang tersedia.</p>
    </div>
    <?php endif; ?>
</div>
