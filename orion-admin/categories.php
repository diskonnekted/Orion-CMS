<?php
/**
 * Categories Management Page
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

$message = '';
$error_msg = '';
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
                $error_msg = 'Error adding category.';
            }
        } else {
            $error_msg = 'Category name is required.';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_category') {
        $term_id = isset($_POST['term_id']) ? (int)$_POST['term_id'] : 0;
        $name = isset($_POST['tag-name']) ? trim($_POST['tag-name']) : '';
        $slug = isset($_POST['tag-slug']) ? trim($_POST['tag-slug']) : '';
        $desc = isset($_POST['tag-description']) ? trim($_POST['tag-description']) : '';
        
        if ($term_id && $name) {
            wp_update_term($term_id, 'category', array('name' => $name, 'slug' => $slug, 'description' => $desc));
            // Redirect to clear edit mode
            header("Location: categories.php?updated=1");
            exit;
        } else {
             $error_msg = 'Category name and ID are required.';
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
        $error_msg = 'Error deleting category.';
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

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Categories</h1>
    <p class="text-gray-500 mt-1">Organize your content with categories.</p>
</div>

<?php if($message || isset($_GET['updated'])): ?>
<div x-data="{ show: true }" x-show="show" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-md shadow-sm animate-fade-in-down" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium"><?php echo $message ? $message : 'Category updated successfully.'; ?></p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button @click="show = false" type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($error_msg): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-md shadow-sm" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700"><?php echo $error_msg; ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="flex flex-col lg:flex-row gap-8">
    <!-- Add/Edit Category Form -->
    <div class="lg:w-1/3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900"><?php echo $editing_term ? 'Edit Category' : 'Add New Category'; ?></h2>
                <?php if($editing_term): ?>
                <a href="categories.php" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Cancel Edit</a>
                <?php endif; ?>
            </div>
            
            <form method="POST" action="categories.php" class="p-6">
                <input type="hidden" name="action" value="<?php echo $editing_term ? 'update_category' : 'add_category'; ?>">
                <?php if($editing_term): ?>
                <input type="hidden" name="term_id" value="<?php echo $editing_term->term_id; ?>">
                <?php endif; ?>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="tag-name">Name</label>
                    <input name="tag-name" id="tag-name" type="text" value="<?php echo $editing_term ? htmlspecialchars($editing_term->name) : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orion-500 focus:border-orion-500 outline-none transition-all duration-200" required placeholder="e.g. Technology">
                    <p class="text-xs text-gray-500 mt-1.5">The name is how it appears on your site.</p>
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="tag-slug">Slug</label>
                    <input name="tag-slug" id="tag-slug" type="text" value="<?php echo $editing_term ? htmlspecialchars($editing_term->slug) : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orion-500 focus:border-orion-500 outline-none transition-all duration-200" placeholder="e.g. technology">
                    <p class="text-xs text-gray-500 mt-1.5">The "slug" is the URL-friendly version of the name. Usually all lowercase and contains only letters, numbers, and hyphens.</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="tag-description">Description</label>
                    <textarea name="tag-description" id="tag-description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orion-500 focus:border-orion-500 outline-none transition-all duration-200" rows="4" placeholder="Describe this category..."><?php echo $editing_term ? htmlspecialchars($editing_term->description) : ''; ?></textarea>
                    <p class="text-xs text-gray-500 mt-1.5">The description is not prominent by default; however, some themes may show it.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-orion-600 hover:bg-orion-700 text-white font-bold py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orion-500 shadow-sm transition-all duration-200">
                        <?php echo $editing_term ? 'Update Category' : 'Add New Category'; ?>
                    </button>
                    
                    <?php if($editing_term): ?>
                    <a href="categories.php" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center transition-all duration-200">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Category List -->
    <div class="lg:w-2/3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Slug
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Count
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(empty($cats)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    <p class="text-lg font-medium text-gray-900">No categories found</p>
                                    <p class="text-sm text-gray-500 mt-1">Start by adding a new category on the left.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($cats as $cat): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-orion-600 transition-colors">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                    </div>
                                    <!-- Mobile view description -->
                                    <div class="text-xs text-gray-500 mt-1 sm:hidden">
                                        <?php echo htmlspecialchars($cat->slug); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <div class="text-sm text-gray-500 line-clamp-2 max-w-xs">
                                        <?php echo $cat->description ? htmlspecialchars($cat->description) : '<span class="text-gray-300 italic">No description</span>'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    <code class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded border border-gray-200 font-mono">
                                        <?php echo htmlspecialchars($cat->slug); ?>
                                    </code>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo $cat->count; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-3">
                                        <a href="categories.php?action=edit&term_id=<?php echo $cat->term_id; ?>" class="text-gray-400 hover:text-orion-600 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <a href="categories.php?action=delete&term_id=<?php echo $cat->term_id; ?>" data-orion-confirm="Are you sure you want to delete this category?" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>
