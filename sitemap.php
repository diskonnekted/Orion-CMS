<?php

require_once __DIR__ . '/orion-load.php';

header('Content-Type: application/xml; charset=UTF-8');

$base = rtrim(orion_guess_root_url(), '/');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') . "</loc>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "    <priority>1.0</priority>\n";
echo "  </url>\n";

global $orion_db, $table_prefix;
$table = $table_prefix . 'posts';
$sql = "SELECT ID, post_date, post_modified, post_type FROM $table WHERE post_status = 'publish' AND (post_type = 'post' OR post_type = 'page') ORDER BY ID DESC";
$res = $orion_db->query($sql);

if ($res) {
    while ($row = $res->fetch_object()) {
        $id = (int) $row->ID;
        $loc = ($row->post_type === 'page') ? ($base . '/?page_id=' . $id) : ($base . '/?p=' . $id);
        $last = isset($row->post_modified) && $row->post_modified ? (string) $row->post_modified : (string) $row->post_date;
        $lastmod = '';
        if ($last) {
            $ts = strtotime($last);
            if ($ts) $lastmod = date('Y-m-d', $ts);
        }

        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') . "</loc>\n";
        if ($lastmod !== '') {
            echo "    <lastmod>" . htmlspecialchars($lastmod, ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
        }
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>\n";
    }
}

echo "</urlset>\n";

