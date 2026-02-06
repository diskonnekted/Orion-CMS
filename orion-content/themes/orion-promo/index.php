<?php get_header(); ?>

<!-- Hero Section -->
<section class="pt-32 pb-20 bg-gradient-to-br from-brand-50 to-white overflow-hidden relative">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/hero-bg.svg" alt="Background Pattern" class="w-full h-full object-cover opacity-60">
    </div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-4xl mx-auto">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold mb-6 animate-fade-in-up">
                v1.0 Sekarang Tersedia
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-slate-900 mb-6 leading-tight tracking-tight">
                Kelola Konten Anda <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-purple-600">Tanpa Batas</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Orion CMS adalah solusi manajemen konten yang cepat, ringan, dan fleksibel. Dirancang untuk performa dan kemudahan penggunaan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#features" class="px-8 py-4 bg-brand-600 text-white rounded-full font-bold text-lg hover:bg-brand-700 transition shadow-xl shadow-brand-500/20 transform hover:-translate-y-1">
                    Pelajari Lebih Lanjut
                </a>
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-full font-bold text-lg hover:bg-slate-50 transition hover:border-slate-300">
                    Live Demo
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Kenapa Memilih Orion?</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Kami fokus pada hal-hal yang penting: kecepatan, keamanan, dan pengalaman pengguna.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Performa Tinggi</h3>
                <p class="text-slate-600 leading-relaxed">Dibangun dengan kode yang efisien dan ringan, memastikan website Anda memuat dalam hitungan milidetik.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Mudah Kustomisasi</h3>
                <p class="text-slate-600 leading-relaxed">Struktur tema yang fleksibel dan sistem plugin yang kuat memungkinkan Anda membangun apa saja.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Aman & Terpercaya</h3>
                <p class="text-slate-600 leading-relaxed">Keamanan adalah prioritas utama kami. Data Anda dilindungi dengan standar industri terbaik.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest News / Showcase -->
<section id="news" class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Berita Terbaru</h2>
                <p class="text-slate-600">Update terkini dari dunia Orion.</p>
            </div>
            <a href="#" class="hidden md:inline-flex items-center font-semibold text-brand-600 hover:text-brand-700">
                Lihat Semua
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php 
            // Simple logic to get posts directly if function exists, mimicking standard WP loop
            $posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
            if ($posts):
                foreach ($posts as $post):
                    $thumb_url = get_the_post_thumbnail_url($post->ID);
            ?>
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col h-full">
                <?php if ($thumb_url): ?>
                <div class="h-48 overflow-hidden">
                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                </div>
                <?php else: ?>
                <div class="h-48 bg-slate-200 flex items-center justify-center text-slate-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <?php endif; ?>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="text-sm text-brand-600 font-semibold mb-2">
                        <?php echo date('d M Y', strtotime($post->post_date)); ?>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2">
                        <a href="?p=<?php echo $post->ID; ?>" class="hover:text-brand-600 transition">
                            <?php echo htmlspecialchars($post->post_title); ?>
                        </a>
                    </h3>
                    <p class="text-slate-600 mb-4 line-clamp-3 flex-grow">
                        <?php echo substr(strip_tags($post->post_content), 0, 100) . '...'; ?>
                    </p>
                    <a href="?p=<?php echo $post->ID; ?>" class="text-brand-600 font-medium hover:text-brand-700 inline-flex items-center mt-auto">
                        Baca Selengkapnya
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </article>
            <?php endforeach; else: ?>
                <div class="col-span-3 text-center py-10 bg-white rounded-xl border border-dashed border-slate-300">
                    <p class="text-slate-500">Belum ada berita yang dipublikasikan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-brand-600 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Memulai Project Anda?</h2>
        <p class="text-xl text-brand-100 mb-10 max-w-2xl mx-auto">Bergabunglah dengan ribuan pengembang yang telah memilih Orion CMS untuk website mereka.</p>
        <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-8 py-4 bg-white text-brand-600 rounded-full font-bold text-lg hover:bg-brand-50 transition shadow-lg">
            Mulai Sekarang
        </a>
    </div>
</section>

<?php get_footer(); ?>
