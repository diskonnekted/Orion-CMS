<?php
if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die('Orion CMS core not found.');
    }
}

get_header();
?>

<?php if (is_single()): ?>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden border border-mosque-100">
                <?php if (has_post_thumbnail()): ?>
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" class="w-full h-72 object-cover" alt="<?php the_title(); ?>">
                <?php endif; ?>
                <div class="p-8">
                    <h1 class="text-3xl font-bold mb-4 text-mosque-800"><?php the_title(); ?></h1>
                    <div class="flex items-center text-slate-500 text-sm mb-6 gap-3">
                        <span><?php echo get_the_date(); ?></span>
                        <span class="w-1 h-1 rounded-full bg-mosque-400"></span>
                        <span><?php the_author(); ?></span>
                    </div>
                    <div class="prose max-w-none text-slate-700">
                        <?php the_content(); ?>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center text-sm">
                        <a href="<?php echo site_url(); ?>" class="text-mosque-600 hover:text-mosque-700 font-semibold">
                            &larr; Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </article>
        <?php endwhile; endif; ?>
    </div>

<?php else: ?>
<?php
$hero_title = get_option('orion_mosque_hero_title', 'Masjid Nyaman');
$hero_subtitle = get_option('orion_mosque_hero_subtitle', 'Penuh Cahaya Ibadah');
$hero_description = get_option('orion_mosque_hero_description', 'Pusat ibadah, kajian ilmu, dan kegiatan sosial yang ramah keluarga. Temukan jadwal shalat, agenda kegiatan, dan informasi donasi dalam satu halaman.');
$hero_image = get_option('orion_mosque_hero_image', '');

$contact_address = get_option('orion_mosque_contact_address', 'Jl. Contoh Masjid No. 123, Kota Anda');
$contact_phone = get_option('orion_mosque_contact_phone', '08xx-xxxx-xxxx');
$contact_email = get_option('orion_mosque_contact_email', 'info@orionmosque.id');

$prayer_city = get_option('orion_mosque_prayer_city', 'Jakarta');
$prayer_country = get_option('orion_mosque_prayer_country', 'Indonesia');
$prayer_method = (int)get_option('orion_mosque_prayer_method', 20);
if ($prayer_method <= 0) {
    $prayer_method = 20;
}

$kegiatan_badge = get_option('orion_mosque_kegiatan_badge', 'Agenda');
$kegiatan_title = get_option('orion_mosque_kegiatan_title', 'Kegiatan Rutin Masjid');
$kegiatan_description = get_option('orion_mosque_kegiatan_description', 'Highlight beberapa kegiatan unggulan masjid dalam seminggu.');
?>

