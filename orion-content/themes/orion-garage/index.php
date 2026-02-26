<?php get_header(); ?>

<?php
$post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
$page_id = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
$view_id = $post_id > 0 ? $post_id : $page_id;

$front_title = get_option('orion_garage_front_title', 'Rawat mobil lebih mudah dengan bengkel yang mengerti kebutuhanmu');
$front_subtitle = get_option('orion_garage_front_subtitle', 'Orion Garage menyediakan servis berkala, perbaikan darurat, pengecekan komputer, hingga detailing dengan teknisi berpengalaman dan peralatan modern.');
$front_cta_text = get_option('orion_garage_front_cta_text', 'Booking Servis via WhatsApp');
$front_cta_url = get_option('orion_garage_front_cta_url', '');
$front_hero_image = get_option('orion_garage_front_hero_image', '');

$store_name = get_option('orion_garage_store_name', 'Orion Garage');
$store_address = get_option('orion_garage_store_address', "Jl. Otomotif Raya No. 123\nKota Anda, Indonesia");
$store_phone = get_option('orion_garage_store_phone', '+62 812‑3456‑7890');
$store_whatsapp = get_option('orion_garage_store_whatsapp', '6281234567890');
$store_hours = get_option('orion_garage_store_hours', "Senin–Jumat: 08.00–17.00\nSabtu: 08.00–15.00\nMinggu dan hari libur: By appointment");
$map_embed = get_option('orion_garage_map_embed', '');

