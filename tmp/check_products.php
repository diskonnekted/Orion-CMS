<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../orion-load.php';

$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
);
$query = new WP_Query($args);

echo "Total Products: " . $query->found_posts . "\n";

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "- " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
        $terms = get_the_terms(get_the_ID(), 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            $cat_names = array_map(function($t) { return $t->name; }, $terms);
            echo "  Category: " . implode(', ', $cat_names) . "\n";
        }
        echo "  Image: " . get_post_meta(get_the_ID(), '_thumbnail_url', true) . "\n";
    }
} else {
    echo "No products found.\n";
}

