<?php
$page = isset($_GET['page']) ? strtolower(trim($_GET['page'])) : 'home';
if (isset($_GET['p'])) {
    $page = 'single_post';
}

$quote_errors = array();
$quote_values = array(
    'name' => '',
    'email' => '',
    'phone' => '',
    'company' => '',
    'project_type' => '',
    'budget' => '',
    'location' => '',
    'message' => ''
);
$quote_success = false;

$about_badge = get_option('orion_construct_about_badge', 'Tentang Kami');
$about_title = get_option('orion_construct_about_title', 'Partner konstruksi yang memprioritaskan struktur, waktu, dan keselamatan.');
$about_paragraph1 = get_option('orion_construct_about_paragraph1', 'Kami percaya bahwa proyek yang baik bukan hanya selesai dibangun, tetapi juga aman, efisien, dan mudah dirawat dalam jangka panjang. Oleh karena itu, setiap proyek kami jalankan dengan perencanaan matang dan pengawasan ketat di lapangan.');
$about_paragraph2 = get_option('orion_construct_about_paragraph2', 'Mulai dari hunian pribadi hingga gedung komersial dan infrastruktur, tim kami siap mendampingi seluruh proses, dari studi awal, desain, perhitungan struktur, hingga konstruksi dan serah terima.');
$about_box1_title = get_option('orion_construct_about_box1_title', 'Pendekatan Terukur');
$about_box1_text = get_option('orion_construct_about_box1_text', 'Setiap keputusan teknis diambil berdasarkan data perhitungan dan standar rujukan.');
$about_box2_title = get_option('orion_construct_about_box2_title', 'Transparansi Biaya');
$about_box2_text = get_option('orion_construct_about_box2_text', 'RAB dan progres pekerjaan disampaikan secara berkala kepada pemilik proyek.');

$services_badge = get_option('orion_construct_services_badge', 'Layanan Utama');
$services_title = get_option('orion_construct_services_title', 'Solusi konstruksi dari fondasi hingga finishing.');
$service1_tag = get_option('orion_construct_service1_tag', 'Bangun Baru');
$service1_title = get_option('orion_construct_service1_title', 'Gedung dan Rumah Tinggal');
$service1_text = get_option('orion_construct_service1_text', 'Perencanaan dan pembangunan struktur baru untuk hunian, ruko, dan gedung komersial dengan standar struktur yang terukur.');
$service2_tag = get_option('orion_construct_service2_tag', 'Renovasi');
$service2_title = get_option('orion_construct_service2_title', 'Renovasi dan Strengthening');
$service2_text = get_option('orion_construct_service2_text', 'Perbaikan, penambahan ruang, dan penguatan struktur untuk bangunan eksisting tanpa mengganggu aktivitas utama.');
$service3_tag = get_option('orion_construct_service3_tag', 'Infrastruktur');
$service3_title = get_option('orion_construct_service3_title', 'Jalan, Drainase, dan Sipil');
$service3_text = get_option('orion_construct_service3_text', 'Pekerjaan sipil, utilitas, dan infrastruktur lingkungan untuk kawasan industri maupun perumahan.');

$projects_badge = get_option('orion_construct_projects_badge', 'Portofolio');
$projects_title = get_option('orion_construct_projects_title', 'Beberapa proyek yang pernah kami tangani.');
$project1_category = get_option('orion_construct_project1_category', 'Gedung Perkantoran');
$project1_title = get_option('orion_construct_project1_title', 'Pembangunan Kantor 10 Lantai Jakarta');
$project1_summary = get_option('orion_construct_project1_summary', 'Pekerjaan desain struktur, pengadaan, dan konstruksi lengkap dengan sistem MEP.');
$project1_meta = get_option('orion_construct_project1_meta', 'Durasi 18 bulan · 100% tepat waktu');
$project2_category = get_option('orion_construct_project2_category', 'Perumahan');
$project2_title = get_option('orion_construct_project2_title', 'Cluster Residensial 50 Unit');
$project2_summary = get_option('orion_construct_project2_summary', 'Pembangunan kawasan hunian lengkap dengan infrastruktur jalan, drainase, dan taman.');
$project2_meta = get_option('orion_construct_project2_meta', 'Durasi 14 bulan · Zero accident');
$project3_category = get_option('orion_construct_project3_category', 'Interior Kantor');
$project3_title = get_option('orion_construct_project3_title', 'Fit Out Kantor Teknologi');
$project3_summary = get_option('orion_construct_project3_summary', 'Pengerjaan interior modern open space dengan sistem mekanikal dan elektrikal terintegrasi.');
$project3_meta = get_option('orion_construct_project3_meta', 'Durasi 4 bulan · Serah terima tepat waktu');

