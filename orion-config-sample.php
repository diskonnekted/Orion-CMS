<?php

define('DB_TYPE', 'sqlite');
define('DB_FILE', ABSPATH . 'orion-content/database/orion.sqlite');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');

$table_prefix = 'orion_';

define('ORION_DEBUG', true);

if ( ! defined('ABSPATH') ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'orion-settings.php';

