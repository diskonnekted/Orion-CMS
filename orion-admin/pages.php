<?php
/**
 * Pages Administration Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    wp_delete_post($_GET['id']);
    header("Location: pages.php?deleted=1");
    exit;
}

require_once( 'admin-header.php' );

$pages = get_posts(array('numberposts' => 100, 'post_type' => 'page')); // Get latest 100 pages
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Pages</h1>
        <p class="text-gray-600">Manage your static pages.</p>
    </div>
    <a href="page-new.php" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-4 rounded">
        Add New
    </a>
</div>

<?php if(isset($_GET['deleted'])): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
  <span class="block sm:inline">Page deleted successfully.</span>
</div>
<?php endif; ?>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Title</th>
                <th class="py-3 px-6 text-left">Author</th>
                <th class="py-3 px-6 text-center">Date</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            <?php if (empty($pages)): ?>
                <tr>
                    <td colspan="4" class="py-3 px-6 text-center">No pages found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pages as $page): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium"><?php echo htmlspecialchars($page->post_title); ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span>Admin</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span><?php echo date('Y-m-d', strtotime($page->post_date)); ?></span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="page-new.php?id=<?php echo $page->ID; ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <a href="pages.php?action=delete&id=<?php echo $page->ID; ?>" onclick="return confirm('Are you sure?');" class="w-4 transform hover:text-red-500 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

<?php require_once( 'admin-footer.php' ); ?>
