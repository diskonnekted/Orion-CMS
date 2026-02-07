<?php
// Load Orion Core
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

// Check Admin Access
if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

require_once( ABSPATH . 'orion-admin/admin-header.php' );

// Handle Settings Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orion_form_settings_submit'])) {
    $email_notification = isset($_POST['email_notification']) ? sanitize_text_field($_POST['email_notification']) : '';
    update_option('orion_form_email_notification', $email_notification);
    echo '<div class="bg-green-100 text-green-700 p-4 rounded mb-4 mx-6 mt-6">Settings saved successfully.</div>';
}

$current_email = get_option('orion_form_email_notification', get_option('admin_email'));
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Orion Form Settings</h1>
        <a href="forms.php" class="text-slate-600 hover:text-slate-800 font-medium">
            &larr; Back to Forms
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 max-w-2xl">
        <form method="post" action="">
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2" for="email_notification">Default Notification Email</label>
                <p class="text-slate-500 text-sm mb-2">Receive email notifications when a form is submitted.</p>
                <input type="email" name="email_notification" id="email_notification" value="<?php echo htmlspecialchars($current_email); ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-orion-500 focus:ring-1 focus:ring-orion-500">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" name="orion_form_settings_submit" class="px-6 py-2 bg-orion-600 text-white rounded-lg font-bold hover:bg-orion-700 transition shadow-md">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once( ABSPATH . 'orion-admin/admin-footer.php' ); ?>
