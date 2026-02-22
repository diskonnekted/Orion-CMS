<?php get_header(); ?>

<?php if (is_single()): ?>
    <!-- Single Post View -->
    <div class="max-w-4xl mx-auto px-4 py-12">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                 <?php 
                 $feat_img = get_the_post_thumbnail_url(get_the_ID(), 'large');
                 if (!$feat_img) {
                    $gallery = get_post_meta(get_the_ID(), '_gallery_images', true);
                    if ($gallery) {
                        $images = json_decode($gallery, true);
                        if (!empty($images) && is_array($images)) $feat_img = $images[0];
                    }
                 }
                 if (!$feat_img && function_exists('get_first_image_from_content')) $feat_img = get_first_image_from_content(get_the_content());
                 
                 if ($feat_img): ?>
                    <img src="<?php echo $feat_img; ?>" class="w-full h-96 object-cover" alt="<?php the_title(); ?>">
                 <?php endif; ?>
                 
                 <div class="p-8">
                    <h1 class="text-3xl font-bold mb-4 text-slate-900"><?php the_title(); ?></h1>
                    <div class="flex items-center text-slate-500 text-sm mb-6">
                        <span><?php echo get_the_date(); ?></span>
                        <span class="mx-2">•</span>
                        <span><?php the_author(); ?></span>
                    </div>
                    <div class="prose max-w-none text-slate-700 leading-relaxed">
                        <?php the_content(); ?>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <a href="<?php echo site_url(); ?>" class="text-brand-600 font-medium hover:underline">&larr; Kembali ke Beranda</a>
                    </div>
                 </div>
            </article>
        <?php endwhile; endif; ?>
    </div>

<?php else: ?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-brand-900 to-brand-800 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cube-coat.png')]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-500/20 border border-brand-400/30 text-brand-300 text-sm font-semibold mb-6 backdrop-blur-sm">
                    Komunitas Profesional Terdepan
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                    Tingkatkan Karir & <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-white">Koneksi Anda</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Bergabunglah dengan ribuan profesional lainnya. Dapatkan akses eksklusif ke wawasan industri, event networking, dan layanan konsultasi prioritas.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="register-member.php" class="inline-flex justify-center items-center px-8 py-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-lg transition-all shadow-lg shadow-brand-600/30 hover:-translate-y-1">
                        Daftar Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="#benefits" class="inline-flex justify-center items-center px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold backdrop-blur-sm border border-white/10 transition-all">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-10 pt-8 border-t border-white/10 flex flex-col sm:flex-row gap-8 justify-center lg:justify-start text-slate-400 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        <span>500+ Member Aktif</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>Terverifikasi Resmi</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="absolute -inset-4 bg-brand-500/30 rounded-full blur-3xl animate-pulse"></div>
                <img src="https://illustrations.popsy.co/amber/success.svg" alt="Community Success" class="relative w-full drop-shadow-2xl transform hover:scale-105 transition duration-500">
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-brand-600 font-bold tracking-wide uppercase text-sm mb-3">Mengapa Bergabung?</h2>
            <h3 class="text-3xl font-bold text-slate-900 mb-4">Benefit Eksklusif Member</h3>
            <p class="text-slate-600 text-lg">Kami menyediakan platform terbaik untuk pertumbuhan profesional Anda dengan berbagai fasilitas unggulan.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Benefit 1 -->
            <div class="bg-slate-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Akses Materi Premium</h4>
                <p class="text-slate-600 leading-relaxed">Dapatkan akses tak terbatas ke ribuan artikel, jurnal, dan materi pembelajaran eksklusif yang dikurasi oleh ahli.</p>
            </div>
            
            <!-- Benefit 2 -->
            <div class="bg-slate-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Jaringan Luas</h4>
                <p class="text-slate-600 leading-relaxed">Terhubung dengan sesama profesional, mentor, dan pemimpin industri melalui forum diskusi dan event networking.</p>
            </div>
            
            <!-- Benefit 3 -->
            <div class="bg-slate-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Sertifikasi Resmi</h4>
                <p class="text-slate-600 leading-relaxed">Tingkatkan kredibilitas profil profesional Anda dengan sertifikat resmi yang diakui di berbagai industri.</p>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section id="news" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Berita & Artikel Terbaru</h2>
                <p class="text-slate-600">Update terkini seputar kegiatan dan informasi membership.</p>
            </div>
            <a href="#" class="text-brand-600 font-semibold hover:text-brand-700 flex items-center gap-1 group">
                Lihat Semua Berita 
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $args = array(
                'posts_per_page' => 3,
                'category_name' => 'membership'
            );
            $recent_posts = get_posts($args);
            if (empty($recent_posts)) {
                $recent_posts = get_posts(array('posts_per_page' => 3));
            }

            if ($recent_posts):
                foreach ($recent_posts as $post):
                    setup_postdata($post);
            ?>
            <article class="bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden group h-full flex flex-col">
                <div class="relative overflow-hidden h-56">
                    <?php 
                    $thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url($post->ID, 'medium') : get_first_image_from_content($post->post_content);
                    if ($thumbnail): ?>
                        <img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        <?php echo get_the_date('d M Y'); ?>
                    </div>
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 leading-snug">
                        <a href="<?php the_permalink(); ?>" class="hover:text-brand-600 transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <p class="text-slate-600 text-sm mb-6 flex-1 line-clamp-3 leading-relaxed">
                        <?php echo wp_trim_words(strip_tags($post->post_content), 20); ?>
                    </p>
                    <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">Oleh <?php the_author(); ?></span>
                        <a href="<?php the_permalink(); ?>" class="text-brand-600 text-sm font-bold hover:underline flex items-center gap-1">
                            Baca <span class="hidden sm:inline">Selengkapnya</span> &rarr;
                        </a>
                    </div>
                </div>
            </article>
            <?php 
                endforeach; 
                wp_reset_postdata();
            else:
            ?>
                <div class="col-span-3 text-center py-16 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <h3 class="text-lg font-medium text-slate-900">Belum ada berita</h3>
                    <p class="text-slate-500">Silakan kembali lagi nanti untuk update terbaru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Consultation Form Section -->
