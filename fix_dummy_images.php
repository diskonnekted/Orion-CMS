<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'orion-load.php';

// Unsplash Direct URLs (Reliable)
$images = [
    'elektronik' => [
        'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&q=80', // Laptop
        'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80', // Phone
        'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80', // Camera
        'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=800&q=80', // Electronics
        'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&q=80', // Tech
    ],
    'fashion-pria' => [
        'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&q=80', // Shirt
        'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&q=80', // Jacket
        'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800&q=80', // Sneakers
        'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=800&q=80', // Men Fashion
    ],
    'fashion-wanita' => [
        'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&q=80', // Dress
        'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&q=80', // Bag
        'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&q=80', // Heels
        'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&q=80', // Fashion
    ],
    'peralatan-rumah' => [
        'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80', // Sofa
        'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&q=80', // Kitchen
        'https://images.unsplash.com/photo-1507473888900-52e1ad14592d?w=800&q=80', // Lamp
        'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=800&q=80', // Home
    ],
    'default' => [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80' // Product Generic
    ]
];

// Get all products
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'numberposts' => -1,
);
$posts = get_posts($args);

echo "Found " . count($posts) . " products. Updating images...\n";

foreach ($posts as $post) {
    // Get category slug
    $cats = get_the_terms($post->ID, 'product_cat');
    $slug = 'default';
    
    if ($cats && !is_wp_error($cats) && !empty($cats)) {
        $slug = $cats[0]->slug;
    }
    
    // Select image pool
    $pool = isset($images[$slug]) ? $images[$slug] : $images['default'];
    
    // Pick random image from pool
    $img_url = $pool[array_rand($pool)];
    
    // Update meta
    update_post_meta($post->ID, '_thumbnail_url', $img_url);
    
    echo "Updated Product [{$post->ID}] ($slug) -> $img_url\n";
}

echo "Done.\n";
