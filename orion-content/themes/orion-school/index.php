<?php get_header(); ?>

<?php 
$page = isset($_GET['page']) ? $_GET['page'] : '';
$post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;

if ($post_id > 0) {
    $post = get_post($post_id);
    if ($post):
?>
    <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
        <div class="hero-pattern absolute inset-0 opacity-20"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl">
                <span class="inline-block bg-primary-500/30 text-primary-100 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-widest border border-primary-400/30">Berita & Artikel</span>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight font-serif"><?php echo htmlspecialchars($post->post_title); ?></h1>
                <div class="flex items-center gap-6 mt-6 text-sm text-primary-200">
                    <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <?php echo date('d F Y', strtotime($post->post_date)); ?></span>
                    <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Admin Orion</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <?php $thumb = get_the_post_thumbnail_url($post->ID, 'large'); if ($thumb): ?>
                <img src="<?php echo $thumb; ?>" class="w-full h-[500px] object-cover rounded-3xl shadow-2xl mb-12 border border-slate-100">
            <?php endif; ?>
            <div class="prose prose-lg prose-primary max-w-none text-slate-700 leading-relaxed font-sans">
                <?php 
                $content = $post->post_content;
                for($i=0;$i<3;$i++) $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
                echo apply_filters('the_content', $content); 
                ?>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-100">
                <a href="index.php?page=berita" class="text-primary-600 font-bold flex items-center gap-2 hover:text-primary-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </div>
<?php
    endif;
} elseif (!empty($page)) {
    switch ($page) {
        case 'sejarah':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Sejarah Sekolah</h1>
                    <p class="text-primary-100 max-w-2xl mx-auto text-lg">Perjalanan panjang kami dalam mencerdaskan kehidupan bangsa.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-lg border border-slate-100 prose prose-lg prose-slate text-slate-600">
                    <p class="lead text-xl text-slate-800 font-medium border-l-4 border-primary-500 pl-4 mb-8">Orion School didirikan pada tahun 1990 dengan semangat untuk memberikan pendidikan berkualitas bagi masyarakat sekitar.</p>
                    <p>Berawal dari sebuah gedung sederhana dengan 3 ruang kelas, kini kami telah berkembang menjadi institusi pendidikan modern yang dilengkapi dengan berbagai fasilitas penunjang pembelajaran.</p>
                    <div class="my-8 grid grid-cols-2 gap-4 not-prose">
                        <div class="bg-slate-50 p-6 rounded-xl text-center border border-slate-200">
                            <div class="text-3xl font-bold text-primary-600 mb-2">1990</div>
                            <div class="text-sm text-slate-500">Tahun Berdiri</div>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-xl text-center border border-slate-200">
                            <div class="text-3xl font-bold text-primary-600 mb-2">A</div>
                            <div class="text-sm text-slate-500">Akreditasi</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'visi-misi':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                 <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                 <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Visi & Misi</h1>
                    <p class="text-primary-100 text-lg">Arah dan tujuan kami melangkah untuk masa depan.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-primary-500">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">Visi</h2>
                        <p class="text-slate-600 italic text-lg bg-slate-50 p-6 rounded-xl border border-slate-100">"Menjadi sekolah unggulan yang melahirkan generasi cerdas, berkarakter mulia, dan berwawasan global."</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary-500">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Misi</h2>
                        <ul class="space-y-4 text-slate-600">
                            <li class="flex items-start bg-slate-50 p-3 rounded-lg border border-slate-100">Menyelenggarakan pendidikan yang integratif dan holistik.</li>
                            <li class="flex items-start bg-slate-50 p-3 rounded-lg border border-slate-100">Mengembangkan potensi siswa secara optimal sesuai bakat.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'sambutan':
            $p_name = get_option('orion_school_principal_name', 'Dr. Budi Santoso, M.Pd.');
            $p_text = get_option('orion_school_principal_text', "Selamat datang di website resmi Orion School. Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa atas segala limpahan rahmat-Nya.\n\nWebsite ini hadir sebagai media informasi dan komunikasi antara sekolah, orang tua, siswa, dan masyarakat luas.");
            $p_img = get_option('orion_school_principal_image', '');
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Sambutan Kepala Sekolah</h1>
                    <p class="text-primary-100 text-lg">Visi dan harapan untuk masa depan pendidikan.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="flex flex-col md:flex-row gap-12 items-start max-w-6xl mx-auto">
                    <div class="md:w-1/3">
                        <div class="relative group">
                            <div class="absolute -inset-4 bg-primary-500/20 rounded-3xl blur-2xl group-hover:bg-primary-500/30 transition duration-500"></div>
                            <div class="bg-white p-3 rounded-3xl shadow-2xl border border-slate-100 relative z-10">
                                <?php if($p_img): ?>
                                    <img src="<?php echo $p_img; ?>" class="w-full h-auto rounded-2xl shadow-inner object-cover aspect-[3/4]" alt="Principal">
                                <?php else: ?>
                                    <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-[3/4] flex items-center justify-center relative group">
                                        <span class="text-8xl transform group-hover:scale-110 transition duration-500">👨‍🏫</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center mt-8">
                            <h3 class="font-bold text-2xl text-slate-900"><?php echo $p_name; ?></h3>
                            <p class="text-primary-600 font-medium bg-primary-50 inline-block px-4 py-1 rounded-full mt-2 text-sm">Kepala Sekolah</p>
                        </div>
                    </div>
                    <div class="md:w-2/3 bg-white p-8 md:p-10 rounded-2xl shadow-lg border border-slate-100 relative">
                        <div class="absolute -left-4 top-10 w-8 h-8 bg-white transform rotate-45 border-l border-b border-slate-100 hidden md:block"></div>
                        <div class="prose prose-lg prose-slate text-slate-600 max-w-none">
                            <h3 class="text-slate-800 font-bold mb-4">Assalamu'alaikum Warahmatullahi Wabarakatuh,</h3>
                            <p><?php echo nl2br(htmlspecialchars($p_text)); ?></p>
                            <p class="font-bold text-slate-800 mt-8 text-right">Kepala Sekolah Orion School</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'guru':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Guru & Staf</h1>
                    <p class="text-primary-100 text-lg">Pendidik profesional yang berdedikasi tinggi.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php 
                    for($i=1; $i<=4; $i++): 
                        $g_name = get_option("orion_school_guru{$i}_name");
                        $g_role = get_option("orion_school_guru{$i}_role");
                        $g_img = get_option("orion_school_guru{$i}_image");
                        if(!$g_name) continue;
                    ?>
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 group">
                        <div class="h-64 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                            <?php if($g_img): ?>
                                <img src="<?php echo $g_img; ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                            <?php else: ?>
                                <span class="text-6xl transform group-hover:scale-110 transition duration-500">👨‍🏫</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-primary-600 transition"><?php echo $g_name; ?></h3>
                            <p class="text-primary-600 text-sm font-medium mb-1"><?php echo $g_role; ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <?php
            break;

        case 'berita':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Berita Sekolah</h1>
                    <p class="text-primary-100 text-lg">Informasi terbaru seputar kegiatan dan prestasi Orion School.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php 
                    $news = get_posts(array('numberposts' => 9, 'post_type' => 'post'));
                    if($news): foreach($news as $n):
                        $n_thumb = get_the_post_thumbnail_url($n->ID, 'medium');
                    ?>
                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100 hover:shadow-xl transition flex flex-col h-full group">
                        <div class="h-56 relative overflow-hidden">
                            <?php if($n_thumb): ?>
                                <img src="<?php echo $n_thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="text-[10px] font-bold text-primary-600 uppercase tracking-widest mb-2"><?php echo date('d M Y', strtotime($n->post_date)); ?></span>
                            <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-primary-600 transition"><a href="?p=<?php echo $n->ID; ?>"><?php echo htmlspecialchars($n->post_title); ?></a></h3>
                            <p class="text-slate-500 text-sm line-clamp-3 mb-6"><?php echo wp_trim_words(strip_tags(html_entity_decode(html_entity_decode($n->post_content))), 20); ?></p>
                            <a href="?p=<?php echo $n->ID; ?>" class="mt-auto text-primary-600 font-bold text-sm flex items-center gap-2">Baca Selengkapnya &rarr;</a>
                        </div>
                    </article>
                    <?php endforeach; else: ?>
                        <p class="col-span-full text-center py-20 text-slate-400 italic">Belum ada berita yang diposting.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            break;

        case 'ppdb':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">PPDB Online</h1>
                    <p class="text-primary-100 text-lg">Penerimaan Peserta Didik Baru Tahun Ajaran 2026/2027.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-slate-100 text-center">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Pendaftaran Telah Dibuka!</h2>
                    <p class="text-slate-600 mb-10 text-lg">Mari menjadi bagian dari komunitas pembelajar di Orion School. Kami berkomitmen untuk membimbing putra-putri anda mencapai potensi terbaiknya.</p>
                    <a href="#" class="inline-block px-10 py-4 bg-primary-600 text-white font-bold rounded-full shadow-lg hover:bg-primary-700 transition transform hover:-translate-y-1">Daftar Sekarang</a>
                </div>
            </div>
            <?php
            break;
    }
} else {
    // Standard Homepage (Hero + Brief principal + Latest news)
    $p_name = get_option('orion_school_principal_name', 'Dr. Budi Santoso, M.Pd.');
    $p_img = get_option('orion_school_principal_image', '');
    ?>
    <!-- Homepage Hero -->
    <?php 
    $hero_banner = get_option('orion_school_hero_banner'); 
    $hero_style = $hero_banner ? "background-image: url('{$hero_banner}'); background-size: cover; background-position: center;" : "";
    ?>
    <section class="<?php echo $hero_banner ? '' : 'hero-pattern'; ?> min-h-[600px] flex items-center text-white relative overflow-hidden" style="<?php echo $hero_style; ?>">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900 via-primary-900/80 to-transparent z-10"></div>
        <div class="container mx-auto px-4 relative z-20 grid md:grid-cols-2 gap-12 items-center py-20">
            <div class="space-y-8 animate-fade-in-up">
                <span class="inline-block bg-secondary-500 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest">Pendaftaran 2026/2027 Dibuka</span>
                <h1 class="text-5xl md:text-7xl font-bold leading-tight font-serif italic">Membentuk <br><span class="text-primary-400 not-italic">Generasi Unggul</span></h1>
                <p class="text-xl text-primary-100 max-w-lg leading-relaxed">Pendidikan holistik yang menggabungkan keunggulan akademik dengan penguatan karakter mulia.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="?page=ppdb" class="px-8 py-4 bg-white text-primary-900 font-bold rounded-full shadow-xl hover:bg-primary-50 transition">Daftar PPDB</a>
                    <a href="?page=visi-misi" class="px-8 py-4 border-2 border-white/30 text-white font-bold rounded-full hover:bg-white/10 transition">Tentang Kami</a>
                </div>
            </div>
            <?php if (!$hero_banner): ?>
            <div class="hidden md:block">
                <div class="w-full h-auto bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/10 shadow-2xl">
                    <img src="https://illustrations.popsy.co/blue/school-admission.svg" class="w-full h-auto drop-shadow-2xl floating-animation" alt="School Illustration">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Principal Brief -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-12 items-center max-w-5xl mx-auto">
                <div class="md:w-1/3">
                    <?php if($p_img): ?>
                        <img src="<?php echo $p_img; ?>" class="rounded-3xl shadow-2xl border-8 border-slate-50 w-full aspect-[3/4] object-cover" alt="Principal">
                    <?php else: ?>
                        <div class="bg-slate-100 rounded-3xl w-full aspect-[3/4] flex items-center justify-center text-slate-300">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="md:w-2/3 space-y-6 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 font-serif">Sambutan Kepala Sekolah</h2>
                    <p class="text-lg text-slate-600 italic">"Kami percaya setiap anak adalah permata unik yang menunggu untuk dipoles hingga bersinar. Melalui Orion School, kami berkomitmen menemani perjalanan tumbuh kembang mereka."</p>
                    <div class="pt-4">
                        <p class="font-bold text-slate-900 text-xl"><?php echo $p_name; ?></p>
                        <p class="text-primary-600 font-medium">Kepala Orion School</p>
                    </div>
                    <a href="?page=sambutan" class="inline-flex items-center gap-2 text-primary-600 font-bold border-b-2 border-primary-600 pb-1 hover:text-primary-700 hover:border-primary-700 transition mt-4">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Grid -->
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 font-serif">Berita & Kegiatan</h2>
            <div class="w-20 h-1.5 bg-primary-500 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <?php 
            $news = get_posts(array('numberposts' => 3, 'post_type' => 'post'));
            if($news): foreach($news as $n):
                $n_thumb = get_the_post_thumbnail_url($n->ID, 'medium');
            ?>
            <article class="bg-white rounded-2xl shadow-sm overflow-hidden group border border-slate-100">
                <div class="h-56 overflow-hidden">
                    <?php if($n_thumb): ?>
                        <img src="<?php echo $n_thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-primary-600 transition truncate"><a href="?p=<?php echo $n->ID; ?>"><?php echo htmlspecialchars($n->post_title); ?></a></h3>
                    <p class="text-slate-500 text-sm mb-4 line-clamp-2"><?php echo wp_trim_words(strip_tags(html_entity_decode(html_entity_decode($n->post_content))), 15); ?></p>
                    <a href="?p=<?php echo $n->ID; ?>" class="text-primary-600 font-bold text-xs uppercase tracking-widest">Detail &rarr;</a>
                </div>
            </article>
            <?php endforeach; endif; ?>
        </div>
        <div class="text-center">
            <a href="?page=berita" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-full hover:bg-primary-700 transition shadow-lg">Lihat Semua Berita</a>
        </div>
    </section>
<?php } ?>

<?php get_footer(); ?>
