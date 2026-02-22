<?php

if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die('Orion CMS Core not found.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = isset($_POST['donorbox_url']) ? trim($_POST['donorbox_url']) : '';
    if (function_exists('orion_donorbox_set_campaign_url')) {
        orion_donorbox_set_campaign_url($url);
    } else {
        $options = array('donorbox_embed_campaign_id' => $url);
        update_option('donorbox_embed_campaign_options', $options);
    }
    header('Location: ' . site_url('/orion-content/plugins/donorbox/settings.php?updated=1'));
    exit;
}

require_once ABSPATH . 'orion-admin/admin-header.php';

$options = function_exists('orion_donorbox_get_options')
    ? orion_donorbox_get_options()
    : get_option('donorbox_embed_campaign_options', array());

$current_url = '';
if (is_array($options) && isset($options['donorbox_embed_campaign_id'])) {
    $current_url = $options['donorbox_embed_campaign_id'];
}

$updated = isset($_GET['updated']) && $_GET['updated'] == '1';
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800 mb-1">Donorbox Settings</h1>
        <p class="text-slate-500 text-sm">
            Integrasi Donorbox dengan Orion CMS. Gunakan shortcode <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs">[donate]</span>
            atau <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs">[donate-with-info]</span> di konten.
        </p>
    </div>

    <?php if ($updated): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 text-sm">
            Pengaturan Donorbox berhasil disimpan.
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="post" action="">
            <div class="mb-4">
                <label for="donorbox_url" class="block text-sm font-medium text-slate-700 mb-1">
                    Donorbox Campaign URL
                </label>
                <input
                    type="text"
                    id="donorbox_url"
                    name="donorbox_url"
                    value="<?php echo htmlspecialchars($current_url, ENT_QUOTES, 'UTF-8'); ?>"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orion-500 focus:border-orion-500 text-sm"
                    placeholder="https://donorbox.org/nama-campaign-anda"
                >
                <p class="text-xs text-slate-500 mt-2">
                    Anda bisa menempelkan URL lengkap seperti <span class="font-mono">https://donorbox.org/masjid-orion</span>
                    atau variasi dengan parameter query.
                </p>
            </div>

            <div class="mb-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-2">Cara pakai di konten</h2>
                <ul class="list-disc list-inside text-xs text-slate-500 space-y-1">
                    <li>Gunakan <span class="font-mono bg-slate-100 px-1 py-0.5 rounded">[donate]</span> untuk menampilkan form Donorbox standar.</li>
                    <li>Gunakan <span class="font-mono bg-slate-100 px-1 py-0.5 rounded">[donate-with-info]</span> untuk menampilkan form beserta informasi campaign.</li>
                    <li>Anda juga bisa override URL per halaman: <span class="font-mono bg-slate-100 px-1 py-0.5 rounded">[donate url="https://donorbox.org/campaign-lain"]</span>.</li>
                </ul>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-orion-600 hover:bg-orion-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors"
                >
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once ABSPATH . 'orion-admin/admin-footer.php'; ?>

