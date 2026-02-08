<?php
define('ABSPATH', __DIR__ . '/');
require_once 'orion-config.php';
require_once 'orion-settings.php';

// Force install to run
orion_install();

echo "Database updated successfully. Default admin user created.";

