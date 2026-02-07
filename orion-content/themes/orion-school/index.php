<?php get_header(); ?>

<?php
$page = isset($_GET['page']) ? $_GET['page'] : '';
$post_id = isset($_GET['p']) ? intval($_GET['p']) : 0;

if ($post_id > 0) {
    // Single Post View (e.g. News Detail)
    $post = get_post($post_id);
    if ($post) {
        ?>
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <?php if (get_the_post_thumbnail_url($post->ID)): ?>
                <img src="<?php echo get_the_post_thumbnail_url($post->ID); ?>" class="w-full h-96 object-cover">
                <?php endif; ?>
                
                <div class="p-8 md:p-12">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4"><?php echo htmlspecialchars($post->post_title); ?></h1>
                    <div class="flex items-center text-slate-500 text-sm mb-8 border-b border-slate-100 pb-6">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <?php echo date('d M Y', strtotime($post->post_date)); ?>
                        </span>
                        <span class="mx-3 text-slate-300">|</span>
                        <span class="flex items-center text-primary-600 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            Berita Sekolah
                        </span>
                    </div>
                    <div class="prose prose-lg prose-slate max-w-none text-slate-600 leading-relaxed">
                        <?php echo nl2br($post->post_content); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    // Page Routing
    switch ($page) {
        case 'sejarah':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Sejarah Sekolah</h1>
                    <p class="text-primary-100 max-w-2xl mx-auto text-lg">Perjalanan panjang kami dalam mencerdaskan kehidupan bangsa.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-lg border border-slate-100 prose prose-lg prose-slate text-slate-600">
                    <p class="lead text-xl text-slate-800 font-medium border-l-4 border-primary-500 pl-4 mb-8">Orion School didirikan pada tahun 1990 dengan semangat untuk memberikan pendidikan berkualitas bagi masyarakat sekitar.</p>
                    <p>Berawal dari sebuah gedung sederhana dengan 3 ruang kelas, kini kami telah berkembang menjadi institusi pendidikan modern yang dilengkapi dengan berbagai fasilitas penunjang pembelajaran.</p>
                    <p>Pada tahun 2005, sekolah kami mendapatkan akreditasi A dan mulai membuka program kelas internasional. Inovasi terus kami lakukan demi menjawab tantangan zaman...</p>
                    
                    <div class="my-8 grid grid-cols-2 gap-4 not-prose">
                        <div class="bg-slate-50 p-6 rounded-xl text-center border border-slate-200">
                            <div class="text-3xl font-bold text-primary-600 mb-2">1990</div>
                            <div class="text-sm text-slate-500">Tahun Berdiri</div>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-xl text-center border border-slate-200">
                            <div class="text-3xl font-bold text-primary-600 mb-2">A</div>
                            <div class="text-sm text-slate-500">Akreditasi</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'visi-misi':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                 <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                 <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Visi & Misi</h1>
                    <p class="text-primary-100 text-lg">Arah dan tujuan kami melangkah untuk masa depan.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-primary-500 hover:shadow-xl transition duration-300">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                            <span class="bg-primary-100 text-primary-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </span>
                            Visi
                        </h2>
                        <p class="text-slate-600 italic text-lg leading-relaxed bg-slate-50 p-6 rounded-xl border border-slate-100">"Menjadi sekolah unggulan yang melahirkan generasi cerdas, berkarakter mulia, dan berwawasan global pada tahun 2030."</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary-500 hover:shadow-xl transition duration-300">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                            <span class="bg-secondary-100 text-secondary-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                            Misi
                        </h2>
                        <ul class="space-y-4 text-slate-600">
                            <li class="flex items-start bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-secondary-500 mr-3 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                Menyelenggarakan pendidikan yang integratif dan holistik.
                            </li>
                            <li class="flex items-start bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-secondary-500 mr-3 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                Mengembangkan potensi siswa secara optimal sesuai bakat.
                            </li>
                            <li class="flex items-start bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-secondary-500 mr-3 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                Menanamkan nilai-nilai karakter, disiplin, dan budi pekerti luhur.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'sambutan':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Sambutan Kepala Sekolah</h1>
                    <p class="text-primary-100 text-lg">Pesan hangat dari pimpinan sekolah kami.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-5xl mx-auto flex flex-col md:flex-row gap-12 items-start">
                    <div class="md:w-1/3 w-full sticky top-24">
                        <div class="bg-white p-2 rounded-2xl shadow-xl border border-slate-100">
                            <div class="bg-slate-100 rounded-xl overflow-hidden aspect-[3/4] flex items-center justify-center relative group">
                                <span class="text-8xl transform group-hover:scale-110 transition duration-500">👨‍💼</span>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <h3 class="font-bold text-2xl text-slate-900">Dr. Budi Santoso, M.Pd.</h3>
                            <p class="text-primary-600 font-medium bg-primary-50 inline-block px-4 py-1 rounded-full mt-2 text-sm">Kepala Sekolah</p>
                        </div>
                    </div>
                    <div class="md:w-2/3 bg-white p-8 md:p-10 rounded-2xl shadow-lg border border-slate-100 relative">
                        <div class="absolute -left-4 top-10 w-8 h-8 bg-white transform rotate-45 border-l border-b border-slate-100 hidden md:block"></div>
                        <div class="prose prose-lg prose-slate text-slate-600 max-w-none">
                            <h3 class="text-slate-800 font-bold mb-4">Assalamu'alaikum Warahmatullahi Wabarakatuh,</h3>
                            <p>Selamat datang di website resmi Orion School. Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa atas segala limpahan rahmat-Nya.</p>
                            <p>Website ini hadir sebagai media informasi dan komunikasi antara sekolah, orang tua, siswa, dan masyarakat luas. Di era digital ini, kami berkomitmen untuk terus berinovasi dalam memberikan layanan pendidikan terbaik.</p>
                            <blockquote class="bg-primary-50 border-l-4 border-primary-500 p-4 italic text-slate-700 rounded-r-lg my-6">
                                "Pendidikan bukan hanya tentang mengisi wadah, tetapi tentang menyalakan api semangat belajar."
                            </blockquote>
                            <p>Kami berharap website ini dapat memberikan gambaran yang jelas tentang program-program sekolah, prestasi siswa, serta berbagai aktivitas yang kami laksanakan.</p>
                            <p>Mari bersama-sama kita wujudkan generasi yang unggul, berkarakter, dan siap menghadapi tantangan masa depan.</p>
                            <p class="font-bold text-slate-800 mt-8">Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'guru':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Guru & Staf</h1>
                    <p class="text-primary-100 text-lg">Pendidik profesional yang berdedikasi tinggi.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Teacher Card -->
                    <?php for($i=1; $i<=8; $i++): ?>
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 group">
                        <div class="h-64 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                            <span class="text-6xl transform group-hover:scale-110 transition duration-500">👨‍🏫</span>
                            <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end justify-center pb-6">
                                <div class="flex space-x-3">
                                    <a href="#" class="bg-white/20 hover:bg-white text-white hover:text-primary-900 p-2 rounded-full backdrop-blur-sm transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                                    <a href="#" class="bg-white/20 hover:bg-white text-white hover:text-primary-900 p-2 rounded-full backdrop-blur-sm transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-primary-600 transition">Guru <?php echo $i; ?></h3>
                            <p class="text-primary-600 text-sm font-medium mb-1">Matematika</p>
                            <p class="text-slate-400 text-xs uppercase tracking-wide">S.Pd., M.Pd.</p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <?php
            break;

        case 'ppdb':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Penerimaan Peserta Didik Baru</h1>
                    <p class="text-primary-100 text-lg">Bergabunglah menjadi bagian dari keluarga besar Orion School.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
                    <div class="p-8 md:p-12 border-b border-slate-100">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-slate-900">Jadwal PPDB 2026/2027</h2>
                            <span class="bg-secondary-100 text-secondary-700 px-4 py-1 rounded-full text-sm font-bold border border-secondary-200 animate-pulse">Sedang Dibuka</span>
                        </div>
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-slate-700">
                                    <tr>
                                        <th class="p-4 border-b border-slate-200 font-bold text-sm uppercase tracking-wide">Kegiatan</th>
                                        <th class="p-4 border-b border-slate-200 font-bold text-sm uppercase tracking-wide">Tanggal</th>
                                        <th class="p-4 border-b border-slate-200 font-bold text-sm uppercase tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-600 divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-4 font-medium">Pendaftaran Online</td>
                                        <td class="p-4">1 Feb - 30 Mar 2026</td>
                                        <td class="p-4"><span class="bg-secondary-100 text-secondary-700 px-2 py-0.5 rounded text-xs font-bold border border-secondary-200">BUKA</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-4 font-medium">Verifikasi Berkas</td>
                                        <td class="p-4">1 - 5 April 2026</td>
                                        <td class="p-4"><span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">MENUNGGU</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-4 font-medium">Tes Akademik</td>
                                        <td class="p-4">10 April 2026</td>
                                        <td class="p-4 text-sm text-slate-500">Offline (Di Sekolah)</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-4 font-medium">Pengumuman</td>
                                        <td class="p-4">20 April 2026</td>
                                        <td class="p-4 text-sm text-slate-500">Via Website</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="p-8 md:p-12 bg-slate-50 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Siap Mendaftar?</h3>
                        <p class="text-slate-600 mb-8 max-w-lg mx-auto">Silakan lengkapi formulir pendaftaran online. Pastikan data yang Anda masukkan benar dan sesuai dengan dokumen asli.</p>
                        <a href="#" class="px-8 py-4 bg-gradient-to-r from-secondary-600 to-secondary-500 hover:from-secondary-700 hover:to-secondary-600 text-white font-bold rounded-xl shadow-lg shadow-secondary-500/30 transition transform hover:-translate-y-1 inline-flex items-center text-lg">
                            Isi Formulir Pendaftaran
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'kontak':
            ?>
            <div class="bg-primary-900 py-20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-900/50 mix-blend-multiply"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Hubungi Kami</h1>
                    <p class="text-primary-100 text-lg">Kami siap melayani informasi yang Anda butuhkan.</p>
                </div>
            </div>
            <div class="container mx-auto px-4 py-16">
                <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100">
                    <div class="md:w-5/12 p-10 bg-gradient-to-br from-slate-900 to-primary-900 text-white relative overflow-hidden">
                        <div class="hero-pattern absolute inset-0 opacity-10"></div>
                        <h3 class="text-2xl font-bold mb-8 relative z-10">Informasi Kontak</h3>
                        <div class="space-y-8 relative z-10">
                            <div class="flex items-start">
                                <div class="bg-white/10 p-3 rounded-lg text-primary-300 mr-4 backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg mb-1">Alamat</h4>
                                    <p class="text-primary-100 text-sm leading-relaxed">Jl. Pendidikan No. 123<br>Jakarta Selatan, DKI Jakarta 12345</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-white/10 p-3 rounded-lg text-primary-300 mr-4 backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg mb-1">Telepon</h4>
                                    <p class="text-primary-100 text-sm">(021) 1234-5678</p>
                                    <p class="text-primary-100 text-sm text-xs mt-1">Senin - Jumat, 07:00 - 16:00 WIB</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-white/10 p-3 rounded-lg text-primary-300 mr-4 backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg mb-1">Email</h4>
                                    <p class="text-primary-100 text-sm">info@orionschool.sch.id</p>
                                    <p class="text-primary-100 text-sm">admissions@orionschool.sch.id</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-7/12 p-10">
                        <h3 class="text-2xl font-bold text-slate-900 mb-6">Kirim Pesan</h3>
                        <form action="" method="post" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Nama Lengkap</label>
                                    <input type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition" placeholder="Nama Anda">
                                </div>
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Email</label>
                                    <input type="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition" placeholder="email@contoh.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2">Subjek</label>
                                <input type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition" placeholder="Judul Pesan">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2">Pesan</label>
                                <textarea class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition h-32 resize-none" placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            <button class="w-full bg-primary-600 text-white font-bold py-4 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-500/30 transform hover:-translate-y-1">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            break;

        default:
            // Homepage
            ?>
            <!-- Hero Section -->
            <section class="relative bg-primary-900 text-white py-28 md:py-40 overflow-hidden">
                <!-- Background Image & Gradient -->
                <div class="absolute inset-0 z-0">
                    <img src="<?php echo get_option('orion_school_hero_bg', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'); ?>" alt="School Background" class="w-full h-full object-cover opacity-20">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-900 via-primary-900/90 to-primary-800/80"></div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-secondary-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>

                <div class="container mx-auto px-4 relative z-10">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-primary-100 text-sm font-semibold mb-8 shadow-sm">
                            <span class="flex h-2 w-2 relative mr-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary-500"></span>
                            </span>
                            Penerimaan Siswa Baru Tahun Ajaran 2026/2027
                        </div>
                        
                        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight tracking-tight">
                            <?php echo get_option('orion_school_hero_title', 'Mewujudkan Generasi Emas Berkarakter'); ?>
                        </h1>
                        
                        <p class="text-xl md:text-2xl text-primary-100 mb-10 leading-relaxed max-w-2xl font-light">
                            <?php echo get_option('orion_school_hero_subtitle', 'Orion School berkomitmen memberikan pendidikan berkualitas dengan standar internasional untuk masa depan yang gemilang.'); ?>
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-5">
                            <a href="<?php echo get_option('orion_school_hero_cta_link', '?page=ppdb'); ?>" class="px-8 py-4 bg-secondary-600 hover:bg-secondary-500 text-white font-bold rounded-xl shadow-xl shadow-secondary-600/30 transition transform hover:-translate-y-1 text-center flex items-center justify-center">
                                <?php echo get_option('orion_school_hero_cta_text', 'Daftar Sekarang'); ?>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                            <a href="?page=profil" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-bold rounded-xl transition text-center flex items-center justify-center">
                                Profil Sekolah
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section (Fixed Visibility) -->
            <section class="relative -mt-16 z-20 pb-20">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center transform hover:-translate-y-2 transition duration-300">
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mb-2"><?php echo get_option('orion_school_stats_students', '1.2k+'); ?></div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Siswa Aktif</div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center transform hover:-translate-y-2 transition duration-300 delay-75">
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mb-2"><?php echo get_option('orion_school_stats_teachers', '85'); ?></div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Guru Profesional</div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center transform hover:-translate-y-2 transition duration-300 delay-100">
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mb-2"><?php echo get_option('orion_school_stats_extra', '30+'); ?></div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Ekstrakurikuler</div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center transform hover:-translate-y-2 transition duration-300 delay-150">
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mb-2"><?php echo get_option('orion_school_stats_graduation', '100%'); ?></div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Lulusan PTN</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Latest News -->
            <section class="py-20 bg-slate-50">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                        <div>
                            <span class="text-primary-600 font-bold tracking-wider uppercase text-sm">Update Terkini</span>
                            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Berita & Kegiatan Sekolah</h2>
                        </div>
                        <a href="?page=berita" class="group flex items-center text-primary-600 font-bold hover:text-primary-800 transition">
                            Lihat Semua Berita 
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php 
                        $posts = get_posts(array('numberposts' => 3)); 
                        if ($posts): foreach($posts as $post):
                            $thumb = get_the_post_thumbnail_url($post->ID);
                        ?>
                        <article class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-2xl hover:border-primary-100 transition duration-300 flex flex-col h-full">
                            <div class="h-56 bg-slate-200 overflow-hidden relative">
                                <?php if ($thumb): ?>
                                <img src="<?php echo $thumb; ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium">No Image</span>
                                </div>
                                <?php endif; ?>
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-primary-700 uppercase tracking-wide shadow-sm">
                                    Berita
                                </div>
                            </div>
                            <div class="p-8 flex-grow flex flex-col">
                                <div class="text-xs text-slate-500 font-bold uppercase mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <?php echo date('d M Y', strtotime($post->post_date)); ?>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-primary-600 transition leading-snug">
                                    <a href="?p=<?php echo $post->ID; ?>" class="focus:outline-none">
                                        <span class="absolute inset-0"></span>
                                        <?php echo $post->post_title; ?>
                                    </a>
                                </h3>
                                <p class="text-slate-600 text-sm line-clamp-3 mb-6 leading-relaxed flex-grow">
                                    <?php echo substr(strip_tags($post->post_content), 0, 120); ?>...
                                </p>
                                <div class="flex items-center text-primary-600 font-bold text-sm mt-auto group-hover:translate-x-1 transition">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </section>
            
            <!-- Call to Action -->
            <section class="py-24 bg-gradient-to-br from-primary-800 to-primary-900 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full filter blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary-500 opacity-10 rounded-full filter blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
                
                <div class="container mx-auto px-4 text-center relative z-10">
                    <h2 class="text-3xl md:text-5xl font-bold mb-6">Siap untuk Bergabung?</h2>
                    <p class="text-primary-100 text-lg md:text-xl max-w-2xl mx-auto mb-10">Jangan lewatkan kesempatan untuk memberikan pendidikan terbaik bagi putra-putri Anda.</p>
                    <a href="?page=ppdb" class="inline-block px-10 py-4 bg-white text-primary-900 font-bold rounded-full shadow-2xl hover:bg-slate-50 transition transform hover:-translate-y-1 hover:shadow-white/20">
                        Daftar PPDB Online Sekarang
                    </a>
                </div>
            </section>
            <?php
            break;
    }
}
?>

<?php get_footer(); ?>
