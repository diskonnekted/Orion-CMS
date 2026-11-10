<?php

function orion_admin_theme_details_json_path() {
    return ABSPATH . 'orion-content/uploads/theme-catalog/theme-details.json';
}

function orion_admin_theme_details_load_overrides() {
    $path = orion_admin_theme_details_json_path();
    if (!file_exists($path)) return array();
    $raw = file_get_contents($path);
    if (!is_string($raw) || trim($raw) === '') return array();
    $json = json_decode($raw, true);
    return is_array($json) ? $json : array();
}

function orion_admin_theme_details_normalize_list($value) {
    if (is_array($value)) {
        $out = array();
        foreach ($value as $v) {
            $v = trim((string) $v);
            if ($v !== '') $out[] = $v;
        }
        return array_values($out);
    }
    $value = trim((string) $value);
    if ($value === '') return array();
    $parts = preg_split("/\r\n|\n|\r|,/", $value);
    $out = array();
    foreach ($parts as $p) {
        $p = trim((string) $p);
        if ($p !== '') $out[] = $p;
    }
    return array_values($out);
}

function orion_admin_theme_details_apply_overrides($base, $overrides) {
    if (!is_array($base)) $base = array();
    if (!is_array($overrides)) return $base;

    foreach ($overrides as $slug => $ov) {
        if ($slug === '_plugins_catalog') continue;
        if (!is_array($ov)) continue;

        if (!isset($base[$slug]) || !is_array($base[$slug])) {
            $base[$slug] = array();
        }

        if (isset($ov['title'])) $base[$slug]['title'] = trim((string) $ov['title']);
        if (isset($ov['summary'])) $base[$slug]['summary'] = trim((string) $ov['summary']);
        if (isset($ov['highlights'])) $base[$slug]['highlights'] = orion_admin_theme_details_normalize_list($ov['highlights']);
        if (isset($ov['ideal_for'])) $base[$slug]['ideal_for'] = orion_admin_theme_details_normalize_list($ov['ideal_for']);
        if (isset($ov['plugins'])) $base[$slug]['plugins'] = orion_admin_theme_details_normalize_list($ov['plugins']);
        if (isset($ov['screenshot'])) $base[$slug]['screenshot'] = trim((string) $ov['screenshot']);
    }

    return $base;
}