if ($view_id > 0) {
    $post = get_post($view_id);
    if ($post) {
        $title = trim($post->post_title);
        $type = $post->post_type;

        if ($type === 'page' && $title === 'Layanan') {
            ?>
            <section class="space-y-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold tracking-wide uppercase text-garage-200 mb-2">Layanan Orion Garage</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 leading-tight">
                        Paket servis untuk berbagai kebutuhan mobil
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base">
                        Pilih paket yang paling sesuai dengan kondisi mobilmu. Semua paket sudah termasuk pengecekan multi‑point.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-5 flex flex-col">
                        <p class="text-xs font-semibold text-garage-200 mb-1 uppercase">Paket Hemat</p>
                        <h2 class="text-xl font-bold text-white mb-2">Servis Berkala 10.000 km</h2>
                        <p class="text-2xl font-extrabold text-garage-200 mb-1">Rp 750.000*</p>
                        <p class="text-xs text-gray-400 mb-3">Estimasi, tergantung jenis mobil dan oli</p>
                        <ul class="text-sm text-gray-200 space-y-1.5 mb-4">
                            <li>• Ganti oli mesin dan filter oli</li>
                            <li>• Cek rem, ban, dan suspensi</li>
                            <li>• Cek aki dan sistem kelistrikan dasar</li>
                        </ul>
                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya ingin tanya Paket Servis Berkala 10.000 km di Orion Garage.'); ?>" class="mt-auto inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold text-white shadow-lg shadow-garage-600/40 transition">
                            Konsultasi Paket Ini
                        </a>
                    </div>
                    <div class="rounded-2xl bg-slate-900/80 border border-garage-500/60 p-5 flex flex-col ring-1 ring-garage-500/40">
                        <p class="text-xs font-semibold text-garage-200 mb-1 uppercase">Paling Populer</p>
                        <h2 class="text-xl font-bold text-white mb-2">Servis Lengkap</h2>
                        <p class="text-2xl font-extrabold text-garage-200 mb-1">Rp 1.250.000*</p>
                        <p class="text-xs text-gray-400 mb-3">Estimasi, termasuk beberapa parts pengganti</p>
                        <ul class="text-sm text-gray-200 space-y-1.5 mb-4">
                            <li>• Semua item Paket Hemat</li>
                            <li>• Pengecekan dan pembersihan throttle body</li>
                            <li>• Cek sistem injeksi dan scanner OBD</li>
                            <li>• Cek AC dan sistem pendingin</li>
                        </ul>
                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya ingin booking Paket Servis Lengkap di Orion Garage.'); ?>" class="mt-auto inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold text-white shadow-lg shadow-garage-600/40 transition">
                            Booking Paket Lengkap
                        </a>
                    </div>
                    <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-5 flex flex-col">
                        <p class="text-xs font-semibold text-garage-200 mb-1 uppercase">Premium</p>
                        <h2 class="text-xl font-bold text-white mb-2">Detailing & Perawatan</h2>
                        <p class="text-2xl font-extrabold text-garage-200 mb-1">Mulai Rp 900.000</p>
                        <p class="text-xs text-gray-400 mb-3">Untuk interior dan eksterior mobil</p>
                        <ul class="text-sm text-gray-200 space-y-1.5 mb-4">
                            <li>• Cuci salon interior lengkap</li>
                            <li>• Poles bodi dan coating ringan</li>
                            <li>• Proteksi kaca dan lampu</li>
                        </ul>
                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya ingin konsultasi paket detailing di Orion Garage.'); ?>" class="mt-auto inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold text-white shadow-lg shadow-garage-600/40 transition">
                            Tanya Paket Detailing
                        </a>
                    </div>
                </div>
                <div class="text-xs text-gray-400">
                    *Harga di atas adalah estimasi. Harga final akan diinformasikan setelah pengecekan langsung di bengkel.
                </div>
            </section>
            <?php
        } elseif ($type === 'page' && $title === 'Promo') {
            ?>
            <section class="space-y-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold tracking-wide uppercase text-garage-200 mb-2">Promo Bengkel</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 leading-tight">
                        Promo servis dan spare part untukmu
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base">
                        Manfaatkan promo berkala untuk servis rutin dan penggantian spare part original.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl bg-slate-900/60 border border-emerald-400/40 p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-bold text-white">Promo Servis Akhir Pekan</h2>
                            <span class="px-2 py-1 rounded-full bg-emerald-500/20 text-xs text-emerald-200 font-semibold">-10%</span>
                        </div>
                        <p class="text-sm text-gray-200 mb-3">
                            Diskon 10% untuk servis berkala setiap Sabtu, khusus booking via WhatsApp.
                        </p>
                        <p class="text-xs text-gray-400 mb-4">Minimal transaksi Rp 750.000.</p>
                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya ingin ambil Promo Servis Akhir Pekan di Orion Garage.'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-xs font-semibold text-white shadow-lg shadow-emerald-600/40 transition">
                            Klaim Promo via WhatsApp
                        </a>
                    </div>
                    <div class="rounded-2xl bg-slate-900/60 border border-amber-400/40 p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-bold text-white">Promo Ganti Oli + Filter</h2>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-xs text-amber-200 font-semibold">Bundling</span>
                        </div>
                        <p class="text-sm text-gray-200 mb-3">
                            Paket bundling ganti oli mesin + filter oli + cek 20 titik hanya Rp 650.000*.
                        </p>
                        <p class="text-xs text-gray-400 mb-4">Berlaku untuk mobil LCGC dan city car.</p>
                        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya tertarik dengan Promo Ganti Oli + Filter di Orion Garage.'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-amber-500 hover:bg-amber-400 text-xs font-semibold text-slate-900 shadow-lg shadow-amber-500/40 transition">
                            Tanya Detail Promo
                        </a>
                    </div>
                </div>
            </section>
            <?php
        } elseif ($type === 'page' && $title === 'Kontak') {
            ?>
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                <div class="lg:col-span-7 xl:col-span-8 space-y-5">
                    <div>
                        <p class="text-xs font-semibold tracking-wide uppercase text-garage-200 mb-2">Kontak & Booking</p>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 leading-tight">
                            Booking jadwal servis mobilmu
                        </h1>
                        <p class="text-gray-300 text-sm sm:text-base">
                            Isi formulir berikut, lalu kirim via WhatsApp. Tim kami akan mengkonfirmasi jadwal dan estimasi biaya.
                        </p>
                    </div>
                    <form id="garage-booking-form" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-300 mb-1">Nama lengkap</label>
                                <input type="text" id="gb-name" class="w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-garage-500" placeholder="Nama kamu">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-300 mb-1">Nomor WhatsApp</label>
                                <input type="text" id="gb-phone" class="w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-garage-500" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-300 mb-1">Jenis layanan</label>
                                <select id="gb-service" class="w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-garage-500">
                                    <option value="Servis berkala">Servis berkala</option>
                                    <option value="Perbaikan mesin">Perbaikan mesin</option>
                                    <option value="AC dan kelistrikan">AC dan kelistrikan</option>
                                    <option value="Detailing">Detailing</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-300 mb-1">Tanggal yang diinginkan</label>
                                <input type="date" id="gb-date" class="w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-garage-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-300 mb-1">Catatan tambahan</label>
                            <textarea id="gb-notes" rows="3" class="w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-garage-500" placeholder="Contoh: jenis mobil, keluhan yang dirasakan, jam yang diinginkan"></textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                            <button type="button" id="gb-submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold text-white shadow-lg shadow-garage-600/40 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.5 3.5A11.5 11.5 0 0 0 3 17.6L2 22l4.5-1.2A11.4 11.4 0 0 0 12 21.5h.1A11.5 11.5 0 0 0 20.5 3.5Zm-8.4 15a9.4 9.4 0 0 1-4.8-1.3L7 17l-2.6.7.7-2.5-.2-.5a9.4 9.4 0 1 1 6.2 4.8ZM17 14.4c-.3-.1-1.7-.8-1.9-.9s-.4-.1-.6.1l-.9 1c-.1.1-.3.2-.6.1s-1.2-.4-2.3-1.5-1.4-1.9-1.5-2.1 0-.4.1-.5l.4-.5.3-.5c.1-.1 0-.3 0-.4L9.2 7c-.2-.4-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-1 2.2c0 1.3.9 2.5 1 2.6s1.8 2.7 4.4 3.7a14.5 14.5 0 0 0 1.5.5 3.6 3.6 0 0 0 1.7.1c.5-.1 1.7-.7 1.9-1.3s.2-1.1.2-1.2-.2-.1-.4-.2Z"/>
                                </svg>
                                Kirim via WhatsApp
                            </button>
                            <p class="text-xs text-gray-400">
                                Data di atas tidak disimpan di server. Pesan langsung dikirim ke WhatsApp bengkel.
                            </p>
                        </div>
                    </form>
                </div>
                <aside class="lg:col-span-5 xl:col-span-4 space-y-4">
                    <div class="bg-slate-800/70 border border-white/10 rounded-2xl p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-white mb-3">Lokasi Bengkel</h2>
                        <p class="text-gray-300 text-sm whitespace-pre-line">
                            <?php echo nl2br(htmlspecialchars($store_address)); ?>
                        </p>
                        <p class="text-gray-300 text-sm mt-2">
                            Telepon: <a href="tel:<?php echo htmlspecialchars($store_phone); ?>" class="text-garage-200 hover:text-garage-100"><?php echo htmlspecialchars($store_phone); ?></a>
                        </p>
                        <p class="text-gray-300 text-sm">
                            WhatsApp: <a href="<?php echo 'https://wa.me/' . htmlspecialchars($store_whatsapp); ?>" class="text-garage-200 hover:text-garage-100">Chat sekarang</a>
                        </p>
                    </div>
                    <?php if ($map_embed): ?>
                    <div class="bg-slate-800/70 border border-white/10 rounded-2xl p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-white mb-3">Peta Lokasi</h2>
                        <div class="mt-3 rounded-xl overflow-hidden border border-white/10 bg-slate-900">
                            <?php echo $map_embed; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </aside>
            </section>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                var btn = document.getElementById('gb-submit');
                if (!btn) return;
                btn.addEventListener('click', function () {
                    var name = document.getElementById('gb-name').value.trim();
                    var phone = document.getElementById('gb-phone').value.trim();
                    var service = document.getElementById('gb-service').value;
                    var date = document.getElementById('gb-date').value;
                    var notes = document.getElementById('gb-notes').value.trim();

                    var text = 'Halo Orion Garage,%0A%0A';
                    text += 'Saya ingin booking servis mobil.%0A';
                    if (name) text += 'Nama: ' + encodeURIComponent(name) + '%0A';
                    if (phone) text += 'No. WhatsApp: ' + encodeURIComponent(phone) + '%0A';
                    if (service) text += 'Jenis layanan: ' + encodeURIComponent(service) + '%0A';
                    if (date) text += 'Tanggal yang diinginkan: ' + encodeURIComponent(date) + '%0A';
                    if (notes) text += 'Catatan: ' + encodeURIComponent(notes) + '%0A';

                    var waNumber = '<?php echo htmlspecialchars($store_whatsapp); ?>';
                    var url = 'https://wa.me/' + waNumber + '?text=' + text;
                    window.open(url, '_blank');
                });
            });
            </script>
            <?php
        } elseif ($type === 'page' && $title === 'Shop' && function_exists('orion_garage_is_shop_manager_active') && orion_garage_is_shop_manager_active()) {
            $products = array();
            if (function_exists('orion_shop_get_products')) {
                $products = orion_shop_get_products(12);
            }
            ?>
            <section class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wide uppercase text-garage-200 mb-2">Shop</p>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 leading-tight">
                            Spare part dan layanan dengan harga transparan
                        </h1>
                        <p class="text-gray-300 text-sm sm:text-base">
                            Harga tiap produk dan layanan disusun melalui Orion Shop Manager dan ditampilkan langsung di sini.
                        </p>
                    </div>
                    <a href="<?php echo site_url('/orion-content/plugins/orion-shop-manager/manager.php'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 rounded-full border border-white/20 text-xs font-semibold text-gray-100 hover:bg-white/10">
                        Buka Shop Manager (Admin)
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php
                    if ($products) {
                        foreach ($products as $product) {
                            $price = function_exists('orion_shop_get_price') ? orion_shop_get_price($product->id) : (isset($product->price) ? (int)$product->price : 0);
                            $stock = function_exists('orion_shop_get_stock') ? orion_shop_get_stock($product->id) : '';
                            $price_label = function_exists('orion_shop_format_price') ? orion_shop_format_price($price) : $price;
                            $wa_url = function_exists('orion_shop_get_whatsapp_url') ? orion_shop_get_whatsapp_url($product->id) : ('https://wa.me/' . htmlspecialchars($store_whatsapp));
                            $thumb = isset($product->image) && $product->image !== '' ? $product->image : '';
                            $is_service = isset($product->type) && $product->type === 'service';
                            $cta_label = $is_service ? 'Booking via WhatsApp' : 'Beli via WhatsApp';
                            ?>
                            <article class="rounded-2xl bg-slate-900/60 border border-white/10 overflow-hidden flex flex-col">
                                <div class="relative w-full h-40 sm:h-44 md:h-48 bg-slate-800">
                                    <?php if ($thumb): ?>
                                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="absolute inset-0 w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="absolute inset-0 flex items-center justify-center text-gray-500 text-xs">
                                            Tidak ada gambar
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <h2 class="text-sm font-semibold text-white mb-1 line-clamp-2"><?php echo htmlspecialchars($product->name); ?></h2>
                                    <p class="text-xs text-gray-400 mb-1">
                                        <?php echo isset($product->category) && $product->category !== '' ? htmlspecialchars($product->category) : ($is_service ? 'Layanan Bengkel' : 'Produk Bengkel'); ?>
                                    </p>
                                    <p class="text-sm font-bold text-garage-200 mb-1"><?php echo $price_label; ?></p>
                                    <?php if ($stock !== ''): ?>
                                        <p class="text-xs text-gray-300 mb-3">Stok: <?php echo htmlspecialchars($stock); ?></p>
                                    <?php endif; ?>
                                    <div class="mt-auto flex">
                                        <a href="<?php echo $wa_url; ?>" target="_blank" class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-full bg-garage-600 hover:bg-garage-500 text-xs font-semibold text-white">
                                            <?php echo $cta_label; ?>
                                        </a>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        ?>
                        <p class="text-gray-300 text-sm">Belum ada produk yang diatur di Shop Manager.</p>
                        <?php
                    }
                    ?>
                </div>
            </section>
            <?php
        } else {
            $thumb_url = get_the_post_thumbnail_url($post->ID);
            ?>
            <article class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                <div class="lg:col-span-7 xl:col-span-8">
                    <div class="mb-6">
                        <p class="text-xs font-semibold tracking-wide uppercase text-garage-200 mb-2">Detail Informasi</p>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 leading-tight">
                            <?php echo htmlspecialchars($post->post_title); ?>
                        </h1>
                        <p class="text-xs text-gray-400">
                            Diperbarui pada <?php echo date('d F Y', strtotime($post->post_date)); ?>
                        </p>
                    </div>
                    <?php if ($thumb_url): ?>
                        <div class="mb-6 rounded-2xl overflow-hidden border border-white/10 shadow-xl shadow-black/40">
                            <img src="<?php echo htmlspecialchars($thumb_url); ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-auto object-cover">
                        </div>
                    <?php endif; ?>
                    <div class="prose prose-invert prose-sm sm:prose lg:prose-lg max-w-none text-gray-100 leading-relaxed">
                        <?php
                        if (function_exists('apply_filters')) {
                            echo apply_filters('the_content', $post->post_content);
                        } else {
                            echo $post->post_content;
                        }
                        ?>
                    </div>
                </div>
                <aside class="lg:col-span-5 xl:col-span-4 space-y-6">
                    <div class="bg-slate-800/70 border border-white/10 rounded-2xl p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-garage-600 text-xs font-bold">A</span>
                            Booking Servis Cepat
                        </h2>
                        <p class="text-gray-300 text-sm mb-4">
                            Hubungi tim kami untuk booking jadwal servis mobil tanpa antre dan konsultasi gratis.
                        </p>
                        <div class="space-y-3 text-sm">
                            <a href="https://wa.me/6281234567890" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-green-500 hover:bg-green-400 text-white font-semibold shadow-lg shadow-green-500/40 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.5 3.5A11.5 11.5 0 0 0 3 17.6L2 22l4.5-1.2A11.4 11.4 0 0 0 12 21.5h.1A11.5 11.5 0 0 0 20.5 3.5Zm-8.4 15a9.4 9.4 0 0 1-4.8-1.3L7 17l-2.6.7.7-2.5-.2-.5a9.4 9.4 0 1 1 6.2 4.8ZM17 14.4c-.3-.1-1.7-.8-1.9-.9s-.4-.1-.6.1l-.9 1c-.1.1-.3.2-.6.1s-1.2-.4-2.3-1.5-1.4-1.9-1.5-2.1 0-.4.1-.5l.4-.5.3-.5c.1-.1 0-.3 0-.4L9.2 7c-.2-.4-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-1 2.2c0 1.3.9 2.5 1 2.6s1.8 2.7 4.4 3.7a14.5 14.5 0 0 0 1.5.5 3.6 3.6 0 0 0 1.7.1c.5-.1 1.7-.7 1.9-1.3s.2-1.1.2-1.2-.2-.1-.4-.2Z"/>
                                </svg>
                                Chat via WhatsApp
                            </a>
                            <a href="tel:+6281234567890" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-full border border-white/20 text-gray-100 hover:bg-white/10 font-semibold transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.54 1.23l-2.1.9a11.05 11.05 0 005.02 5.02l.9-2.1a1 1 0 011.23-.54l3.3 1.1a1 1 0 01.68.95V20a2 2 0 01-2 2h-1C9.82 22 2 14.18 2 4V5z" />
                                </svg>
                                Hubungi Bengkel
                            </a>
                        </div>
                    </div>
                    <div class="bg-slate-800/70 border border-white/10 rounded-2xl p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-white mb-3">Jam Operasional</h2>
                        <p class="text-gray-300 text-sm whitespace-pre-line">
                            <?php echo nl2br(htmlspecialchars($store_hours)); ?>
                        </p>
                    </div>
                </aside>
            </article>
            <?php
        }
    } else {
        ?>
        <p class="text-gray-200">Halaman tidak ditemukan.</p>
        <?php
    }
} else {
    ?>
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start mb-10">
        <div class="lg:col-span-7 xl:col-span-8 space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-garage-600/20 border border-garage-500/40 text-xs font-semibold text-garage-100">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-garage-400"></span>
                Bengkel Servis Mobil Terpercaya
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white leading-tight">
                <?php echo htmlspecialchars($front_title); ?>
            </h1>
            <p class="text-gray-300 text-sm sm:text-base max-w-xl">
                <?php echo htmlspecialchars($front_subtitle); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="<?php echo $front_cta_url !== '' ? htmlspecialchars($front_cta_url) : 'https://wa.me/' . htmlspecialchars($store_whatsapp); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-garage-600 hover:bg-garage-500 text-sm font-semibold text-white shadow-lg shadow-garage-600/40 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.5 3.5A11.5 11.5 0 0 0 3 17.6L2 22l4.5-1.2A11.4 11.4 0 0 0 12 21.5h.1A11.5 11.5 0 0 0 20.5 3.5Zm-8.4 15a9.4 9.4 0 0 1-4.8-1.3L7 17l-2.6.7.7-2.5-.2-.5a9.4 9.4 0 1 1 6.2 4.8ZM17 14.4c-.3-.1-1.7-.8-1.9-.9s-.4-.1-.6.1l-.9 1c-.1.1-.3.2-.6.1s-1.2-.4-2.3-1.5-1.4-1.9-1.5-2.1 0-.4.1-.5l.4-.5.3-.5c.1-.1 0-.3 0-.4L9.2 7c-.2-.4-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-1 2.2c0 1.3.9 2.5 1 2.6s1.8 2.7 4.4 3.7a14.5 14.5 0 0 0 1.5.5 3.6 3.6 0 0 0 1.7.1c.5-.1 1.7-.7 1.9-1.3s.2-1.1.2-1.2-.2-.1-.4-.2Z"/>
                    </svg>
                    <?php echo htmlspecialchars($front_cta_text); ?>
                </a>
                <a href="<?php echo site_url(); ?>/?page=Layanan" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full border border-white/20 text-sm font-semibold text-gray-100 hover:bg-white/10 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Lihat Paket Layanan
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs sm:text-sm mt-4">
                <div class="rounded-xl bg-slate-800/70 border border-white/10 p-3">
                    <p class="text-gray-400">Pengalaman</p>
                    <p class="text-lg font-bold text-white">10+ tahun</p>
                </div>
                <div class="rounded-xl bg-slate-800/70 border border-white/10 p-3">
                    <p class="text-gray-400">Kendaraan ditangani</p>
                    <p class="text-lg font-bold text-white">5.000+</p>
                </div>
                <div class="rounded-xl bg-slate-800/70 border border-white/10 p-3">
                    <p class="text-gray-400">Rating pelanggan</p>
                    <p class="text-lg font-bold text-white">4.9/5</p>
                </div>
                <div class="rounded-xl bg-slate-800/70 border border-white/10 p-3">
                    <p class="text-gray-400">Garansi servis</p>
                    <p class="text-lg font-bold text-white">30 hari</p>
                </div>
            </div>
        </div>
        <div class="lg:col-span-5 xl:col-span-4">
            <div class="relative rounded-3xl border border-white/10 bg-gradient-to-br from-slate-800 to-slate-900 p-5 sm:p-6 shadow-xl shadow-black/40 overflow-hidden">
                <div class="absolute -right-24 -top-24 h-64 w-64 bg-garage-600/30 rounded-full blur-3xl"></div>
                <div class="relative space-y-4">
                    <?php if ($front_hero_image): ?>
                    <div class="mb-4 rounded-2xl overflow-hidden border border-white/10">
                        <img src="<?php echo htmlspecialchars($front_hero_image); ?>" alt="<?php echo htmlspecialchars($front_title); ?>" class="w-full h-48 sm:h-56 object-cover">
                    </div>
                    <?php endif; ?>
                    <h2 class="text-sm font-semibold text-white tracking-wide uppercase">Layanan Unggulan</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-3">
                            <div class="mt-1">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900/80 border border-white/10">
                                    <svg class="w-4 h-4 text-garage-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Servis Berkala</p>
                                <p class="text-gray-300 text-xs sm:text-sm">
                                    Ganti oli, pengecekan rem, filter, dan tune up untuk menjaga performa mesin tetap maksimal.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="mt-1">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900/80 border border-white/10">
                                    <svg class="w-4 h-4 text-garage-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Diagnosa Komputer</p>
                                <p class="text-gray-300 text-xs sm:text-sm">
                                    Scanner OBD untuk mendeteksi masalah elektronik dan sensor secara akurat.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="mt-1">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900/80 border border-white/10">
                                    <svg class="w-4 h-4 text-garage-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">AC dan Kelistrikan</p>
                                <p class="text-gray-300 text-xs sm:text-sm">
                                    Servis AC, penggantian refrigerant, perbaikan kelistrikan lampu, aki, dan starter.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-white/10 mt-2 text-xs text-gray-300">
                        Gratis pengecekan 20 titik untuk setiap paket servis berkala.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $front_products = array();
    if (function_exists('orion_shop_get_products')) {
        $front_products = orion_shop_get_products(3);
    }
    if (!empty($front_products)) {
        ?>
        <section class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-garage-200">Produk dan Layanan</p>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Produk Toko Terbaru</h2>
                </div>
                <a href="<?php echo site_url('/?page=Shop'); ?>" class="hidden sm:inline-flex text-xs text-gray-300 hover:text-white">
                    Lihat semua produk
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <?php foreach ($front_products as $product): ?>
                    <?php
                    $price = function_exists('orion_shop_get_price') ? orion_shop_get_price($product->id) : (isset($product->price) ? (int)$product->price : 0);
                    $stock = function_exists('orion_shop_get_stock') ? orion_shop_get_stock($product->id) : '';
                    $price_label = function_exists('orion_shop_format_price') ? orion_shop_format_price($price) : $price;
                    $wa_url = function_exists('orion_shop_get_whatsapp_url') ? orion_shop_get_whatsapp_url($product->id) : ('https://wa.me/' . htmlspecialchars($store_whatsapp));
                    $thumb = isset($product->image) && $product->image !== '' ? $product->image : '';
                    $is_service = isset($product->type) && $product->type === 'service';
                    $cta_label = $is_service ? 'Booking via WhatsApp' : 'Beli via WhatsApp';
                    ?>
                    <article class="rounded-2xl bg-slate-900/60 border border-white/10 overflow-hidden flex flex-col">
                        <div class="relative w-full h-40 sm:h-44 md:h-48 bg-slate-800">
                            <?php if ($thumb): ?>
                                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="absolute inset-0 w-full h-full object-cover">
                            <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center text-gray-500 text-xs">
                                    Tidak ada gambar
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="text-sm font-semibold text-white mb-1 line-clamp-2"><?php echo htmlspecialchars($product->name); ?></h3>
                            <p class="text-xs text-gray-400 mb-1">
                                <?php echo isset($product->category) && $product->category !== '' ? htmlspecialchars($product->category) : ($is_service ? 'Layanan Bengkel' : 'Produk Bengkel'); ?>
                            </p>
                            <p class="text-sm font-bold text-garage-200 mb-1"><?php echo $price_label; ?></p>
                            <?php if ($stock !== ''): ?>
                                <p class="text-xs text-gray-300 mb-3">Stok: <?php echo htmlspecialchars($stock); ?></p>
                            <?php endif; ?>
                            <div class="mt-auto flex">
                                <a href="<?php echo $wa_url; ?>" target="_blank" class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-full bg-garage-600 hover:bg-garage-500 text-xs font-semibold text-white">
                                    <?php echo $cta_label; ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    $latest_posts = get_posts(array(
        'numberposts' => 3,
        'post_type' => 'post',
        'post_status' => 'publish'
    ));
    if ($latest_posts) {
        ?>
        <section class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-garage-200">Tips Perawatan Mobil</p>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Artikel Terbaru</h2>
                </div>
                <a href="<?php echo site_url(); ?>/?page=Berita" class="hidden sm:inline-flex text-xs text-gray-300 hover:text-white">
                    Lihat semua artikel
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <?php foreach ($latest_posts as $lp): ?>
                    <?php $thumb = get_the_post_thumbnail_url($lp->ID); ?>
                    <article class="bg-slate-900/60 border border-white/10 rounded-2xl overflow-hidden flex flex-col">
                        <div class="aspect-video bg-slate-800 relative">
                            <?php if ($thumb): ?>
                                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($lp->post_title); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-xs">
                                    Tidak ada gambar
                                </div>
                            <?php endif; ?>
                            <a href="?p=<?php echo $lp->ID; ?>" class="absolute inset-0"></a>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <p class="text-xs text-gray-400 mb-1"><?php echo date('d M Y', strtotime($lp->post_date)); ?></p>
                            <h3 class="text-sm font-semibold text-white mb-2 line-clamp-2">
                                <a href="?p=<?php echo $lp->ID; ?>" class="hover:text-garage-200">
                                    <?php echo htmlspecialchars($lp->post_title); ?>
                                </a>
                            </h3>
                            <p class="text-xs text-gray-300 mb-3 line-clamp-3">
                                <?php
                                $raw_excerpt = html_entity_decode($lp->post_content);
                                $plain_text = strip_tags($raw_excerpt);
                                if (function_exists('mb_substr')) {
                                    $excerpt = mb_substr($plain_text, 0, 120);
                                } else {
                                    $excerpt = substr($plain_text, 0, 120);
                                }
                                echo htmlspecialchars($excerpt . '...');
                                ?>
                            </p>
                            <a href="?p=<?php echo $lp->ID; ?>" class="mt-auto inline-flex items-center text-xs font-semibold text-garage-200 hover:text-garage-100">
                                Baca selengkapnya
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
?>

<?php get_footer(); ?>