<section class="relative overflow-hidden bg-gradient-to-br from-mosque-700 via-mosque-600 to-mosque-800 text-white">
    <?php if ($hero_image !== ''): ?>
        <div class="absolute inset-0">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($hero_image, ENT_QUOTES, 'UTF-8'); ?>');"></div>
            <div class="absolute inset-0 bg-mosque-900/70"></div>
        </div>
    <?php endif; ?>
    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_top,_#98FB98_0,_transparent_55%),radial-gradient(circle_at_bottom,_#50C878_0,_transparent_55%)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-vanilla-cream text-hunter border border-yellow-green text-xs font-semibold tracking-wide mb-5 shadow-sm">
                    Selamat Datang di
                    <span class="ml-2 text-hunter">Orion Mosque</span>
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    <?php echo $hero_title; ?><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-200 via-white to-mosque-100">
                        <?php echo $hero_subtitle; ?>
                    </span>
                </h1>
                <p class="text-mosque-50/90 text-base sm:text-lg mb-8 max-w-xl">
                    <?php echo $hero_description; ?>
                </p>
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="#jadwal" class="inline-flex items-center px-6 py-3 rounded-full bg-white text-mosque-700 font-semibold text-sm shadow-lg hover:bg-mosque-50 transition">
                        Lihat Jadwal Shalat
                    </a>
                    <a href="#kegiatan" class="inline-flex items-center px-5 py-3 rounded-full border border-mosque-200/70 text-mosque-50/90 text-sm hover:bg-white/5 transition">
                        Agenda Kegiatan
                    </a>
                </div>
                <div class="mt-10 grid grid-cols-3 gap-4 text-xs sm:text-sm">
                    <div>
                        <p class="text-mosque-200 font-semibold">Shalat Berjamaah</p>
                        <p class="text-mosque-50/80">Fardhu 5 waktu setiap hari</p>
                    </div>
                    <div>
                        <p class="text-mosque-200 font-semibold">Kajian Rutin</p>
                        <p class="text-mosque-50/80">Akhir pekan & hari besar</p>
                    </div>
                    <div>
                        <p class="text-mosque-200 font-semibold">Program Sosial</p>
                        <p class="text-mosque-50/80">Santunan dan kegiatan warga</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -inset-6 bg-emerald-300/20 blur-3xl"></div>
                <div class="relative bg-white/10 border border-white/20 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-mosque-500 text-white">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                            </svg>
                        </span>
                        Informasi Kontak
                    </h2>
                    <ul class="space-y-3 text-sm text-mosque-50/90">
                        <li>Alamat: <?php echo nl2br($contact_address); ?></li>
                        <li>Telepon: <?php echo $contact_phone; ?></li>
                        <li>Email: <?php echo $contact_email; ?></li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-white/20 text-xs text-mosque-50/80">
                        Silakan sesuaikan informasi ini melalui file tema atau sistem pengaturan yang Anda buat.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="jadwal" class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-2">Jadwal Shalat</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Waktu Shalat Hari Ini</h2>
                <p class="text-sm text-slate-500 mt-2">Silakan sesuaikan jadwal ini dengan data aktual dari sistem Anda.</p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs text-hunter bg-vanilla-cream border border-yellow-green rounded-full px-3 py-1">
                Contoh tampilan jadwal, statis di kode tema.
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <?php
            $dynamic_times = function_exists('orion_mosque_get_prayer_times')
                ? orion_mosque_get_prayer_times($prayer_city, $prayer_country, $prayer_method)
                : null;

            if (is_array($dynamic_times)) {
                $prayer_times = [
                    ['name' => 'Subuh', 'time' => $dynamic_times['Subuh'], 'accent' => 'from-mosque-500 to-mosque-600'],
                    ['name' => 'Dzuhur', 'time' => $dynamic_times['Dzuhur'], 'accent' => 'from-mosque-400 to-mosque-500'],
                    ['name' => 'Ashar', 'time' => $dynamic_times['Ashar'], 'accent' => 'from-mosque-300 to-mosque-400'],
                    ['name' => 'Maghrib', 'time' => $dynamic_times['Maghrib'], 'accent' => 'from-mosque-600 to-mosque-700'],
                    ['name' => 'Isya', 'time' => $dynamic_times['Isya'], 'accent' => 'from-mosque-700 to-mosque-800'],
                    ['name' => 'Jumat', 'time' => $dynamic_times['Dzuhur'], 'accent' => 'from-mosque-500 to-mosque-700'],
                ];
            } else {
                $prayer_times = [
                    ['name' => 'Subuh', 'time' => '04:30', 'accent' => 'from-mosque-500 to-mosque-600'],
                    ['name' => 'Dzuhur', 'time' => '12:00', 'accent' => 'from-mosque-400 to-mosque-500'],
                    ['name' => 'Ashar', 'time' => '15:15', 'accent' => 'from-mosque-300 to-mosque-400'],
                    ['name' => 'Maghrib', 'time' => '18:00', 'accent' => 'from-mosque-600 to-mosque-700'],
                    ['name' => 'Isya', 'time' => '19:15', 'accent' => 'from-mosque-700 to-mosque-800'],
                    ['name' => 'Jumat', 'time' => '12:00', 'accent' => 'from-mosque-500 to-mosque-700'],
                ];
            }

            foreach ($prayer_times as $item):
            ?>
                <div class="rounded-2xl border border-mosque-100 bg-white shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-gradient-to-r <?php echo $item['accent']; ?> text-white text-[11px] sm:text-xs font-semibold uppercase tracking-wide">
                        <?php echo $item['name']; ?>
                    </div>
                    <div class="px-4 py-3.5 flex items-baseline justify-between">
                        <p class="text-2xl font-bold text-mosque-800 leading-none"><?php echo $item['time']; ?></p>
                        <p class="text-[11px] text-slate-500">WIB</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="kegiatan" class="bg-mosque-50 py-16 border-y border-mosque-100/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-sage-green uppercase mb-2"><?php echo strtoupper($kegiatan_badge); ?></p>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900"><?php echo $kegiatan_title; ?></h2>
                <p class="text-sm text-slate-500 mt-2"><?php echo $kegiatan_description; ?></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-vanilla-cream rounded-2xl shadow-sm border border-sage-green p-6">
                <h3 class="text-lg font-semibold text-mosque-700 mb-2">Kajian Tafsir Al-Qur'an</h3>
                <p class="text-sm text-slate-500 mb-3">Setiap Sabtu Ba'da Maghrib bersama ustadz pilihan.</p>
                <p class="text-xs text-mosque-600 font-semibold">Terbuka untuk umum</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-yellow-green p-6">
                <h3 class="text-lg font-semibold text-mosque-700 mb-2">Pembinaan Remaja Masjid</h3>
                <p class="text-sm text-slate-500 mb-3">Program mentoring, diskusi, dan kegiatan kreatif untuk remaja.</p>
                <p class="text-xs text-mosque-600 font-semibold">Setiap Ahad pagi</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-blushed-brick p-6">
                <h3 class="text-lg font-semibold text-mosque-700 mb-2">Kelas Tahsin & Tahfidz</h3>
                <p class="text-sm text-slate-500 mb-3">Kelas intensif untuk memperbaiki bacaan dan menghafal Al-Qur'an.</p>
                <p class="text-xs text-mosque-600 font-semibold">Jadwal fleksibel, pendaftaran di sekretariat.</p>
            </div>
        </div>
    </div>
