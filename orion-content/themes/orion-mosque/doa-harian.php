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

$doa_items = function_exists('orion_doa_get_all') ? orion_doa_get_all() : [];
$has_error = !is_array($doa_items) || count($doa_items) === 0;
?>

<section class="bg-mosque-50 py-16 border-b border-mosque-100/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-2">Dzikir & Doa</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Doa Harian</h1>
                <p class="text-sm text-slate-500 mt-2">
                    Kumpulan doa harian lengkap dengan teks Arab, latin, dan terjemahan Bahasa Indonesia.
                </p>
            </div>
        </div>

        <?php if ($has_error): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-8 text-center text-sm text-red-600">
                Tidak dapat mengambil data doa dari API. Silakan periksa koneksi internet server atau coba beberapa saat lagi.
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border border-mosque-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-mosque-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.2em] text-hunter uppercase mb-1">Sumber</p>
                        <p class="text-xs text-slate-500">
                            Data doa diambil secara langsung dari open-api.my.id/api/doa.
                        </p>
                    </div>
                    <p class="text-xs text-slate-500">
                        Total <?php echo count($doa_items); ?> doa harian tersedia.
                    </p>
                </div>
                <div class="max-h-[36rem] overflow-y-auto divide-y divide-mosque-50">
                    <?php foreach ($doa_items as $item): ?>
                        <article class="px-5 py-5 hover:bg-mosque-50/40 transition">
                            <header class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <p class="text-[11px] font-semibold text-hunter uppercase tracking-[0.22em] mb-1">
                                        Doa #<?php echo (int)$item['id']; ?>
                                    </p>
                                    <h2 class="text-base sm:text-lg font-semibold text-slate-900">
                                        <?php echo htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h2>
                                </div>
                            </header>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:border-r md:border-mosque-50">
                                    <p class="text-2xl leading-relaxed text-right text-slate-900">
                                        <?php echo htmlspecialchars($item['arab'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                </div>
                                <div>
                                    <?php if (!empty($item['latin'])): ?>
                                        <p class="text-xs text-slate-500 italic mb-2">
                                            <?php echo htmlspecialchars($item['latin'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="text-sm text-slate-700 leading-relaxed">
                                        <?php echo htmlspecialchars($item['terjemah'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>

