<?php
/**
 * Users Administration Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );
require_once( 'admin-header.php' );

// Check capability
if (!current_user_can('administrator')) {
    echo '<div class="p-6 text-red-500">You do not have permission to view this page.</div>';
    require_once( 'admin-footer.php' );
    exit;
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user'])) {
    $user_id = (int) $_GET['user'];
    if ($user_id != $current_user->ID) { // Prevent self-deletion
        wp_delete_user($user_id);
        echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 mx-6 mt-6" role="alert"><p>User deleted.</p></div>';
    }
}

// Fetch Users
global $orion_db, $table_prefix;
$users_table = $table_prefix . 'users';
$sql = "SELECT * FROM $users_table ORDER BY user_registered DESC";
$result = $orion_db->query($sql);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Users</h1>
    <a href="user-new.php" class="bg-orion-600 hover:bg-orion-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add New User
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Username</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                <?php while ($user = $result->fetch_object()): 
                    $u = new WP_User($user);
                    $role = !empty($u->roles) ? ucfirst($u->roles[0]) : 'None';
                    $avatar_url = get_user_meta($user->ID, 'orion_avatar', true);
                    if (!$avatar_url) {
                        $avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($user->display_name) . '&background=random&size=32';
                    }
                ?>
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full object-cover mr-3" src="<?php echo $avatar_url; ?>" alt="">
                            <div class="text-sm font-medium text-slate-900"><?php echo htmlspecialchars($user->user_login); ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        <?php echo htmlspecialchars($user->display_name); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        <a href="mailto:<?php echo htmlspecialchars($user->user_email); ?>" class="hover:text-orion-600"><?php echo htmlspecialchars($user->user_email); ?></a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $role == 'Administrator' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'; ?>">
                            <?php echo $role; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="user-new.php?user_id=<?php echo $user->ID; ?>" class="text-orion-600 hover:text-orion-900 mr-3">Edit</a>
                        <?php if ($user->ID != $current_user->ID): ?>
                        <a href="users.php?action=delete&user=<?php echo $user->ID; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>
