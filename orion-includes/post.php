<?php
/**
 * Orion CMS Post API
 */

/**
 * Insert or update a post.
 *
 * @param array $postarr
 * @return int|false Post ID on success, false on failure.
 */
function wp_insert_post($postarr) {
    global $orion_db, $table_prefix;
    
    $defaults = array(
        'ID' => 0,
        'post_content' => '',
        'post_title' => '',
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_author' => 1,
        'post_date' => date('Y-m-d H:i:s'),
    );

    $postarr = array_merge($defaults, $postarr);
    
    // Sanitize
    $post_title = $orion_db->real_escape_string($postarr['post_title']);
    $post_content = $orion_db->real_escape_string($postarr['post_content']);
    $post_status = $orion_db->real_escape_string($postarr['post_status']);
    $post_type = $orion_db->real_escape_string($postarr['post_type']);
    $post_date = $orion_db->real_escape_string($postarr['post_date']);
    $post_author = (int) $postarr['post_author'];

    $table = $table_prefix . 'posts';

    if ( !empty($postarr['ID']) ) {
        // Update
        $ID = (int) $postarr['ID'];
        $sql = "UPDATE $table SET 
            post_title = '$post_title',
            post_content = '$post_content',
            post_status = '$post_status',
            post_modified = NOW()
            WHERE ID = $ID";
            
        if ($orion_db->query($sql)) {
            return $ID;
        }
    } else {
        // Insert
        $sql = "INSERT INTO $table (post_author, post_date, post_content, post_title, post_status, post_type, post_modified)
                VALUES ($post_author, '$post_date', '$post_content', '$post_title', '$post_status', '$post_type', NOW())";
        
        if ($orion_db->query($sql)) {
            return $orion_db->insert_id;
        }
    }

    return false;
}

/**
 * Update a post in the database.
 *
 * @param array $postarr
 * @return int|false Post ID on success, false on failure.
 */
function wp_update_post($postarr = array()) {
    if ( isset($postarr['ID']) ) {
        return wp_insert_post($postarr);
    }
    return false;
}

/**
 * Retrieve list of posts.
 *
 * @param array $args
 * @return array List of posts
 */
function get_posts($args = null) {
    global $orion_db, $table_prefix;

    $defaults = array(
        'numberposts' => 5,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish'
    );
    
    // Merge args logic simpler for now
    $limit = isset($args['numberposts']) ? (int) $args['numberposts'] : 5;
    $offset = isset($args['offset']) ? (int) $args['offset'] : 0;
    $table = $table_prefix . 'posts';
    
    // Build Where Clause
    $where = "WHERE 1=1";
    
    // Post Type Handling
    if (isset($args['post_type'])) {
        if (is_array($args['post_type'])) {
            $types = array_map(function($t) use ($orion_db) { return "'" . $orion_db->real_escape_string($t) . "'"; }, $args['post_type']);
            if (!empty($types)) {
                $where .= " AND post_type IN (" . implode(',', $types) . ")";
            }
        } else {
            $post_type = $orion_db->real_escape_string($args['post_type']);
            if ($post_type !== 'any') {
                $where .= " AND post_type = '$post_type'";
            }
        }
    } else {
        $where .= " AND post_type = 'post'";
    }

    // Post Status Handling
    if (isset($args['post_status'])) {
        if (is_array($args['post_status'])) {
            $statuses = array_map(function($s) use ($orion_db) { return "'" . $orion_db->real_escape_string($s) . "'"; }, $args['post_status']);
            if (!empty($statuses)) {
                $where .= " AND post_status IN (" . implode(',', $statuses) . ")";
            }
        } else {
            $post_status = $orion_db->real_escape_string($args['post_status']);
            if ($post_status !== 'any') {
                $where .= " AND post_status = '$post_status'";
            }
        }
    } else {
        $where .= " AND post_status = 'publish'";
    }
    
    // Handle specific IDs
    if (isset($args['include'])) {
        $ids = implode(',', array_map('intval', (array) $args['include']));
        if ($ids) {
            $where .= " AND ID IN ($ids)";
        }
    }
    
    if (isset($args['p'])) {
        $pid = (int) $args['p'];
        $where .= " AND ID = $pid";
    }
    
    if (isset($args['page_id'])) {
        $pid = (int) $args['page_id'];
        $where .= " AND ID = $pid";
    }

    // Category/Term Filter
    if (isset($args['category']) || isset($args['taxonomy'])) {
        $taxonomy = isset($args['taxonomy']) ? $orion_db->real_escape_string($args['taxonomy']) : 'category';
        $cat_id = isset($args['category']) ? (int) $args['category'] : 0;
        
        $term_relationships = $table_prefix . 'term_relationships';
        $term_taxonomy = $table_prefix . 'term_taxonomy';

        if ($cat_id > 0) {
            // Specific category
            $where .= " AND ID IN (
                SELECT object_id FROM $term_relationships tr
                INNER JOIN $term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tt.taxonomy = '$taxonomy' AND tt.term_id = $cat_id
            )";
        } elseif (isset($args['taxonomy'])) {
            // Any category in this taxonomy
            $where .= " AND ID IN (
                SELECT object_id FROM $term_relationships tr
                INNER JOIN $term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tt.taxonomy = '$taxonomy'
            )";
        }
    }

    // Search Logic
    if (isset($args['s']) && !empty($args['s'])) {
        $search_term = $orion_db->real_escape_string($args['s']);
        $where .= " AND (post_title LIKE '%$search_term%' OR post_content LIKE '%$search_term%')";
    }

    // Order By Logic
    $orderby_sql = "post_date";
    if (isset($args['orderby'])) {
        if ($args['orderby'] == 'rand') {
            $orderby_sql = "RAND()";
        } elseif ($args['orderby'] == 'title') {
            $orderby_sql = "post_title";
        } elseif ($args['orderby'] == 'ID') {
            $orderby_sql = "ID";
        }
    }
    
    // Order Logic
    $order_sql = "DESC";
    if (isset($args['order']) && strtoupper($args['order']) == 'ASC') {
        $order_sql = "ASC";
    }

    $sql = "SELECT * FROM $table $where ORDER BY $orderby_sql $order_sql";
    
    if ($limit > 0) {
        $sql .= " LIMIT $limit";
    }
    
    if ($offset > 0) {
        $sql .= " OFFSET $offset";
    }

    $result = $orion_db->query($sql);
    $posts = array();
    
    if ($result) {
        while ($row = $result->fetch_object()) {
            $posts[] = $row;
        }
    }
    
    return $posts;
}

