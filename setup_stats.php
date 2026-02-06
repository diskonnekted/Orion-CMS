<?php
require_once('orion-load.php');

global $orion_db, $table_prefix;

$table_name = $table_prefix . 'visitor_log';

$sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    ip_address varchar(50) NOT NULL,
    visit_date datetime DEFAULT CURRENT_TIMESTAMP,
    user_agent varchar(255) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY visit_date (visit_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($orion_db->query($sql)) {
    echo "Table $table_name created successfully.";
} else {
    echo "Error creating table: " . $orion_db->error;
}
?>