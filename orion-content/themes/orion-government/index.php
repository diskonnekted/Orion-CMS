<?php get_header(); ?>

<?php
$orion_gov_page = '';
if (isset($_GET['page'])) {
    $orion_gov_page = strtolower((string) $_GET['page']);
    $orion_gov_page = preg_replace('/[^a-z0-9_-]/', '', $orion_gov_page);
}

$orion_gov_force_archive = ($orion_gov_page === 'berita');

if ($orion_gov_page !== '' && !$orion_gov_force_archive) {
    $page_titles = array(
        'profil' => 'Profil',
        'layanan' => 'Layanan Publik',
        'transparansi' => 'Transparansi',
        'kontak' => 'Kontak',
        'pengaduan' => 'Lapor / Pengaduan',
    );
    $page_title = isset($page_titles[$orion_gov_page]) ? $page_titles[$orion_gov_page] : 'Halaman';
    ?>
    <div class="bg-slate-50 py-12 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Portal Pemerintah</div>
                <h1 class="mt-2 text-3xl md:text-4xl font-bold text-slate-900 font-serif"><?php echo htmlspecialchars($page_title); ?></h1>
                <div class="mt-3 text-sm text-slate-500">
                    <a class="hover:text-gov-700" href="<?php echo site_url(); ?>">Beranda</a>
                    <span class="mx-2">/</span>
                    <span class="text-slate-700 font-semibold"><?php echo htmlspecialchars($page_title); ?></span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 md:p-12 prose prose-slate max-w-none">
                    <?php if ($orion_gov_page === 'profil'): ?>
                        <h2>Tentang Instansi</h2>
                        <p>Halaman ini berisi profil singkat instansi, visi &amp; misi, serta struktur organisasi.</p>
                        <h3>Visi</h3>
                        <p>Mewujudkan layanan publik yang cepat, transparan, dan berorientasi pada warga.</p>
                        <h3>Misi</h3>
                        <ul>
                            <li>Meningkatkan kualitas pelayanan publik berbasis digital.</li>
                            <li>Memperkuat transparansi dan akuntabilitas.</li>
                            <li>Mendorong partisipasi masyarakat dalam pembangunan.</li>
                        </ul>
                    <?php elseif ($orion_gov_page === 'layanan'): ?>
                        <h2>Layanan Publik</h2>
                        <p>Berikut daftar layanan yang tersedia. Anda bisa menyesuaikan kontennya sesuai kebutuhan instansi.</p>
                        <ul>
                            <li>Administrasi Kependudukan</li>
                            <li>Perizinan Usaha</li>
                            <li>Kesehatan &amp; Sosial</li>
                            <li>Pengaduan Masyarakat</li>
                        </ul>
                    <?php elseif ($orion_gov_page === 'transparansi'): ?>
                        <h2>Transparansi</h2>
                        <p>Halaman ini dapat digunakan untuk publikasi laporan, anggaran, kegiatan, dan dokumen penting.</p>
                        <ul>
                            <li>APBD / Anggaran Tahunan</li>
                            <li>Laporan Kinerja</li>
                            <li>Rencana Program</li>
                            <li>Pengadaan &amp; Tender</li>
                        </ul>
                    <?php elseif ($orion_gov_page === 'kontak'): ?>
                        <?php
                        $contact_email = get_option('orion_gov_contact_email', 'info@example.com');
                        $contact_phone = get_option('orion_gov_contact_phone', '021-000000');
                        $contact_address = get_option('orion_gov_contact_address', 'Alamat kantor belum diatur.');
                        ?>
                        <h2>Kontak</h2>
                        <p>Hubungi kami melalui informasi berikut:</p>
                        <ul>
                            <li><strong>Email:</strong> <?php echo htmlspecialchars($contact_email); ?></li>
                            <li><strong>Telepon:</strong> <?php echo htmlspecialchars($contact_phone); ?></li>
                            <li><strong>Alamat:</strong> <?php echo htmlspecialchars($contact_address); ?></li>
                        </ul>
                        <h3>Form Pesan</h3>
                        <div class="not-prose">
                            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input class="w-full p-3 rounded-xl border border-slate-200" placeholder="Nama">
                                <input class="w-full p-3 rounded-xl border border-slate-200" placeholder="Email">
                                <input class="w-full p-3 rounded-xl border border-slate-200 md:col-span-2" placeholder="Subjek">
                                <textarea class="w-full p-3 rounded-xl border border-slate-200 md:col-span-2" rows="5" placeholder="Pesan"></textarea>
                                <button type="button" class="md:col-span-2 inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gov-700 hover:bg-gov-800 text-white font-bold transition">Kirim</button>
                            </form>
                        </div>
                    <?php elseif ($orion_gov_page === 'pengaduan'): ?>
                        <h2>Lapor / Pengaduan</h2>
                        <p>Gunakan halaman ini untuk menerima masukan atau laporan dari masyarakat.</p>
                        <div class="not-prose mt-6">
                            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input class="w-full p-3 rounded-xl border border-slate-200" placeholder="Nama">
                                <input class="w-full p-3 rounded-xl border border-slate-200" placeholder="Nomor HP">
                                <input class="w-full p-3 rounded-xl border border-slate-200 md:col-span-2" placeholder="Lokasi / Alamat Kejadian">
                                <textarea class="w-full p-3 rounded-xl border border-slate-200 md:col-span-2" rows="6" placeholder="Isi laporan"></textarea>
                                <button type="button" class="md:col-span-2 inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gold-500 hover:bg-gold-400 text-slate-900 font-extrabold transition">Kirim Laporan</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <h2>Konten</h2>
                        <p>Halaman ini belum memiliki konten khusus.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    get_footer();
    return;
}

