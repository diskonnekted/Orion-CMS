<!-- Hero Section -->
<section class="pt-32 pb-20 bg-gradient-to-br from-brand-50 via-white to-blue-50 overflow-hidden relative">
    <!-- Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-brand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/hero-bg.svg" alt="Pattern" class="absolute inset-0 w-full h-full object-cover opacity-40">
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Hero Content -->
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white border border-brand-100 text-brand-600 text-sm font-semibold mb-6 shadow-sm animate-fade-in-up">
                    <span class="flex h-2 w-2 relative mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    Orion CMS v1.0 Resmi Dirilis
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-bold text-slate-900 mb-6 leading-tight tracking-tight">
                    Bangun Web <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-purple-600">Tanpa Batas</span>
                </h1>
                
                <p class="text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Platform manajemen konten modern yang menggabungkan performa tinggi, keamanan tingkat lanjut, dan kemudahan penggunaan untuk developer maupun pemula.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="<?php echo site_url('/download/'); ?>" class="px-8 py-4 bg-brand-600 text-white rounded-full font-bold text-lg hover:bg-brand-700 transition shadow-xl shadow-brand-500/20 transform hover:-translate-y-1 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Gratis
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-full font-bold text-lg hover:bg-slate-50 transition hover:border-slate-300 flex items-center justify-center">
                        Pelajari Fitur
                    </a>
                </div>
                
                <div class="mt-8 flex items-center justify-center lg:justify-start gap-4 text-sm text-slate-500 font-medium">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Open Source
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tanpa Biaya Bulanan
                    </div>
                </div>
            </div>
            
            <!-- Hero Visual -->
            <div class="lg:w-1/2 relative perspective-1000">
                <div class="relative rounded-xl bg-white shadow-2xl border border-slate-200 overflow-hidden transform rotate-y-6 rotate-x-6 hover:rotate-0 transition-all duration-700 ease-out p-2">
                    <div class="absolute top-0 left-0 w-full h-8 bg-slate-100 border-b border-slate-200 flex items-center px-4 gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <div class="ml-4 flex-1 h-4 bg-white rounded-md border border-slate-200 text-[10px] flex items-center px-2 text-slate-400 font-mono">
                            https://your-orion-site.com/admin
                        </div>
                    </div>
                    <div class="mt-8 rounded-lg overflow-hidden border border-slate-100">
                         <!-- Fallback visual using available asset -->
                        <img src="<?php echo site_url('/assets/img/CMS-ORION-ONE.png'); ?>" alt="Orion Dashboard" class="w-full h-auto object-cover">
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute -bottom-10 -left-10 bg-white p-4 rounded-xl shadow-xl border border-slate-100 animate-float">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">System Updated</div>
                            <div class="text-xs text-slate-500">Just now</div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -top-10 -right-5 bg-white p-4 rounded-xl shadow-xl border border-slate-100 animate-float animation-delay-1000">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">New Plugin Added</div>
                            <div class="text-xs text-slate-500">2 mins ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-10 bg-slate-900 text-white border-y border-slate-800">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-800">
            <div>
                <div class="text-4xl font-bold text-brand-400 mb-1">10k+</div>
                <div class="text-slate-400 text-sm font-medium uppercase tracking-wider">Downloads</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-brand-400 mb-1">50+</div>
                <div class="text-slate-400 text-sm font-medium uppercase tracking-wider">Themes</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-brand-400 mb-1">100+</div>
                <div class="text-slate-400 text-sm font-medium uppercase tracking-wider">Plugins</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-brand-400 mb-1">99%</div>
                <div class="text-slate-400 text-sm font-medium uppercase tracking-wider">Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Features -->
<section id="features" class="py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-6">
        <!-- Feature 1 -->
        <div class="flex flex-col md:flex-row items-center gap-16 mb-24">
            <div class="md:w-1/2 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-brand-100 to-purple-100 rounded-full filter blur-3xl opacity-50 transform -translate-x-10"></div>
                <img src="<?php echo site_url('/assets/img/magazine.PNG'); ?>" alt="Theme Showcase" class="relative rounded-2xl shadow-2xl border border-slate-100 transform hover:scale-105 transition duration-500">
            </div>
            <div class="md:w-1/2">
                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center text-brand-600 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Desain Memukau, Tanpa Ribet</h2>
                <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                    Pilih dari puluhan tema profesional yang siap pakai. Kustomisasi tampilan website Anda dengan mudah tanpa harus menyentuh kode sedikitpun. Responsif di semua perangkat.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Drag & Drop Layout Builder
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Mobile Friendly & SEO Optimized
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited Color Customization
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Feature 2 -->
        <div class="flex flex-col md:flex-row-reverse items-center gap-16">
            <div class="md:w-1/2 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-green-100 rounded-full filter blur-3xl opacity-50 transform translate-x-10"></div>
                <img src="<?php echo site_url('/assets/img/smartvillage.PNG'); ?>" alt="Plugin System" class="relative rounded-2xl shadow-2xl border border-slate-100 transform hover:scale-105 transition duration-500">
            </div>
            <div class="md:w-1/2">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Ekosistem Plugin yang Kuat</h2>
                <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                    Perluas fungsionalitas website Anda dengan ribuan plugin yang tersedia. Dari toko online, forum diskusi, hingga sistem booking, semuanya ada.
                </p>
                <div class="flex gap-4">
                    <div class="p-4 bg-slate-50 rounded-lg text-center w-32 border border-slate-100">
                        <div class="text-2xl font-bold text-brand-600 mb-1">500+</div>
                        <div class="text-xs text-slate-500 font-medium">Free Plugins</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-lg text-center w-32 border border-slate-100">
                        <div class="text-2xl font-bold text-purple-600 mb-1">200+</div>
                        <div class="text-xs text-slate-500 font-medium">Premium Addons</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Theme Showcase Carousel -->
