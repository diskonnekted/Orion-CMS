<?php
/**
 * Orion Member Theme Functions
 */

/**
 * Get the first image from post content
 */
if (!function_exists('get_first_image_from_content')) {
    function get_first_image_from_content($content) {
        if (empty($content)) return false;
        
        // Clean content first
        $content = html_entity_decode(html_entity_decode($content));
        
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
        if (isset($matches[1][0])) {
            return $matches[1][0];
        }
        return false;
    }
}

/**
 * Get a post thumbnail with fallbacks
 */
function orion_member_get_thumbnail($post_id) {
    // 1. Featured Image
    $thumb = get_the_post_thumbnail_url($post_id, 'medium');
    if ($thumb) return $thumb;
    
    // 2. Gallery
    $gallery = get_post_meta($post_id, '_gallery_images', true);
    if ($gallery) {
        $images = json_decode($gallery, true);
        if (!empty($images)) return $images[0];
    }
    
    // 3. First image in content
    $post = get_post($post_id);
    if ($post) {
        $first_img = get_first_image_from_content($post->post_content);
        if ($first_img) return $first_img;
    }
    
    return false;
}
