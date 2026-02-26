<?php

$bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';

if (file_exists($bootstrap_path)) {
    require_once $bootstrap_path;
} else {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php';
    } else {
        die('Orion CMS Core not found.');
    }
}

if (!function_exists('is_user_logged_in') || !is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

global $orion_db, $table_prefix;

$table_quotes = $table_prefix . 'orion_construct_quotes';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_status' && isset($_POST['quote_id'], $_POST['status'])) {
        $quote_id = (int) $_POST['quote_id'];
        $allowed_status = array('masuk', 'dilaporkan', 'dibalas');
        $new_status = in_array($_POST['status'], $allowed_status, true) ? $_POST['status'] : 'masuk';

        $status_escaped = $orion_db->real_escape_string($new_status);
        $update_sql = "UPDATE $table_quotes SET status = '$status_escaped', updated_at = NOW() WHERE id = $quote_id";
        $orion_db->query($update_sql);

        header('Location: manage-quotes.php?message=status_updated');
        exit;
    }
}

if (isset($_GET['message']) && $_GET['message'] === 'status_updated') {
    $message = 'Status quotation berhasil diperbarui.';
}

$status_labels = array(
    'masuk' => 'Masuk',
    'dilaporkan' => 'Dilaporkan manajemen',
    'dibalas' => 'Dibalas'
);

$status_filter = '';
if (isset($_GET['status']) && array_key_exists($_GET['status'], $status_labels)) {
    $status_filter = $_GET['status'];
}

$quotes = array();

if (isset($orion_db, $table_prefix)) {
    $where = '';
    if ($status_filter !== '') {
        $status_escaped = $orion_db->real_escape_string($status_filter);
        $where = "WHERE status = '$status_escaped'";
    }

    $sql = "SELECT * FROM $table_quotes $where ORDER BY created_at DESC";
    if ($result = $orion_db->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $quotes[] = $row;
        }
        $result->free();
    }
}

require_once ABSPATH . 'orion-admin/admin-header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Quotation</h1>
    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded">Orion Construction Theme</span>
</div>

<?php if ($message): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 text-sm">
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow border border-gray-200 mb-6 p-4 flex flex-wrap items-center justify-between gap-3">
    <div class="text-sm text-gray-600">
        Halaman ini menampung semua permintaan penawaran yang masuk dari formulir di tema Orion Construction.
    </div>
    <div class="flex items-center gap-2 text-xs">
        <span class="text-gray-500">Filter status:</span>
        <a href="manage-quotes.php" class="px-2.5 py-1 rounded-full border text-xs <?php echo $status_filter === '' ? 'bg-slate-900 text-white border-slate-900' : 'border-slate-300 text-slate-700 hover:bg-slate-50'; ?>">Semua</a>
        <?php foreach ($status_labels as $key => $label): ?>
            <a href="manage-quotes.php?status=<?php echo urlencode($key); ?>" class="px-2.5 py-1 rounded-full border text-xs <?php echo $status_filter === $key ? 'bg-slate-900 text-white border-slate-900' : 'border-slate-300 text-slate-700 hover:bg-slate-50'; ?>">
                <?php echo htmlspecialchars($label); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
        <div class="text-sm font-semibold text-gray-800">Daftar Quotation</div>
        <div class="text-xs text-gray-500">
            Total: <?php echo count($quotes); ?> permintaan
        </div>
    </div>

    <?php if (empty($quotes)): ?>
        <div class="p-6 text-sm text-gray-500">
            Belum ada permintaan penawaran yang tersimpan.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Waktu</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Pemohon</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Kontak</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Ringkasan Proyek</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($quotes as $q): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 align-top text-xs text-gray-600">
                                <?php echo htmlspecialchars($q['created_at']); ?>
                            </td>
                            <td class="px-4 py-2 align-top">
                                <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($q['name']); ?></div>
                                <?php if (!empty($q['company'])): ?>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($q['company']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 align-top text-xs text-gray-700">
                                <?php if (!empty($q['email'])): ?>
                                    <div>Email: <a href="mailto:<?php echo htmlspecialchars($q['email']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($q['email']); ?></a></div>
                                <?php endif; ?>
                                <?php if (!empty($q['phone'])): ?>
                                    <div>Telepon: <span class="font-medium"><?php echo htmlspecialchars($q['phone']); ?></span></div>
                                <?php endif; ?>
                                <?php if (!empty($q['location'])): ?>
                                    <div>Lokasi: <?php echo htmlspecialchars($q['location']); ?></div>
                                <?php endif; ?>
                                <?php
                                $channel_label = '';
                                if (!empty($q['contact_channel'])) {
                                    if ($q['contact_channel'] === 'telpon') {
                                        $channel_label = 'Telepon';
                                    } elseif ($q['contact_channel'] === 'whatsapp') {
                                        $channel_label = 'WhatsApp';
                                    } elseif ($q['contact_channel'] === 'email') {
                                        $channel_label = 'Email';
                                    } elseif ($q['contact_channel'] === 'kunjungan') {
                                        $channel_label = 'Kunjungan langsung';
                                    }
                                }
                                ?>
                                <?php if ($channel_label !== ''): ?>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-medium">
                                            Preferensi kontak: <?php echo htmlspecialchars($channel_label); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 align-top text-xs text-gray-700 max-w-sm">
                                <?php if (!empty($q['project_type']) || !empty($q['budget'])): ?>
                                    <div class="mb-1 text-gray-600">
                                        <?php if (!empty($q['project_type'])): ?>
                                            <span class="font-semibold"><?php echo htmlspecialchars($q['project_type']); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($q['budget'])): ?>
                                            <span class="ml-1 text-gray-500">· Budget: <?php echo htmlspecialchars($q['budget']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($q['message'])): ?>
                                    <div class="text-gray-700 whitespace-pre-line">
                                        <?php echo nl2br(htmlspecialchars(mb_strimwidth($q['message'], 0, 220, '...'))); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($q['document_path'])): ?>
                                    <div class="mt-2 text-xs">
                                        <a href="<?php echo site_url('/' . ltrim($q['document_path'], '/')); ?>" target="_blank" class="inline-flex items-center px-2 py-0.5 rounded bg-slate-900 text-white hover:bg-slate-800">
                                            Lihat dokumen (PDF)
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 align-top">
                                <?php
                                $status_key = isset($q['status']) ? $q['status'] : 'masuk';
                                $label = isset($status_labels[$status_key]) ? $status_labels[$status_key] : $status_key;
                                $badge_classes = 'bg-slate-100 text-slate-800';
                                if ($status_key === 'masuk') {
                                    $badge_classes = 'bg-amber-100 text-amber-800';
                                } elseif ($status_key === 'dilaporkan') {
                                    $badge_classes = 'bg-sky-100 text-sky-800';
                                } elseif ($status_key === 'dibalas') {
                                    $badge_classes = 'bg-emerald-100 text-emerald-800';
                                }
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo $badge_classes; ?>">
                                    <?php echo htmlspecialchars($label); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 align-top text-right">
                                <form method="POST" class="inline-flex items-center gap-1">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="quote_id" value="<?php echo (int) $q['id']; ?>">
                                    <select name="status" class="border-gray-300 rounded-md text-xs px-2 py-1 focus:ring-blue-500 focus:border-blue-500">
                                        <?php foreach ($status_labels as $key => $label): ?>
                                            <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $status_key === $key ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="ml-1 inline-flex items-center px-2 py-1 rounded-md bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once ABSPATH . 'orion-admin/admin-footer.php'; ?>
