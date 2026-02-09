<?php
/**
 * Print Member Card
 */

// Bootstrap Orion Core
$bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
if (file_exists($bootstrap_path)) {
    require_once $bootstrap_path;
} else {
    die("Orion CMS Core not found.");
}

// Auth Check
if (!function_exists('is_user_logged_in') || !is_user_logged_in() || !current_user_can('administrator')) {
    die("Access Denied");
}

$member_ids = [];
if (isset($_GET['member_ids'])) {
    $member_ids = array_map('intval', explode(',', $_GET['member_ids']));
} elseif (isset($_GET['member_id'])) {
    $member_ids = [(int)$_GET['member_id']];
}

if (empty($member_ids)) {
    die("Member not found.");
}

// Get Card Settings
$card_logo = get_option('member_card_logo', '');
$card_bg_color = get_option('member_card_bg_color', '#1e293b'); // Default slate-800
$card_text_color = get_option('member_card_text_color', '#ffffff');

// Fallback Logo
if (!$card_logo) {
    // Use site logo or text
    $card_logo = ''; // Will render site name text if empty
} else {
    if (strpos($card_logo, 'http') !== 0) {
        $card_logo = site_url('/' . ltrim($card_logo, '/'));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Anggota</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
            gap: 20px;
        }

        .card-container {
            width: 85.6mm;
            height: 53.98mm;
            background-color: <?php echo $card_bg_color; ?>;
            color: <?php echo $card_text_color; ?>;
            border-radius: 4mm;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: row;
            flex-shrink: 0;
            page-break-after: always;
        }

        .card-container:last-child {
            page-break-after: auto;
        }

        .card-left {
            width: 35%;
            background-color: rgba(255,255,255,0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            border-radius: 0;
            object-fit: cover;
            border: none;
        }

        .card-right {
            width: 65%;
            padding: 5mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-header {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .logo {
            height: 8mm;
            object-fit: contain;
        }

        .org-name {
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .member-name {
            font-size: 11pt;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .member-position {
            font-size: 7pt;
            opacity: 0.8;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }

        .member-details {
            font-size: 6pt;
            opacity: 0.9;
        }

        .member-details div {
            margin-bottom: 1mm;
        }

        .member-number {
            font-family: monospace;
            font-size: 8pt;
            letter-spacing: 1px;
            background: rgba(0,0,0,0.2);
            padding: 1mm 2mm;
            border-radius: 1mm;
            display: inline-block;
            margin-top: auto;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn:hover {
            background: #1d4ed8;
        }

        @media print {
            body {
                background: none;
                display: block;
            }
            .card-container {
                box-shadow: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                page-break-inside: avoid;
            }
            .no-print {
                display: none;
            }
            @page {
                size: 85.6mm 53.98mm; /* ID Card Size */
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak Kartu (PDF)</button>
    </div>

    <?php
    foreach ($member_ids as $m_id):
        $member = get_post($m_id);
        if (!$member || $member->post_type !== 'member') continue;

        // Get Meta
        $member_number = get_post_meta($m_id, 'member_number', true);
        $position = get_post_meta($m_id, 'member_position', true);
        $photo_url = get_post_meta($m_id, 'member_photo', true);
        $email = get_post_meta($m_id, 'member_email', true);
        $phone = get_post_meta($m_id, 'member_phone', true);
        $address = get_post_meta($m_id, 'member_address', true);

        // Fallback Photo
        if (!$photo_url) {
            $photo_url = 'https://ui-avatars.com/api/?name=' . urlencode($member->post_title) . '&background=random&size=200';
        } else {
            // Make URL absolute if relative
            if (strpos($photo_url, 'http') !== 0) {
                $photo_url = site_url('/' . ltrim($photo_url, '/'));
            }
        }
    ?>
    <div class="card-container">
        <div class="card-left">
            <img src="<?php echo $photo_url; ?>" alt="Profile" class="profile-photo">
        </div>
        <div class="card-right">
            <div class="card-header">
                <?php if ($card_logo): ?>
                    <img src="<?php echo $card_logo; ?>" class="logo" alt="Logo">
                <?php else: ?>
                    <div class="org-name"><?php echo get_option('blogname', 'Orion CMS'); ?></div>
                <?php endif; ?>
            </div>
            
            <div>
                <h2 class="member-name"><?php echo htmlspecialchars($member->post_title); ?></h2>
                <div class="member-position"><?php echo $position ? htmlspecialchars($position) : 'Member'; ?></div>
                
                <div class="member-details">
                    <div>Join: <?php echo date('d M Y', strtotime($member->post_date)); ?></div>
                    <?php if ($address): ?>
                    <div style="font-size: 5pt; line-height: 1.1; margin-bottom: 0.5mm;"><?php echo htmlspecialchars($address); ?></div>
                    <?php endif; ?>
                    <?php if ($phone): ?>
                    <div><?php echo htmlspecialchars($phone); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="member-number">
                <?php echo $member_number ? $member_number : 'NO ID'; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</body>
</html>
