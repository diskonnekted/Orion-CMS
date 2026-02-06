<!-- Kepala Desa Section -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2 mb-4">Profil Kepala Desa</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_kepala_desa">
                Nama Kepala Desa
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_kepala_desa" type="text" name="smartvillage_kepala_desa" value="<?php echo get_option('smartvillage_kepala_desa', 'Bapak Susanto, S.IP'); ?>">
        </div>
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_periode">
                Periode Jabatan
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_periode" type="text" name="smartvillage_periode" value="<?php echo get_option('smartvillage_periode', '2024 - 2030'); ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_quote">
                Kutipan / Motto
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_quote" name="smartvillage_quote" rows="2"><?php echo get_option('smartvillage_quote', 'Mewujudkan desa yang mandiri, berbudaya, dan berdaya saing melalui inovasi digital.'); ?></textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_kades_image">
                Foto Kepala Desa
            </label>
            <?php if (get_option('smartvillage_kades_image')): ?>
                <div class="mb-2">
                    <img src="<?php echo get_option('smartvillage_kades_image'); ?>" alt="Current Image" class="h-20 w-20 object-cover rounded-full border-2 border-slate-200">
                </div>
            <?php endif; ?>
            <input class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-emerald-50 file:text-emerald-700
                hover:file:bg-emerald-100
            " id="smartvillage_kades_image" type="file" name="smartvillage_kades_image" accept="image/*">
            <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
        </div>
    </div>
</div>

<!-- Informasi Kontak -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2 mb-4">Informasi Kontak</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_phone">
                Nomor Telepon
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_phone" type="text" name="smartvillage_phone" value="<?php echo get_option('smartvillage_phone', '(021) 1234-5678'); ?>">
        </div>
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_email">
                Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_email" type="text" name="smartvillage_email" value="<?php echo get_option('smartvillage_email', 'info@desa-digital.go.id'); ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_address">
                Alamat Kantor Desa
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_address" type="text" name="smartvillage_address" value="<?php echo get_option('smartvillage_address', 'Jl. Raya Desa No. 123, Kecamatan Orion, Kabupaten Orion, Jawa Barat 40000'); ?>">
        </div>
    </div>
</div>

<!-- Statistik Desa -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2 mb-4">Statistik Desa</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_stat_pop">
                Total Penduduk
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_stat_pop" type="text" name="smartvillage_stat_pop" value="<?php echo get_option('smartvillage_stat_pop', '4,250'); ?>">
        </div>
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_stat_kk">
                Jumlah KK
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_stat_kk" type="text" name="smartvillage_stat_kk" value="<?php echo get_option('smartvillage_stat_kk', '1,240'); ?>">
        </div>
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_stat_area">
                Luas Wilayah (km²)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_stat_area" type="text" name="smartvillage_stat_area" value="<?php echo get_option('smartvillage_stat_area', '12.5'); ?>">
        </div>
        <div>
            <label class="block text-slate-700 text-sm font-bold mb-2" for="smartvillage_stat_fund">
                Realisasi Dana (%)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_stat_fund" type="text" name="smartvillage_stat_fund" value="<?php echo get_option('smartvillage_stat_fund', '95%'); ?>">
        </div>
    </div>
</div>

<!-- Potensi Desa -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2 mb-4">Potensi Desa</h3>
    <p class="text-sm text-slate-500 mb-4">Isi hingga 4 potensi unggulan desa Anda.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
            <h4 class="font-bold text-emerald-600 mb-3">Potensi #<?php echo $i; ?></h4>
            
            <div class="mb-3">
                <label class="block text-slate-700 text-xs font-bold mb-1" for="smartvillage_potensi_<?php echo $i; ?>_title">Judul</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 text-sm leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_potensi_<?php echo $i; ?>_title" type="text" name="smartvillage_potensi_<?php echo $i; ?>_title" value="<?php echo get_option('smartvillage_potensi_'.$i.'_title', ($i==1?'Pertanian':($i==2?'UMKM':($i==3?'Pariwisata':'Peternakan')))); ?>">
            </div>
            
            <div class="mb-3">
                <label class="block text-slate-700 text-xs font-bold mb-1" for="smartvillage_potensi_<?php echo $i; ?>_desc">Sub-Judul / Deskripsi Singkat</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 text-sm leading-tight focus:outline-none focus:shadow-outline" id="smartvillage_potensi_<?php echo $i; ?>_desc" type="text" name="smartvillage_potensi_<?php echo $i; ?>_desc" value="<?php echo get_option('smartvillage_potensi_'.$i.'_desc', 'Deskripsi singkat potensi'); ?>">
            </div>
            
            <div>
                <label class="block text-slate-700 text-xs font-bold mb-1" for="smartvillage_potensi_<?php echo $i; ?>_image">Gambar</label>
                <?php if (get_option('smartvillage_potensi_'.$i.'_image')): ?>
                    <div class="mb-2">
                        <img src="<?php echo get_option('smartvillage_potensi_'.$i.'_image'); ?>" class="h-16 w-full object-cover rounded border border-slate-200">
                    </div>
                <?php endif; ?>
                <input class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200" id="smartvillage_potensi_<?php echo $i; ?>_image" type="file" name="smartvillage_potensi_<?php echo $i; ?>_image" accept="image/*">
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>