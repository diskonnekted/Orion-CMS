<?php

function orion_products_setup() {
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'orion_products_setup');

function orion_products_slugify($value) {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9\s-]/', '', $value);
    $value = preg_replace('/\s+/', '-', $value);
    $value = preg_replace('/-+/', '-', $value);
    $value = trim($value, '-');
    return $value !== '' ? $value : 'produk';
}

function orion_products_normalize_text($value, $default = '') {
    $value = (string) $value;
    if ($value === '') return (string) $default;
    $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    if (strpos($value, '&') !== false) {
        $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }
    return $value;
}

function orion_products_get_text_option($key, $default = '') {
    if (!function_exists('get_option')) return (string) $default;
    return orion_products_normalize_text(get_option($key, $default), $default);
}

function orion_products_list_images() {
    $dir = ABSPATH . 'assets/img/products/';
    if (!is_dir($dir)) return array();
    $files = glob($dir . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
    if (!is_array($files)) return array();
    sort($files, SORT_NATURAL | SORT_FLAG_CASE);
    $urls = array();
    foreach ($files as $f) {
        $base = basename($f);
        $urls[] = site_url('/assets/img/products/' . rawurlencode($base));
    }
    return $urls;
}

function orion_products_parse_products($product_lines, $images) {
    $rows = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", (string) $product_lines))));
    $products = array();
    $seen = array();
    $img_index = 1;

    foreach ($rows as $row) {
        $parts = array_map('trim', explode('|', $row));
        $slug = '';
        $category = 'General';
        $name = '';
        $price = '';
        $desc = '';
        $img = '';

        if (count($parts) >= 6) {
            $slug = $parts[0];
            $category = $parts[1] !== '' ? $parts[1] : $category;
            $name = $parts[2];
            $price = $parts[3];
            $desc = $parts[4];
            $img = $parts[5];
        } elseif (count($parts) >= 5) {
            $slug = $parts[0];
            $category = $parts[1] !== '' ? $parts[1] : $category;
            $name = $parts[2];
            $price = $parts[3];
            $desc = $parts[4];
        } elseif (count($parts) >= 3) {
            $name = $parts[0];
            $price = $parts[1];
            $desc = $parts[2];
        } elseif (count($parts) >= 2) {
            $name = $parts[0];
            $price = $parts[1];
        } elseif (count($parts) >= 1) {
            $name = $parts[0];
        }

        if ($name === '') continue;
        if ($slug === '') $slug = orion_products_slugify($name);
        $slug = orion_products_slugify($slug);

        $base_slug = $slug;
        $n = 2;
        while (isset($seen[$slug])) {
            $slug = $base_slug . '-' . $n;
            $n++;
        }
        $seen[$slug] = true;

        if ($img === '') {
            if (isset($images[$img_index])) {
                $img = $images[$img_index];
                $img_index++;
            } elseif (!empty($images)) {
                $img = $images[0];
            }
        } else {
            $img = trim($img);
        }

        $products[] = array(
            'slug' => $slug,
            'category' => $category,
            'name' => $name,
            'price' => $price,
            'desc' => $desc,
            'img' => $img
        );
    }

    return $products;
}

function orion_products_get_products() {
    $images = orion_products_list_images();
    $defaults = implode("\n", array(
        "Radiance Cleanser|Rp 149.000|Membersihkan tanpa membuat kering.",
        "Hydra Serum|Rp 189.000|Melembapkan dan mengunci hidrasi.",
        "Glow Moisturizer|Rp 179.000|Kulit terasa kenyal sepanjang hari.",
        "Sun Shield SPF 50|Rp 159.000|Perlindungan harian, ringan dan nyaman.",
        "Velvet Lip Tint|Rp 129.000|Warna tahan lama, tidak lengket.",
        "Soft Blush|Rp 119.000|Hasil natural, mudah dibaurkan.",
    ));
    $lines = get_option('orion_products_product_lines', $defaults);
    return orion_products_parse_products($lines, $images);
}

function orion_products_get_product_by_slug($slug) {
    $slug = orion_products_slugify($slug);
    foreach (orion_products_get_products() as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

function orion_products_product_url($slug) {
    return site_url('/?product=' . rawurlencode(orion_products_slugify($slug)));
}

function orion_products_catalog_url($category = '', $q = '') {
    $url = site_url('/?page=produk');
    $params = array();
    if ($category !== '') $params['kategori'] = $category;
    if ($q !== '') $params['q'] = $q;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    return $url;
}

function orion_products_wa_buy_url($whatsapp, $message) {
    $phone = preg_replace('/[^0-9]/', '', (string) $whatsapp);
    if ($phone === '') return '#';
    $text = rawurlencode((string) $message);
    return 'https://wa.me/' . $phone . '?text=' . $text;
}

function orion_products_buy_message($brand_name, $product, $qty = 1) {
    $qty = (int) $qty;
    if ($qty < 1) $qty = 1;
    $lines = array();
    $lines[] = 'Halo admin ' . $brand_name . ', saya ingin order:';
    $lines[] = '- Produk: ' . $product['name'];
    if (!empty($product['category'])) $lines[] = '- Kategori: ' . $product['category'];
    if (!empty($product['price'])) $lines[] = '- Harga: ' . $product['price'];
    $lines[] = '- Qty: ' . $qty;
    $lines[] = '- Link: ' . orion_products_product_url($product['slug']);
    $lines[] = '';
    $lines[] = 'Nama:';
    $lines[] = 'Alamat:';
    $lines[] = 'Catatan:';
    return implode("\n", $lines);
}
