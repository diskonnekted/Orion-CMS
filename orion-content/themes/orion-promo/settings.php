<?php
$news_per_page = (int) get_option('orion_promo_news_per_page', 6);
if ($news_per_page < 1) {
    $news_per_page = 6;
}
if ($news_per_page > 20) {
    $news_per_page = 20;
}

$news_layout = get_option('orion_promo_news_layout', 'grid');
if (!in_array($news_layout, array('grid', 'featured_1_2'))) {
    $news_layout = 'grid';
}
?>

<div class="space-y-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Pengaturan Berita</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_promo_news_per_page">Jumlah berita per halaman</label>
                <input
                    class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                    id="orion_promo_news_per_page"
                    type="number"
                    name="orion_promo_news_per_page"
                    value="<?php echo htmlspecialchars($news_per_page); ?>"
                    min="1"
                    max="20"
                >
                <p class="text-slate-500 text-xs italic mt-1">Atur berapa banyak berita yang tampil per halaman (maksimal 20).</p>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2">Layout kartu berita</label>
                <div class="space-y-2">
                    <label class="flex items-center space-x-3">
                        <input
                            type="radio"
                            name="orion_promo_news_layout"
                            value="grid"
                            <?php echo $news_layout === 'grid' ? 'checked' : ''; ?>
                        >
                        <span class="text-slate-700 text-sm">Grid rata (semua kartu ukuran sama)</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input
                            type="radio"
                            name="orion_promo_news_layout"
                            value="featured_1_2"
                            <?php echo $news_layout === 'featured_1_2' ? 'checked' : ''; ?>
                        >
                        <span class="text-slate-700 text-sm">1 kartu besar pertama, 2 kartu di baris berikutnya, lalu grid biasa</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

