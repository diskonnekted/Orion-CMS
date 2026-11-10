<?php
/*
Plugin Name: Orion KAih (Sekolah)
Description: Aplikasi Anak Hebat (KAih) untuk siswa dan dashboard guru/orang tua. Storage berbasis file JSON agar tetap jalan walau DB berubah.
Version: 1.0.0
Author: Orion Team
*/

function orion_kaih_upload_base_dir() {
    $base = ABSPATH . 'orion-content/uploads/kaih/';
    if (!file_exists($base)) {
        @mkdir($base, 0755, true);
    }
    if (!file_exists($base . 'students/')) {
        @mkdir($base . 'students/', 0755, true);
    }
    if (!file_exists($base . 'proofs/')) {
        @mkdir($base . 'proofs/', 0755, true);
    }
    return $base;
}

function orion_kaih_root_url($path = '') {
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

function orion_kaih_sanitize_student_id($id) {
    if (!is_string($id)) return '';
    $id = trim($id);
    if ($id === '') return '';
    if (strlen($id) > 64) return '';
    if (!preg_match('/^[A-Za-z0-9_-]+$/', $id)) return '';
    return $id;
}

function orion_kaih_default_state() {
    return array(
        'schemaVersion' => 1,
        'updatedAt' => (int) (microtime(true) * 1000),
        'totalBintang' => 0,
        'namaAnak' => 'Anak Hebat',
        'inventory' => (object) array(),
        'physicalRewards' => array(),
        'currentMissions' => array(
            'misi-1' => array('id' => 'misi-1', 'nama' => 'Bangun Pagi', 'keterangan' => 'Bangun sebelum jam 06:00', 'poin' => 10, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-sun', 'ikonBg' => 'bg-yellow-100 text-yellow-600'),
            'misi-2' => array('id' => 'misi-2', 'nama' => 'Beribadah', 'keterangan' => 'Berdoa atau sholat tepat waktu', 'poin' => 15, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-hands-praying', 'ikonBg' => 'bg-sky-100 text-sky-600'),
            'misi-3' => array('id' => 'misi-3', 'nama' => 'Berolahraga', 'keterangan' => 'Gerak badan minimal 15 menit', 'poin' => 10, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-basketball', 'ikonBg' => 'bg-orange-100 text-orange-600'),
            'misi-4' => array('id' => 'misi-4', 'nama' => 'Makan Sehat', 'keterangan' => 'Makan sayur dan buah', 'poin' => 10, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-carrot', 'ikonBg' => 'bg-green-100 text-green-600'),
            'misi-5' => array('id' => 'misi-5', 'nama' => 'Gemar Belajar', 'keterangan' => 'Membaca buku atau belajar 30 menit', 'poin' => 15, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-book-open', 'ikonBg' => 'bg-indigo-100 text-indigo-600'),
            'misi-6' => array('id' => 'misi-6', 'nama' => 'Bermasyarakat', 'keterangan' => 'Membantu orang tua di rumah', 'poin' => 10, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-heart', 'ikonBg' => 'bg-pink-100 text-pink-600'),
            'misi-7' => array('id' => 'misi-7', 'nama' => 'Tidur Cepat', 'keterangan' => 'Tidur sebelum jam 21:00', 'poin' => 10, 'pending' => false, 'approved' => false, 'ikonClass' => 'ph-fill ph-moon', 'ikonBg' => 'bg-purple-100 text-purple-600'),
        ),
    );
}

function orion_kaih_state_file_path($student_id) {
    $base = orion_kaih_upload_base_dir();
    return $base . 'students/' . $student_id . '.json';
}

function orion_kaih_tables() {
    global $table_prefix;
    return array(
        'students' => $table_prefix . 'kaih_students',
        'teacher_students' => $table_prefix . 'kaih_teacher_students',
    );
}

function orion_kaih_install_schema() {
    if (!function_exists('get_option') || !function_exists('update_option')) return;
    $flag = get_option('orion_kaih_schema_v1', '0');
    if ($flag === '1') return;

    global $orion_db;
    if (!$orion_db) return;

    $t = orion_kaih_tables();

    $sql1 = "CREATE TABLE IF NOT EXISTS {$t['students']} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        student_id varchar(64) NOT NULL,
        child_name varchar(190) NOT NULL DEFAULT '',
        parent_pin_hash varchar(255) DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY student_id (student_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $sql2 = "CREATE TABLE IF NOT EXISTS {$t['teacher_students']} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        teacher_user_id bigint(20) NOT NULL,
        student_id varchar(64) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY teacher_student (teacher_user_id, student_id),
        KEY student_id (student_id),
        KEY teacher_user_id (teacher_user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $ok1 = $orion_db->query($sql1);
    $ok2 = $orion_db->query($sql2);
    if ($ok1 && $ok2) {
        update_option('orion_kaih_schema_v1', '1');
    }
}
add_action('init', 'orion_kaih_install_schema', 0);

function orion_kaih_get_student($student_id) {
    global $orion_db;
    if (!$orion_db) return null;
    $t = orion_kaih_tables();
    $sid = $orion_db->real_escape_string($student_id);
    $sql = "SELECT * FROM {$t['students']} WHERE student_id = '$sid' LIMIT 1";
    $res = $orion_db->query($sql);
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc();
    }
    return null;
}

function orion_kaih_upsert_student($student_id, $child_name, $parent_pin_plain = null) {
    global $orion_db;
    if (!$orion_db) return false;
    $t = orion_kaih_tables();

    $sid = $orion_db->real_escape_string($student_id);
    $name = $orion_db->real_escape_string((string) $child_name);

    $existing = orion_kaih_get_student($student_id);
    if ($existing) {
        $pin_sql = '';
        if (is_string($parent_pin_plain) && $parent_pin_plain !== '') {
            $hash = password_hash($parent_pin_plain, PASSWORD_DEFAULT);
            $hash = $orion_db->real_escape_string($hash);
            $pin_sql = ", parent_pin_hash = '$hash'";
        }
        $sql = "UPDATE {$t['students']} SET child_name = '$name' $pin_sql WHERE student_id = '$sid'";
        return (bool) $orion_db->query($sql);
    }

    $hash_sql = 'NULL';
    if (is_string($parent_pin_plain) && $parent_pin_plain !== '') {
        $hash = password_hash($parent_pin_plain, PASSWORD_DEFAULT);
        $hash_sql = "'" . $orion_db->real_escape_string($hash) . "'";
    }
    $sql = "INSERT INTO {$t['students']} (student_id, child_name, parent_pin_hash) VALUES ('$sid', '$name', $hash_sql)";
    return (bool) $orion_db->query($sql);
}

function orion_kaih_is_teacher_user($user_id) {
    if (!function_exists('get_user_by')) return false;
    $u = get_user_by('id', (int) $user_id);
    if (!$u) return false;
    $wu = new WP_User($u);
    return in_array('administrator', $wu->roles, true) || in_array('kaih_teacher', $wu->roles, true);
}

function orion_kaih_current_teacher_id() {
    if (!function_exists('is_user_logged_in') || !is_user_logged_in()) return 0;
    if (!function_exists('wp_get_current_user')) return 0;
    $u = wp_get_current_user();
    if (!$u || !isset($u->ID)) return 0;
    if (!orion_kaih_is_teacher_user((int) $u->ID)) return 0;
    return (int) $u->ID;
}

function orion_kaih_teacher_can_access_student($teacher_user_id, $student_id) {
    if ((int) $teacher_user_id <= 0) return false;
    if (!function_exists('current_user_can')) return false;
    if (current_user_can('administrator')) return true;

    global $orion_db;
    if (!$orion_db) return false;
    $t = orion_kaih_tables();
    $tid = (int) $teacher_user_id;
    $sid = $orion_db->real_escape_string($student_id);
    $sql = "SELECT id FROM {$t['teacher_students']} WHERE teacher_user_id = $tid AND student_id = '$sid' LIMIT 1";
    $res = $orion_db->query($sql);
    return $res && $res->num_rows > 0;
}

function orion_kaih_read_state($student_id) {
    $path = orion_kaih_state_file_path($student_id);
    if (!file_exists($path)) {
        return orion_kaih_default_state();
    }
    $raw = @file_get_contents($path);
    if (!is_string($raw) || $raw === '') {
        return orion_kaih_default_state();
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return orion_kaih_default_state();
    }
    if (!isset($data['schemaVersion'])) $data['schemaVersion'] = 1;
    if (!isset($data['updatedAt'])) $data['updatedAt'] = (int) (microtime(true) * 1000);
    if (!isset($data['totalBintang'])) $data['totalBintang'] = 0;
    if (!isset($data['namaAnak'])) $data['namaAnak'] = 'Anak Hebat';
    if (!isset($data['inventory']) || !is_array($data['inventory'])) $data['inventory'] = array();
    if (!isset($data['physicalRewards']) || !is_array($data['physicalRewards'])) $data['physicalRewards'] = array();
    if (!isset($data['currentMissions']) || !is_array($data['currentMissions'])) $data['currentMissions'] = orion_kaih_default_state()['currentMissions'];
    return $data;
}

function orion_kaih_write_state($student_id, $state) {
    $state['schemaVersion'] = 1;
    $state['updatedAt'] = (int) (microtime(true) * 1000);

    $path = orion_kaih_state_file_path($student_id);
    $dir = dirname($path);
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }

    $json = json_encode($state, JSON_UNESCAPED_UNICODE);
    if (!is_string($json)) {
        return false;
    }

    $fp = @fopen($path, 'c+');
    if (!$fp) {
        return false;
    }
    if (!@flock($fp, LOCK_EX)) {
        @fclose($fp);
        return false;
    }
    @ftruncate($fp, 0);
    @fwrite($fp, $json);
    @fflush($fp);
    @flock($fp, LOCK_UN);
    @fclose($fp);
    return true;
}

function orion_kaih_json_response($data, $status = 200) {
    if (!headers_sent()) {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function orion_kaih_handle_api() {
    if (!isset($_GET['orion_kaih_api'])) return;

    $action = isset($_GET['action']) ? trim((string) $_GET['action']) : '';

    if ($action === 'teacher_students') {
        $teacher_id = orion_kaih_current_teacher_id();
        if ($teacher_id <= 0) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'not_authorized'), 401);
        }

        global $orion_db;
        if (!$orion_db) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'db_unavailable'), 500);
        }
        $t = orion_kaih_tables();
        $tid = (int) $teacher_id;
        $sql = "SELECT s.student_id, s.child_name FROM {$t['students']} s INNER JOIN {$t['teacher_students']} ts ON ts.student_id = s.student_id WHERE ts.teacher_user_id = $tid ORDER BY s.child_name ASC";
        $res = $orion_db->query($sql);
        $rows = array();
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = array('student_id' => $r['student_id'], 'child_name' => $r['child_name']);
            }
        }
        orion_kaih_json_response(array('ok' => true, 'students' => $rows));
    }

    $student_id = isset($_GET['student_id']) ? orion_kaih_sanitize_student_id((string) $_GET['student_id']) : '';
    if ($student_id === '') {
        $student_id = isset($_POST['student_id']) ? orion_kaih_sanitize_student_id((string) $_POST['student_id']) : '';
    }
    if ($student_id === '') {
        orion_kaih_json_response(array('ok' => false, 'error' => 'student_id_required'), 400);
    }

    if ($action === 'parent_pin_verify') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            orion_kaih_json_response(array('ok' => false, 'error' => 'method_not_allowed'), 405);
        }
        $pin = isset($_POST['pin']) ? trim((string) $_POST['pin']) : '';
        if ($pin === '') {
            orion_kaih_json_response(array('ok' => false, 'error' => 'pin_required'), 400);
        }
        $row = orion_kaih_get_student($student_id);
        if (!$row || empty($row['parent_pin_hash'])) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'pin_not_set'), 400);
        }
        if (!password_verify($pin, (string) $row['parent_pin_hash'])) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'pin_invalid'), 401);
        }
        orion_kaih_json_response(array('ok' => true));
    }

    if ($action === 'state') {
        $teacher_id = orion_kaih_current_teacher_id();
        if ($teacher_id > 0 && !orion_kaih_teacher_can_access_student($teacher_id, $student_id)) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'forbidden'), 403);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $state = orion_kaih_read_state($student_id);
            if (function_exists('orion_kaih_get_student') && function_exists('orion_kaih_upsert_student')) {
                $s = orion_kaih_get_student($student_id);
                if (!$s) {
                    orion_kaih_upsert_student($student_id, isset($state['namaAnak']) ? (string) $state['namaAnak'] : 'Anak Hebat');
                }
            }
            orion_kaih_json_response(array('ok' => true, 'state' => $state));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $raw = file_get_contents('php://input');
            $payload = json_decode($raw, true);
            if (!is_array($payload) || !isset($payload['state']) || !is_array($payload['state'])) {
                orion_kaih_json_response(array('ok' => false, 'error' => 'invalid_payload'), 400);
            }
            $incoming = $payload['state'];
            $existing = orion_kaih_read_state($student_id);
            $is_teacher = $teacher_id > 0;

            if (!$is_teacher) {
                $merged = $existing;

                $merged_total = isset($incoming['totalBintang']) ? (int) $incoming['totalBintang'] : (int) $existing['totalBintang'];
                $merged['totalBintang'] = min((int) $existing['totalBintang'], $merged_total);

                if (isset($incoming['namaAnak']) && is_string($incoming['namaAnak'])) {
                    $merged['namaAnak'] = trim($incoming['namaAnak']);
                }
                if (isset($incoming['inventory']) && is_array($incoming['inventory'])) {
                    $merged['inventory'] = $incoming['inventory'];
                }
                if (isset($incoming['physicalRewards']) && is_array($incoming['physicalRewards'])) {
                    $merged['physicalRewards'] = $incoming['physicalRewards'];
                }

                if (isset($incoming['currentMissions']) && is_array($incoming['currentMissions'])) {
                    $merged['currentMissions'] = $existing['currentMissions'];
                    foreach ($incoming['currentMissions'] as $mid => $m) {
                        if (!is_array($m)) continue;
                        if (!isset($merged['currentMissions'][$mid]) || !is_array($merged['currentMissions'][$mid])) {
                            $merged['currentMissions'][$mid] = $m;
                        }
                        $curr = $merged['currentMissions'][$mid];

                        if (isset($m['pending'])) $curr['pending'] = (bool) $m['pending'];
                        if (isset($m['completed'])) $curr['completed'] = (bool) $m['completed'];
                        if (isset($m['proofUrl']) && is_string($m['proofUrl'])) $curr['proofUrl'] = $m['proofUrl'];
                        if (isset($m['submittedAt']) && is_string($m['submittedAt'])) $curr['submittedAt'] = $m['submittedAt'];

                        if (isset($curr['approved']) && $curr['approved']) {
                            $curr['pending'] = false;
                        } else {
                            $curr['approved'] = false;
                        }

                        $merged['currentMissions'][$mid] = $curr;
                    }
                }

                $state = $merged;
            } else {
                $state = $incoming;
            }

            $ok = orion_kaih_write_state($student_id, $state);
            if (!$ok) {
                orion_kaih_json_response(array('ok' => false, 'error' => 'save_failed'), 500);
            }
            $saved = orion_kaih_read_state($student_id);
            if (function_exists('orion_kaih_get_student') && function_exists('orion_kaih_upsert_student')) {
                $s = orion_kaih_get_student($student_id);
                if (!$s) {
                    orion_kaih_upsert_student($student_id, isset($saved['namaAnak']) ? (string) $saved['namaAnak'] : 'Anak Hebat');
                }
            }
            orion_kaih_json_response(array('ok' => true, 'state' => $saved));
        }

        orion_kaih_json_response(array('ok' => false, 'error' => 'method_not_allowed'), 405);
    }

    if ($action === 'upload') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            orion_kaih_json_response(array('ok' => false, 'error' => 'method_not_allowed'), 405);
        }
        if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'file_required'), 400);
        }
        if (!isset($_FILES['file']['error']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'upload_error'), 400);
        }

        $max = 5 * 1024 * 1024;
        if (isset($_FILES['file']['size']) && (int) $_FILES['file']['size'] > $max) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'file_too_large'), 400);
        }

        $name = isset($_FILES['file']['name']) ? (string) $_FILES['file']['name'] : '';
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'mov', 'webm');
        if (!in_array($ext, $allowed, true)) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'invalid_file_type'), 400);
        }

        $base = orion_kaih_upload_base_dir();
        $dest_dir = $base . 'proofs/' . $student_id . '/';
        if (!file_exists($dest_dir)) {
            @mkdir($dest_dir, 0755, true);
        }

        $mission_id = isset($_POST['mission_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['mission_id']) : 'misi';
        $filename = 'proof-' . time() . '-' . $mission_id . '.' . $ext;
        $dest_path = $dest_dir . $filename;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $dest_path)) {
            orion_kaih_json_response(array('ok' => false, 'error' => 'save_file_failed'), 500);
        }

        $url = orion_kaih_root_url('/orion-content/uploads/kaih/proofs/' . rawurlencode($student_id) . '/' . rawurlencode($filename));
        orion_kaih_json_response(array('ok' => true, 'url' => $url));
    }

    orion_kaih_json_response(array('ok' => false, 'error' => 'unknown_action'), 400);
}
add_action('init', 'orion_kaih_handle_api', 1);

