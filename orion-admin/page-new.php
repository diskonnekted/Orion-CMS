<?php
/**
 * Add New Page / Edit Page Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

$current_user = wp_get_current_user();
if ( !$current_user->ID ) {
    die('Access Denied: You must be logged in.');
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;
if ($post_id) {
    $post = get_post($post_id);
    // Ensure we are editing a page
    if ($post && $post->post_type !== 'page') {
        die('Invalid post type.');
    }
}

$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_data = array(
        'ID' => $post_id,
        'post_title' => $_POST['post_title'],
        'post_content' => $_POST['post_content'],
        'post_type' => 'page'
    );

    $new_post_id = wp_insert_post($post_data);

    if ($new_post_id) {
        $post_id = $new_post_id;
        $message = "Page saved successfully.";

        $upload_dir = ABSPATH . 'orion-content/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Handle Featured Image
        $feat_img_url = '';
        if (isset($_POST['featured_image_url'])) {
            $feat_img_url = $_POST['featured_image_url'];
        }

        if (!empty($_FILES['featured_image']['name'])) {
            $file_name = time() . '_feat_' . basename($_FILES['featured_image']['name']);
            $target_file = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
                $feat_img_url = site_url('/orion-content/uploads/' . $file_name);
            }
        }
        
        if (isset($_POST['featured_image_url']) || !empty($_FILES['featured_image']['name'])) {
             update_post_meta($post_id, '_thumbnail_url', $feat_img_url);
        }

        // Refresh post data
        $post = get_post($post_id);
    }
}

require_once( 'admin-header.php' );
?>

<!-- CKEditor 5 -->
<style>
.ck-editor__editable_inline {
    min-height: 400px;
}
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create( document.querySelector( '#post_content' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ]
            } )
            .catch( error => {
                console.error( error );
            } );
    });
</script>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800"><?php echo $post_id ? 'Edit Page' : 'Add New Page'; ?></h1>
</div>

<?php if($message): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Main Content Area -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="post_title">Title</label>
                <input name="post_title" id="post_title" type="text" value="<?php echo $post ? htmlspecialchars($post->post_title) : ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-xl font-bold" placeholder="Enter title here" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="post_content">Content</label>
                <textarea name="post_content" id="post_content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-96" placeholder="Start writing..."><?php echo $post ? htmlspecialchars($post->post_content) : ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- Sidebar Area -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Publish</h2>
            <div class="flex justify-between">
                <button type="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded w-full">
                    <?php echo $post_id ? 'Update' : 'Publish'; ?>
                </button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Featured Image</h2>
            <?php 
            $thumb_url = get_post_meta($post_id, '_thumbnail_url', true);
            ?>
            <div id="featured-image-preview" class="mb-4">
                 <?php if ($thumb_url): ?>
                    <img src="<?php echo $thumb_url; ?>" class="w-full h-auto mb-2 rounded border">
                <?php endif; ?>
            </div>
            <input type="hidden" name="featured_image_url" id="featured_image_url" value="<?php echo $thumb_url; ?>">
            
            <div class="flex flex-col gap-2">
                 <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow text-sm text-center">
                    <span>Upload New Image</span>
                    <input type="file" name="featured_image" class="hidden" onchange="previewFeatured(this)">
                </label>
                 <?php if ($thumb_url): ?>
                <button type="button" onclick="removeFeatured()" class="text-red-500 hover:text-red-700 text-sm">Remove Featured Image</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<script>
function previewFeatured(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('featured-image-preview').innerHTML = '<img src="'+e.target.result+'" class="w-full h-auto mb-2 rounded border">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFeatured() {
    document.getElementById('featured_image_url').value = '';
    document.getElementById('featured-image-preview').innerHTML = '';
}
</script>

<?php require_once( 'admin-footer.php' ); ?>
