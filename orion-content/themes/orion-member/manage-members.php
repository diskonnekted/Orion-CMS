<?php
/**
 * Member Manager for Orion Member Theme
 */

// Bootstrap Orion Core
// Assuming this file is at /orion-content/themes/orion-member/manage-members.php
// So root is ../../../
$bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';

if (file_exists($bootstrap_path)) {
    require_once $bootstrap_path;
} else {
    // Fallback if path structure is different, try looking for orion-load.php in typical places
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/orion/orion-load.php';
    } else {
        die("Orion CMS Core not found.");
    }
}

// Auth Check
if (!function_exists('is_user_logged_in') || !is_user_logged_in() || !current_user_can('administrator')) {
    header("Location: " . site_url('/login.php'));
    exit;
}

$message = '';
$error = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['member_id'])) {
        $member_id = (int)$_POST['member_id'];
        if (wp_delete_post($member_id)) {
            $message = "Member berhasil dihapus.";
        } else {
            $error = "Gagal menghapus member.";
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'approve' && isset($_POST['member_id'])) {
        $member_id = (int)$_POST['member_id'];
        $update_post = array(
            'ID'            => $member_id,
            'post_status'   => 'publish'
        );
        wp_update_post($update_post);
        $message = "Member disetujui.";
    }

    if (isset($_POST['action']) && $_POST['action'] === 'save_card_settings') {
        // Save Colors
        if (isset($_POST['card_bg_color'])) {
            update_option('member_card_bg_color', sanitize_hex_color($_POST['card_bg_color']));
        }
        if (isset($_POST['card_text_color'])) {
            update_option('member_card_text_color', sanitize_hex_color($_POST['card_text_color']));
        }

        if (isset($_FILES['card_logo']) && $_FILES['card_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['card_logo']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'svg');
            
            if (in_array($file_ext, $allowed_ext)) {
                $new_filename = 'card-logo-' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['card_logo']['tmp_name'], $destination)) {
                    $logo_url = 'orion-content/uploads/settings/' . $new_filename;
                    update_option('member_card_logo', $logo_url);
                }
            }
        }
        $message = "Pengaturan kartu berhasil disimpan.";
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update_member') {
        $member_id = (int)$_POST['member_id'];
        $name = strip_tags($_POST['full_name']);
        $email = strip_tags($_POST['email']);
        $phone = strip_tags($_POST['phone']);
        $address = strip_tags($_POST['address']);
        
        // Update Post Title
        wp_update_post(array(
            'ID' => $member_id,
            'post_title' => $name
        ));

        // Update Meta
        update_post_meta($member_id, 'member_email', $email);
        update_post_meta($member_id, 'member_phone', $phone);
        update_post_meta($member_id, 'member_address', $address);
        
        // Handle Photo Upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
             $upload_dir = ABSPATH . 'orion-content/uploads/members/';
             if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
             $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
             $allowed = array('jpg','jpeg','png');
             if (in_array($file_ext, $allowed)) {
                 $new_name = 'member-' . $member_id . '-' . time() . '.' . $file_ext;
                 move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_name);
                 update_post_meta($member_id, 'member_photo', 'orion-content/uploads/members/' . $new_name);
             }
        }

        $message = "Member berhasil diperbarui.";
    }
}


// Get Members
$args = array(
    'post_type'      => 'member',
    'post_status'    => array('publish', 'pending'),
    'posts_per_page' => -1
);
$members = get_posts($args);

require_once ABSPATH . 'orion-admin/admin-header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Anggota</h1>
    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Orion Member Theme</span>
</div>

<!-- Card Settings -->
<div class="bg-white rounded-lg shadow p-6 mb-8 border border-gray-200">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Pengaturan Kartu Anggota</h2>
    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <input type="hidden" name="action" value="save_card_settings">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo Kartu</label>
            <input type="file" name="card_logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <?php if ($curr_logo = get_option('member_card_logo')): ?>
                <div class="mt-2 text-xs text-gray-500">Logo saat ini: <a href="<?php echo site_url('/' . $curr_logo); ?>" target="_blank" class="text-blue-600 hover:underline">Lihat</a></div>
            <?php endif; ?>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Warna Background</label>
            <div class="flex items-center space-x-2">
                <input type="color" name="card_bg_color" value="<?php echo get_option('member_card_bg_color', '#1e293b'); ?>" class="h-9 w-9 rounded cursor-pointer border border-gray-300">
                <input type="text" name="card_bg_color" value="<?php echo get_option('member_card_bg_color', '#1e293b'); ?>" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Warna Teks</label>
            <div class="flex items-center space-x-2">
                <input type="color" name="card_text_color" value="<?php echo get_option('member_card_text_color', '#ffffff'); ?>" class="h-9 w-9 rounded cursor-pointer border border-gray-300">
                <input type="text" name="card_text_color" value="<?php echo get_option('member_card_text_color', '#ffffff'); ?>" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div class="md:col-span-4">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-bold">Simpan Pengaturan</button>
        </div>
    </form>
