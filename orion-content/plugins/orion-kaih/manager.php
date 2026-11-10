<?php
if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die('Orion CMS Core not found.');
    }
}

function orion_kaih_manager_root_url($path = '') {
    if (function_exists('get_option')) {
        $db_url = get_option('siteurl');
        if ($db_url) {
            return rtrim($db_url, '/') . $path;
        }
    }

    $protocol = 'http://';
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = 'https://';
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

    $basePath = '';
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $absPath = rtrim(str_replace('\\', '/', realpath(ABSPATH)), '/');
        if ($docRoot && $absPath && strpos($absPath, $docRoot) === 0) {
            $sub = trim(substr($absPath, strlen($docRoot)), '/');
            if ($sub !== '') {
                $basePath = '/' . $sub;
            }
        }
    }

    if ($basePath === '') {
        $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
        $dir = preg_replace('#/orion-content/plugins/.*$#', '', $dir);
        $dir = preg_replace('#/orion-content/themes/.*$#', '', $dir);
        $orion_admin = '/orion-admin';
        $len = strlen($orion_admin);
        if (substr($dir, -$len) === $orion_admin) {
            $dir = substr($dir, 0, -$len);
        } elseif (($pos = strpos($dir, $orion_admin . '/')) !== false) {
            $dir = substr($dir, 0, $pos);
        }
        if ($dir && $dir !== '/') $basePath = $dir;
    }

    return $protocol . $host . $basePath . $path;
}

if (function_exists('is_user_logged_in') && !is_user_logged_in()) {
    header('Location: ' . orion_kaih_manager_root_url('/orion-admin/'));
    exit;
}

$is_admin = function_exists('current_user_can') && current_user_can('administrator');
if (!$is_admin) {
    http_response_code(403);
    die('Anda tidak punya akses.');
}

$tab = isset($_GET['tab']) ? trim((string) $_GET['tab']) : 'students';
if (!in_array($tab, array('students', 'teachers', 'assignments'), true)) $tab = 'students';

$message = '';
$error = '';

global $orion_db, $table_prefix;
$tables = function_exists('orion_kaih_tables') ? orion_kaih_tables() : array(
    'students' => $table_prefix . 'kaih_students',
    'teacher_students' => $table_prefix . 'kaih_teacher_students',
);

function orion_kaih_manager_sanitize_student_id($id) {
    if (function_exists('orion_kaih_sanitize_student_id')) {
        return orion_kaih_sanitize_student_id($id);
    }
    if (!is_string($id)) return '';
    $id = trim($id);
    if ($id === '' || strlen($id) > 64) return '';
    if (!preg_match('/^[A-Za-z0-9_-]+$/', $id)) return '';
    return $id;
}

function orion_kaih_manager_teacher_users() {
    global $orion_db, $table_prefix;
    $users_table = $table_prefix . 'users';
    $res = $orion_db->query("SELECT * FROM $users_table ORDER BY user_registered DESC");
    $rows = array();
    if ($res) {
        while ($u = $res->fetch_object()) {
            $wu = new WP_User($u);
            if (in_array('kaih_teacher', $wu->roles, true) || in_array('administrator', $wu->roles, true)) {
                $rows[] = $wu;
            }
        }
    }
    return $rows;
}

function orion_kaih_manager_students() {
    global $orion_db, $tables;
    $sql = "SELECT * FROM {$tables['students']} ORDER BY updated_at DESC, id DESC";
    $res = $orion_db->query($sql);
    $rows = array();
    if ($res) {
        while ($r = $res->fetch_assoc()) $rows[] = $r;
    }
    return $rows;
}

function orion_kaih_manager_assignments() {
    global $orion_db, $tables;
    $sql = "SELECT * FROM {$tables['teacher_students']} ORDER BY created_at DESC, id DESC";
    $res = $orion_db->query($sql);
    $rows = array();
    if ($res) {
        while ($r = $res->fetch_assoc()) $rows[] = $r;
    }
    return $rows;
}

