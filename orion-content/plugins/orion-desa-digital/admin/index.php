<?php
if (!defined('ABSPATH')) exit;
global $orion_db, $table_prefix;

// Handle Form Submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_profil_desa'])) {
        update_option('desa_kades', sanitize_text_field($_POST['desa_kades']));
        update_option('desa_sekdes', sanitize_text_field($_POST['desa_sekdes']));
        update_option('desa_anggaran', sanitize_text_field($_POST['desa_anggaran']));
        update_option('desa_peta', $_POST['desa_peta']); // iframe embed
        $message = "Profil & Anggaran Desa berhasil disimpan.";
    } elseif (isset($_POST['update_layanan_id'])) {
        $id = (int)$_POST['update_layanan_id'];
        $status = sanitize_text_field($_POST['status']);
        $table_layanan = $table_prefix . 'desa_layanan';
        $orion_db->query("UPDATE $table_layanan SET status = '$status' WHERE id = $id");
        $message = "Status layanan diperbarui.";
    }
}

// Fetch Stats
$table_penduduk = $table_prefix . 'desa_penduduk';
$table_layanan = $table_prefix . 'desa_layanan';

$total_penduduk = 0;
$res = $orion_db->query("SELECT COUNT(*) as count FROM $table_penduduk");
if ($res) { $row = $res->fetch_object(); $total_penduduk = $row->count; }

$pending_layanan = 0;
$res = $orion_db->query("SELECT COUNT(*) as count FROM $table_layanan WHERE status = 'Pending'");
if ($res) { $row = $res->fetch_object(); $pending_layanan = $row->count; }

// Fetch Data
$layanan_list = [];
$res = $orion_db->query("SELECT * FROM $table_layanan ORDER BY tanggal_pengajuan DESC LIMIT 10");
if ($res) { while($row = $res->fetch_object()) $layanan_list[] = $row; }

$penduduk_list = [];
$res = $orion_db->query("SELECT * FROM $table_penduduk ORDER BY created_at DESC LIMIT 10");
if ($res) { while($row = $res->fetch_object()) $penduduk_list[] = $row; }

// Options
$kades = get_option('desa_kades', 'Bpk. Ahmad Sujatmiko');
$sekdes = get_option('desa_sekdes', 'Ibu Siti Aminah');
$anggaran = get_option('desa_anggaran', 'Rp 1.250.000.000');
$peta = get_option('desa_peta', '');

// Header already included by Orion
?>

