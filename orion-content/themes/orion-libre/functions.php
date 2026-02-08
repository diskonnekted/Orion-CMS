<?php
/**
 * Orion Libre Theme Functions
 */

// Include the PDF Reader plugin functionality if not already loaded by the system
if (!function_exists('orion_pdf_viewer')) {
    $plugin_path = ABSPATH . 'orion-content/plugins/orion-pdf-reader/orion-pdf-reader.php';
    if (file_exists($plugin_path)) {
        require_once $plugin_path;
    }
}

function orion_libre_get_book_cover($post_id) {
    // Try to get gallery images first as cover
    $gallery = get_post_meta($post_id, '_gallery_images', true);
    if ($gallery) {
        $images = json_decode($gallery, true);
        if (!empty($images)) {
            return $images[0];
        }
    }
    // Fallback placeholder
    return 'https://via.placeholder.com/300x450?text=No+Cover';
}

function orion_libre_get_pdf($post_id) {
    $attachments = get_post_meta($post_id, '_attachments', true);
    if ($attachments) {
        $atts = json_decode($attachments, true);
        if (is_array($atts)) {
            foreach ($atts as $att) {
                // Check if it's a PDF (by extension or mime type assumption)
                // The structure is array('url' => ..., 'name' => ...)
                if (isset($att['url']) && strpos(strtolower($att['url']), '.pdf') !== false) {
                    return $att['url'];
                }
            }
        }
    }
    return false;
}
?>