$hero_image = get_option('orion_construct_hero_image', '');
$project1_image = get_option('orion_construct_project1_image', '');
$project2_image = get_option('orion_construct_project2_image', '');
$project3_image = get_option('orion_construct_project3_image', '');

if ($page === 'minta-penawaran' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['construct_quote_submit'])) {
    $quote_values['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
    $quote_values['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $quote_values['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $quote_values['company'] = isset($_POST['company']) ? trim($_POST['company']) : '';
    $quote_values['project_type'] = isset($_POST['project_type']) ? trim($_POST['project_type']) : '';
    $quote_values['budget'] = isset($_POST['budget']) ? trim($_POST['budget']) : '';
    $quote_values['location'] = isset($_POST['location']) ? trim($_POST['location']) : '';
    $quote_values['message'] = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($quote_values['name'] === '') {
        $quote_errors['name'] = 'Nama wajib diisi.';
    }
    if ($quote_values['email'] === '') {
        $quote_errors['email'] = 'Email wajib diisi.';
    }
    if ($quote_values['phone'] === '') {
        $quote_errors['phone'] = 'Nomor telepon wajib diisi.';
    }
    if ($quote_values['project_type'] === '') {
        $quote_errors['project_type'] = 'Jenis proyek wajib dipilih.';
    }
    if ($quote_values['message'] === '') {
        $quote_errors['message'] = 'Deskripsi kebutuhan proyek wajib diisi.';
    }

    if (empty($quote_errors)) {
        global $orion_db, $table_prefix;

        if (isset($orion_db, $table_prefix)) {
            $table_quotes = $table_prefix . 'orion_construct_quotes';

            $name = $orion_db->real_escape_string($quote_values['name']);
            $email = $orion_db->real_escape_string($quote_values['email']);
            $phone = $orion_db->real_escape_string($quote_values['phone']);
            $company = $orion_db->real_escape_string($quote_values['company']);
            $project_type = $orion_db->real_escape_string($quote_values['project_type']);
            $budget = $orion_db->real_escape_string($quote_values['budget']);
            $location = $orion_db->real_escape_string($quote_values['location']);
            $message = $orion_db->real_escape_string($quote_values['message']);
            $contact_channel = isset($_POST['contact_channel']) ? trim($_POST['contact_channel']) : '';
            $allowed_channels = array('telpon', 'whatsapp', 'email', 'kunjungan');
            if (!in_array($contact_channel, $allowed_channels, true)) {
                $contact_channel = '';
            }
            $contact_channel_db = $orion_db->real_escape_string($contact_channel);

            $document_path_db = '';
            if (isset($_FILES['project_document']) && $_FILES['project_document']['error'] === UPLOAD_ERR_OK) {
                $max_size = 4 * 1024 * 1024;
                $file_size = (int) $_FILES['project_document']['size'];
                $file_ext = strtolower(pathinfo($_FILES['project_document']['name'], PATHINFO_EXTENSION));

                if ($file_ext === 'pdf' && $file_size > 0 && $file_size <= $max_size) {
                    $upload_dir = ABSPATH . 'orion-content/uploads/quotes/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    $new_name = 'quote-' . time() . '-' . mt_rand(1000, 9999) . '.pdf';
                    $destination = $upload_dir . $new_name;
                    if (move_uploaded_file($_FILES['project_document']['tmp_name'], $destination)) {
                        $document_path_db = $orion_db->real_escape_string('orion-content/uploads/quotes/' . $new_name);
                    }
                }
            }

            $insert_sql = "INSERT INTO $table_quotes (name, email, phone, company, project_type, budget, location, contact_channel, message, document_path, status, created_at) VALUES (
                '$name',
                '$email',
                '$phone',
                '$company',
                '$project_type',
                '$budget',
                '$location',
                '$contact_channel_db',
                '$message',
                " . ($document_path_db !== '' ? "'$document_path_db'" : "NULL") . ",
                'masuk',
                NOW()
            )";

            $orion_db->query($insert_sql);
        }

        $admin_email = get_option('admin_email', '');
        if ($admin_email === '') {
            $admin_email = 'admin@localhost';
        }
        $subject = 'Permintaan Penawaran Konstruksi dari ' . $quote_values['name'];
        $lines = array();
        $lines[] = 'Nama: ' . $quote_values['name'];
        $lines[] = 'Email: ' . $quote_values['email'];
        $lines[] = 'Telepon: ' . $quote_values['phone'];
        if ($quote_values['company'] !== '') {
            $lines[] = 'Perusahaan: ' . $quote_values['company'];
        }
        if ($quote_values['project_type'] !== '') {
            $lines[] = 'Jenis Proyek: ' . $quote_values['project_type'];
        }
        if ($quote_values['budget'] !== '') {
            $lines[] = 'Estimasi Anggaran: ' . $quote_values['budget'];
        }
        if ($quote_values['location'] !== '') {
            $lines[] = 'Lokasi Proyek: ' . $quote_values['location'];
        }
        $lines[] = '';
        $lines[] = 'Deskripsi Kebutuhan Proyek:';
        $lines[] = $quote_values['message'];
        if ($contact_channel !== '') {
            $lines[] = '';
            $lines[] = 'Preferensi kontak: ' . ucfirst($contact_channel);
        }
        if (!empty($document_path_db)) {
            $lines[] = '';
            $lines[] = 'Dokumen terlampir disimpan di: ' . site_url('/' . $document_path_db);
        }
        $lines[] = '';
        $lines[] = 'Pesan ini dikirim melalui formulir "Minta Penawaran" tema Orion Construction.';
        $body = implode("\n", $lines);

        $headers = array();
        if ($quote_values['email'] !== '') {
            $headers[] = 'Reply-To: ' . $quote_values['name'] . ' <' . $quote_values['email'] . '>';
        }

        if (function_exists('wp_mail')) {
            wp_mail($admin_email, $subject, $body, $headers);
        } else {
            @mail($admin_email, $subject, $body);
        }

        $quote_success = true;
        $quote_values = array(
            'name' => '',
            'email' => '',
            'phone' => '',
            'company' => '',
            'project_type' => '',
            'budget' => '',
            'location' => '',
            'message' => ''
        );
    }
}

get_header();

if ($page === 'minta-penawaran') {
    ?>
    <section class="bg-slate-950 py-10 sm:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2">
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-50 mb-3">Formulir Permintaan Penawaran</h1>
                    <p class="text-slate-300 text-sm sm:text-base mb-6">Isi detail proyek konstruksi yang Anda rencanakan. Tim kami akan menghubungi Anda dengan estimasi biaya dan jadwal pengerjaan.</p>
                    <?php
                    $contact_channel_value = isset($_POST['contact_channel']) ? $_POST['contact_channel'] : '';
                    $allowed_channels = array('telpon', 'whatsapp', 'email', 'kunjungan');
                    if (!in_array($contact_channel_value, $allowed_channels, true)) {
                        $contact_channel_value = '';
                    }
                    ?>
                    <?php if ($quote_success): ?>
                        <div class="mb-6 rounded-lg border border-emerald-500/60 bg-emerald-950/40 text-emerald-100 text-sm px-4 py-3">
                            Permintaan penawaran Anda sudah terkirim. Kami akan menghubungi Anda dalam waktu dekat.
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($quote_errors)): ?>
                        <div class="mb-6 rounded-lg border border-red-500/60 bg-red-950/40 text-red-100 text-sm px-4 py-3">
                            Mohon periksa kembali isian Anda. Beberapa data wajib masih belum lengkap.
                        </div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($quote_values['name']); ?>" class="w-full rounded-lg border <?php echo isset($quote_errors['name']) ? 'border-red-500' : 'border-slate-700'; ?> bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Email</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($quote_values['email']); ?>" class="w-full rounded-lg border <?php echo isset($quote_errors['email']) ? 'border-red-500' : 'border-slate-700'; ?> bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Nomor Telepon/WhatsApp</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($quote_values['phone']); ?>" class="w-full rounded-lg border <?php echo isset($quote_errors['phone']) ? 'border-red-500' : 'border-slate-700'; ?> bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Nama Perusahaan (opsional)</label>
                                <input type="text" name="company" value="<?php echo htmlspecialchars($quote_values['company']); ?>" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Jenis Proyek</label>
                                <select name="project_type" class="w-full rounded-lg border <?php echo isset($quote_errors['project_type']) ? 'border-red-500' : 'border-slate-700'; ?> bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih jenis proyek</option>
                                    <option value="Bangun baru rumah tinggal"<?php echo $quote_values['project_type'] === 'Bangun baru rumah tinggal' ? ' selected' : ''; ?>>Bangun baru rumah tinggal</option>
                                    <option value="Renovasi rumah"<?php echo $quote_values['project_type'] === 'Renovasi rumah' ? ' selected' : ''; ?>>Renovasi rumah</option>
                                    <option value="Gedung komersial"<?php echo $quote_values['project_type'] === 'Gedung komersial' ? ' selected' : ''; ?>>Gedung komersial</option>
                                    <option value="Kantor dan interior"<?php echo $quote_values['project_type'] === 'Kantor dan interior' ? ' selected' : ''; ?>>Kantor dan interior</option>
                                    <option value="Infrastruktur dan sipil"<?php echo $quote_values['project_type'] === 'Infrastruktur dan sipil' ? ' selected' : ''; ?>>Infrastruktur dan sipil</option>
                                    <option value="Lainnya"<?php echo $quote_values['project_type'] === 'Lainnya' ? ' selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Estimasi Anggaran (opsional)</label>
                                <input type="text" name="budget" value="<?php echo htmlspecialchars($quote_values['budget']); ?>" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Contoh: 500 juta, 1 M">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Lokasi Proyek</label>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($quote_values['location']); ?>" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Kota, provinsi">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Ingin dihubungi lewat apa?</label>
                                <select name="contact_channel" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih cara dihubungi</option>
                                    <option value="telpon"<?php echo $contact_channel_value === 'telpon' ? ' selected' : ''; ?>>Telepon</option>
                                    <option value="whatsapp"<?php echo $contact_channel_value === 'whatsapp' ? ' selected' : ''; ?>>WhatsApp</option>
                                    <option value="email"<?php echo $contact_channel_value === 'email' ? ' selected' : ''; ?>>Email</option>
                                    <option value="kunjungan"<?php echo $contact_channel_value === 'kunjungan' ? ' selected' : ''; ?>>Kunjungan langsung</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-200 mb-1">Lokasi Proyek</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($quote_values['location']); ?>" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Kota, provinsi">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-200 mb-1">Deskripsi Kebutuhan Proyek</label>
                            <textarea name="message" rows="5" class="w-full rounded-lg border <?php echo isset($quote_errors['message']) ? 'border-red-500' : 'border-slate-700'; ?> bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Jelaskan jenis pekerjaan, luas bangunan, jadwal yang diharapkan, dan informasi lain yang relevan."><?php echo htmlspecialchars($quote_values['message']); ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 mb-1">Upload dokumen (opsional, PDF maks. 4 MB)</label>
                                <input type="file" name="project_document" accept="application/pdf" class="block w-full text-xs text-slate-300 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-300 hover:file:bg-amber-500/20">
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-3">
                                <p class="text-xs text-slate-400 max-w-xs">Dengan mengirim formulir ini, Anda menyetujui bahwa tim kami akan menghubungi Anda sesuai preferensi kontak yang dipilih.</p>
                                <button type="submit" name="construct_quote_submit" value="1" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-semibold shadow-lg shadow-amber-500/40 transition">
                                    Kirim Permintaan Penawaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <aside class="lg:col-span-1">
                    <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5 space-y-4">
                        <p class="text-xs font-semibold tracking-wide uppercase text-amber-400">Mengapa bersama kami</p>
                        <h2 class="text-lg font-bold text-slate-50">Kualitas struktur, transparansi biaya, dan jadwal yang terjaga.</h2>
                        <ul class="space-y-3 text-sm text-slate-300">
                            <li class="flex gap-2">
                                <span class="mt-1 h-5 w-5 rounded-full bg-amber-500/10 border border-amber-500/40 flex items-center justify-center text-[10px] text-amber-300">1</span>
                                <span>Didukung tim engineer berpengalaman di proyek gedung bertingkat dan infrastruktur.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-5 w-5 rounded-full bg-amber-500/10 border border-amber-500/40 flex items-center justify-center text-[10px] text-amber-300">2</span>
                                <span>Perhitungan RAB yang terukur dengan pilihan skema pembayaran bertahap.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-5 w-5 rounded-full bg-amber-500/10 border border-amber-500/40 flex items-center justify-center text-[10px] text-amber-300">3</span>
                                <span>Penerapan standar K3 di setiap lokasi proyek.</span>
                            </li>
                        </ul>
                        <div class="text-xs text-slate-400 pt-2 border-t border-slate-800">
                            Alternatif cepat: hubungi langsung tim penawaran di <span class="text-amber-400 font-semibold">+62 21 1234 5678</span>.
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'tentang') {
    ?>
    <section class="bg-slate-950 py-12 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <div>
                <p class="text-xs font-semibold tracking-wide uppercase text-amber-400 mb-2"><?php echo htmlspecialchars($about_badge); ?></p>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-50 mb-3"><?php echo htmlspecialchars($about_title); ?></h1>
                <p class="text-sm text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($about_paragraph1)); ?></p>
                <p class="text-sm text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($about_paragraph2)); ?></p>
            </div>
            <div class="grid grid-cols-1 gap-4 text-xs text-slate-200">
                <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                    <p class="font-semibold mb-1"><?php echo htmlspecialchars($about_box1_title); ?></p>
                    <p class="text-slate-400"><?php echo nl2br(htmlspecialchars($about_box1_text)); ?></p>
                </div>
                <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                    <p class="font-semibold mb-1"><?php echo htmlspecialchars($about_box2_title); ?></p>
                    <p class="text-slate-400"><?php echo nl2br(htmlspecialchars($about_box2_text)); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'layanan') {
    ?>
    <section class="bg-slate-950 py-12 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-amber-400"><?php echo htmlspecialchars($services_badge); ?></p>
                    <h1 class="text-3xl font-bold text-slate-50"><?php echo htmlspecialchars($services_title); ?></h1>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service1_tag); ?></p>
                    <h2 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service1_title); ?></h2>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service1_text)); ?></p>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Diskusikan rencana proyek</a>
                </div>
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service2_tag); ?></p>
                    <h2 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service2_title); ?></h2>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service2_text)); ?></p>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Minta survey lokasi</a>
                </div>
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service3_tag); ?></p>
                    <h2 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service3_title); ?></h2>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service3_text)); ?></p>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Rencanakan proyek infrastruktur</a>
                </div>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'proyek') {
    ?>
    <section class="bg-slate-950 py-12 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-amber-400"><?php echo htmlspecialchars($projects_badge); ?></p>
                    <h1 class="text-3xl font-bold text-slate-50"><?php echo htmlspecialchars($projects_title); ?></h1>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <?php
                        $project1_image_url = $project1_image !== '' ? $project1_image : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=900&q=80';
                        ?>
                        <img src="<?php echo htmlspecialchars($project1_image_url); ?>" alt="<?php echo htmlspecialchars($project1_category); ?>" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project1_category); ?></p>
                        <h2 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project1_title); ?></h2>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project1_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project1_meta); ?></p>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <?php
                        $project2_image_url = $project2_image !== '' ? $project2_image : 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=900&q=80';
                        ?>
                        <img src="<?php echo htmlspecialchars($project2_image_url); ?>" alt="<?php echo htmlspecialchars($project2_category); ?>" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project2_category); ?></p>
                        <h2 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project2_title); ?></h2>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project2_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project2_meta); ?></p>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <?php
                        $project3_image_url = $project3_image !== '' ? $project3_image : 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80';
                        ?>
                        <img src="<?php echo htmlspecialchars($project3_image_url); ?>" alt="<?php echo htmlspecialchars($project3_category); ?>" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project3_category); ?></p>
                        <h2 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project3_title); ?></h2>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project3_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project3_meta); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'single_post') {
    $post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
    $post = $post_id > 0 ? get_post($post_id) : null;
    if ($post) {
        $content = $post->post_content;
        if (function_exists('apply_filters')) {
            $content = apply_filters('the_content', $content);
        }
        ?>
        <section class="bg-slate-950 py-12 sm:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">
                <article class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6 sm:p-8">
                    <p class="text-xs font-semibold tracking-wide uppercase text-amber-400 mb-2">Artikel</p>
                    <h1 class="text-3xl font-bold text-slate-50 mb-3"><?php echo htmlspecialchars($post->post_title); ?></h1>
                    <p class="text-xs text-slate-400 mb-6"><?php echo date('d M Y', strtotime($post->post_date)); ?></p>
                    <div class="prose prose-invert max-w-none">
                        <?php echo $content; ?>
                    </div>
                </article>
            </div>
        </section>
        <?php
    } else {
        ?>
        <section class="bg-slate-950 py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center text-slate-300">
                <h1 class="text-2xl font-bold text-slate-50 mb-3">Konten tidak ditemukan</h1>
                <p class="text-sm mb-4">Artikel yang Anda cari tidak tersedia atau sudah dihapus.</p>
                <a href="<?php echo site_url(); ?>" class="inline-flex px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-semibold">Kembali ke beranda</a>
            </div>
        </section>
        <?php
    }
} else {
    $hero_bg = $hero_image !== '' ? $hero_image : 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1600&q=80';
    ?>
    <section class="relative bg-slate-950 overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo $hero_bg; ?>" alt="Proyek konstruksi" class="w-full h-full object-cover opacity-70">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/70 via-slate-950/60 to-slate-900/60"></div>
        </div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 sm:py-28 lg:py-32">
            <div class="max-w-3xl">
                <p class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/40 text-amber-300 text-xs font-semibold mb-4">Perusahaan Konstruksi Profesional</p>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-50 leading-tight mb-4">Mewujudkan struktur kokoh dan estetika yang selaras.</h1>
                <p class="text-sm sm:text-base text-slate-200 mb-6">Kami mengerjakan proyek bangunan baru, renovasi, dan infrastruktur dengan perencanaan matang, pengawasan ketat, dan komitmen terhadap kualitas.</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-semibold shadow-lg shadow-amber-500/40 transition">
                        Minta Penawaran Proyek
                    </a>
                    <a href="<?php echo site_url(); ?>/?page=proyek" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-500 text-slate-100 text-sm font-semibold hover:bg-slate-900/60 transition">
                        Lihat Portofolio Proyek
                    </a>
                </div>
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-slate-200">
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="text-slate-400">Pengalaman</p>
                        <p class="text-lg font-bold text-slate-50">15+ tahun</p>
                    </div>
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="text-slate-400">Proyek selesai</p>
                        <p class="text-lg font-bold text-slate-50">120+</p>
                    </div>
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="text-slate-400">Tim engineer</p>
                        <p class="text-lg font-bold text-slate-50">30+</p>
                    </div>
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="text-slate-400">Kepuasan klien</p>
                        <p class="text-lg font-bold text-slate-50">4.9/5</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="layanan" class="bg-slate-950 py-12 sm:py-16 border-t border-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-amber-400"><?php echo htmlspecialchars($services_badge); ?></p>
                    <h2 class="text-2xl font-bold text-slate-50"><?php echo htmlspecialchars($services_title); ?></h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service1_tag); ?></p>
                    <h3 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service1_title); ?></h3>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service1_text)); ?></p>
                    <ul class="text-slate-300 text-xs space-y-1 mb-4">
                        <li>Perencanaan arsitektur dan struktur</li>
                        <li>Pengurusan perizinan dasar</li>
                        <li>Pelaksanaan konstruksi menyeluruh</li>
                    </ul>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Diskusikan rencana gedung Anda</a>
                </div>
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service2_tag); ?></p>
                    <h3 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service2_title); ?></h3>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service2_text)); ?></p>
                    <ul class="text-slate-300 text-xs space-y-1 mb-4">
                        <li>Perbaikan struktur dan finishing</li>
                        <li>Interior kantor dan komersial</li>
                        <li>Peningkatan kapasitas beban struktur</li>
                    </ul>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Minta survey lokasi</a>
                </div>
                <div class="rounded-2xl bg-slate-900/70 border border-slate-800 p-5 flex flex-col">
                    <p class="text-xs font-semibold text-amber-400 mb-1 uppercase"><?php echo htmlspecialchars($service3_tag); ?></p>
                    <h3 class="text-lg font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($service3_title); ?></h3>
                    <p class="text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($service3_text)); ?></p>
                    <ul class="text-slate-300 text-xs space-y-1 mb-4">
                        <li>Pekerjaan jalan dan rigid pavement</li>
                        <li>Drainase dan saluran air</li>
                        <li>Pondasi dan pekerjaan tanah</li>
                    </ul>
                    <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="mt-auto inline-flex text-xs font-semibold text-amber-400 hover:text-amber-300">Rencanakan proyek infrastruktur</a>
                </div>
            </div>
        </div>
    </section>
    <section id="proyek" class="bg-slate-950 py-12 sm:py-16 border-t border-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase text-amber-400"><?php echo htmlspecialchars($projects_badge); ?></p>
                    <h2 class="text-2xl font-bold text-slate-50"><?php echo htmlspecialchars($projects_title); ?></h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=900&q=80" alt="Proyek gedung perkantoran" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project1_category); ?></p>
                        <h3 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project1_title); ?></h3>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project1_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project1_meta); ?></p>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <img src="https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=900&q=80" alt="Proyek perumahan" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project2_category); ?></p>
                        <h3 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project2_title); ?></h3>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project2_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project2_meta); ?></p>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-slate-900/70 border border-slate-800 flex flex-col">
                    <div class="relative h-44 bg-slate-800">
                        <img src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80" alt="Proyek interior kantor" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-slate-400 mb-1"><?php echo htmlspecialchars($project3_category); ?></p>
                        <h3 class="text-sm font-semibold text-slate-50 mb-2"><?php echo htmlspecialchars($project3_title); ?></h3>
                        <p class="text-xs text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($project3_summary)); ?></p>
                        <p class="text-[11px] text-slate-400 mt-auto"><?php echo htmlspecialchars($project3_meta); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="tentang" class="bg-slate-950 py-12 sm:py-16 border-t border-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-xs font-semibold tracking-wide uppercase text-amber-400 mb-2"><?php echo htmlspecialchars($about_badge); ?></p>
                <h2 class="text-2xl font-bold text-slate-50 mb-3"><?php echo htmlspecialchars($about_title); ?></h2>
                <p class="text-sm text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($about_paragraph1)); ?></p>
                <p class="text-sm text-slate-300 mb-3"><?php echo nl2br(htmlspecialchars($about_paragraph2)); ?></p>
                <div class="mt-4 grid grid-cols-2 gap-4 text-xs text-slate-200">
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="font-semibold mb-1"><?php echo htmlspecialchars($about_box1_title); ?></p>
                        <p class="text-slate-400"><?php echo nl2br(htmlspecialchars($about_box1_text)); ?></p>
                    </div>
                    <div class="rounded-xl bg-slate-900/70 border border-slate-800 p-3">
                        <p class="font-semibold mb-1"><?php echo htmlspecialchars($about_box2_title); ?></p>
                        <p class="text-slate-400"><?php echo nl2br(htmlspecialchars($about_box2_text)); ?></p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-50 mb-3">Butuh diskusi cepat?</h3>
                <p class="text-sm text-slate-300 mb-4">Kirimkan gambaran singkat rencana proyek Anda. Kami akan memberikan rekomendasi awal sebelum menyusun penawaran resmi.</p>
                <a href="<?php echo site_url(); ?>/?page=minta-penawaran" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-semibold shadow-lg shadow-amber-500/40 transition">
                    Buka Formulir Minta Penawaran
                </a>
                <p class="text-xs text-slate-500 mt-3">Tidak ada biaya konsultasi awal. Penawaran resmi akan disesuaikan dengan kebutuhan dan prioritas Anda.</p>
            </div>
        </div>
    </section>
    <?php
}

get_footer();
