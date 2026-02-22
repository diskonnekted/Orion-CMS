<?php
// Load Orion Core
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

// Check Admin Access
if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

require_once( ABSPATH . 'orion-admin/admin-header.php' );

global $orion_db, $table_prefix;
$table_forms = $table_prefix . 'orion_forms';

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $orion_db->query("DELETE FROM $table_forms WHERE id = $id");
    echo '<div class="bg-green-100 text-green-700 p-4 rounded mb-4 mx-6 mt-6">Form deleted successfully.</div>';
}

$forms = $orion_db->query("SELECT * FROM $table_forms ORDER BY created_at DESC");
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Orion Forms</h1>
        <div class="flex space-x-3">
            <a href="settings.php" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition flex items-center font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
            <a href="form-edit.php" class="px-4 py-2 bg-orion-600 text-white rounded-md hover:bg-orion-700 transition flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Form
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 font-semibold text-slate-700">Form Title</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Shortcode / Function</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Created At</th>
                    <th class="px-6 py-4 font-semibold text-slate-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($forms->num_rows > 0): ?>
                    <?php while($form = $forms->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900"><?php echo htmlspecialchars($form['title']); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-100 px-2 py-1 rounded text-sm text-slate-600">display_orion_form(<?php echo $form['id']; ?>)</code>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm">
                            <?php echo date('M j, Y', strtotime($form['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="entries.php?id=<?php echo $form['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Entries</a>
                            <span class="text-slate-300">|</span>
                            <a href="form-edit.php?id=<?php echo $form['id']; ?>" class="text-slate-600 hover:text-slate-800 font-medium text-sm">Edit</a>
                            <span class="text-slate-300">|</span>
                            <a href="?action=delete&id=<?php echo $form['id']; ?>" data-orion-confirm="Are you sure?" class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            No forms created yet. Click "Add New Form" to get started.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once( ABSPATH . 'orion-admin/admin-footer.php' ); ?>
