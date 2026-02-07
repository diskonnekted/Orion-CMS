<?php
/**
 * Add New Post / Edit Post Screen
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
    if ($post && !current_user_can('administrator') && $post->post_author != $current_user->ID) {
        echo '<div class="p-6 text-red-500">You do not have permission to edit this post.</div>';
        require_once( 'admin-footer.php' );
        exit;
    }
}

$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Double check permission on save (in case of direct POST manipulation)
    if ($post_id) {
        $existing_post = get_post($post_id);
        if ($existing_post && !current_user_can('administrator') && $existing_post->post_author != $current_user->ID) {
            die('Access Denied');
        }
    }

    $post_data = array(
        'ID' => $post_id,
        'post_title' => $_POST['post_title'],
        'post_content' => $_POST['post_content'],
        'post_status' => isset($_POST['post_status']) ? $_POST['post_status'] : 'draft',
    );
    
    // Set author for new posts
    if (!$post_id) {
        $post_data['post_author'] = $current_user->ID;
    }

    $new_post_id = wp_insert_post($post_data);

    if ($new_post_id) {
        $post_id = $new_post_id;
        $message = "Post saved successfully.";

        // Handle Categories
        // Always call wp_set_object_terms to handle uncheck all scenarios (clearing categories)
        $categories_to_save = isset($_POST['post_category']) ? $_POST['post_category'] : array();
        
        // Sanitize categories to integers to avoid them being treated as new tag names
        $categories_to_save = array_map('intval', $categories_to_save);
        
        // Debug logging for category issue
        $debug_msg = date('Y-m-d H:i:s') . " - Post ID: $post_id - Categories Submitted: " . print_r($categories_to_save, true) . "\n";
        file_put_contents(ABSPATH . 'debug_cat_log.txt', $debug_msg, FILE_APPEND);
        
        wp_set_object_terms($post_id, $categories_to_save, 'category');

        $upload_dir = ABSPATH . 'orion-content/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Handle Featured Image
        // Priority: 1. New File Upload, 2. Selected from Library, 3. Keep Existing (handled by not updating if both empty, but we might want to clear)
        // Note: If user wants to remove, we might need a specific flag or empty url.
        // Current implementation: if URL provided (hidden input), use it. If File provided, upload and use it.
        
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
        
        // Update if we have a value (even empty string if clearing is desired, though here we only update if we have a new value or if it's explicitly cleared? 
        // For now, let's assume if hidden input is present, it reflects the desired state unless a file is uploaded)
        if (isset($_POST['featured_image_url']) || !empty($_FILES['featured_image']['name'])) {
             update_post_meta($post_id, '_thumbnail_url', $feat_img_url);
        }

        // Handle Gallery (Multiple)
        // Reconstruct from existing (submitted via hidden inputs) + new uploads
        $gallery_urls = array();
        
        if (isset($_POST['gallery_images_existing']) && is_array($_POST['gallery_images_existing'])) {
            $gallery_urls = $_POST['gallery_images_existing'];
        }

        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach($_FILES['gallery_images']['name'] as $key => $val){
                if ($_FILES['gallery_images']['name'][$key]) {
                     $file_name = time() . '_gal_' . basename($_FILES['gallery_images']['name'][$key]);
                     $target_file = $upload_dir . $file_name;
                     if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $target_file)) {
                         $gallery_urls[] = site_url('/orion-content/uploads/' . $file_name);
                     }
                }
            }
        }
        update_post_meta($post_id, '_gallery_images', json_encode($gallery_urls));

        // Handle Attachments (Multiple)
        $attachment_urls = array();
        
        // Recover existing attachments from hidden inputs? Or just keep simple for now. 
        // To support "Select from Library" for attachments, we need similar logic.
        // Assuming we might have existing attachments passed back.
        // For now, let's just append new ones to existing ones IF we don't have a way to manage them fully yet.
        // BUT, if we want to support "Select from Library", we should treat it like Gallery.
        
        // NOTE: The previous code read from DB then appended. This prevents deletion.
        // We should switch to full state management via hidden inputs.
        
        if (isset($_POST['attachments_existing']) && is_array($_POST['attachments_existing'])) {
             // We need to decode the JSON strings passed back or handle array structure
             // The hidden inputs will likely pass URL. We might lose the "name" if we just pass URL.
             // Let's assume we pass JSON string or just URL.
             // If we only pass URL, we lose the original filename if it was stored separately.
             // Let's try to pass the full object encoded in hidden input?
             foreach($_POST['attachments_existing'] as $att_json) {
                 $att = json_decode(stripslashes($att_json), true);
                 if ($att) $attachment_urls[] = $att;
             }
        }

        if (!empty($_FILES['attachments']['name'][0])) {
            foreach($_FILES['attachments']['name'] as $key => $val){
                 if ($_FILES['attachments']['name'][$key]) {
                     $file_name = time() . '_att_' . basename($_FILES['attachments']['name'][$key]);
                     $target_file = $upload_dir . $file_name;
                     if (move_uploaded_file($_FILES['attachments']['tmp_name'][$key], $target_file)) {
                         $attachment_urls[] = array(
                             'url' => site_url('/orion-content/uploads/' . $file_name),
                             'name' => $_FILES['attachments']['name'][$key]
                         );
                     }
                }
            }
        }
        update_post_meta($post_id, '_attachments', json_encode($attachment_urls));
        
        // Refresh post data
        $post = get_post($post_id);
    }
}

// Ensure default categories exist
$cats = get_terms('category', array('hide_empty' => false));
if (empty($cats)) {
    wp_insert_term('Uncategorized', 'category');
    wp_insert_term('News', 'category');
    wp_insert_term('Technology', 'category');
    $cats = get_terms('category', array('hide_empty' => false));
}

// Get current post categories
$post_cats = array();
if ($post) {
    $current_terms = get_the_terms($post->ID, 'category');
    if ($current_terms && is_array($current_terms)) {
        foreach($current_terms as $t) {
            $post_cats[] = $t->term_id;
        }
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
    let editorInstance;
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create( document.querySelector( '#post_content' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ]
            } )
            .then( editor => {
                editorInstance = editor;
            } )
            .catch( error => {
                console.error( error );
            } );
    });
</script>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800"><?php echo $post_id ? 'Edit Post' : 'Add New Post'; ?></h1>
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

        <!-- Gallery Section -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Photo Gallery</h2>
            <div class="mb-4">
                <div id="gallery-preview" class="flex flex-wrap gap-2 mb-4">
                 <?php 
                $gallery = $post ? get_post_meta($post->ID, '_gallery_images', true) : '';
                if ($gallery) {
                    $gallery_images = json_decode($gallery, true);
                    foreach($gallery_images as $img) {
                        echo '<div class="relative group h-20 w-20">
                                <img src="'.$img.'" class="h-full w-full object-cover rounded border">
                                <input type="hidden" name="gallery_images_existing[]" value="'.$img.'">
                                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">&times;</button>
                              </div>';
                    }
                }
                ?>
                </div>
                
                <div class="flex gap-2">
                    <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow text-sm">
                        <span>Upload Files</span>
                        <input type="file" name="gallery_images[]" multiple class="hidden" onchange="previewGallery(this)">
                    </label>
                    <button type="button" onclick="openMediaSelector('gallery')" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-4 border border-blue-400 rounded shadow text-sm">
                        Select from Library
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">You can upload new files or select from existing media.</p>
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Attachments (Files)</h2>
            <div class="mb-4">
                <div id="attachments-preview" class="space-y-2 mb-4">
                 <?php 
                $attachments = $post ? get_post_meta($post->ID, '_attachments', true) : '';
                if ($attachments) {
                    $att_files = json_decode($attachments, true);
                    foreach($att_files as $att) {
                        $att_json = htmlspecialchars(json_encode($att), ENT_QUOTES, 'UTF-8');
                        echo '<div class="flex items-center justify-between bg-gray-50 p-2 rounded border">
                                <a href="'.$att['url'].'" target="_blank" class="text-blue-600 hover:underline text-sm truncate">'.$att['name'].'</a>
                                <input type="hidden" name="attachments_existing[]" value="'.$att_json.'">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm ml-2">Remove</button>
                              </div>';
                    }
                }
                ?>
                </div>
                <div class="flex gap-2">
                     <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow text-sm">
                        <span>Upload Files</span>
                        <input type="file" name="attachments[]" multiple class="hidden" onchange="previewAttachments(this)">
                    </label>
                     <!-- Attachments usually need name, so library selection needs to handle name generation -->
                     <!-- For now, we allow selecting, using filename as name -->
                     <button type="button" onclick="openMediaSelector('attachment')" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-4 border border-blue-400 rounded shadow text-sm">
                        Select from Library
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Area -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Publish</h2>
            
            <div class="mb-4">
                <div class="flex justify-between gap-2">
                    <button type="button" onclick="previewPost()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded w-1/2 transition duration-200">
                        Preview
                    </button>
                    <button type="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded w-1/2 transition duration-200">
                        <?php echo $post_id ? 'Update' : 'Publish'; ?>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="post_status">Status</label>
                <select name="post_status" id="post_status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?php $status = $post ? $post->post_status : 'draft'; ?>
                    <option value="draft" <?php echo $status == 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="publish" <?php echo $status == 'publish' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>
            
            <div class="flex justify-between mt-4">
                <button type="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded w-full">
                    <?php echo $post_id ? 'Update' : 'Save'; ?>
                </button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Categories</h2>
            <div class="max-h-48 overflow-y-auto border p-2 rounded bg-gray-50">
                <?php foreach($cats as $cat): ?>
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="post_category[]" value="<?php echo $cat->term_id; ?>" id="cat-<?php echo $cat->term_id; ?>" <?php echo in_array($cat->term_id, $post_cats) ? 'checked' : ''; ?> class="h-4 w-4 text-orion-600 focus:ring-orion-500 border-gray-300 rounded">
                    <label for="cat-<?php echo $cat->term_id; ?>" class="ml-2 block text-sm text-gray-900">
                        <?php echo htmlspecialchars($cat->name); ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-2">
                 <div class="mt-4 pt-4 border-t">
    <button type="button" id="btn-add-cat-toggle" class="text-orion-600 hover:underline text-sm font-semibold">+ Add New Category</button>
    <div id="add-cat-container" class="hidden mt-2">
        <input type="text" id="new-cat-name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-2 leading-tight focus:outline-none focus:shadow-outline" placeholder="New Category Name">
        <button type="button" id="btn-add-cat-submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded text-sm border border-gray-400">Add</button>
    </div>
</div>
<script>
document.getElementById('btn-add-cat-toggle').addEventListener('click', function() {
    document.getElementById('add-cat-container').classList.toggle('hidden');
});

document.getElementById('btn-add-cat-submit').addEventListener('click', function() {
    const catNameInput = document.getElementById('new-cat-name');
    const catName = catNameInput.value;
    const btn = this;
    
    if(!catName) return;
    
    btn.disabled = true;
    btn.textContent = 'Adding...';
    
    const formData = new FormData();
    formData.append('action', 'add_category');
    formData.append('cat_name', catName);
    
    fetch('ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Add to list
            const container = document.querySelector('.max-h-48');
            const newDiv = document.createElement('div');
            newDiv.className = 'flex items-center mb-2 bg-yellow-50 p-1 rounded'; // Highlight new
            newDiv.innerHTML = `
                <input type="checkbox" name="post_category[]" value="${data.term_id}" id="cat-${data.term_id}" checked class="h-4 w-4 text-orion-600 focus:ring-orion-500 border-gray-300 rounded">
                <label for="cat-${data.term_id}" class="ml-2 block text-sm text-gray-900 font-bold">
                    ${data.term_name}
                </label>
            `;
            container.prepend(newDiv);
            
            // Reset
            catNameInput.value = '';
            document.getElementById('add-cat-container').classList.add('hidden');
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error adding category.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Add';
    });
});
</script>
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
                 <button type="button" onclick="openMediaSelector('featured')" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-4 border border-blue-400 rounded shadow text-sm">
                    Select from Library
                </button>
                 <?php if ($thumb_url): ?>
                <button type="button" onclick="removeFeatured()" class="text-red-500 hover:text-red-700 text-sm">Remove Featured Image</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<!-- Media Selector Modal -->
<div id="media-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-4xl h-[80vh] flex flex-col mx-4">
        <div class="flex justify-between items-center mb-4 border-b pb-4">
            <h2 class="text-xl font-bold">Select Media</h2>
            <button onclick="closeMediaSelector()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-2">
             <div id="media-grid" class="grid grid-cols-3 md:grid-cols-5 gap-4">
                 <!-- Media items loaded via AJAX -->
                 <p class="col-span-full text-center py-10 text-gray-500">Loading media...</p>
             </div>
        </div>
        
        <div class="mt-4 pt-4 border-t flex justify-end">
            <button onclick="closeMediaSelector()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel</button>
        </div>
    </div>
</div>

<script>
function previewPost() {
    // Sync CKEditor
    if (typeof editorInstance !== 'undefined' && editorInstance) {
        editorInstance.updateSourceElement();
    }

    var form = document.querySelector('form');
    var originalAction = form.action;
    var originalTarget = form.target;
    
    form.action = '../preview.php';
    form.target = '_blank';
    
    // Add post_id if editing
    var postIdInput = document.createElement('input');
    postIdInput.type = 'hidden';
    postIdInput.name = 'post_id';
    postIdInput.value = '<?php echo $post_id; ?>';
    form.appendChild(postIdInput);

    // Add post_type
    var postTypeInput = document.createElement('input');
    postTypeInput.type = 'hidden';
    postTypeInput.name = 'post_type';
    postTypeInput.value = 'post';
    form.appendChild(postTypeInput);
    
    form.submit();
    
    // Reset after short delay
    setTimeout(function() {
        form.action = originalAction;
        form.target = originalTarget;
        form.removeChild(postIdInput);
        form.removeChild(postTypeInput);
    }, 500);
}

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
    // Also reset file input if possible, or just ignore it if hidden input is empty? 
    // Actually if file is selected, it takes precedence. 
    // Ideally we should clear the file input too, but JS can't easily clear file input value for security, 
    // but we can replace it or reset form. For now, this clears the "existing" one.
}

function previewGallery(input) {
    // Just show count or basic preview? 
    // For simplicity, we just append to preview area as "New uploads"
    // But since we can't easily remove individual files from a file input list, 
    // we'll just show them.
    // Ideally, we'd use a more complex uploader. 
    // For now, let's just show text "X files selected".
    
    // Actually, let's try to preview images
    const previewContainer = document.getElementById('gallery-preview');
    // Note: We don't clear existing ones because we want to ADD to them.
    
    // Remove previous "preview-only" items (not having hidden input)
    // Actually hard to distinguish.
    
    if (input.files) {
         Array.from(input.files).forEach(file => {
             var reader = new FileReader();
             reader.onload = function(e) {
                  const div = document.createElement('div');
                  div.className = 'relative h-20 w-20 border-2 border-blue-400 rounded'; // Highlight as new
                  div.innerHTML = '<img src="'+e.target.result+'" class="h-full w-full object-cover rounded opacity-75">';
                  previewContainer.appendChild(div);
             }
             reader.readAsDataURL(file);
         });
    }
}

function previewAttachments(input) {
    // Similar logic
}

// Media Selector Logic
let currentMediaType = ''; // 'featured', 'gallery', 'attachment'

function openMediaSelector(type) {
    currentMediaType = type;
    document.getElementById('media-modal').classList.remove('hidden');
    loadMedia();
}

function closeMediaSelector() {
    document.getElementById('media-modal').classList.add('hidden');
}

function loadMedia() {
    fetch('ajax.php?action=get_media')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const grid = document.getElementById('media-grid');
            grid.innerHTML = '';
            
            data.files.forEach(file => {
                const div = document.createElement('div');
                div.className = 'cursor-pointer group relative border rounded hover:border-blue-500 overflow-hidden aspect-square';
                div.onclick = () => selectMedia(file);
                
                if (file.is_image) {
                    div.innerHTML = `<img src="${file.url}" class="w-full h-full object-cover">
                                     <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition"></div>`;
                } else {
                     div.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-gray-50 text-xs text-center p-2 break-all">
                                        ${file.name}
                                      </div>`;
                }
                grid.appendChild(div);
            });
        }
    });
}

function selectMedia(file) {
    if (currentMediaType === 'featured') {
        if (!file.is_image) {
            alert('Please select an image for featured image.');
            return;
        }
        document.getElementById('featured_image_url').value = file.url;
        document.getElementById('featured-image-preview').innerHTML = `<img src="${file.url}" class="w-full h-auto mb-2 rounded border">`;
        closeMediaSelector();
    } else if (currentMediaType === 'gallery') {
         if (!file.is_image) {
            alert('Please select an image for gallery.');
            return;
        }
        const container = document.getElementById('gallery-preview');
        const div = document.createElement('div');
        div.className = 'relative group h-20 w-20';
        div.innerHTML = `
            <img src="${file.url}" class="h-full w-full object-cover rounded border">
            <input type="hidden" name="gallery_images_existing[]" value="${file.url}">
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">&times;</button>
        `;
        container.appendChild(div);
        closeMediaSelector(); // Or keep open for multiple? Let's close for now or add "Done" button.
        // User might want to select multiple.
        // Let's modify: Clicking adds it, and we show visual feedback.
    } else if (currentMediaType === 'attachment') {
        const container = document.getElementById('attachments-preview');
        const att = { name: file.name, url: file.url };
        const attJson = JSON.stringify(att).replace(/"/g, '&quot;');
        
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-gray-50 p-2 rounded border';
        div.innerHTML = `
            <a href="${file.url}" target="_blank" class="text-blue-600 hover:underline text-sm truncate">${file.name}</a>
            <input type="hidden" name="attachments_existing[]" value="${attJson}">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm ml-2">Remove</button>
        `;
        container.appendChild(div);
        closeMediaSelector();
    }
}
</script>

<?php require_once( 'admin-footer.php' ); ?>
