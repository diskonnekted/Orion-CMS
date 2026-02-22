<?php
$donation_title = get_option('orion_mosque_donation_title', 'Dukung Kegiatan Masjid');
$donation_description = get_option('orion_mosque_donation_description', 'Infaq dan sedekah Anda akan digunakan untuk operasional masjid, program kajian, santunan sosial, dan perawatan fasilitas bersama.');
$donation_account_title = get_option('orion_mosque_donation_account_title', 'Rekening Infaq Operasional');
$donation_account_detail = get_option('orion_mosque_donation_account_detail', 'Bank Syariah Contoh • 1234 5678 90 a.n. DKM Orion Mosque');
$donation_program_title = get_option('orion_mosque_donation_program_title', 'Donasi Program Sosial');
$donation_program_detail = get_option('orion_mosque_donation_program_detail', 'Santunan yatim, dhuafa, dan program Ramadhan');

$contact_address = get_option('orion_mosque_contact_address', 'Jl. Contoh Masjid No. 123, Kota Anda');
$contact_phone = get_option('orion_mosque_contact_phone', '08xx-xxxx-xxxx');
$contact_email = get_option('orion_mosque_contact_email', 'info@orionmosque.id');

$donorbox_enabled = false;
$donorbox_iframe = '';
if (function_exists('orion_donorbox_generate_iframe') && function_exists('orion_donorbox_get_options')) {
    $donorbox_options = orion_donorbox_get_options();
    if (is_array($donorbox_options) && isset($donorbox_options['donorbox_embed_campaign_id']) && trim($donorbox_options['donorbox_embed_campaign_id']) !== '') {
        $donorbox_enabled = true;
        $donorbox_iframe = orion_donorbox_generate_iframe(true, '');
    }
}
?>

<section id="donasi" class="bg-gradient-to-br from-hunter via-mosque-700 to-blushed-brick text-white py-16 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-3xl font-bold mb-4"><?php echo $donation_title; ?></h2>
                <p class="text-mosque-50/90 mb-6 leading-relaxed">
                    <?php echo $donation_description; ?>
                </p>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-green text-hunter">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .843-3 1.882C9 11.157 10.343 12 12 12s3 .843 3 1.882C15 15.157 13.657 16 12 16m0-8c1.11 0 2.08.402 2.598 1M12 8V6m0 10v2m8-8a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold"><?php echo $donation_account_title; ?></p>
                            <p class="text-mosque-50/80 text-xs"><?php echo $donation_account_detail; ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blushed-brick text-white">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold"><?php echo $donation_program_title; ?></p>
                            <p class="text-mosque-50/80 text-xs"><?php echo $donation_program_detail; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/15 p-6 sm:p-8 shadow-xl">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-mosque-500 text-white">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    Donasi Online
                </h3>
                <?php if ($donorbox_enabled): ?>
                    <p class="text-mosque-50/80 text-sm mb-4">
                        Anda dapat berdonasi secara online melalui form Donorbox berikut.
                    </p>
                    <div class="bg-white rounded-xl p-3 text-slate-900">
                        <?php echo $donorbox_iframe; ?>
                    </div>
                <?php else: ?>
                    <p class="text-mosque-50/80 text-sm mb-4">
                        Setelah transfer, silakan kirim bukti donasi untuk kami catat dan doakan secara khusus.
                    </p>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Nama</label>
                            <input type="text" class="w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm placeholder-white/40 focus:outline-none focus:ring-1 focus:ring-mosque-300" placeholder="Nama lengkap">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1">Nominal</label>
                                <input type="text" class="w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm placeholder-white/40 focus:outline-none focus:ring-1 focus:ring-mosque-300" placeholder="Contoh: 500.000">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1">Kontak WhatsApp</label>
                                <input type="text" class="w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm placeholder-white/40 focus:outline-none focus:ring-1 focus:ring-mosque-300" placeholder="08xx">
                            </div>
                        </div>
                        <button type="button" class="w-full mt-2 inline-flex items-center justify-center rounded-lg bg-white text-mosque-700 font-semibold text-sm px-4 py-2 shadow-md hover:bg-mosque-50 transition">
                            Kirim Konfirmasi
                        </button>
                        <p class="text-[11px] text-mosque-50/60 mt-2">
                            Form ini bersifat ilustratif. Silakan sesuaikan dengan sistem pengelolaan donasi masjid Anda.
                        </p>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<footer class="bg-slate-950 text-slate-300 pt-10 pb-6 border-t border-mosque-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-2xl overflow-hidden bg-white flex items-center justify-center">
                        <img src="<?php echo site_url('/orion-content/themes/orion-mosque/logo.png'); ?>" alt="Orion Mosque" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-white">Orion Mosque</p>
                        <p class="text-[11px] uppercase tracking-[0.2em] text-mosque-300">Masjid Ramah Jamaah</p>
                    </div>
                </div>
                <p class="text-sm text-slate-400 max-w-md">
                    Menjadi pusat ibadah, ilmu, dan kegiatan sosial yang menenangkan hati serta menguatkan ukhuwah umat.
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white mb-3">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="index.php#jadwal" class="hover:text-mosque-300 transition">Jadwal Shalat</a></li>
                    <li><a href="index.php#kegiatan" class="hover:text-mosque-300 transition">Agenda Kegiatan</a></li>
                    <li><a href="index.php#berita" class="hover:text-mosque-300 transition">Berita Masjid</a></li>
                    <li><a href="index.php#donasi" class="hover:text-mosque-300 transition">Informasi Donasi</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white mb-3">Kontak</h3>
                <ul class="space-y-2 text-sm">
                    <li><?php echo nl2br($contact_address); ?></li>
                    <li>Telp: <?php echo $contact_phone; ?></li>
                    <li>Email: <?php echo $contact_email; ?></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-4 text-xs flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; <?php echo date('Y'); ?> Orion Mosque. Seluruh hak cipta dilindungi.</p>
            <p class="text-slate-500">Ditenagai oleh Orion CMS.</p>
        </div>
    </div>
</footer>

</main>

</body>
</html>
