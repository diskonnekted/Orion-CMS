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

    // Check if term is used in other taxonomies. If not, delete from terms table too.
    $check_other = $orion_db->query("SELECT term_taxonomy_id FROM $term_taxonomy_table WHERE term_id = $term_id");
    if (!$check_other || $check_other->num_rows == 0) {
        $orion_db->query("DELETE FROM $terms_table WHERE term_id = $term_id");
    }

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
 * Get term by field.
 */
function get_term_by($field, $value, $taxonomy = '', $output = OBJECT, $filter = 'raw') {
    global $orion_db, $table_prefix;
    
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    if ($field == 'id') $field = 'term_id';
    
    $value = $orion_db->real_escape_string($value);
    
    $sql = "SELECT t.*, tt.* FROM $terms_table AS t 
            INNER JOIN $term_taxonomy_table AS tt ON t.term_id = tt.term_id 
            WHERE ";
            
    if ($field == 'term_id' || $field == 'name' || $field == 'slug') {
        $sql .= "t.$field = '$value'";
    } else {
        return false; // Unsupported field for now
    }
            
    if (!empty($taxonomy)) {
        $sql .= " AND tt.taxonomy = '$taxonomy'";
    }
    
    $result = $orion_db->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return false;
}

/**
 * Get a single term.
 */
function get_term($term_id, $taxonomy = '') {
    global $orion_db, $table_prefix;
    
    $term_id = (int)$term_id;
    $terms_table = $table_prefix . 'terms';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    
    $sql = "SELECT t.*, tt.* FROM $terms_table AS t 
            INNER JOIN $term_taxonomy_table AS tt ON t.term_id = tt.term_id 
            WHERE t.term_id = $term_id";
            
    if (!empty($taxonomy)) {
        $sql .= " AND tt.taxonomy = '$taxonomy'";
    }
    
    $result = $orion_db->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}

/**
 * Set object terms.
 */
function wp_set_object_terms($object_id, $terms, $taxonomy, $append = false) {
    global $orion_db, $table_prefix;
    
    $object_id = (int)$object_id;
    $term_relationships_table = $table_prefix . 'term_relationships';
    $term_taxonomy_table = $table_prefix . 'term_taxonomy';
    $terms_table = $table_prefix . 'terms';

    if (!is_array($terms)) {
        $terms = array($terms);
    }
    
    if (!$append) {
        // Remove existing relationships for this taxonomy
        // First get term_taxonomy_ids for this object and taxonomy to delete carefully?
        // Or just delete where object_id = X AND term_taxonomy_id IN (SELECT ... taxonomy=Y)
        // Simpler: Delete all for this object, but we need to filter by taxonomy.
        // Since we don't store taxonomy in relationships, we need join or subquery.
        
        $sql_delete = "DELETE tr FROM $term_relationships_table tr 
                       INNER JOIN $term_taxonomy_table tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
                       WHERE tr.object_id = $object_id AND tt.taxonomy = '$taxonomy'";
        $orion_db->query($sql_delete);
    }
    
    foreach ($terms as $term) {
        $tt_id = 0;
        if (is_numeric($term)) {
            // It's a term_id, find tt_id
            $term = (int)$term;
            $res = $orion_db->query("SELECT term_taxonomy_id FROM $term_taxonomy_table WHERE term_id = $term AND taxonomy = '$taxonomy'");
            if ($res && $res->num_rows > 0) {
                $tt_id = $res->fetch_object()->term_taxonomy_id;
            }
        } else {
            // It's a name, insert if not exists
             $res = wp_insert_term($term, $taxonomy);
             $tt_id = $res['term_taxonomy_id'];
        }
        
        if ($tt_id) {
            // Check if relationship exists
            $check = $orion_db->query("SELECT object_id FROM $term_relationships_table WHERE object_id = $object_id AND term_taxonomy_id = $tt_id");
            if (!$check || $check->num_rows == 0) {
                 $orion_db->query("INSERT INTO $term_relationships_table (object_id, term_taxonomy_id) VALUES ($object_id, $tt_id)");
            }
        }
    }
    
    // Update counts (TODO)
}

/**
 * Get object terms.
 */
function get_the_terms($post_id, $taxonomy) {
    global $orion_db, $table_prefix;
    
    $post_id = (int)$post_id;
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
