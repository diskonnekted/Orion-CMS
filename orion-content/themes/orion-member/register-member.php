<?php
// Handle form submission before output
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $details = isset($_POST['details']) ? trim($_POST['details']) : '';
    
    // Social Media
    $social = array(
        'facebook' => isset($_POST['social_fb']) ? trim($_POST['social_fb']) : '',
        'twitter' => isset($_POST['social_tw']) ? trim($_POST['social_tw']) : '',
        'instagram' => isset($_POST['social_ig']) ? trim($_POST['social_ig']) : '',
        'linkedin' => isset($_POST['social_li']) ? trim($_POST['social_li']) : ''
    );

    // Handle Photo Upload
    $photo_url = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = ABSPATH . 'orion-content/uploads/members/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = 'member-' . time() . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $photo_url = 'orion-content/uploads/members/' . $new_filename;
            } else {
                $error = "Gagal mengupload foto.";
            }
        } else {
            $error = "Format foto tidak didukung (hanya JPG/PNG).";
        }
    }

    if (empty($error)) {
        if (empty($name) || empty($email)) {
            $error = "Nama dan Email wajib diisi.";
        } else {
            // Generate Member Number
            $member_number = 'MEM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // Create new member post
            $post_data = array(
                'post_title'    => $name,
                'post_content'  => $details, // Store details in content
                'post_status'   => 'pending', // Pending approval
                'post_type'     => 'member',
                'post_author'   => 1 // Assign to admin
            );
    
            $post_id = wp_insert_post($post_data);
    
            if ($post_id) {
                // Save meta data
                update_post_meta($post_id, 'member_number', $member_number);
                update_post_meta($post_id, 'member_email', $email);
                update_post_meta($post_id, 'member_phone', $phone);
                update_post_meta($post_id, 'member_address', $address);
                update_post_meta($post_id, 'member_position', $position);
                update_post_meta($post_id, 'member_photo', $photo_url);
                update_post_meta($post_id, 'member_social', json_encode($social));
                
                $message = "Pendaftaran berhasil! Nomor Keanggotaan Anda: <strong>$member_number</strong>. Data Anda sedang kami proses.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
            }
        }
    }
}

get_header(); 
?>

<div class="bg-brand-50 min-h-screen py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-brand-600 px-8 py-6">
                <h1 class="text-2xl font-bold text-white text-center">Formulir Pendaftaran Member</h1>
                <p class="text-brand-100 text-center mt-2">Lengkapi data diri Anda untuk bergabung.</p>
            </div>
            
            <div class="p-8">
                <?php if ($message): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Sukses!</p>
                        <p><?php echo $message; ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Error!</p>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!$message): ?>
                <form method="POST" action="register-member.php" enctype="multipart/form-data">
                    <div class="space-y-6">
                        <!-- Personal Info -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                                <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition">
                            </div>
                        </div>
                        
                        <!-- Job & Photo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="position" class="block text-sm font-medium text-slate-700 mb-1">Jabatan / Pekerjaan</label>
                                <input type="text" name="position" id="position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition">
                            </div>
                            <div>
                                <label for="photo" class="block text-sm font-medium text-slate-700 mb-1">Foto Profil (JPG/PNG)</label>
                                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition bg-white">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition"></textarea>
                        </div>
                        
                        <div>
                            <label for="details" class="block text-sm font-medium text-slate-700 mb-1">Detail Membership / Tentang Saya</label>
                            <textarea name="details" id="details" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 transition" placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                        </div>

                        <!-- Social Media -->
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-slate-900 mb-4">Media Sosial</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="social_fb" class="block text-xs font-medium text-slate-500 mb-1">Facebook URL</label>
                                    <input type="text" name="social_fb" id="social_fb" placeholder="https://facebook.com/..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-brand-500 focus:border-brand-500">
                                </div>
                                <div>
                                    <label for="social_tw" class="block text-xs font-medium text-slate-500 mb-1">Twitter/X URL</label>
                                    <input type="text" name="social_tw" id="social_tw" placeholder="https://twitter.com/..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-brand-500 focus:border-brand-500">
                                </div>
                                <div>
                                    <label for="social_ig" class="block text-xs font-medium text-slate-500 mb-1">Instagram URL</label>
                                    <input type="text" name="social_ig" id="social_ig" placeholder="https://instagram.com/..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-brand-500 focus:border-brand-500">
                                </div>
                                <div>
                                    <label for="social_li" class="block text-xs font-medium text-slate-500 mb-1">LinkedIn URL</label>
                                    <input type="text" name="social_li" id="social_li" placeholder="https://linkedin.com/in/..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-brand-500 focus:border-brand-500">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="register_submit" class="w-full bg-brand-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-brand-700 transition shadow-md transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                    <div class="text-center py-8">
                        <a href="index.php" class="inline-flex items-center text-brand-600 font-bold hover:underline">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
