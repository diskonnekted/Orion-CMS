<?php
if (!defined('ABSPATH')) {
    // If accessed directly, try to load WP context
    require_once dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
}

global $orion_db, $table_prefix;
$message = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_penduduk'])) {
        $nik = sanitize_text_field($_POST['nik']);
        $nama = sanitize_text_field($_POST['nama']);
        $jk = sanitize_text_field($_POST['jk']);
        $alamat = sanitize_text_field($_POST['alamat']);
        $rt = sanitize_text_field($_POST['rt']);
        $rw = sanitize_text_field($_POST['rw']);
        
        $table = $table_prefix . 'desa_penduduk';
        
        // Simple check
        $check = $orion_db->query("SELECT id FROM $table WHERE nik = '$nik'");
        if ($check && $check->num_rows > 0) {
            $message = "Maaf, NIK tersebut sudah terdaftar di sistem kami.";
            $msg_type = "error";
        } else {
            $sql = "INSERT INTO $table (nik, nama, jk, alamat, rt, rw) VALUES ('$nik', '$nama', '$jk', '$alamat', '$rt', '$rw')";
            if ($orion_db->query($sql)) {
                $message = "Pendaftaran berhasil! Data Anda telah masuk ke database desa.";
                $msg_type = "success";
            } else {
                $message = "Gagal menyimpan data: " . $orion_db->error;
                $msg_type = "error";
            }
        }
    } elseif (isset($_POST['submit_layanan'])) {
        $nik = sanitize_text_field($_POST['nik']);
        $nama = sanitize_text_field($_POST['nama']);
        $jenis = sanitize_text_field($_POST['jenis_surat']);
        $keperluan = sanitize_text_field($_POST['keperluan']);
        
        $table = $table_prefix . 'desa_layanan';
        $sql = "INSERT INTO $table (nik, nama, jenis_surat, keperluan) VALUES ('$nik', '$nama', '$jenis', '$keperluan')";
        
        if ($orion_db->query($sql)) {
            $message = "Pengajuan layanan berhasil dikirim. Silakan tunggu proses dari perangkat desa.";
            $msg_type = "success";
        } else {
            $message = "Gagal mengirim pengajuan.";
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Layanan Warga - Desa Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">

    <!-- Header -->
    <header class="bg-emerald-800 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 bg-emerald-900/50 mix-blend-multiply"></div>
        <div class="container mx-auto px-4 py-12 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Portal Layanan Warga</h1>
            <p class="text-emerald-100 max-w-2xl mx-auto text-lg">Platform pelayanan digital terpadu untuk memudahkan administrasi dan kependudukan masyarakat desa.</p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12 -mt-8 relative z-20">
        
        <?php if($message): ?>
            <div class="max-w-4xl mx-auto mb-8 p-4 rounded-xl shadow-lg border <?php echo $msg_type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'; ?>">
                <p class="font-bold text-center"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            
            <!-- Form Pendaftaran -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Pendaftaran Warga Baru</h2>
                <p class="text-slate-500 text-sm mb-8">Daftarkan diri anda ke dalam database kependudukan desa digital kami.</p>

                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" required pattern="[0-9]{16}" title="NIK harus 16 digit angka" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="16 Digit NIK">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="jk" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Alamat Domisili</label>
                        <textarea name="alamat" required rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="Nama Jalan / Dusun"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">RT</label>
                            <input type="text" name="rt" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="001">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">RW</label>
                            <input type="text" name="rw" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="002">
                        </div>
                    </div>
                    <button type="submit" name="submit_penduduk" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">Daftar Sekarang</button>
                </form>
            </div>

            <!-- Form Layanan -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full -z-10"></div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Pengajuan Surat Online</h2>
                <p class="text-slate-500 text-sm mb-8">Ajukan pembuatan surat keterangan langsung dari rumah. Cepat dan mudah.</p>

                <form method="POST" class="space-y-5 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">NIK Pemohon</label>
                        <input type="text" name="nik" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="Masukkan NIK Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Pemohon</label>
                        <input type="text" name="nama" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Jenis Layanan / Surat</label>
                        <select name="jenis_surat" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-slate-50 focus:bg-white transition">
                            <option value="">Pilih Jenis Surat...</option>
                            <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                            <option value="Surat Keterangan Usaha (SKU)">Surat Keterangan Usaha (SKU)</option>
                            <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Keterangan Tidak Mampu (SKTM)</option>
                            <option value="Pengantar SKCK">Pengantar SKCK</option>
                            <option value="Surat Keterangan Belum Menikah">Surat Keterangan Belum Menikah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Keperluan</label>
                        <textarea name="keperluan" required rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-slate-50 focus:bg-white transition" placeholder="Jelaskan secara singkat keperluan pembuatan surat ini..."></textarea>
                    </div>
                    <button type="submit" name="submit_layanan" class="w-full py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-1">Kirim Pengajuan</button>
                </form>
            </div>

        </div>
    </main>
    
    <footer class="text-center py-8 text-slate-500 text-sm border-t mt-12">
        <p>&copy; <?php echo date('Y'); ?> Sistem Informasi Desa Digital. Ditenagai oleh Orion CMS.</p>
    </footer>

</body>
</html>