// HOME PAGE LAYOUT
if ( !is_single() && !$orion_gov_force_archive && !isset($_GET['page_id']) && !isset($_GET['p']) && !isset($_GET['cat']) && !isset($_GET['s']) ):
?>

    <!-- Hero Section -->
    <section class="relative bg-slate-900 text-white overflow-hidden">
        <div class="absolute inset-0 z-0">
            <?php 
                $hero_bg = get_option('orion_gov_hero_bg', 'https://images.unsplash.com/photo-1577017040065-6505235af3d8?auto=format&fit=crop&q=80'); 
            ?>
            <img src="<?php echo $hero_bg; ?>" alt="Government Building" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-gold-500/20 text-gold-400 text-sm font-semibold tracking-wide uppercase mb-6 border border-gold-500/30">
                    Selamat Datang di Portal Resmi
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-serif leading-tight mb-6">
                    <?php echo html_entity_decode(get_option('orion_gov_hero_title', 'Membangun Kota Cerdas & Melayani Sepenuh Hati'), ENT_QUOTES, 'UTF-8'); ?>
                </h1>
                <p class="text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed">
                    <?php echo html_entity_decode(get_option('orion_gov_hero_subtitle', 'Akses layanan publik, berita terbaru, dan transparansi anggaran dalam satu pintu. Kami hadir untuk melayani masyarakat dengan integritas.'), ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo site_url('/?page=layanan'); ?>" class="px-8 py-4 bg-gov-600 hover:bg-gov-500 text-white rounded-lg font-semibold shadow-lg shadow-gov-500/30 transition transform hover:-translate-y-1">
                        Layanan Publik
                    </a>
                    <a href="<?php echo site_url('/?page=pengaduan'); ?>" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur text-white border border-white/20 rounded-lg font-semibold transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Lapor / Pengaduan
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats/Info Bar -->
        <div class="relative z-20 bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-100">
                    <div class="p-6 text-center group hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-3xl font-bold text-gov-700 mb-1 group-hover:scale-110 transition duration-300">24/7</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Layanan Darurat</div>
                    </div>
                    <div class="p-6 text-center group hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-3xl font-bold text-gov-700 mb-1 group-hover:scale-110 transition duration-300">15+</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Layanan Online</div>
                    </div>
                    <div class="p-6 text-center group hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-3xl font-bold text-gov-700 mb-1 group-hover:scale-110 transition duration-300">98%</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kepuasan Publik</div>
                    </div>
                    <div class="p-6 text-center group hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-3xl font-bold text-gov-700 mb-1 group-hover:scale-110 transition duration-300">COVID-19</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Info Terkini</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 font-serif mb-4">Layanan Unggulan</h2>
                <p class="text-slate-600">Akses berbagai layanan publik secara mudah, cepat, dan transparan langsung dari perangkat Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition p-8 border border-slate-100 group">
                    <div class="w-14 h-14 bg-gov-100 text-gov-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-gov-600 group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Administrasi Kependudukan</h3>
                    <p class="text-slate-500 mb-6 leading-relaxed">Pengurusan KTP, KK, Akta Kelahiran, dan dokumen kependudukan lainnya secara online.</p>
                    <a href="#" class="inline-flex items-center text-gov-600 font-semibold hover:text-gov-700">
                        Akses Layanan <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition p-8 border border-slate-100 group">
                    <div class="w-14 h-14 bg-gov-100 text-gov-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-gov-600 group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Perizinan Usaha</h3>
                    <p class="text-slate-500 mb-6 leading-relaxed">Layanan perizinan terpadu satu pintu untuk kemudahan berinvestasi dan berusaha.</p>
                    <a href="#" class="inline-flex items-center text-gov-600 font-semibold hover:text-gov-700">
                        Akses Layanan <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <!-- Service 3 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition p-8 border border-slate-100 group">
                    <div class="w-14 h-14 bg-gov-100 text-gov-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-gov-600 group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kesehatan & Sosial</h3>
                    <p class="text-slate-500 mb-6 leading-relaxed">Informasi fasilitas kesehatan, bantuan sosial, dan jaminan kesehatan masyarakat.</p>
                    <a href="#" class="inline-flex items-center text-gov-600 font-semibold hover:text-gov-700">
                        Akses Layanan <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 font-serif mb-2">Berita Terkini</h2>
                    <p class="text-slate-600">Informasi terbaru seputar kegiatan pemerintah dan pembangunan.</p>
                </div>
                <a href="<?php echo site_url('/?page=berita'); ?>" class="hidden md:inline-flex items-center text-gov-600 font-semibold hover:text-gov-800">
                    Lihat Semua Berita <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $latest_posts = get_posts(['numberposts' => 3]);
                if ($latest_posts):
                    foreach ($latest_posts as $post):
                        $img = get_the_post_thumbnail_url($post->ID);
                        if(!$img) $img = 'https://via.placeholder.com/800x600?text=No+Image';
                ?>
                <article class="group flex flex-col h-full bg-white rounded-xl overflow-hidden border border-slate-100 hover:shadow-lg transition">
                    <div class="h-48 overflow-hidden relative">
                        <img src="<?php echo $img; ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-4 left-4 bg-gov-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            Berita
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs text-slate-500 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <?php echo date('d M Y', strtotime($post->post_date)); ?>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-gov-600 transition">
                            <a href="<?php echo site_url('?p=' . $post->ID); ?>">
                                <?php echo $post->post_title; ?>
                            </a>
                        </h3>
                        <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-1">
                            <?php echo wp_trim_words(strip_tags(html_entity_decode($post->post_content, ENT_QUOTES, 'UTF-8')), 20); ?>
                        </p>
                        <a href="<?php echo site_url('?p=' . $post->ID); ?>" class="text-gov-600 font-semibold text-sm hover:underline mt-auto">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>
                <?php 
                    endforeach; 
                else:
                ?>
                    <p class="col-span-3 text-center text-slate-500 py-10">Belum ada berita yang diterbitkan.</p>
                <?php endif; ?>
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="<?php echo site_url('/?page=berita'); ?>" class="inline-block px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">
                    Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>

    <!-- Public Call to Action -->
    <section class="py-16 bg-gov-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl font-bold mb-6 font-serif">Mari Wujudkan Kota yang Lebih Baik</h2>
            <p class="text-gov-100 text-lg mb-8 max-w-2xl mx-auto">Partisipasi Anda sangat berarti bagi pembangunan daerah. Sampaikan aspirasi, saran, dan pengaduan Anda melalui kanal resmi kami.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="px-8 py-3 bg-gold-500 hover:bg-gold-400 text-slate-900 font-bold rounded-lg transition shadow-lg shadow-gold-500/20">
                    Sampaikan Aspirasi
                </a>
                <a href="#" class="px-8 py-3 bg-transparent border border-white/30 hover:bg-white/10 text-white font-semibold rounded-lg transition">
                    Cek Status Pengaduan
                </a>
            </div>
        </div>
    </section>

<?php 
// SINGLE POST / PAGE LAYOUT
elseif ( is_single() || isset($_GET['page_id']) ): 
    global $wp_query;
    $post_id = 0;
    if (isset($_GET['p'])) {
        $post_id = (int) $_GET['p'];
    } elseif (isset($_GET['page_id'])) {
        $post_id = (int) $_GET['page_id'];
    } elseif (isset($wp_query) && isset($wp_query->query_vars['p'])) {
        $post_id = (int) $wp_query->query_vars['p'];
    } elseif (isset($wp_query) && isset($wp_query->query_vars['page_id'])) {
        $post_id = (int) $wp_query->query_vars['page_id'];
    } elseif (isset($wp_query) && !empty($wp_query->posts) && isset($wp_query->posts[0]) && isset($wp_query->posts[0]->ID)) {
        $post_id = (int) $wp_query->posts[0]->ID;
    }
    $post = get_post($post_id);
    if ($post):
?>
    <div class="bg-slate-50 py-12 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <?php if (get_the_post_thumbnail_url($post->ID)): ?>
                <div class="h-64 md:h-96 w-full relative">
                    <img src="<?php echo get_the_post_thumbnail_url($post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <?php if(!isset($_GET['page_id'])): ?>
                        <span class="inline-block px-3 py-1 bg-gov-600 text-white text-xs font-bold rounded-full mb-3">Berita</span>
                        <?php endif; ?>
                        <h1 class="text-3xl md:text-4xl font-bold text-white font-serif leading-tight text-shadow"><?php echo $post->post_title; ?></h1>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-8 md:p-12 border-b border-slate-100 bg-gov-50">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 font-serif leading-tight mb-4"><?php echo $post->post_title; ?></h1>
                    <?php if(!isset($_GET['page_id'])): ?>
                    <div class="flex items-center text-sm text-slate-500 gap-4">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> <?php echo date('d F Y', strtotime($post->post_date)); ?></span>
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Admin</span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="p-8 md:p-12 prose prose-slate prose-lg max-w-none">
                    <?php echo get_the_content(null, false, $post->ID); ?>
                </div>
                
                <?php if(!isset($_GET['page_id'])): ?>
                <div class="px-8 md:px-12 py-8 bg-slate-50 border-t border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Bagikan Informasi Ini</h3>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-[#3b5998] text-white rounded text-sm font-medium hover:opacity-90">Facebook</button>
                        <button class="px-4 py-2 bg-[#1da1f2] text-white rounded text-sm font-medium hover:opacity-90">Twitter</button>
                        <button class="px-4 py-2 bg-[#25d366] text-white rounded text-sm font-medium hover:opacity-90">WhatsApp</button>
                    </div>
                </div>
                <?php endif; ?>
            </article>
        </div>
    </div>

<?php 
    else:
        echo '<div class="max-w-7xl mx-auto px-4 py-20 text-center"><h1 class="text-2xl font-bold">Konten tidak ditemukan.</h1></div>';
    endif;

// DEFAULT / ARCHIVE LAYOUT
else: 
?>
    <div class="bg-slate-50 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-8 font-serif border-b pb-4">Arsip Berita & Informasi</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                if (have_posts()):
                    while (have_posts()): the_post();
                        $img = get_the_post_thumbnail_url();
                        if(!$img) $img = 'https://via.placeholder.com/800x600?text=Orion+Government';
                ?>
                <article class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
                    <a href="<?php the_permalink(); ?>" class="block h-48 overflow-hidden">
                        <img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                    </a>
                    <div class="p-6">
                        <div class="text-xs text-slate-500 mb-2"><?php echo get_the_date(); ?></div>
                        <h2 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 hover:text-gov-600 transition">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                            <?php echo wp_trim_words(get_the_content(), 15); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="text-gov-600 font-semibold text-sm hover:underline">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>
                <?php 
                    endwhile;
                else:
                    echo '<p class="col-span-3 text-slate-500">Belum ada konten.</p>';
                endif;
                ?>
            </div>
            
            <!-- Simple Pagination -->
            <div class="mt-12 flex justify-center gap-2">
                <?php 
                // Basic pagination simulation if needed, or core functions handles it
                ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
