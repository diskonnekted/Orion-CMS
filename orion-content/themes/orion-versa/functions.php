<?php

function orion_versa_setup() {
    register_nav_menus(array(
        'primary' => 'Menu Utama',
        'footer'  => 'Menu Footer'
    ));
}
add_action('after_setup_theme', 'orion_versa_setup');

function orion_versa_defaults_init() {
    if (!get_option('orion_versa_brand_name')) {
        update_option('orion_versa_brand_name', 'Orion Versa');
    }
    if (!get_option('orion_versa_hero_badge')) {
        update_option('orion_versa_hero_badge', 'WARNING / EMERGENCY IOT');
    }
    if (!get_option('orion_versa_hero_title')) {
        update_option('orion_versa_hero_title', 'Solusi IoT & AI untuk Mitigasi & Monitoring');
    }
    if (!get_option('orion_versa_hero_subtitle')) {
        update_option('orion_versa_hero_subtitle', 'Orion Versa adalah lini solusi IoT yang dirancang khusus untuk kondisi lokal Indonesia: bekerja di lapangan, tangguh, mudah diintegrasikan, dan bisa dipakai hingga daerah dengan jaringan terbatas.');
    }
    if (!get_option('orion_versa_whatsapp')) {
        update_option('orion_versa_whatsapp', '085117041846');
    }
    if (!get_option('orion_versa_contact_email')) {
        update_option('orion_versa_contact_email', 'info@clasnet.id');
    }
    if (!get_option('orion_versa_contact_phone')) {
        update_option('orion_versa_contact_phone', '+62 851 1704 1846');
    }
    if (!get_option('orion_versa_contact_address')) {
        update_option('orion_versa_contact_address', 'Perum Kalisemi Baru D5 No.7, Banjarnegara 53412, Indonesia');
    }
    if (!get_option('orion_versa_shop_whatsapp')) {
        update_option('orion_versa_shop_whatsapp', '081328128315');
    }

    if (!get_option('orion_versa_features')) {
        $features = implode("\n", array(
            'Bekerja di Lapangan|Solusi didesain agar benar-benar bekerja di lokasi pemasangan, bukan hanya bagus di demo.',
            'Kondisi Tropis|Dirancang untuk cuaca Indonesia dan lingkungan yang menantang.',
            'Jaringan Terpencil|Mendukung beragam konektivitas sesuai kondisi (mis. LoRa/MQTT/Satellite).',
            'Notifikasi Bertingkat|Alert otomatis ke dashboard dan WhatsApp tim lapangan.',
            'Integrasi Mudah|Struktur data dan dashboard dibuat agar mudah diintegrasikan.',
            'Command Center|Satu dashboard untuk memantau semua perangkat dan data real-time.'
        ));
        update_option('orion_versa_features', $features);
    }

    if (!get_option('orion_versa_products')) {
        $products = implode("\n", array(
            'Deteksi Longsor & Banjir|Sensor pergerakan tanah dan ketinggian air real-time dengan notifikasi bertingkat.|Mitigasi',
            'Deteksi Muatan (Load Scanner)|Pemindaian volume muatan truk (pasir/batu/batubara) non-kontak menggunakan LiDAR.|Industri',
            'Stasiun Cuaca|Monitoring curah hujan, kelembapan, suhu, arah angin untuk pertanian & konstruksi.|Cuaca',
            'Divitrack Scanner|Pelacakan personel di lokasi bencana memakai kombinasi GPS/RFID/sensor.|Operasi',
            'Command Center|Dashboard terpadu untuk visualisasi data real-time, histori, notifikasi, dan kontrol.|Dashboard'
        ));
        update_option('orion_versa_products', $products);
    }

    if (!get_option('orion_versa_pricing')) {
        $pricing = implode("\n", array(
            'Mitigasi Bencana|Custom|Deteksi banjir/longsor dan respons cepat|Sensor banjir & longsor|Notifikasi bertingkat|Command Center|Integrasi tim lapangan',
            'Industri & Logistik|Custom|Transparansi data lapangan untuk operasional|Load Scanner LiDAR|Dashboard & histori|Integrasi sistem|Pelaporan',
            'Pertanian & Konstruksi|Custom|Monitoring cuaca dan kondisi lingkungan|Stasiun Cuaca|Laporan & histori|Notifikasi otomatis|Akses mobile'
        ));
        update_option('orion_versa_pricing', $pricing);
    }

    if (!get_option('orion_versa_ongkos_rows')) {
        $rows = implode("\n", array(
            'Survey Lokasi|Mulai Rp 250rb|Cek titik pemasangan dan kebutuhan jaringan.',
            'Instalasi Per Titik|Mulai Rp 350rb|Pemasangan perangkat + testing dasar.',
            'Kalibrasi & Setting|Mulai Rp 150rb|Setting notifikasi, threshold, dan dashboard.',
            'Maintenance Berkala|Mulai Rp 200rb|Pengecekan rutin, kebersihan sensor, dan validasi data.',
            'Pelatihan Operator|Mulai Rp 300rb|Training penggunaan dashboard & SOP respon.',
            'Custom Integrasi|Sesuai scope|Integrasi API, laporan, atau sistem pihak ketiga.'
        ));
        update_option('orion_versa_ongkos_rows', $rows);
    }

    if (!get_option('orion_versa_faq')) {
        $faq = implode("\n", array(
            'Apa itu Orion Versa?|Lini solusi IoT & AI untuk kondisi lokal Indonesia: monitoring, mitigasi, dan dashboard terpadu.',
            'Produk unggulannya apa saja?|Load Scanner, deteksi longsor & banjir, stasiun cuaca, Divitrack, dan Command Center.',
            'Apakah ada notifikasi WhatsApp?|Ada. Notifikasi bisa dibuat bertingkat sesuai threshold dan SOP tim lapangan.',
            'Apakah ada instalasi dan maintenance?|Ada. Ongkos survey, instalasi, dan maintenance bisa dilihat di halaman Ongkos Layanan.',
            'Bagaimana cara mulai?|Klik Booking Instalasi, tulis kebutuhan lokasi/alat, lalu tim kami akan menghubungi.'
        ));
        update_option('orion_versa_faq', $faq);
    }

    $md_flag = get_option('orion_versa_md_concept_v1', '0');
    if ($md_flag !== '1') {
        $touch = array(
            'orion_versa_brand_name' => 'Orion Versa',
            'orion_versa_hero_badge' => 'WARNING / EMERGENCY IOT',
            'orion_versa_hero_title' => 'Solusi IoT & AI untuk Mitigasi & Monitoring',
            'orion_versa_hero_subtitle' => 'Orion Versa adalah lini solusi IoT yang dirancang khusus untuk kondisi lokal Indonesia: bekerja di lapangan, tangguh, mudah diintegrasikan, dan bisa dipakai hingga daerah dengan jaringan terbatas.',
            'orion_versa_whatsapp' => '085117041846',
            'orion_versa_contact_email' => 'info@clasnet.id',
            'orion_versa_contact_phone' => '+62 851 1704 1846',
            'orion_versa_contact_address' => 'Perum Kalisemi Baru D5 No.7, Banjarnegara 53412, Indonesia',
        );
        foreach ($touch as $k => $v) {
            $cur = (string) get_option($k, '');
            if (trim($cur) === '') {
                update_option($k, $v);
            }
        }
        update_option('orion_versa_md_concept_v1', '1');
    }
}
add_action('init', 'orion_versa_defaults_init');

