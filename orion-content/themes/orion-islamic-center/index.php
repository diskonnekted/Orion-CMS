<?php get_header(); ?>

<?php
$profile_id = (int) get_option('orion_ic_page_profile_id', 0);
$program_id = (int) get_option('orion_ic_page_program_id', 0);
$registration_id = (int) get_option('orion_ic_page_registration_id', 0);
$schedule_id = (int) get_option('orion_ic_page_schedule_id', 0);
$gallery_id = (int) get_option('orion_ic_page_gallery_id', 0);
$announcements_id = (int) get_option('orion_ic_page_announcements_id', 0);
$contact_id = (int) get_option('orion_ic_page_contact_id', 0);

$hero_title = (string) get_option('orion_ic_hero_title', 'Membina Generasi Qur’ani & Berakhlak Mulia');
$hero_subtitle = (string) get_option('orion_ic_hero_subtitle', 'Pendidikan terpadu: ilmu syar’i, tahfidz, adab, dan keterampilan untuk masa depan.');
$hero_title_decoded = $hero_title;
$hero_subtitle_decoded = $hero_subtitle;
for ($i = 0; $i < 5; $i++) {
    $next = html_entity_decode($hero_title_decoded, ENT_QUOTES, 'UTF-8');
    if ($next === $hero_title_decoded) break;
    $hero_title_decoded = $next;
}
for ($i = 0; $i < 5; $i++) {
    $next = html_entity_decode($hero_subtitle_decoded, ENT_QUOTES, 'UTF-8');
    if ($next === $hero_subtitle_decoded) break;
    $hero_subtitle_decoded = $next;
}
$hero_title_decoded = preg_replace('/&(?:amp;)+/i', '&', $hero_title_decoded);
$hero_subtitle_decoded = preg_replace('/&(?:amp;)+/i', '&', $hero_subtitle_decoded);
$hero_bg = get_option('orion_ic_hero_bg', 'https://images.unsplash.com/photo-1542810100-2c3c5f7c7d12?auto=format&fit=crop&q=80');
$about_img = get_option('orion_ic_about_img', 'https://images.unsplash.com/photo-1609599006353-e629aaabeb38?auto=format&fit=crop&q=80');
?>