function orion_kaih_get_page_id_by_title($title) {
    global $orion_db, $table_prefix;
    if (!$orion_db) return 0;
    $title_safe = $orion_db->real_escape_string($title);
    $table = $table_prefix . 'posts';
    $sql = "SELECT ID FROM $table WHERE post_title = '$title_safe' AND post_type = 'page' AND post_status = 'publish' LIMIT 1";
    $res = $orion_db->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_object();
        return (int) $row->ID;
    }
    return 0;
}

function orion_kaih_ensure_pages() {
    if (!function_exists('wp_insert_post') || !function_exists('update_option') || !function_exists('get_option')) {
        return;
    }

    $student_key = 'orion_kaih_page_student_id';
    $teacher_key = 'orion_kaih_page_teacher_id';

    $student_id = (int) get_option($student_key, 0);
    $teacher_id = (int) get_option($teacher_key, 0);

    if ($student_id <= 0) {
        $student_id = orion_kaih_get_page_id_by_title('KAih Siswa');
        if ($student_id <= 0) {
            $student_id = (int) wp_insert_post(array(
                'post_title' => 'KAih Siswa',
                'post_content' => 'Halaman aplikasi KAih untuk siswa.',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'kaih-siswa'
            ));
        }
        if ($student_id > 0) update_option($student_key, (string) $student_id);
    }

    if ($teacher_id <= 0) {
        $teacher_id = orion_kaih_get_page_id_by_title('KAih Guru');
        if ($teacher_id <= 0) {
            $teacher_id = (int) wp_insert_post(array(
                'post_title' => 'KAih Guru',
                'post_content' => 'Halaman dashboard KAih untuk guru/orang tua.',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'kaih-guru'
            ));
        }
        if ($teacher_id > 0) update_option($teacher_key, (string) $teacher_id);
    }
}
add_action('init', 'orion_kaih_ensure_pages', 1);

