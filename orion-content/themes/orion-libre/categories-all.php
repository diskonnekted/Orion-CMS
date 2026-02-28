<div class="space-y-12 pb-20">
    <!-- Page Header -->
    <div class="text-center space-y-4">
        <span class="inline-block px-4 py-1 rounded-full bg-libre-100 text-libre-700 font-bold text-xs tracking-widest uppercase border border-libre-200">Genre & Topics</span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-libre-900">All <span class="text-libre-600 italic">Categories</span></h1>
        <p class="text-gray-500 font-serif max-w-2xl mx-auto">Temukan buku berdasarkan topik atau genre yang anda minati. Kami mengelompokkan koleksi kami untuk memudahkan pencarian anda.</p>
    </div>

    <!-- Full Categories Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-8">
        <?php 
        $all_categories = get_terms('category');
        $colors = ['from-rose-400 to-orange-300', 'from-amber-400 to-yellow-200', 'from-emerald-400 to-teal-300', 'from-blue-400 to-indigo-300', 'from-violet-400 to-purple-300', 'from-fuchsia-400 to-pink-300'];
        
        $icons_map = [
            'Berita'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>',
            'Artikel'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
            'Opini'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>',
            'Teknologi'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>',
            'Pendidikan'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>',
            'Bisnis'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
            'Kesehatan'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.0 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.0 0 00-6.364 0z"></path>',
            'Hiburan'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 007 9.817v4.366a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            'Default'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'
        ];

        $idx = 0;
        foreach($all_categories as $cat): 
            $grad = $colors[$idx % count($colors)];
            $idx++;
            
            $svg_path = $icons_map['Default'];
            foreach($icons_map as $key => $path) {
                if (stripos($cat->name, $key) !== false) {
                    $svg_path = $path;
                    break;
                }
            }
        ?>
        <a href="index.php?cat=<?php echo $cat->term_id; ?>" class="group relative flex flex-col items-center p-8 bg-white rounded-[2.5rem] shadow-sm border border-libre-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-gradient-to-br <?php echo $grad; ?> opacity-0 group-hover:opacity-20 blur-2xl transition-opacity duration-500"></div>
            
            <div class="relative mb-6">
                <div class="w-16 h-16 bg-gradient-to-br <?php echo $grad; ?> rounded-2xl flex items-center justify-center text-white shadow-lg transform transition-transform duration-500 group-hover:rotate-12 group-hover:scale-110">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?php echo $svg_path; ?></svg>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br <?php echo $grad; ?> rounded-2xl blur-lg opacity-40 -z-10 group-hover:opacity-60 transition-opacity"></div>
            </div>

            <h4 class="relative z-10 font-bold text-gray-800 group-hover:text-libre-900 transition text-center leading-tight"><?php echo $cat->name; ?></h4>
            <div class="mt-2 text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-libre-600 transition">View Books</div>
            
            <div class="absolute bottom-0 left-0 w-full h-1.5 bg-gradient-to-r <?php echo $grad; ?> transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
