<?php
/**
 * Add/Edit User Administration Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );
require_once( 'admin-header.php' );

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$is_edit = $user_id > 0;
$is_profile = ($user_id == $current_user->ID);

// Permission Check
if (!$is_profile && !current_user_can('administrator')) {
    echo '<div class="p-6 text-red-500">You do not have permission to edit users.</div>';
    require_once( 'admin-footer.php' );
    exit;
}

$user_data = null;
if ($is_edit) {
    $user_data = get_user_by('id', $user_id);
    if (!$user_data) {
        echo '<div class="p-6 text-red-500">User not found.</div>';
        require_once( 'admin-footer.php' );
        exit;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_user'])) {
    $username = isset($_POST['user_login']) ? trim($_POST['user_login']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $display_name = isset($_POST['display_name']) ? trim($_POST['display_name']) : $first_name . ' ' . $last_name;
    if (empty($display_name)) $display_name = $username;
    
    $password = isset($_POST['pass1']) ? $_POST['pass1'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'operator';
    
    $errors = array();
    
    if (!$is_edit) {
        // Create User
        if (empty($username) || empty($email) || empty($password)) {
            $errors[] = "Username, Email, and Password are required.";
        } else {
            $new_user_id = wp_create_user($username, $password, $email);
            if (is_wp_error($new_user_id)) {
                $errors[] = $new_user_id->get_error_message();
            } else {
                $user_id = $new_user_id;
                $is_edit = true;
                $message = "User created successfully.";
            }
        }
    } else {
        // Update User
        $update_data = array(
            'ID' => $user_id,
            'user_email' => $email,
            'display_name' => $display_name
        );
        
        if (!empty($password)) {
            $update_data['user_pass'] = $password;
        }
        
        $result = wp_update_user($update_data);
        if (is_wp_error($result)) {
            $errors[] = $result->get_error_message();
        } else {
            $message = "User updated successfully.";
        }
    }
    
    if (empty($errors) && $user_id) {
        // Update Meta
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        
        // Update Role (Only admins can change roles)
        if (current_user_can('administrator')) {
            $caps = array($role => true);
            update_user_meta($user_id, 'orion_capabilities', serialize($caps));
            update_user_meta($user_id, 'orion_user_level', ($role == 'administrator' ? 10 : 5));
        }
        
        // Handle Avatar Upload
        if (isset($_FILES['user_avatar']) && $_FILES['user_avatar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = dirname(dirname(__FILE__)) . '/assets/uploads/avatars/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_tmp = $_FILES['user_avatar']['tmp_name'];
            $file_name = basename($_FILES['user_avatar']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_ext, $allowed)) {
                $new_file_name = 'avatar-' . $user_id . '-' . time() . '.' . $file_ext;
                $target_file = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $avatar_url = site_url('/assets/uploads/avatars/' . $new_file_name);
                    update_user_meta($user_id, 'orion_avatar', $avatar_url);
                }
            }
        }
    }
}

// Reload user data if edited
if ($is_edit && isset($user_id)) {
    $user_data = get_user_by('id', $user_id);
    $user_meta = new WP_User($user_data);
    $current_role = !empty($user_meta->roles) ? $user_meta->roles[0] : 'operator';
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $avatar_url = get_user_meta($user_id, 'orion_avatar', true);
} else {
    $current_role = 'operator';
    $first_name = '';
    $last_name = '';
    $avatar_url = '';
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-slate-800"><?php echo $is_edit ? 'Edit User' : 'Add New User'; ?></h1>
    <?php if ($is_edit && !$is_profile): ?>
        <a href="user-new.php" class="text-orion-600 hover:text-orion-800 font-medium">Add New</a>
    <?php endif; ?>
</div>

<?php if (!empty($errors)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Account Info -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-slate-700 border-b pb-2">Account Information</h2>
                
                <div class="mb-4">
                    <label for="user_login" class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <input type="text" name="user_login" id="user_login" value="<?php echo $is_edit ? htmlspecialchars($user_data->user_login) : ''; ?>" <?php echo $is_edit ? 'readonly class="w-full px-3 py-2 border border-slate-200 bg-slate-100 rounded-md text-slate-500 cursor-not-allowed"' : 'class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" required'; ?>>
                    <?php if ($is_edit): ?><p class="text-xs text-slate-500 mt-1">Usernames cannot be changed.</p><?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $is_edit ? htmlspecialchars($user_data->user_email) : ''; ?>" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" required>
                </div>
                
                <?php if (current_user_can('administrator')): ?>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                    <select name="role" id="role" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                        <option value="operator" <?php echo $current_role == 'operator' ? 'selected' : ''; ?>>Operator</option>
                        <option value="administrator" <?php echo $current_role == 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="mb-4">
                    <label for="pass1" class="block text-sm font-medium text-slate-700 mb-1"><?php echo $is_edit ? 'New Password' : 'Password'; ?></label>
                    <input type="password" name="pass1" id="pass1" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500" <?php echo $is_edit ? '' : 'required'; ?>>
                    <?php if ($is_edit): ?><p class="text-xs text-slate-500 mt-1">Leave blank to keep current password.</p><?php endif; ?>
                </div>
            </div>
            
            <!-- Right Column: Personal Info & Avatar -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-slate-700 border-b pb-2">Personal Options</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($first_name); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($last_name); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="display_name" class="block text-sm font-medium text-slate-700 mb-1">Display Name Publicly as</label>
                    <input type="text" name="display_name" id="display_name" value="<?php echo $is_edit ? htmlspecialchars($user_data->display_name) : ''; ?>" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-orion-500 focus:border-orion-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Avatar</label>
                    <div class="flex items-center gap-4">
                        <?php if ($avatar_url): ?>
                            <img src="<?php echo $avatar_url; ?>" alt="Current Avatar" class="h-16 w-16 rounded-full object-cover border border-slate-200">
                        <?php else: ?>
                            <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <input type="file" name="user_avatar" id="user_avatar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orion-50 file:text-orion-700 hover:file:bg-orion-100">
                            <p class="text-xs text-slate-500 mt-1">Allowed formats: JPG, PNG, WEBP.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="submit" name="submit_user" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition-colors">
                <?php echo $is_edit ? 'Update User' : 'Add New User'; ?>
            </button>
        </div>
    </form>
</div>

<?php require_once( 'admin-footer.php' ); ?>