function orion_kaih_manager_set_student_pin($student_id, $pin) {
    global $orion_db, $tables;
    if (!$orion_db) return false;
    $sid = $orion_db->real_escape_string($student_id);
    $hash = password_hash($pin, PASSWORD_DEFAULT);
    $hash = $orion_db->real_escape_string($hash);
    return (bool) $orion_db->query("UPDATE {$tables['students']} SET parent_pin_hash = '$hash' WHERE student_id = '$sid'");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaih_action'])) {
    $act = trim((string) $_POST['kaih_action']);

    if ($act === 'add_student') {
        $sid = orion_kaih_manager_sanitize_student_id(isset($_POST['student_id']) ? (string) $_POST['student_id'] : '');
        $name = isset($_POST['child_name']) ? trim((string) $_POST['child_name']) : '';
        $pin = isset($_POST['parent_pin']) ? trim((string) $_POST['parent_pin']) : '';
        if ($sid === '' || $name === '') {
            $error = 'ID Siswa dan Nama wajib diisi.';
        } else {
            $sid_sql = $orion_db->real_escape_string($sid);
            $name_sql = $orion_db->real_escape_string($name);
            $exists = $orion_db->query("SELECT id FROM {$tables['students']} WHERE student_id = '$sid_sql' LIMIT 1");
            if ($exists && $exists->num_rows > 0) {
                $error = 'ID Siswa sudah ada.';
            } else {
                $hash_sql = 'NULL';
                if ($pin !== '') {
                    $hash = password_hash($pin, PASSWORD_DEFAULT);
                    $hash_sql = "'" . $orion_db->real_escape_string($hash) . "'";
                }
                $ok = $orion_db->query("INSERT INTO {$tables['students']} (student_id, child_name, parent_pin_hash) VALUES ('$sid_sql', '$name_sql', $hash_sql)");
                if ($ok) {
                    $message = 'Siswa ditambahkan.';
                } else {
                    $error = 'Gagal menambah siswa.';
                }
            }
        }
    }

    if ($act === 'update_student') {
        $sid = orion_kaih_manager_sanitize_student_id(isset($_POST['student_id']) ? (string) $_POST['student_id'] : '');
        $name = isset($_POST['child_name']) ? trim((string) $_POST['child_name']) : '';
        $pin = isset($_POST['parent_pin']) ? trim((string) $_POST['parent_pin']) : '';
        if ($sid === '' || $name === '') {
            $error = 'ID Siswa dan Nama wajib diisi.';
        } else {
            $sid_sql = $orion_db->real_escape_string($sid);
            $name_sql = $orion_db->real_escape_string($name);
            $ok = $orion_db->query("UPDATE {$tables['students']} SET child_name = '$name_sql' WHERE student_id = '$sid_sql'");
            if ($ok) {
                if ($pin !== '') {
                    orion_kaih_manager_set_student_pin($sid, $pin);
                }
                $message = 'Siswa diperbarui.';
            } else {
                $error = 'Gagal memperbarui siswa.';
            }
        }
    }

    if ($act === 'delete_student') {
        $sid = orion_kaih_manager_sanitize_student_id(isset($_POST['student_id']) ? (string) $_POST['student_id'] : '');
        if ($sid !== '') {
            $sid_sql = $orion_db->real_escape_string($sid);
            $orion_db->query("DELETE FROM {$tables['teacher_students']} WHERE student_id = '$sid_sql'");
            $ok = $orion_db->query("DELETE FROM {$tables['students']} WHERE student_id = '$sid_sql'");
            if ($ok) $message = 'Siswa dihapus.';
            else $error = 'Gagal menghapus siswa.';
        }
    }

    if ($act === 'assign') {
        $sid = orion_kaih_manager_sanitize_student_id(isset($_POST['student_id']) ? (string) $_POST['student_id'] : '');
        $tid = isset($_POST['teacher_user_id']) ? (int) $_POST['teacher_user_id'] : 0;
        if ($sid === '' || $tid <= 0) {
            $error = 'Pilih guru dan siswa.';
        } else {
            $sid_sql = $orion_db->real_escape_string($sid);
            $tid_sql = (int) $tid;
            $ok = $orion_db->query("INSERT IGNORE INTO {$tables['teacher_students']} (teacher_user_id, student_id) VALUES ($tid_sql, '$sid_sql')");
            if ($ok) $message = 'Relasi ditambahkan.';
            else $error = 'Gagal menambah relasi.';
        }
    }

    if ($act === 'unassign') {
        $sid = orion_kaih_manager_sanitize_student_id(isset($_POST['student_id']) ? (string) $_POST['student_id'] : '');
        $tid = isset($_POST['teacher_user_id']) ? (int) $_POST['teacher_user_id'] : 0;
        if ($sid !== '' && $tid > 0) {
            $sid_sql = $orion_db->real_escape_string($sid);
            $tid_sql = (int) $tid;
            $ok = $orion_db->query("DELETE FROM {$tables['teacher_students']} WHERE teacher_user_id = $tid_sql AND student_id = '$sid_sql'");
            if ($ok) $message = 'Relasi dihapus.';
            else $error = 'Gagal menghapus relasi.';
        }
    }

    if ($act === 'add_teacher') {
        $username = isset($_POST['user_login']) ? trim((string) $_POST['user_login']) : '';
        $email = isset($_POST['user_email']) ? trim((string) $_POST['user_email']) : '';
        $display = isset($_POST['display_name']) ? trim((string) $_POST['display_name']) : '';
        $pass = isset($_POST['user_pass']) ? (string) $_POST['user_pass'] : '';
        if ($username === '' || $email === '' || $pass === '') {
            $error = 'Username, Email, dan Password wajib diisi.';
        } else {
            $new_id = wp_create_user($username, $pass, $email);
            if (is_wp_error($new_id)) {
                $error = $new_id->get_error_message();
            } else {
                if ($display !== '') {
                    wp_update_user(array('ID' => $new_id, 'display_name' => $display));
                }
                update_user_meta($new_id, 'orion_capabilities', serialize(array('kaih_teacher' => true)));
                update_user_meta($new_id, 'orion_user_level', 5);
                $message = 'Akun guru dibuat.';
            }
        }
    }
}

