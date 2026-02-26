<?php

if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die('Orion CMS Core not found.');
    }
}

require_once ABSPATH . 'orion-admin/admin-header.php';

$doa_items = function_exists('orion_doa_get_all') ? orion_doa_get_all() : [];
$has_error = !is_array($doa_items) || count($doa_items) === 0;

?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Doa Harian</h1>
    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded">Orion Doa Harian Plugin</span>
</div>

<?php if ($has_error): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm">
        <p>Tidak dapat mengambil data doa dari API. Periksa koneksi internet server atau coba lagi nanti.</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow border border-gray-200 mb-6 p-4 flex flex-wrap items-center justify-between gap-3">
        <div class="text-sm text-gray-600">
            <p>Total <?php echo count($doa_items); ?> doa tersedia dari API open-api.my.id.</p>
            <p class="text-xs text-gray-400 mt-1">Data hanya dibaca (read-only) dari API, tidak disimpan di database.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Doa</h2>
            <span class="text-xs text-gray-400">Sumber: open-api.my.id/api/doa</span>
        </div>
        <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto">
            <?php foreach ($doa_items as $item): ?>
                <div class="px-4 py-4 hover:bg-slate-50 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-1">
                                Doa #<?php echo (int)$item['id']; ?>
                            </p>
                            <h3 class="text-base font-semibold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:border-r md:border-gray-100">
                            <p class="text-2xl leading-relaxed text-right text-gray-900">
                                <?php echo htmlspecialchars($item['arab'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>
                        <div>
                            <?php if (!empty($item['latin'])): ?>
                                <p class="text-xs text-gray-500 italic mb-2">
                                    <?php echo htmlspecialchars($item['latin'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-700">
                                <?php echo htmlspecialchars($item['terjemah'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

