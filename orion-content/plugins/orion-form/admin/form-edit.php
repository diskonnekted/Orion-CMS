<?php
require_once( dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/orion-load.php' );

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

require_once( ABSPATH . 'orion-admin/admin-header.php' );

global $orion_db, $table_prefix;
$table_forms = $table_prefix . 'orion_forms';

$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$form_data = ['title' => '', 'fields' => '[]'];

if ($form_id > 0) {
    $result = $orion_db->query("SELECT * FROM $table_forms WHERE id = $form_id");
    if ($result->num_rows > 0) {
        $form_data = $result->fetch_assoc();
    }
}

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $orion_db->real_escape_string($_POST['title']);
    $fields = $orion_db->real_escape_string($_POST['fields']); // JSON string
    
    if ($form_id > 0) {
        $sql = "UPDATE $table_forms SET title = '$title', fields = '$fields' WHERE id = $form_id";
    } else {
        $sql = "INSERT INTO $table_forms (title, fields) VALUES ('$title', '$fields')";
    }
    
    if ($orion_db->query($sql)) {
        echo '<script>window.location.href = "forms.php";</script>';
        exit;
    } else {
        $error = "Error saving form.";
    }
}
?>

<div class="max-w-4xl mx-auto" x-data="formBuilder()">
    <div class="flex items-center gap-4 mb-6">
        <a href="forms.php" class="text-slate-500 hover:text-slate-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo $form_id > 0 ? 'Edit Form' : 'Create New Form'; ?></h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <!-- Form Title -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <label class="block text-sm font-medium text-slate-700 mb-1">Form Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($form_data['title']); ?>" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-orion-500 focus:ring-orion-500 text-lg">
        </div>

        <!-- Fields Builder -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-slate-800">Form Fields</h2>
                <button type="button" @click="addField()" class="text-sm px-3 py-1.5 bg-orion-600 text-white rounded-md hover:bg-orion-700 font-medium transition">
                    + Add Field
                </button>
            </div>

            <div class="space-y-4" id="fields-container">
                <template x-for="(field, index) in fields" :key="index">
                    <div class="border border-slate-200 rounded-lg p-4 bg-slate-50 relative group">
                        <button type="button" @click="removeField(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase mb-1">Label</label>
                                <input type="text" x-model="field.label" @input="updateName(index)" class="w-full rounded border-slate-300 text-sm" placeholder="e.g. Full Name">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase mb-1">Field Name (System)</label>
                                <input type="text" x-model="field.name" class="w-full rounded border-slate-300 text-sm bg-slate-100" readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase mb-1">Type</label>
                                <select x-model="field.type" class="w-full rounded border-slate-300 text-sm">
                                    <option value="text">Text Input</option>
                                    <option value="email">Email</option>
                                    <option value="textarea">Text Area</option>
                                    <option value="number">Number</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                            <div class="flex items-center pt-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="field.required" class="rounded border-slate-300 text-orion-600 shadow-sm focus:border-orion-500 focus:ring-orion-500">
                                    <span class="ml-2 text-sm text-slate-700">Required Field</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="fields.length === 0" class="text-center py-8 text-slate-400 border-2 border-dashed border-slate-200 rounded-lg">
                    No fields added yet.
                </div>
            </div>
            
            <!-- Hidden Input for JSON -->
            <input type="hidden" name="fields" :value="JSON.stringify(fields)">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-orion-600 text-white rounded-lg font-bold shadow-lg shadow-orion-500/30 hover:bg-orion-700 transition transform hover:-translate-y-0.5">
                Save Form
            </button>
        </div>
    </form>
</div>

<script>
function formBuilder() {
    return {
        fields: <?php echo $form_data['fields'] ?: '[]'; ?>,
        addField() {
            this.fields.push({
                label: '',
                name: '',
                type: 'text',
                required: false
            });
        },
        removeField(index) {
            this.fields.splice(index, 1);
        },
        updateName(index) {
            // Auto-generate name from label (slugify)
            let label = this.fields[index].label;
            let slug = label.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
            this.fields[index].name = slug;
        }
    }
}
</script>

<?php require_once( ABSPATH . 'orion-admin/admin-footer.php' ); ?>
