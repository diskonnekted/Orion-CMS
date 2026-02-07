<?php 
// Simple router logic for the promo theme
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

get_header(); 

// Router switch
switch ($page) {
    case 'download':
        // Load download page
        if (file_exists(get_template_directory() . '/download-content.php')) {
            include get_template_directory() . '/download-content.php';
        } else {
            echo '<div class="container mx-auto py-20 text-center">Error: Template download-content.php missing.</div>';
        }
        break;
        
    case 'home':
        // Load home page
        if (file_exists(get_template_directory() . '/home-content.php')) {
            include get_template_directory() . '/home-content.php';
        } else {
            // Fallback content if home-content.php is missing
            echo '<div class="container mx-auto py-20 text-center">Welcome to Orion Promo</div>';
        }
        break;

    default:
        // Try to find page in database
        global $orion_db, $table_prefix;
        $posts_table = $table_prefix . 'posts';
        
        // Check if input is numeric ID or Title
        $page_id = intval($page);
        if ($page_id > 0) {
            $sql = "SELECT * FROM $posts_table WHERE ID = $page_id AND post_type = 'page' AND post_status = 'publish'";
        } else {
            $page_title = $orion_db->real_escape_string($page);
            $sql = "SELECT * FROM $posts_table WHERE post_title = '$page_title' AND post_type = 'page' AND post_status = 'publish'";
        }
        
        $result = $orion_db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $post = $result->fetch_object();
            
            // Process content
            $content = $post->post_content;
            
            // Apply Filters (Standard Orion Way)
            if (function_exists('apply_filters')) {
                $content = apply_filters('the_content', $content);
            } elseif (function_exists('orion_form_shortcode_parser')) {
                // Fallback direct call
                $content = orion_form_shortcode_parser($content);
            }
            
            // Render Page
            echo '<div class="container mx-auto px-6 py-20 min-h-screen">';
            echo '<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">';
            echo '<h1 class="text-3xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">' . htmlspecialchars($post->post_title) . '</h1>';
            echo '<div class="prose prose-slate max-w-none">' . $content . '</div>';
            echo '</div>';
            echo '</div>';
            
        } else {
            // 404 Not Found
            echo '<div class="container mx-auto py-32 text-center">';
            echo '<h1 class="text-6xl font-bold text-slate-200 mb-4">404</h1>';
            echo '<p class="text-xl text-slate-600">Halaman tidak ditemukan.</p>';
            echo '<a href="index.php" class="inline-block mt-6 text-brand-600 hover:text-brand-800 font-medium">&larr; Kembali ke Beranda</a>';
            echo '</div>';
        }
        break;
}

get_footer(); 
?>
