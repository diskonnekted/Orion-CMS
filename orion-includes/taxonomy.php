<?php
/**
 * Orion CMS Taxonomy API
 */

/**
 * Insert a new term.
 */
function wp_insert_term($term, $taxonomy, $args = array()) {
    global $orion_db, $table_prefix;
    
    $slug = isset($args['slug']) ? $args['slug'] : strtolower(str_replace(' ', '-', $term));
    $description = isset($args['description']) ? $args['description'] : '';
    $parent = isset($args['parent']) ? (int)$args['parent'] : 0;
    
    $term = $orion_db->real_escape_string($term);
    $slug = $orion_db->real_escape_string($slug);
    $description = $orion_db->real_escape_string($description);
    
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    // Check if term exists
    $check = $orion_db->query("SELECT term_id FROM $terms_table WHERE slug = '$slug'");
    if ($check && $check->num_rows > 0) {
        $term_obj = $check->fetch_object();
        $term_id = $term_obj->term_id;
    } else {
        $orion_db->query("INSERT INTO $terms_table (name, slug) VALUES ('$term', '$slug')");
        $term_id = $orion_db->insert_id;
    }
    
    // Check taxonomy
    $check_tax = $orion_db->query("SELECT term_taxonomy_id FROM $term_taxonomy_table WHERE term_id = $term_id AND taxonomy = '$taxonomy'");
    if ($check_tax && $check_tax->num_rows > 0) {
        $tax_obj = $check_tax->fetch_object();
        return array('term_id' => $term_id, 'term_taxonomy_id' => $tax_obj->term_taxonomy_id);
    } else {
        $orion_db->query("INSERT INTO $term_taxonomy_table (term_id, taxonomy, description, parent) VALUES ($term_id, '$taxonomy', '$description', $parent)");
        return array('term_id' => $term_id, 'term_taxonomy_id' => $orion_db->insert_id);
    }
}

/**
 * Update term.
 */
function wp_update_term($term_id, $taxonomy, $args = array()) {
    global $orion_db, $table_prefix;
    
    $term_id = (int) $term_id;
    $defaults = array('name' => '', 'slug' => '', 'description' => '');
    $args = array_merge($defaults, $args);
    
    $name = $orion_db->real_escape_string($args['name']);
    $slug = $orion_db->real_escape_string($args['slug']);
    $description = $orion_db->real_escape_string($args['description']);
    
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    // Update terms table (name, slug)
    if (!empty($name) || !empty($slug)) {
        $update_parts = array();
        if (!empty($name)) $update_parts[] = "name = '$name'";
        if (!empty($slug)) $update_parts[] = "slug = '$slug'";
        
        $sql = "UPDATE $terms_table SET " . implode(', ', $update_parts) . " WHERE term_id = $term_id";
        $orion_db->query($sql);
    }
    
    // Update term_taxonomy table (description)
    // Note: In WP, description is in term_taxonomy
    $sql_tt = "UPDATE $term_taxonomy_table SET description = '$description' WHERE term_id = $term_id AND taxonomy = '$taxonomy'";
    $orion_db->query($sql_tt);
    
    return array('term_id' => $term_id, 'term_taxonomy_id' => 0); // simplified return
}

/**
 * Delete a term.
 */
function wp_delete_term($term_id, $taxonomy, $args = array()) {
    global $orion_db, $table_prefix;

    $term_id = (int) $term_id;

    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    $term_relationships_table = $table_prefix . 'term_relationships';

    // Get term_taxonomy_id
    $tt_id_query = $orion_db->query("SELECT term_taxonomy_id FROM $term_taxonomy_table WHERE term_id = $term_id AND taxonomy = '$taxonomy'");
    if (!$tt_id_query || $tt_id_query->num_rows == 0) {
        return false;
    }
    $tt_obj = $tt_id_query->fetch_object();
    $tt_id = $tt_obj->term_taxonomy_id;

    // Delete from term_relationships
    $orion_db->query("DELETE FROM $term_relationships_table WHERE term_taxonomy_id = $tt_id");
    
    // Delete from term_taxonomy
    $orion_db->query("DELETE FROM $term_taxonomy_table WHERE term_taxonomy_id = $tt_id");
    
    // Delete from terms (if not used in other taxonomies? Simplified: just delete)
    $orion_db->query("DELETE FROM $terms_table WHERE term_id = $term_id");
    
    return true;
}

/**
 * Get terms.
 */
