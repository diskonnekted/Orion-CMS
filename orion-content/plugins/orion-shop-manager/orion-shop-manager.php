<?php
/*
Plugin Name: Orion Shop Manager
Description: Manages dedicated product catalog (barang & jasa) for Orion.
Version: 1.1
Author: Orion AI
*/

if (!defined('ABSPATH')) {
    exit;
}

function orion_shop_install() {
    global $orion_db, $table_prefix;

    $table_products = $table_prefix . 'orion_products';

    $charset = 'DEFAULT CHARSET=utf8mb4';
    $sql = "CREATE TABLE IF NOT EXISTS $table_products (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        description LONGTEXT NULL,
        price INT UNSIGNED NOT NULL DEFAULT 0,
        sale_price INT UNSIGNED DEFAULT NULL,
        type ENUM('physical','digital','service') NOT NULL DEFAULT 'physical',
        stock_status ENUM('in_stock','out_of_stock','preorder') NOT NULL DEFAULT 'in_stock',
        stock_quantity INT NULL,
        unit VARCHAR(50) DEFAULT NULL,
        category VARCHAR(191) DEFAULT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB $charset;";

    $orion_db->query($sql);
}

orion_shop_install();

function orion_shop_seed_dummy_products() {
    global $orion_db, $table_prefix;

    if (function_exists('get_option') && function_exists('update_option')) {
        $seeded = get_option('orion_shop_dummy_seeded', 0);
        if ($seeded) {
            return;
        }
    }

    $table_products = $table_prefix . 'orion_products';
    $count = 0;
    if ($result = $orion_db->query("SELECT COUNT(*) AS cnt FROM $table_products")) {
        $row = $result->fetch_object();
        $count = $row ? (int)$row->cnt : 0;
    }
    if ($count > 0) {
        if (function_exists('update_option')) {
            update_option('orion_shop_dummy_seeded', 1);
        }
        return;
    }

    $items = array(
        array(
            'sku' => 'OR-OLI-10K',
            'name' => 'Paket Ganti Oli + Filter',
            'description' => 'Paket ganti oli mesin hingga 4L termasuk filter oli dan pengecekan bawah mobil.',
            'price' => 750000,
            'sale_price' => 650000,
            'type' => 'physical',
            'stock_status' => 'in_stock',
            'stock_quantity' => 10,
            'unit' => 'paket',
            'category' => 'Oli & Servis',
            'image' => ''
        ),
        array(
            'sku' => 'OR-AKI-45',
            'name' => 'Aki Maintenance Free 45 Ah',
            'description' => 'Aki MF 45 Ah untuk mobil penumpang, termasuk cek sistem pengisian.',
            'price' => 1250000,
            'sale_price' => null,
            'type' => 'physical',
            'stock_status' => 'in_stock',
            'stock_quantity' => 5,
            'unit' => 'unit',
            'category' => 'Kelistrikan',
            'image' => ''
        ),
        array(
            'sku' => 'OR-BAN-R15',
            'name' => 'Paket Ban 4 pcs R15',
            'description' => 'Paket 4 ban ukuran R15 termasuk balancing dan spooring dasar.',
            'price' => 3800000,
            'sale_price' => 3500000,
            'type' => 'physical',
            'stock_status' => 'in_stock',
            'stock_quantity' => 3,
            'unit' => 'paket',
            'category' => 'Ban & Kaki-kaki',
            'image' => ''
        ),
        array(
            'sku' => 'OR-COOL-1L',
            'name' => 'Coolant Radiator 1L',
            'description' => 'Cairan coolant radiator siap pakai, cocok untuk mayoritas mobil Jepang.',
            'price' => 85000,
            'sale_price' => null,
            'type' => 'physical',
            'stock_status' => 'in_stock',
            'stock_quantity' => 20,
            'unit' => 'botol',
            'category' => 'Radiator',
            'image' => ''
        ),
        array(
            'sku' => 'OR-WIPER-SET',
            'name' => 'Set Wiper Karet Premium',
            'description' => 'Satu set karet wiper premium depan kiri dan kanan, termasuk pemasangan.',
            'price' => 250000,
            'sale_price' => 220000,
            'type' => 'physical',
            'stock_status' => 'in_stock',
            'stock_quantity' => 15,
            'unit' => 'set',
            'category' => 'Aksesoris',
            'image' => ''
        ),
        array(
            'sku' => 'SRV-10K',
            'name' => 'Servis Berkala 10.000 km',
            'description' => 'Paket servis berkala 10.000 km termasuk pemeriksaan 20+ titik dan scanner OBD.',
            'price' => 550000,
            'sale_price' => null,
            'type' => 'service',
            'stock_status' => 'in_stock',
            'stock_quantity' => null,
            'unit' => 'layanan',
            'category' => 'Servis Berkala',
            'image' => ''
        ),
        array(
            'sku' => 'SRV-TUNEUP',
            'name' => 'Tune Up Mesin & Scanner',
            'description' => 'Tune up mesin lengkap dengan pembersihan throttle body dan scan error.',
            'price' => 650000,
            'sale_price' => null,
            'type' => 'service',
            'stock_status' => 'in_stock',
            'stock_quantity' => null,
            'unit' => 'layanan',
            'category' => 'Mesin',
            'image' => ''
        ),
        array(
            'sku' => 'SRV-DETAIL',
            'name' => 'Detailing Interior & Eksterior',
            'description' => 'Paket detailing lengkap interior dan eksterior termasuk poles bodi ringan.',
            'price' => 950000,
            'sale_price' => null,
            'type' => 'service',
            'stock_status' => 'in_stock',
            'stock_quantity' => null,
            'unit' => 'layanan',
            'category' => 'Detailing',
            'image' => ''
        ),
        array(
            'sku' => 'SRV-AC',
            'name' => 'Cek AC & Pengisian Freon',
            'description' => 'Pengecekan sistem AC, kebocoran, dan pengisian freon dasar.',
            'price' => 450000,
            'sale_price' => null,
            'type' => 'service',
            'stock_status' => 'in_stock',
            'stock_quantity' => null,
            'unit' => 'layanan',
            'category' => 'AC',
            'image' => ''
        ),
    );

    foreach ($items as $item) {
        $stmt = $orion_db->prepare("INSERT INTO $table_products (sku, name, description, price, sale_price, type, stock_status, stock_quantity, unit, category, image) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        if ($stmt) {
            $stmt->bind_param(
                'sssisssisss',
                $item['sku'],
                $item['name'],
                $item['description'],
                $item['price'],
                $item['sale_price'],
                $item['type'],
                $item['stock_status'],
                $item['stock_quantity'],
                $item['unit'],
                $item['category'],
                $item['image']
            );
            $stmt->execute();
        }
    }

    if (function_exists('update_option')) {
        update_option('orion_shop_dummy_seeded', 1);
    }
}

