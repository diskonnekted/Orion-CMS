<?php
require_once '../orion-load.php';

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

function orion_admin_coba_gratis_path() {
    return ABSPATH . 'orion-content/uploads/coba-gratis/submissions.jsonl';
}

function orion_admin_coba_gratis_read_entries($max_lines = 5000) {
    $path = orion_admin_coba_gratis_path();
    if (!file_exists($path)) return array();
    $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!is_array($lines)) return array();
    if ($max_lines > 0 && count($lines) > $max_lines) {
        $lines = array_slice($lines, -$max_lines);
    }
    $out = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') continue;
        $row = json_decode($line, true);
        if (!is_array($row)) continue;
        $out[] = $row;
    }
    $out = array_reverse($out);
    return $out;
}

function orion_admin_coba_gratis_matches($entry, $q) {
    if ($q === '') return true;
    $hay = array();
    $fields = array('id', 'submitted_at', 'nama', 'email', 'whatsapp', 'subdomain', 'domain', 'jenis_web', 'tema', 'catatan');
    foreach ($fields as $f) {
        if (isset($entry[$f]) && $entry[$f] !== '') $hay[] = (string) $entry[$f];
    }
    $haystack = strtolower(implode(' | ', $hay));
    return strpos($haystack, strtolower($q)) !== false;
}

