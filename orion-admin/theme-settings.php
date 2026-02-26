<?php
require_once '../orion-load.php';

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

$current_theme = get_option('template', 'orion-default');

if ($current_theme === 'orion-garage' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['orion_garage_theme_settings_submit'])) {
        update_option('orion_garage_front_title', isset($_POST['front_title']) ? trim($_POST['front_title']) : '');
        update_option('orion_garage_front_subtitle', isset($_POST['front_subtitle']) ? trim($_POST['front_subtitle']) : '');
        update_option('orion_garage_front_cta_text', isset($_POST['front_cta_text']) ? trim($_POST['front_cta_text']) : '');
        update_option('orion_garage_front_cta_url', isset($_POST['front_cta_url']) ? trim($_POST['front_cta_url']) : '');
        $hero_image_url = isset($_POST['front_hero_image']) ? trim($_POST['front_hero_image']) : '';
        if (isset($_FILES['front_hero_image_file']) && $_FILES['front_hero_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['front_hero_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'garage-hero-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['front_hero_image_file']['tmp_name'], $destination)) {
                    $hero_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_garage_front_hero_image', $hero_image_url);

        update_option('orion_garage_store_name', isset($_POST['store_name']) ? trim($_POST['store_name']) : '');
        update_option('orion_garage_store_address', isset($_POST['store_address']) ? trim($_POST['store_address']) : '');
        update_option('orion_garage_store_phone', isset($_POST['store_phone']) ? trim($_POST['store_phone']) : '');
        update_option('orion_garage_store_whatsapp', isset($_POST['store_whatsapp']) ? trim($_POST['store_whatsapp']) : '');
        update_option('orion_garage_store_hours', isset($_POST['store_hours']) ? trim($_POST['store_hours']) : '');

        update_option('orion_garage_map_embed', isset($_POST['map_embed']) ? trim($_POST['map_embed']) : '');

        header('Location: theme-settings.php?updated=1');
        exit;
    } elseif (isset($_POST['orion_garage_backup_submit'])) {
        global $orion_db, $table_prefix;

        $backup = array(
            'created_at' => date('c'),
            'theme' => 'orion-garage',
            'options' => array(
                'front_title' => get_option('orion_garage_front_title', ''),
                'front_subtitle' => get_option('orion_garage_front_subtitle', ''),
                'front_cta_text' => get_option('orion_garage_front_cta_text', ''),
                'front_cta_url' => get_option('orion_garage_front_cta_url', ''),
                'front_hero_image' => get_option('orion_garage_front_hero_image', ''),
                'store_name' => get_option('orion_garage_store_name', ''),
                'store_address' => get_option('orion_garage_store_address', ''),
                'store_phone' => get_option('orion_garage_store_phone', ''),
                'store_whatsapp' => get_option('orion_garage_store_whatsapp', ''),
                'store_hours' => get_option('orion_garage_store_hours', ''),
                'map_embed' => get_option('orion_garage_map_embed', ''),
            ),
            'products' => array(),
        );

        if (isset($orion_db, $table_prefix)) {
            $table_products = $table_prefix . 'orion_products';
            $products_result = $orion_db->query("SELECT * FROM $table_products ORDER BY id ASC");
            if ($products_result) {
                while ($row = $products_result->fetch_assoc()) {
                    $backup['products'][] = $row;
                }
            }
        }

        update_option('orion_garage_backup_snapshot', $backup);

        header('Location: theme-settings.php?backup=1');
        exit;
    }
} elseif ($current_theme === 'orion-mosque' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['orion_mosque_theme_settings_submit'])) {
        update_option('orion_mosque_hero_title', isset($_POST['hero_title']) ? trim($_POST['hero_title']) : '');
        update_option('orion_mosque_hero_subtitle', isset($_POST['hero_subtitle']) ? trim($_POST['hero_subtitle']) : '');
        update_option('orion_mosque_hero_description', isset($_POST['hero_description']) ? trim($_POST['hero_description']) : '');

        $hero_image_url = isset($_POST['hero_image']) ? trim($_POST['hero_image']) : '';
        if (isset($_FILES['hero_image_file']) && $_FILES['hero_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['hero_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'mosque-hero-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['hero_image_file']['tmp_name'], $destination)) {
                    $hero_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_mosque_hero_image', $hero_image_url);

        update_option('orion_mosque_contact_address', isset($_POST['contact_address']) ? trim($_POST['contact_address']) : '');
        update_option('orion_mosque_contact_phone', isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '');
        update_option('orion_mosque_contact_email', isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '');

        $city = isset($_POST['prayer_city']) ? trim($_POST['prayer_city']) : '';
        $country = isset($_POST['prayer_country']) ? trim($_POST['prayer_country']) : '';
        $method = isset($_POST['prayer_method']) ? (int)$_POST['prayer_method'] : 20;
        if ($method <= 0) {
            $method = 20;
        }

        update_option('orion_mosque_prayer_city', $city);
        update_option('orion_mosque_prayer_country', $country);
        update_option('orion_mosque_prayer_method', $method);

        update_option('orion_mosque_donation_title', isset($_POST['donation_title']) ? trim($_POST['donation_title']) : '');
        update_option('orion_mosque_donation_description', isset($_POST['donation_description']) ? trim($_POST['donation_description']) : '');
        update_option('orion_mosque_donation_account_title', isset($_POST['donation_account_title']) ? trim($_POST['donation_account_title']) : '');
        update_option('orion_mosque_donation_account_detail', isset($_POST['donation_account_detail']) ? trim($_POST['donation_account_detail']) : '');
        update_option('orion_mosque_donation_program_title', isset($_POST['donation_program_title']) ? trim($_POST['donation_program_title']) : '');
        update_option('orion_mosque_donation_program_detail', isset($_POST['donation_program_detail']) ? trim($_POST['donation_program_detail']) : '');

        update_option('orion_mosque_kegiatan_badge', isset($_POST['kegiatan_badge']) ? trim($_POST['kegiatan_badge']) : '');
        update_option('orion_mosque_kegiatan_title', isset($_POST['kegiatan_title']) ? trim($_POST['kegiatan_title']) : '');
        update_option('orion_mosque_kegiatan_description', isset($_POST['kegiatan_description']) ? trim($_POST['kegiatan_description']) : '');

        header('Location: theme-settings.php?updated=1');
        exit;
    }
} elseif ($current_theme === 'orion-construct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['orion_construct_theme_settings_submit'])) {
        $hero_image_url = isset($_POST['hero_image']) ? trim($_POST['hero_image']) : '';
        if (isset($_FILES['hero_image_file']) && $_FILES['hero_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['hero_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'construct-hero-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['hero_image_file']['tmp_name'], $destination)) {
                    $hero_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_construct_hero_image', $hero_image_url);

        $project1_image_url = isset($_POST['project1_image']) ? trim($_POST['project1_image']) : '';
        if (isset($_FILES['project1_image_file']) && $_FILES['project1_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['project1_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'construct-project1-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['project1_image_file']['tmp_name'], $destination)) {
                    $project1_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_construct_project1_image', $project1_image_url);

        $project2_image_url = isset($_POST['project2_image']) ? trim($_POST['project2_image']) : '';
        if (isset($_FILES['project2_image_file']) && $_FILES['project2_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['project2_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'construct-project2-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['project2_image_file']['tmp_name'], $destination)) {
                    $project2_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_construct_project2_image', $project2_image_url);

        $project3_image_url = isset($_POST['project3_image']) ? trim($_POST['project3_image']) : '';
        if (isset($_FILES['project3_image_file']) && $_FILES['project3_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['project3_image_file']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            if (in_array($file_ext, $allowed_ext, true)) {
                $new_filename = 'construct-project3-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['project3_image_file']['tmp_name'], $destination)) {
                    $project3_image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                }
            }
        }
        update_option('orion_construct_project3_image', $project3_image_url);

        update_option('orion_construct_about_badge', isset($_POST['about_badge']) ? trim($_POST['about_badge']) : '');
        update_option('orion_construct_about_title', isset($_POST['about_title']) ? trim($_POST['about_title']) : '');
        update_option('orion_construct_about_paragraph1', isset($_POST['about_paragraph1']) ? trim($_POST['about_paragraph1']) : '');
        update_option('orion_construct_about_paragraph2', isset($_POST['about_paragraph2']) ? trim($_POST['about_paragraph2']) : '');
        update_option('orion_construct_about_box1_title', isset($_POST['about_box1_title']) ? trim($_POST['about_box1_title']) : '');
        update_option('orion_construct_about_box1_text', isset($_POST['about_box1_text']) ? trim($_POST['about_box1_text']) : '');
        update_option('orion_construct_about_box2_title', isset($_POST['about_box2_title']) ? trim($_POST['about_box2_title']) : '');
        update_option('orion_construct_about_box2_text', isset($_POST['about_box2_text']) ? trim($_POST['about_box2_text']) : '');

        update_option('orion_construct_services_badge', isset($_POST['services_badge']) ? trim($_POST['services_badge']) : '');
        update_option('orion_construct_services_title', isset($_POST['services_title']) ? trim($_POST['services_title']) : '');
        update_option('orion_construct_service1_tag', isset($_POST['service1_tag']) ? trim($_POST['service1_tag']) : '');
        update_option('orion_construct_service1_title', isset($_POST['service1_title']) ? trim($_POST['service1_title']) : '');
        update_option('orion_construct_service1_text', isset($_POST['service1_text']) ? trim($_POST['service1_text']) : '');
        update_option('orion_construct_service2_tag', isset($_POST['service2_tag']) ? trim($_POST['service2_tag']) : '');
        update_option('orion_construct_service2_title', isset($_POST['service2_title']) ? trim($_POST['service2_title']) : '');
        update_option('orion_construct_service2_text', isset($_POST['service2_text']) ? trim($_POST['service2_text']) : '');
        update_option('orion_construct_service3_tag', isset($_POST['service3_tag']) ? trim($_POST['service3_tag']) : '');
        update_option('orion_construct_service3_title', isset($_POST['service3_title']) ? trim($_POST['service3_title']) : '');
        update_option('orion_construct_service3_text', isset($_POST['service3_text']) ? trim($_POST['service3_text']) : '');

        update_option('orion_construct_projects_badge', isset($_POST['projects_badge']) ? trim($_POST['projects_badge']) : '');
        update_option('orion_construct_projects_title', isset($_POST['projects_title']) ? trim($_POST['projects_title']) : '');

        update_option('orion_construct_project1_category', isset($_POST['project1_category']) ? trim($_POST['project1_category']) : '');
        update_option('orion_construct_project1_title', isset($_POST['project1_title']) ? trim($_POST['project1_title']) : '');
        update_option('orion_construct_project1_summary', isset($_POST['project1_summary']) ? trim($_POST['project1_summary']) : '');
        update_option('orion_construct_project1_meta', isset($_POST['project1_meta']) ? trim($_POST['project1_meta']) : '');

        update_option('orion_construct_project2_category', isset($_POST['project2_category']) ? trim($_POST['project2_category']) : '');
        update_option('orion_construct_project2_title', isset($_POST['project2_title']) ? trim($_POST['project2_title']) : '');
        update_option('orion_construct_project2_summary', isset($_POST['project2_summary']) ? trim($_POST['project2_summary']) : '');
        update_option('orion_construct_project2_meta', isset($_POST['project2_meta']) ? trim($_POST['project2_meta']) : '');

        update_option('orion_construct_project3_category', isset($_POST['project3_category']) ? trim($_POST['project3_category']) : '');
        update_option('orion_construct_project3_title', isset($_POST['project3_title']) ? trim($_POST['project3_title']) : '');
        update_option('orion_construct_project3_summary', isset($_POST['project3_summary']) ? trim($_POST['project3_summary']) : '');
        update_option('orion_construct_project3_meta', isset($_POST['project3_meta']) ? trim($_POST['project3_meta']) : '');

        header('Location: theme-settings.php?updated=1');
        exit;
    }
}

require_once 'admin-header.php';

$message = '';
if (isset($_GET['updated']) && $_GET['updated'] == '1') {
    $message = 'Pengaturan tema berhasil disimpan.';
} elseif (isset($_GET['backup']) && $_GET['backup'] == '1') {
    $message = 'Snapshot konfigurasi dan produk toko berhasil disimpan.';
}

?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Theme Settings</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola konten front page dan informasi toko.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<?php if ($current_theme === 'orion-garage'): ?>
<?php
$front_title = get_option('orion_garage_front_title', 'Orion Garage');
$front_subtitle = get_option('orion_garage_front_subtitle', 'Bengkel modern untuk mobil kesayangan Anda');
$front_cta_text = get_option('orion_garage_front_cta_text', 'Booking Servis Sekarang');
$front_cta_url = get_option('orion_garage_front_cta_url', '#kontak');
$front_hero_image = get_option('orion_garage_front_hero_image', '');

$store_name = get_option('orion_garage_store_name', 'Orion Garage');
$store_address = get_option('orion_garage_store_address', '');
$store_phone = get_option('orion_garage_store_phone', '');
$store_whatsapp = get_option('orion_garage_store_whatsapp', '');
$store_hours = get_option('orion_garage_store_hours', '');

$map_embed = get_option('orion_garage_map_embed', '');
$guide_url = site_url('/orion-content/themes/orion-garage/garage-settings-guide.html');
?>

<div class="mb-4">
    <a href="<?php echo $guide_url; ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-orion-600 text-white text-[10px] font-bold">i</span>
        <span>Petunjuk lengkap pengaturan tema Orion Garage</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <form method="POST" action="theme-settings.php" enctype="multipart/form-data">
        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Front Page Content</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama</label>
                        <input type="text" name="front_title" value="<?php echo htmlspecialchars($front_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                        <input type="text" name="front_subtitle" value="<?php echo htmlspecialchars($front_subtitle); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol (CTA)</label>
                        <input type="text" name="front_cta_text" value="<?php echo htmlspecialchars($front_cta_text); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Tombol (CTA URL)</label>
                        <input type="text" name="front_cta_url" value="<?php echo htmlspecialchars($front_cta_url); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="#kontak atau URL penuh">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Hero Front Page</label>
                            <input type="text" name="front_hero_image" value="<?php echo htmlspecialchars($front_hero_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="https://...">
                            <p class="text-xs text-gray-500 mt-1">Bisa isi URL gambar dari Media / CDN, atau upload di bawah.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Hero dari PC / HP</label>
                            <input type="file" name="front_hero_image_file" accept="image/*" class="block w-full text-sm text-gray-700">
                            <p class="text-xs text-gray-500 mt-1">Jika diisi, gambar upload akan dipakai dan URL di atas akan diisi otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Toko</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                        <input type="text" name="store_name" value="<?php echo htmlspecialchars($store_name); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Toko</label>
                        <input type="text" name="store_phone" value="<?php echo htmlspecialchars($store_phone); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="021-...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Toko</label>
                        <input type="text" name="store_whatsapp" value="<?php echo htmlspecialchars($store_whatsapp); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="62812xxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" name="store_hours" value="<?php echo htmlspecialchars($store_hours); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="Senin - Sabtu, 09.00 - 17.00">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="store_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($store_address); ?></textarea>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Peta Toko (Google Maps)</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Embed Code / Iframe</label>
                        <textarea name="map_embed" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="Tempel kode iframe Google Maps di sini"><?php echo htmlspecialchars($map_embed); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Dukung iframe langsung dari Google Maps Embed. Akan ditampilkan di halaman Kontak / footer.</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6 border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500 max-w-md">
                    Data pengaturan tema dan produk toko akan tetap tersimpan di database meski tema diganti atau plugin dinonaktifkan. Gunakan tombol di kanan untuk menyimpan snapshot konfigurasi dan produk saat ini.
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" name="orion_garage_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-colors duration-200">
                        Simpan Pengaturan
                    </button>
                    <button type="submit" name="orion_garage_backup_submit" value="1" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 px-4 rounded shadow border border-slate-600 transition-colors duration-200">
                        Simpan Konfigurasi & Produk
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php elseif ($current_theme === 'orion-mosque'): ?>
<?php
$hero_title = get_option('orion_mosque_hero_title', 'Masjid Nyaman');
$hero_subtitle = get_option('orion_mosque_hero_subtitle', 'Penuh Cahaya Ibadah');
$hero_description = get_option('orion_mosque_hero_description', 'Pusat ibadah, kajian ilmu, dan kegiatan sosial yang ramah keluarga. Temukan jadwal shalat, agenda kegiatan, dan informasi donasi dalam satu halaman.');
$hero_image = get_option('orion_mosque_hero_image', '');

$contact_address = get_option('orion_mosque_contact_address', "Jl. Contoh Masjid No. 123\nKota Anda");
$contact_phone = get_option('orion_mosque_contact_phone', '08xx-xxxx-xxxx');
$contact_email = get_option('orion_mosque_contact_email', 'info@orionmosque.id');

$prayer_city = get_option('orion_mosque_prayer_city', 'Jakarta');
$prayer_country = get_option('orion_mosque_prayer_country', 'Indonesia');
$prayer_method = get_option('orion_mosque_prayer_method', 20);

$donation_title = get_option('orion_mosque_donation_title', 'Dukung Kegiatan Masjid');
$donation_description = get_option('orion_mosque_donation_description', 'Infaq dan sedekah Anda akan digunakan untuk operasional masjid, program kajian, santunan sosial, dan perawatan fasilitas bersama.');
$donation_account_title = get_option('orion_mosque_donation_account_title', 'Rekening Infaq Operasional');
$donation_account_detail = get_option('orion_mosque_donation_account_detail', 'Bank Syariah Contoh • 1234 5678 90 a.n. DKM Orion Mosque');
$donation_program_title = get_option('orion_mosque_donation_program_title', 'Donasi Program Sosial');
$donation_program_detail = get_option('orion_mosque_donation_program_detail', 'Santunan yatim, dhuafa, dan program Ramadhan');

$kegiatan_badge = get_option('orion_mosque_kegiatan_badge', 'Agenda');
$kegiatan_title = get_option('orion_mosque_kegiatan_title', 'Kegiatan Rutin Masjid');
$kegiatan_description = get_option('orion_mosque_kegiatan_description', 'Highlight beberapa kegiatan unggulan masjid dalam seminggu.');
?>

<div class="bg-white rounded-xl shadow p-6">
    <form method="POST" action="theme-settings.php" enctype="multipart/form-data">
        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Hero & Tagline Masjid</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama</label>
                        <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                        <input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($hero_subtitle); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="hero_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($hero_description); ?></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 items-start">
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Background Hero</label>
                        <input type="text" name="hero_image" value="<?php echo htmlspecialchars($hero_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="https://...">
                        <p class="text-xs text-gray-500 mt-1">Bisa isi URL gambar dari Media / CDN, atau upload di bawah.</p>
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Background Hero</label>
                        <input type="file" name="hero_image_file" accept="image/*" class="block w-full text-sm text-gray-700">
                        <p class="text-xs text-gray-500 mt-1">Jika diisi, gambar upload akan dipakai dan URL di atas akan diisi otomatis.</p>
                    </div>
                    <?php if ($hero_image): ?>
                        <div class="space-y-2 md:col-span-1">
                            <p class="block text-sm font-medium text-gray-700 mb-1">Preview Background Aktif</p>
                            <div class="border border-slate-200 rounded-lg overflow-hidden bg-slate-50 shadow-sm max-w-xs">
                                <div class="w-full aspect-[16/9] bg-slate-100">
                                    <img src="<?php echo htmlspecialchars($hero_image); ?>" alt="Preview background hero" class="w-full h-full object-cover">
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 break-all"><?php echo htmlspecialchars($hero_image); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Kontak Masjid</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="contact_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($contact_address); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="08xx-xxxx-xxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="info@...">
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Jadwal Shalat & Donasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">Konfigurasi Jadwal Shalat (API)</p>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" name="prayer_city" value="<?php echo htmlspecialchars($prayer_city); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="Contoh: Jakarta">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Negara</label>
                        <input type="text" name="prayer_country" value="<?php echo htmlspecialchars($prayer_country); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="Contoh: Indonesia">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Metode Perhitungan (ID AlAdhan)</label>
                        <input type="number" name="prayer_method" value="<?php echo htmlspecialchars($prayer_method); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" min="1">
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">Informasi Donasi</p>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bagian Donasi</label>
                        <input type="text" name="donation_title" value="<?php echo htmlspecialchars($donation_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat Donasi</label>
                        <textarea name="donation_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2"><?php echo htmlspecialchars($donation_description); ?></textarea>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Rekening Utama</label>
                        <input type="text" name="donation_account_title" value="<?php echo htmlspecialchars($donation_account_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Detail Rekening Utama</label>
                        <input type="text" name="donation_account_detail" value="<?php echo htmlspecialchars($donation_account_detail); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Program Donasi</label>
                        <input type="text" name="donation_program_title" value="<?php echo htmlspecialchars($donation_program_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Program Donasi</label>
                        <textarea name="donation_program_detail" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($donation_program_detail); ?></textarea>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Kegiatan Rutin Masjid</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Kecil</label>
                        <input type="text" name="kegiatan_badge" value="<?php echo htmlspecialchars($kegiatan_badge); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bagian</label>
                        <input type="text" name="kegiatan_title" value="<?php echo htmlspecialchars($kegiatan_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="kegiatan_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($kegiatan_description); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6 border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500 max-w-md">
                    Pengaturan ini akan mengisi konten hero, jadwal shalat, kontak, dan donasi pada tema Orion Mosque.
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" name="orion_mosque_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-colors duration-200">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php elseif ($current_theme === 'orion-construct'): ?>
<?php
$hero_image = get_option('orion_construct_hero_image', '');

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

$project1_image = get_option('orion_construct_project1_image', '');
$project2_image = get_option('orion_construct_project2_image', '');
$project3_image = get_option('orion_construct_project3_image', '');
?>

<div class="bg-white rounded-xl shadow p-6">
    <form method="POST" action="theme-settings.php" enctype="multipart/form-data">
        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Hero</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Hero</label>
                        <input type="text" name="hero_image" value="<?php echo htmlspecialchars($hero_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" placeholder="https://...">
                        <p class="text-xs text-gray-500 mt-1">Bisa isi URL gambar dari Media / CDN, atau upload di bawah.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Hero</label>
                        <input type="file" name="hero_image_file" accept="image/*" class="block w-full text-sm text-gray-700">
                        <p class="text-xs text-gray-500 mt-1">Jika diisi, gambar upload akan dipakai dan URL di atas akan diisi otomatis.</p>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Tentang Perusahaan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Kecil</label>
                        <input type="text" name="about_badge" value="<?php echo htmlspecialchars($about_badge); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama</label>
                        <input type="text" name="about_title" value="<?php echo htmlspecialchars($about_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paragraf 1</label>
                        <textarea name="about_paragraph1" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($about_paragraph1); ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paragraf 2</label>
                        <textarea name="about_paragraph2" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($about_paragraph2); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Box Kiri - Judul</label>
                        <input type="text" name="about_box1_title" value="<?php echo htmlspecialchars($about_box1_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Box Kiri - Isi Singkat</label>
                        <textarea name="about_box1_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($about_box1_text); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Box Kanan - Judul</label>
                        <input type="text" name="about_box2_title" value="<?php echo htmlspecialchars($about_box2_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Box Kanan - Isi Singkat</label>
                        <textarea name="about_box2_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($about_box2_text); ?></textarea>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Layanan</h2>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Kecil</label>
                            <input type="text" name="services_badge" value="<?php echo htmlspecialchars($services_badge); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bagian</label>
                            <input type="text" name="services_title" value="<?php echo htmlspecialchars($services_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Kartu Layanan 1</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tag Atas</label>
                            <input type="text" name="service1_tag" value="<?php echo htmlspecialchars($service1_tag); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="service1_title" value="<?php echo htmlspecialchars($service1_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea name="service1_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($service1_text); ?></textarea>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Kartu Layanan 2</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tag Atas</label>
                            <input type="text" name="service2_tag" value="<?php echo htmlspecialchars($service2_tag); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="service2_title" value="<?php echo htmlspecialchars($service2_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea name="service2_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($service2_text); ?></textarea>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Kartu Layanan 3</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tag Atas</label>
                            <input type="text" name="service3_tag" value="<?php echo htmlspecialchars($service3_tag); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="service3_title" value="<?php echo htmlspecialchars($service3_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea name="service3_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500"><?php echo htmlspecialchars($service3_text); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Proyek</h2>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Kecil</label>
                            <input type="text" name="projects_badge" value="<?php echo htmlspecialchars($projects_badge); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bagian</label>
                            <input type="text" name="projects_title" value="<?php echo htmlspecialchars($projects_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Proyek 1</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="project1_category" value="<?php echo htmlspecialchars($project1_category); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="project1_title" value="<?php echo htmlspecialchars($project1_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                            <textarea name="project1_summary" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2"><?php echo htmlspecialchars($project1_summary); ?></textarea>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Info Tambahan</label>
                            <input type="text" name="project1_meta" value="<?php echo htmlspecialchars($project1_meta); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Proyek 1</label>
                            <input type="text" name="project1_image" value="<?php echo htmlspecialchars($project1_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2" placeholder="https://...">
                            <input type="file" name="project1_image_file" accept="image/*" class="block w-full text-xs text-gray-700">
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Proyek 2</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="project2_category" value="<?php echo htmlspecialchars($project2_category); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="project2_title" value="<?php echo htmlspecialchars($project2_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                            <textarea name="project2_summary" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2"><?php echo htmlspecialchars($project2_summary); ?></textarea>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Info Tambahan</label>
                            <input type="text" name="project2_meta" value="<?php echo htmlspecialchars($project2_meta); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Proyek 2</label>
                            <input type="text" name="project2_image" value="<?php echo htmlspecialchars($project2_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2" placeholder="https://...">
                            <input type="file" name="project2_image_file" accept="image/*" class="block w-full text-xs text-gray-700">
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Proyek 3</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="project3_category" value="<?php echo htmlspecialchars($project3_category); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="project3_title" value="<?php echo htmlspecialchars($project3_title); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                            <textarea name="project3_summary" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2"><?php echo htmlspecialchars($project3_summary); ?></textarea>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Info Tambahan</label>
                            <input type="text" name="project3_meta" value="<?php echo htmlspecialchars($project3_meta); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Proyek 3</label>
                            <input type="text" name="project3_image" value="<?php echo htmlspecialchars($project3_image); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500 mb-2" placeholder="https://...">
                            <input type="file" name="project3_image_file" accept="image/*" class="block w-full text-xs text-gray-700">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6 border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500 max-w-md">
                    Konten halaman Tentang Perusahaan, Layanan, dan Proyek di tema Orion Construction akan mengikuti pengaturan di atas.
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" name="orion_construct_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-colors duration-200">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
<div class="bg-white rounded-xl shadow p-6">
    <p class="text-slate-600">Tema saat ini belum memiliki halaman pengaturan khusus.</p>
</div>
<?php endif; ?>

<?php require_once 'admin-footer.php'; ?>
