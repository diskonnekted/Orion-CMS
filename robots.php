<?php

require_once __DIR__ . '/orion-load.php';

header('Content-Type: text/plain; charset=UTF-8');

$base = rtrim(orion_guess_root_url(), '/');

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Sitemap: " . $base . "/sitemap.php\n";

