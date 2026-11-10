<?php get_header(); ?>

<?php
$brand = get_option('orion_versa_brand_name', 'Orion Versa');
$hero_badge = get_option('orion_versa_hero_badge', 'WARNING / EMERGENCY IOT');
$hero_title = get_option('orion_versa_hero_title', 'Solusi IoT & AI untuk Mitigasi & Monitoring');
$hero_subtitle = get_option('orion_versa_hero_subtitle', 'Orion Versa adalah lini solusi IoT yang dirancang khusus untuk kondisi lokal Indonesia: bekerja di lapangan, tangguh, mudah diintegrasikan, dan bisa dipakai hingga daerah dengan jaringan terbatas.');

$pricing_id = (int) get_option('orion_versa_page_pricing_id', 0);
$booking_id = (int) get_option('orion_versa_page_booking_id', 0);
$ongkos_id = (int) get_option('orion_versa_page_ongkos_id', 0);
$shop_id = (int) get_option('orion_versa_page_shop_id', 0);

$current_page_id = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;

$features = orion_versa_parse_rows(get_option('orion_versa_features', ''), 2);
$products = orion_versa_parse_rows(get_option('orion_versa_products', ''), 2);
$pricing = orion_versa_parse_rows(get_option('orion_versa_pricing', ''), 4);
$ongkos_rows = orion_versa_parse_rows(get_option('orion_versa_ongkos_rows', ''), 3);
$faq = orion_versa_parse_rows(get_option('orion_versa_faq', ''), 2);

function orion_versa_card_icon($i) {
    $icons = array(
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'
    );
    return $icons[$i % count($icons)];
}
?>

<?php if ($current_page_id && $current_page_id === $booking_id): ?>
    <?php $page = get_post($current_page_id); ?>
    <section class="bg-slate-50 min-h-[70vh]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="mb-8">
                <div class="text-xs font-black uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars($brand); ?></div>
                <h1 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900"><?php echo htmlspecialchars($page ? $page->post_title : 'Booking / Demo'); ?></h1>
                <div class="mt-3 text-sm text-slate-500">
                    <a class="hover:text-slate-900" href="<?php echo site_url(); ?>">Beranda</a>
                    <span class="mx-2">/</span>
                    <span class="text-slate-700 font-bold"><?php echo htmlspecialchars($page ? $page->post_title : 'Booking'); ?></span>
                </div>
            </div>

            <?php if (isset($_GET['submitted']) && $_GET['submitted'] == '1'): ?>
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900 font-semibold">
                    Terima kasih! Data Anda sudah kami terima. Tim kami akan menghubungi Anda.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-900 font-semibold">
                    Mohon lengkapi minimal Nama dan WhatsApp.
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
                    <form method="POST" class="space-y-5">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1">Nama</label>
                            <input name="nama" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Nama Anda">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1">WhatsApp</label>
                                <input name="whatsapp" class="w-full p-3 rounded-xl border border-slate-200" placeholder="08xxxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1">Email (opsional)</label>
                                <input name="email" class="w-full p-3 rounded-xl border border-slate-200" placeholder="nama@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1">Perusahaan (opsional)</label>
                            <input name="perusahaan" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Nama usaha/perusahaan">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1">Paket diminati</label>
                                <select name="paket" class="w-full p-3 rounded-xl border border-slate-200 bg-white">
                                    <option value="">Pilih paket</option>
                                    <?php foreach ($pricing as $p): ?>
                                        <option value="<?php echo htmlspecialchars($p[0]); ?>"><?php echo htmlspecialchars($p[0]); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1">Tujuan</label>
                                <select name="kebutuhan" class="w-full p-3 rounded-xl border border-slate-200 bg-white">
                                    <option value="">Pilih tujuan</option>
                                    <option value="Deteksi Banjir/Longsor">Deteksi Banjir/Longsor</option>
                                    <option value="Load Scanner">Load Scanner</option>
                                    <option value="Stasiun Cuaca">Stasiun Cuaca</option>
                                    <option value="Divitrack">Divitrack</option>
                                    <option value="Command Center">Command Center</option>
                                    <option value="Survey & Instalasi">Survey & Instalasi</option>
                                    <option value="Butuh Konsultasi">Butuh Konsultasi</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="orion_versa_booking_submit" class="w-full py-4 rounded-2xl bg-versa-500 hover:bg-versa-400 text-slate-900 font-extrabold shadow-lg shadow-versa-500/20 transition">
                            Kirim Permintaan
                        </button>
                        <div class="text-xs text-slate-500">
                            Dengan mengirim form ini, Anda setuju untuk dihubungi oleh tim kami.
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-5 space-y-5">
                    <div class="rounded-3xl bg-slate-900 text-white p-7 versa-glow">
                        <div class="text-xs font-black uppercase tracking-widest text-white/70">Respon cepat</div>
                        <div class="mt-2 text-2xl font-black">Konsultasi via WhatsApp</div>
                        <p class="mt-2 text-white/70">Jika Anda perlu jawaban cepat, klik tombol WhatsApp dan kirim kebutuhan (mitigasi banjir/longsor, load scanner, cuaca, command center).</p>
                        <a class="mt-5 inline-flex items-center justify-center w-full px-5 py-3 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/90 transition" href="<?php echo orion_versa_whatsapp_url(); ?>">
                            WhatsApp Sekarang
                        </a>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-7">
                        <div class="text-sm font-extrabold">Yang Anda dapatkan</div>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li class="flex items-start gap-3"><span class="mt-0.5 text-versa-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span><span>Rekomendasi paket sesuai kebutuhan</span></li>
                            <li class="flex items-start gap-3"><span class="mt-0.5 text-versa-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span><span>Estimasi biaya & timeline implementasi</span></li>
                            <li class="flex items-start gap-3"><span class="mt-0.5 text-versa-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span><span>Demo alur booking & layanan</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <?php if ($page): ?>
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 prose prose-slate max-w-none">
                        <?php echo get_the_content(null, false, $page->ID); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php get_footer(); return; ?>