function orion_admin_theme_details_data() {
    $plugins = array(
        'orion-form' => array(
            'name' => 'Orion Form',
            'desc' => 'Membuat form kontak/lead (kontak, booking, konsultasi) dan menyimpan entry.',
            'links' => array(
                array('label' => 'Aktifkan Plugin', 'url' => 'plugins.php'),
                array('label' => 'Kelola Form', 'url' => site_url('/orion-content/plugins/orion-form/admin/forms.php'))
            )
        ),
        'orion-shop-manager' => array(
            'name' => 'Orion Shop Manager',
            'desc' => 'Manajemen katalog, produk, dan fitur toko (untuk tema commerce).',
            'links' => array(
                array('label' => 'Aktifkan Plugin', 'url' => 'plugins.php'),
                array('label' => 'Shop Manager', 'url' => site_url('/orion-content/plugins/orion-shop-manager/manager.php')),
                array('label' => 'Users', 'url' => site_url('/orion-admin/users.php'))
            )
        ),
        'orion-kaih' => array(
            'name' => 'Orion KAih (Sekolah)',
            'desc' => 'Portal KAih untuk sekolah: siswa, guru, relasi, PIN orang tua, dan persetujuan misi.',
            'links' => array(
                array('label' => 'Aktifkan Plugin', 'url' => 'plugins.php'),
                array('label' => 'KAih Manager', 'url' => site_url('/orion-content/plugins/orion-kaih/manager.php'))
            )
        ),
        'orion-classic-editor' => array(
            'name' => 'Orion Classic Editor',
            'desc' => 'Editor klasik yang lebih ringan untuk penulisan konten blog/berita.',
            'links' => array(
                array('label' => 'Aktifkan Plugin', 'url' => 'plugins.php')
            )
        )
    );

    $data = array(
        'orion-products' => array(
            'title' => 'Orion Products',
            'summary' => 'Tema promosi brand produk (kosmetik) dengan landing, katalog, detail produk, dan tombol WhatsApp otomatis.',
            'highlights' => array(
                'Landing page fokus konversi: hero, manfaat, galeri, testimoni, CTA',
                'Katalog dengan filter kategori dan pencarian',
                'Halaman detail per produk dengan tombol Beli WhatsApp',
                'Cocok untuk brand kosmetik, skincare, personal care'
            ),
            'ideal_for' => array('Brand kosmetik/skincare', 'Single brand store', 'Campaign & launch produk'),
            'plugins' => array('orion-form', 'orion-shop-manager')
        ),
        'orion-school' => array(
            'title' => 'Orion School',
            'summary' => 'Tema profil sekolah: halaman institusi, PPDB, berita, dan kontak.',
            'highlights' => array(
                'Halaman sekolah siap pakai: beranda, berita, PPDB, kontak',
                'Komponen informasi sekolah (alamat, telepon, email)',
                'Struktur rapi untuk profil lembaga pendidikan'
            ),
            'ideal_for' => array('Sekolah', 'Lembaga pendidikan', 'PPDB & informasi akademik'),
            'plugins' => array('orion-kaih', 'orion-form')
        ),
        'orion-shop' => array(
            'title' => 'Orion Shop',
            'summary' => 'Tema toko online untuk menampilkan produk dan promosi penjualan.',
            'highlights' => array(
                'Layout katalog yang cocok untuk display produk',
                'Section promo untuk konversi',
                'Cocok untuk UMKM dan retail'
            ),
            'ideal_for' => array('Toko online', 'UMKM', 'Katalog produk'),
            'plugins' => array('orion-shop-manager', 'orion-form')
        ),
        'orion-promo' => array(
            'title' => 'Orion Promo',
            'summary' => 'Tema landing page untuk campaign/promosi dengan CTA kuat.',
            'highlights' => array(
                'Section hero + benefit + CTA',
                'Cocok untuk lead generation dan promosi event',
                'Ringan dan fokus konversi'
            ),
            'ideal_for' => array('Landing page', 'Promosi event', 'Lead generation'),
            'plugins' => array('orion-form')
        ),
        'orion-portfolio' => array(
            'title' => 'Orion Portfolio',
            'summary' => 'Tema portofolio untuk showcase karya, project, dan profil jasa.',
            'highlights' => array(
                'Tampilan galeri/portofolio rapi',
                'Cocok untuk freelancer/agency',
                'CTA menuju kontak'
            ),
            'ideal_for' => array('Portofolio', 'Freelancer', 'Agency'),
            'plugins' => array('orion-form')
        ),
        'orion-magazine' => array(
            'title' => 'Orion Magazine',
            'summary' => 'Tema blog/magazine untuk berita, artikel, dan konten rutin.',
            'highlights' => array(
                'Layout nyaman untuk membaca',
                'Cocok untuk portal berita dan blog',
                'Mendukung pengelolaan konten intensif'
            ),
            'ideal_for' => array('Blog', 'Magazine', 'Portal berita'),
            'plugins' => array('orion-classic-editor')
        ),
        'orion-bakery' => array(
            'title' => 'Orion Bakery',
            'summary' => 'Tema promosi produk bakery/food dengan visual kuat dan CTA order.',
            'highlights' => array(
                'Layout menu/produk yang menggugah',
                'Cocok untuk toko roti dan F&B',
                'CTA order via kontak'
            ),
            'ideal_for' => array('Bakery', 'Kafe', 'F&B'),
            'plugins' => array('orion-form', 'orion-shop-manager')
        ),
        'orion-government' => array(
            'title' => 'Orion Government',
            'summary' => 'Tema profil instansi untuk informasi publik, program, dan layanan.',
            'highlights' => array(
                'Tampilan formal dan informatif',
                'Cocok untuk instansi pemerintah/komunitas',
                'Section layanan dan informasi'
            ),
            'ideal_for' => array('Instansi', 'Organisasi', 'Layanan publik'),
            'plugins' => array('orion-form')
        ),
        'orion-smartvillage' => array(
            'title' => 'Orion Smartvillage',
            'summary' => 'Tema portal desa/komunitas untuk layanan, informasi, dan pengumuman.',
            'highlights' => array(
                'Struktur portal informatif',
                'Cocok untuk desa/kelurahan/komunitas',
                'CTA layanan dan kontak'
            ),
            'ideal_for' => array('Portal desa', 'Komunitas', 'Informasi publik'),
            'plugins' => array('orion-form')
        ),
        'orion-pmc' => array(
            'title' => 'Orion PMC',
            'summary' => 'Tema profil jasa profesional/kontraktor dengan section layanan dan CTA.',
            'highlights' => array(
                'Section layanan dan portfolio',
                'Cocok untuk perusahaan jasa/kontraktor',
                'CTA konsultasi'
            ),
            'ideal_for' => array('Perusahaan jasa', 'Kontraktor', 'Konsultan'),
            'plugins' => array('orion-form')
        ),
        'orion-construction' => array(
            'title' => 'Orion Construction',
            'summary' => 'Tema profil bisnis konstruksi dengan showcase proyek dan kontak.',
            'highlights' => array(
                'Showcase proyek + CTA',
                'Cocok untuk konstruksi dan property',
                'Tampilan tegas dan profesional'
            ),
            'ideal_for' => array('Konstruksi', 'Property', 'Developer'),
            'plugins' => array('orion-form')
        ),
        'orion-developer' => array(
            'title' => 'Orion Developer',
            'summary' => 'Tema perusahaan developer/property dengan listing dan promosi.',
            'highlights' => array(
                'Cocok untuk promosi proyek',
                'Section benefit dan CTA',
                'Tampilan premium'
            ),
            'ideal_for' => array('Developer', 'Property', 'Real estate'),
            'plugins' => array('orion-form')
        ),
        'orion-garage' => array(
            'title' => 'Orion Garage',
            'summary' => 'Tema otomotif/garage untuk layanan servis, promo, dan booking.',
            'highlights' => array(
                'Service list dan promo',
                'Cocok untuk bengkel dan otomotif',
                'CTA booking/kontak'
            ),
            'ideal_for' => array('Bengkel', 'Otomotif', 'Servis'),
            'plugins' => array('orion-form')
        ),
        'orion-libre' => array(
            'title' => 'Orion Libre',
            'summary' => 'Tema serbaguna dengan layout modern untuk banyak kebutuhan.',
            'highlights' => array(
                'Layout fleksibel',
                'Cocok untuk profil bisnis atau personal',
                'CTA dan section promosi'
            ),
            'ideal_for' => array('Company profile', 'Personal brand', 'Landing serbaguna'),
            'plugins' => array('orion-form')
        ),
        'orion-one' => array(
            'title' => 'Orion One',
            'summary' => 'Tema serbaguna untuk company profile modern dan ringkas.',
            'highlights' => array(
                'Hero + fitur + CTA',
                'Cocok untuk berbagai bisnis',
                'Tampilan bersih'
            ),
            'ideal_for' => array('Company profile', 'Jasa', 'Produk'),
            'plugins' => array('orion-form')
        ),
        'orion-member' => array(
            'title' => 'Orion Member',
            'summary' => 'Tema membership/komunitas (landing) untuk promosi program/keanggotaan.',
            'highlights' => array(
                'Section benefit membership',
                'CTA daftar/bergabung',
                'Cocok untuk komunitas'
            ),
            'ideal_for' => array('Komunitas', 'Membership', 'Program'),
            'plugins' => array('orion-form')
        ),
        'orion-mosque' => array(
            'title' => 'Orion Mosque',
            'summary' => 'Tema masjid untuk informasi kegiatan, program, dan kontak.',
            'highlights' => array(
                'Tampilan bersih dan informatif',
                'Cocok untuk masjid dan kegiatan komunitas',
                'CTA informasi/kontak'
            ),
            'ideal_for' => array('Masjid', 'Komunitas', 'Kegiatan'),
            'plugins' => array('orion-form')
        ),
        'islamic-center' => array(
            'title' => 'Islamic Center',
            'summary' => 'Tema Islamic Center untuk profil, kegiatan, dan informasi publik.',
            'highlights' => array(
                'Cocok untuk pusat kegiatan Islam',
                'Section program dan informasi',
                'CTA kontak/konfirmasi'
            ),
            'ideal_for' => array('Islamic Center', 'Komunitas', 'Kegiatan'),
            'plugins' => array('orion-form')
        ),
        '_plugins_catalog' => $plugins
    );

    $overrides = orion_admin_theme_details_load_overrides();
    $data = orion_admin_theme_details_apply_overrides($data, $overrides);

    return $data;
}