function orion_admin_coba_gratis_csv($rows) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="coba-gratis-submissions.csv"');
    echo "\xEF\xBB\xBF";
    $out = fopen('php://output', 'wb');
    $headers = array('submitted_at', 'id', 'subdomain', 'domain', 'nama', 'email', 'whatsapp', 'jenis_web', 'tema', 'catatan', 'ip', 'user_agent');
    fputcsv($out, $headers);
    foreach ($rows as $r) {
        $line = array();
        foreach ($headers as $h) {
            $line[] = isset($r[$h]) ? (string) $r[$h] : '';
        }
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 20;
if ($per_page < 10) $per_page = 10;
if ($per_page > 100) $per_page = 100;
$paged = isset($_GET['paged']) ? (int) $_GET['paged'] : 1;
if ($paged < 1) $paged = 1;

$entries = orion_admin_coba_gratis_read_entries(5000);
$filtered = array();
foreach ($entries as $e) {
    if (orion_admin_coba_gratis_matches($e, $q)) $filtered[] = $e;
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    orion_admin_coba_gratis_csv($filtered);
}

$total = count($filtered);
$total_pages = $per_page > 0 ? (int) ceil($total / $per_page) : 1;
if ($total_pages < 1) $total_pages = 1;
if ($paged > $total_pages) $paged = $total_pages;
$offset = ($paged - 1) * $per_page;
$page_rows = array_slice($filtered, $offset, $per_page);

function orion_admin_coba_gratis_url($params = array()) {
    $base = site_url('/orion-admin/coba-gratis-submissions.php');
    $cur = array();
    if (isset($_GET['q']) && trim((string) $_GET['q']) !== '') $cur['q'] = trim((string) $_GET['q']);
    if (isset($_GET['per_page']) && (int) $_GET['per_page'] > 0) $cur['per_page'] = (int) $_GET['per_page'];
    if (isset($_GET['paged']) && (int) $_GET['paged'] > 0) $cur['paged'] = (int) $_GET['paged'];
    foreach ($params as $k => $v) {
        if ($v === null || $v === '') unset($cur[$k]);
        else $cur[$k] = $v;
    }
    if (empty($cur)) return $base;
    return $base . '?' . http_build_query($cur);
}

include 'admin-header.php';
?>

<div class="max-w-7xl">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Coba Gratis Submissions</h1>
            <p class="text-slate-500 mt-1">Daftar permintaan website gratis dari halaman Coba Gratis.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo htmlspecialchars(orion_admin_coba_gratis_url(array('export' => 'csv', 'paged' => 1))); ?>" class="px-4 py-2 rounded-lg bg-orion-600 hover:bg-orion-700 text-white font-bold">Export CSV</a>
            <a href="<?php echo htmlspecialchars(site_url('/?page=coba-gratis')); ?>" class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">Buka Halaman</a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-6">
                <label class="block text-sm font-bold text-slate-700 mb-1">Search</label>
                <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Cari nama, email, subdomain, tema, catatan...">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-slate-700 mb-1">Per halaman</label>
                <select name="per_page" class="w-full p-3 rounded-xl border border-slate-200 bg-white">
                    <?php foreach (array(10, 20, 50, 100) as $n): ?>
                        <option value="<?php echo (int) $n; ?>" <?php echo $per_page === (int) $n ? 'selected' : ''; ?>><?php echo (int) $n; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button class="flex-1 px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold">Terapkan</button>
                <a href="<?php echo htmlspecialchars(site_url('/orion-admin/coba-gratis-submissions.php')); ?>" class="px-5 py-3 rounded-xl bg-white border border-slate-200 font-extrabold text-slate-900 hover:bg-slate-50">Reset</a>
            </div>
        </form>

        <div class="mt-4 text-sm text-slate-600">
            Menampilkan <span class="font-extrabold text-slate-900"><?php echo count($page_rows); ?></span> dari <span class="font-extrabold text-slate-900"><?php echo $total; ?></span> entry
            <?php if ($q !== ''): ?> untuk pencarian <span class="font-mono text-slate-900"><?php echo htmlspecialchars($q); ?></span><?php endif; ?>.
        </div>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left text-slate-700">
                        <th class="px-4 py-3 font-extrabold">Waktu</th>
                        <th class="px-4 py-3 font-extrabold">Nama</th>
                        <th class="px-4 py-3 font-extrabold">Subdomain</th>
                        <th class="px-4 py-3 font-extrabold">Jenis</th>
                        <th class="px-4 py-3 font-extrabold">Tema</th>
                        <th class="px-4 py-3 font-extrabold">Kontak</th>
                        <th class="px-4 py-3 font-extrabold">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($page_rows)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada entry.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($page_rows as $r): ?>
                            <?php
                            $submitted = isset($r['submitted_at']) ? (string) $r['submitted_at'] : '';
                            $nama = isset($r['nama']) ? (string) $r['nama'] : '';
                            $sub = isset($r['subdomain']) ? (string) $r['subdomain'] : '';
                            $domain = isset($r['domain']) ? (string) $r['domain'] : ($sub ? ($sub . '.orionversa.com') : '');
                            $jenis = isset($r['jenis_web']) ? (string) $r['jenis_web'] : '';
                            $tema = isset($r['tema']) ? (string) $r['tema'] : '';
                            $email = isset($r['email']) ? (string) $r['email'] : '';
                            $wa = isset($r['whatsapp']) ? (string) $r['whatsapp'] : '';
                            $catatan = isset($r['catatan']) ? (string) $r['catatan'] : '';
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-600 font-mono text-xs"><?php echo htmlspecialchars($submitted); ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-extrabold text-slate-900"><?php echo htmlspecialchars($nama ?: '-'); ?></div>
                                    <?php if (isset($r['id']) && $r['id'] !== ''): ?>
                                        <div class="text-xs text-slate-500 font-mono"><?php echo htmlspecialchars((string) $r['id']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-mono text-slate-900 text-xs"><?php echo htmlspecialchars($domain ?: '-'); ?></div>
                                </td>
                                <td class="px-4 py-3 text-slate-700"><?php echo htmlspecialchars($jenis ?: '-'); ?></td>
                                <td class="px-4 py-3 text-slate-700 font-mono text-xs"><?php echo htmlspecialchars($tema ?: '-'); ?></td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-700"><?php echo htmlspecialchars($email ?: '-'); ?></div>
                                    <div class="text-slate-500 font-mono text-xs"><?php echo htmlspecialchars($wa ?: ''); ?></div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 max-w-[28rem]">
                                    <div class="line-clamp-3"><?php echo htmlspecialchars($catatan ?: '-'); ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-t border-slate-200 bg-white">
            <div class="text-sm text-slate-600">
                Page <span class="font-extrabold text-slate-900"><?php echo (int) $paged; ?></span> / <span class="font-extrabold text-slate-900"><?php echo (int) $total_pages; ?></span>
            </div>
            <div class="flex gap-2">
                <?php if ($paged > 1): ?>
                    <a class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50" href="<?php echo htmlspecialchars(orion_admin_coba_gratis_url(array('paged' => $paged - 1))); ?>">Prev</a>
                <?php endif; ?>
                <?php if ($paged < $total_pages): ?>
                    <a class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50" href="<?php echo htmlspecialchars(orion_admin_coba_gratis_url(array('paged' => $paged + 1))); ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mt-4 text-xs text-slate-500">
        Sumber data: <span class="font-mono"><?php echo htmlspecialchars(orion_admin_coba_gratis_path()); ?></span> (ditampilkan maksimal 5000 entry terbaru).
    </div>
</div>

<?php include 'admin-footer.php'; ?>

