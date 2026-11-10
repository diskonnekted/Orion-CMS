<?php
$brand_name = orion_products_get_text_option('orion_products_brand_name', 'Orion Products');
$tagline = orion_products_get_text_option('orion_products_tagline', 'Skincare & kosmetik premium untuk rutinitas harian.');
$whatsapp = get_option('orion_products_whatsapp', '628123456789');

$slug = isset($_GET['product']) ? (string) $_GET['product'] : '';
$product = orion_products_get_product_by_slug($slug);

$images = orion_products_list_images();
$gallery = array();
if ($product && !empty($product['img'])) {
    $gallery[] = $product['img'];
}
foreach ($images as $img) {
    if (!in_array($img, $gallery, true)) $gallery[] = $img;
}

$qty = isset($_GET['qty']) ? (int) $_GET['qty'] : 1;
if ($qty < 1) $qty = 1;
if ($qty > 99) $qty = 99;
?>

<main data-theme="orion-products">
    <?php if (!$product): ?>
        <section class="bg-slate-950 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-3xl font-extrabold">Produk tidak ditemukan</div>
                <div class="mt-2 text-white/70">Coba kembali ke katalog untuk memilih produk.</div>
                <div class="mt-8">
                    <a href="<?php echo htmlspecialchars(orion_products_catalog_url()); ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 font-extrabold transition">Buka Katalog</a>
                </div>
            </div>
        </section>
    <?php else: ?>
        <?php
        $buy_msg = orion_products_buy_message($brand_name, $product, $qty);
        $buy_url = orion_products_wa_buy_url($whatsapp, $buy_msg);
        $catalog_url = orion_products_catalog_url($product['category']);
        ?>
        <section class="bg-slate-950 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <div class="text-sm font-bold text-white/80 uppercase tracking-widest"><?php echo htmlspecialchars($product['category']); ?></div>
                        <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="mt-3 text-white/70 max-w-2xl"><?php echo htmlspecialchars($product['desc']); ?></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?php echo htmlspecialchars($catalog_url); ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 font-extrabold transition">Kembali ke Katalog</a>
                        <a href="<?php echo htmlspecialchars($buy_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">Beli via WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <div class="lg:col-span-6">
                        <div class="rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-sm aspect-[4/5] bg-slate-50">
                            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="mt-4 grid grid-cols-4 gap-3">
                            <?php foreach (array_slice($gallery, 0, 8) as $img): ?>
                                <a href="<?php echo htmlspecialchars($img); ?>" target="_blank" rel="noopener" class="rounded-2xl overflow-hidden border border-slate-200 bg-white aspect-square">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="Gallery" class="w-full h-full object-cover">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="lg:col-span-6">
                        <div class="rounded-[2.5rem] border border-slate-200 p-8 bg-slate-50">
                            <div class="flex items-end justify-between gap-6">
                                <div>
                                    <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Harga</div>
                                    <div class="mt-2 text-3xl font-extrabold text-slate-900"><?php echo htmlspecialchars($product['price'] ?: 'Hubungi admin'); ?></div>
                                </div>
                                <div class="text-sm text-slate-500"><?php echo htmlspecialchars($brand_name); ?></div>
                            </div>

                            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                                    <div class="font-extrabold text-slate-900">Manfaat</div>
                                    <div class="mt-2 text-sm text-slate-600">Mendukung rutinitas harian untuk tampilan yang lebih segar.</div>
                                </div>
                                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                                    <div class="font-extrabold text-slate-900">Tekstur</div>
                                    <div class="mt-2 text-sm text-slate-600">Ringan, mudah diratakan, nyaman dipakai.</div>
                                </div>
                                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                                    <div class="font-extrabold text-slate-900">Cara pakai</div>
                                    <div class="mt-2 text-sm text-slate-600">Gunakan sesuai kebutuhan. Konsultasikan untuk urutan layering.</div>
                                </div>
                                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                                    <div class="font-extrabold text-slate-900">Cocok untuk</div>
                                    <div class="mt-2 text-sm text-slate-600">Pemula maupun rutinitas harian yang sederhana.</div>
                                </div>
                            </div>

                            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                                <form method="GET" class="flex items-center gap-3">
                                    <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['slug']); ?>">
                                    <label class="text-sm font-bold text-slate-700">Qty</label>
                                    <input name="qty" type="number" min="1" max="99" value="<?php echo (int) $qty; ?>" class="w-24 p-3 rounded-2xl border border-slate-200 bg-white">
                                    <button class="px-5 py-3 rounded-2xl border border-slate-200 bg-white font-extrabold">Update</button>
                                </form>
                                <a href="<?php echo htmlspecialchars($buy_url); ?>" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition">
                                    Beli Sekarang
                                </a>
                            </div>
                            <div class="mt-6 text-xs text-slate-500">Klik Beli untuk mengirim pesan WhatsApp otomatis berisi produk, harga, qty, dan link.</div>
                        </div>

                        <div class="mt-8 rounded-[2.5rem] border border-slate-200 p-8 bg-white">
                            <div class="font-extrabold text-slate-900 text-xl">Tentang Brand</div>
                            <div class="mt-3 text-slate-600 leading-relaxed"><?php echo htmlspecialchars($tagline); ?></div>
                            <div class="mt-6 flex gap-3">
                                <a href="<?php echo htmlspecialchars(orion_products_catalog_url()); ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 font-extrabold">Lihat Produk Lain</a>
                                <a href="<?php echo htmlspecialchars(orion_products_wa_buy_url($whatsapp, 'Halo admin ' . $brand_name . ', saya ingin konsultasi produk.')); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-50 border border-brand-100 text-brand-800 font-extrabold">Konsultasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
