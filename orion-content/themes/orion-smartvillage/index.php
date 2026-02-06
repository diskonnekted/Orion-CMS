<?php get_header(); ?>

<?php
// Check for Single Post View
$post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
$view = isset($_GET['view']) ? $_GET['view'] : '';

// Special Case: News Page (ID 21) -> Force Archive View
if ($post_id == 21) {
    $view = 'archive';
    $post_id = 0; // Prevent single post view
}

// Special Case: Home Page (ID 11) -> Force Landing Page View
if ($post_id == 11) {
    $post_id = 0; // Prevent single post view, fall through to default landing page
}

$is_single = false;
$post = null;

if ($post_id > 0) {
    $post = get_post($post_id);
    if ($post && $post->post_status == 'publish') {
        $is_single = true;
    }
}

if ($is_single):
    // --- SPECIAL PAGE: CONTACT (ID 6) ---
    if ($post->ID == 6) {
        ?>
        <div class="bg-slate-50 min-h-screen pb-20">
            <!-- Hero Section -->
            <div class="relative bg-slate-900 min-h-[500px] flex items-center justify-center overflow-hidden pb-32 pt-20">
                <div class="absolute inset-0 opacity-40">
                    <img src="https://images.unsplash.com/photo-1516387938699-a93567ec168e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/90"></div>
                <div class="relative z-10 text-center px-4">
                    <span class="text-emerald-400 font-bold tracking-wider uppercase mb-2 block">Hubungi Kami</span>
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Pusat Layanan Masyarakat</h1>
                    <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">Kami siap melayani dan mendengar masukan Anda untuk kemajuan desa bersama. Silakan hubungi kami melalui saluran di bawah ini.</p>
                </div>
            </div>

            <div class="container mx-auto px-4 -mt-24 relative z-20">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Contact Info Cards -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Address -->
                        <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 border border-slate-100">
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Kantor Desa</h3>
                            <p class="text-slate-500 leading-relaxed">Jl. Raya Smart Village No. 1<br>Kecamatan Maju Jaya<br>Kabupaten Sejahtera, 12345</p>
                        </div>

                        <!-- Phone & Email -->
                        <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 border border-slate-100">
                            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Kontak Resmi</h3>
                            <div class="space-y-4">
                                <a href="tel:+6281234567890" class="flex items-center group">
                                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center mr-3 group-hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <span class="text-slate-600 group-hover:text-blue-600 font-medium transition">(021) 123-4567</span>
                                </a>
                                <a href="mailto:info@smartvillage.id" class="flex items-center group">
                                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center mr-3 group-hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-slate-600 group-hover:text-blue-600 font-medium transition">info@smartvillage.id</span>
                                </a>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 border border-slate-100">
                            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Jam Operasional</h3>
                            <ul class="space-y-3">
                                <li class="flex justify-between items-center border-b border-slate-50 pb-2">
                                    <span class="text-slate-500">Senin - Jumat</span> 
                                    <span class="font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full text-sm">08:00 - 16:00</span>
                                </li>
                                <li class="flex justify-between items-center border-b border-slate-50 pb-2">
                                    <span class="text-slate-500">Sabtu</span> 
                                    <span class="font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full text-sm">08:00 - 12:00</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-500">Minggu</span> 
                                    <span class="font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full text-sm">Tutup</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Message Form -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-slate-100">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-slate-800 mb-2">Kirim Pesan</h2>
                                <p class="text-slate-500">Isi formulir di bawah ini untuk mengirimkan saran, pengaduan, atau pertanyaan.</p>
                            </div>
                            
                            <form action="#" method="POST" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap</label>
                                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition outline-none bg-slate-50 focus:bg-white" placeholder="Nama Anda">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Email</label>
                                        <input type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition outline-none bg-slate-50 focus:bg-white" placeholder="email@contoh.com">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Subjek</label>
                                    <select class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition outline-none bg-slate-50 focus:bg-white">
                                        <option>Pilih Kategori Pesan</option>
                                        <option>Layanan Administrasi</option>
                                        <option>Pengaduan Masyarakat</option>
                                        <option>Saran & Masukan</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Pesan</label>
                                    <textarea rows="6" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition outline-none bg-slate-50 focus:bg-white resize-none" placeholder="Tulis pesan Anda secara detail di sini..."></textarea>
                                </div>
                                <div class="pt-4">
                                    <button type="button" class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold py-4 px-10 rounded-xl hover:shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-1 transition w-full md:w-auto flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Kirim Pesan Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Map Section -->
                        <div class="bg-white p-2 rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6664532826353!2d106.8249646747501!3d-6.175387060507297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e04f8298!2sMonas%20Jakarta!5e0!3m2!1sen!2sid!4v1709795000000!5m2!1sen!2sid" width="100%" height="400" style="border:0; border-radius: 1rem;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    // --- SPECIAL PAGE: ABOUT (ID 5) ---
    } elseif ($post->ID == 5) {
        ?>
        <div class="bg-slate-50 min-h-screen pb-20">
            <!-- Hero Section -->
            <div class="relative bg-slate-900 min-h-[500px] flex items-center justify-center overflow-hidden pb-32 pt-20">
                <div class="absolute inset-0 opacity-40">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/90"></div>
                <div class="relative z-10 text-center px-4">
                    <span class="text-emerald-400 font-bold tracking-wider uppercase mb-2 block">Profil Desa</span>
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Membangun Desa, Sejahterakan Warga</h1>
                    <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">Mengenal lebih dekat visi, misi, dan potensi yang kami kembangkan untuk masa depan desa yang lebih baik.</p>
                </div>
            </div>

            <div class="container mx-auto px-4 -mt-24 relative z-20">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 flex items-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mr-6 shadow-sm flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-3xl font-bold text-slate-800">5.420</span>
                            <span class="text-slate-500 font-medium">Jiwa Penduduk</span>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 flex items-center">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mr-6 shadow-sm flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <div>
                            <span class="block text-3xl font-bold text-slate-800">12.5</span>
                            <span class="text-slate-500 font-medium">Luas Wilayah (km²)</span>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 flex items-center">
                        <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mr-6 shadow-sm flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <span class="block text-3xl font-bold text-slate-800">8 RW / 32 RT</span>
                            <span class="text-slate-500 font-medium">Administrasi</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- About Section -->
                        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 border border-slate-100">
                            <h2 class="text-3xl font-bold text-slate-800 mb-6 flex items-center">
                                <span class="bg-emerald-100 text-emerald-600 p-2 rounded-lg mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </span>
                                Sejarah & Profil
                            </h2>
                            <div class="prose prose-lg prose-emerald text-slate-600">
                                <?php echo nl2br($post->post_content); ?>
                            </div>
                        </div>

                        <!-- Vision & Mission -->
                        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 border border-slate-100">
                            <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
                                <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </span>
                                Visi & Misi
                            </h2>
                            
                            <div class="mb-10">
                                <h3 class="text-xl font-bold text-slate-800 mb-4 border-l-4 border-emerald-500 pl-4">Visi</h3>
                                <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
                                    <p class="text-lg text-emerald-800 font-medium italic">"Terwujudnya Desa Sejahtera, Mandiri, dan Berbudaya dengan Tata Kelola Pemerintahan yang Bersih, Transparan, dan Melayani."</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-slate-800 mb-4 border-l-4 border-blue-500 pl-4">Misi</h3>
                                <ul class="space-y-4">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-4 text-sm">1</span>
                                        <span class="text-slate-600 leading-relaxed pt-1">Meningkatkan kualitas pelayanan publik melalui digitalisasi dan reformasi birokrasi desa.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-4 text-sm">2</span>
                                        <span class="text-slate-600 leading-relaxed pt-1">Mengembangkan potensi ekonomi lokal melalui BUMDes dan pemberdayaan UMKM.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-4 text-sm">3</span>
                                        <span class="text-slate-600 leading-relaxed pt-1">Mewujudkan pembangunan infrastruktur yang merata dan berwawasan lingkungan.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-4 text-sm">4</span>
                                        <span class="text-slate-600 leading-relaxed pt-1">Melestarikan nilai-nilai budaya dan kearifan lokal dalam kehidupan bermasyarakat.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-8">
                        <!-- Head of Village -->
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 sticky top-8">
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-center">
                                <h3 class="text-white font-bold text-lg uppercase tracking-wider">Kepala Desa</h3>
                            </div>
                            <div class="p-8 text-center">
                                <div class="w-32 h-32 mx-auto bg-slate-200 rounded-full mb-6 overflow-hidden border-4 border-white shadow-lg">
                                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=10b981&color=fff&size=256" alt="Kepala Desa" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-xl font-bold text-slate-800 mb-1">Bapak Budi Santoso</h4>
                                <span class="text-emerald-600 font-medium text-sm bg-emerald-50 px-3 py-1 rounded-full">Periode 2024 - 2029</span>
                                <p class="text-slate-500 mt-6 text-sm leading-relaxed">"Mari bersama-sama membangun desa kita tercinta menuju masa depan yang lebih gemilang."</p>
                            </div>
                            <div class="bg-slate-50 p-4 border-t border-slate-100 flex justify-center space-x-4">
                                <a href="#" class="text-slate-400 hover:text-emerald-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                                <a href="#" class="text-slate-400 hover:text-blue-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                                <a href="#" class="text-slate-400 hover:text-pink-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        // --- GENERIC SINGLE POST VIEW ---
    $author = get_user_by('id', $post->post_author);
    $author_name = $author ? $author->display_name : 'Admin';
    $date = date('d F Y', strtotime($post->post_date));
    $categories = get_the_terms($post->ID, 'category');
?>

<div class="bg-slate-50 min-h-screen pb-20">
    <!-- Hero Section -->
    <div class="relative bg-slate-900 min-h-[500px] flex items-center justify-center overflow-hidden pb-32 pt-20">
        <div class="absolute inset-0 opacity-40">
            <!-- Default Hero Image for News/Posts - Using a high quality Unsplash image for consistency -->
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="<?php echo $post->post_title; ?>" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/90"></div>
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <?php if ($categories): ?>
                <div class="flex justify-center gap-2 mb-6">
                    <?php foreach($categories as $cat): ?>
                    <span class="bg-emerald-500 text-white text-xs px-4 py-1.5 rounded-full font-bold uppercase tracking-wider shadow-lg ring-2 ring-emerald-500/30"><?php echo $cat->name; ?></span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <span class="text-emerald-400 font-bold tracking-wider uppercase mb-4 block">Berita & Artikel</span>
            <?php endif; ?>
            
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-8 leading-tight drop-shadow-xl"><?php echo $post->post_title; ?></h1>
            
            <div class="flex items-center justify-center space-x-6 text-sm md:text-base text-slate-300">
                <span class="flex items-center bg-slate-800/50 px-4 py-2 rounded-full backdrop-blur-sm border border-slate-700">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> 
                    <?php echo $author_name; ?>
                </span>
                <span class="flex items-center bg-slate-800/50 px-4 py-2 rounded-full backdrop-blur-sm border border-slate-700">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> 
                    <?php echo $date; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 -mt-24 relative z-20">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-100 p-8 md:p-12">
            <div class="prose prose-lg prose-emerald max-w-none text-slate-700 leading-relaxed">
                <?php echo nl2br($post->post_content); ?>
            </div>

            <!-- Back Button -->
            <div class="mt-12 pt-8 border-t border-slate-100">
                <a href="index.php" class="inline-flex items-center text-emerald-600 font-bold hover:text-emerald-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Related News Section -->
        <div class="max-w-4xl mx-auto mt-12">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 border-l-4 border-emerald-500 pl-4">Berita Lainnya</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php 
                $related_posts = get_posts(['numberposts' => 3]);
                $count = 0;
                foreach ($related_posts as $r_post):
                    if ($r_post->ID == $post->ID) continue; 
                    if ($count >= 3) break;
                    $count++;
                ?>
                <article class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://picsum.photos/seed/<?php echo $r_post->ID; ?>/600/400" alt="<?php echo $r_post->post_title; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 left-3">
                            <span class="bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded text-slate-700">
                                <?php echo date('d M', strtotime($r_post->post_date)); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-slate-800 mb-2 leading-snug group-hover:text-emerald-600 transition">
                            <a href="index.php?p=<?php echo $r_post->ID; ?>"><?php echo $r_post->post_title; ?></a>
                        </h4>
                        <a href="index.php?p=<?php echo $r_post->ID; ?>" class="text-sm text-emerald-600 font-medium hover:underline">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php elseif ($view == 'archive'): ?>
    <!-- ARCHIVE VIEW -->
    <div class="bg-slate-50 py-12 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Arsip Berita</h1>
                <p class="text-slate-600 max-w-2xl mx-auto">Kumpulan berita dan informasi terkini seputar kegiatan dan perkembangan desa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php 
                $archive_posts = get_posts(['numberposts' => 6]); // Get more posts for archive
                foreach ($archive_posts as $post):
                ?>
                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group h-full flex flex-col">
                    <div class="h-48 overflow-hidden relative flex-shrink-0">
                        <img src="https://picsum.photos/seed/<?php echo $post->ID; ?>/600/400" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-2 py-1 rounded shadow-sm">
                                <?php echo date('d M', strtotime($post->post_date)); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition line-clamp-2">
                            <a href="index.php?p=<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
                        </h4>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-3 flex-grow"><?php echo substr(strip_tags($post->post_content), 0, 100); ?>...</p>
                        <a href="index.php?p=<?php echo $post->ID; ?>" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 mt-auto inline-flex items-center">
                            Baca selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="mt-12 text-center">
                <a href="index.php" class="inline-flex items-center text-slate-600 font-semibold hover:text-emerald-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

<?php elseif ($view == 'services'): ?>
    <!-- SERVICES VIEW -->
    <div class="bg-slate-50 py-12 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Layanan Desa</h1>
                <p class="text-slate-600 max-w-2xl mx-auto">Akses berbagai layanan administrasi dan informasi publik secara mandiri.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-emerald-500">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Permohonan Surat</h3>
                    <p class="text-slate-500 mb-4">Buat surat pengantar KTP, KK, SKCK, dan surat keterangan lainnya secara online.</p>
                    <button class="text-emerald-600 font-bold text-sm">Akses Layanan &rarr;</button>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-blue-500">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Cek Bantuan Sosial</h3>
                    <p class="text-slate-500 mb-4">Periksa status penerima bantuan sosial (BLT, PKH, BPNT) desa secara transparan.</p>
                    <button class="text-blue-600 font-bold text-sm">Cek Sekarang &rarr;</button>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-yellow-500">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Pengaduan Warga</h3>
                    <p class="text-slate-500 mb-4">Sampaikan aspirasi, keluhan, atau laporan kejadian di lingkungan desa.</p>
                    <button class="text-yellow-600 font-bold text-sm">Buat Laporan &rarr;</button>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="index.php" class="inline-flex items-center text-slate-600 font-semibold hover:text-emerald-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

<?php else: ?>

<!-- Hero Section -->
<section class="relative bg-emerald-800 text-white">
    <!-- Background Pattern & Image -->
    <div class="absolute inset-0 opacity-20 hero-pattern"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-emerald-800/70 z-10"></div>
    <img src="https://picsum.photos/seed/village/1920/1080" alt="Village Landscape" class="absolute inset-0 w-full h-full object-cover">
    
    <div class="container mx-auto px-4 py-24 relative z-20">
        <div class="max-w-2xl">
            <?php 
            $home_page = get_post(11);
            if ($home_page && $home_page->post_status == 'publish'):
            ?>
                <span class="inline-block bg-emerald-700 bg-opacity-50 border border-emerald-500 text-emerald-100 px-3 py-1 rounded-full text-sm font-semibold mb-4 backdrop-blur-sm">
                    Selamat Datang di Website Resmi
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    <?php echo $home_page->post_title; ?>
                </h1>
                <div class="text-emerald-100 text-lg mb-8 leading-relaxed">
                    <?php echo nl2br($home_page->post_content); ?>
                </div>
            <?php else: ?>
                <span class="inline-block bg-emerald-700 bg-opacity-50 border border-emerald-500 text-emerald-100 px-3 py-1 rounded-full text-sm font-semibold mb-4 backdrop-blur-sm">
                    Selamat Datang di Website Resmi
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Membangun Desa Cerdas, <br>Masyarakat Sejahtera
                </h1>
                <p class="text-emerald-100 text-lg mb-8 leading-relaxed">
                    Platform informasi pelayanan publik dan transparansi anggaran desa. Menuju tata kelola pemerintahan yang modern, akuntabel, dan melayani.
                </p>
            <?php endif; ?>
            <div class="flex flex-wrap gap-4">
                <a href="#layanan" class="bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-bold px-6 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-1">
                    Layanan Warga
                </a>
                <a href="#profil" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-6 py-3 rounded-lg backdrop-blur-sm transition">
                    Jelajahi Profil Desa
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards (Floating) -->
    <div class="container mx-auto px-4 relative z-30 -mb-16 hidden md:block">
        <div class="grid grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-emerald-500 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Total Penduduk</p>
                        <h4 class="text-3xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_pop', '4,250'); ?></h4>
                        <span class="text-xs text-green-600 font-medium flex items-center mt-1">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +12% tahun ini
                        </span>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-blue-500 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Kepala Keluarga</p>
                        <h4 class="text-3xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_kk', '1,240'); ?></h4>
                        <span class="text-xs text-blue-600 font-medium mt-1 block">Terdata Digital</span>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-yellow-500 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Luas Wilayah</p>
                        <h4 class="text-3xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_area', '12.5'); ?></h4>
                        <span class="text-xs text-slate-400 font-medium mt-1 block">Kilometer Persegi</span>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-purple-500 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Dana Desa</p>
                        <h4 class="text-3xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_fund', '95%'); ?></h4>
                        <span class="text-xs text-purple-600 font-medium mt-1 block">Realisasi 2024</span>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Stats (Visible only on mobile) -->
<section class="py-8 bg-slate-50 md:hidden">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-emerald-500">
                <p class="text-slate-500 text-xs font-bold">PENDUDUK</p>
                <h4 class="text-2xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_pop', '4,250'); ?></h4>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <p class="text-slate-500 text-xs font-bold">KK</p>
                <h4 class="text-2xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_kk', '1,240'); ?></h4>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <p class="text-slate-500 text-xs font-bold">WILAYAH</p>
                <h4 class="text-2xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_area', '12.5'); ?></h4>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                <p class="text-slate-500 text-xs font-bold">DANA DESA</p>
                <h4 class="text-2xl font-bold text-slate-800"><?php echo get_option('smartvillage_stat_fund', '95%'); ?></h4>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Area -->
<section class="py-16 md:pt-32 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Left Column: Latest News (2/3 width) -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Section Title -->
                <div class="flex justify-between items-end border-b border-slate-200 pb-4">
                    <div>
                        <span class="text-emerald-600 font-bold text-sm tracking-wider uppercase">Kabar Desa</span>
                        <h2 class="text-3xl font-bold text-slate-800 mt-1">Berita Terkini</h2>
                    </div>
                    <a href="index.php?view=archive" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center">
                        Lihat Semua <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <!-- Featured Post (Latest) -->
                <?php 
                $all_posts = get_posts(['numberposts' => 5]); 
                if (!empty($all_posts)): 
                    $featured_post = $all_posts[0];
                ?>
                <article class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="md:flex">
                        <div class="md:w-1/2 overflow-hidden">
                            <img src="https://picsum.photos/seed/<?php echo $featured_post->ID; ?>/800/600" alt="<?php echo $featured_post->post_title; ?>" class="w-full h-64 md:h-full object-cover transform group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-8 md:w-1/2 flex flex-col justify-center">
                            <div class="flex items-center gap-3 text-xs font-medium text-slate-500 mb-4">
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded">Berita Utama</span>
                                <span><?php echo date('d M Y', strtotime($featured_post->post_date)); ?></span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-emerald-700 transition">
                                <a href="index.php?p=<?php echo $featured_post->ID; ?>"><?php echo $featured_post->post_title; ?></a>
                            </h3>
                            <p class="text-slate-600 mb-6 line-clamp-3">
                                <?php echo substr(strip_tags($featured_post->post_content), 0, 150) . '...'; ?>
                            </p>
                            <a href="index.php?p=<?php echo $featured_post->ID; ?>" class="inline-flex items-center text-emerald-600 font-bold hover:text-emerald-700">
                                Baca Selengkapnya <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endif; ?>

                <!-- Recent Posts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php 
                    // Skip the first one (featured)
                    $recent_posts = array_slice($all_posts, 1);
                    foreach ($recent_posts as $post):
                    ?>
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group">
                        <div class="h-48 overflow-hidden relative">
                            <img src="https://picsum.photos/seed/<?php echo $post->ID; ?>/600/400" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-2 py-1 rounded shadow-sm">
                                    <?php echo date('d M', strtotime($post->post_date)); ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition line-clamp-2">
                                <a href="index.php?p=<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
                            </h4>
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2"><?php echo substr(strip_tags($post->post_content), 0, 100); ?>...</p>
                            <a href="index.php?p=<?php echo $post->ID; ?>" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Baca selengkapnya &rarr;</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Right Column: Sidebar (1/3 width) -->
            <div class="space-y-8">
                
                <!-- Kepala Desa Widget -->
                <div class="bg-white rounded-xl shadow-sm p-6 text-center border-t-4 border-emerald-500">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2">Kepala Desa</h3>
                    <div class="w-32 h-32 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-emerald-100 rounded-full transform translate-x-1 translate-y-1"></div>
                        <img src="<?php echo get_option('smartvillage_kades_image', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'); ?>" alt="Kepala Desa" class="w-full h-full object-cover rounded-full border-4 border-white relative z-10 shadow-md">
                    </div>
                    <h4 class="text-xl font-bold text-slate-800"><?php echo get_option('smartvillage_kepala_desa', 'Bapak Susanto, S.IP'); ?></h4>
                    <p class="text-emerald-600 font-medium text-sm mb-4">Periode <?php echo get_option('smartvillage_periode', '2024 - 2030'); ?></p>
                    <p class="text-slate-500 text-sm italic mb-6">"<?php echo get_option('smartvillage_quote', 'Mewujudkan desa yang mandiri, berbudaya, dan berdaya saing melalui inovasi digital.'); ?>"</p>
                    <a href="index.php?p=5" class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 rounded-lg text-sm transition">
                        Sambutan Kades
                    </a>
                </div>

                <!-- Layanan Mandiri Widget -->
                <div class="bg-emerald-600 rounded-xl shadow-lg p-6 text-white overflow-hidden relative">
                    <div class="absolute -right-6 -bottom-6 text-emerald-500 opacity-20">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 relative z-10">Layanan Mandiri</h3>
                    <p class="text-emerald-100 text-sm mb-6 relative z-10">Urus surat pengantar, cek bantuan, dan layanan administrasi lainnya secara online.</p>
                    <ul class="space-y-3 relative z-10">
                        <li>
                            <a href="index.php?view=services" class="flex items-center gap-3 bg-emerald-700 hover:bg-emerald-800 p-3 rounded-lg transition">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="font-medium text-sm">Permohonan Surat</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?view=services" class="flex items-center gap-3 bg-emerald-700 hover:bg-emerald-800 p-3 rounded-lg transition">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium text-sm">Cek Bantuan Sosial</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?p=6" class="flex items-center gap-3 bg-emerald-700 hover:bg-emerald-800 p-3 rounded-lg transition">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span class="font-medium text-sm">Pengaduan Warga</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Agenda Kegiatan Widget -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2">Agenda Kegiatan</h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 text-center">
                                <span class="block text-xs font-bold text-red-500 uppercase">Feb</span>
                                <span class="block text-xl font-bold text-slate-800">10</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-700 text-sm">Musyawarah Desa</h5>
                                <p class="text-xs text-slate-500 mt-1">09:00 WIB @ Balai Desa</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 text-center">
                                <span class="block text-xs font-bold text-red-500 uppercase">Feb</span>
                                <span class="block text-xl font-bold text-slate-800">14</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-700 text-sm">Posyandu Balita</h5>
                                <p class="text-xs text-slate-500 mt-1">08:00 WIB @ Posyandu Mawar</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 text-center">
                                <span class="block text-xs font-bold text-red-500 uppercase">Feb</span>
                                <span class="block text-xl font-bold text-slate-800">20</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-700 text-sm">Kerja Bakti Masal</h5>
                                <p class="text-xs text-slate-500 mt-1">07:00 WIB @ Lingkungan RT 01</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Potensi Desa Gallery -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-emerald-600 font-bold text-sm tracking-wider uppercase">Jelajahi</span>
            <h2 class="text-3xl font-bold text-slate-800 mt-1">Potensi Desa</h2>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto">Mengenal lebih dekat kekayaan alam, budaya, dan produk unggulan yang menjadi kebanggaan desa kami.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php 
            $defaults = [
                1 => ['title' => 'Pertanian', 'desc' => 'Lumbung Padi Berkualitas', 'img' => 'https://picsum.photos/seed/padi/800/600'],
                2 => ['title' => 'UMKM', 'desc' => 'Kerajinan Tangan Lokal', 'img' => 'https://picsum.photos/seed/craft/800/600'],
                3 => ['title' => 'Wisata Alam', 'desc' => 'Air Terjun Bidadari', 'img' => 'https://picsum.photos/seed/waterfall/800/600'],
                4 => ['title' => 'Budaya', 'desc' => 'Tari Tradisional', 'img' => 'https://picsum.photos/seed/dance/800/600']
            ];

            for ($i = 1; $i <= 4; $i++): 
                $title = get_option('smartvillage_potensi_'.$i.'_title', $defaults[$i]['title']);
                $desc = get_option('smartvillage_potensi_'.$i.'_desc', $defaults[$i]['desc']);
                $img = get_option('smartvillage_potensi_'.$i.'_image');
                if (!$img) $img = $defaults[$i]['img'];
            ?>
            <a href="#" class="group relative overflow-hidden rounded-xl h-64">
                <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                    <div>
                        <h4 class="text-white font-bold text-lg"><?php echo $title; ?></h4>
                        <p class="text-slate-300 text-xs mt-1 opacity-0 group-hover:opacity-100 transition duration-300 transform translate-y-2 group-hover:translate-y-0"><?php echo $desc; ?></p>
                    </div>
                </div>
            </a>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>