function orion_kaih_get_target_menu_id() {
    if (!function_exists('get_option')) return 0;

    $locations = get_option('nav_menu_locations', array());
    if (is_array($locations) && isset($locations['primary']) && (int) $locations['primary'] > 0) {
        return (int) $locations['primary'];
    }

    if (function_exists('get_terms')) {
        $menus = get_terms('nav_menu', array('hide_empty' => false));
        if (is_array($menus) && !empty($menus) && isset($menus[0]->term_id)) {
            return (int) $menus[0]->term_id;
        }
    }

    if (function_exists('wp_insert_term')) {
        $res = wp_insert_term('Main Menu', 'nav_menu');
        if (is_array($res) && isset($res['term_id'])) {
            $menu_id = (int) $res['term_id'];
            if ($menu_id > 0 && function_exists('update_option')) {
                $locations = get_option('nav_menu_locations', array());
                if (!is_array($locations)) $locations = array();
                $locations['primary'] = $menu_id;
                update_option('nav_menu_locations', $locations);
            }
            return $menu_id;
        }
    }

    return 0;
}

function orion_kaih_nav_menu_has_url($menu_id, $url) {
    if (!function_exists('wp_get_nav_menu_items')) return false;
    $items = wp_get_nav_menu_items($menu_id);
    if (!is_array($items)) return false;
    foreach ($items as $it) {
        if (isset($it->url) && (string) $it->url === (string) $url) {
            return true;
        }
    }
    return false;
}

