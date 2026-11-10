<?php get_header(); ?>

<?php
$brand_name = orion_products_get_text_option('orion_products_brand_name', 'Orion Products');
$tagline = orion_products_get_text_option('orion_products_tagline', 'Skincare & kosmetik premium untuk rutinitas harian.');
$hero_title = orion_products_get_text_option('orion_products_hero_title', 'Kulit sehat, tampilan percaya diri.');
$hero_subtitle = orion_products_get_text_option('orion_products_hero_subtitle', 'Formula ringan, hasil terlihat. Dari skincare harian sampai kosmetik untuk momen spesial.');
$cta_text = orion_products_get_text_option('orion_products_cta_text', 'Konsultasi & Order');
$whatsapp = get_option('orion_products_whatsapp', '628123456789');
$cta_url = $whatsapp ? ('https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp)) : '#';
$email = get_option('orion_products_email', 'hello@orionproducts.id');
$address = orion_products_get_text_option('orion_products_address', 'Jl. Contoh No. 10, Jakarta');

$local_images = orion_products_list_images();
if (empty($local_images)) {
    $asset_base = site_url('/assets/img/products');
    $local_images = array(
        $asset_base . '/pexels-cottonbro-7428102.jpg',
        $asset_base . '/pexels-elena-druzhinina-54874780-7817505.jpg',
        $asset_base . '/pexels-elsa-olofsson-3357043-5331314.jpg',
        $asset_base . '/pexels-misolo-cosmetic-2588316-11179692.jpg',
        $asset_base . '/pexels-misolo-cosmetic-2588316-4841523.jpg',
        $asset_base . '/pexels-noratopicals-7038220.jpg',
        $asset_base . '/pexels-noratopicals-7038227.jpg',
        $asset_base . '/pexels-noratopicals-7038230.jpg',
        $asset_base . '/pexels-noratopicals-7038243.jpg',
        $asset_base . '/pexels-taha-balta-3031128-4834307.jpg',
        $asset_base . '/pexels-tree-of-life-seeds-1708680-3577295.jpg',
    );
}

$hero_bg = get_option('orion_products_hero_bg', isset($local_images[0]) ? $local_images[0] : '');
$logo = get_option('orion_products_logo', '');

$products_all = orion_products_get_products();
$products = array_slice($products_all, 0, 6);
$gallery = $local_images;

$page = isset($_GET['page']) ? trim((string) $_GET['page']) : '';
if (isset($_GET['product']) && trim((string) $_GET['product']) !== '') {
    require __DIR__ . '/templates/product.php';
    get_footer();
    return;
}
if ($page === 'produk') {
    require __DIR__ . '/templates/catalog.php';
    get_footer();
    return;
}
?>

<main data-theme="orion-products">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo htmlspecialchars($hero_bg); ?>" alt="<?php echo htmlspecialchars($brand_name); ?>" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-tr from-slate-950/85 via-slate-950/55 to-brand-700/30"></div>
            <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top,rgba(244,63,94,0.55),transparent_55%)]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-sm font-bold border border-white/15 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-brand-400"></span>
                        Kosmetik • Skincare • Personal Care
                    </div>
                    <h1 class="mt-6 text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                        <?php echo htmlspecialchars($hero_title); ?>
                    </h1>
                    <p class="mt-6 text-lg text-white/80 leading-relaxed max-w-2xl">
                        <?php echo htmlspecialchars($hero_subtitle); ?>
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row gap-3">
                        <a href="#produk" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold shadow-lg shadow-brand-600/25 transition">
                            Lihat Produk
                        </a>
                        <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold border border-white/15 backdrop-blur transition">
                            <?php echo htmlspecialchars($cta_text); ?>
                        </a>
                    </div>

                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-white/10 border border-white/15 p-5 backdrop-blur">
                            <div class="text-white font-extrabold text-xl">Derm-friendly</div>
                            <div class="text-white/70 text-sm mt-1">Formula nyaman untuk pemakaian rutin.</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 border border-white/15 p-5 backdrop-blur">
                            <div class="text-white font-extrabold text-xl">Clean feel</div>
                            <div class="text-white/70 text-sm mt-1">Ringan, mudah menyerap, no greasy.</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 border border-white/15 p-5 backdrop-blur">
                            <div class="text-white font-extrabold text-xl">Everyday glow</div>
                            <div class="text-white/70 text-sm mt-1">Fokus pada hidrasi dan tampilan segar.</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="relative">
                        <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-brand-500/25 to-fuchsia-500/20 blur-2xl"></div>
                        <div class="relative bg-white/10 border border-white/15 backdrop-blur rounded-[2.5rem] p-4">
                            <div class="rounded-[2rem] overflow-hidden aspect-[4/5] bg-slate-900/30">
                                <img src="<?php echo htmlspecialchars($local_images[2]); ?>" alt="Produk" class="w-full h-full object-cover">
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-3">
                                <div class="rounded-2xl overflow-hidden aspect-square border border-white/10">
                                    <img src="<?php echo htmlspecialchars($local_images[3]); ?>" alt="Produk" class="w-full h-full object-cover">
                                </div>
                                <div class="rounded-2xl overflow-hidden aspect-square border border-white/10">
                                    <img src="<?php echo htmlspecialchars($local_images[4]); ?>" alt="Produk" class="w-full h-full object-cover">
                                </div>
                                <div class="rounded-2xl overflow-hidden aspect-square border border-white/10">
                                    <img src="<?php echo htmlspecialchars($local_images[5]); ?>" alt="Produk" class="w-full h-full object-cover">
                                </div>
                            </div>
                            <div class="mt-4 rounded-2xl bg-white/10 border border-white/15 p-4">
                                <div class="text-white font-extrabold"><?php echo htmlspecialchars($brand_name); ?></div>
                                <div class="text-white/70 text-sm mt-1"><?php echo htmlspecialchars($tagline); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="produk" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-bold">Produk Unggulan</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Rangkaian yang gampang dipakai</h2>
                    <p class="mt-3 text-slate-600 max-w-2xl">Pilih paket sesuai kebutuhan kulit dan gaya Anda. Semua foto di bawah menggunakan aset dari folder produk.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="<?php echo site_url('/?page=produk'); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 font-extrabold text-slate-900 hover:bg-slate-50 transition">Buka Katalog</a>
                    <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Tanya Produk</a>
                </div>
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $p): ?>
                    <?php
                    $detail_url = orion_products_product_url($p['slug']);
                    $buy_msg = orion_products_buy_message($brand_name, $p, 1);
                    $buy_url = orion_products_wa_buy_url($whatsapp, $buy_msg);
                    ?>
                    <div class="group bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition">
                        <a href="<?php echo htmlspecialchars($detail_url); ?>" class="block relative aspect-[4/3] overflow-hidden">
                            <img src="<?php echo htmlspecialchars($p['img']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/0"></div>
                            <?php if ($p['price']): ?>
                                <div class="absolute bottom-4 right-4 px-4 py-2 rounded-2xl bg-white/90 backdrop-blur border border-white/40 text-slate-900 font-extrabold text-sm">
                                    <?php echo htmlspecialchars($p['price']); ?>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="p-6">
                            <a href="<?php echo htmlspecialchars($detail_url); ?>" class="text-lg font-extrabold text-slate-900 hover:text-brand-700 transition"><?php echo htmlspecialchars($p['name']); ?></a>
                            <div class="mt-2 text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($p['desc']); ?></div>
                            <div class="mt-6 flex gap-2">
                                <a href="<?php echo htmlspecialchars($detail_url); ?>" class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-slate-200 font-extrabold text-slate-900 hover:bg-slate-50 transition">Detail</a>
                                <a href="<?php echo htmlspecialchars($buy_url); ?>" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Beli</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="manfaat" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm aspect-[4/5]">
                        <img src="<?php echo htmlspecialchars($local_images[6]); ?>" alt="Manfaat" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 text-brand-800 text-sm font-bold">Kenapa pilih kami</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Terlihat baik, terasa nyaman</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed max-w-2xl">Kami fokus pada pengalaman pemakaian: tekstur, aroma, dan hasil akhir. Cocok untuk rutinitas singkat maupun layering.</p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                            <div class="font-extrabold text-slate-900">Hidrasi & glow</div>
                            <div class="text-sm text-slate-600 mt-1">Membantu kulit terasa lembap, tampak segar.</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                            <div class="font-extrabold text-slate-900">Ringan dipakai</div>
                            <div class="text-sm text-slate-600 mt-1">Nyaman untuk cuaca tropis, tidak berat.</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                            <div class="font-extrabold text-slate-900">Looks natural</div>
                            <div class="text-sm text-slate-600 mt-1">Makeup lebih menyatu di kulit, hasil rapi.</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                            <div class="font-extrabold text-slate-900">Mudah dibawa</div>
                            <div class="text-sm text-slate-600 mt-1">Packaging travel-friendly dan estetik.</div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-3xl border border-slate-200 p-6 bg-gradient-to-br from-brand-50 to-white">
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <div>
                                <div class="font-extrabold text-slate-900">Butuh rekomendasi?</div>
                                <div class="text-sm text-slate-600 mt-1">Chat admin untuk rekomendasi rutin & produk.</div>
                            </div>
                            <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Konsultasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="galeri" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-bold">Galeri</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Lookbook produk</h2>
                    <p class="mt-3 text-slate-600 max-w-2xl">Semua foto di bawah berasal dari folder <span class="font-mono">/tmp/product</span> yang disalin ke aset tema.</p>
                </div>
            </div>

            <div class="mt-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <?php foreach ($gallery as $img): ?>
                    <a href="<?php echo htmlspecialchars($img); ?>" target="_blank" rel="noopener" class="group rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-lg transition">
                        <div class="aspect-square overflow-hidden">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="Gallery" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 text-brand-800 text-sm font-bold">Testimoni</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Dipakai harian, hasilnya konsisten</h2>
                    <p class="mt-3 text-slate-600 leading-relaxed">Contoh testimoni berikut bisa Anda ganti dengan konten asli. Layout ini sudah siap untuk materi promosi brand.</p>
                    <div class="mt-8 rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                        <img src="<?php echo htmlspecialchars($local_images[10]); ?>" alt="Customer" class="w-full h-80 object-cover">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                            <div class="text-sm font-bold text-slate-700">“Teksturnya enak dan ringan.”</div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Kulit terasa lebih lembap tanpa lengket. Makeup juga jadi lebih nempel.</div>
                            <div class="mt-5 text-xs font-extrabold text-slate-500 uppercase tracking-widest">Alya • Jakarta</div>
                        </div>
                        <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                            <div class="text-sm font-bold text-slate-700">“Warna lip tint-nya cakep.”</div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Nyaman di bibir, tidak bikin kering. Cocok untuk daily.</div>
                            <div class="mt-5 text-xs font-extrabold text-slate-500 uppercase tracking-widest">Nadia • Bandung</div>
                        </div>
                        <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                            <div class="text-sm font-bold text-slate-700">“Packaging-nya premium.”</div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Praktis dibawa dan tampilannya estetik buat konten.</div>
                            <div class="mt-5 text-xs font-extrabold text-slate-500 uppercase tracking-widest">Raisa • Surabaya</div>
                        </div>
                        <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                            <div class="text-sm font-bold text-slate-700">“Admin responsif banget.”</div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Dapat rekomendasi produk sesuai kondisi kulit, jelas dan helpful.</div>
                            <div class="mt-5 text-xs font-extrabold text-slate-500 uppercase tracking-widest">Dina • Depok</div>
                        </div>
                    </div>

                    <div class="mt-8 rounded-3xl border border-slate-200 p-6 bg-gradient-to-br from-slate-50 to-white">
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <div>
                                <div class="font-extrabold text-slate-900">Mau dapat promo?</div>
                                <div class="text-sm text-slate-600 mt-1">Kirim pesan untuk cek bundle & diskon.</div>
                            </div>
                            <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Minta Promo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-bold">FAQ</div>
                <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Pertanyaan yang sering ditanya</h2>
                <p class="mt-3 text-slate-600">Silakan sesuaikan FAQ sesuai brand Anda.</p>
            </div>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <details class="group bg-white rounded-3xl border border-slate-200 p-6">
                    <summary class="cursor-pointer list-none flex items-center justify-between gap-4">
                        <div class="font-extrabold text-slate-900">Apakah produk aman untuk pemakaian harian?</div>
                        <div class="h-10 w-10 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-700 group-open:bg-brand-50 group-open:border-brand-100">
                            <span class="group-open:hidden">+</span>
                            <span class="hidden group-open:block">–</span>
                        </div>
                    </summary>
                    <div class="mt-4 text-sm text-slate-600 leading-relaxed">Gunakan sesuai petunjuk dan hentikan pemakaian bila terjadi iritasi. Untuk kondisi khusus, konsultasikan terlebih dahulu.</div>
                </details>
                <details class="group bg-white rounded-3xl border border-slate-200 p-6">
                    <summary class="cursor-pointer list-none flex items-center justify-between gap-4">
                        <div class="font-extrabold text-slate-900">Berapa lama pengiriman?</div>
                        <div class="h-10 w-10 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-700 group-open:bg-brand-50 group-open:border-brand-100">
                            <span class="group-open:hidden">+</span>
                            <span class="hidden group-open:block">–</span>
                        </div>
                    </summary>
                    <div class="mt-4 text-sm text-slate-600 leading-relaxed">Pengiriman biasanya 1–3 hari kerja tergantung lokasi. Anda akan menerima nomor resi setelah paket diproses.</div>
                </details>
                <details class="group bg-white rounded-3xl border border-slate-200 p-6">
                    <summary class="cursor-pointer list-none flex items-center justify-between gap-4">
                        <div class="font-extrabold text-slate-900">Bisa konsultasi sebelum beli?</div>
                        <div class="h-10 w-10 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-700 group-open:bg-brand-50 group-open:border-brand-100">
                            <span class="group-open:hidden">+</span>
                            <span class="hidden group-open:block">–</span>
                        </div>
                    </summary>
                    <div class="mt-4 text-sm text-slate-600 leading-relaxed">Bisa. Klik tombol WhatsApp untuk konsultasi kebutuhan kulit dan rekomendasi rutin.</div>
                </details>
                <details class="group bg-white rounded-3xl border border-slate-200 p-6">
                    <summary class="cursor-pointer list-none flex items-center justify-between gap-4">
                        <div class="font-extrabold text-slate-900">Apakah tersedia paket hemat?</div>
                        <div class="h-10 w-10 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-700 group-open:bg-brand-50 group-open:border-brand-100">
                            <span class="group-open:hidden">+</span>
                            <span class="hidden group-open:block">–</span>
                        </div>
                    </summary>
                    <div class="mt-4 text-sm text-slate-600 leading-relaxed">Tersedia bundle untuk beberapa kategori. Hubungi admin untuk info promo terbaru.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] bg-gradient-to-br from-brand-600 to-fuchsia-600 text-white overflow-hidden">
                <div class="p-10 md:p-14 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-7">
                        <div class="text-sm font-bold uppercase tracking-widest text-white/80">Get started</div>
                        <div class="mt-3 text-3xl md:text-4xl font-extrabold leading-tight">Mulai rutinitas baru hari ini</div>
                        <div class="mt-4 text-white/85 leading-relaxed max-w-2xl">Dapatkan rekomendasi produk sesuai kebutuhan Anda. Kami bantu dari pemilihan sampai cara pakai.</div>
                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <a href="<?php echo htmlspecialchars($cta_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/95 transition">Chat WhatsApp</a>
                            <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white/10 border border-white/15 font-bold hover:bg-white/15 transition">Email</a>
                        </div>
                        <div class="mt-6 text-sm text-white/80"><?php echo nl2br(htmlspecialchars($address)); ?></div>
                    </div>
                    <div class="lg:col-span-5">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-3xl overflow-hidden aspect-square border border-white/15">
                                <img src="<?php echo htmlspecialchars($local_images[8]); ?>" alt="Promo" class="w-full h-full object-cover">
                            </div>
                            <div class="rounded-3xl overflow-hidden aspect-square border border-white/15">
                                <img src="<?php echo htmlspecialchars($local_images[9]); ?>" alt="Promo" class="w-full h-full object-cover">
                            </div>
                            <div class="col-span-2 rounded-3xl overflow-hidden aspect-[16/9] border border-white/15">
                                <img src="<?php echo htmlspecialchars($local_images[7]); ?>" alt="Promo" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
