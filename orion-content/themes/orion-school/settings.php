<?php
// Settings for Orion School Theme

// Get current values
$address = get_option('orion_school_address', 'Jl. Pendidikan No. 123, Jakarta Selatan');
$phone = get_option('orion_school_phone', '(021) 555-0123');
$email = get_option('orion_school_email', 'info@orionschool.sch.id');

$facebook = get_option('orion_school_facebook', '#');
$instagram = get_option('orion_school_instagram', '#');
$youtube = get_option('orion_school_youtube', '#');

$hero_title = get_option('orion_school_hero_title', 'Mewujudkan Generasi Emas Berkarakter');
$hero_subtitle = get_option('orion_school_hero_subtitle', 'Orion School berkomitmen memberikan pendidikan berkualitas dengan standar internasional untuk masa depan yang gemilang.');
$hero_bg = get_option('orion_school_hero_bg', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
$hero_cta_text = get_option('orion_school_hero_cta_text', 'Daftar Sekarang');
$hero_cta_link = get_option('orion_school_hero_cta_link', '?page=ppdb');

$stats_students = get_option('orion_school_stats_students', '1.2k+');
$stats_teachers = get_option('orion_school_stats_teachers', '85');
$stats_extra = get_option('orion_school_stats_extra', '30+');
$stats_graduation = get_option('orion_school_stats_graduation', '100%');
?>

<div class="space-y-8">
    <!-- Contact Information -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Informasi Kontak</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_address">Alamat Sekolah</label>
                <textarea class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_address" name="orion_school_address" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_phone">Nomor Telepon</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_phone" type="text" name="orion_school_phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_email">Email</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_email" type="email" name="orion_school_email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
        </div>
    </div>

    <!-- Social Media -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Media Sosial</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_facebook">Facebook URL</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_facebook" type="text" name="orion_school_facebook" value="<?php echo htmlspecialchars($facebook); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_instagram">Instagram URL</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_instagram" type="text" name="orion_school_instagram" value="<?php echo htmlspecialchars($instagram); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_youtube">YouTube URL</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_youtube" type="text" name="orion_school_youtube" value="<?php echo htmlspecialchars($youtube); ?>">
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Hero Section (Beranda)</h2>
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_hero_title">Judul Utama</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_hero_title" type="text" name="orion_school_hero_title" value="<?php echo htmlspecialchars($hero_title); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_hero_subtitle">Sub-Judul</label>
                <textarea class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_hero_subtitle" name="orion_school_hero_subtitle" rows="3"><?php echo htmlspecialchars($hero_subtitle); ?></textarea>
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_hero_bg">URL Gambar Background</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_hero_bg" type="text" name="orion_school_hero_bg" value="<?php echo htmlspecialchars($hero_bg); ?>">
                <p class="text-slate-500 text-xs italic mt-1">Masukkan URL gambar (rekomendasi: Unsplash)</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_hero_cta_text">Teks Tombol CTA</label>
                    <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_hero_cta_text" type="text" name="orion_school_hero_cta_text" value="<?php echo htmlspecialchars($hero_cta_text); ?>">
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_hero_cta_link">Link Tombol CTA</label>
                    <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_hero_cta_link" type="text" name="orion_school_hero_cta_link" value="<?php echo htmlspecialchars($hero_cta_link); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Statistik Sekolah</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_stats_students">Jumlah Siswa</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_stats_students" type="text" name="orion_school_stats_students" value="<?php echo htmlspecialchars($stats_students); ?>">
                <p class="text-slate-500 text-xs italic mt-1">Contoh: 1.2k+</p>
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_stats_teachers">Jumlah Guru</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_stats_teachers" type="text" name="orion_school_stats_teachers" value="<?php echo htmlspecialchars($stats_teachers); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_stats_extra">Ekstrakurikuler</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_stats_extra" type="text" name="orion_school_stats_extra" value="<?php echo htmlspecialchars($stats_extra); ?>">
            </div>
            <div>
                <label class="block text-slate-700 text-sm font-bold mb-2" for="orion_school_stats_graduation">Kelulusan PTN</label>
                <input class="shadow-sm appearance-none border border-slate-300 rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" id="orion_school_stats_graduation" type="text" name="orion_school_stats_graduation" value="<?php echo htmlspecialchars($stats_graduation); ?>">
            </div>
        </div>
    </div>
</div>