function orion_kaih_add_custom_menu_item($menu_id, $title, $url) {
    if (!function_exists('wp_insert_post') || !function_exists('wp_set_object_terms') || !function_exists('update_post_meta')) {
        return 0;
    }
    if ($menu_id <= 0) return 0;

    $item_id = (int) wp_insert_post(array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_type' => 'nav_menu_item'
    ));
    if ($item_id <= 0) return 0;

    wp_set_object_terms($item_id, $menu_id, 'nav_menu');
    update_post_meta($item_id, '_menu_item_type', 'custom');
    update_post_meta($item_id, '_menu_item_object_id', 0);
    update_post_meta($item_id, '_menu_item_object', 'custom');
    update_post_meta($item_id, '_menu_item_url', $url);
    update_post_meta($item_id, '_menu_item_menu_item_parent', 0);
    return $item_id;
}

function orion_kaih_ensure_menu_items() {
    if (!function_exists('get_option') || !function_exists('update_option')) return;

    $student_page_id = (int) get_option('orion_kaih_page_student_id', 0);
    $teacher_page_id = (int) get_option('orion_kaih_page_teacher_id', 0);

    $student_url = $student_page_id ? orion_kaih_root_url('/?page_id=' . $student_page_id) : orion_kaih_root_url('/?kaih=siswa');
    $teacher_url = $teacher_page_id ? orion_kaih_root_url('/?page_id=' . $teacher_page_id) : orion_kaih_root_url('/?kaih=guru');

    $menu_id = orion_kaih_get_target_menu_id();
    if ($menu_id <= 0) return;

    if (function_exists('wp_get_nav_menu_items') && function_exists('update_post_meta')) {
        $items = wp_get_nav_menu_items($menu_id);
        if (is_array($items)) {
            foreach ($items as $it) {
                $url = isset($it->url) ? (string) $it->url : '';
                $title = isset($it->post_title) ? (string) $it->post_title : '';
                if ($url === '') continue;
                if (strpos($url, '/orion-content/plugins/orion-kaih/') === false) continue;
                if ($title === 'KAih Siswa' && $url !== $student_url) {
                    update_post_meta($it->ID, '_menu_item_url', $student_url);
                }
                if ($title === 'KAih Guru' && $url !== $teacher_url) {
                    update_post_meta($it->ID, '_menu_item_url', $teacher_url);
                }
            }
        }
    }

    if (get_option('orion_kaih_menu_seeded', '') === '1') return;

    $added_any = false;
    if (!orion_kaih_nav_menu_has_url($menu_id, $student_url)) {
        $id = orion_kaih_add_custom_menu_item($menu_id, 'KAih Siswa', $student_url);
        if ($id > 0) $added_any = true;
    }
    if (!orion_kaih_nav_menu_has_url($menu_id, $teacher_url)) {
        $id = orion_kaih_add_custom_menu_item($menu_id, 'KAih Guru', $teacher_url);
        if ($id > 0) $added_any = true;
    }

    if ($added_any) {
        update_option('orion_kaih_menu_seeded', '1');
    }
}
add_action('init', 'orion_kaih_ensure_menu_items', 5);