orion_shop_seed_dummy_products();

function orion_shop_get_product($id) {
    global $orion_db, $table_prefix;
    $table_products = $table_prefix . 'orion_products';
    $id = (int)$id;
    $result = $orion_db->query("SELECT * FROM $table_products WHERE id = $id LIMIT 1");
    return $result ? $result->fetch_object() : null;
}

function orion_shop_get_products($limit = 20) {
    global $orion_db, $table_prefix;
    $table_products = $table_prefix . 'orion_products';
    $limit = (int)$limit;
    $result = $orion_db->query("SELECT * FROM $table_products ORDER BY created_at DESC LIMIT $limit");
    $items = array();
    if ($result) {
        while ($row = $result->fetch_object()) {
            $items[] = $row;
        }
    }
    return $items;
}

function orion_shop_get_price($product_id) {
    $product = orion_shop_get_product($product_id);
    if (!$product) {
        return 0;
    }
    return $product->sale_price !== null && $product->sale_price > 0 ? (int)$product->sale_price : (int)$product->price;
}

function orion_shop_get_stock_status($product_id) {
    $product = orion_shop_get_product($product_id);
    if (!$product) {
        return 'out_of_stock';
    }
    return $product->stock_status;
}

function orion_shop_get_stock($product_id) {
    $product = orion_shop_get_product($product_id);
    if (!$product) {
        return 'Tidak tersedia';
    }
    if ($product->stock_status === 'out_of_stock') {
        return 'Habis';
    }
    if ($product->stock_status === 'preorder') {
        return 'Pre-order';
    }
    if ($product->stock_quantity !== null) {
        $unit = $product->unit ? $product->unit : 'unit';
        return $product->stock_quantity . ' ' . $unit;
    }
    return 'Tersedia';
}

function orion_shop_format_price($price) {
    return 'Rp ' . number_format((int)$price, 0, ',', '.');
}

function orion_shop_get_whatsapp_url($product_id) {
    $product = orion_shop_get_product($product_id);
    if (!$product) {
        return '#';
    }
    $price = orion_shop_format_price(orion_shop_get_price($product_id));
    $phone = '628123456789';
    if (function_exists('get_option')) {
        $custom_phone = get_option('orion_garage_store_whatsapp', '');
        if ($custom_phone !== '') {
            $digits_only = preg_replace('/[^0-9]/', '', $custom_phone);
            if ($digits_only !== '') {
                $phone = $digits_only;
            }
        }
    }
    $text = "Halo, saya tertarik dengan produk/jasa di Orion Garage: " . $product->name . " (" . $price . ")";
    $text_encoded = urlencode($text);
    return "https://wa.me/{$phone}?text={$text_encoded}";
}

function orion_shop_ensure_shop_page() {
    global $orion_db, $table_prefix;

    $table_posts = $table_prefix . 'posts';

    $exists_id = 0;
    $res = $orion_db->query("SELECT ID FROM $table_posts WHERE post_title = 'Shop' AND post_type = 'page' AND post_status = 'publish' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_object();
        $exists_id = (int)$row->ID;
    }

    if ($exists_id > 0) {
        return;
    }

    if (!function_exists('wp_insert_post')) {
        return;
    }

    $page_data = array(
        'post_title'   => 'Shop',
        'post_name'    => 'shop',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page'
    );

    wp_insert_post($page_data);
}

orion_shop_ensure_shop_page();