</div>

<?php 
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['member_id'])):
    $edit_id = (int)$_GET['member_id'];
    $member_post = get_post($edit_id);
    if ($member_post):
        $e_email = get_post_meta($edit_id, 'member_email', true);
        $e_phone = get_post_meta($edit_id, 'member_phone', true);
        $e_address = get_post_meta($edit_id, 'member_address', true);
        $e_photo = get_post_meta($edit_id, 'member_photo', true);
?>
<div class="bg-white rounded-lg shadow p-6 mb-8 border border-gray-200">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-gray-800">Edit Member</h2>
        <a href="manage-members.php" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
    </div>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="action" value="update_member">
        <input type="hidden" name="member_id" value="<?php echo $edit_id; ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($member_post->post_title); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($e_email); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($e_phone); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Foto</label>
                <input type="file" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <?php if($e_photo): ?>
                    <img src="<?php echo site_url($e_photo); ?>" class="h-16 w-16 object-cover rounded-full mt-2">
                <?php endif; ?>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"><?php echo htmlspecialchars($e_address); ?></textarea>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-bold">Update Member</button>
        </div>
    </form>
</div>
<?php 
    endif;
endif;
?>

<?php if ($message): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p><?php echo $error; ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Bulk Actions -->
    <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center">
        <button id="btn-print-selected" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-bold flex items-center disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200" disabled>
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu Terpilih
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($members): foreach ($members as $member): 
                    $email = get_post_meta($member->ID, 'member_email', true);
                    $phone = get_post_meta($member->ID, 'member_phone', true);
                    $address = get_post_meta($member->ID, 'member_address', true);
                ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="member-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4" value="<?php echo $member->ID; ?>">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <span class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                    <?php echo strtoupper(substr($member->post_title, 0, 1)); ?>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($member->post_title); ?></div>
                                <div class="text-sm text-gray-500 truncate max-w-xs" title="<?php echo htmlspecialchars($address); ?>"><?php echo htmlspecialchars($address); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($email); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($phone); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($member->post_status === 'publish'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo date('d M Y', strtotime($member->post_date)); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <?php if ($member->post_status !== 'publish'): ?>
                            <form method="POST" class="inline">
                                <input type="hidden" name="action" value="approve">
                                <input type="hidden" name="member_id" value="<?php echo $member->ID; ?>">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">Approve</button>
                            </form>
                            <?php else: ?>
                                <a href="print-card.php?member_id=<?php echo $member->ID; ?>" target="_blank" class="text-green-600 hover:text-green-900 flex items-center inline-block">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak
                                </a>
                            <?php endif; ?>
                            
                            <a href="?action=edit&member_id=<?php echo $member->ID; ?>" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>

                            <form method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member ini?');">

                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="member_id" value="<?php echo $member->ID; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data member.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.member-checkbox');
        const btnPrint = document.getElementById('btn-print-selected');

        function updateButton() {
            const checked = document.querySelectorAll('.member-checkbox:checked');
            btnPrint.disabled = checked.length === 0;
            if (checked.length > 0) {
                btnPrint.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnPrint.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateButton();
                // Uncheck "Select All" if any checkbox is unchecked
                if (!this.checked && selectAll) {
                    selectAll.checked = false;
                }
                // Check "Select All" if all are checked
                if (selectAll && document.querySelectorAll('.member-checkbox:checked').length === checkboxes.length) {
                    selectAll.checked = true;
                }
            });
        });

        if (btnPrint) {
            btnPrint.addEventListener('click', function() {
                const checked = document.querySelectorAll('.member-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.value).join(',');
                if (ids) {
                    window.open('print-card.php?member_ids=' + ids, '_blank');
                }
            });
        }
    });
</script>

<?php require_once ABSPATH . 'orion-admin/admin-footer.php'; ?>