function orion_versa_auto_activate_shop_plugin() {
    $done = get_option('orion_versa_shop_plugin_autoloaded_v1', '0');
    if ($done === '1') return;

    $plugin_rel = 'orion-shop-manager/orion-shop-manager.php';
    $plugin_file = ABSPATH . 'orion-content/plugins/' . $plugin_rel;
    if (!file_exists($plugin_file)) {
        update_option('orion_versa_shop_plugin_autoloaded_v1', '1');
        return;
    }

    $active_plugins = get_option('active_plugins', array());
    if (!is_array($active_plugins)) $active_plugins = array();
    if (!in_array($plugin_rel, $active_plugins, true)) {
        $active_plugins[] = $plugin_rel;
        update_option('active_plugins', $active_plugins);
    }

    update_option('orion_versa_shop_plugin_autoloaded_v1', '1');
}
add_action('init', 'orion_versa_auto_activate_shop_plugin', 1);

function orion_versa_parse_rows($raw, $min_cols = 1) {
    $raw = (string) $raw;
    $lines = preg_split("/\r\n|\n|\r/", $raw);
    $out = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') continue;
        $cols = array_map('trim', explode('|', $line));
        if ($min_cols > 1 && count($cols) < $min_cols) continue;
        $out[] = $cols;
    }
    return $out;
}

function orion_versa_whatsapp_url($text = '') {
    $wa = (string) get_option('orion_versa_whatsapp', '081328128315');
    $wa = preg_replace('/[^0-9]/', '', $wa);
    if (substr($wa, 0, 1) === '0') {
        $wa = '62' . substr($wa, 1);
    }
    $msg = $text !== '' ? $text : 'Halo Orion Versa, saya ingin konsultasi solusi IoT & AI (mitigasi bencana / monitoring / command center).';
    return 'https://wa.me/' . $wa . '?text=' . urlencode($msg);
}

function orion_versa_normalize_wa_number($wa) {
    $wa = (string) $wa;
    $wa = preg_replace('/[^0-9]/', '', $wa);
    if ($wa === '') return '';
    if (strpos($wa, '00') === 0) $wa = substr($wa, 2);
    if (strpos($wa, '62') === 0) return $wa;
    if (substr($wa, 0, 1) === '0') return '62' . substr($wa, 1);
    return $wa;
}