$student_page_id = function_exists('get_option') ? (int) get_option('orion_kaih_page_student_id', 0) : 0;
$teacher_page_id = function_exists('get_option') ? (int) get_option('orion_kaih_page_teacher_id', 0) : 0;

$student_url = $student_page_id ? orion_kaih_manager_root_url('/?page_id=' . $student_page_id) : orion_kaih_manager_root_url('/?kaih=siswa');
$teacher_url = $teacher_page_id ? orion_kaih_manager_root_url('/?page_id=' . $teacher_page_id) : orion_kaih_manager_root_url('/?kaih=guru');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAih Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto p-8">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">KAih (Sekolah)</h1>
            <p class="text-slate-600 mb-6">Kelola data Siswa, Guru, dan Relasi KAih.</p>

            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-2 mb-6">
                <a href="?tab=students" class="px-4 py-2 rounded-xl text-sm font-bold border <?php echo $tab === 'students' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200'; ?>">Siswa</a>
                <a href="?tab=teachers" class="px-4 py-2 rounded-xl text-sm font-bold border <?php echo $tab === 'teachers' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200'; ?>">Guru</a>
                <a href="?tab=assignments" class="px-4 py-2 rounded-xl text-sm font-bold border <?php echo $tab === 'assignments' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200'; ?>">Relasi</a>
                <div class="flex-1"></div>
                <a href="<?php echo $student_url; ?>" class="px-4 py-2 rounded-xl text-sm font-bold bg-blue-600 text-white">Buka KAih Siswa</a>
                <a href="<?php echo $teacher_url; ?>" class="px-4 py-2 rounded-xl text-sm font-bold bg-indigo-600 text-white">Buka KAih Guru</a>
            </div>

            <?php if ($tab === 'students'): ?>
                <div class="grid grid-cols-1 gap-6">
                    <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50">
                        <div class="font-bold text-slate-900 mb-4">Tambah Siswa</div>
                        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <input type="hidden" name="kaih_action" value="add_student">
                            <input name="student_id" class="p-2 rounded-lg border border-slate-200" placeholder="ID Siswa (NIS)" required>
                            <input name="child_name" class="p-2 rounded-lg border border-slate-200" placeholder="Nama Anak" required>
                            <input name="parent_pin" class="p-2 rounded-lg border border-slate-200" placeholder="PIN Ortu (opsional)">
                            <button class="px-4 py-2 rounded-lg bg-slate-900 text-white font-bold">Tambah</button>
                        </form>
                        <div class="text-xs text-slate-500 mt-2">PIN Ortu dipakai untuk membuka Dashboard Ortu di KAih Siswa.</div>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 bg-white border-b border-slate-200 font-bold text-slate-900">Daftar Siswa</div>
                        <div class="overflow-x-auto bg-white">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="text-left px-5 py-3">ID</th>
                                        <th class="text-left px-5 py-3">Nama</th>
                                        <th class="text-left px-5 py-3">PIN</th>
                                        <th class="text-left px-5 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach (orion_kaih_manager_students() as $s): ?>
                                        <tr>
                                            <td class="px-5 py-3 font-mono"><?php echo htmlspecialchars($s['student_id']); ?></td>
                                            <td class="px-5 py-3">
                                                <form method="POST" class="flex flex-col md:flex-row gap-2 items-start md:items-center">
                                                    <input type="hidden" name="kaih_action" value="update_student">
                                                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($s['student_id']); ?>">
                                                    <input name="child_name" class="p-2 rounded-lg border border-slate-200 w-full md:w-64" value="<?php echo htmlspecialchars($s['child_name']); ?>">
                                                    <input name="parent_pin" class="p-2 rounded-lg border border-slate-200 w-full md:w-48" placeholder="PIN baru (opsional)">
                                                    <button class="px-3 py-2 rounded-lg bg-blue-600 text-white font-bold">Simpan</button>
                                                </form>
                                            </td>
                                            <td class="px-5 py-3 text-slate-600"><?php echo !empty($s['parent_pin_hash']) ? 'Set' : 'Belum'; ?></td>
                                            <td class="px-5 py-3">
                                                <form method="POST" onsubmit="return confirm('Hapus siswa ini?')" class="inline">
                                                    <input type="hidden" name="kaih_action" value="delete_student">
                                                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($s['student_id']); ?>">
                                                    <button class="px-3 py-2 rounded-lg bg-red-600 text-white font-bold">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($tab === 'teachers'): ?>
                <div class="grid grid-cols-1 gap-6">
                    <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50">
                        <div class="font-bold text-slate-900 mb-4">Tambah Akun Guru</div>
                        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <input type="hidden" name="kaih_action" value="add_teacher">
                            <input name="user_login" class="p-2 rounded-lg border border-slate-200" placeholder="Username" required>
                            <input name="user_email" type="email" class="p-2 rounded-lg border border-slate-200" placeholder="Email" required>
                            <input name="display_name" class="p-2 rounded-lg border border-slate-200" placeholder="Nama Guru">
                            <input name="user_pass" type="password" class="p-2 rounded-lg border border-slate-200" placeholder="Password" required>
                            <button class="md:col-span-4 px-4 py-2 rounded-lg bg-slate-900 text-white font-bold">Buat Akun Guru</button>
                        </form>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white">
                        <div class="px-5 py-4 border-b border-slate-200 font-bold text-slate-900">Daftar Guru</div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="text-left px-5 py-3">Nama</th>
                                        <th class="text-left px-5 py-3">Username</th>
                                        <th class="text-left px-5 py-3">Email</th>
                                        <th class="text-left px-5 py-3">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach (orion_kaih_manager_teacher_users() as $tuser): ?>
                                        <tr>
                                            <td class="px-5 py-3 font-bold text-slate-900"><?php echo htmlspecialchars($tuser->display_name); ?></td>
                                            <td class="px-5 py-3"><?php echo htmlspecialchars($tuser->user_login); ?></td>
                                            <td class="px-5 py-3"><?php echo htmlspecialchars($tuser->user_email); ?></td>
                                            <td class="px-5 py-3"><?php echo !empty($tuser->roles) ? htmlspecialchars($tuser->roles[0]) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-5 py-4 text-xs text-slate-500 border-t border-slate-200">Guru login melalui <span class="font-mono">/login.php</span>, lalu buka KAih Guru.</div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($tab === 'assignments'): ?>
                <div class="grid grid-cols-1 gap-6">
                    <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50">
                        <div class="font-bold text-slate-900 mb-4">Buat Relasi Guru → Siswa</div>
                        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <input type="hidden" name="kaih_action" value="assign">
                            <select name="teacher_user_id" class="p-2 rounded-lg border border-slate-200" required>
                                <option value="">Pilih Guru</option>
                                <?php foreach (orion_kaih_manager_teacher_users() as $tuser): ?>
                                    <?php if (in_array('kaih_teacher', $tuser->roles, true) || in_array('administrator', $tuser->roles, true)): ?>
                                        <option value="<?php echo (int) $tuser->ID; ?>"><?php echo htmlspecialchars($tuser->display_name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <select name="student_id" class="p-2 rounded-lg border border-slate-200" required>
                                <option value="">Pilih Siswa</option>
                                <?php foreach (orion_kaih_manager_students() as $s): ?>
                                    <option value="<?php echo htmlspecialchars($s['student_id']); ?>"><?php echo htmlspecialchars($s['child_name']); ?> (<?php echo htmlspecialchars($s['student_id']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <div class="md:col-span-2">
                                <button class="px-4 py-2 rounded-lg bg-slate-900 text-white font-bold w-full">Tambah Relasi</button>
                            </div>
                        </form>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white">
                        <div class="px-5 py-4 border-b border-slate-200 font-bold text-slate-900">Daftar Relasi</div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="text-left px-5 py-3">Guru</th>
                                        <th class="text-left px-5 py-3">Siswa</th>
                                        <th class="text-left px-5 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php
                                    $teachers_map = array();
                                    foreach (orion_kaih_manager_teacher_users() as $tuser) $teachers_map[(int) $tuser->ID] = $tuser;
                                    $students_map = array();
                                    foreach (orion_kaih_manager_students() as $s) $students_map[$s['student_id']] = $s;
                                    ?>
                                    <?php foreach (orion_kaih_manager_assignments() as $rel): ?>
                                        <?php
                                        $tid = (int) $rel['teacher_user_id'];
                                        $sid = (string) $rel['student_id'];
                                        $tuser = isset($teachers_map[$tid]) ? $teachers_map[$tid] : null;
                                        $s = isset($students_map[$sid]) ? $students_map[$sid] : null;
                                        ?>
                                        <tr>
                                            <td class="px-5 py-3"><?php echo $tuser ? htmlspecialchars($tuser->display_name) : ('User #' . $tid); ?></td>
                                            <td class="px-5 py-3"><?php echo $s ? (htmlspecialchars($s['child_name']) . ' (' . htmlspecialchars($sid) . ')') : htmlspecialchars($sid); ?></td>
                                            <td class="px-5 py-3">
                                                <form method="POST" class="inline" onsubmit="return confirm('Hapus relasi ini?')">
                                                    <input type="hidden" name="kaih_action" value="unassign">
                                                    <input type="hidden" name="teacher_user_id" value="<?php echo $tid; ?>">
                                                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($sid); ?>">
                                                    <button class="px-3 py-2 rounded-lg bg-red-600 text-white font-bold">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-8 text-sm text-slate-500">
                Data tersimpan di folder <span class="font-mono">orion-content/uploads/kaih/</span> (JSON).
            </div>

            <div class="mt-6 text-sm">
                <a class="text-blue-600 font-bold hover:underline" href="<?php echo orion_kaih_manager_root_url('/orion-admin/'); ?>">Kembali ke Admin</a>
            </div>
        </div>
    </div>
</body>
</html>
