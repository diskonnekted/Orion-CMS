<?php
$brand_name = orion_products_get_text_option('orion_products_brand_name', 'Orion Products');
$whatsapp = get_option('orion_products_whatsapp', '628123456789');
$cta_text = orion_products_get_text_option('orion_products_cta_text', 'Konsultasi & Order');

$all_products = orion_products_get_products();
$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$kategori = isset($_GET['kategori']) ? trim((string) $_GET['kategori']) : '';

$categories = array();
foreach ($all_products as $p) {
    $c = isset($p['category']) ? (string) $p['category'] : '';
    if ($c === '') $c = 'General';
    $categories[$c] = true;
}
$category_list = array_keys($categories);
sort($category_list, SORT_NATURAL | SORT_FLAG_CASE);

$filtered = array();
foreach ($all_products as $p) {
    $ok = true;
    if ($kategori !== '' && strcasecmp($kategori, (string) $p['category']) !== 0) $ok = false;
    if ($q !== '') {
        $hay = strtolower($p['name'] . ' ' . $p['category'] . ' ' . $p['desc']);
        if (strpos($hay, strtolower($q)) === false) $ok = false;
    }
    if ($ok) $filtered[] = $p;
}
?>

<main data-theme="orion-products">
    <section class="bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/15 text-sm font-bold">Katalog Produk</div>
                    <h1 class="mt-5 text-3xl sm:text-4xl font-extrabold">Temukan produk yang cocok</h1>
                    <p class="mt-3 text-white/70 max-w-2xl">Filter kategori, cari nama produk, lalu klik untuk lihat detail atau langsung beli.</p>
                </div>
                <a href="<?php echo htmlspecialchars(orion_products_catalog_url()); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 font-bold">Reset Filter</a>
            </div>
        </div>
    </section>

    <section class="py-10 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <input type="hidden" name="page" value="produk">
                <div class="md:col-span-5">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Cari</label>
                    <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="w-full p-3 rounded-2xl border border-slate-200 bg-white" placeholder="Cari produk...">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full p-3 rounded-2xl border border-slate-200 bg-white">
                        <option value="">Semua kategori</option>
                        <?php foreach ($category_list as $c): ?>
                            <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($kategori !== '' && strcasecmp($kategori, $c) === 0) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <button class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Terapkan</button>
                    <a href="<?php echo htmlspecialchars(orion_products_catalog_url()); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 font-bold">Reset</a>
                </div>
            </form>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="<?php echo htmlspecialchars(orion_products_catalog_url('', $q)); ?>" class="px-4 py-2 rounded-full text-sm font-bold border <?php echo $kategori === '' ? 'bg-brand-600 text-white border-brand-600' : 'bg-white text-slate-700 border-slate-200'; ?>">Semua</a>
                <?php foreach ($category_list as $c): ?>
                    <a href="<?php echo htmlspecialchars(orion_products_catalog_url($c, $q)); ?>" class="px-4 py-2 rounded-full text-sm font-bold border <?php echo ($kategori !== '' && strcasecmp($kategori, $c) === 0) ? 'bg-brand-600 text-white border-brand-600' : 'bg-white text-slate-700 border-slate-200'; ?>">
                        <?php echo htmlspecialchars($c); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 text-sm text-slate-600">
                Menampilkan <span class="font-extrabold text-slate-900"><?php echo count($filtered); ?></span> produk.
            </div>
        </div>
    </section>

    <section class="py-14 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (empty($filtered)): ?>
                <div class="bg-white border border-slate-200 rounded-3xl p-10 text-center">
                    <div class="text-2xl font-extrabold text-slate-900">Produk tidak ditemukan</div>
                    <div class="mt-2 text-slate-600">Coba ubah kata kunci atau kategori.</div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($filtered as $p): ?>
                        <?php
                        $detail_url = orion_products_product_url($p['slug']);
                        $msg = orion_products_buy_message($brand_name, $p, 1);
                        $buy_url = orion_products_wa_buy_url($whatsapp, $msg);
                        ?>
                        <div class="group bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition">
                            <a href="<?php echo htmlspecialchars($detail_url); ?>" class="block relative aspect-[4/3] overflow-hidden">
                                <img src="<?php echo htmlspecialchars($p['img']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/0"></div>
                                <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 border border-white/60 text-xs font-extrabold text-slate-800">
                                    <?php echo htmlspecialchars($p['category']); ?>
                                </div>
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
            <?php endif; ?>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] bg-gradient-to-br from-brand-600 to-fuchsia-600 text-white p-10 md:p-14">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                    <div>
                        <div class="text-sm font-bold uppercase tracking-widest text-white/80">Butuh rekomendasi?</div>
                        <div class="mt-2 text-3xl font-extrabold">Chat admin untuk konsultasi</div>
                        <div class="mt-3 text-white/85 max-w-2xl">Sampaikan kondisi kulit & preferensi, kami bantu pilihkan produk yang tepat.</div>
                    </div>
                    <a href="<?php echo htmlspecialchars(orion_products_wa_buy_url($whatsapp, 'Halo admin ' . $brand_name . ', saya ingin konsultasi produk.')); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white text-slate-900 font-extrabold hover:bg-white/95 transition">
                        <?php echo htmlspecialchars($cta_text); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
