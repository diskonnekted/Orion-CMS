<?php
if (!defined('ABSPATH')) exit;

// Save Options
if (isset($_POST['orion_bakery_save_menu'])) {
    if (isset($_POST['daily_products'])) {
        update_option('orion_bakery_daily_products', array_map('intval', $_POST['daily_products']));
    } else {
        update_option('orion_bakery_daily_products', array());
    }
    echo '<div class="notice notice-success is-dismissible"><p>Menu hari ini telah diperbarui!</p></div>';
}

$daily_products = get_option('orion_bakery_daily_products', array());

// Fetch all downloads (products)
$args = array(
    'post_type' => 'download',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC'
);
$products = get_posts($args);
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Manajemen Menu Roti Hari Ini</h1>
    <p class="description">Pilih produk roti yang tersedia (ready stock) untuk dijual hari ini. Produk yang dipilih akan tampil di halaman "Menu Hari Ini".</p>
    
    <form method="post" action="">
        <div class="card" style="max-width: 100%; margin-top: 20px; padding: 0;">
            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all-1"></td>
                        <th scope="col" id="title" class="manage-column column-title column-primary">Nama Produk</th>
                        <th scope="col" id="price" class="manage-column column-price">Harga</th>
                        <th scope="col" id="status" class="manage-column column-status">Status Hari Ini</th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php if ($products) : ?>
                        <?php foreach ($products as $product) : 
                            $is_checked = in_array($product->ID, $daily_products);
                            $price = function_exists('edd_get_download_price') ? edd_currency_filter(edd_format_amount(edd_get_download_price($product->ID))) : 'Rp -';
                            $img = get_the_post_thumbnail_url($product->ID, 'thumbnail');
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" name="daily_products[]" value="<?php echo $product->ID; ?>" <?php checked($is_checked, true); ?>>
                            </th>
                            <td class="title column-title has-row-actions column-primary" data-colname="Nama Produk">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if($img): ?>
                                        <img src="<?php echo $img; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; background: #eee; border-radius: 4px;"></div>
                                    <?php endif; ?>
                                    <strong><?php echo $product->post_title; ?></strong>
                                </div>
                            </td>
                            <td class="price column-price" data-colname="Harga"><?php echo $price; ?></td>
                            <td class="status column-status" data-colname="Status">
                                <?php if($is_checked): ?>
                                    <span class="dashicons dashicons-yes" style="color: green;"></span> <span style="color: green; font-weight: bold;">Tersedia</span>
                                <?php else: ?>
                                    <span style="color: #999;">Tidak Tersedia</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="4">Belum ada produk yang ditambahkan di Easy Digital Downloads.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <p class="submit">
            <input type="submit" name="orion_bakery_save_menu" id="submit" class="button button-primary" value="Simpan Perubahan">
        </p>
    </form>
</div>

<script>
    document.getElementById('cb-select-all-1').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[name="daily_products[]"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    });
</script>
