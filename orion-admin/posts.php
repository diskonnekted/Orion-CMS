<?php
/**
 * Posts Administration Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $post_id = (int) $_GET['id'];
    $post = get_post($post_id);
    
    if ($post) {
        // Permission Check: Admin or Author
        if (current_user_can('administrator') || $post->post_author == $current_user->ID) {
            wp_delete_post($post_id);
            header("Location: posts.php?deleted=1");
            exit;
        } else {
            // Access Denied
            $error_msg = "You do not have permission to delete this post.";
        }
    }
}

require_once( 'admin-header.php' );

// Pagination Setup
$paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
$posts_per_page = 10;
$offset = ($paged - 1) * $posts_per_page;

// Get Posts
$args = array(
    'numberposts' => $posts_per_page,
    'offset' => $offset,
    'orderby' => 'post_date',
    'order' => 'DESC'
);
$posts = get_posts($args);

// Get Total Posts for Pagination
global $orion_db, $table_prefix;
$count_sql = "SELECT COUNT(*) as count FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status != 'trash'";
$count_res = $orion_db->query($count_sql);
$total_posts = ($count_res) ? $count_res->fetch_object()->count : 0;
$total_pages = ceil($total_posts / $posts_per_page);

?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Posts</h1>
        <p class="text-gray-500 mt-1">Manage your news articles and content.</p>
    </div>
    <a href="post-new.php" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-orion-600 hover:bg-orion-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orion-500 shadow-sm transition-all duration-200">
        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Add New Post
    </a>
</div>

<?php if(isset($_GET['deleted'])): ?>
<div x-data="{ show: true }" x-show="show" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-md shadow-sm animate-fade-in-down" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium">Post deleted successfully.</p>
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

<?php if(isset($error_msg)): ?>
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

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Author
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                <p class="text-lg font-medium text-gray-900">No posts found</p>
                                <p class="text-sm text-gray-500 mt-1">Get started by creating a new post.</p>
                                <a href="post-new.php" class="mt-4 text-orion-600 hover:text-orion-800 font-medium text-sm">Create new post &rarr;</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): 
                        $author_data = get_user_by('id', $post->post_author);
                        $author_name = $author_data ? $author_data->display_name : 'Unknown';
                        $can_edit = (current_user_can('administrator') || $post->post_author == $current_user->ID);
                        $thumb = get_the_post_thumbnail_url($post->ID);
                        
                        // Status styling
                        $status_classes = 'bg-gray-100 text-gray-800';
                        $status_label = 'Draft';
                        if($post->post_status == 'publish') {
                            $status_classes = 'bg-green-100 text-green-800 border border-green-200';
                            $status_label = 'Published';
                        }
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-10 w-16 rounded overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                <?php if ($thumb): ?>
                                    <img class="h-full w-full object-cover" src="<?php echo htmlspecialchars($thumb); ?>" alt="">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 line-clamp-1 max-w-xs group-hover:text-orion-600 transition-colors">
                                <a href="post-new.php?id=<?php echo $post->ID; ?>"><?php echo htmlspecialchars($post->post_title); ?></a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-orion-100 flex items-center justify-center text-orion-600 font-bold text-xs mr-2">
                                    <?php echo strtoupper(substr($author_name, 0, 1)); ?>
                                </div>
                                <span class="text-sm text-gray-700"><?php echo htmlspecialchars($author_name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            $categories = get_the_terms($post->ID, 'category');
                            if ($categories && !is_wp_error($categories)) {
                                $cat = $categories[0]; // Just show first category
                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">' . htmlspecialchars($cat->name) . '</span>';
                                if (count($categories) > 1) {
                                    echo '<span class="ml-1 text-xs text-gray-400">+' . (count($categories) - 1) . '</span>';
                                }
                            } else {
                                echo '<span class="text-sm text-gray-400 italic">Uncategorized</span>';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo date('M d, Y', strtotime($post->post_date)); ?></div>
                            <div class="text-xs text-gray-500"><?php echo date('h:i A', strtotime($post->post_date)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_classes; ?>">
                                <?php echo $status_label; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                <?php if ($can_edit): ?>
                                <a href="post-new.php?id=<?php echo $post->ID; ?>" class="text-gray-400 hover:text-orion-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <a href="posts.php?action=delete&id=<?php echo $post->ID; ?>" onclick="return confirm('Are you sure you want to delete this post?');" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                                <?php else: ?>
                                    <span class="text-gray-300 cursor-not-allowed" title="View Only">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <div class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium"><?php echo ($offset + 1); ?></span>
                    to
                    <span class="font-medium"><?php echo min($offset + $posts_per_page, $total_posts); ?></span>
                    of
                    <span class="font-medium"><?php echo $total_posts; ?></span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <?php if ($paged > 1): ?>
                    <a href="?paged=<?php echo $paged - 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <?php endif; ?>

                    <?php 
                    // Simple pagination window logic
                    $range = 2;
                    $start = max(1, $paged - $range);
                    $end = min($total_pages, $paged + $range);
                    
                    if ($start > 1) {
                        echo '<a href="?paged=1" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>';
                        if ($start > 2) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active_class = ($i == $paged) 
                            ? 'z-10 bg-orion-50 border-orion-500 text-orion-600' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50';
                        echo '<a href="?paged=' . $i . '" aria-current="page" class="' . $active_class . ' relative inline-flex items-center px-4 py-2 border text-sm font-medium">' . $i . '</a>';
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                        echo '<a href="?paged=' . $total_pages . '" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">' . $total_pages . '</a>';
                    }
                    ?>

                    <?php if ($paged < $total_pages): ?>
                    <a href="?paged=<?php echo $paged + 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <!-- Mobile Pagination View -->
        <div class="flex items-center justify-between sm:hidden w-full">
            <?php if ($paged > 1): ?>
                <a href="?paged=<?php echo $paged - 1; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            <?php else: ?>
                <button disabled class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                    Previous
                </button>
            <?php endif; ?>
            
            <span class="text-sm text-gray-700">
                Page <span class="font-medium"><?php echo $paged; ?></span> of <span class="font-medium"><?php echo $total_pages; ?></span>
            </span>
            
            <?php if ($paged < $total_pages): ?>
                <a href="?paged=<?php echo $paged + 1; ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            <?php else: ?>
                <button disabled class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                    Next
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once( 'admin-footer.php' ); ?>
