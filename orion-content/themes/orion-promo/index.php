<?php 
// Simple router logic for the promo theme
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle ?p=ID for posts
if (isset($_GET['p'])) {
    $page = 'single_post';
}

get_header(); 

// Router switch
switch ($page) {
    case 'single_post':
        global $orion_db, $table_prefix;
        $posts_table = $table_prefix . 'posts';
        $post_id = intval($_GET['p']);
        
        $sql = "SELECT * FROM $posts_table WHERE ID = $post_id AND post_status = 'publish'";
        $result = $orion_db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $post = $result->fetch_object();
            
            // Process content
            $content = $post->post_content;
            
            // Apply Filters
            if (function_exists('apply_filters')) {
                $content = apply_filters('the_content', $content);
            } elseif (function_exists('orion_form_shortcode_parser')) {
                $content = orion_form_shortcode_parser($content);
            }
            
            // Render Post
            echo '<div class="container mx-auto px-6 py-20 min-h-screen">';
            echo '<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">';
            
            // Featured Image
            if (function_exists('get_the_post_thumbnail_url')) {
                $thumbnail_url = get_the_post_thumbnail_url($post->ID);
                if ($thumbnail_url) {
                    echo '<div class="mb-8 rounded-xl overflow-hidden shadow-sm aspect-video">';
                    echo '<img src="' . htmlspecialchars($thumbnail_url) . '" alt="' . htmlspecialchars($post->post_title) . '" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">';
                    echo '</div>';
                }
            }
            
            echo '<h1 class="text-3xl font-bold text-slate-900 mb-2">' . htmlspecialchars($post->post_title) . '</h1>';
            
            // Date for posts
            echo '<p class="text-sm text-slate-500 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">';
            echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
            echo date('d M Y', strtotime($post->post_date));
            echo '</p>';

            echo '<div class="prose prose-slate max-w-none">' . $content . '</div>';
            echo '</div>';
            echo '</div>';
            
        } else {
            // 404 Not Found
            echo '<div class="container mx-auto py-32 text-center">';
            echo '<h1 class="text-6xl font-bold text-slate-200 mb-4">404</h1>';
            echo '<p class="text-xl text-slate-600">Berita tidak ditemukan.</p>';
            echo '<a href="index.php" class="inline-block mt-6 text-brand-600 hover:text-brand-800 font-medium">&larr; Kembali ke Beranda</a>';
            echo '</div>';
        }
        break;

    case 'download':
        // Load download page
        if (file_exists(get_template_directory() . '/download-content.php')) {
            include get_template_directory() . '/download-content.php';
        } else {
            echo '<div class="container mx-auto py-20 text-center">Error: Template download-content.php missing.</div>';
        }
        break;

    case 'documentation':
        // Load documentation page
        if (file_exists(get_template_directory() . '/documentation-content.php')) {
            include get_template_directory() . '/documentation-content.php';
        } else {
            echo '<div class="container mx-auto py-20 text-center">Error: Template documentation-content.php missing.</div>';
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