function orion_versa_shop_buy_url($product_name, $product_price, $product_sku = '', $product_url = '') {
    $wa = (string) get_option('orion_versa_shop_whatsapp', '081328128315');
    $wa = orion_versa_normalize_wa_number($wa);
    if ($wa === '') $wa = '6281328128315';

    $name = trim((string) $product_name);
    $price = trim((string) $product_price);
    $sku = trim((string) $product_sku);
    $url = trim((string) $product_url);

    $lines = array();
    $lines[] = "Halo, saya ingin beli:";
    if ($name !== '') $lines[] = $name;
    if ($sku !== '') $lines[] = "SKU: " . $sku;
    if ($price !== '') $lines[] = "Harga: " . $price;
    if ($url !== '') $lines[] = "Link: " . $url;
    $text = implode("\n", $lines);

    return 'https://wa.me/' . $wa . '?text=' . urlencode($text);
}

function orion_versa_shop_format_price($amount) {
    $amount = (int) $amount;
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function orion_versa_resolve_media_url($value) {
    $value = trim((string) $value);
    if ($value === '') return '';

    if (preg_match('#^https?://#i', $value)) {
        return $value;
    }

    if ($value[0] !== '/') {
        $value = '/' . $value;
    }

    return site_url($value);
}

function orion_versa_ensure_pages() {
    if (!function_exists('wp_insert_post')) return;

    global $orion_db, $table_prefix;
    if (!$orion_db) return;
    $table = $table_prefix . 'posts';

    $pages = array(
        'orion_versa_page_pricing_id' => array(
            'title' => 'Harga & Paket',
            'content' => '<h2>Harga & Paket</h2><p>Halaman ini menampilkan paket perangkat IoT Orion. Anda bisa menyesuaikan detail perangkat, layanan instalasi, dan maintenance.</p>'
        ),
        'orion_versa_page_booking_id' => array(
            'title' => 'Booking / Demo',
            'content' => '<h2>Booking / Demo</h2><p>Silakan isi form untuk booking demo atau konsultasi. Tim kami akan menghubungi Anda.</p>'
        ),
        'orion_versa_page_ongkos_id' => array(
            'title' => 'Ongkos Layanan',
            'content' => '<h2>Ongkos Layanan</h2><p>Gunakan halaman ini untuk menampilkan ongkos survey, instalasi, setting, dan maintenance perangkat IoT.</p>'
        ),
        'orion_versa_page_shop_id' => array(
            'title' => 'Shop',
            'content' => ''
        )
    );

    foreach ($pages as $option_key => $page) {
        $title = $orion_db->real_escape_string($page['title']);
        $res = $orion_db->query("SELECT ID FROM $table WHERE post_title = '$title' AND post_type = 'page' AND post_status = 'publish' LIMIT 1");
        $id = 0;
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_object();
            $id = (int) $row->ID;
        } else {
            $id = (int) wp_insert_post(array(
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
        }
        if ($id > 0) {
            update_option($option_key, (string) $id);
        }
    }
}
add_action('init', 'orion_versa_ensure_pages');

function orion_versa_booking_path() {
    return ABSPATH . 'orion-content/uploads/versa/bookings.jsonl';
}

function orion_versa_handle_booking_submit() {
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (!isset($_POST['orion_versa_booking_submit'])) return;

    $booking_page_id = (int) get_option('orion_versa_page_booking_id', 0);
    $current_page_id = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;
    if (!$booking_page_id || $current_page_id !== $booking_page_id) return;

    $nama = isset($_POST['nama']) ? trim((string) $_POST['nama']) : '';
    $wa = isset($_POST['whatsapp']) ? trim((string) $_POST['whatsapp']) : '';
    $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
    $perusahaan = isset($_POST['perusahaan']) ? trim((string) $_POST['perusahaan']) : '';
    $kebutuhan = isset($_POST['kebutuhan']) ? trim((string) $_POST['kebutuhan']) : '';
    $paket = isset($_POST['paket']) ? trim((string) $_POST['paket']) : '';

    if ($nama === '' || $wa === '') {
        header('Location: ' . site_url('/?page_id=' . $booking_page_id . '&error=1'));
        exit;
    }

    $dir = dirname(orion_versa_booking_path());
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $entry = array(
        'id' => bin2hex(random_bytes(8)),
        'submitted_at' => date('c'),
        'nama' => $nama,
        'whatsapp' => $wa,
        'email' => $email,
        'perusahaan' => $perusahaan,
        'kebutuhan' => $kebutuhan,
        'paket' => $paket,
        'ip' => isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '',
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : ''
    );

    $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($line) {
        @file_put_contents(orion_versa_booking_path(), $line . "\n", FILE_APPEND | LOCK_EX);
    }

    header('Location: ' . site_url('/?page_id=' . $booking_page_id . '&submitted=1'));
    exit;
}
add_action('init', 'orion_versa_handle_booking_submit');
