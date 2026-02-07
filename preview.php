<?php
/**
 * Preview Handler for Orion CMS
 * This file handles previewing posts/pages without saving them to the database.
 */

// Load Orion Core
require_once( dirname( __FILE__ ) . '/orion-load.php' );

// Access Control
if ( !is_user_logged_in() ) {
    die('Access Denied: You must be logged in to preview content.');
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If accessed directly via GET, maybe redirect to home or show error
    wp_redirect(site_url());
    exit;
}

// Get POST data
$post_title = isset($_POST['post_title']) ? stripslashes($_POST['post_title']) : '(No Title)';
$post_content = isset($_POST['post_content']) ? stripslashes($_POST['post_content']) : '';
$post_type = isset($_POST['post_type']) ? $_POST['post_type'] : 'post';
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

// Construct a mock post object
$post = new stdClass();
$post->ID = $post_id;
$post->post_title = $post_title;
$post->post_content = $post_content;
$post->post_status = 'preview'; // Virtual status
$post->post_type = $post_type;
$post->post_author = get_current_user_id();
$post->post_date = date('Y-m-d H:i:s');
$post->comment_status = 'closed';
$post->ping_status = 'closed';
$post->post_name = sanitize_title($post_title);
$post->post_parent = 0;
$post->guid = ''; // No guid for preview
$post->menu_order = 0;
$post->post_mime_type = '';
$post->comment_count = 0;

// Setup global post data
global $post;
setup_postdata($post);

// Handle Shortcodes and Filters
if (function_exists('apply_filters')) {
    $post_content = apply_filters('the_content', $post_content);
} elseif (function_exists('orion_form_shortcode_parser')) {
    $post_content = orion_form_shortcode_parser($post_content);
}

// Determine Template
// We try to load the appropriate template from the active theme
$template_dir = get_template_directory();

// Basic Header
get_header(); 
?>

<!-- Preview Notice -->
<div class="fixed top-0 left-0 w-full bg-yellow-100 border-b border-yellow-300 text-yellow-800 px-4 py-3 z-50 shadow-md text-center">
    <span class="font-bold">Preview Mode</span> - This is how your content will look. Changes are not saved yet.
    <button onclick="window.close()" class="ml-4 underline text-yellow-900 hover:text-yellow-700">Close Preview</button>
</div>
<div class="mt-16"></div> <!-- Spacer for fixed header -->

<?php
// Render Content
// Since we can't easily include theme files that do their own queries (like index.php),
// we will try to mimic a generic single page layout compatible with most themes.
// Or we can try to include 'single.php' or 'page.php' but we need to ensure they use the global $post we set up.

$rendered = false;

// If the theme has a specific template for single/page that uses the global $post, we can try to include it.
// However, many themes' index.php do their own query.
// 'orion-promo' theme's index.php does manual routing and query. 
// We should check if we can include a content-part.

if ($post_type == 'page') {
    if (file_exists($template_dir . '/page-content.php')) {
        include $template_dir . '/page-content.php';
        $rendered = true;
    }
} else {
    if (file_exists($template_dir . '/single-content.php')) {
        include $template_dir . '/single-content.php';
        $rendered = true;
    }
}

if (!$rendered) {
    // Fallback: Generic Render
    // This matches the structure seen in orion-promo/index.php
    ?>
    <div class="container mx-auto px-6 py-20 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">
            <h1 class="text-3xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4"><?php echo htmlspecialchars($post_title); ?></h1>
            
            <?php 
            // Featured Image Preview if available
            if (isset($_POST['featured_image_url']) && !empty($_POST['featured_image_url'])) {
                echo '<div class="mb-6"><img src="' . htmlspecialchars($_POST['featured_image_url']) . '" class="w-full h-auto rounded-lg shadow-sm"></div>';
            }
            ?>
            
            <div class="prose prose-slate max-w-none">
                <?php echo $post_content; ?>
            </div>
            
            <?php if ($post_type == 'post'): ?>
            <div class="mt-8 pt-4 border-t border-slate-100 text-sm text-slate-500">
                Posted on <?php echo date('F j, Y'); ?> by <?php echo get_the_author(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

get_footer();
?>