/**
 * Count number of posts by status
 *
 * @param string $type Post type
 * @param string $perm User permission (unused)
 * @return object Object containing post counts by status
 */
function wp_count_posts($type = 'post', $perm = '') {
    global $orion_db, $table_prefix;
    $type = $orion_db->real_escape_string($type);
    $table = $table_prefix . 'posts';
    
    $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$table} WHERE post_type = '{$type}' GROUP BY post_status";
    $result = $orion_db->query($query);
    
    $counts = array('publish' => 0, 'draft' => 0, 'trash' => 0); // Default structure
    if ($result) {
        while ($row = $result->fetch_object()) {
            $counts[$row->post_status] = (int)$row->num_posts;
        }
    }
    
    return (object) $counts;
}

/**
 * Get single post
 */
function get_post($post_id) {
    global $orion_db, $table_prefix;
    $post_id = (int) $post_id;
    $table = $table_prefix . 'posts';
    $result = $orion_db->query("SELECT * FROM $table WHERE ID = $post_id LIMIT 1");
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}

/**
 * Delete Post
 */
function wp_delete_post($post_id) {
    global $orion_db, $table_prefix;
    $post_id = (int) $post_id;
    $table_posts = $table_prefix . 'posts';
    $table_meta = $table_prefix . 'postmeta';
    
    // Delete meta first
    $orion_db->query("DELETE FROM $table_meta WHERE post_id = $post_id");
    // Delete post
    return $orion_db->query("DELETE FROM $table_posts WHERE ID = $post_id");
}


/**
 * Update Post Meta
 */
function update_post_meta($post_id, $meta_key, $meta_value) {
    global $orion_db, $table_prefix;
    
    $post_id = (int) $post_id;
    $meta_key = $orion_db->real_escape_string($meta_key);
    $meta_value = $orion_db->real_escape_string($meta_value);
    
    $table = $table_prefix . 'postmeta';
    
    // Check if exists
    $check = $orion_db->query("SELECT meta_id FROM $table WHERE post_id = $post_id AND meta_key = '$meta_key'");
    
    if ($check && $check->num_rows > 0) {
        return $orion_db->query("UPDATE $table SET meta_value = '$meta_value' WHERE post_id = $post_id AND meta_key = '$meta_key'");
    } else {
        return $orion_db->query("INSERT INTO $table (post_id, meta_key, meta_value) VALUES ($post_id, '$meta_key', '$meta_value')");
    }
}

/**
 * Get Post Meta
 */
function get_post_meta($post_id, $meta_key = '', $single = false) {
    global $orion_db, $table_prefix;
    
    $post_id = (int) $post_id;
    $table = $table_prefix . 'postmeta';
    
    $sql = "SELECT meta_value FROM $table WHERE post_id = $post_id";
    if (!empty($meta_key)) {
        $meta_key = $orion_db->real_escape_string($meta_key);
        $sql .= " AND meta_key = '$meta_key'";
    }
    
    $result = $orion_db->query($sql);
    
    if ($result) {
        if ($single) {
            $row = $result->fetch_object();
            return $row ? $row->meta_value : '';
        } else {
            $arr = array();
            while($row = $result->fetch_object()) {
                $arr[] = $row->meta_value;
            }
            return $arr;
        }
    }
    return $single ? '' : array();
}

/**
 * Get Featured Image URL
 */
function get_the_post_thumbnail_url($post = null) {
    $post_id = get_post_id($post);
    return get_post_meta($post_id, '_thumbnail_url', true);
}

/**
 * Helper to get ID
 */
function get_post_id($post = null) {
    if (is_numeric($post)) {
        return (int) $post;
    } elseif (is_object($post)) {
        return $post->ID;
    } else {
        // Try global post if loop implemented properly later
        return 0;
    }
}
