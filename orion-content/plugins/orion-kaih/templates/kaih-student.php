<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAih - Aplikasi Anak Hebat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f9ff; padding-bottom: 72px; }
        .card-selesai { background-color: #dcfce7 !important; border-color: #22c55e !important; opacity: 0.95; cursor: default; }
        .card-selesai .ikon-centang { display: block !important; }
        .card-selesai .teks-poin { display: none !important; }
        .misi-card { display: flex; align-items: center; width: 100%; background-color: #ffffff; padding: 1rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s; border: 2px solid #e5e7eb; cursor: pointer; }
        .misi-card:hover:not(.card-selesai) { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1); }
        .ikon-misi { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; flex-shrink: 0; margin-right: 1rem; }
        .nav-bar { position: fixed; bottom: 0; left: 0; right: 0; background-color: #ffffff; box-shadow: 0 -4px 6px -1px rgba(0,0,0,0.1); display: flex; justify-content: space-around; padding: 0.5rem 0; z-index: 50; }
        .nav-bar button { display: flex; flex-direction: column; align-items: center; padding: 0.5rem; color: #9ca3af; transition: color 0.2s; font-size: 0.75rem; font-weight: 600; }
        .nav-bar button i { font-size: 1.5rem; margin-bottom: 0.25rem; }
        .nav-bar button.active { color: #3b82f6; }
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal-content { background: white; padding: 1.5rem; border-radius: 1.5rem; width: 90%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; }
        #loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.9); display: flex; align-items: center; justify-content: center; z-index: 9999; flex-direction: column; }
    </style>
    <script>
        window.KAIH_API_BASE = <?php echo json_encode(function_exists('orion_kaih_root_url') ? orion_kaih_root_url('/?orion_kaih_api=1') : site_url('/?orion_kaih_api=1'), JSON_UNESCAPED_SLASHES); ?>;
    </script>
</head>
<body class="min-h-screen">
    <div id="loading-overlay">
        <i class="ph-fill ph-spinner text-5xl text-blue-500 animate-spin"></i>
        <p class="text-lg font-semibold text-gray-700 mt-3">Memuat data...</p>
    </div>

    <div class="max-w-md mx-auto p-4 relative">
        <header class="mb-6 pt-2">
            <h1 id="header-salam" class="text-2xl font-extrabold text-gray-800 mb-1">Hai, ...! 👋</h1>
            <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-b-4 border-yellow-500">
                <span class="text-sm font-semibold text-gray-600">Total Bintang Anda:</span>
                <span class="text-3xl font-extrabold text-yellow-600 flex items-center">
                    <i class="ph-fill ph-star mr-1"></i>
                    <span id="total-bintang">0</span>
                </span>
            </div>
            <div id="progress-container" class="mt-4">
                <p id="progres-teks" class="text-sm font-medium text-gray-700 mb-1">0/7 Misi</p>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="progres-bar" class="h-3 rounded-full bg-blue-500 transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </header>

        <div id="page-misi" class="page space-y-3">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Misi Harian Anda:</h2>
            <div id="misi-list-container" class="space-y-3"></div>
            <button id="reset-button" class="w-full mt-6 py-3 bg-red-500 text-white font-bold rounded-xl shadow-md hover:bg-red-600 transition-colors">
                <i class="ph-fill ph-arrow-clockwise mr-2"></i> Mulai Hari Baru
            </button>
        </div>

        <div id="page-toko" class="page hidden">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Toko Virtual (Aksesoris & Latar Belakang)</h2>
            <div id="shop-items-container" class="grid grid-cols-2 gap-4 mb-8">
                <div id="item-1" data-cost="50" class="flex flex-col items-center bg-white p-4 rounded-xl shadow-lg border-2 border-yellow-300">
                    <span class="text-4xl mb-2">⭐</span>
                    <p class="font-semibold text-center mb-1">Stiker Bintang Biru</p>
                    <p class="text-xs text-gray-500 mb-3">Aksesori</p>
                    <button class="btn-beli w-full py-2 bg-yellow-400 text-white font-bold rounded-lg hover:bg-yellow-500 transition-colors" data-item="item-1">
                        50 <i class="ph-fill ph-star text-sm"></i>
                    </button>
                </div>
                <div id="item-2" data-cost="150" class="flex flex-col items-center bg-white p-4 rounded-xl shadow-lg border-2 border-indigo-300">
                    <span class="text-4xl mb-2">🌌</span>
                    <p class="font-semibold text-center mb-1">Latar Belakang Galaksi</p>
                    <p class="text-xs text-gray-500 mb-3">Latar Belakang</p>
                    <button class="btn-beli w-full py-2 bg-yellow-400 text-white font-bold rounded-lg hover:bg-yellow-500 transition-colors" data-item="item-2">
                        150 <i class="ph-fill ph-star text-sm"></i>
                    </button>
                </div>
                <div id="item-3" data-cost="200" class="flex flex-col items-center bg-white p-4 rounded-xl shadow-lg border-2 border-purple-300">
                    <span class="text-4xl mb-2">🧙</span>
                    <p class="font-semibold text-center mb-1">Topi Penyihir</p>
                    <p class="text-xs text-gray-500 mb-3">Aksesori</p>
                    <button class="btn-beli w-full py-2 bg-yellow-400 text-white font-bold rounded-lg hover:bg-yellow-500 transition-colors" data-item="item-3">
                        200 <i class="ph-fill ph-star text-sm"></i>
                    </button>
                </div>
                <div id="item-4" data-cost="300" class="flex flex-col items-center bg-white p-4 rounded-xl shadow-lg border-2 border-green-300">
                    <span class="text-4xl mb-2">🌿</span>
                    <p class="font-semibold text-center mb-1">Latar Hijau Daun</p>
                    <p class="text-xs text-gray-500 mb-3">Latar Belakang</p>
                    <button class="btn-beli w-full py-2 bg-yellow-400 text-white font-bold rounded-lg hover:bg-yellow-500 transition-colors" data-item="item-4">
                        300 <i class="ph-fill ph-star text-sm"></i>
                    </button>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Tukar Hadiah Fisik</h2>
            <div id="physical-reward-list" class="space-y-3"></div>
        </div>

        <div id="page-profil" class="page hidden">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Profil Anak</h2>
            <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center mb-6">
                <div class="relative avatar-container w-28 h-28 flex items-center justify-center rounded-full border-4 border-orange-500 bg-yellow-200 transition-all duration-300">
                    <span class="avatar-body text-6xl">🧑</span>
                    <span class="avatar-overlay absolute text-4xl top-0 right-0"></span>
                </div>
                <div class="mt-4 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <h3 id="nama-anak-profil" class="text-2xl font-extrabold text-gray-800">...</h3>
                        <button id="btn-edit-nama" class="text-blue-500 hover:text-blue-600"><i class="ph-light ph-pencil-simple text-xl"></i></button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Total Bintang: <span id="total-bintang-profil" class="font-bold text-yellow-600">0</span></p>
                </div>
            </div>

            <div class="bg-blue-50 border-2 border-blue-200 p-4 rounded-xl shadow-md mb-6">
                <p class="text-center font-semibold text-blue-700">ID Siswa Anda (untuk Guru):</p>
                <p id="student-user-id" class="text-lg font-bold text-blue-900 text-center break-words mt-1">Memuat ID...</p>
                <p class="text-xs text-center text-gray-500 mt-2">Berikan ID ini kepada guru Anda agar mereka dapat melihat progres misi Anda.</p>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-3">Inventori (Ketuk untuk Menggunakan)</h3>
            <div id="inventory-list" class="grid grid-cols-3 gap-4"></div>
            <p id="empty-inventory-message" class="text-sm text-center text-gray-500 mt-4 hidden">Inventori masih kosong. Yuk, belanja!</p>

            <button data-page="ortu" class="w-full mt-6 py-3 bg-blue-500 text-white font-bold rounded-xl shadow-md hover:bg-blue-600 transition-colors">
                <i class="ph-fill ph-lock-key-open mr-2"></i> Masuk Dashboard Orang Tua
            </button>
        </div>

        <div id="page-ortu" class="page hidden">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Dashboard Orang Tua</h2>
            <div id="ortu-pin-lock" class="flex flex-col items-center justify-center h-64 bg-white rounded-xl shadow-lg p-6">
                <i class="ph-fill ph-lock text-5xl text-red-500 mb-4"></i>
                <p class="text-lg font-semibold mb-3">Masukkan PIN Orang Tua</p>
                <form id="pin-form" class="w-full max-w-xs">
                    <input type="password" id="pin-input" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center text-xl tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-blue-500" maxlength="4" required>
                    <p id="pin-message" class="text-xs mt-2 h-4"></p>
                    <button type="submit" class="w-full mt-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition-colors">Masuk</button>
                </form>
            </div>
            <div id="ortu-dashboard" class="hidden space-y-4">
                <div class="bg-blue-50 p-4 rounded-xl shadow-md">
                    <p class="text-sm font-semibold text-blue-800">Ringkasan untuk: <span id="ortu-nama-anak" class="font-extrabold">...</span></p>
                    <p class="text-sm text-gray-600">Total Bintang Anak: <span class="font-bold text-yellow-600" id="ortu-total-bintang-display"></span></p>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-3 pt-2">Pengaturan Hadiah & Misi</h3>
                <button id="atur-hadiah-btn" class="w-full py-3 bg-indigo-500 text-white font-bold rounded-xl shadow-md hover:bg-indigo-600 transition-colors">
                    <i class="ph-fill ph-gift mr-2"></i> Atur Hadiah Fisik
                </button>
                <button id="edit-misi-btn" class="w-full py-3 bg-green-500 text-white font-bold rounded-xl shadow-md hover:bg-green-600 transition-colors">
                    <i class="ph-fill ph-list-checks mr-2"></i> Edit Misi Harian
                </button>
                <button id="logout-button" class="w-full mt-6 py-2 bg-red-400 text-white font-semibold rounded-lg hover:bg-red-500 transition-colors">
                    <i class="ph-fill ph-sign-out mr-2"></i> Keluar Dashboard
                </button>
            </div>
        </div>
    </div>

    <div class="nav-bar">
        <button data-page="misi" class="active">
            <i class="ph-fill ph-clipboard-text"></i>
            <span>Misi</span>
        </button>
        <button data-page="toko">
            <i class="ph-light ph-storefront"></i>
            <span>Toko</span>
        </button>
        <button data-page="profil">
            <i class="ph-light ph-user-circle"></i>
            <span>Profil</span>
        </button>
        <button data-page="ortu">
            <i class="ph-light ph-lock-key"></i>
            <span>Ortu</span>
        </button>
    </div>

    <div id="modal-edit-nama" class="modal-overlay hidden">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Nama Anak</h3>
            <input type="text" id="input-nama-baru" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama baru">
            <div class="flex justify-end space-x-3">
                <button id="tutup-nama-btn" class="px-4 py-2 text-gray-500 font-semibold rounded-lg hover:text-red-500">Batal</button>
                <button id="simpan-nama-btn" class="px-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600">Simpan</button>
            </div>
        </div>
    </div>

    <div id="modal-hadiah-fisik" class="modal-overlay hidden">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-indigo-600 mb-4 text-center">Atur Hadiah Fisik</h3>
            <h4 class="font-semibold text-gray-700 mb-2">Daftar Hadiah:</h4>
            <div id="edit-hadiah-container" class="space-y-2 max-h-48 overflow-y-auto p-2 border rounded-lg bg-gray-50 mb-4"></div>
            <h4 class="font-semibold text-gray-700 mb-2">Tambah Hadiah Baru:</h4>
            <form id="form-tambah-hadiah" class="space-y-2">
                <input type="text" id="input-hadiah-nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Nama Hadiah (cth: Pergi ke kebun binatang)" required>
                <input type="number" id="input-hadiah-poin" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Poin yang dibutuhkan (cth: 500)" min="10" required>
                <button type="submit" class="w-full py-2 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition-colors">Tambah Hadiah</button>
            </form>
            <button id="tutup-hadiah-btn" class="w-full mt-4 py-2 text-gray-500 font-semibold rounded-lg hover:text-red-500">Selesai</button>
        </div>
    </div>

    <div id="modal-unggah-bukti" class="modal-overlay hidden">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-blue-600 mb-4 text-center">Unggah Bukti Misi</h3>
            <p id="bukti-misi-nama" class="text-lg font-semibold text-gray-800 mb-3 text-center"></p>
            <form id="form-unggah-bukti" class="space-y-4">
                <div class="border-2 border-dashed border-blue-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                    <input type="file" id="input-bukti-file" class="hidden" accept="image/*,video/*">
                    <label for="input-bukti-file" id="label-bukti-file" class="block cursor-pointer">
                        <i class="ph-light ph-image text-4xl text-blue-500 mx-auto block mb-2"></i>
                        <p class="text-sm font-medium text-blue-600">Pilih Foto atau Video</p>
                        <p class="text-xs text-gray-500 mt-1">Maks. 5MB</p>
                        <p id="file-name-display" class="text-xs font-semibold text-gray-700 mt-2">Belum ada file dipilih</p>
                        <p class="text-xs text-red-500 mt-1" id="file-error-message" style="display: none;">Ukuran file terlalu besar! (Maks 5MB)</p>
                    </label>
                </div>
                <input type="hidden" id="misi-id-terpilih">
                <button type="submit" id="btn-submit-bukti" class="w-full bg-green-500 text-white font-bold py-3 rounded-xl transition-colors hover:bg-green-600 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    Selesaikan Misi (Kirim Laporan)
                </button>
                <button type="button" id="tutup-bukti-btn" class="w-full text-gray-500 font-semibold py-1 rounded-xl hover:text-red-500 transition-colors">
                    Batal
                </button>
            </form>
        </div>
    </div>

    <div id="modal-edit-misi" class="modal-overlay hidden">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-green-600 mb-4 text-center">Edit Misi Harian</h3>
            <p class="text-sm text-gray-500 mb-3">Atur nama dan poin untuk setiap misi.</p>
            <h4 class="font-semibold text-gray-700 mb-2">Daftar Misi Aktif:</h4>
            <div id="edit-misi-container" class="space-y-2 max-h-48 overflow-y-auto p-2 border rounded-lg bg-gray-50 mb-4"></div>
            <h4 class="font-semibold text-gray-700 mt-4 mb-2 border-t pt-4">Tambah Misi Baru:</h4>
            <form id="form-tambah-misi" class="space-y-2">
                <input type="text" id="input-misi-nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Nama Misi Baru" required>
                <input type="text" id="input-misi-keterangan" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Keterangan" required>
                <input type="number" id="input-misi-poin" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Poin" min="1" required>
                <button type="submit" id="btn-tambah-misi-ortu" class="w-full py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="ph-fill ph-plus mr-2"></i> Tambah Misi
                </button>
            </form>
            <button id="tutup-misi-btn" class="w-full mt-4 py-2 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600">
                <i class="ph-fill ph-floppy-disk mr-2"></i> Simpan & Selesai
            </button>
        </div>
    </div>

    <script>
        let totalBintang = 0;
        let namaAnak = 'Anak Hebat';
        let inventory = {};
        let currentMissions = {};
        let physicalRewards = [];
        let isParentAuthenticated = false;
        let isSaving = false;
        let debounceTimer;
        let lastSavedAt = 0;
        let studentId = '';

        const defaultMissions = {
            'misi-1': { id: 'misi-1', nama: 'Bangun Pagi', keterangan: 'Bangun sebelum jam 06:00', poin: 10, pending: false, approved: false, ikonClass: 'ph-fill ph-sun', ikonBg: 'bg-yellow-100 text-yellow-600'},
            'misi-2': { id: 'misi-2', nama: 'Beribadah', keterangan: 'Berdoa atau sholat tepat waktu', poin: 15, pending: false, approved: false, ikonClass: 'ph-fill ph-hands-praying', ikonBg: 'bg-sky-100 text-sky-600'},
            'misi-3': { id: 'misi-3', nama: 'Berolahraga', keterangan: 'Gerak badan minimal 15 menit', poin: 10, pending: false, approved: false, ikonClass: 'ph-fill ph-basketball', ikonBg: 'bg-orange-100 text-orange-600'},
            'misi-4': { id: 'misi-4', nama: 'Makan Sehat', keterangan: 'Makan sayur dan buah', poin: 10, pending: false, approved: false, ikonClass: 'ph-fill ph-carrot', ikonBg: 'bg-green-100 text-green-600'},
            'misi-5': { id: 'misi-5', nama: 'Gemar Belajar', keterangan: 'Membaca buku atau belajar 30 menit', poin: 15, pending: false, approved: false, ikonClass: 'ph-fill ph-book-open', ikonBg: 'bg-indigo-100 text-indigo-600'},
            'misi-6': { id: 'misi-6', nama: 'Bermasyarakat', keterangan: 'Membantu orang tua di rumah', poin: 10, pending: false, approved: false, ikonClass: 'ph-fill ph-heart', ikonBg: 'bg-pink-100 text-pink-600'},
            'misi-7': { id: 'misi-7', nama: 'Tidur Cepat', keterangan: 'Tidur sebelum jam 21:00', poin: 10, pending: false, approved: false, ikonClass: 'ph-fill ph-moon', ikonBg: 'bg-purple-100 text-purple-600'}
        };

        const shopItems = {
            'item-1': { nama: 'Stiker Bintang Biru', emoji: '⭐', type: 'accessory', overlay: '⭐' },
            'item-2': { nama: 'Latar Belakang Galaksi', emoji: '🌌', type: 'background', bg: 'from-indigo-900 to-black' },
            'item-3': { nama: 'Topi Penyihir', emoji: '🧙', type: 'accessory', overlay: '🧙' },
            'item-4': { nama: 'Latar Hijau Daun', emoji: '🌿', type: 'background', bg: 'from-green-700 to-emerald-900' },
        };

        const loadingOverlay = document.getElementById('loading-overlay');
        const headerSalamEl = document.getElementById('header-salam');
        const totalBintangEl = document.getElementById('total-bintang');
        const progresTeksEl = document.getElementById('progres-teks');
        const progresBarEl = document.getElementById('progres-bar');
        const progressContainerEl = document.getElementById('progress-container');
        const misiListContainerEl = document.getElementById('misi-list-container');
        const resetBtnEl = document.getElementById('reset-button');
        const inventoryListEl = document.getElementById('inventory-list');
        const emptyInventoryMessageEl = document.getElementById('empty-inventory-message');
        const physicalRewardListEl = document.getElementById('physical-reward-list');
        const namaAnakEl = document.getElementById('nama-anak-profil');
        const totalBintangProfilEl = document.getElementById('total-bintang-profil');
        const ortuNamaAnakEl = document.getElementById('ortu-nama-anak');
        const ortuTotalBintangDisplayEl = document.getElementById('ortu-total-bintang-display');
        const studentUserIdEl = document.getElementById('student-user-id');

        const modalEditNamaEl = document.getElementById('modal-edit-nama');
        const inputNamaBaruEl = document.getElementById('input-nama-baru');
        const tutupNamaBtn = document.getElementById('tutup-nama-btn');
        const simpanNamaBtn = document.getElementById('simpan-nama-btn');
        const btnEditNama = document.getElementById('btn-edit-nama');

        const modalHadiahFisikEl = document.getElementById('modal-hadiah-fisik');
        const editHadiahContainerEl = document.getElementById('edit-hadiah-container');
        const formTambahHadiahEl = document.getElementById('form-tambah-hadiah');
        const inputHadiahNamaEl = document.getElementById('input-hadiah-nama');
        const inputHadiahPoinEl = document.getElementById('input-hadiah-poin');
        const aturHadiahBtn = document.getElementById('atur-hadiah-btn');
        const tutupHadiahBtn = document.getElementById('tutup-hadiah-btn');

        const modalEditMisiEl = document.getElementById('modal-edit-misi');
        const editMisiBtn = document.getElementById('edit-misi-btn');
        const tutupMisiBtn = document.getElementById('tutup-misi-btn');
        const editMisiContainerEl = document.getElementById('edit-misi-container');
        const formTambahMisiEl = document.getElementById('form-tambah-misi');
        const inputMisiNamaEl = document.getElementById('input-misi-nama');
        const inputMisiKeteranganEl = document.getElementById('input-misi-keterangan');
        const inputMisiPoinEl = document.getElementById('input-misi-poin');

        const modalUnggahBuktiEl = document.getElementById('modal-unggah-bukti');
        const tutupBuktiBtn = document.getElementById('tutup-bukti-btn');
        const formUnggahBuktiEl = document.getElementById('form-unggah-bukti');
        const inputBuktiFileEl = document.getElementById('input-bukti-file');
        const fileNameDisplayEl = document.getElementById('file-name-display');
        const btnSubmitBuktiEl = document.getElementById('btn-submit-bukti');
        const misiIdTerpilihEl = document.getElementById('misi-id-terpilih');
        const buktiMisiNamaEl = document.getElementById('bukti-misi-nama');
        const fileErrorMessageEl = document.getElementById('file-error-message');

        const pages = {
            'misi': document.getElementById('page-misi'),
            'toko': document.getElementById('page-toko'),
            'profil': document.getElementById('page-profil'),
            'ortu': document.getElementById('page-ortu')
        };

        function showModal(modalElement) { modalElement.classList.remove('hidden'); }
        function hideModal(modalElement) { modalElement.classList.add('hidden'); }

        function ensureStudentId() {
            const existing = localStorage.getItem('kaih_student_id');
            if (existing && /^[A-Za-z0-9_-]{6,64}$/.test(existing)) {
                return existing;
            }
            const id = (crypto && crypto.randomUUID) ? crypto.randomUUID().replace(/-/g, '') : (Date.now().toString(36) + Math.random().toString(36).slice(2));
            const clean = id.replace(/[^A-Za-z0-9_-]/g, '').slice(0, 64);
            localStorage.setItem('kaih_student_id', clean);
            return clean;
        }

        async function apiGetState() {
            const url = `${window.KAIH_API_BASE}&action=state&student_id=${encodeURIComponent(studentId)}`;
            const res = await fetch(url, { method: 'GET' });
            const json = await res.json();
            if (!json || !json.ok) throw new Error(json && json.error ? json.error : 'load_failed');
            return json.state;
        }

        async function apiSaveState(state) {
            const url = `${window.KAIH_API_BASE}&action=state&student_id=${encodeURIComponent(studentId)}`;
            const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ state }) });
            const json = await res.json();
            if (!json || !json.ok) throw new Error(json && json.error ? json.error : 'save_failed');
            return json.state;
        }

        async function apiUploadProof(file, missionId) {
            const url = `${window.KAIH_API_BASE}&action=upload&student_id=${encodeURIComponent(studentId)}`;
            const fd = new FormData();
            fd.append('student_id', studentId);
            fd.append('mission_id', missionId);
            fd.append('file', file);
            const res = await fetch(url, { method: 'POST', body: fd });
            const json = await res.json();
            if (!json || !json.ok) throw new Error(json && json.error ? json.error : 'upload_failed');
            return json.url;
        }

        function saveStateDebounced() {
            clearTimeout(debounceTimer);
            if (isSaving) return;
            debounceTimer = setTimeout(async () => {
                isSaving = true;
                try {
                    const state = { totalBintang, namaAnak, inventory, currentMissions, physicalRewards, updatedAt: lastSavedAt };
                    const saved = await apiSaveState(state);
                    lastSavedAt = saved.updatedAt || lastSavedAt;
                } catch (e) {
                } finally {
                    isSaving = false;
                }
            }, 900);
        }

        function normalizeMission(m) {
            if (!m) return m;
            if (typeof m.pending !== 'boolean') m.pending = !!m.completed;
            if (typeof m.approved !== 'boolean') m.approved = false;
            return m;
        }

        function renderMisiCards() {
            misiListContainerEl.innerHTML = '';
            const missionKeys = Object.keys(currentMissions || {});
            const totalMisi = missionKeys.length;
            let doneCount = 0;

            missionKeys.forEach((key) => {
                const misi = normalizeMission(currentMissions[key]);
                const isPending = !!misi.pending;
                const isApproved = !!misi.approved;
                const card = document.createElement('div');
                card.id = misi.id;
                card.className = `misi-card ${isApproved ? 'card-selesai' : ''}`;
                card.setAttribute('data-nama', misi.nama);
                const ikonClass = misi.ikonClass || 'ph-fill ph-book';
                const ikonBg = misi.ikonBg || 'bg-gray-100 text-gray-600';
                card.innerHTML = `
                    <div class="ikon-misi ${ikonBg}"><i class="${ikonClass}"></i></div>
                    <div class="info-misi flex-grow">
                        <h3 class="font-semibold text-gray-800">${misi.nama}</h3>
                        <p class="text-xs text-gray-500">${misi.keterangan || ''}</p>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <span class="teks-poin text-base font-bold text-yellow-500 ${(isPending || isApproved) ? 'hidden' : ''}">+${misi.poin}</span>
                        <i class="ikon-centang ph-fill ph-check-circle text-2xl text-green-500 ${isApproved ? '' : 'hidden'}"></i>
                    </div>
                `;
                misiListContainerEl.appendChild(card);

                if (isApproved) doneCount++;

                card.addEventListener('click', (e) => {
                    const mission = normalizeMission(currentMissions[misi.id]);
                    if (mission.approved) {
                        alert(`Misi "${mission.nama}" sudah disetujui.`);
                        return;
                    }
                    if (mission.pending) {
                        alert(`Misi "${mission.nama}" sedang menunggu persetujuan Guru.`);
                        return;
                    }
                    misiIdTerpilihEl.value = misi.id;
                    buktiMisiNamaEl.textContent = misi.nama;
                    inputBuktiFileEl.value = '';
                    fileNameDisplayEl.textContent = 'Belum ada file dipilih';
                    btnSubmitBuktiEl.disabled = true;
                    fileErrorMessageEl.style.display = 'none';
                    showModal(modalUnggahBuktiEl);
                });

                if (isPending && !isApproved) {
                    const statusOverlay = document.createElement('div');
                    statusOverlay.className = 'absolute inset-0 bg-white bg-opacity-90 rounded-xl flex items-center justify-center';
                    statusOverlay.innerHTML = `
                        <div class="text-center text-orange-600 font-bold">
                            <i class="ph-fill ph-hourglass-high text-4xl animate-pulse"></i>
                            <p class="text-sm mt-1">Menunggu Persetujuan...</p>
                        </div>
                    `;
                    card.classList.add('relative');
                    card.appendChild(statusOverlay);
                }
            });

            progresTeksEl.textContent = `${doneCount}/${totalMisi} Misi`;
            const pct = totalMisi > 0 ? (doneCount / totalMisi) * 100 : 0;
            progresBarEl.style.width = `${pct}%`;
        }

        function renderInventory() {
            inventoryListEl.innerHTML = '';
            const ids = Object.keys(inventory || {});
            if (!ids.length) {
                emptyInventoryMessageEl.classList.remove('hidden');
                return;
            }
            emptyInventoryMessageEl.classList.add('hidden');
            ids.forEach((id) => {
                const item = inventory[id];
                const el = document.createElement('button');
                el.type = 'button';
                el.className = `bg-white p-3 rounded-xl shadow border text-center ${item.used ? 'border-blue-500' : 'border-slate-200'}`;
                el.innerHTML = `<div class="text-3xl">${item.emoji || '🎁'}</div><div class="text-xs font-semibold mt-1">${item.nama || id}</div>`;
                el.addEventListener('click', () => {
                    Object.keys(inventory).forEach((k) => {
                        if (inventory[k] && inventory[k].type === item.type) inventory[k].used = false;
                    });
                    inventory[id].used = true;
                    saveStateDebounced();
                    updateTampilan();
                });
                inventoryListEl.appendChild(el);
            });
        }

        function renderHadiahFisikList() {
            physicalRewardListEl.innerHTML = '';
            if (!Array.isArray(physicalRewards) || !physicalRewards.length) {
                physicalRewardListEl.innerHTML = '<div class="text-sm text-gray-500 text-center bg-white p-4 rounded-xl shadow border">Belum ada hadiah fisik.</div>';
                return;
            }
            physicalRewards.forEach((reward, idx) => {
                const used = !!reward.used;
                const canRedeem = totalBintang >= (reward.poin || 0) && !used;
                const card = document.createElement('div');
                card.className = 'bg-white p-4 rounded-xl shadow border flex items-center justify-between gap-4';
                card.innerHTML = `
                    <div>
                        <div class="font-bold text-gray-800">${reward.nama || 'Hadiah'}</div>
                        <div class="text-xs text-gray-500">${reward.poin || 0} bintang</div>
                    </div>
                    <button type="button" class="px-3 py-2 rounded-lg text-xs font-bold ${canRedeem ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-500'}">
                        ${used ? 'Sudah Ditukar' : (canRedeem ? 'Tukar' : 'Kurang Bintang')}
                    </button>
                `;
                const btn = card.querySelector('button');
                btn.addEventListener('click', () => {
                    if (!canRedeem) return;
                    reward.used = true;
                    saveStateDebounced();
                    updateTampilan();
                    alert('Hadiah ditandai sebagai sudah ditukar.');
                });
                physicalRewardListEl.appendChild(card);
            });
        }

        function renderEditHadiahList() {
            editHadiahContainerEl.innerHTML = '';
            if (!Array.isArray(physicalRewards) || !physicalRewards.length) {
                editHadiahContainerEl.innerHTML = '<div class="text-xs text-gray-500">Belum ada hadiah.</div>';
                return;
            }
            physicalRewards.forEach((reward, idx) => {
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-2 bg-white p-2 rounded-lg border';
                row.innerHTML = `
                    <div class="text-sm font-semibold">${reward.nama || 'Hadiah'}</div>
                    <div class="text-xs text-gray-500">${reward.poin || 0}</div>
                    <button type="button" class="text-xs font-bold text-red-600">Hapus</button>
                `;
                row.querySelector('button').addEventListener('click', () => {
                    physicalRewards.splice(idx, 1);
                    saveStateDebounced();
                    updateTampilan();
                });
                editHadiahContainerEl.appendChild(row);
            });
        }

        function renderEditMisiList() {
            editMisiContainerEl.innerHTML = '';
            Object.keys(currentMissions || {}).forEach((id) => {
                const m = currentMissions[id];
                const row = document.createElement('div');
                row.className = 'bg-white p-2 rounded-lg border flex items-center justify-between gap-2';
                row.innerHTML = `
                    <div class="flex-1">
                        <div class="text-sm font-bold text-gray-800">${m.nama}</div>
                        <div class="text-xs text-gray-500">${m.keterangan || ''}</div>
                    </div>
                    <div class="text-xs font-bold text-yellow-600">+${m.poin}</div>
                `;
                row.addEventListener('click', () => {
                    const nama = prompt('Nama misi', m.nama);
                    if (nama === null) return;
                    const ket = prompt('Keterangan', m.keterangan || '');
                    if (ket === null) return;
                    const poin = parseInt(prompt('Poin', String(m.poin || 0)) || '0', 10);
                    m.nama = nama;
                    m.keterangan = ket;
                    m.poin = isNaN(poin) ? 0 : poin;
                    saveStateDebounced();
                    updateTampilan();
                });
                editMisiContainerEl.appendChild(row);
            });
        }

        function updateTampilan() {
            totalBintangEl.textContent = totalBintang;
            ortuTotalBintangDisplayEl.textContent = totalBintang;
            headerSalamEl.textContent = `Hai, ${namaAnak}! 👋`;
            namaAnakEl.textContent = namaAnak;
            totalBintangProfilEl.textContent = totalBintang;
            ortuNamaAnakEl.textContent = namaAnak;
            studentUserIdEl.textContent = studentId;
            renderMisiCards();
            renderHadiahFisikList();
            renderEditHadiahList();
            renderEditMisiList();
            renderInventory();
        }

        function showPage(pageId) {
            Object.keys(pages).forEach((id) => pages[id].classList.toggle('hidden', id !== pageId));
            document.querySelectorAll('.nav-bar button').forEach((b) => {
                b.classList.toggle('active', b.getAttribute('data-page') === pageId);
                const icon = b.querySelector('i');
                if (!icon) return;
                if (b.classList.contains('active')) { icon.classList.remove('ph-light'); icon.classList.add('ph-fill'); }
                else { icon.classList.remove('ph-fill'); icon.classList.add('ph-light'); }
            });
            progressContainerEl.style.display = pageId === 'misi' ? 'block' : 'none';
        }

        async function boot() {
            studentId = ensureStudentId();
            try {
                const state = await apiGetState();
                lastSavedAt = state.updatedAt || 0;
                totalBintang = state.totalBintang || 0;
                namaAnak = state.namaAnak || 'Anak Hebat';
                inventory = state.inventory || {};
                currentMissions = state.currentMissions && Object.keys(state.currentMissions).length ? state.currentMissions : JSON.parse(JSON.stringify(defaultMissions));
                physicalRewards = Array.isArray(state.physicalRewards) ? state.physicalRewards : [];
            } catch (e) {
                currentMissions = JSON.parse(JSON.stringify(defaultMissions));
            }
            updateTampilan();
            loadingOverlay.style.display = 'none';

            setInterval(async () => {
                try {
                    const remote = await apiGetState();
                    const remoteAt = remote.updatedAt || 0;
                    if (remoteAt > lastSavedAt && !isSaving) {
                        lastSavedAt = remoteAt;
                        totalBintang = remote.totalBintang || totalBintang;
                        namaAnak = remote.namaAnak || namaAnak;
                        inventory = remote.inventory || inventory;
                        currentMissions = remote.currentMissions || currentMissions;
                        physicalRewards = Array.isArray(remote.physicalRewards) ? remote.physicalRewards : physicalRewards;
                        updateTampilan();
                    }
                } catch (e) {}
            }, 2500);
        }

        document.addEventListener('DOMContentLoaded', () => {
            boot();

            document.querySelectorAll('.nav-bar button').forEach((btn) => {
                btn.addEventListener('click', () => showPage(btn.getAttribute('data-page')));
            });

            btnEditNama.addEventListener('click', () => {
                inputNamaBaruEl.value = namaAnak;
                showModal(modalEditNamaEl);
            });
            tutupNamaBtn.addEventListener('click', () => hideModal(modalEditNamaEl));
            simpanNamaBtn.addEventListener('click', () => {
                const v = inputNamaBaruEl.value.trim();
                if (v) {
                    namaAnak = v;
                    saveStateDebounced();
                    updateTampilan();
                }
                hideModal(modalEditNamaEl);
            });

            aturHadiahBtn.addEventListener('click', () => showModal(modalHadiahFisikEl));
            tutupHadiahBtn.addEventListener('click', () => hideModal(modalHadiahFisikEl));
            formTambahHadiahEl.addEventListener('submit', (e) => {
                e.preventDefault();
                const nama = inputHadiahNamaEl.value.trim();
                const poin = parseInt(inputHadiahPoinEl.value || '0', 10);
                if (!nama || isNaN(poin) || poin < 10) return;
                physicalRewards.push({ nama, poin, used: false });
                inputHadiahNamaEl.value = '';
                inputHadiahPoinEl.value = '';
                saveStateDebounced();
                updateTampilan();
            });

            editMisiBtn.addEventListener('click', () => showModal(modalEditMisiEl));
            tutupMisiBtn.addEventListener('click', () => hideModal(modalEditMisiEl));
            formTambahMisiEl.addEventListener('submit', (e) => {
                e.preventDefault();
                const nama = inputMisiNamaEl.value.trim();
                const ket = inputMisiKeteranganEl.value.trim();
                const poin = parseInt(inputMisiPoinEl.value || '0', 10);
                if (!nama || !ket || isNaN(poin) || poin < 1) return;
                const id = 'misi-' + (Date.now().toString(36));
                currentMissions[id] = { id, nama, keterangan: ket, poin, pending: false, approved: false, ikonClass: 'ph-fill ph-book', ikonBg: 'bg-gray-100 text-gray-600' };
                inputMisiNamaEl.value = '';
                inputMisiKeteranganEl.value = '';
                inputMisiPoinEl.value = '';
                saveStateDebounced();
                updateTampilan();
            });

            const MAX_FILE_SIZE = 5 * 1024 * 1024;
            inputBuktiFileEl.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    fileNameDisplayEl.textContent = file.name;
                    if (file.size > MAX_FILE_SIZE) {
                        fileErrorMessageEl.style.display = 'block';
                        btnSubmitBuktiEl.disabled = true;
                        inputBuktiFileEl.value = '';
                    } else {
                        fileErrorMessageEl.style.display = 'none';
                        btnSubmitBuktiEl.disabled = false;
                    }
                } else {
                    fileNameDisplayEl.textContent = 'Belum ada file dipilih';
                    fileErrorMessageEl.style.display = 'none';
                    btnSubmitBuktiEl.disabled = true;
                }
            });
            tutupBuktiBtn.addEventListener('click', () => hideModal(modalUnggahBuktiEl));
            formUnggahBuktiEl.addEventListener('submit', async (e) => {
                e.preventDefault();
                const misiId = misiIdTerpilihEl.value;
                const file = inputBuktiFileEl.files[0];
                if (!misiId || !file) return;
                if (file.size > MAX_FILE_SIZE) return;
                const mission = normalizeMission(currentMissions[misiId]);
                if (!mission || mission.pending || mission.approved) return;
                btnSubmitBuktiEl.disabled = true;
                try {
                    const url = await apiUploadProof(file, misiId);
                    mission.pending = true;
                    mission.approved = false;
                    mission.proofUrl = url;
                    mission.submittedAt = new Date().toISOString();
                    saveStateDebounced();
                    updateTampilan();
                    hideModal(modalUnggahBuktiEl);
                    alert(`Laporan misi "${mission.nama}" berhasil dikirim! Menunggu persetujuan Guru/Orang Tua.`);
                } catch (err) {
                    btnSubmitBuktiEl.disabled = false;
                    alert('Gagal upload bukti. Coba lagi.');
                }
            });

            resetBtnEl.addEventListener('click', () => {
                alert('Memulai hari baru: misi akan direset (bintang tidak direset).');
                Object.keys(currentMissions).forEach((id) => {
                    currentMissions[id].pending = false;
                    currentMissions[id].approved = false;
                    currentMissions[id].completed = false;
                    delete currentMissions[id].proofUrl;
                    delete currentMissions[id].submittedAt;
                    delete currentMissions[id].approvedAt;
                });
                physicalRewards.forEach((r) => r.used = false);
                saveStateDebounced();
                updateTampilan();
            });

            document.querySelectorAll('.btn-beli').forEach((tombol) => {
                tombol.addEventListener('click', (e) => {
                    const itemId = e.currentTarget.getAttribute('data-item');
                    const itemCard = document.getElementById(itemId);
                    const cost = parseInt(itemCard.getAttribute('data-cost'), 10);
                    if (inventory[itemId]) { alert('Item ini sudah Anda miliki.'); return; }
                    if (totalBintang < cost) { alert('Bintang Anda tidak cukup!'); return; }
                    alert(`Membeli ${shopItems[itemId].nama} (${cost} bintang).`);
                    totalBintang -= cost;
                    Object.keys(inventory).forEach((id) => {
                        if (shopItems[id]?.type === shopItems[itemId].type) inventory[id].used = false;
                    });
                    inventory[itemId] = { ...shopItems[itemId], used: true };
                    saveStateDebounced();
                    updateTampilan();
                });
            });

            document.getElementById('pin-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                const v = document.getElementById('pin-input').value;
                const msg = document.getElementById('pin-message');
                msg.textContent = '';
                msg.className = 'text-xs mt-2 h-4 text-slate-500';
                try {
                    const url = `${window.KAIH_API_BASE}&action=parent_pin_verify&student_id=${encodeURIComponent(studentId)}`;
                    const fd = new FormData();
                    fd.append('student_id', studentId);
                    fd.append('pin', v);
                    const res = await fetch(url, { method: 'POST', body: fd });
                    const json = await res.json();
                    if (json && json.ok) {
                        isParentAuthenticated = true;
                        msg.textContent = '';
                        document.getElementById('ortu-pin-lock').classList.add('hidden');
                        document.getElementById('ortu-dashboard').classList.remove('hidden');
                        return;
                    }
                    if (json && json.error === 'pin_not_set') {
                        msg.textContent = 'PIN belum diset oleh admin.';
                    } else {
                        msg.textContent = 'PIN salah.';
                    }
                    msg.className = 'text-xs mt-2 h-4 text-red-600';
                } catch (err) {
                    msg.textContent = 'Gagal verifikasi PIN. Coba lagi.';
                    msg.className = 'text-xs mt-2 h-4 text-red-600';
                }
            });
            document.getElementById('logout-button').addEventListener('click', () => {
                isParentAuthenticated = false;
                document.getElementById('pin-input').value = '';
                document.getElementById('ortu-pin-lock').classList.remove('hidden');
                document.getElementById('ortu-dashboard').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
