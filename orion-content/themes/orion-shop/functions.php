<?php
/**
 * Orion Shop Theme Functions
 */

if (!function_exists('orion_shop_get_image')) {
    function orion_shop_get_image($post_id) {
        // Check for featured image first
        $featured = get_post_meta($post_id, '_thumbnail_url', true);
        if ($featured) {
            return $featured;
        }

        // Fallback to gallery meta
        $gallery = get_post_meta($post_id, '_gallery_images', true);
        if ($gallery) {
            $images = json_decode($gallery, true);
            if (!empty($images) && is_array($images)) {
                return $images[0];
            }
        }
        // Fallback placeholder with random colors for variety
        $colors = ['e5e7eb', 'f3f4f6', 'fca5a5', '93c5fd', '86efac', 'fde047'];
        $color = $colors[array_rand($colors)];
        return "https://dummyimage.com/400x400/{$color}/737373&text=Product+" . $post_id;
    }
}

if (!function_exists('orion_shop_get_price')) {
    function orion_shop_get_price($post_id) {
        $price = get_post_meta($post_id, 'shop_price', true);
        if (!$price) {
            $price = get_post_meta($post_id, 'price', true);
        }
        return $price ? $price : 0;
    }
}

if (!function_exists('orion_shop_format_price')) {
    function orion_shop_format_price($price) {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}

if (!function_exists('orion_shop_get_categories')) {
    function orion_shop_get_categories() {
        if (function_exists('get_terms')) {
            $terms = get_terms('product_cat', ['hide_empty' => false]);
            $cats = [];
            foreach ($terms as $term) {
                $cats[] = [
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                    'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>'
                ];
            }
            return $cats;
        }
        return [];
    }
}

if (!function_exists('orion_shop_get_rating')) {
    function orion_shop_get_rating($post_id) {
        // Mock rating logic
        // In real app, this would query the comments/reviews table
        $hash = md5($post_id);
        $rating = 3 + (hexdec(substr($hash, 0, 2)) % 21) / 10; // Random between 3.0 and 5.0
        return min(5, max(1, $rating));
    }
}

if (!function_exists('orion_shop_get_sold_count')) {
    function orion_shop_get_sold_count($post_id) {
        // Mock sold count
        $hash = md5($post_id . 'sold');
        return hexdec(substr($hash, 0, 3)) % 1000 + 10;
    }
}

if (!function_exists('orion_shop_get_stock')) {
    function orion_shop_get_stock($post_id) {
        // Mock stock
        $hash = md5($post_id . 'stock');
        return hexdec(substr($hash, 0, 2)) % 100;
    }
}

if (!function_exists('orion_shop_get_location')) {
    function orion_shop_get_location($post_id) {
        $locations = ['Jakarta Pusat', 'Jakarta Barat', 'Jakarta Selatan', 'Surabaya', 'Bandung', 'Medan', 'Tangerang', 'Bekasi', 'Kab. Bogor', 'Luar Negeri'];
        $hash = md5($post_id . 'loc');
        return $locations[hexdec(substr($hash, 0, 1)) % count($locations)];
    }
}

if (!function_exists('orion_shop_get_whatsapp_url')) {
    function orion_shop_get_whatsapp_url($post_id) {
        $title = get_the_title($post_id);
        $url = get_permalink($post_id);
        $phone = '6281234567890'; // Example phone number
        $text = "Halo, saya tertarik dengan produk: $title ($url)";
        return "https://wa.me/$phone?text=" . urlencode($text);
    }
}
?>