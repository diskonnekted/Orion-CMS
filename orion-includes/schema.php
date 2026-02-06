<?php
/**
 * Orion CMS Schema and Installation
 */

function orion_install() {
    global $orion_db, $table_prefix;

    $posts_table = $table_prefix . 'posts';
    $postmeta_table = $table_prefix . 'postmeta';

    // Create Posts Table
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

    // Create Postmeta Table
    $sql_postmeta = "CREATE TABLE IF NOT EXISTS $postmeta_table (
        meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        post_id bigint(20) unsigned NOT NULL DEFAULT '0',
        meta_key varchar(255) DEFAULT NULL,
        meta_value longtext,
        PRIMARY KEY (meta_id),
        KEY post_id (post_id),
        KEY meta_key (meta_key(191))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // Create Terms Table
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

    // Create Term Taxonomy Table
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

    // Create Term Relationships Table
    $term_relationships_table = $table_prefix . 'term_relationships';
    $sql_term_relationships = "CREATE TABLE IF NOT EXISTS $term_relationships_table (
        object_id bigint(20) unsigned NOT NULL DEFAULT 0,
        term_taxonomy_id bigint(20) unsigned NOT NULL DEFAULT 0,
        term_order int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (object_id,term_taxonomy_id),
        KEY term_taxonomy_id (term_taxonomy_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // Create Options Table
    $options_table = $table_prefix . 'options';
    $sql_options = "CREATE TABLE IF NOT EXISTS $options_table (
        option_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        option_name varchar(191) NOT NULL DEFAULT '',
        option_value longtext NOT NULL,
        autoload varchar(20) NOT NULL DEFAULT 'yes',
        PRIMARY KEY (option_id),
        UNIQUE KEY option_name (option_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // Create Users Table
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

    // Create Usermeta Table
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

    if (!$orion_db->query($sql_posts)) {
        die("Error creating posts table: " . $orion_db->error);
    }
    
    if (!$orion_db->query($sql_postmeta)) {
        die("Error creating postmeta table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_terms)) {
        die("Error creating terms table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_term_taxonomy)) {
        die("Error creating term_taxonomy table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_term_relationships)) {
        die("Error creating term_relationships table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_options)) {
        die("Error creating options table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_users)) {
        die("Error creating users table: " . $orion_db->error);
    }

    if (!$orion_db->query($sql_usermeta)) {
        die("Error creating usermeta table: " . $orion_db->error);
    }

    // Insert Default Admin User if not exists
    $result = $orion_db->query("SELECT ID FROM $users_table WHERE user_login = 'admin'");
    if ($result->num_rows == 0) {
        // Default password: password (hashed)
        $pass_hash = password_hash('password', PASSWORD_DEFAULT);
        $orion_db->query("INSERT INTO $users_table (user_login, user_pass, user_nicename, user_email, display_name) VALUES ('admin', '$pass_hash', 'admin', 'admin@example.com', 'Administrator')");
        $user_id = $orion_db->insert_id;
        
        // Add capabilities
        $orion_db->query("INSERT INTO $usermeta_table (user_id, meta_key, meta_value) VALUES ($user_id, 'orion_capabilities', 'a:1:{s:13:\"administrator\";b:1;}')");
        $orion_db->query("INSERT INTO $usermeta_table (user_id, meta_key, meta_value) VALUES ($user_id, 'orion_user_level', '10')");
    }


    if (!$orion_db->query($sql_options)) {
        die("Error creating options table: " . $orion_db->error);
    }

    // Insert default options if not exist
    $check_template = $orion_db->query("SELECT option_id FROM $options_table WHERE option_name = 'template'");
    if ($check_template->num_rows == 0) {
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('template', 'orion-one')");
        $orion_db->query("INSERT INTO $options_table (option_name, option_value) VALUES ('blogname', 'Orion CMS')");
    }
}

function orion_is_installed() {
    global $orion_db, $table_prefix;
    $result = $orion_db->query("SHOW TABLES LIKE '{$table_prefix}terms'");
    return $result && $result->num_rows > 0;
}