function orion_kaih_can_view_teacher_dashboard() {
    if (!function_exists('is_user_logged_in') || !is_user_logged_in()) return false;
    if (!function_exists('current_user_can')) return false;
    return current_user_can('administrator') || current_user_can('kaih_teacher');
}

function orion_kaih_router() {
    $student_page_id = function_exists('get_option') ? (int) get_option('orion_kaih_page_student_id', 0) : 0;
    $teacher_page_id = function_exists('get_option') ? (int) get_option('orion_kaih_page_teacher_id', 0) : 0;

    if (isset($_GET['page_id'])) {
        $pid = (int) $_GET['page_id'];
        $base = __DIR__ . '/';
        if ($student_page_id > 0 && $pid === $student_page_id) {
            include $base . 'templates/kaih-student.php';
            exit;
        }
        if ($teacher_page_id > 0 && $pid === $teacher_page_id) {
            if (!orion_kaih_can_view_teacher_dashboard()) {
                header('Location: ' . orion_kaih_root_url('/login.php'));
                exit;
            }
            include $base . 'templates/kaih-teacher.php';
            exit;
        }
    }

    if (!isset($_GET['kaih'])) return;
    $mode = trim((string) $_GET['kaih']);
    $base = __DIR__ . '/';
    if ($mode === 'siswa') {
        include $base . 'templates/kaih-student.php';
        exit;
    }
    if ($mode === 'guru') {
        if (!orion_kaih_can_view_teacher_dashboard()) {
            header('Location: ' . orion_kaih_root_url('/login.php'));
            exit;
        }
        include $base . 'templates/kaih-teacher.php';
        exit;
    }
}
add_action('init', 'orion_kaih_router', 2);

