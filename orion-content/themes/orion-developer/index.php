<?php get_header(); ?>

<!-- Main Content Area -->
<div class="space-y-24 pb-20">

    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-center overflow-hidden">
        <!-- Abstract Decorations -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 w-80 h-80 bg-pink-500/10 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-8">
                    <div class="inline-flex items-center space-x-2 bg-indigo-50 border border-indigo-100 rounded-full px-4 py-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Available for New Projects</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1]">
                        Website <span class="gradient-text">Murah, Express</span> & Mudah Untuk Semua Keperluan.
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl">
                        Kami adalah partner digital anda untuk menghadirkan kehadiran online yang profesional dalam hitungan hari. Tidak perlu pusing teknis, biarkan kami yang mengurus semuanya.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#pricing" class="px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            Lihat Paket Harga
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="<?php echo orion_dev_wa_link(); ?>" class="px-8 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-bold text-lg hover:border-indigo-400 transition flex items-center justify-center gap-2">
                            Konsultasi Gratis
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-6 pt-8 border-t border-slate-100">
                        <div class="flex -space-x-3">
                            <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-full border-2 border-white">
                            <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-full border-2 border-white">
                            <img src="https://i.pravatar.cc/100?u=3" class="w-10 h-10 rounded-full border-2 border-white">
                        </div>
                        <p class="text-sm text-slate-400">
                            <span class="font-bold text-slate-900">500+</span> Klien Puas Telah Go-Digital
                        </p>
                    </div>
                </div>
                
                <div class="lg:col-span-5 relative">
                    <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl bg-white p-2 transform rotate-3 hover:rotate-0 transition duration-500">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Work Showcase" class="rounded-2xl w-full h-auto">
                    </div>
                    <div class="absolute -bottom-10 -left-10 z-20 bg-indigo-600 text-white p-6 rounded-2xl shadow-2xl flex items-center gap-4">
                        <div class="text-4xl font-bold">48</div>
                        <div class="text-xs uppercase font-bold tracking-widest leading-none">Jam <br> Rata-rata <br> Pengerjaan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="container mx-auto px-6">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Apa Saja Yang Bisa Kami Buat?</h2>
            <p class="text-slate-500">Solusi digital lengkap untuk menaikkan level bisnis anda.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition group">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8 flex-shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Toko Online</h3>
                <p class="text-sm text-slate-500">Mulai jualan produk anda secara online dengan sistem pembayaran otomatis.</p>
            </div>
            
            <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition group">
                <div class="w-14 h-14 bg-pink-50 rounded-2xl flex items-center justify-center text-pink-600 mb-6 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8 flex-shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Profil Perusahaan</h3>
                <p class="text-sm text-slate-500">Tampilkan profesionalisme bisnis anda kepada mitra dan calon klien.</p>
            </div>
            
            <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition group">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8 flex-shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Landing Page</h3>
                <p class="text-sm text-slate-500">Fokus pada konversi iklan dengan landing page yang cepat dan persuasif.</p>
            </div>
            
            <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition group">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8 flex-shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Web Sekolah/Desa</h3>
                <p class="text-sm text-slate-500">Informasi publik yang mudah diakses dan dikelola untuk instansi anda.</p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="work" class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="space-y-4">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Portfolio <span class="gradient-text">Terbaik</span> Kami</h2>
                <p class="text-slate-500 max-w-xl">Intip beberapa proyek website yang telah kami kerjakan dengan dedikasi tinggi untuk klien-klien kami.</p>
            </div>
            <a href="<?php echo orion_dev_wa_link('Lihat Portfolio Lengkap'); ?>" class="inline-flex items-center gap-2 text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1 hover:text-indigo-400 hover:border-indigo-400 transition">
                Lihat Semua Proyek
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $defaults = array(
                1 => array('img' => 'https://images.unsplash.com/photo-1522542550221-31fd19255a7a?auto=format&fit=crop&w=800&q=80', 'cat' => 'E-Commerce', 'title' => 'Lumina Fashion Store', 'color' => 'indigo'),
                2 => array('img' => 'https://images.unsplash.com/photo-1454165833767-027ffea9e77b?auto=format&fit=crop&w=800&q=80', 'cat' => 'Company Profile', 'title' => 'NexGen Tech Solutions', 'color' => 'pink'),
                3 => array('img' => 'https://images.unsplash.com/photo-1551288049-bbbda5366392?auto=format&fit=crop&w=800&q=80', 'cat' => 'SaaS / Web App', 'title' => 'DataFlow Analytics', 'color' => 'amber'),
                4 => array('img' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80', 'cat' => 'Portfolio', 'title' => 'Personal Brand: Elena', 'color' => 'emerald'),
                5 => array('img' => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=800&q=80', 'cat' => 'Real Estate', 'title' => 'Orion Residence', 'color' => 'indigo'),
                6 => array('img' => 'https://images.unsplash.com/photo-1557821552-17105176677c?auto=format&fit=crop&w=800&q=80', 'cat' => 'Travel & News', 'title' => 'Wanderlust Magazine', 'color' => 'pink'),
            );

            for ($i = 1; $i <= 6; $i++):
                $custom_img = get_option("orion_dev_gallery_img{$i}");
                $custom_desc = get_option("orion_dev_gallery_desc{$i}");
                
                $img_url = $custom_img ? $custom_img : $defaults[$i]['img'];
                
                // If custom description exists, we split it by "-" to get cat and title, 
                // or just use it as title if no separator.
                if ($custom_desc) {
                    $parts = explode('-', $custom_desc, 2);
                    if (count($parts) > 1) {
                        $cat = trim($parts[0]);
                        $title = trim($parts[1]);
                    } else {
                        $cat = 'Project';
                        $title = $custom_desc;
                    }
                } else {
                    $cat = $defaults[$i]['cat'];
                    $title = $defaults[$i]['title'];
                }

                $color = $defaults[$i]['color'];
                $text_color_class = "text-{$color}-400";
            ?>
                <!-- Project <?php echo $i; ?> -->
                <div class="group relative rounded-[2rem] overflow-hidden shadow-lg aspect-[4/3] bg-slate-200">
                    <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 p-8 flex flex-col justify-end">
                        <span class="<?php echo $text_color_class; ?> text-xs font-bold uppercase tracking-widest mb-2"><?php echo htmlspecialchars($cat); ?></span>
                        <h3 class="text-white text-xl font-bold"><?php echo htmlspecialchars($title); ?></h3>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="bg-indigo-950 py-24 relative overflow-hidden rounded-[3rem]">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-pink-500/10 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white">Paket Harga <span class="text-indigo-400">Transparan</span></h2>
                <p class="text-indigo-200">Sesuai budget, tanpa biaya tersembunyi.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Package 1 -->
                <?php $p1_name = get_option('orion_dev_pkg1_name'); ?>
                <div class="card-pricing bg-white rounded-[2rem] p-10 flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo $p1_name; ?></h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-indigo-600"><?php echo get_option('orion_dev_pkg1_price'); ?></span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <?php foreach(orion_dev_get_features('orion_dev_pkg1_features') as $f): ?>
                        <li class="flex items-center gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <?php echo $f; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo orion_dev_wa_link($p1_name); ?>" class="w-full py-4 rounded-xl bg-slate-900 text-white font-bold text-center hover:bg-slate-800 transition">Pilih Paket</a>
                </div>

                <!-- Package 2 (Highlighted) -->
                <?php $p2_name = get_option('orion_dev_pkg2_name'); ?>
                <div class="card-pricing bg-indigo-600 rounded-[2rem] p-10 flex flex-col shadow-2xl shadow-indigo-500/50 transform scale-105 border-4 border-indigo-400 relative">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-amber-500 text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg">Paling Populer</div>
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-white mb-2"><?php echo $p2_name; ?></h3>
                        <div class="flex items-baseline gap-1 text-white">
                            <span class="text-4xl font-extrabold"><?php echo get_option('orion_dev_pkg2_price'); ?></span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <?php foreach(orion_dev_get_features('orion_dev_pkg2_features') as $f): ?>
                        <li class="flex items-center gap-3 text-indigo-50">
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <?php echo $f; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo orion_dev_wa_link($p2_name); ?>" class="w-full py-4 rounded-xl bg-white text-indigo-600 font-bold text-center hover:bg-indigo-50 transition">Ambil Promo</a>
                </div>

                <!-- Package 3 -->
                <?php $p3_name = get_option('orion_dev_pkg3_name'); ?>
                <div class="card-pricing bg-white rounded-[2rem] p-10 flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo $p3_name; ?></h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-indigo-600"><?php echo get_option('orion_dev_pkg3_price'); ?></span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <?php foreach(orion_dev_get_features('orion_dev_pkg3_features') as $f): ?>
                        <li class="flex items-center gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <?php echo $f; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo orion_dev_wa_link($p3_name); ?>" class="w-full py-4 rounded-xl bg-slate-900 text-white font-bold text-center hover:bg-slate-800 transition">Minta Penawaran</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="container mx-auto px-6">
        <div class="bg-indigo-50 rounded-[3rem] p-12 md:p-20 relative overflow-hidden">
             <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                 <div>
                     <h2 class="text-4xl font-extrabold text-slate-900 mb-8 leading-tight">Apa Kata Mereka Tentang Layanan Kami?</h2>
                     <div class="space-y-8">
                         <div class="flex gap-4">
                             <div class="flex-shrink-0 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                                 <svg class="w-6 h-6 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.827a1 1 0 00-.788 0l-7 3a1 1 0 000 1.846l7 3a1 1 0 00.788 0l7-3a1 1 0 000-1.846l-7-3zM3.11 12.48l6.503 2.787a1 1 0 00.774 0l6.503-2.787c.507-.217.97-.504 1.363-.855l1.615 1.615A1 1 0 0118.12 15H1.88a1 1 0 01-.707-1.707l1.615-1.615c.392.35.856.638 1.322.855z"></path></svg>
                             </div>
                             <div>
                                 <p class="text-slate-700 font-medium italic">"Web starter-nya beneran cepat jadi. Cuma 2 hari sudah bisa jualan online. Mantap Orion Developer!"</p>
                                 <p class="text-sm text-slate-400 mt-2">— Budi, Pemilik Toko Sepatu</p>
                             </div>
                         </div>
                         <div class="flex gap-4">
                             <div class="flex-shrink-0 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                                 <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.827a1 1 0 00-.788 0l-7 3a1 1 0 000 1.846l7 3a1 1 0 00.788 0l7-3a1 1 0 000-1.846l-7-3zM3.11 12.48l6.503 2.787a1 1 0 00.774 0l6.503-2.787c.507-.217.97-.504 1.363-.855l1.615 1.615A1 1 0 0118.12 15H1.88a1 1 0 01-.707-1.707l1.615-1.615c.392.35.856.638 1.322.855z"></path></svg>
                             </div>
                             <div>
                                 <p class="text-slate-700 font-medium italic">"Hasil desainnya sangat modern, tidak kaku. Pas buat target market agency saya."</p>
                                 <p class="text-sm text-slate-400 mt-2">— Sarah, Creative Agency</p>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="bg-indigo-600 rounded-2xl p-8 text-white">
                     <h3 class="text-2xl font-bold mb-4">Punya Pertanyaan Khusus?</h3>
                     <p class="text-indigo-100 mb-8">Tim kami siap membantu menjawab pertanyaan anda seputar integrasi sistem, hosting, atau penawaran harga khusus untuk proyek jangka panjang.</p>
                     <a href="<?php echo orion_dev_wa_link('Konsultasi Khusus'); ?>" class="inline-flex items-center gap-2 text-white font-bold border-b-2 border-white pb-1 hover:text-indigo-200 hover:border-indigo-200 transition">
                         Hubungi Tim Teknis
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                     </a>
                 </div>
             </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
