<?php
/**
 * Theme Settings Page for Orion Developer
 */

function orion_developer_settings_page() {
    if (isset($_POST['orion_dev_save_settings'])) {
        update_option('orion_dev_whatsapp', $_POST['whatsapp']);
        
        update_option('orion_dev_pkg1_name', $_POST['pkg1_name']);
        update_option('orion_dev_pkg1_price', $_POST['pkg1_price']);
        update_option('orion_dev_pkg1_features', $_POST['pkg1_features']);
        
        update_option('orion_dev_pkg2_name', $_POST['pkg2_name']);
        update_option('orion_dev_pkg2_price', $_POST['pkg2_price']);
        update_option('orion_dev_pkg2_features', $_POST['pkg2_features']);
        
        update_option('orion_dev_pkg3_name', $_POST['pkg3_name']);
        update_option('orion_dev_pkg3_price', $_POST['pkg3_price']);
        update_option('orion_dev_pkg3_features', $_POST['pkg3_features']);
        
        echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">Settings saved successfully!</div>';
    }

    $wa = get_option('orion_dev_whatsapp');
    $pkg1 = array(
        'name' => get_option('orion_dev_pkg1_name'),
        'price' => get_option('orion_dev_pkg1_price'),
        'features' => get_option('orion_dev_pkg1_features')
    );
    $pkg2 = array(
        'name' => get_option('orion_dev_pkg2_name'),
        'price' => get_option('orion_dev_pkg2_price'),
        'features' => get_option('orion_dev_pkg2_features')
    );
    $pkg3 = array(
        'name' => get_option('orion_dev_pkg3_name'),
        'price' => get_option('orion_dev_pkg3_price'),
        'features' => get_option('orion_dev_pkg3_features')
    );
    ?>

    <div class="max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Orion Developer Settings</h1>
            <p class="text-slate-500 mt-2">Manage your pricing packages and contact information.</p>
        </div>

        <form method="POST" class="space-y-8">
            <!-- Global Contact -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.54 1.23l-2.1.9a11.05 11.05 0 005.02 5.02l.9-2.1a1 1 0 011.23-.54l3.3 1.1a1 1 0 01.68.95V20a2 2 0 01-2 2h-1C9.82 22 2 14.18 2 4V5z"></path></svg>
                    Contact Information
                </h3>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($wa); ?>" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="e.g. 081328128315">
                    <p class="text-xs text-slate-400 mt-2 italic">Format: 08xx or 628xx. Numbers only preferred.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pkg 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest">Package 1 (Left)</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Name</label>
                        <input type="text" name="pkg1_name" value="<?php echo htmlspecialchars($pkg1['name']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Price Label</label>
                        <input type="text" name="pkg1_price" value="<?php echo htmlspecialchars($pkg1['price']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Features (one per line)</label>
                        <textarea name="pkg1_features" rows="6" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none"><?php echo htmlspecialchars($pkg1['features']); ?></textarea>
                    </div>
                </div>

                <!-- Pkg 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-indigo-500 space-y-4 relative">
                    <span class="absolute -top-3 right-4 bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">Center/Featured</span>
                    <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest">Package 2 (Middle)</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Name</label>
                        <input type="text" name="pkg2_name" value="<?php echo htmlspecialchars($pkg2['name']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Price Label</label>
                        <input type="text" name="pkg2_price" value="<?php echo htmlspecialchars($pkg2['price']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Features (one per line)</label>
                        <textarea name="pkg2_features" rows="6" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none"><?php echo htmlspecialchars($pkg2['features']); ?></textarea>
                    </div>
                </div>

                <!-- Pkg 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest">Package 3 (Right)</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Name</label>
                        <input type="text" name="pkg3_name" value="<?php echo htmlspecialchars($pkg3['name']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Price Label</label>
                        <input type="text" name="pkg3_price" value="<?php echo htmlspecialchars($pkg3['price']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Features (one per line)</label>
                        <textarea name="pkg3_features" rows="6" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none"><?php echo htmlspecialchars($pkg3['features']); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" name="orion_dev_save_settings" class="px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-1">
                    Save All Changes
                </button>
            </div>
        </form>
    </div>
    <?php
}