<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Galeri Tema Pilihan</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Jelajahi berbagai kemungkinan desain yang bisa Anda terapkan.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Theme Card School -->
            <div class="group relative rounded-xl overflow-hidden shadow-lg cursor-pointer">
                <img src="<?php echo site_url('/assets/img/smartvillage.PNG'); ?>" alt="Orion School" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">Orion School</h3>
                        <p class="text-slate-300 text-sm">Academic & Education</p>
                    </div>
                </div>
            </div>
            <!-- Theme Card 1 -->
            <div class="group relative rounded-xl overflow-hidden shadow-lg cursor-pointer">
                <img src="<?php echo site_url('/assets/img/one.PNG'); ?>" alt="Theme One" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">Orion One</h3>
                        <p class="text-slate-300 text-sm">Multipurpose Theme</p>
                    </div>
                </div>
            </div>
             <!-- Theme Card 2 -->
             <div class="group relative rounded-xl overflow-hidden shadow-lg cursor-pointer">
                <img src="<?php echo site_url('/assets/img/portfolio.PNG'); ?>" alt="Theme Portfolio" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">Creative Portfolio</h3>
                        <p class="text-slate-300 text-sm">For Designers & Artists</p>
                    </div>
                </div>
            </div>
             <!-- Theme Card 3 -->
             <div class="group relative rounded-xl overflow-hidden shadow-lg cursor-pointer">
                <img src="<?php echo site_url('/assets/img/magazine.PNG'); ?>" alt="Theme Magazine" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">News Magazine</h3>
                        <p class="text-slate-300 text-sm">Content Heavy Sites</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo site_url('/?page=download'); ?>" class="inline-flex items-center font-bold text-brand-600 hover:text-brand-700 text-lg">
                Lihat Semua Tema
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>

<!-- Latest News -->
<section id="news" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Berita & Artikel</h2>
                <p class="text-slate-600">Tips, trik, dan update seputar pengembangan web.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php 
            $posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
            if ($posts):
                foreach ($posts as $post):
                    $thumb_url = get_the_post_thumbnail_url($post->ID);
            ?>
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col h-full group">
                <?php if ($thumb_url): ?>
                <div class="h-56 overflow-hidden relative">
                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-brand-600 uppercase tracking-wide">
                        News
                    </div>
                </div>
                <?php else: ?>
                <div class="h-56 bg-slate-100 flex items-center justify-center text-slate-400 relative">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                     <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-brand-600 uppercase tracking-wide">
                        Article
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="p-8 flex-grow flex flex-col">
                    <div class="text-sm text-slate-500 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo date('d M Y', strtotime($post->post_date)); ?>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-600 transition">
                        <a href="?p=<?php echo $post->ID; ?>">
                            <?php echo htmlspecialchars($post->post_title); ?>
                        </a>
                    </h3>
                    <p class="text-slate-600 mb-4 line-clamp-3 flex-grow leading-relaxed">
                        <?php echo substr(strip_tags($post->post_content), 0, 100) . '...'; ?>
                    </p>
                    <a href="?p=<?php echo $post->ID; ?>" class="text-brand-600 font-bold hover:text-brand-700 inline-flex items-center mt-auto uppercase text-sm tracking-wide">
                        Baca Selengkapnya
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </article>
            <?php endforeach; else: ?>
                <div class="col-span-3 text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-500 text-lg">Belum ada konten yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 bg-brand-600 relative overflow-hidden">
    <!-- Abstract Shapes -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-purple-500 opacity-20 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-6 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Mulai Website Impian Anda</h2>
        <p class="text-xl text-brand-100 mb-10 max-w-2xl mx-auto leading-relaxed">
            Download Orion CMS sekarang dan rasakan kemudahan membangun website profesional dalam hitungan menit.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo site_url('/download/'); ?>" class="px-10 py-5 bg-white text-brand-600 rounded-full font-bold text-xl hover:bg-brand-50 transition shadow-2xl shadow-black/20 transform hover:-translate-y-1">
                Download Gratis
            </a>
            <a href="#" class="px-10 py-5 bg-transparent border-2 border-white text-white rounded-full font-bold text-xl hover:bg-white/10 transition">
                Dokumentasi
            </a>
        </div>
        <p class="mt-8 text-sm text-brand-200 opacity-80">
            Versi 1.0.0 • Berlisensi MIT • Didukung Komunitas
        </p>
    </div>
</section>
