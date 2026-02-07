<?php
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

require_once( ABSPATH . 'orion-admin/admin-header.php' );

global $orion_db, $table_prefix;
$table_forms = $table_prefix . 'orion_forms';
$table_entries = $table_prefix . 'orion_form_entries';

$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($form_id === 0) {
    echo '<script>window.location.href = "forms.php";</script>';
    exit;
}

// Get Form Details
$form = $orion_db->query("SELECT * FROM $table_forms WHERE id = $form_id")->fetch_assoc();
if (!$form) die("Form not found.");

$form_fields = json_decode($form['fields'], true);

// Get Entries
$entries = $orion_db->query("SELECT * FROM $table_entries WHERE form_id = $form_id ORDER BY created_at DESC");
?>

<div class="max-w-7xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="forms.php" class="text-slate-500 hover:text-slate-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Entries: <?php echo htmlspecialchars($form['title']); ?></h1>
            <p class="text-slate-500 text-sm">Total Submissions: <?php echo $entries->num_rows; ?></p>
        </div>
        <div class="ml-auto">
             <a href="forms.php" class="text-sm font-medium text-orion-600 hover:text-orion-800">Back to Forms</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 font-semibold text-slate-700 w-10">#</th>
                    <th class="px-6 py-4 font-semibold text-slate-700 w-48">Date</th>
                    <?php foreach ($form_fields as $field): ?>
                        <th class="px-6 py-4 font-semibold text-slate-700"><?php echo htmlspecialchars($field['label']); ?></th>
                    <?php endforeach; ?>
                    <th class="px-6 py-4 font-semibold text-slate-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($entries->num_rows > 0): ?>
                    <?php while($entry = $entries->fetch_assoc()): 
                        $data = json_decode($entry['data'], true);
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-500"><?php echo $entry['id']; ?></td>
                        <td class="px-6 py-4 text-slate-600 text-sm"><?php echo date('M j, Y H:i', strtotime($entry['created_at'])); ?></td>
                        
                        <?php foreach ($form_fields as $field): 
                            $val = isset($data[$field['name']]) ? $data[$field['name']] : '-';
                            // Truncate long text
                            if (strlen($val) > 50) $val = substr($val, 0, 50) . '...';
                        ?>
                            <td class="px-6 py-4 text-slate-800"><?php echo htmlspecialchars($val); ?></td>
                        <?php endforeach; ?>
                        
                        <td class="px-6 py-4 text-right">
                             <!-- Placeholder for delete/view details if needed -->
                             <button class="text-slate-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                             </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo count($form_fields) + 3; ?>" class="px-6 py-12 text-center text-slate-500">
                            No entries found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once( ABSPATH . 'orion-admin/admin-footer.php' ); ?>