function get_terms($taxonomy, $args = array()) {
    global $orion_db, $table_prefix;
    
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    $sql = "SELECT t.*, tt.* FROM $terms_table AS t 
            INNER JOIN $term_taxonomy_table AS tt ON t.term_id = tt.term_id 
            WHERE tt.taxonomy = '$taxonomy'";
    
    if (isset($args['hide_empty']) && $args['hide_empty']) {
        $sql .= " AND tt.count > 0";
    }
    
    $result = $orion_db->query($sql);
    $terms = array();
    if ($result) {
        while($row = $result->fetch_object()) {
            $terms[] = $row;
        }
    }
    return $terms;
}

/**
 * Get a single term.
 */
function get_term($term_id, $taxonomy) {
    global $orion_db, $table_prefix;
    
    $term_id = (int) $term_id;
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    $sql = "SELECT t.*, tt.* FROM $terms_table AS t 
            INNER JOIN $term_taxonomy_table AS tt ON t.term_id = tt.term_id 
            WHERE t.term_id = $term_id AND tt.taxonomy = '$taxonomy' LIMIT 1";
            
    $result = $orion_db->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return false;
}

/**
 * Set object terms (Add/Update terms for a post).
 * Replaces existing terms with new ones.
 */
function wp_set_object_terms($object_id, $terms, $taxonomy, $append = false) {
    global $orion_db, $table_prefix;

    $object_id = (int) $object_id;
    $term_relationships_table = $table_prefix . 'term_relationships';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    // Convert single term to array
    if (!is_array($terms)) {
        $terms = array($terms);
    }
    
    // Filter empty values
    $terms = array_filter($terms);
    
    // If not appending, clear existing terms
    if (!$append) {
        // Find existing relationship IDs to delete
        // We need to delete by term_taxonomy_id where taxonomy matches
        // Complex query: Delete from TR where object_id = X AND term_taxonomy_id IN (SELECT term_taxonomy_id FROM TT WHERE taxonomy = Y)
        // MySQL delete with join:
        $sql_delete = "DELETE tr FROM $term_relationships_table tr
                       INNER JOIN $term_taxonomy_table tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                       WHERE tr.object_id = $object_id AND tt.taxonomy = '$taxonomy'";
        $orion_db->query($sql_delete);
    }
    
    $tt_ids = array();
    
    foreach ($terms as $term) {
        if (is_numeric($term)) {
            // It's a term_id, we need term_taxonomy_id
            $term_id = (int) $term;
            $res = $orion_db->query("SELECT term_taxonomy_id FROM $term_taxonomy_table WHERE term_id = $term_id AND taxonomy = '$taxonomy' LIMIT 1");
            if ($res && $row = $res->fetch_object()) {
                $tt_ids[] = $row->term_taxonomy_id;
            }
        } else {
            // It's a slug or name, create/get it (Not fully implemented here for simplicity, assuming IDs passed from UI)
            // If needed, implement get_term_by logic here
        }
    }
    
    // Insert relationships
    $tt_ids = array_unique($tt_ids);
    foreach ($tt_ids as $tt_id) {
        // Check existence to avoid duplicate key errors if appending or if race condition
        $check = $orion_db->query("SELECT * FROM $term_relationships_table WHERE object_id = $object_id AND term_taxonomy_id = $tt_id");
        if (!$check || $check->num_rows == 0) {
            $orion_db->query("INSERT INTO $term_relationships_table (object_id, term_taxonomy_id) VALUES ($object_id, $tt_id)");
        }
    }
    
    // Update counts (Simplified: just recount all?)
    // In a full WP, we'd update count in term_taxonomy. Skipping for now unless needed.
    
    return $tt_ids;
}

/**
 * Get the terms for a post.
 */
function get_the_terms($post_id, $taxonomy) {
    global $orion_db, $table_prefix;
    
    $post_id = (int) $post_id;
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    $term_relationships_table = $table_prefix . 'term_relationships';
    
    $sql = "SELECT t.*, tt.* FROM $terms_table AS t 
            INNER JOIN $term_taxonomy_table AS tt ON t.term_id = tt.term_id 
            INNER JOIN $term_relationships_table AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
            WHERE tr.object_id = $post_id AND tt.taxonomy = '$taxonomy'";
            
    $result = $orion_db->query($sql);
    $terms = array();
    if ($result) {
        while($row = $result->fetch_object()) {
            $terms[] = $row;
        }
    }
    
    if (empty($terms)) return false;
    return $terms;
}

