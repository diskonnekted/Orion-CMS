</main>

<?php
$wa = trim((string) get_option('orion_ic_whatsapp', ''));
$profile_id = (int) get_option('orion_ic_page_profile_id', 0);
$program_id = (int) get_option('orion_ic_page_program_id', 0);
$reg_id = (int) get_option('orion_ic_page_registration_id', 0);
$schedule_id = (int) get_option('orion_ic_page_schedule_id', 0);
$gallery_id = (int) get_option('orion_ic_page_gallery_id', 0);
$announcements_id = (int) get_option('orion_ic_page_announcements_id', 0);
$contact_id = (int) get_option('orion_ic_page_contact_id', 0);
?>

<footer class="bg-ic-green-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="space-y-4">
                <div class="text-xl font-bold"><?php echo get_option('blogname', 'Islamic Center'); ?></div>
                <div class="text-white/80 text-sm leading-relaxed">
                    Pusat pendidikan dan pembinaan akhlak. Menyatukan ilmu, adab, dan amal dalam keseharian santri.
                </div>
                <?php if ($wa): ?>
                    <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', $wa); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ic-yellow-300 text-ic-green-900 font-bold hover:bg-ic-yellow-200 transition">
                        <span>WhatsApp</span>
                    </a>
                <?php endif; ?>
            </div>
            <div>
                <div class="font-bold mb-4">Halaman</div>
                <div class="grid gap-2 text-sm text-white/80">
                    <a class="hover:text-white transition" href="<?php echo $profile_id ? site_url('/?page_id=' . $profile_id) : '#'; ?>">Profil</a>
                    <a class="hover:text-white transition" href="<?php echo $program_id ? site_url('/?page_id=' . $program_id) : '#'; ?>">Program</a>
                    <a class="hover:text-white transition" href="<?php echo $schedule_id ? site_url('/?page_id=' . $schedule_id) : '#'; ?>">Jadwal Kegiatan</a>
                    <a class="hover:text-white transition" href="<?php echo $gallery_id ? site_url('/?page_id=' . $gallery_id) : '#'; ?>">Galeri Kegiatan</a>
                    <a class="hover:text-white transition" href="<?php echo $announcements_id ? site_url('/?page_id=' . $announcements_id) : '#'; ?>">Pengumuman</a>
                    <a class="hover:text-white transition" href="<?php echo $reg_id ? site_url('/?page_id=' . $reg_id) : '#'; ?>">Pendaftaran Santri</a>
                    <a class="hover:text-white transition" href="<?php echo $contact_id ? site_url('/?page_id=' . $contact_id) : '#'; ?>">Kontak</a>
                </div>
            </div>
            <div>
                <div class="font-bold mb-4">Fitur</div>
                <div class="grid gap-2 text-sm text-white/80">
                    <a class="hover:text-white transition" href="<?php echo site_url('/'); ?>">Artikel</a>
                    <a class="hover:text-white transition" href="<?php echo $schedule_id ? site_url('/?page_id=' . $schedule_id) : '#'; ?>">Agenda</a>
                </div>
            </div>
            <div>
                <div class="font-bold mb-4">Kontak</div>
                <div class="text-sm text-white/80 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars(get_option('orion_ic_address', "Jl. Pesantren No. 1\nKota, Provinsi"))); ?>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-white/60">
            <div>© <?php echo date('Y'); ?> <?php echo get_option('blogname', 'Islamic Center'); ?></div>
            <div class="flex items-center gap-4">
                <a class="hover:text-white transition" href="<?php echo site_url('/?page=privacy'); ?>">Privasi</a>
                <a class="hover:text-white transition" href="<?php echo site_url('/?page=terms'); ?>">Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
