<?php
/**
 * Migrate Product Categories
 * 
 * Separates categories for Products and News.
 * Products (identified by shop_price meta) will be moved to 'product_cat' taxonomy.
 * News (others) will remain in 'category' taxonomy.
 */

require_once 'orion-load.php';

// if (!function_exists('is_user_logged_in') || !is_user_logged_in()) {
//    die("Please log in as admin to run this script.");
// }

echo "Starting Migration...\n";

global $orion_db, $table_prefix;

$post_table = $table_prefix . 'posts';
$meta_table = $table_prefix . 'postmeta';

// Find IDs of products (posts with shop_price)
$sql = "SELECT DISTINCT p.ID, p.post_title FROM $post_table p 
        INNER JOIN $meta_table pm ON p.ID = pm.post_id 
        WHERE pm.meta_key = 'shop_price'";

$result = $orion_db->query($sql);
$product_ids = array();

if ($result) {
    while ($row = $result->fetch_object()) {
        $product_ids[$row->ID] = $row->post_title;
    }
}

echo "Found " . count($product_ids) . " products.\n";

$migrated_count = 0;

foreach ($product_ids as $pid => $title) {
    // Get current 'category' terms using available function
    $current_terms = get_the_terms($pid, 'category');
    
    if (!empty($current_terms) && is_array($current_terms)) {
        $new_term_ids = array();
        
        foreach ($current_terms as $term) {
            // wp_insert_term handles existence check internally
            $new_term = wp_insert_term($term->name, 'product_cat', array(
                'slug' => $term->slug,
                'description' => isset($term->description) ? $term->description : ''
            ));
            
            if (is_array($new_term) && isset($new_term['term_id'])) {
                $term_id = $new_term['term_id'];
                $new_term_ids[] = (int)$term_id;
                // echo "Ensured product_cat term: {$term->name}\n";
            } else {
                echo "Error processing term {$term->name}\n";
            }
        }
        
        // Assign to product_cat
        if (!empty($new_term_ids)) {
            wp_set_object_terms($pid, $new_term_ids, 'product_cat');
            
            // Remove from category
            wp_set_object_terms($pid, array(), 'category');
            
            $migrated_count++;
            echo "Migrated product: $title\n";
        }
    } else {
        // echo "Product $title has no categories.\n";
    }
}

echo "Migration complete. $migrated_count products updated.\n";
