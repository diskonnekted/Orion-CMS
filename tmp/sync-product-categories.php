<?php
// Load Orion Core
require_once '../orion-load.php';

// Categories for Products
$categories = [
    ['name' => 'Elektronik', 'slug' => 'electronics'],
    ['name' => 'Fashion Pria', 'slug' => 'men-fashion'],
    ['name' => 'Fashion Wanita', 'slug' => 'women-fashion'],
    ['name' => 'Rumah & Dapur', 'slug' => 'home-living'],
    ['name' => 'Komputer & Laptop', 'slug' => 'computers'],
    ['name' => 'Handphone & Tablet', 'slug' => 'mobile'],
    ['name' => 'Ibu & Bayi', 'slug' => 'mom-baby'],
    ['name' => 'Kesehatan & Kecantikan', 'slug' => 'health-beauty'],
    ['name' => 'Olahraga & Outdoor', 'slug' => 'sports'],
    ['name' => 'Otomotif', 'slug' => 'automotive'],
];

echo "Syncing product categories (product_cat)...\n";

foreach ($categories as $cat) {
    // Insert into 'product_cat' taxonomy
    $result = wp_insert_term($cat['name'], 'product_cat', [
        'slug' => $cat['slug']
    ]);
    
    if (is_array($result) && isset($result['term_id'])) {
        echo "Synced product category: {$cat['name']} (ID: {$result['term_id']})\n";
    } else {
        echo "Failed to sync product category: {$cat['name']}\n";
    }
}

echo "Done.\n";
?>