</section>

<section id="berita" class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-2">Informasi</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Berita Terbaru Masjid</h2>
                <p class="text-sm text-slate-500 mt-2">Gunakan postingan biasa untuk membagikan kabar terbaru.</p>
            </div>
            <a href="#" class="inline-flex items-center text-sm font-semibold text-mosque-600 hover:text-mosque-700">
                Lihat Semua Berita
                <svg class="w-4 h-4 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $news_posts = get_posts(['posts_per_page' => 3]);
            if ($news_posts):
                foreach ($news_posts as $post):
                    setup_postdata($post);
            ?>
                <article class="bg-mosque-50/60 border border-mosque-100 rounded-2xl overflow-hidden shadow-sm flex flex-col">
                    <div class="px-5 pt-5">
                        <div class="inline-flex items-center text-[11px] text-mosque-600 bg-mosque-100/80 px-2 py-1 rounded-full mb-3">
                            <?php echo get_the_date('d M Y'); ?>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-mosque-700">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 mb-4">
                            <?php
                            global $post;
                            $excerpt_raw = isset($post->post_content) ? $post->post_content : '';
                            $excerpt_clean = strip_tags(html_entity_decode($excerpt_raw, ENT_QUOTES, 'UTF-8'));
                            echo wp_trim_words($excerpt_clean, 18);
                            ?>
                        </p>
                    </div>
                    <div class="mt-auto px-5 pb-5 pt-3 border-t border-mosque-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Oleh <?php the_author(); ?></span>
                        <a href="<?php the_permalink(); ?>" class="text-mosque-600 font-semibold hover:text-mosque-700">
                            Baca &rarr;
                        </a>
                    </div>
                </article>
            <?php
                endforeach;
                wp_reset_postdata();
            else:
            ?>
                <div class="col-span-3 bg-mosque-50 border border-mosque-100 rounded-2xl p-10 text-center text-sm text-slate-500">
                    Belum ada berita yang dipublikasikan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>
