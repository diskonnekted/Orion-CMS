<?php
/*
Plugin Name: Orion Shop Manager
Description: Manages product prices and stock for Orion Shop theme.
Version: 1.0
Author: Orion AI
*/

/**
 * Get product price
 */
function orion_shop_get_price($post_id) {
    $price = get_post_meta($post_id, 'shop_price', true);
    return $price ? $price : 0;
}

/**
 * Get product stock status
 */
function orion_shop_get_stock_status($post_id) {
    $status = get_post_meta($post_id, 'shop_stock_status', true);
    return $status ? $status : 'in_stock'; // in_stock, out_of_stock
}

/**
 * Get product stock (formatted)
 */
function orion_shop_get_stock($post_id) {
    $status = orion_shop_get_stock_status($post_id);
    if ($status === 'out_of_stock') {
        return 'Habis';
    }
    return 'Tersedia';
}

/**
 * Format price
 */
function orion_shop_format_price($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

/**
 * Get WhatsApp Link
 */
function orion_shop_get_whatsapp_url($post_id) {
    $post = get_post($post_id);
    $price = orion_shop_format_price(orion_shop_get_price($post_id));
    $phone = '628123456789'; // Dummy number as requested
    
    $text = "Halo, saya tertarik dengan produk: " . $post->post_title . " (" . $price . ")";
    $text_encoded = urlencode($text);
    
    return "https://wa.me/{$phone}?text={$text_encoded}";
}
