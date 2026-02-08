<?php
/**
 * Template Name: Manage Books
 * Description: Frontend management dashboard for Orion Libre theme.
 */

// Ensure core is loaded if accessed directly (though typically accessed via theme route)
// In this simple CMS structure, we might need to load orion-load.php if accessed directly.
// However, since it's a theme file, it's usually included via index.php logic.
// But the user asked for a "standalone page". If we access it as `orion-content/themes/orion-libre/manage-books.php`,
// we need to bootstrap Orion.
if (!defined('ABSPATH')) {
    // Try to find orion-load.php relative to this file
    // Path: .../orion-content/themes/orion-libre/manage-books.php
    // Target: .../orion-load.php
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die("Orion CMS Core not found.");
    }
}

// Authentication Check
if (!is_user_logged_in()) {
    // Redirect to admin login
    header("Location: " . site_url('/orion-admin/'));
    exit;
}

$current_user = wp_get_current_user();
$message = '';
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'save_book') {
        $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : '';
        $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : '';
        
        if (empty($title)) {
            $error = "Title is required.";
        } else {
            $post_data = array(
                'ID' => $post_id,
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'post', // Using standard post type for simplicity
                'post_author' => $current_user->ID
            );
            
            $saved_post_id = wp_insert_post($post_data);
            
            if ($saved_post_id) {
                $message = "Book saved successfully.";
                
                // Handle Cover Image Upload
                if (!empty($_FILES['cover_image']['name'])) {
                    $upload_dir = ABSPATH . 'orion-content/uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    
                    $file_name = time() . '_cover_' . basename($_FILES['cover_image']['name']);
                    $target_file = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                        $cover_url = site_url('/orion-content/uploads/' . $file_name);
                        // Store as gallery images (array) to match theme logic
                        update_post_meta($saved_post_id, '_gallery_images', json_encode(array($cover_url)));
                    }
                }
                
                // Handle PDF Upload
                if (!empty($_FILES['book_pdf']['name'])) {
                    $upload_dir = ABSPATH . 'orion-content/uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    
                    $file_name = time() . '_book_' . basename($_FILES['book_pdf']['name']);
                    $target_file = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['book_pdf']['tmp_name'], $target_file)) {
                        $pdf_url = site_url('/orion-content/uploads/' . $file_name);
                        // Store as attachments (array of objects)
                        $attachment_data = array(
                            array(
                                'url' => $pdf_url,
                                'name' => $_FILES['book_pdf']['name']
                            )
                        );
                        update_post_meta($saved_post_id, '_attachments', json_encode($attachment_data));
                    }
                }
                
                // Redirect to avoid resubmission
                // header("Location: manage-books.php?msg=saved");
                // exit;
            } else {
                $error = "Failed to save book.";
            }
        }
    }
    
    if ($_POST['action'] === 'delete_book' && isset($_POST['post_id'])) {
        $delete_id = (int)$_POST['post_id'];
        // Simple delete via update status or we need a wp_delete_post (not sure if exists, let's just set status to trash or draft)
        // Or direct DB delete if wp_delete_post is missing
        // Checking orion-includes/post.php, no delete function. We'll do direct DB for now or set to draft.
        // Let's set to 'trash'
        $post_data = array('ID' => $delete_id, 'post_status' => 'trash');
        wp_insert_post($post_data);
        $message = "Book moved to trash.";
    }
}

// Get Book to Edit if ID provided
$edit_post = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_post = get_post((int)$_GET['id']);
}

