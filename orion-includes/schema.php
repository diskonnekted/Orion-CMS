<?php
/**
 * Orion CMS Schema and Installation
 */

function orion_install() {
    global $orion_db, $table_prefix;

    $posts_table = $table_prefix . 'posts';
    $postmeta_table = $table_prefix . 'postmeta';

    $db_type = defined('DB_TYPE') ? strtolower((string) DB_TYPE) : 'mysql';
    $is_sqlite = ($db_type === 'sqlite');

    $GLOBALS['orion_install_error'] = '';
    $fail = function ($msg) {
        $GLOBALS['orion_install_error'] = (string) $msg;
        return false;
    };

    if ($is_sqlite) {
        $sql_posts = "CREATE TABLE IF NOT EXISTS $posts_table (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            post_author INTEGER NOT NULL DEFAULT 0,
            post_date TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            post_content TEXT NOT NULL,
            post_title TEXT NOT NULL,
            post_status TEXT NOT NULL DEFAULT 'publish',
            post_type TEXT NOT NULL DEFAULT 'post',
            post_modified TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );";

        $sql_postmeta = "CREATE TABLE IF NOT EXISTS $postmeta_table (
            meta_id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL DEFAULT 0,
            meta_key TEXT DEFAULT NULL,
            meta_value TEXT
        );";

        $terms_table = $table_prefix . 'terms';
        $sql_terms = "CREATE TABLE IF NOT EXISTS $terms_table (
            term_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL DEFAULT '',
            slug TEXT NOT NULL DEFAULT '',
            term_group INTEGER NOT NULL DEFAULT 0
        );";

        $term_taxonomy_table = $table_prefix . 'term_taxonomy';
        $sql_term_taxonomy = "CREATE TABLE IF NOT EXISTS $term_taxonomy_table (
            term_taxonomy_id INTEGER PRIMARY KEY AUTOINCREMENT,
            term_id INTEGER NOT NULL DEFAULT 0,
            taxonomy TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL,
            parent INTEGER NOT NULL DEFAULT 0,
            count INTEGER NOT NULL DEFAULT 0
        );";

        $term_relationships_table = $table_prefix . 'term_relationships';
        $sql_term_relationships = "CREATE TABLE IF NOT EXISTS $term_relationships_table (
            object_id INTEGER NOT NULL DEFAULT 0,
            term_taxonomy_id INTEGER NOT NULL DEFAULT 0,
            term_order INTEGER NOT NULL DEFAULT 0,
            PRIMARY KEY (object_id, term_taxonomy_id)
        );";

        $options_table = $table_prefix . 'options';
        $sql_options = "CREATE TABLE IF NOT EXISTS $options_table (
            option_id INTEGER PRIMARY KEY AUTOINCREMENT,
            option_name TEXT NOT NULL DEFAULT '',
            option_value TEXT NOT NULL,
            autoload TEXT NOT NULL DEFAULT 'yes'
        );";

        $users_table = $table_prefix . 'users';
        $sql_users = "CREATE TABLE IF NOT EXISTS $users_table (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            user_login TEXT NOT NULL DEFAULT '',
            user_pass TEXT NOT NULL DEFAULT '',
            user_nicename TEXT NOT NULL DEFAULT '',
            user_email TEXT NOT NULL DEFAULT '',
            user_registered TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_status INTEGER NOT NULL DEFAULT 0,
            display_name TEXT NOT NULL DEFAULT ''
        );";

        $usermeta_table = $table_prefix . 'usermeta';
        $sql_usermeta = "CREATE TABLE IF NOT EXISTS $usermeta_table (
            umeta_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL DEFAULT 0,
            meta_key TEXT DEFAULT NULL,
            meta_value TEXT
        );";
    } else {
        $sql_posts = "CREATE TABLE IF NOT EXISTS $posts_table (
            ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            post_author bigint(20) unsigned NOT NULL DEFAULT '0',
            post_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            post_content longtext NOT NULL,
            post_title text NOT NULL,
            post_status varchar(20) NOT NULL DEFAULT 'publish',
            post_type varchar(20) NOT NULL DEFAULT 'post',
            post_modified datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $sql_postmeta = "CREATE TABLE IF NOT EXISTS $postmeta_table (
            meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            post_id bigint(20) unsigned NOT NULL DEFAULT '0',
            meta_key varchar(255) DEFAULT NULL,
            meta_value longtext,
            PRIMARY KEY (meta_id),
            KEY post_id (post_id),
            KEY meta_key (meta_key(191))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $terms_table = $table_prefix . 'terms';
        $sql_terms = "CREATE TABLE IF NOT EXISTS $terms_table (
            term_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(200) NOT NULL DEFAULT '',
            slug varchar(200) NOT NULL DEFAULT '',
            term_group bigint(10) NOT NULL DEFAULT 0,
            PRIMARY KEY (term_id),
            KEY slug (slug(191)),
            KEY name (name(191))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $term_taxonomy_table = $table_prefix . 'term_taxonomy';
        $sql_term_taxonomy = "CREATE TABLE IF NOT EXISTS $term_taxonomy_table (
            term_taxonomy_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            term_id bigint(20) unsigned NOT NULL DEFAULT 0,
            taxonomy varchar(32) NOT NULL DEFAULT '',
            description longtext NOT NULL,
            parent bigint(20) unsigned NOT NULL DEFAULT 0,
            count bigint(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (term_taxonomy_id),
            UNIQUE KEY term_id_taxonomy (term_id,taxonomy),
            KEY taxonomy (taxonomy)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $term_relationships_table = $table_prefix . 'term_relationships';
        $sql_term_relationships = "CREATE TABLE IF NOT EXISTS $term_relationships_table (
            object_id bigint(20) unsigned NOT NULL DEFAULT 0,
            term_taxonomy_id bigint(20) unsigned NOT NULL DEFAULT 0,
            term_order int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (object_id,term_taxonomy_id),
            KEY term_taxonomy_id (term_taxonomy_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $options_table = $table_prefix . 'options';
        $sql_options = "CREATE TABLE IF NOT EXISTS $options_table (
            option_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            option_name varchar(191) NOT NULL DEFAULT '',
            option_value longtext NOT NULL,
            autoload varchar(20) NOT NULL DEFAULT 'yes',
            PRIMARY KEY (option_id),
            UNIQUE KEY option_name (option_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $users_table = $table_prefix . 'users';
        $sql_users = "CREATE TABLE IF NOT EXISTS $users_table (
            ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_login varchar(60) NOT NULL DEFAULT '',
            user_pass varchar(255) NOT NULL DEFAULT '',
            user_nicename varchar(50) NOT NULL DEFAULT '',
            user_email varchar(100) NOT NULL DEFAULT '',
            user_registered datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_status int(11) NOT NULL DEFAULT '0',
            display_name varchar(250) NOT NULL DEFAULT '',
            PRIMARY KEY (ID),
            KEY user_login_key (user_login),
            KEY user_nicename (user_nicename),
            KEY user_email (user_email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $usermeta_table = $table_prefix . 'usermeta';
        $sql_usermeta = "CREATE TABLE IF NOT EXISTS $usermeta_table (
            umeta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL DEFAULT '0',
            meta_key varchar(255) DEFAULT NULL,
            meta_value longtext,
            PRIMARY KEY (umeta_id),
            KEY user_id (user_id),
            KEY meta_key (meta_key(191))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    }

    if (!$orion_db->query($sql_posts)) {
        return $fail("Error creating posts table: " . $orion_db->error);
    }
    
    if (!$orion_db->query($sql_postmeta)) {
        return $fail("Error creating postmeta table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_terms)) {
        return $fail("Error creating terms table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_term_taxonomy)) {
        return $fail("Error creating term_taxonomy table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_term_relationships)) {
        return $fail("Error creating term_relationships table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_options)) {
        return $fail("Error creating options table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_users)) {
        return $fail("Error creating users table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_usermeta)) {
        return $fail("Error creating usermeta table: " . $orion_db->error);
    }

    if ($is_sqlite) {
        $orion_db->query("CREATE UNIQUE INDEX IF NOT EXISTS {$options_table}_option_name ON $options_table(option_name)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$postmeta_table}_post_id ON $postmeta_table(post_id)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$postmeta_table}_meta_key ON $postmeta_table(meta_key)");
        $orion_db->query("CREATE UNIQUE INDEX IF NOT EXISTS {$term_taxonomy_table}_term_id_taxonomy ON $term_taxonomy_table(term_id, taxonomy)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$term_taxonomy_table}_taxonomy ON $term_taxonomy_table(taxonomy)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$terms_table}_slug ON $terms_table(slug)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$users_table}_user_login ON $users_table(user_login)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$users_table}_user_email ON $users_table(user_email)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$usermeta_table}_user_id ON $usermeta_table(user_id)");
        $orion_db->query("CREATE INDEX IF NOT EXISTS {$usermeta_table}_meta_key ON $usermeta_table(meta_key)");
    }

    // Insert Default Admin User if not exists
    $has_any_user = false;
    $check_any = $orion_db->query("SELECT ID FROM $users_table LIMIT 1");
    if ($check_any && $check_any->num_rows > 0) {
        $has_any_user = true;
    }
    if (!$has_any_user) {
        // Never seed a known password: 'password' in the source is a login for
        // anyone who reads the repository. Generate one and expose it once so a
        // fresh install can hand it to the operator, who should change it.
        $generated_pass = bin2hex(random_bytes(12));
        $pass_hash = password_hash($generated_pass, PASSWORD_DEFAULT);
        $orion_db->query("INSERT INTO $users_table (user_login, user_pass, user_nicename, user_email, display_name) VALUES ('diskonnekted', '$pass_hash', 'diskonnekted', 'arif.susio@gmail.com', 'Administrator')");
        $user_id = $orion_db->insert_id;
        $orion_db->query("INSERT INTO $usermeta_table (user_id, meta_key, meta_value) VALUES ($user_id, 'orion_capabilities', 'a:1:{s:13:\"administrator\";b:1;}')");
        $orion_db->query("INSERT INTO $usermeta_table (user_id, meta_key, meta_value) VALUES ($user_id, 'orion_user_level', '10')");
        $GLOBALS['orion_generated_admin_password'] = $generated_pass;
    }

    // Insert default options if not exist
    $check_template = $orion_db->query("SELECT option_id FROM $options_table WHERE option_name = 'template'");
    if ($check_template->num_rows == 0) {
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('template', 'orion-one')");
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('blogname', 'Orion CMS')");
    }

    $check_tagline = $orion_db->query("SELECT option_id FROM $options_table WHERE option_name = 'blogdescription'");
    if ($check_tagline && $check_tagline->num_rows == 0) {
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('blogdescription', 'fast.flexible.free')");
    }

    $check_desc = $orion_db->query("SELECT option_id FROM $options_table WHERE option_name = 'site_meta_description'");
    if ($check_desc && $check_desc->num_rows == 0) {
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('site_meta_description', 'Orion CMS adalah CMS ringan untuk membuat website gratis, cepat, dan mudah dipakai.')");
    }

    $check_keys = $orion_db->query("SELECT option_id FROM $options_table WHERE option_name = 'site_meta_keywords'");
    if ($check_keys && $check_keys->num_rows == 0) {
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('site_meta_keywords', 'website gratis, buat website gratis, bikin website gratis, cara membuat website gratis, website gratis tanpa coding, website builder, website builder gratis, builder website tanpa coding, cms gratis, free cms, fre cms, free website, web gratis, web gr4atis, web developer profesional, website developer pro, jasa pembuatan website, jasa bikin website, landing page builder, orion cms, orion cms free')");
    }

    return true;
}

function orion_is_installed() {
    global $orion_db, $table_prefix;
    $db_type = defined('DB_TYPE') ? strtolower((string) DB_TYPE) : 'mysql';
    if ($db_type === 'sqlite') {
        $table = $table_prefix . 'terms';
        $result = $orion_db->query("SELECT name FROM sqlite_master WHERE type='table' AND name = '$table' LIMIT 1");
        return $result && $result->num_rows > 0;
    }
    $result = $orion_db->query("SHOW TABLES LIKE '{$table_prefix}terms'");
    return $result && $result->num_rows > 0;
}
