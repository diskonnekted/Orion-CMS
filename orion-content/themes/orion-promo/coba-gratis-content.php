<?php
function orion_promo_try_free_clean_text($value) {
    $value = trim((string) $value);
    $value = preg_replace('/\s+/', ' ', $value);
    return $value;
}

function orion_promo_try_free_clean_email($value) {
    $value = trim((string) $value);
    if ($value === '') return '';
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
}

function orion_promo_try_free_clean_phone($value) {
    $value = preg_replace('/[^0-9+]/', '', (string) $value);
    $value = trim($value);
    return $value;
}

function orion_promo_try_free_clean_subdomain($value) {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9-]/', '', $value);
    $value = preg_replace('/-+/', '-', $value);
    $value = trim($value, '-');
    return $value;
}

function orion_promo_try_free_theme_options() {
    $themes_dir = ABSPATH . 'orion-content/themes/';
    $themes = array();
    if (is_dir($themes_dir) && ($dh = opendir($themes_dir))) {
        while (($file = readdir($dh)) !== false) {
            if ($file === '.' || $file === '..') continue;
            if (!is_dir($themes_dir . $file)) continue;
            $style_css = $themes_dir . $file . '/style.css';
            if (!file_exists($style_css)) continue;
            $css_data = file_get_contents($style_css);
            preg_match('/Theme Name:(.*)$/m', $css_data, $name_match);
            $name = isset($name_match[1]) ? trim($name_match[1]) : $file;
            $themes[$file] = $name;
        }
        closedir($dh);
    }
    ksort($themes, SORT_NATURAL | SORT_FLAG_CASE);
    return $themes;
}

$errors = array();
$success = false;
$saved_id = '';

$jenis_web_options = array(
    'landing' => 'Landing Page / Promo',
    'company' => 'Company Profile',
    'shop' => 'Toko Online / Katalog',
    'school' => 'Sekolah / Pendidikan',
    'blog' => 'Blog / Magazine',
    'government' => 'Instansi / Organisasi',
    'portfolio' => 'Portfolio',
    'other' => 'Lainnya'
);

$themes = orion_promo_try_free_theme_options();