<?php if ( !is_single() && !is_page() && !isset($_GET['cat']) && !isset($_GET['s']) ): ?>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo $hero_bg; ?>" class="w-full h-full object-cover" alt="Islamic Center">
            <div class="absolute inset-0 bg-black/35"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-ic-green-950/90 via-ic-green-900/75 to-ic-green-900/30"></div>
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,rgba(250,204,21,0.35),transparent_55%)]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-ic-yellow-200/20 text-ic-yellow-100 text-sm font-semibold border border-ic-yellow-200/30">
                    <span class="w-2 h-2 rounded-full bg-ic-yellow-300"></span>
                    Pesantren • Islamic Center
                </span>
                <h1 class="mt-6 text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight">
                    <?php echo htmlspecialchars($hero_title_decoded, ENT_QUOTES, 'UTF-8'); ?>
                </h1>
                <p class="mt-6 text-lg text-white/80 leading-relaxed">
                    <?php echo htmlspecialchars($hero_subtitle_decoded, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo $registration_id ? site_url('/?page_id=' . $registration_id) : '#'; ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-ic-yellow-300 hover:bg-ic-yellow-200 text-ic-green-950 font-bold shadow-lg shadow-ic-yellow-300/20 transition">
                        Daftar Santri Baru
                    </a>
                    <a href="<?php echo $schedule_id ? site_url('/?page_id=' . $schedule_id) : '#'; ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-semibold border border-white/15 backdrop-blur transition">
                        Lihat Jadwal Kegiatan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-ic-green-50 border border-ic-green-100">
                    <div class="text-sm font-bold text-ic-green-800">Program Unggulan</div>
                    <div class="mt-2 text-2xl font-serif font-bold text-slate-900">Tahfidz & Dirasah</div>
                    <div class="mt-3 text-slate-600">Pembinaan hafalan Al-Qur’an, tajwid, serta kajian kitab dasar dengan metode yang terstruktur.</div>
                </div>
                <div class="p-6 rounded-3xl bg-ic-yellow-50 border border-ic-yellow-100">
                    <div class="text-sm font-bold text-ic-green-800">Pembinaan Karakter</div>
                    <div class="mt-2 text-2xl font-serif font-bold text-slate-900">Adab & Disiplin</div>
                    <div class="mt-3 text-slate-600">Pembiasaan ibadah harian, kedisiplinan, dan akhlak mulia dalam kehidupan asrama.</div>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-slate-200">
                    <div class="text-sm font-bold text-ic-green-800">Keterampilan</div>
                    <div class="mt-2 text-2xl font-serif font-bold text-slate-900">Life Skill</div>
                    <div class="mt-3 text-slate-600">Pelatihan public speaking, literasi digital, dan kemandirian untuk masa depan santri.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-ic-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-ic-green-100 text-ic-green-800 text-sm font-semibold">
                        Tentang Kami
                    </div>
                    <h2 class="mt-4 text-3xl md:text-4xl font-serif font-bold text-slate-900">Pesantren yang Hangat & Terarah</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        Islamic Center membina santri dengan kurikulum terpadu: akademik, diniyah, tahfidz, dan pembentukan karakter. Lingkungan asri, pembimbing berpengalaman, serta fokus pada penguatan adab menjadi ruh pendidikan.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="<?php echo $profile_id ? site_url('/?page_id=' . $profile_id) : '#'; ?>" class="px-5 py-3 rounded-2xl bg-ic-green-700 hover:bg-ic-green-800 text-white font-bold transition">
                            Baca Profil Pesantren
                        </a>
                        <a href="<?php echo $program_id ? site_url('/?page_id=' . $program_id) : '#'; ?>" class="px-5 py-3 rounded-2xl bg-ic-yellow-100 hover:bg-ic-yellow-200 text-ic-green-900 font-bold border border-ic-yellow-200 transition">
                            Lihat Program
                        </a>
                        <a href="<?php echo $registration_id ? site_url('/?page_id=' . $registration_id) : '#'; ?>" class="px-5 py-3 rounded-2xl bg-white hover:bg-ic-green-50 text-ic-green-800 font-bold border border-ic-green-200 transition">
                            Alur Pendaftaran
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-br from-ic-green-300/30 to-ic-yellow-200/30 blur-2xl rounded-[40px]"></div>
                    <div class="relative rounded-[32px] overflow-hidden border border-white shadow-xl">
                        <img src="<?php echo htmlspecialchars($about_img); ?>" alt="Masjid" class="w-full h-[360px] object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-end justify-between gap-6 mb-10">
                <div>
                    <div class="text-sm font-bold text-ic-green-700">Pengumuman</div>
                    <h2 class="mt-2 text-3xl font-serif font-bold text-slate-900">Info Resmi Pesantren</h2>
                </div>
                <a class="hidden sm:inline-flex items-center gap-2 text-ic-green-700 font-bold hover:text-ic-green-800" href="<?php echo $announcements_id ? site_url('/?page_id=' . $announcements_id) : '#'; ?>">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                $announcements = get_posts(array('numberposts' => 3, 'post_type' => 'ic_announcement'));
                if ($announcements):
                    foreach ($announcements as $a):
                ?>
                    <article class="rounded-3xl border border-ic-green-100 bg-ic-green-50 p-6 shadow-sm hover:shadow-md transition flex flex-col">
                        <div class="flex items-center justify-between gap-4">
                            <div class="inline-flex items-center gap-2 text-xs font-bold text-ic-green-800">
                                <span class="w-2 h-2 rounded-full bg-ic-yellow-300"></span>
                                Pengumuman
                            </div>
                            <div class="text-xs text-slate-500 font-semibold"><?php echo date('d M Y', strtotime($a->post_date)); ?></div>
                        </div>
                        <h3 class="mt-3 text-xl font-bold text-slate-900 hover:text-ic-green-700 transition">
                            <a href="<?php echo site_url('/?p=' . $a->ID); ?>"><?php echo htmlspecialchars($a->post_title); ?></a>
                        </h3>
                        <div class="mt-3 text-sm text-slate-700 leading-relaxed flex-1">
                            <?php echo wp_trim_words(strip_tags(html_entity_decode($a->post_content, ENT_QUOTES, 'UTF-8')), 18); ?>
                        </div>
                        <a class="mt-4 inline-flex items-center gap-2 text-ic-green-700 font-bold hover:text-ic-green-800" href="<?php echo site_url('/?p=' . $a->ID); ?>">
                            Baca Pengumuman
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </article>
                <?php
                    endforeach;
                else:
                ?>
                    <div class="md:col-span-3 text-center text-slate-600 py-10">Belum ada pengumuman.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    $gallery_json = (string) get_option('orion_ic_gallery_images', '[]');
    $gallery_images = json_decode($gallery_json, true);
    if (!is_array($gallery_images)) $gallery_images = array();
    $gallery_preview = array_slice($gallery_images, 0, 6);
    ?>
    <section class="bg-ic-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-end justify-between gap-6 mb-10">
                <div>
                    <div class="text-sm font-bold text-ic-green-700">Galeri Kegiatan</div>
                    <h2 class="mt-2 text-3xl font-serif font-bold text-slate-900">Momen Berharga</h2>
                </div>
                <a class="hidden sm:inline-flex items-center gap-2 text-ic-green-700 font-bold hover:text-ic-green-800" href="<?php echo $gallery_id ? site_url('/?page_id=' . $gallery_id) : '#'; ?>">
                    Lihat Galeri
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <?php if (!empty($gallery_preview)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($gallery_preview as $url): ?>
                        <a href="<?php echo htmlspecialchars($url); ?>" class="group rounded-3xl overflow-hidden border border-white shadow-sm hover:shadow-md transition bg-white">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="<?php echo htmlspecialchars($url); ?>" alt="Galeri Kegiatan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center text-slate-600 py-10 rounded-3xl border border-ic-green-100 bg-white">Galeri belum diisi.</div>
            <?php endif; ?>
        </div>
    </section>

    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-end justify-between gap-6 mb-10">
                <div>
                    <div class="text-sm font-bold text-ic-green-700">Berita & Artikel</div>
                    <h2 class="mt-2 text-3xl font-serif font-bold text-slate-900">Kabar Terbaru</h2>
                </div>
                <a class="hidden sm:inline-flex items-center gap-2 text-ic-green-700 font-bold hover:text-ic-green-800" href="<?php echo site_url('/'); ?>">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                $latest_posts = get_posts(array('numberposts' => 3, 'post_type' => 'post'));
                if ($latest_posts):
                    foreach ($latest_posts as $p):
                        $img = get_the_post_thumbnail_url($p->ID, 'large');
                        if (!$img) $img = 'https://images.unsplash.com/photo-1581888227599-779811939961?auto=format&fit=crop&q=80';
                ?>
                    <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                        <a href="<?php echo site_url('/?p=' . $p->ID); ?>" class="block h-44 overflow-hidden">
                            <img src="<?php echo $img; ?>" class="w-full h-full object-cover hover:scale-105 transition duration-500" alt="<?php echo htmlspecialchars($p->post_title); ?>">
                        </a>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="text-xs text-slate-500 font-semibold"><?php echo date('d M Y', strtotime($p->post_date)); ?></div>
                            <h3 class="mt-2 text-xl font-bold text-slate-900 hover:text-ic-green-700 transition">
                                <a href="<?php echo site_url('/?p=' . $p->ID); ?>"><?php echo htmlspecialchars($p->post_title); ?></a>
                            </h3>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed flex-1">
                                <?php echo wp_trim_words(strip_tags(html_entity_decode($p->post_content, ENT_QUOTES, 'UTF-8')), 20); ?>
                            </div>
                            <a class="mt-4 inline-flex items-center gap-2 text-ic-green-700 font-bold hover:text-ic-green-800" href="<?php echo site_url('/?p=' . $p->ID); ?>">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                <?php
                    endforeach;
                else:
                ?>
                    <div class="md:col-span-3 text-center text-slate-600 py-10">Belum ada artikel.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php elseif ( is_page() || isset($_GET['page_id']) ): ?>
    <?php
    $page_id = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;
    $page = $page_id ? get_post($page_id) : null;
    ?>

    <?php if ($page_id && $page): ?>
        <?php if ($page_id === $profile_id): ?>
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ic-green-50 border border-ic-green-100 text-ic-green-800 text-sm font-semibold">Profil</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>
                        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-3xl border border-ic-green-100 bg-ic-green-50 p-6">
                                <div class="font-bold text-ic-green-800">Visi</div>
                                <div class="mt-2 text-slate-700">Mencetak generasi berilmu, beradab, dan bermanfaat.</div>
                            </div>
                            <div class="rounded-3xl border border-ic-yellow-100 bg-ic-yellow-50 p-6">
                                <div class="font-bold text-ic-green-800">Misi</div>
                                <div class="mt-2 text-slate-700">Pembinaan tahfidz, akhlak, ibadah, dan keterampilan.</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                                <div class="font-bold text-ic-green-800">Fasilitas</div>
                                <div class="mt-2 text-slate-700">Asrama, masjid, kelas, perpustakaan, dan lapangan.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $program_id): ?>
            <section class="bg-ic-green-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-ic-green-100 text-ic-green-800 text-sm font-semibold">Program</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>
                    </div>

                    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-3xl bg-white border border-ic-green-100 p-6 shadow-sm">
                            <div class="text-sm font-bold text-ic-green-800">Tahfidz</div>
                            <div class="mt-2 text-xl font-serif font-bold text-slate-900">Halaqah Harian</div>
                            <div class="mt-3 text-slate-600">Target hafalan bertahap dengan evaluasi rutin dan pendampingan musyrif.</div>
                        </div>
                        <div class="rounded-3xl bg-white border border-ic-green-100 p-6 shadow-sm">
                            <div class="text-sm font-bold text-ic-green-800">Diniyah</div>
                            <div class="mt-2 text-xl font-serif font-bold text-slate-900">Kajian Kitab</div>
                            <div class="mt-3 text-slate-600">Pembelajaran fiqh, akidah, sirah, adab, dan bacaan Al-Qur’an.</div>
                        </div>
                        <div class="rounded-3xl bg-white border border-ic-green-100 p-6 shadow-sm">
                            <div class="text-sm font-bold text-ic-green-800">Kemandirian</div>
                            <div class="mt-2 text-xl font-serif font-bold text-slate-900">Life Skill</div>
                            <div class="mt-3 text-slate-600">Kegiatan pembiasaan, kepemimpinan, dan keterampilan praktis.</div>
                        </div>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $schedule_id): ?>
            <?php
            $rows = trim((string) get_option('orion_ic_schedule_rows', ''));
            $ic_norm = function($value) {
                $value = is_string($value) ? trim($value) : '';
                for ($i = 0; $i < 5; $i++) {
                    $decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
                    if ($decoded === $value) break;
                    $value = $decoded;
                }
                for ($i = 0; $i < 5; $i++) {
                    $collapsed = preg_replace('/&(?:amp;)+/i', '&', $value);
                    if ($collapsed === $value) break;
                    $value = $collapsed;
                }
                return $value;
            };
            $items = array();
            foreach (preg_split("/\r\n|\n|\r/", $rows) as $line) {
                $line = trim($line);
                if ($line === '') continue;
                $parts = array_map('trim', explode('|', $line));
                $items[] = array(
                    'day' => $ic_norm(isset($parts[0]) ? $parts[0] : ''),
                    'time' => $ic_norm(isset($parts[1]) ? $parts[1] : ''),
                    'title' => $ic_norm(isset($parts[2]) ? $parts[2] : '')
                );
            }
            ?>
            <section class="bg-ic-green-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-ic-green-100 text-ic-green-800 text-sm font-semibold">Jadwal</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-4 text-slate-600">Agenda harian dan kegiatan pesantren yang terjadwal.</div>
                    </div>

                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $it): ?>
                                <div class="rounded-3xl bg-white border border-ic-green-100 p-6 shadow-sm">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="text-sm font-bold text-ic-green-800"><?php echo htmlspecialchars($it['day']); ?></div>
                                            <div class="mt-2 text-xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($it['title']); ?></div>
                                        </div>
                                        <div class="px-3 py-1 rounded-full bg-ic-yellow-100 text-ic-green-900 text-sm font-bold">
                                            <?php echo htmlspecialchars($it['time']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="md:col-span-2 text-center text-slate-600 py-10">Jadwal belum diatur.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $registration_id): ?>
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ic-yellow-50 border border-ic-yellow-100 text-ic-green-800 text-sm font-semibold">Pendaftaran</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-4 text-slate-600">Isi data di bawah ini. Admin akan menghubungi Anda untuk tahap berikutnya.</div>

                        <?php if (isset($_GET['submitted']) && $_GET['submitted'] === '1'): ?>
                            <div class="mt-6 rounded-2xl border border-ic-green-200 bg-ic-green-50 p-4 text-ic-green-900 font-semibold">
                                Pendaftaran berhasil dikirim. Kami akan menghubungi Anda.
                            </div>
                        <?php elseif (isset($_GET['error']) && $_GET['error'] === '1'): ?>
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 font-semibold">
                                Mohon lengkapi data wajib.
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="hidden" name="orion_ic_register" value="1">
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700">Nama Lengkap *</label>
                                <input name="full_name" required class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="Nama lengkap calon santri">
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">Jenis Kelamin</label>
                                <select name="gender" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">No. HP *</label>
                                <input name="phone" required class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="08xxxxxxxxxx">
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">Tempat Lahir</label>
                                <input name="birth_place" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="Kota">
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700">Alamat</label>
                                <textarea name="address" rows="3" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="Alamat lengkap"></textarea>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">Jenjang</label>
                                <select name="education_level" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300">
                                    <option value="">Pilih</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700">Nama Wali</label>
                                <input name="guardian_name" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="Nama orang tua/wali">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700">No. HP Wali</label>
                                <input name="guardian_phone" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ic-green-300" placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="md:col-span-2 flex flex-col sm:flex-row gap-3 mt-2">
                                <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-ic-green-700 hover:bg-ic-green-800 text-white font-bold shadow-sm transition">
                                    Kirim Pendaftaran
                                </button>
                                <a href="<?php echo $schedule_id ? site_url('/?page_id=' . $schedule_id) : '#'; ?>" class="flex-1 px-6 py-4 rounded-2xl bg-ic-yellow-100 hover:bg-ic-yellow-200 text-ic-green-900 font-bold text-center border border-ic-yellow-200 transition">
                                    Lihat Jadwal Kegiatan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $gallery_id): ?>
            <?php
            $gallery_json = (string) get_option('orion_ic_gallery_images', '[]');
            $gallery_images = json_decode($gallery_json, true);
            if (!is_array($gallery_images)) $gallery_images = array();
            ?>
            <section class="bg-ic-green-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-ic-green-100 text-ic-green-800 text-sm font-semibold">Galeri</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>
                    </div>

                    <div class="mt-10">
                        <?php if (!empty($gallery_images)): ?>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <?php foreach ($gallery_images as $url): ?>
                                    <a href="<?php echo htmlspecialchars($url); ?>" class="group rounded-3xl overflow-hidden border border-white shadow-sm hover:shadow-md transition bg-white">
                                        <div class="aspect-[4/3] overflow-hidden">
                                            <img src="<?php echo htmlspecialchars($url); ?>" alt="Galeri Kegiatan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-slate-600 py-10 rounded-3xl border border-ic-green-100 bg-white">Galeri belum diisi.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $announcements_id): ?>
            <?php
            $announcements = get_posts(array('numberposts' => 50, 'post_type' => 'ic_announcement'));
            ?>
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ic-yellow-50 border border-ic-yellow-100 text-ic-green-800 text-sm font-semibold">Pengumuman</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>
                    </div>

                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if ($announcements): ?>
                            <?php foreach ($announcements as $a): ?>
                                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                    <div class="text-xs text-slate-500 font-semibold"><?php echo date('d M Y', strtotime($a->post_date)); ?></div>
                                    <h3 class="mt-2 text-xl font-bold text-slate-900 hover:text-ic-green-700 transition">
                                        <a href="<?php echo site_url('/?p=' . $a->ID); ?>"><?php echo htmlspecialchars($a->post_title); ?></a>
                                    </h3>
                                    <div class="mt-3 text-sm text-slate-600 leading-relaxed">
                                        <?php echo wp_trim_words(strip_tags(html_entity_decode($a->post_content, ENT_QUOTES, 'UTF-8')), 24); ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="md:col-span-2 text-center text-slate-600 py-10">Belum ada pengumuman.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php elseif ($page_id === $contact_id): ?>
            <?php
            $wa = trim((string) get_option('orion_ic_whatsapp', ''));
            $address = (string) get_option('orion_ic_address', "Jl. Pesantren No. 1\nKota, Provinsi");
            $wa_digits = preg_replace('/\D+/', '', $wa);
            ?>
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ic-green-50 border border-ic-green-100 text-ic-green-800 text-sm font-semibold">Kontak</div>
                        <h1 class="mt-4 text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>

                        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                                <div class="font-bold text-slate-900">Alamat</div>
                                <div class="mt-3 text-slate-600 whitespace-pre-line"><?php echo htmlspecialchars($address); ?></div>
                            </div>
                            <div class="rounded-3xl border border-ic-green-100 bg-ic-green-50 p-6">
                                <div class="font-bold text-ic-green-900">WhatsApp</div>
                                <div class="mt-3 text-slate-700"><?php echo htmlspecialchars($wa); ?></div>
                                <?php if ($wa_digits): ?>
                                    <a class="mt-4 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-ic-yellow-300 hover:bg-ic-yellow-200 text-ic-green-950 font-bold transition" href="https://wa.me/<?php echo $wa_digits; ?>">
                                        Chat WhatsApp
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                    <div class="max-w-3xl">
                        <h1 class="text-4xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($page->post_title); ?></h1>
                        <div class="mt-6 prose prose-slate max-w-none">
                            <?php echo get_the_content(null, false, $page_id); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php else: ?>
        <section class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="text-2xl font-bold text-slate-900">Halaman tidak ditemukan.</div>
            </div>
        </section>
    <?php endif; ?>

<?php elseif ( is_single() || isset($_GET['p']) ): ?>
    <?php
    $post_id = isset($_GET['p']) ? (int) $_GET['p'] : 0;
    $p = $post_id ? get_post($post_id) : null;
    ?>
    <?php if ($p): ?>
        <section class="bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="text-sm text-slate-500 font-semibold"><?php echo date('d M Y', strtotime($p->post_date)); ?></div>
                <h1 class="mt-3 text-4xl md:text-5xl font-serif font-bold text-slate-900"><?php echo htmlspecialchars($p->post_title); ?></h1>
                <div class="mt-8 prose prose-slate max-w-none">
                    <?php echo get_the_content(null, false, $p->ID); ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="text-2xl font-bold text-slate-900">Konten tidak ditemukan.</div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php get_footer(); ?>
