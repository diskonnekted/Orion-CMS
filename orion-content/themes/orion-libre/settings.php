<?php
/**
 * Theme Settings for Orion Libre
 */

function orion_libre_settings_init() {
    // Default Featured Book (0 means latest)
    if (get_option('orion_libre_featured_book') === false) {
        update_option('orion_libre_featured_book', '0');
    }
    
    // Default Category Title & Subtitle
    if (get_option('orion_libre_cat_title') === false) {
        update_option('orion_libre_cat_title', 'Explore Categories');
    }
    if (get_option('orion_libre_cat_subtitle') === false) {
        update_option('orion_libre_cat_subtitle', 'Find your next favorite book by genre');
    }

    // List of active categories (empty = all)
    if (get_option('orion_libre_active_cats') === false) {
        update_option('orion_libre_active_cats', '');
    }
}
add_action('init', 'orion_libre_settings_init');

function orion_libre_settings_page() {
    global $orion_db, $table_prefix;

    if (isset($_POST['orion_libre_save_settings'])) {
        update_option('orion_libre_featured_book', $_POST['featured_book']);
        update_option('orion_libre_cat_title', $_POST['cat_title']);
        update_option('orion_libre_cat_subtitle', $_POST['cat_subtitle']);
        
        $cats = isset($_POST['active_cats']) ? implode(',', $_POST['active_cats']) : '';
        update_option('orion_libre_active_cats', $cats);
        
        echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm">Settings saved successfully!</div>';
    }

    $featured_book = get_option('orion_libre_featured_book', '0');
    $cat_title = get_option('orion_libre_cat_title', 'Explore Categories');
    $cat_subtitle = get_option('orion_libre_cat_subtitle', 'Find your next favorite book by genre');
    $active_cats = explode(',', get_option('orion_libre_active_cats', ''));

    // Fetch all books for dropdown
    $posts_table = $table_prefix . 'posts';
    $books_res = $orion_db->query("SELECT ID, post_title FROM $posts_table WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_title ASC");
    $books = [];
    if ($books_res) {
        while($row = $books_res->fetch_object()) $books[] = $row;
    }

    // Fetch all categories
    $categories = get_terms('category');
    ?>

    <div class="max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-libre-900 font-serif">Orion Libre Settings</h1>
            <p class="text-slate-500 mt-2">Personalize your digital library appearance.</p>
        </div>

        <form method="POST" class="space-y-8">
            <!-- Featured Book -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-libre-100">
                <h3 class="text-lg font-bold text-libre-900 mb-6 flex items-center gap-2 font-serif">
                    <svg class="w-5 h-5 text-libre-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.362-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Hero / Featured Book
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Book to Feature</label>
                        <select name="featured_book" class="w-full px-4 py-3 rounded-xl border border-libre-200 focus:ring-2 focus:ring-libre-500 outline-none transition bg-libre-50/30">
                            <option value="0" <?php echo $featured_book == '0' ? 'selected' : ''; ?>>-- Latest Published (Default) --</option>
                            <?php foreach($books as $book): ?>
                                <option value="<?php echo $book->ID; ?>" <?php echo $featured_book == $book->ID ? 'selected' : ''; ?>><?php echo htmlspecialchars($book->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-slate-400 mt-2 italic">If latest is selected, the newest book added will appear in the large hero section.</p>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-libre-100">
                <h3 class="text-lg font-bold text-libre-900 mb-6 flex items-center gap-2 font-serif">
                    <svg class="w-5 h-5 text-libre-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Explore Categories Section
                </h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Section Title</label>
                            <input type="text" name="cat_title" value="<?php echo htmlspecialchars($cat_title); ?>" class="w-full px-4 py-3 rounded-xl border border-libre-200 focus:ring-2 focus:ring-libre-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Section Subtitle</label>
                            <input type="text" name="cat_subtitle" value="<?php echo htmlspecialchars($cat_subtitle); ?>" class="w-full px-4 py-3 rounded-xl border border-libre-200 focus:ring-2 focus:ring-libre-500 outline-none transition">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-4">Display Categories</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <?php foreach($categories as $cat): ?>
                            <label class="flex items-center p-3 rounded-xl border border-libre-50 bg-libre-50/20 hover:bg-libre-50 cursor-pointer transition group">
                                <input type="checkbox" name="active_cats[]" value="<?php echo $cat->term_id; ?>" <?php echo in_array($cat->term_id, $active_cats) ? 'checked' : ''; ?> class="w-4 h-4 text-libre-600 rounded border-libre-300 focus:ring-libre-500">
                                <span class="ml-3 text-sm font-medium text-slate-700 group-hover:text-libre-900"><?php echo $cat->name; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-xs text-slate-400 mt-4 italic">Uncheck all to show all categories. Check specific ones to show only them.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" name="orion_libre_save_settings" class="px-10 py-4 bg-libre-800 text-white font-bold rounded-2xl hover:bg-libre-900 shadow-xl shadow-libre-800/30 transition transform hover:-translate-y-1">
                    Save Library Settings
                </button>
            </div>
        </form>
    </div>
    <?php
}
