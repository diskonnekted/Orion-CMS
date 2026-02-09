<div class="bg-slate-50 min-h-screen pt-24">
    <!-- Hero Header -->
    <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-brand-900 text-white py-20 lg:py-28 overflow-hidden rounded-b-[3rem] shadow-2xl">
        <!-- Background Patterns -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 py-1 px-4 rounded-full bg-white/10 border border-white/20 text-brand-300 text-sm font-semibold mb-8 backdrop-blur-md shadow-lg">
                <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                Dokumentasi Resmi v1.0
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight leading-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400">
                    Kuasai Orion CMS
                </span>
            </h1>
            
            <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed mb-10 font-light">
                Panduan komprehensif untuk membangun website modern dengan performa tinggi menggunakan Orion CMS.
            </p>
            
            <!-- Search Box (Visual) -->
            <div class="max-w-2xl mx-auto relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-brand-500 to-purple-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-6 py-4 shadow-xl transition-transform transform group-hover:scale-[1.01]">
                    <svg class="w-6 h-6 text-slate-300 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" placeholder="Cari artikel, tutorial, atau referensi API..." class="bg-transparent border-none text-white placeholder-slate-400 focus:ring-0 w-full outline-none text-lg">
                    <span class="hidden md:block text-xs text-slate-400 border border-white/10 px-2 py-1 rounded bg-black/20">Ctrl + K</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="container mx-auto px-6 py-16 relative z-20">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="sticky top-32 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 uppercase text-xs tracking-wider">Daftar Isi</h3>
                        <span class="text-xs text-slate-400">6 Topik</span>
                    </div>
                    <nav class="p-3">
                        <ul class="space-y-1">
                            <li>
                                <a href="#pengenalan" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Pengenalan</span>
                                </a>
                            </li>
                            <li>
                                <a href="#fitur-utama" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 3.214L13 21l-2.286-6.857L5 12l5.714-3.214L13 3z"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Fitur Utama</span>
                                </a>
                            </li>
                            <li>
                                <a href="#persyaratan-sistem" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-xs group-hover:bg-green-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Persyaratan Sistem</span>
                                </a>
                            </li>
                            <li>
                                <a href="#instalasi" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-xs group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Instalasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="#struktur-direktori" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Struktur Direktori</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tema-plugin" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all group">
                                    <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center text-xs group-hover:bg-pink-600 group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                    </span>
                                    <span class="font-medium text-sm">Tema & Plugin</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 space-y-8">
                <!-- Introduction Card -->
                <div id="pengenalan" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 scroll-mt-32 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900">Apa itu Orion CMS?</h2>
                    </div>
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg leading-relaxed text-slate-600">
                            Orion CMS adalah sistem manajemen konten (CMS) yang <span class="font-semibold text-brand-600">ringan, modular, dan berorientasi pada performa</span>, dibangun dengan PHP native dan MySQL. Dirancang untuk fleksibilitas dan kemudahan penggunaan, Orion CMS menawarkan fondasi yang kokoh untuk membangun website, blog, portofolio, dan toko online dengan fokus pada standar pengembangan modern dan arsitektur yang bersih.
                        </p>
                    </div>
                </div>

                <!-- Features Card -->
                <div id="fitur-utama" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 scroll-mt-32 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 3.214L13 21l-2.286-6.857L5 12l5.714-3.214L13 3z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900">Fitur Unggulan</h2>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group p-6 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all duration-300">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                                🚀 Core Ringan
                            </h4>
                            <p class="text-sm text-slate-600">Dioptimalkan untuk kecepatan loading maksimal dan penggunaan sumber daya server yang minimal.</p>
                        </div>
                        <div class="group p-6 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all duration-300">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                                🧩 Arsitektur Modular
                            </h4>
                            <p class="text-sm text-slate-600">Sistem plugin yang tangguh memungkinkan Anda menambah fitur tanpa membebani core sistem.</p>
                        </div>
                        <div class="group p-6 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all duration-300">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                                🎨 Theme Engine Modern
                            </h4>
                            <p class="text-sm text-slate-600">Struktur tema yang familiar (mirip WordPress) memudahkan developer beradaptasi dengan cepat.</p>
                        </div>
                        <div class="group p-6 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all duration-300">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                                🛍️ E-Commerce Ready
                            </h4>
                            <p class="text-sm text-slate-600">Sudah termasuk tema Orion Shop dengan fitur produk, kategori, dan manajemen stok sederhana.</p>
                        </div>
                    </div>
                </div>

                <!-- System Requirements & Installation -->
                <div class="grid md:grid-cols-2 gap-8">
                    <div id="persyaratan-sistem" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 scroll-mt-32 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Persyaratan Sistem</h3>
                        </div>
                        <ul class="space-y-4 text-slate-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>PHP:</strong> Versi 7.4 atau lebih tinggi</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Database:</strong> MySQL 5.7+ atau MariaDB 10.2+</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Server:</strong> Apache (mod_rewrite) / Nginx</span>
                            </li>
                        </ul>
                    </div>

                    <div id="instalasi" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 scroll-mt-32 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Mulai Cepat</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm text-slate-500 mb-1">
                                    <span>Clone Repository</span>
                                    <span class="text-xs bg-slate-100 px-2 py-0.5 rounded">Terminal</span>
                                </div>
                                <div class="bg-slate-800 text-slate-300 p-3 rounded-lg font-mono text-sm overflow-x-auto border border-slate-700">
                                    git clone https://github.com/diskonnekted/Orion-CMS.git
                                </div>
                            </div>
                            <p class="text-sm text-slate-600">
                                Setelah clone, rename <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">orion-config-sample.php</code> ke <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">orion-config.php</code> dan sesuaikan database.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Directory Structure -->
                <div id="struktur-direktori" class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-8 md:p-12 scroll-mt-32 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-brand-900 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
                    
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-slate-800 text-yellow-400 flex items-center justify-center border border-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white">Struktur Direktori</h2>
                    </div>

                    <div class="font-mono text-sm text-slate-300 relative z-10">
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 p-2 hover:bg-slate-800/50 rounded transition-colors cursor-default">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span class="text-blue-400 font-bold">orion-admin/</span> 
                                <span class="text-slate-500 border-l border-slate-700 pl-3 ml-auto">Dashboard admin & logika backend</span>
                            </li>
                            <li class="flex items-center gap-3 p-2 hover:bg-slate-800/50 rounded transition-colors cursor-default">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span class="text-blue-400 font-bold">orion-content/</span> 
                                <span class="text-slate-500 border-l border-slate-700 pl-3 ml-auto">Folder konten user</span>
                            </li>
                            <div class="pl-8 space-y-2 border-l border-slate-800 ml-4">
                                <li class="flex items-center gap-3">
                                    <span class="text-yellow-400">themes/</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="text-yellow-400">plugins/</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="text-yellow-400">uploads/</span>
                                </li>
                            </div>
                            <li class="flex items-center gap-3 p-2 hover:bg-slate-800/50 rounded transition-colors cursor-default">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span class="text-blue-400 font-bold">orion-includes/</span> 
                                <span class="text-slate-500 border-l border-slate-700 pl-3 ml-auto">Core system library</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Themes & Plugins -->
                <div id="tema-plugin" class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 scroll-mt-32 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900">Ekosistem Tema & Plugin</h2>
                    </div>
                    <p class="text-slate-700 mb-8 text-lg">
                        Orion CMS memisahkan logika presentasi (Themes) dan fungsionalitas (Plugins) untuk menjaga situs Anda tetap terorganisir.
                    </p>
                    
                    <div class="grid sm:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:-translate-y-1 transition-transform duration-300">
                            <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">🛍️</div>
                            <h4 class="font-bold text-slate-800">Orion Shop</h4>
                            <p class="text-xs text-slate-500 mt-2">Solusi E-commerce lengkap</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:-translate-y-1 transition-transform duration-300">
                            <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">📰</div>
                            <h4 class="font-bold text-slate-800">Orion Magazine</h4>
                            <p class="text-xs text-slate-500 mt-2">Portal berita & majalah</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:-translate-y-1 transition-transform duration-300">
                            <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">🎨</div>
                            <h4 class="font-bold text-slate-800">Orion Portfolio</h4>
                            <p class="text-xs text-slate-500 mt-2">Showcase karya kreatif</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>