$form = array(
    'nama' => '',
    'email' => '',
    'whatsapp' => '',
    'subdomain' => '',
    'jenis_web' => 'landing',
    'tema' => 'orion-promo',
    'catatan' => '',
    'setuju' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $honeypot = isset($_POST['website']) ? trim((string) $_POST['website']) : '';
    if ($honeypot !== '') {
        $errors['global'] = 'Gagal memproses permintaan.';
    } else {
        $form['nama'] = orion_promo_try_free_clean_text(isset($_POST['nama']) ? $_POST['nama'] : '');
        $form['email'] = orion_promo_try_free_clean_email(isset($_POST['email']) ? $_POST['email'] : '');
        $form['whatsapp'] = orion_promo_try_free_clean_phone(isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '');
        $form['subdomain'] = orion_promo_try_free_clean_subdomain(isset($_POST['subdomain']) ? $_POST['subdomain'] : '');
        $form['jenis_web'] = isset($_POST['jenis_web']) ? (string) $_POST['jenis_web'] : '';
        $form['tema'] = isset($_POST['tema']) ? (string) $_POST['tema'] : '';
        $form['catatan'] = trim((string) (isset($_POST['catatan']) ? $_POST['catatan'] : ''));
        $form['setuju'] = isset($_POST['setuju']) ? (string) $_POST['setuju'] : '';

        if ($form['nama'] === '') $errors['nama'] = 'Nama pemohon wajib diisi.';
        if ($form['email'] === '' && trim((string) (isset($_POST['email']) ? $_POST['email'] : '')) !== '') $errors['email'] = 'Email tidak valid.';
        if (!isset($jenis_web_options[$form['jenis_web']])) $errors['jenis_web'] = 'Jenis web tidak valid.';
        if (!isset($themes[$form['tema']])) $errors['tema'] = 'Tema tidak valid.';

        $reserved = array('www', 'admin', 'mail', 'ftp', 'api');
        if ($form['subdomain'] === '') {
            $errors['subdomain'] = 'Subdomain wajib diisi.';
        } elseif (strlen($form['subdomain']) < 3 || strlen($form['subdomain']) > 30) {
            $errors['subdomain'] = 'Subdomain minimal 3 dan maksimal 30 karakter.';
        } elseif (in_array($form['subdomain'], $reserved, true)) {
            $errors['subdomain'] = 'Subdomain tidak boleh menggunakan nama umum (mis. www/admin/mail).';
        }

        if ($form['setuju'] !== '1') $errors['setuju'] = 'Anda harus menyetujui terms untuk melanjutkan.';

        if (empty($errors)) {
            $dir = ABSPATH . 'orion-content/uploads/coba-gratis/';
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $index_path = $dir . 'index.html';
            if (!file_exists($index_path)) {
                @file_put_contents($index_path, '');
            }
            $file = $dir . 'submissions.jsonl';

            $saved_id = 'cg_' . date('Ymd_His') . '_' . substr(bin2hex(random_bytes(8)), 0, 12);
            $entry = array(
                'id' => $saved_id,
                'submitted_at' => date('c'),
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '',
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '',
                'nama' => $form['nama'],
                'email' => $form['email'],
                'whatsapp' => $form['whatsapp'],
                'subdomain' => $form['subdomain'],
                'domain' => $form['subdomain'] . '.orionversa.com',
                'jenis_web' => $form['jenis_web'],
                'tema' => $form['tema'],
                'catatan' => $form['catatan']
            );

            $fp = @fopen($file, 'ab');
            if ($fp) {
                if (function_exists('flock')) @flock($fp, LOCK_EX);
                @fwrite($fp, json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
                if (function_exists('flock')) @flock($fp, LOCK_UN);
                @fclose($fp);
                $success = true;
            } else {
                $errors['global'] = 'Gagal menyimpan permintaan. Silakan coba lagi.';
            }
        }
    }
}
?>

<section class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-blue-50 relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 -mr-24 -mt-24 w-[28rem] h-[28rem] bg-brand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute bottom-0 left-0 -ml-24 -mb-24 w-[28rem] h-[28rem] bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-25"></div>
        <img src="<?php echo esc_url(site_url('/assets/img/hero-bg.svg')); ?>" alt="Pattern" class="absolute inset-0 w-full h-full object-cover opacity-40">
    </div>

    <div class="container mx-auto px-6 relative z-10 py-16 md:py-20">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur border border-white/50 text-slate-800 text-sm font-extrabold shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-brand-600"></span>
                        Coba Gratis
                    </div>
                    <h1 class="mt-5 text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Website Gratis <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-700 to-purple-700">siap pakai</span>
                    </h1>
                    <p class="mt-4 text-slate-600 text-lg leading-relaxed max-w-2xl">
                        Minta website gratis dengan subdomain <span class="font-mono text-slate-900">*.orionversa.com</span>. Pilih jenis web dan tema, lalu kirim permintaan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo esc_url(site_url('/?page=download')); ?>" class="px-4 py-2 rounded-full bg-white/80 border border-white/60 text-slate-700 font-bold text-sm hover:bg-white transition">Download</a>
                    <a href="<?php echo esc_url(site_url('/?page=documentation')); ?>" class="px-4 py-2 rounded-full bg-white/80 border border-white/60 text-slate-700 font-bold text-sm hover:bg-white transition">Dokumentasi</a>
                    <a href="<?php echo esc_url(site_url('/')); ?>" class="px-4 py-2 rounded-full bg-white/80 border border-white/60 text-slate-700 font-bold text-sm hover:bg-white transition">Beranda</a>
                </div>
            </div>

            <?php if (isset($errors['global'])): ?>
                <div class="mt-8 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 font-bold"><?php echo htmlspecialchars($errors['global']); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mt-8 bg-white/90 backdrop-blur border border-green-200 rounded-3xl p-8 shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <div class="text-xs font-extrabold uppercase tracking-widest text-green-700">Berhasil</div>
                            <div class="mt-2 text-2xl font-extrabold text-slate-900">Permintaan terkirim</div>
                            <div class="mt-3 text-slate-600">ID: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($saved_id); ?></span></div>
                            <div class="mt-1 text-slate-600">Subdomain: <span class="font-mono text-slate-900"><?php echo htmlspecialchars($form['subdomain']); ?>.orionversa.com</span></div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?php echo esc_url(site_url('/?page=coba-gratis')); ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold transition shadow-xl shadow-brand-500/20">Kirim Lagi</a>
                            <a href="<?php echo esc_url(site_url('/')); ?>" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-800 font-extrabold hover:bg-slate-50 transition">Kembali</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white/80 backdrop-blur border border-white/60 rounded-3xl p-7 shadow-sm">
                        <div class="text-sm font-extrabold text-slate-900">Cara kerja singkat</div>
                        <div class="mt-4 space-y-3">
                            <div class="flex gap-3">
                                <div class="h-9 w-9 rounded-2xl bg-brand-600 text-white font-extrabold flex items-center justify-center flex-shrink-0">1</div>
                                <div>
                                    <div class="font-bold text-slate-800">Isi data & pilih tema</div>
                                    <div class="text-sm text-slate-600">Tentukan subdomain, jenis web, dan tema yang Anda mau.</div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="h-9 w-9 rounded-2xl bg-purple-600 text-white font-extrabold flex items-center justify-center flex-shrink-0">2</div>
                                <div>
                                    <div class="font-bold text-slate-800">Kami proses</div>
                                    <div class="text-sm text-slate-600">Permintaan akan dicatat untuk pembuatan website gratis.</div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="h-9 w-9 rounded-2xl bg-slate-900 text-white font-extrabold flex items-center justify-center flex-shrink-0">3</div>
                                <div>
                                    <div class="font-bold text-slate-800">Website siap dipakai</div>
                                    <div class="text-sm text-slate-600">Website bisa digunakan apa adanya, atau upgrade untuk penyesuaian.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white/80 backdrop-blur border border-white/60 rounded-3xl p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="font-extrabold text-slate-900">Tanpa biaya</div>
                            </div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Untuk mulai mencoba, cukup gunakan subdomain orionversa.com.</div>
                        </div>
                        <div class="bg-white/80 backdrop-blur border border-white/60 rounded-3xl p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2v1c0 1.105 1.343 2 3 2s3 .895 3 2v1c0 1.105-1.343 2-3 2m0-12v2m0 10v2"></path></svg>
                                </div>
                                <div class="font-extrabold text-slate-900">Upgrade kapan saja</div>
                            </div>
                            <div class="mt-3 text-sm text-slate-600 leading-relaxed">Jika butuh edit desain/fitur, Anda bisa naik ke layanan berbayar.</div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur border border-white/60 rounded-3xl p-7 shadow-sm">
                        <div class="text-sm font-extrabold text-slate-900">Preview URL</div>
                        <?php $preview_sub = $form['subdomain'] !== '' ? $form['subdomain'] : 'namaanda'; ?>
                        <div class="mt-3 p-4 rounded-2xl bg-slate-900 text-white font-mono text-sm break-all"><?php echo htmlspecialchars($preview_sub); ?>.orionversa.com</div>
                        <div class="mt-3 text-xs text-slate-500">Ini hanya preview. Ketersediaan subdomain akan diproses setelah Anda kirim permintaan.</div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-900/5 overflow-hidden">
                        <div class="p-7 md:p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                            <div class="flex items-start justify-between gap-6">
                                <div>
                                    <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Form Pemesanan</div>
                                    <div class="mt-2 text-2xl font-extrabold text-slate-900">Lengkapi data pemohon</div>
                                    <div class="mt-2 text-slate-600">Pilih jenis web dan tema. Setelah dikirim, permintaan akan disimpan untuk diproses.</div>
                                </div>
                                <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-extrabold">
                                    <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    1 menit isi form
                                </div>
                            </div>
                        </div>

                        <div class="p-7 md:p-8">
                            <form method="POST" class="space-y-6">
                                <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Nama Pemohon</label>
                                        <input name="nama" value="<?php echo htmlspecialchars($form['nama']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['nama']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400" placeholder="Nama lengkap">
                                        <?php if (isset($errors['nama'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['nama']); ?></div><?php endif; ?>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Email (opsional)</label>
                                        <input name="email" value="<?php echo htmlspecialchars(isset($_POST['email']) ? (string) $_POST['email'] : $form['email']); ?>" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['email']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400" placeholder="nama@email.com">
                                        <?php if (isset($errors['email'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['email']); ?></div><?php endif; ?>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">WhatsApp (opsional)</label>
                                        <input name="whatsapp" value="<?php echo htmlspecialchars($form['whatsapp']); ?>" class="w-full p-3.5 rounded-2xl border border-slate-200 bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400" placeholder="628xxxx atau 08xxxx">
                                        <div class="mt-2 text-xs text-slate-500">Isi jika ingin dihubungi untuk alternatif subdomain.</div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Subdomain</label>
                                        <div class="flex items-stretch rounded-2xl border <?php echo isset($errors['subdomain']) ? 'border-red-300' : 'border-slate-200'; ?> overflow-hidden bg-white focus-within:ring-4 focus-within:ring-brand-200/50 focus-within:border-brand-400">
                                            <input name="subdomain" value="<?php echo htmlspecialchars($form['subdomain']); ?>" class="flex-1 p-3.5 outline-none font-mono text-sm" placeholder="contoh: tokobunda">
                                            <div class="px-3.5 py-3.5 bg-slate-50 border-l border-slate-200 text-slate-700 font-mono text-xs flex items-center">.orionversa.com</div>
                                        </div>
                                        <?php if (isset($errors['subdomain'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['subdomain']); ?></div><?php endif; ?>
                                        <div class="mt-2 text-xs text-slate-500">Huruf kecil, angka, dan tanda minus. Tanpa spasi. Panjang 3–30 karakter.</div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Jenis Web</label>
                                        <select name="jenis_web" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['jenis_web']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400">
                                            <?php foreach ($jenis_web_options as $k => $label): ?>
                                                <option value="<?php echo htmlspecialchars($k); ?>" <?php echo $form['jenis_web'] === $k ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['jenis_web'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['jenis_web']); ?></div><?php endif; ?>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Tema yang Diinginkan</label>
                                        <select name="tema" class="w-full p-3.5 rounded-2xl border <?php echo isset($errors['tema']) ? 'border-red-300' : 'border-slate-200'; ?> bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400">
                                            <?php foreach ($themes as $slug => $name): ?>
                                                <option value="<?php echo htmlspecialchars($slug); ?>" <?php echo $form['tema'] === $slug ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($name . ' (' . $slug . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['tema'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['tema']); ?></div><?php endif; ?>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Catatan (opsional)</label>
                                        <textarea name="catatan" rows="3" class="w-full p-3.5 rounded-2xl border border-slate-200 bg-white focus:outline-none focus:ring-4 focus:ring-brand-200/50 focus:border-brand-400" placeholder="Contoh: fokus landing untuk jasa, atau butuh halaman kontak."><?php echo htmlspecialchars($form['catatan']); ?></textarea>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                    <div class="flex items-start justify-between gap-6">
                                        <div>
                                            <div class="text-sm font-extrabold text-slate-900">Terms</div>
                                            <div class="mt-2 text-sm text-slate-600 leading-relaxed">Baca ringkasan terms berikut sebelum mengirim permintaan.</div>
                                        </div>
                                        <details class="text-sm">
                                            <summary class="cursor-pointer select-none font-extrabold text-brand-700 hover:text-brand-800">Lihat lengkap</summary>
                                            <div class="mt-3 space-y-2 text-sm text-slate-600 leading-relaxed">
                                                <div>Website gratis ini disediakan sebagai template siap pakai dan dapat digunakan apa adanya.</div>
                                                <div>Permintaan perubahan desain/fitur tidak dilayani pada paket gratis. Jika ingin penyesuaian, Anda dapat upgrade ke layanan berbayar.</div>
                                                <div>Pengajuan tidak menjamin ketersediaan subdomain. Jika subdomain tidak tersedia, kami akan menghubungi Anda untuk alternatif (jika Anda mengisi kontak).</div>
                                            </div>
                                        </details>
                                    </div>

                                    <label class="mt-5 flex items-start gap-3">
                                        <input type="checkbox" name="setuju" value="1" <?php echo $form['setuju'] === '1' ? 'checked' : ''; ?> class="mt-1.5">
                                        <span class="text-sm font-extrabold text-slate-800">Saya setuju dengan terms di atas</span>
                                    </label>
                                    <?php if (isset($errors['setuju'])): ?><div class="mt-2 text-xs text-red-600 font-extrabold"><?php echo htmlspecialchars($errors['setuju']); ?></div><?php endif; ?>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                                    <div class="text-xs text-slate-500">Data Anda akan disimpan untuk proses pembuatan website gratis.</div>
                                    <button class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white font-extrabold transition shadow-2xl shadow-brand-500/30 ring-4 ring-brand-200/50">
                                        Kirim Permintaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center text-sm text-slate-600">
                <a class="font-extrabold text-brand-700 hover:text-brand-800" href="<?php echo esc_url(site_url('/')); ?>">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</section>