// Get All Books
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'numberposts' => 100
);
$books = get_posts($args);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Orion Libre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        libre: {
                            50: '#f9f7f2',
                            100: '#f0eadd',
                            200: '#e0d3b8',
                            300: '#cbb68d',
                            400: '#b89a66',
                            500: '#a3804d',
                            600: '#8c683f',
                            700: '#725336',
                            800: '#5e4530',
                            900: '#4e3b2b',
                        }
                    },
                    fontFamily: {
                        serif: ['Merriweather', 'serif'],
                        sans: ['Open Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-libre-50 text-libre-900 font-sans min-h-screen">

    <nav class="bg-libre-800 text-libre-50 shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-libre-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-serif font-bold text-xl">Librarian Dashboard</span>
            </div>
            <div class="flex gap-4 text-sm font-semibold">
                <a href="index.php" class="hover:text-libre-300">View Library</a>
                <a href="../../orion-admin/index.php" class="hover:text-libre-300">Admin Panel</a>
                <span class="text-libre-400">|</span>
                <span>Welcome, <?php echo htmlspecialchars($current_user->display_name); ?></span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">
        
        <?php if($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <?php if($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-libre-200">
                    <div class="bg-libre-100 px-6 py-4 border-b border-libre-200">
                        <h2 class="font-serif font-bold text-lg text-libre-800">
                            <?php echo $edit_post ? 'Edit Book' : 'Add New Book'; ?>
                        </h2>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        <input type="hidden" name="action" value="save_book">
                        <?php if($edit_post): ?>
                            <input type="hidden" name="post_id" value="<?php echo $edit_post->ID; ?>">
                        <?php endif; ?>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Book Title</label>
                            <input type="text" name="post_title" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-libre-500" value="<?php echo $edit_post ? htmlspecialchars($edit_post->post_title) : ''; ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Synopsis</label>
                            <textarea name="post_content" rows="5" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-libre-500"><?php echo $edit_post ? htmlspecialchars($edit_post->post_content) : ''; ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Cover Image</label>
                            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-libre-100 file:text-libre-700 hover:file:bg-libre-200">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to keep existing cover.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">PDF File</label>
                            <input type="file" name="book_pdf" accept="application/pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-libre-100 file:text-libre-700 hover:file:bg-libre-200">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to keep existing PDF.</p>
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="submit" class="bg-libre-600 text-white font-bold py-2 px-6 rounded hover:bg-libre-700 transition w-full">
                                <?php echo $edit_post ? 'Update Book' : 'Add to Library'; ?>
                            </button>
                            <?php if($edit_post): ?>
                                <a href="manage-books.php" class="bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-400 transition text-center">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-libre-200">
                    <div class="bg-libre-100 px-6 py-4 border-b border-libre-200 flex justify-between items-center">
                        <h2 class="font-serif font-bold text-lg text-libre-800">Library Inventory</h2>
                        <span class="text-sm bg-libre-200 text-libre-800 py-1 px-3 rounded-full"><?php echo count($books); ?> Books</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                                    <th class="px-6 py-3">Cover</th>
                                    <th class="px-6 py-3">Title / Date</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach($books as $book): 
                                    $cover = 'https://via.placeholder.com/50x75?text=No+Cover';
                                    $gallery = get_post_meta($book->ID, '_gallery_images', true);
                                    if ($gallery) {
                                        $imgs = json_decode($gallery, true);
                                        if (!empty($imgs)) $cover = $imgs[0];
                                    }
                                    
                                    $has_pdf = false;
                                    $attachments = get_post_meta($book->ID, '_attachments', true);
                                    if ($attachments) {
                                        $atts = json_decode($attachments, true);
                                        foreach($atts as $att) {
                                            if (isset($att['url']) && strpos(strtolower($att['url']), '.pdf') !== false) {
                                                $has_pdf = true;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <img src="<?php echo $cover; ?>" class="h-12 w-8 object-cover rounded shadow-sm border border-gray-200">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900"><?php echo $book->post_title; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($book->post_date)); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($has_pdf): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                PDF Ready
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                No PDF
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="manage-books.php?action=edit&id=<?php echo $book->ID; ?>" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">Edit</a>
                                        <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                            <input type="hidden" name="action" value="delete_book">
                                            <input type="hidden" name="post_id" value="<?php echo $book->ID; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(empty($books)): ?>
                        <div class="p-8 text-center text-gray-500">
                            No books found in the library. Start adding some!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
