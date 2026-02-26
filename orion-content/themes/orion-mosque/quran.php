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

$quran_surah = isset($_GET['quran_sura']) ? (int)$_GET['quran_sura'] : 1;
if ($quran_surah < 1) {
    $quran_surah = 1;
}
if ($quran_surah > 114) {
    $quran_surah = 114;
}
$quran_list = [];
$quran_data = null;
$quran_source_label = 'API SantriKoding (Bahasa Indonesia)';

if (function_exists('orion_quran_get_surah_list') && function_exists('orion_quran_get_surah')) {
    $quran_list = orion_quran_get_surah_list();
    $quran_data = orion_quran_get_surah($quran_surah);
} else {
    $quran_list = function_exists('orion_mosque_quran_get_surah_list') ? orion_mosque_quran_get_surah_list() : [];
    $quran_data = function_exists('orion_mosque_quran_get_surah') ? orion_mosque_quran_get_surah($quran_surah, 'indonesian') : null;
    $quran_source_label = 'berkas teks lokal (fallback)';
}
?>

<section class="bg-mosque-50 py-16 border-b border-mosque-100/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-2">Al-Qur'an</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Baca Al-Qur'an</h1>
                <p class="text-sm text-slate-500 mt-2">Pilih surat untuk membaca teks Arab dan terjemahan Bahasa Indonesia.</p>
            </div>
        </div>
        <form method="get" action="" class="bg-white rounded-2xl shadow-sm border border-mosque-100 p-4 sm:p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Surat</label>
                    <select name="quran_sura" class="w-full rounded-xl border border-mosque-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mosque-500 focus:border-mosque-500 bg-white">
                        <?php foreach ($quran_list as $item): ?>
                            <option value="<?php echo (int)$item['index']; ?>" <?php echo $item['index'] === $quran_surah ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex md:justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-mosque-600 text-white text-sm font-semibold shadow-md hover:bg-mosque-500 transition w-full md:w-auto">
                        Tampilkan Surat
                    </button>
                </div>
            </div>
        </form>
        <?php if (is_array($quran_data) && !empty($quran_data['arabic'])): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-mosque-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-mosque-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-1">Surat</p>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                            <?php echo htmlspecialchars($quran_data['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </h2>
                    </div>
                    <p class="text-xs text-slate-500">
                        Menampilkan teks Arab dan terjemahan Bahasa Indonesia via <?php echo htmlspecialchars($quran_source_label, ENT_QUOTES, 'UTF-8'); ?>.
                    </p>
                </div>
                <div class="max-h-[32rem] overflow-y-auto divide-y divide-mosque-50">
                    <?php foreach ($quran_data['arabic'] as $idx => $arabic_line): ?>
                        <?php
                        $aya_num = $idx + 1;
                        $arabic_text = trim($arabic_line);
                        $trans_text = isset($quran_data['translation'][$idx]) ? trim($quran_data['translation'][$idx]) : '';
                        ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                            <div class="px-5 py-4 md:border-r md:border-mosque-50 bg-mosque-50/40">
                                <div class="flex items-baseline justify-between gap-3 mb-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-mosque-600 text-white text-xs font-semibold">
                                        <?php echo $aya_num; ?>
                                    </span>
                                </div>
                                <p class="text-mosque-900 text-xl leading-relaxed text-right">
                                    <?php echo htmlspecialchars($arabic_text, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                            <div class="px-5 py-4 bg-white">
                                <p class="text-sm text-slate-700 leading-relaxed">
                                    <?php echo htmlspecialchars($trans_text, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border border-mosque-100 p-8 text-center text-sm text-slate-500">
                Data Al-Qur'an belum tersedia atau terjadi kendala saat membaca berkas teks.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