<?php endif; ?>

<?php if ($current_page_id && ($current_page_id === $pricing_id || $current_page_id === $ongkos_id)): ?>
    <?php $page = get_post($current_page_id); ?>
    <section class="bg-slate-50 min-h-[70vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="mb-10">
                <div class="text-xs font-black uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars($brand); ?></div>
                <h1 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900"><?php echo htmlspecialchars($page ? $page->post_title : 'Harga'); ?></h1>
                <p class="mt-3 text-slate-600 max-w-2xl">Pilih paket yang sesuai. Untuk kebutuhan khusus, tim kami bisa buatkan konfigurasi terbaik.</p>
            </div>

            <?php if ($current_page_id === $pricing_id): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($pricing as $idx => $p): ?>
                        <?php
                        $name = isset($p[0]) ? $p[0] : '';
                        $price = isset($p[1]) ? $p[1] : '';
                        $tag = isset($p[2]) ? $p[2] : '';
                        $f = array_slice($p, 3);
                        $isMid = ($idx === 1);
                        ?>
                        <div class="rounded-3xl border <?php echo $isMid ? 'border-versa-300 bg-white shadow-xl shadow-versa-500/10' : 'border-slate-200 bg-white shadow-sm'; ?> p-8 flex flex-col">
                            <?php if ($isMid): ?>
                                <div class="inline-flex self-start px-3 py-1 rounded-full bg-versa-50 text-versa-700 text-xs font-black uppercase tracking-widest border border-versa-200">Paling Populer</div>
                            <?php endif; ?>
                            <div class="mt-4 text-xl font-black text-slate-900"><?php echo htmlspecialchars($name); ?></div>
                            <div class="mt-1 text-3xl font-black text-versa-700"><?php echo htmlspecialchars($price); ?></div>
                            <div class="mt-2 text-sm text-slate-600"><?php echo htmlspecialchars($tag); ?></div>
                            <div class="mt-6 space-y-3 text-sm text-slate-700 flex-1">
                                <?php foreach ($f as $ff): ?>
                                    <?php if (trim((string) $ff) !== ''): ?>
                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-versa-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                            <span><?php echo htmlspecialchars($ff); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($booking_id): ?>
                                <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="mt-8 inline-flex items-center justify-center px-5 py-3 rounded-2xl <?php echo $isMid ? 'bg-versa-500 hover:bg-versa-400 text-slate-900' : 'bg-slate-900 hover:bg-slate-800 text-white'; ?> font-extrabold transition">
                                    Booking Instalasi
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo orion_versa_whatsapp_url('Halo Orion Versa, saya ingin tanya paket ' . $name . '.'); ?>" class="mt-3 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">
                                Tanya via WhatsApp
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($current_page_id === $ongkos_id): ?>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                        <div class="text-lg font-extrabold text-slate-900">Ongkos Layanan</div>
                        <div class="text-sm text-slate-600 mt-1">Contoh ongkos untuk survey, instalasi, dan maintenance. Harga final menyesuaikan lokasi dan scope.</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white border-b border-slate-200">
                                <tr class="text-left text-slate-700">
                                    <th class="px-6 py-4 font-extrabold">Layanan</th>
                                    <th class="px-6 py-4 font-extrabold">Biaya</th>
                                    <th class="px-6 py-4 font-extrabold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (empty($ongkos_rows)): ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-slate-500">Data ongkos belum diatur.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($ongkos_rows as $r): ?>
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 font-extrabold text-slate-900"><?php echo htmlspecialchars($r[0]); ?></td>
                                            <td class="px-6 py-4 font-black text-versa-700 whitespace-nowrap"><?php echo htmlspecialchars($r[1]); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($r[2]); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($page): ?>
                <div class="mt-10 bg-white rounded-3xl border border-slate-200 shadow-sm p-8 prose prose-slate max-w-none">
                    <?php echo get_the_content(null, false, $page->ID); ?>
                </div>
            <?php endif; ?>

            <div class="mt-10 rounded-3xl bg-slate-900 text-white p-10">
                <div class="text-2xl md:text-3xl font-black tracking-tight">Butuh paket khusus?</div>
                <p class="mt-2 text-white/70 max-w-2xl">Ceritakan kebutuhan titik pemasangan, sensor, dan dashboard. Kami bantu rekomendasi perangkat, instalasi, serta maintenance.</p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-6 py-3 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/90 transition">Konsultasi WhatsApp</a>
                    <?php if ($booking_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-6 py-3 rounded-2xl bg-white/10 border border-white/15 font-extrabold hover:bg-white/15 transition">Booking Demo</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php get_footer(); return; ?>
<?php endif; ?>

<?php if ($current_page_id && $shop_id && $current_page_id === $shop_id): ?>
    <?php
    $cat = isset($_GET['cat']) ? trim((string) $_GET['cat']) : '';
    $cat = preg_replace('/[^a-zA-Z0-9 _\\-]/', '', $cat);

    $all_products = array();
    if (function_exists('orion_shop_get_products')) {
        $all_products = orion_shop_get_products(200);
    } else {
        global $orion_db, $table_prefix;
        if (isset($orion_db) && $orion_db && isset($table_prefix)) {
            $table = $table_prefix . 'orion_products';
            $exists = $orion_db->query("SHOW TABLES LIKE '" . $orion_db->real_escape_string($table) . "'");
            if ($exists && $exists->num_rows > 0) {
                $res = $orion_db->query("SELECT * FROM $table ORDER BY created_at DESC LIMIT 200");
                if ($res) {
                    while ($row = $res->fetch_object()) {
                        $all_products[] = $row;
                    }
                }
            }
        }
    }

    $categories = array();
    foreach ($all_products as $p) {
        $c = isset($p->category) ? trim((string) $p->category) : '';
        if ($c === '') continue;
        $categories[$c] = true;
    }
    $categories = array_keys($categories);
    sort($categories);

    $products_filtered = array();
    foreach ($all_products as $p) {
        $type = isset($p->type) ? trim((string) $p->type) : '';
        if ($type === 'service') continue;
        $c = isset($p->category) ? trim((string) $p->category) : '';
        if ($cat !== '' && $c !== $cat) continue;
        $products_filtered[] = $p;
    }
    ?>

    <section class="bg-slate-50 min-h-[70vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="mb-10">
                <div class="text-xs font-black uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars($brand); ?></div>
                <h1 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900">Shop</h1>
                <p class="mt-3 text-slate-600 max-w-2xl">Klik Beli untuk langsung chat WhatsApp. Tidak ada proses checkout/ecommerce.</p>
            </div>

            <?php if (!empty($categories)): ?>
                <div class="flex flex-wrap gap-2 mb-8">
                    <a href="<?php echo site_url('/?page_id=' . $shop_id); ?>" class="px-4 py-2 rounded-full text-sm font-extrabold border <?php echo $cat === '' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'; ?>">
                        Semua
                    </a>
                    <?php foreach ($categories as $c): ?>
                        <a href="<?php echo site_url('/?page_id=' . $shop_id . '&cat=' . rawurlencode($c)); ?>" class="px-4 py-2 rounded-full text-sm font-extrabold border <?php echo $cat === $c ? 'bg-versa-500 text-slate-950 border-versa-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'; ?>">
                            <?php echo htmlspecialchars($c); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($products_filtered)): ?>
                <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-600 font-semibold">
                    Produk belum tersedia. Pastikan plugin Orion Shop Manager aktif dan produk sudah ditambahkan.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products_filtered as $p): ?>
                        <?php
                        $name = isset($p->name) ? (string) $p->name : '';
                        $sku = isset($p->sku) ? (string) $p->sku : '';
                        $desc = isset($p->description) ? (string) $p->description : '';
                        $category = isset($p->category) ? (string) $p->category : '';
                        $type = isset($p->type) ? (string) $p->type : '';
                        $img = isset($p->image) ? (string) $p->image : '';
                        $img_url = orion_versa_resolve_media_url($img);
                        $price_raw = 0;
                        if (isset($p->sale_price) && $p->sale_price !== null && (int) $p->sale_price > 0) $price_raw = (int) $p->sale_price;
                        else if (isset($p->price)) $price_raw = (int) $p->price;

                        $price = function_exists('orion_shop_format_price') ? orion_shop_format_price($price_raw) : orion_versa_shop_format_price($price_raw);
                        $buy_url = orion_versa_shop_buy_url($name, $price, $sku, site_url('/?page_id=' . $shop_id));
                        $stock = '';
                        if (isset($p->stock_status)) $stock = (string) $p->stock_status;
                        ?>
                        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm hover:shadow-lg transition flex flex-col">
                            <?php if ($img_url !== ''): ?>
                                <div class="mb-5">
                                    <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="w-full h-44 object-cover rounded-2xl border border-slate-200 bg-slate-50" loading="lazy">
                                </div>
                            <?php else: ?>
                                <div class="mb-5 w-full h-44 rounded-2xl border border-slate-200 bg-versa-50 flex items-center justify-center text-versa-700">
                                    <svg class="w-10 h-10 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18v14H3V5zm3 10l3-3 4 4 3-3 2 2"></path></svg>
                                </div>
                            <?php endif; ?>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <?php if (trim($category) !== ''): ?>
                                        <div class="text-xs font-black uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars($category); ?></div>
                                    <?php endif; ?>
                                    <div class="mt-2 text-lg font-extrabold text-slate-900"><?php echo htmlspecialchars($name); ?></div>
                                    <?php if (trim($sku) !== ''): ?>
                                        <div class="mt-1 text-xs text-slate-500 font-bold">SKU: <?php echo htmlspecialchars($sku); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-versa-700 whitespace-nowrap"><?php echo htmlspecialchars($price); ?></div>
                                    <?php if ($type !== ''): ?>
                                        <div class="mt-1 inline-flex px-2 py-1 rounded-full bg-slate-50 border border-slate-200 text-[11px] font-black uppercase tracking-widest text-slate-700">
                                            <?php echo htmlspecialchars($type); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (trim($desc) !== ''): ?>
                                <div class="mt-4 text-sm text-slate-600 leading-relaxed">
                                    <?php echo htmlspecialchars(orion_seo_text_excerpt($desc, 180)); ?>
                                </div>
                            <?php endif; ?>

                            <div class="mt-6 flex flex-col gap-3 mt-auto">
                                <a href="<?php echo htmlspecialchars($buy_url); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-versa-500 hover:bg-versa-400 text-slate-950 font-extrabold transition">
                                    Beli via WhatsApp
                                </a>
                                <div class="text-xs text-slate-500 font-semibold">
                                    <?php echo $stock !== '' ? ('Status: ' . htmlspecialchars($stock)) : ''; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-10 rounded-3xl bg-slate-950 text-white p-10">
                <div class="text-2xl md:text-3xl font-black tracking-tight">Butuh penawaran proyek?</div>
                <p class="mt-2 text-white/70 max-w-2xl">Untuk kebutuhan instansi/industri (emergency/command center), kirim detail lokasi dan scope. Tim kami bantu rekomendasi perangkat dan instalasi.</p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-6 py-3 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/90 transition">Konsultasi WhatsApp</a>
                    <?php if ($booking_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-6 py-3 rounded-2xl bg-white/10 border border-white/15 font-extrabold hover:bg-white/15 transition">Booking Instalasi</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php get_footer(); return; ?>
<?php endif; ?>

<?php if (is_single() || $current_page_id): ?>
    <?php
    global $wp_query;
    $post_id = 0;
    if (isset($_GET['p'])) $post_id = (int) $_GET['p'];
    elseif ($current_page_id) $post_id = $current_page_id;
    elseif (isset($wp_query) && isset($wp_query->query_vars['p'])) $post_id = (int) $wp_query->query_vars['p'];
    elseif (isset($wp_query) && isset($wp_query->query_vars['page_id'])) $post_id = (int) $wp_query->query_vars['page_id'];
    elseif (isset($wp_query) && !empty($wp_query->posts) && isset($wp_query->posts[0]) && isset($wp_query->posts[0]->ID)) $post_id = (int) $wp_query->posts[0]->ID;
    $post = get_post($post_id);
    ?>
    <?php if ($post): ?>
        <section class="bg-slate-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="mb-8">
                    <div class="text-xs font-black uppercase tracking-widest text-slate-500"><?php echo htmlspecialchars($brand); ?></div>
                    <h1 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900"><?php echo htmlspecialchars($post->post_title); ?></h1>
                    <div class="mt-3 text-sm text-slate-500">
                        <a class="hover:text-slate-900" href="<?php echo site_url(); ?>">Beranda</a>
                        <span class="mx-2">/</span>
                        <span class="text-slate-700 font-bold"><?php echo htmlspecialchars($post->post_title); ?></span>
                    </div>
                </div>
                <article class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 prose prose-slate max-w-none">
                    <?php echo get_the_content(null, false, $post->ID); ?>
                </article>
            </div>
        </section>
    <?php else: ?>
        <div class="max-w-4xl mx-auto px-4 py-20 text-center text-slate-600 font-semibold">Konten tidak ditemukan.</div>
    <?php endif; ?>
    <?php get_footer(); return; ?>
<?php endif; ?>

<section class="bg-versa-500 text-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-7">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-600 text-white text-xs font-black uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    <?php echo htmlspecialchars($hero_badge); ?>
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-[1.05]">
                    <span class="block"><?php echo htmlspecialchars($hero_title); ?></span>
                    <span class="block"><?php echo htmlspecialchars($brand); ?></span>
                </h1>
                <p class="text-slate-900/80 text-lg md:text-xl leading-relaxed max-w-2xl">
                    <?php echo htmlspecialchars($hero_subtitle); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <?php if ($booking_id): ?>
                        <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-6 py-4 rounded-2xl bg-slate-950 hover:bg-slate-900 text-white font-extrabold shadow-lg shadow-slate-900/20 transition">
                            Booking Instalasi
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-6 py-4 rounded-2xl bg-white text-slate-950 font-extrabold hover:bg-slate-50 transition border border-slate-900/20">
                        Konsultasi WhatsApp
                    </a>
                    <a href="#produk" class="px-6 py-4 rounded-2xl bg-transparent border border-slate-900/30 text-slate-950 font-extrabold hover:bg-slate-950/5 transition">
                        Lihat Produk
                    </a>
                </div>
                <div class="pt-6 flex flex-wrap gap-6 text-sm text-slate-900/80">
                    <div class="flex items-center gap-2"><span class="text-slate-950"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>Monitoring real-time</div>
                    <div class="flex items-center gap-2"><span class="text-slate-950"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>Alert bertingkat</div>
                    <div class="flex items-center gap-2"><span class="text-slate-950"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>Command center</div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-900/10 shadow-xl shadow-slate-900/10">
                    <div class="text-xs font-black uppercase tracking-widest text-slate-500">Fokus</div>
                    <div class="mt-2 text-2xl font-black text-slate-950">Warning & operasional</div>
                    <p class="mt-2 text-slate-700">Dari sensor lapangan hingga dashboard: data real-time, histori, dan notifikasi yang bisa ditindak.</p>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-2xl font-black">IoT</div>
                            <div class="text-xs uppercase tracking-widest text-slate-500 mt-1">Lapangan</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-2xl font-black">ALERT</div>
                            <div class="text-xs uppercase tracking-widest text-slate-500 mt-1">WhatsApp</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-2xl font-black">DATA</div>
                            <div class="text-xs uppercase tracking-widest text-slate-500 mt-1">Historis</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-2xl font-black">OPS</div>
                            <div class="text-xs uppercase tracking-widest text-slate-500 mt-1">Command</div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="#konsep" class="inline-flex items-center justify-center w-full px-5 py-3 rounded-2xl bg-slate-950 text-white font-extrabold hover:bg-slate-900 transition">
                            Lihat konsep
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="produk" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Produk</div>
            <h2 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900">Produk unggulan Orion Versa</h2>
            <p class="mt-3 text-slate-600">Lini produk yang fokus pada kebutuhan lapangan: mitigasi, industri, cuaca, pelacakan, dan command center.</p>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $i => $p): ?>
                <?php
                $name = isset($p[0]) ? $p[0] : '';
                $desc = isset($p[1]) ? $p[1] : '';
                $tag = isset($p[2]) ? $p[2] : '';
                ?>
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-2xl bg-versa-50 text-versa-700 flex items-center justify-center">
                        <?php echo orion_versa_card_icon($i); ?>
                    </div>
                    <div class="mt-5 flex items-center gap-2">
                        <div class="text-lg font-extrabold text-slate-900"><?php echo htmlspecialchars($name); ?></div>
                        <?php if (trim($tag) !== ''): ?>
                            <div class="px-2 py-1 rounded-full bg-versa-50 text-versa-800 text-[11px] font-black uppercase tracking-widest border border-versa-200"><?php echo htmlspecialchars($tag); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2 text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($desc); ?></div>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="#paket" class="px-4 py-2 rounded-xl bg-slate-950 text-white font-extrabold hover:bg-slate-900 transition">Lihat Paket</a>
                        <a href="<?php echo orion_versa_whatsapp_url('Halo Orion Versa, saya tertarik produk ' . $name . '.'); ?>" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Tanya</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="konsep" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Konsep</div>
            <h2 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900">Konsep Orion Versa</h2>
            <p class="mt-3 text-slate-600">Berangkat dari kebutuhan lapangan: kondisi tropis, jaringan terbatas, alert bertingkat, dan command center.</p>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($features as $i => $f): ?>
                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-2xl bg-versa-50 text-versa-700 flex items-center justify-center">
                        <?php echo orion_versa_card_icon($i); ?>
                    </div>
                    <div class="mt-5 text-lg font-extrabold text-slate-900"><?php echo htmlspecialchars($f[0]); ?></div>
                    <div class="mt-2 text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($f[1]); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="paket" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="max-w-2xl">
                <div class="text-xs font-black uppercase tracking-widest text-slate-500">Paket</div>
                <h2 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900">Paket implementasi</h2>
                <p class="mt-3 text-slate-600">Pilih paket sesuai kebutuhan. Untuk emergency/instansi, scope disesuaikan lokasi dan SOP respon.</p>
            </div>
            <div class="flex gap-3">
                <?php if ($pricing_id): ?>
                    <a href="<?php echo site_url('/?page_id=' . $pricing_id); ?>" class="px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">Detail Harga</a>
                <?php endif; ?>
                <?php if ($booking_id): ?>
                    <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-5 py-3 rounded-2xl bg-versa-500 hover:bg-versa-400 text-slate-900 font-extrabold transition shadow-lg shadow-versa-500/15">Booking Instalasi</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($pricing as $idx => $p): ?>
                <?php
                $name = isset($p[0]) ? $p[0] : '';
                $price = isset($p[1]) ? $p[1] : '';
                $tag = isset($p[2]) ? $p[2] : '';
                $f = array_slice($p, 3);
                $isMid = ($idx === 1);
                ?>
                <div class="rounded-3xl border <?php echo $isMid ? 'border-versa-300 bg-white shadow-xl shadow-versa-500/10' : 'border-slate-200 bg-white shadow-sm'; ?> p-8 flex flex-col">
                    <?php if ($isMid): ?>
                        <div class="inline-flex self-start px-3 py-1 rounded-full bg-versa-50 text-versa-700 text-xs font-black uppercase tracking-widest border border-versa-200">Rekomendasi</div>
                    <?php endif; ?>
                    <div class="mt-4 text-xl font-black text-slate-900"><?php echo htmlspecialchars($name); ?></div>
                    <div class="mt-2 text-3xl font-black text-versa-700"><?php echo htmlspecialchars($price); ?></div>
                    <div class="mt-2 text-sm text-slate-600"><?php echo htmlspecialchars($tag); ?></div>
                    <div class="mt-6 space-y-3 text-sm text-slate-700 flex-1">
                        <?php foreach ($f as $ff): ?>
                            <?php if (trim((string) $ff) !== ''): ?>
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 text-versa-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                    <span><?php echo htmlspecialchars($ff); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-8 grid gap-3">
                        <?php if ($booking_id): ?>
                            <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl <?php echo $isMid ? 'bg-versa-500 hover:bg-versa-400 text-slate-900' : 'bg-slate-900 hover:bg-slate-800 text-white'; ?> font-extrabold transition">
                                Booking Instalasi
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo orion_versa_whatsapp_url('Halo Orion Versa, saya tertarik paket ' . $name . '.'); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 font-extrabold hover:bg-slate-50 transition">
                            Tanya WhatsApp
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($ongkos_id): ?>
            <div class="mt-10 rounded-3xl bg-white border border-slate-200 p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-lg font-extrabold text-slate-900">Butuh instalasi & maintenance?</div>
                    <div class="text-sm text-slate-600 mt-1">Tampilkan ongkos survey, pemasangan, dan perawatan agar pelanggan paham biaya sejak awal.</div>
                </div>
                <a href="<?php echo site_url('/?page_id=' . $ongkos_id); ?>" class="px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition">
                    Lihat Ongkos
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section id="kontak" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Kontak</div>
            <h2 class="mt-2 text-3xl md:text-4xl font-black tracking-tight text-slate-900">Diskusikan kebutuhan lokasi Anda</h2>
            <p class="mt-3 text-slate-600">Kirim titik pemasangan, tujuan, dan kondisi jaringan. Tim kami bantu rekomendasi perangkat, instalasi, dan SOP alert.</p>
        </div>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($faq as $row): ?>
                <div class="rounded-3xl border border-slate-200 bg-white p-7">
                    <div class="text-lg font-extrabold text-slate-900"><?php echo htmlspecialchars($row[0]); ?></div>
                    <div class="mt-2 text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($row[1]); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 rounded-3xl bg-slate-950 text-white p-10">
            <div class="text-2xl md:text-3xl font-black tracking-tight">Siap mulai?</div>
            <p class="mt-2 text-white/70 max-w-2xl">Klik booking untuk survey/instalasi, atau chat WhatsApp untuk konsultasi kebutuhan emergency dan dashboard.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <?php if ($booking_id): ?>
                    <a href="<?php echo site_url('/?page_id=' . $booking_id); ?>" class="px-6 py-3 rounded-2xl bg-versa-500 hover:bg-versa-400 text-slate-900 font-extrabold transition shadow-lg shadow-versa-500/20">Booking Instalasi</a>
                <?php endif; ?>
                <a href="<?php echo orion_versa_whatsapp_url(); ?>" class="px-6 py-3 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/90 transition">WhatsApp</a>
                <?php if ($pricing_id): ?>
                    <a href="<?php echo site_url('/?page_id=' . $pricing_id); ?>" class="px-6 py-3 rounded-2xl bg-white/10 border border-white/15 font-extrabold hover:bg-white/15 transition">Detail Harga</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-7">
                <div class="text-sm font-extrabold text-slate-900">Email</div>
                <div class="mt-2 text-sm text-slate-600"><?php echo htmlspecialchars(get_option('orion_versa_contact_email', 'info@clasnet.id')); ?></div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-7">
                <div class="text-sm font-extrabold text-slate-900">Telepon</div>
                <div class="mt-2 text-sm text-slate-600"><?php echo htmlspecialchars(get_option('orion_versa_contact_phone', '+62 851 1704 1846')); ?></div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-7">
                <div class="text-sm font-extrabold text-slate-900">Alamat</div>
                <div class="mt-2 text-sm text-slate-600"><?php echo htmlspecialchars(get_option('orion_versa_contact_address', 'Perum Kalisemi Baru D5 No.7, Banjarnegara 53412, Indonesia')); ?></div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
