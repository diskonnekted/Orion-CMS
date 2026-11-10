<?php

function orion_islamic_center_register_menus() {
    register_nav_menus(array(
        'primary' => 'Main Menu',
        'footer'  => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_islamic_center_register_menus');

function orion_islamic_center_register_post_types() {
    register_post_type('santri_registration', array(
        'label' => 'Pendaftaran Santri',
        'public' => false,
        'show_in_menu' => true,
        'supports' => array('title', 'editor')
    ));

    register_post_type('ic_announcement', array(
        'label' => 'Pengumuman',
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor')
    ));
}
add_action('init', 'orion_islamic_center_register_post_types');

function orion_islamic_center_ensure_pages() {
    if (!function_exists('wp_insert_post')) {
        return;
    }

    global $orion_db, $table_prefix;
    if (!$orion_db) {
        return;
    }

    $table = $table_prefix . 'posts';

    $pages = array(
        'orion_ic_page_profile_id' => array(
            'title' => 'Profil Pesantren',
            'content' => '<h2>Profil Pesantren</h2><p>Isi profil pesantren dapat Anda sesuaikan melalui editor halaman. Sertakan sejarah, visi-misi, program unggulan, dan fasilitas.</p>'
        ),
        'orion_ic_page_program_id' => array(
            'title' => 'Program Pendidikan',
            'content' => '<h2>Program Pendidikan</h2><p>Jelaskan program unggulan pesantren: tahfidz, diniyah, akademik, dan pembinaan karakter.</p>'
        ),
        'orion_ic_page_registration_id' => array(
            'title' => 'Pendaftaran Santri',
            'content' => '<h2>Pendaftaran Santri</h2><p>Silakan isi formulir pendaftaran santri. Data akan tersimpan dan dapat dicek oleh admin.</p>'
        ),
        'orion_ic_page_schedule_id' => array(
            'title' => 'Jadwal Kegiatan',
            'content' => '<h2>Jadwal Kegiatan</h2><p>Informasi kegiatan harian dan agenda pesantren.</p>'
        ),
        'orion_ic_page_contact_id' => array(
            'title' => 'Kontak',
            'content' => '<h2>Kontak</h2><p>Silakan hubungi kami melalui WhatsApp atau datang langsung ke lokasi pesantren.</p>'
        ),
        'orion_ic_page_gallery_id' => array(
            'title' => 'Galeri Kegiatan',
            'content' => '<h2>Galeri Kegiatan</h2><p>Dokumentasi kegiatan pesantren.</p>'
        ),
        'orion_ic_page_announcements_id' => array(
            'title' => 'Pengumuman',
            'content' => '<h2>Pengumuman</h2><p>Informasi resmi dan pemberitahuan terbaru dari pesantren.</p>'
        ),
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

    if (!get_option('orion_ic_schedule_rows')) {
        $default_rows = implode("\n", array(
            'Senin-Jumat|04:15 - 05:30|Tahajjud, Shubuh, Wirid & Kajian',
            'Senin-Jumat|07:00 - 12:00|KBM / Madrasah',
            'Senin-Jumat|16:00 - 17:30|Tahfidz / Halaqah',
            'Sabtu|08:00 - 11:30|Praktik Ibadah & Keterampilan',
            'Ahad|05:30 - 07:00|Muhadharah / Kerja Bakti'
        ));
        update_option('orion_ic_schedule_rows', $default_rows);
    }

    if (get_option('orion_ic_gallery_images') === false) {
        update_option('orion_ic_gallery_images', '[]');
    }
}
add_action('init', 'orion_islamic_center_ensure_pages');

function orion_islamic_center_handle_registration() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    if (!isset($_POST['orion_ic_register'])) {
        return;
    }

    $registration_page_id = (int) get_option('orion_ic_page_registration_id', 0);
    $current_page_id = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;

    if (!$registration_page_id || $current_page_id !== $registration_page_id) {
        return;
    }

    $name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if ($name === '' || $phone === '') {
        header('Location: ' . site_url('/?page_id=' . $registration_page_id . '&error=1'));
        exit;
    }

    $registration_id = wp_insert_post(array(
        'post_title' => 'Pendaftaran Santri - ' . $name,
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'santri_registration'
    ));

    if ($registration_id) {
        $fields = array(
            'full_name' => $name,
            'gender' => isset($_POST['gender']) ? trim($_POST['gender']) : '',
            'birth_place' => isset($_POST['birth_place']) ? trim($_POST['birth_place']) : '',
            'birth_date' => isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '',
            'address' => isset($_POST['address']) ? trim($_POST['address']) : '',
            'phone' => $phone,
            'guardian_name' => isset($_POST['guardian_name']) ? trim($_POST['guardian_name']) : '',
            'guardian_phone' => isset($_POST['guardian_phone']) ? trim($_POST['guardian_phone']) : '',
            'education_level' => isset($_POST['education_level']) ? trim($_POST['education_level']) : '',
        );

        foreach ($fields as $k => $v) {
            update_post_meta($registration_id, 'ic_' . $k, $v);
        }

        header('Location: ' . site_url('/?page_id=' . $registration_page_id . '&submitted=1'));
        exit;
    }

    header('Location: ' . site_url('/?page_id=' . $registration_page_id . '&error=1'));
    exit;
}
add_action('init', 'orion_islamic_center_handle_registration');