<section id="consultation" class="py-20 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full bg-slate-50 skew-y-3 transform origin-top-left -z-10"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 border border-slate-100">
            <div class="text-center mb-10">
                <span class="text-brand-600 font-bold tracking-wide uppercase text-sm">Butuh Bantuan?</span>
                <h2 class="text-3xl font-bold text-slate-900 mt-2 mb-4">Konsultasi Membership</h2>
                <p class="text-slate-600 max-w-lg mx-auto">Punya pertanyaan tentang paket membership atau butuh bantuan teknis? Tim kami siap membantu Anda.</p>
            </div>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consultation_submit'])) {
                echo '<div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl relative mb-8 flex items-center shadow-sm">
                    <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold block">Terima kasih!</span>
                        <span class="text-sm">Pertanyaan Anda telah terkirim. Kami akan menghubungi Anda segera.</span>
                    </div>
                </div>';
            }
            ?>

            <form method="POST" action="index.php#consultation" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="c_name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="c_name" id="c_name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 transition-colors py-3 px-4 bg-slate-50 focus:bg-white" placeholder="Masukkan nama Anda">
                </div>
                <div>
                    <label for="c_email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="c_email" id="c_email" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 transition-colors py-3 px-4 bg-slate-50 focus:bg-white" placeholder="nama@email.com">
                </div>
                <div>
                    <label for="c_phone" class="block text-sm font-medium text-slate-700 mb-2">Nomor Telepon (Opsional)</label>
                    <input type="text" name="c_phone" id="c_phone" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 transition-colors py-3 px-4 bg-slate-50 focus:bg-white" placeholder="0812...">
                </div>
                <div class="md:col-span-2">
                    <label for="c_message" class="block text-sm font-medium text-slate-700 mb-2">Pesan / Pertanyaan</label>
                    <textarea name="c_message" id="c_message" rows="4" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 transition-colors py-3 px-4 bg-slate-50 focus:bg-white" placeholder="Tuliskan pertanyaan Anda di sini..."></textarea>
                </div>
                <div class="md:col-span-2 pt-4">
                    <button type="submit" name="consultation_submit" class="w-full bg-brand-600 text-white py-4 px-6 rounded-xl hover:bg-brand-700 transition-all font-bold text-lg shadow-lg hover:shadow-brand-600/30 flex justify-center items-center gap-2 group">
                        <span>Kirim Pesan</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>