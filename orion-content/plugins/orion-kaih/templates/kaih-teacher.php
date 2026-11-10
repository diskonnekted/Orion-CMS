<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAih - Dashboard Guru/Orang Tua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f9ff; padding-bottom: 20px; }
        .loading-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(255,255,255,0.9); display:flex; justify-content:center; align-items:center; z-index:1000; font-size:1.5rem; font-weight:600; color:#1d4ed8; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .spinner { border: 4px solid rgba(0,0,0,0.1); border-top: 4px solid #1d4ed8; border-radius:50%; width:40px; height:40px; animation: spin 1s linear infinite; }
        .request-card { transition: all 0.2s ease-in-out; border-left: 5px solid #f59e0b; }
        .request-card:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.1); transform: translateY(-2px); }
    </style>
    <script>
        window.KAIH_API_BASE = <?php echo json_encode(function_exists('orion_kaih_root_url') ? orion_kaih_root_url('/?orion_kaih_api=1') : site_url('/?orion_kaih_api=1'), JSON_UNESCAPED_SLASHES); ?>;
    </script>
</head>
<body>
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="flex flex-col items-center">
            <div class="spinner mb-4"></div>
            Menghubungkan...
        </div>
    </div>

    <div class="max-w-md mx-auto p-4">
        <header class="bg-indigo-600 text-white rounded-xl shadow-lg p-5 mb-6">
            <h1 class="text-3xl font-extrabold">Dashboard Guru/Orang Tua</h1>
            <p class="text-sm mt-1 opacity-90">Mode: Sinkronisasi (Polling)</p>
            <p id="user-id-display" class="text-xs mt-1 opacity-70 break-all">ID Admin: loading...</p>
        </header>

        <div id="student-id-input-card" class="bg-yellow-50 p-4 rounded-xl shadow-md mb-6 border-l-4 border-yellow-500">
            <p class="font-bold text-yellow-800 mb-2">Pilih Siswa yang Diawasi</p>
            <p class="text-sm text-gray-700 mb-3">Masukkan ID Siswa dari aplikasi siswa untuk memuat data.</p>
            <div id="assigned-students-wrap" class="mb-3 hidden">
                <p class="text-xs font-bold text-gray-600 mb-1">Siswa Terdaftar (Anda)</p>
                <select id="assigned-students" class="w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih siswa...</option>
                </select>
            </div>
            <input id="target-student-id" type="text" placeholder="Masukkan ID Siswa target di sini" class="w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-2">
            <button id="btn-load-student" class="w-full py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-colors">
                <i class="ph-fill ph-check-circle mr-1"></i> Muat Data Siswa
            </button>
            <p id="current-student-status" class="text-sm mt-2 font-bold text-gray-600 text-center"></p>
        </div>

        <div id="student-summary-card" class="bg-white rounded-xl shadow-lg p-4 mb-6 hidden">
            <h2 class="text-xl font-extrabold text-gray-800 mb-3">Ringkasan Siswa</h2>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-600">Nama Siswa:</p>
                    <p id="student-name-display" class="text-2xl font-bold text-indigo-700">...</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-600">Total Poin:</p>
                    <p id="student-poin-display" class="text-4xl font-extrabold text-yellow-600">0</p>
                </div>
            </div>
        </div>

        <div id="misi-page" class="space-y-6 hidden">
            <h2 class="text-xl font-bold mb-4 text-orange-700">1. Permintaan Persetujuan Misi</h2>
            <div id="pending-requests-list" class="space-y-4">
                <p id="requests-empty-state" class="text-center text-gray-500 bg-white p-4 rounded-xl shadow-md">
                    Tidak ada permintaan persetujuan baru saat ini.
                </p>
            </div>

            <h2 class="text-xl font-bold mb-4 text-blue-700 pt-4 border-t">2. Kelola Misi Harian Siswa</h2>
            <div class="bg-blue-100 p-4 rounded-xl shadow-md border-l-4 border-blue-600">
                <h3 class="text-lg font-bold text-blue-800 mb-3">Tambah Misi Baru</h3>
                <input id="input-misi-nama" type="text" placeholder="Nama Misi" class="w-full p-2 mb-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input id="input-misi-keterangan" type="text" placeholder="Keterangan" class="w-full p-2 mb-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input id="input-misi-poin" type="number" min="1" placeholder="Poin (min 1)" class="w-full p-2 mb-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <button id="btn-tambah-misi" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    <i class="ph-plus-circle ph-fill mr-1"></i> Tambahkan Misi Baru
                </button>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Daftar Misi Aktif:</h3>
                <div id="edit-misi-list" class="space-y-3">
                    <p class="text-center text-gray-500">Misi akan dimuat setelah siswa dipilih.</p>
                </div>
                <p class="text-xs text-gray-500 mt-4">Klik misi untuk edit/hapus.</p>
            </div>

            <h2 class="text-xl font-bold mb-4 text-green-700 pt-4 border-t">3. Kelola Hadiah Siswa</h2>
            <div class="bg-green-100 p-4 rounded-xl shadow-md border-l-4 border-green-600">
                <h3 class="text-lg font-bold text-green-800 mb-3">Tambah Hadiah Fisik Baru</h3>
                <input id="input-reward-nama" type="text" placeholder="Nama Hadiah" class="w-full p-2 mb-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                <input id="input-reward-poin" type="number" min="10" placeholder="Poin yang Dibutuhkan (min 10)" class="w-full p-2 mb-3 border rounded-lg focus:ring-green-500 focus:border-green-500">
                <button id="btn-tambah-reward" class="w-full py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors">
                    <i class="ph-plus-circle ph-fill mr-1"></i> Tambahkan Hadiah
                </button>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Daftar Hadiah Aktif:</h3>
                <div id="edit-reward-list" class="space-y-3">
                    <p class="text-center text-gray-500">Hadiah akan dimuat setelah siswa dipilih.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const overlay = document.getElementById('loading-overlay');
        const adminIdEl = document.getElementById('user-id-display');
        const targetStudentInput = document.getElementById('target-student-id');
        const btnLoad = document.getElementById('btn-load-student');
        const statusEl = document.getElementById('current-student-status');
        const summaryCard = document.getElementById('student-summary-card');
        const studentNameEl = document.getElementById('student-name-display');
        const studentPoinEl = document.getElementById('student-poin-display');
        const misiPage = document.getElementById('misi-page');
        const pendingList = document.getElementById('pending-requests-list');
        const emptyState = document.getElementById('requests-empty-state');
        const editMisiList = document.getElementById('edit-misi-list');
        const editRewardList = document.getElementById('edit-reward-list');

        let adminId = '';
        let studentId = '';
        let pollTimer = null;
        let lastAt = 0;
        let state = null;

        function ensureAdminId() {
            const existing = localStorage.getItem('kaih_admin_id');
            if (existing && /^[A-Za-z0-9_-]{6,64}$/.test(existing)) return existing;
            const id = (crypto && crypto.randomUUID) ? crypto.randomUUID().replace(/-/g, '') : (Date.now().toString(36) + Math.random().toString(36).slice(2));
            const clean = id.replace(/[^A-Za-z0-9_-]/g, '').slice(0, 64);
            localStorage.setItem('kaih_admin_id', clean);
            return clean;
        }

        async function apiGetState() {
            const url = `${window.KAIH_API_BASE}&action=state&student_id=${encodeURIComponent(studentId)}`;
            const res = await fetch(url, { method: 'GET' });
            const json = await res.json();
            if (!json || !json.ok) throw new Error(json && json.error ? json.error : 'load_failed');
            return json.state;
        }

        async function apiSaveState(nextState) {
            const url = `${window.KAIH_API_BASE}&action=state&student_id=${encodeURIComponent(studentId)}`;
            const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ state: nextState }) });
            const json = await res.json();
            if (!json || !json.ok) throw new Error(json && json.error ? json.error : 'save_failed');
            return json.state;
        }

        function normMission(m) {
            if (!m) return m;
            if (typeof m.pending !== 'boolean') m.pending = !!m.completed;
            if (typeof m.approved !== 'boolean') m.approved = false;
            return m;
        }

        function renderSummary() {
            if (!state) return;
            summaryCard.classList.remove('hidden');
            misiPage.classList.remove('hidden');
            studentNameEl.textContent = state.namaAnak || 'Siswa';
            studentPoinEl.textContent = state.totalBintang || 0;
        }

        function renderPending() {
            pendingList.querySelectorAll('.request-card').forEach((n) => n.remove());
            if (!state || !state.currentMissions) return;

            const missions = Object.values(state.currentMissions).map(normMission);
            const pending = missions.filter((m) => m.pending && !m.approved);
            emptyState.classList.toggle('hidden', pending.length > 0);

            pending.forEach((m) => {
                const card = document.createElement('div');
                card.className = 'request-card bg-white p-4 rounded-xl shadow-md';
                const proof = m.proofUrl ? `<a class="text-blue-600 font-bold text-sm hover:underline" href="${m.proofUrl}" target="_blank" rel="noopener">Lihat Bukti</a>` : `<span class="text-xs text-gray-400">Tanpa bukti</span>`;
                card.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-bold text-gray-800">${m.nama}</div>
                            <div class="text-xs text-gray-500">${m.keterangan || ''}</div>
                            <div class="mt-2 text-xs font-bold text-yellow-600">+${m.poin} bintang</div>
                            <div class="mt-2">${proof}</div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="button" class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold">Setujui</button>
                            <button type="button" class="px-3 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold">Tolak</button>
                        </div>
                    </div>
                `;
                const [approveBtn, rejectBtn] = card.querySelectorAll('button');
                approveBtn.addEventListener('click', async () => {
                    const ok = confirm(`Setujui misi "${m.nama}" dan tambahkan ${m.poin} bintang?`);
                    if (!ok) return;
                    const mm = normMission(state.currentMissions[m.id]);
                    mm.pending = false;
                    mm.approved = true;
                    mm.approvedAt = new Date().toISOString();
                    state.totalBintang = (state.totalBintang || 0) + (mm.poin || 0);
                    try {
                        overlay.classList.remove('hidden');
                        state = await apiSaveState(state);
                        lastAt = state.updatedAt || lastAt;
                        renderAll();
                    } finally {
                        overlay.classList.add('hidden');
                    }
                });
                rejectBtn.addEventListener('click', async () => {
                    const ok = confirm(`Tolak misi "${m.nama}"?`);
                    if (!ok) return;
                    const mm = normMission(state.currentMissions[m.id]);
                    mm.pending = false;
                    mm.approved = false;
                    delete mm.proofUrl;
                    delete mm.submittedAt;
                    try {
                        overlay.classList.remove('hidden');
                        state = await apiSaveState(state);
                        lastAt = state.updatedAt || lastAt;
                        renderAll();
                    } finally {
                        overlay.classList.add('hidden');
                    }
                });

                pendingList.appendChild(card);
            });
        }

        function renderMissionsAdmin() {
            editMisiList.innerHTML = '';
            if (!state || !state.currentMissions) {
                editMisiList.innerHTML = '<p class="text-center text-gray-500">Misi akan dimuat setelah siswa dipilih.</p>';
                return;
            }
            Object.keys(state.currentMissions).forEach((id) => {
                const m = normMission(state.currentMissions[id]);
                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-full text-left bg-slate-50 hover:bg-slate-100 p-3 rounded-lg border border-slate-200';
                row.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="font-bold text-slate-900">${m.nama}</div>
                            <div class="text-xs text-slate-500">${m.keterangan || ''}</div>
                        </div>
                        <div class="text-xs font-bold text-yellow-700">+${m.poin}</div>
                    </div>
                `;
                row.addEventListener('click', async () => {
                    const aksi = prompt('Ketik: edit / hapus', 'edit');
                    if (!aksi) return;
                    if (aksi.toLowerCase() === 'hapus') {
                        const ok = confirm(`Hapus misi "${m.nama}"?`);
                        if (!ok) return;
                        delete state.currentMissions[id];
                    } else {
                        const nama = prompt('Nama misi', m.nama);
                        if (nama === null) return;
                        const ket = prompt('Keterangan', m.keterangan || '');
                        if (ket === null) return;
                        const poin = parseInt(prompt('Poin', String(m.poin || 0)) || '0', 10);
                        m.nama = nama;
                        m.keterangan = ket;
                        m.poin = isNaN(poin) ? 0 : poin;
                        state.currentMissions[id] = m;
                    }
                    overlay.classList.remove('hidden');
                    try {
                        state = await apiSaveState(state);
                        lastAt = state.updatedAt || lastAt;
                        renderAll();
                    } finally {
                        overlay.classList.add('hidden');
                    }
                });
                editMisiList.appendChild(row);
            });
        }

        function renderRewardsAdmin() {
            editRewardList.innerHTML = '';
            if (!state || !Array.isArray(state.physicalRewards)) {
                editRewardList.innerHTML = '<p class="text-center text-gray-500">Hadiah akan dimuat setelah siswa dipilih.</p>';
                return;
            }
            if (!state.physicalRewards.length) {
                editRewardList.innerHTML = '<p class="text-center text-gray-500">Belum ada hadiah.</p>';
                return;
            }
            state.physicalRewards.forEach((r, idx) => {
                const row = document.createElement('div');
                row.className = 'bg-slate-50 p-3 rounded-lg border border-slate-200 flex items-center justify-between gap-3';
                row.innerHTML = `
                    <div>
                        <div class="font-bold text-slate-900">${r.nama || 'Hadiah'}</div>
                        <div class="text-xs text-slate-500">${r.poin || 0} bintang</div>
                    </div>
                    <button type="button" class="text-xs font-bold text-red-600">Hapus</button>
                `;
                row.querySelector('button').addEventListener('click', async () => {
                    const ok = confirm(`Hapus hadiah "${r.nama}"?`);
                    if (!ok) return;
                    state.physicalRewards.splice(idx, 1);
                    overlay.classList.remove('hidden');
                    try {
                        state = await apiSaveState(state);
                        lastAt = state.updatedAt || lastAt;
                        renderAll();
                    } finally {
                        overlay.classList.add('hidden');
                    }
                });
                editRewardList.appendChild(row);
            });
        }

        function renderAll() {
            renderSummary();
            renderPending();
            renderMissionsAdmin();
            renderRewardsAdmin();
        }

        async function loadStudent() {
            const raw = targetStudentInput.value.trim();
            if (!raw) return;
            if (!/^[A-Za-z0-9_-]{6,64}$/.test(raw)) {
                statusEl.textContent = 'Format ID tidak valid.';
                return;
            }
            studentId = raw;
            statusEl.textContent = `Memuat siswa: ${studentId}`;
            overlay.classList.remove('hidden');
            try {
                state = await apiGetState();
                lastAt = state.updatedAt || 0;
                statusEl.textContent = 'Siswa berhasil dimuat.';
                renderAll();
                if (pollTimer) clearInterval(pollTimer);
                pollTimer = setInterval(async () => {
                    try {
                        const remote = await apiGetState();
                        const at = remote.updatedAt || 0;
                        if (at > lastAt) {
                            state = remote;
                            lastAt = at;
                            renderAll();
                        }
                    } catch (e) {}
                }, 2000);
            } catch (e) {
                statusEl.textContent = 'Gagal memuat data siswa.';
            } finally {
                overlay.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            adminId = ensureAdminId();
            adminIdEl.textContent = `ID Admin: ${adminId}`;

            btnLoad.addEventListener('click', loadStudent);

            (async () => {
                try {
                    const url = `${window.KAIH_API_BASE}&action=teacher_students&student_id=__`;
                    const res = await fetch(url, { method: 'GET' });
                    const json = await res.json();
                    if (json && json.ok && Array.isArray(json.students) && json.students.length) {
                        const wrap = document.getElementById('assigned-students-wrap');
                        const sel = document.getElementById('assigned-students');
                        wrap.classList.remove('hidden');
                        json.students.forEach((s) => {
                            const opt = document.createElement('option');
                            opt.value = s.student_id;
                            opt.textContent = `${s.child_name || 'Siswa'} (${s.student_id})`;
                            sel.appendChild(opt);
                        });
                        sel.addEventListener('change', () => {
                            if (!sel.value) return;
                            targetStudentInput.value = sel.value;
                            loadStudent();
                        });
                    }
                } catch (e) {}
            })();

            document.getElementById('btn-tambah-misi').addEventListener('click', async () => {
                if (!state) return;
                const nama = document.getElementById('input-misi-nama').value.trim();
                const ket = document.getElementById('input-misi-keterangan').value.trim();
                const poin = parseInt(document.getElementById('input-misi-poin').value || '0', 10);
                if (!nama || !ket || isNaN(poin) || poin < 1) return;
                const id = 'misi-' + Date.now().toString(36);
                state.currentMissions = state.currentMissions || {};
                state.currentMissions[id] = { id, nama, keterangan: ket, poin, pending: false, approved: false, ikonClass: 'ph-fill ph-book', ikonBg: 'bg-gray-100 text-gray-600' };
                overlay.classList.remove('hidden');
                try {
                    state = await apiSaveState(state);
                    lastAt = state.updatedAt || lastAt;
                    renderAll();
                    document.getElementById('input-misi-nama').value = '';
                    document.getElementById('input-misi-keterangan').value = '';
                    document.getElementById('input-misi-poin').value = '';
                } finally {
                    overlay.classList.add('hidden');
                }
            });

            document.getElementById('btn-tambah-reward').addEventListener('click', async () => {
                if (!state) return;
                const nama = document.getElementById('input-reward-nama').value.trim();
                const poin = parseInt(document.getElementById('input-reward-poin').value || '0', 10);
                if (!nama || isNaN(poin) || poin < 10) return;
                state.physicalRewards = Array.isArray(state.physicalRewards) ? state.physicalRewards : [];
                state.physicalRewards.push({ nama, poin, used: false });
                overlay.classList.remove('hidden');
                try {
                    state = await apiSaveState(state);
                    lastAt = state.updatedAt || lastAt;
                    renderAll();
                    document.getElementById('input-reward-nama').value = '';
                    document.getElementById('input-reward-poin').value = '';
                } finally {
                    overlay.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