<div class="wrap">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Dasbor Desa Digital
            </h1>
            <p class="text-slate-500 mt-1">Sistem Informasi & Manajemen Pelayanan Desa Terpadu</p>
        </div>
        <a href="<?php echo site_url('?desa_layanan=1'); ?>" target="_blank" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg shadow hover:bg-emerald-700 transition font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            Buka Portal Warga
        </a>
    </div>

    <?php if($message): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 text-green-700 rounded-r-lg shadow-sm font-medium">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <!-- Overview Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Total Penduduk</p>
                <h3 class="text-3xl font-black text-slate-800"><?php echo $total_penduduk; ?> <span class="text-sm text-slate-500 font-normal">Jiwa</span></h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Layanan Pending</p>
                <h3 class="text-3xl font-black <?php echo $pending_layanan > 0 ? 'text-amber-500' : 'text-slate-800'; ?>"><?php echo $pending_layanan; ?> <span class="text-sm text-slate-500 font-normal">Berkas</span></h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between col-span-1 md:col-span-2">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Anggaran Terserap (Estimasi)</p>
                <h3 class="text-3xl font-black text-emerald-600"><?php echo htmlspecialchars($anggaran); ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Tables -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Antrean Layanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h2 class="font-bold text-slate-800 text-lg">Antrean Layanan Warga</h2>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded">Update Real-time</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100">
                                <th class="p-4 font-semibold">Nama / NIK</th>
                                <th class="p-4 font-semibold">Keperluan</th>
                                <th class="p-4 font-semibold">Tanggal</th>
                                <th class="p-4 font-semibold text-center">Status</th>
                                <th class="p-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($layanan_list)): ?>
                            <tr><td colspan="5" class="p-8 text-center text-slate-400 italic">Tidak ada antrean layanan saat ini.</td></tr>
                            <?php else: foreach($layanan_list as $l): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-slate-800"><?php echo htmlspecialchars($l->nama); ?></p>
                                    <p class="text-xs text-slate-500 font-mono"><?php echo htmlspecialchars($l->nik); ?></p>
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-slate-700"><?php echo htmlspecialchars($l->jenis_surat); ?></p>
                                    <p class="text-xs text-slate-500 truncate w-48"><?php echo htmlspecialchars($l->keperluan); ?></p>
                                </td>
                                <td class="p-4 text-slate-500"><?php echo date('d M Y', strtotime($l->tanggal_pengajuan)); ?></td>
                                <td class="p-4 text-center">
                                    <?php 
                                    $bg = $l->status == 'Selesai' ? 'bg-green-100 text-green-700' : ($l->status == 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700');
                                    ?>
                                    <span class="<?php echo $bg; ?> px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"><?php echo $l->status; ?></span>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="update_layanan_id" value="<?php echo $l->id; ?>">
                                        <?php if($l->status == 'Pending'): ?>
                                            <input type="hidden" name="status" value="Diproses">
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium text-xs border border-blue-200 hover:border-blue-600 px-3 py-1 rounded transition">Proses</button>
                                        <?php elseif($l->status == 'Diproses'): ?>
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-medium text-xs border border-emerald-200 hover:border-emerald-600 px-3 py-1 rounded transition">Selesai</button>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-xs">✔ Done</span>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Data Penduduk Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h2 class="font-bold text-slate-800 text-lg">Pendaftaran Penduduk Baru</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100">
                                <th class="p-4 font-semibold">Nama / NIK</th>
                                <th class="p-4 font-semibold">Alamat (RT/RW)</th>
                                <th class="p-4 font-semibold">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($penduduk_list)): ?>
                            <tr><td colspan="3" class="p-8 text-center text-slate-400 italic">Belum ada data penduduk.</td></tr>
                            <?php else: foreach($penduduk_list as $p): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-slate-800 flex items-center gap-2">
                                        <?php echo htmlspecialchars($p->nama); ?>
                                        <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded font-mono"><?php echo $p->jk; ?></span>
                                    </p>
                                    <p class="text-xs text-slate-500 font-mono"><?php echo htmlspecialchars($p->nik); ?></p>
                                </td>
                                <td class="p-4 text-slate-600">
                                    <?php echo htmlspecialchars($p->alamat); ?> <br>
                                    <span class="text-xs font-bold text-slate-400">RT <?php echo htmlspecialchars($p->rt); ?> / RW <?php echo htmlspecialchars($p->rw); ?></span>
                                </td>
                                <td class="p-4 text-slate-500 text-xs"><?php echo date('d M Y', strtotime($p->created_at)); ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column: Settings & Map -->
        <div class="space-y-8">
            <!-- Profil & Aparatur Desa -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="font-bold text-slate-800 text-lg">Profil & Administratif</h2>
                </div>
                <form method="POST" class="p-6 space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kepala Desa</label>
                        <input type="text" name="desa_kades" value="<?php echo htmlspecialchars($kades); ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sekretaris Desa</label>
                        <input type="text" name="desa_sekdes" value="<?php echo htmlspecialchars($sekdes); ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Total Anggaran (Rp)</label>
                        <input type="text" name="desa_anggaran" value="<?php echo htmlspecialchars($anggaran); ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-emerald-700 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Peta Digital (Iframe Gmaps)</label>
                        <textarea name="desa_peta" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-xs text-slate-500 font-mono" placeholder="<iframe src='...'></iframe>"><?php echo htmlspecialchars($peta); ?></textarea>
                    </div>
                    
                    <button type="submit" name="save_profil_desa" class="w-full bg-slate-800 text-white font-bold py-3 rounded-lg hover:bg-slate-900 transition">Simpan Profil Desa</button>
                </form>
            </div>

            <!-- Peta Preview -->
            <?php if($peta): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <h2 class="font-bold text-slate-800">Peta Wilayah</h2>
                </div>
                <div class="w-full h-64 [&>iframe]:w-full [&>iframe]:h-full">
                    <?php echo $peta; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
