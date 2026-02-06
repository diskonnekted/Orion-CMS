<?php
/**
 * Orion Admin AJAX Handler
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

// Clean any previous output (e.g. notices/warnings)
if (ob_get_length()) ob_clean();

header('Content-Type: application/json');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action === 'add_category') {
    $cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
    
    if (empty($cat_name)) {
        echo json_encode(['success' => false, 'message' => 'Category name cannot be empty.']);
        exit;
    }

    $result = wp_insert_term($cat_name, 'category');
    
    if ($result && !empty($result['term_id'])) {
        echo json_encode([
            'success' => true, 
            'term_id' => $result['term_id'],
            'term_name' => htmlspecialchars($cat_name)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert category.']);
    }
    exit;
}

if ($action === 'get_media') {
    $upload_dir_rel = 'orion-content/uploads/';
    $upload_dir = ABSPATH . $upload_dir_rel;
    $upload_url = site_url('/' . $upload_dir_rel);
    
    $files = array();
    if (is_dir($upload_dir)) {
        $scandir = scandir($upload_dir);
        foreach($scandir as $file) {
            if ($file !== '.' && $file !== '..') {
                $is_image = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
                $files[] = array(
                    'name' => $file,
                    'url' => $upload_url . $file,
                    'is_image' => $is_image,
                    'time' => filemtime($upload_dir . $file)
                );
            }
        }
    }
    
    // Sort by time desc
    usort($files, function($a, $b) {
        return $b['time'] - $a['time'];
    });
    
    echo json_encode(['success' => true, 'files' => $files]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
