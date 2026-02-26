<?php

function orion_construct_setup() {
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu'
    ));
}

add_action('after_setup_theme', 'orion_construct_setup');

function orion_construct_install_quotes_table() {
    global $orion_db, $table_prefix;

    if (!isset($orion_db, $table_prefix)) {
        return;
    }

    $table_quotes = $table_prefix . 'orion_construct_quotes';
    $charset = 'DEFAULT CHARSET=utf8mb4';

    $sql = "CREATE TABLE IF NOT EXISTS $table_quotes (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        name VARCHAR(191) NOT NULL,
        email VARCHAR(191) DEFAULT NULL,
        phone VARCHAR(100) DEFAULT NULL,
        company VARCHAR(191) DEFAULT NULL,
        project_type VARCHAR(191) DEFAULT NULL,
        budget VARCHAR(191) DEFAULT NULL,
        location VARCHAR(191) DEFAULT NULL,
        message LONGTEXT NULL,
        status ENUM('masuk','dilaporkan','dibalas') NOT NULL DEFAULT 'masuk'
    ) ENGINE=InnoDB $charset;";

    $orion_db->query($sql);

    $result = $orion_db->query("SHOW COLUMNS FROM $table_quotes LIKE 'contact_channel'");
    if ($result && $result->num_rows === 0) {
        $orion_db->query("ALTER TABLE $table_quotes ADD COLUMN contact_channel VARCHAR(50) DEFAULT NULL AFTER location");
    }
    if ($result) {
        $result->free();
    }

    $result = $orion_db->query("SHOW COLUMNS FROM $table_quotes LIKE 'document_path'");
    if ($result && $result->num_rows === 0) {
        $orion_db->query("ALTER TABLE $table_quotes ADD COLUMN document_path VARCHAR(255) DEFAULT NULL AFTER message");
    }
    if ($result) {
        $result->free();
    }
}

orion_construct_install_quotes_table();
