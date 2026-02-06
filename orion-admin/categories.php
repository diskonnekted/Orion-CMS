<?php
/**
 * Categories Management Page
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

$message = '';
$editing_term = null;

// Handle Add/Update Category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add_category') {
        $name = isset($_POST['tag-name']) ? trim($_POST['tag-name']) : '';
        $slug = isset($_POST['tag-slug']) ? trim($_POST['tag-slug']) : '';
        $desc = isset($_POST['tag-description']) ? trim($_POST['tag-description']) : '';
        
        if ($name) {
            $result = wp_insert_term($name, 'category', array('slug' => $slug, 'description' => $desc));
            if ($result) {
                $message = 'Category added successfully.';
            } else {
                $message = 'Error adding category.';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_category') {
        $term_id = isset($_POST['term_id']) ? (int)$_POST['term_id'] : 0;
        $name = isset($_POST['tag-name']) ? trim($_POST['tag-name']) : '';
        $slug = isset($_POST['tag-slug']) ? trim($_POST['tag-slug']) : '';
        $desc = isset($_POST['tag-description']) ? trim($_POST['tag-description']) : '';
        
        if ($term_id && $name) {
            wp_update_term($term_id, 'category', array('name' => $name, 'slug' => $slug, 'description' => $desc));
            $message = 'Category updated successfully.';
            // Redirect to clear edit mode
            header("Location: categories.php?updated=1");
            exit;
        }
    }
}

// Handle Delete Category
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['term_id'])) {
    $term_id = (int)$_GET['term_id'];
    // Security check usually goes here (nonce)
    
    if (wp_delete_term($term_id, 'category')) {
        $message = 'Category deleted.';
    } else {
        $message = 'Error deleting category.';
    }
}

// Handle Edit Action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['term_id'])) {
    $term_id = (int)$_GET['term_id'];
    $editing_term = get_term($term_id, 'category');
}

// Get all categories
$cats = get_terms('category', array('hide_empty' => false));

require_once( 'admin-header.php' );
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Categories</h1>
</div>

<?php if($message || isset($_GET['updated'])): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
    <?php echo $message ? $message : 'Category updated successfully.'; ?>
</div>
<?php endif; ?>

<div class="flex flex-col md:flex-row gap-8">
    <!-- Add/Edit Category Form -->
    <div class="md:w-1/3">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800"><?php echo $editing_term ? 'Edit Category' : 'Add New Category'; ?></h2>
            <form method="POST" action="categories.php">
                <input type="hidden" name="action" value="<?php echo $editing_term ? 'update_category' : 'add_category'; ?>">
                <?php if($editing_term): ?>
                <input type="hidden" name="term_id" value="<?php echo $editing_term->term_id; ?>">
                <?php endif; ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tag-name">Name</label>
                    <input name="tag-name" id="tag-name" type="text" value="<?php echo $editing_term ? htmlspecialchars($editing_term->name) : ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <p class="text-xs text-gray-500 mt-1">The name is how it appears on your site.</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tag-slug">Slug</label>
                    <input name="tag-slug" id="tag-slug" type="text" value="<?php echo $editing_term ? htmlspecialchars($editing_term->slug) : ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-xs text-gray-500 mt-1">The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tag-description">Description</label>
                    <textarea name="tag-description" id="tag-description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4"><?php echo $editing_term ? htmlspecialchars($editing_term->description) : ''; ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">The description is not prominent by default; however, some themes may show it.</p>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <?php echo $editing_term ? 'Update Category' : 'Add New Category'; ?>
                    </button>
                    
                    <?php if($editing_term): ?>
                    <a href="categories.php" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Category List -->
    <div class="md:w-2/3">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Count
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($cats)): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($cats as $cat): ?>
                        <tr class="hover:bg-gray-50 group">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="font-bold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars($cat->name); ?>
                                </div>
                                <div class="text-xs invisible group-hover:visible">
                                    <a href="categories.php?action=edit&term_id=<?php echo $cat->term_id; ?>" class="text-blue-600 hover:underline">Edit</a> | 
                                    <a href="categories.php?action=delete&term_id=<?php echo $cat->term_id; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                    <!-- View link could go here -->
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($cat->description); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($cat->slug); ?></p>
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo $cat->count; ?></p>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>
