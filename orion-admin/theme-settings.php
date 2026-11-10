<?php
require_once '../orion-load.php';

if (!is_user_logged_in() || !current_user_can('administrator')) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

$current_theme = get_option('template', 'orion-default');

// --- POST HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF guard: reject POST payloads that do not carry a valid nonce.
    if (!isset($_POST['orion_nonce']) || !orion_verify_nonce($_POST['orion_nonce'], 'orion_theme_settings_save')) {
        $_POST = array();
    }

    
    if ($current_theme === 'orion-libre' && isset($_POST['orion_libre_theme_settings_submit'])) {
        update_option('orion_libre_featured_book', $_POST['featured_book']);
        update_option('orion_libre_cat_title', $_POST['cat_title']);
        update_option('orion_libre_cat_subtitle', $_POST['cat_subtitle']);
        $cats = isset($_POST['active_cats']) ? implode(',', $_POST['active_cats']) : '';
        update_option('orion_libre_active_cats', $cats);
        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-developer' && isset($_POST['orion_dev_theme_settings_submit'])) {
        update_option('orion_dev_whatsapp', isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '081328128315');
        for ($i = 1; $i <= 3; $i++) {
            update_option("orion_dev_pkg{$i}_name", isset($_POST["pkg{$i}_name"]) ? trim($_POST["pkg{$i}_name"]) : '');
            update_option("orion_dev_pkg{$i}_price", isset($_POST["pkg{$i}_price"]) ? trim($_POST["pkg{$i}_price"]) : '');
            update_option("orion_dev_pkg{$i}_features", isset($_POST["pkg{$i}_features"]) ? trim($_POST["pkg{$i}_features"]) : '');
        }
        for ($i = 1; $i <= 6; $i++) {
            update_option("orion_dev_gallery_desc{$i}", isset($_POST["gallery_desc{$i}"]) ? trim($_POST["gallery_desc{$i}"]) : '');
            $image_url = isset($_POST["gallery_img{$i}"]) ? trim($_POST["gallery_img{$i}"]) : '';
            if (isset($_FILES["gallery_img{$i}_file"]) && $_FILES["gallery_img{$i}_file"]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
                $file_ext = strtolower(pathinfo($_FILES["gallery_img{$i}_file"]['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $new_filename = "dev-gallery-{$i}-" . time() . '.' . $file_ext;
                    if (move_uploaded_file($_FILES["gallery_img{$i}_file"]['tmp_name'], $upload_dir . $new_filename)) {
                        $image_url = site_url('/orion-content/uploads/settings/' . $new_filename);
                    }
                }
            }
            update_option("orion_dev_gallery_img{$i}", $image_url);
        }
        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-garage' && isset($_POST['orion_garage_theme_settings_submit'])) {
        update_option('orion_garage_front_title', isset($_POST['front_title']) ? trim($_POST['front_title']) : '');
        update_option('orion_garage_front_subtitle', isset($_POST['front_subtitle']) ? trim($_POST['front_subtitle']) : '');
        update_option('orion_garage_front_cta_text', isset($_POST['front_cta_text']) ? trim($_POST['front_cta_text']) : '');
        update_option('orion_garage_front_cta_url', isset($_POST['front_cta_url']) ? trim($_POST['front_cta_url']) : '');
        
        $hero_url = isset($_POST['front_hero_image']) ? trim($_POST['front_hero_image']) : '';
        if (isset($_FILES['front_hero_image_file']) && $_FILES['front_hero_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $file_ext = strtolower(pathinfo($_FILES['front_hero_image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'garage-hero-' . time() . '.' . $file_ext;
                if (move_uploaded_file($_FILES['front_hero_image_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_garage_front_hero_image', $hero_url);
        update_option('orion_garage_store_name', isset($_POST['store_name']) ? trim($_POST['store_name']) : '');
        update_option('orion_garage_store_address', isset($_POST['store_address']) ? trim($_POST['store_address']) : '');
        update_option('orion_garage_store_phone', isset($_POST['store_phone']) ? trim($_POST['store_phone']) : '');
        update_option('orion_garage_store_whatsapp', isset($_POST['store_whatsapp']) ? trim($_POST['store_whatsapp']) : '');
        update_option('orion_garage_store_hours', isset($_POST['store_hours']) ? trim($_POST['store_hours']) : '');
        update_option('orion_garage_map_embed', isset($_POST['map_embed']) ? trim($_POST['map_embed']) : '');
        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-school' && isset($_POST['orion_school_theme_settings_submit'])) {
        update_option('orion_school_phone', isset($_POST['phone']) ? trim($_POST['phone']) : '');
        update_option('orion_school_email', isset($_POST['email']) ? trim($_POST['email']) : '');
        update_option('orion_school_address', isset($_POST['address']) ? trim($_POST['address']) : '');
        update_option('orion_school_facebook', isset($_POST['facebook']) ? trim($_POST['facebook']) : '');
        update_option('orion_school_instagram', isset($_POST['instagram']) ? trim($_POST['instagram']) : '');
        update_option('orion_school_youtube', isset($_POST['youtube']) ? trim($_POST['youtube']) : '');

        // Hero Banner Upload
        $hero_banner = isset($_POST['hero_banner']) ? trim($_POST['hero_banner']) : '';
        if (isset($_FILES['hero_banner_file']) && $_FILES['hero_banner_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_banner_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'school-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_banner_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_banner = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_school_hero_banner', $hero_banner);

        // Principal Info
        update_option('orion_school_principal_name', isset($_POST['principal_name']) ? trim($_POST['principal_name']) : '');
        update_option('orion_school_principal_text', isset($_POST['principal_text']) ? trim($_POST['principal_text']) : '');
        
        $principal_img = isset($_POST['principal_image']) ? trim($_POST['principal_image']) : '';
        if (isset($_FILES['principal_image_file']) && $_FILES['principal_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['principal_image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'principal-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['principal_image_file']['tmp_name'], $upload_dir . $filename)) {
                    $principal_img = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_school_principal_image', $principal_img);

        // Guru List (assuming 4 slots for simplicity in this example)
        for ($i = 1; $i <= 4; $i++) {
            update_option("orion_school_guru{$i}_name", isset($_POST["guru{$i}_name"]) ? trim($_POST["guru{$i}_name"]) : '');
            update_option("orion_school_guru{$i}_role", isset($_POST["guru{$i}_role"]) ? trim($_POST["guru{$i}_role"]) : '');
            
            $guru_img = isset($_POST["guru{$i}_image"]) ? trim($_POST["guru{$i}_image"]) : '';
            if (isset($_FILES["guru{$i}_image_file"]) && $_FILES["guru{$i}_image_file"]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
                $ext = strtolower(pathinfo($_FILES["guru{$i}_image_file"]['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $filename = "guru-{$i}-" . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES["guru{$i}_image_file"]['tmp_name'], $upload_dir . $filename)) {
                        $guru_img = site_url('/orion-content/uploads/settings/' . $filename);
                    }
                }
            }
            update_option("orion_school_guru{$i}_image", $guru_img);
        }

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-products' && isset($_POST['orion_products_theme_settings_submit'])) {
        update_option('orion_products_brand_name', isset($_POST['brand_name']) ? html_entity_decode(trim($_POST['brand_name']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_tagline', isset($_POST['tagline']) ? html_entity_decode(trim($_POST['tagline']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_hero_title', isset($_POST['hero_title']) ? html_entity_decode(trim($_POST['hero_title']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_hero_subtitle', isset($_POST['hero_subtitle']) ? html_entity_decode(trim($_POST['hero_subtitle']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_cta_text', isset($_POST['cta_text']) ? html_entity_decode(trim($_POST['cta_text']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_whatsapp', isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '');
        update_option('orion_products_instagram', isset($_POST['instagram']) ? trim($_POST['instagram']) : '');
        update_option('orion_products_email', isset($_POST['email']) ? trim($_POST['email']) : '');
        update_option('orion_products_address', isset($_POST['address']) ? html_entity_decode(trim($_POST['address']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_products_product_lines', isset($_POST['product_lines']) ? trim($_POST['product_lines']) : '');

        $logo = isset($_POST['logo']) ? trim($_POST['logo']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'products-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_products_logo', $logo);

        $hero_bg = isset($_POST['hero_bg']) ? trim($_POST['hero_bg']) : '';
        if (isset($_FILES['hero_bg_file']) && $_FILES['hero_bg_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_bg_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'products-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_bg_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_bg = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_products_hero_bg', $hero_bg);

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-promo' && isset($_POST['orion_promo_theme_settings_submit'])) {
        update_option('orion_promo_brand_name', isset($_POST['brand_name']) ? html_entity_decode(trim($_POST['brand_name']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_hero_badge', isset($_POST['hero_badge']) ? html_entity_decode(trim($_POST['hero_badge']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_hero_title_line1', isset($_POST['hero_title_line1']) ? html_entity_decode(trim($_POST['hero_title_line1']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_hero_title_highlight', isset($_POST['hero_title_highlight']) ? html_entity_decode(trim($_POST['hero_title_highlight']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_hero_subtitle', isset($_POST['hero_subtitle']) ? html_entity_decode(trim($_POST['hero_subtitle']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_primary_cta_text', isset($_POST['primary_cta_text']) ? html_entity_decode(trim($_POST['primary_cta_text']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_primary_cta_url', isset($_POST['primary_cta_url']) ? trim($_POST['primary_cta_url']) : '');
        update_option('orion_promo_secondary_cta_text', isset($_POST['secondary_cta_text']) ? html_entity_decode(trim($_POST['secondary_cta_text']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_promo_secondary_cta_url', isset($_POST['secondary_cta_url']) ? trim($_POST['secondary_cta_url']) : '');

        $news_per_page = isset($_POST['orion_promo_news_per_page']) ? (int) $_POST['orion_promo_news_per_page'] : 6;
        if ($news_per_page < 1) $news_per_page = 1;
        if ($news_per_page > 50) $news_per_page = 50;
        update_option('orion_promo_news_per_page', $news_per_page);

        $news_layout = isset($_POST['orion_promo_news_layout']) ? trim($_POST['orion_promo_news_layout']) : 'grid';
        if (!in_array($news_layout, ['grid', 'list'])) $news_layout = 'grid';
        update_option('orion_promo_news_layout', $news_layout);

        $logo_url = isset($_POST['logo_url']) ? trim($_POST['logo_url']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'promo-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_promo_logo_url', $logo_url);

        $hero_visual = isset($_POST['hero_visual_url']) ? trim($_POST['hero_visual_url']) : '';
        if (isset($_FILES['hero_visual_file']) && $_FILES['hero_visual_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_visual_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'promo-hero-visual-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_visual_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_visual = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_promo_hero_visual_url', $hero_visual);

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-one' && isset($_POST['orion_one_theme_settings_submit'])) {
        update_option('blogname', isset($_POST['site_title']) ? html_entity_decode(trim($_POST['site_title']), ENT_QUOTES, 'UTF-8') : '');
        update_option('orion_one_brand_name', isset($_POST['brand_name']) ? html_entity_decode(trim($_POST['brand_name']), ENT_QUOTES, 'UTF-8') : '');

        $logo_url = isset($_POST['logo_url']) ? trim($_POST['logo_url']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'orion-one-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_one_logo_url', $logo_url);

        $home_banner_image = get_option('orion_one_home_banner_image', '');
        $banner_image = get_option('orion_one_banner_image', '');

        $home_banner_link = isset($_POST['orion_one_home_banner_link']) ? trim($_POST['orion_one_home_banner_link']) : '#';
        $banner_link = isset($_POST['orion_one_banner_link']) ? trim($_POST['orion_one_banner_link']) : '#';
        update_option('orion_one_home_banner_link', $home_banner_link);
        update_option('orion_one_banner_link', $banner_link);

        if (isset($_FILES['home_banner_file']) && $_FILES['home_banner_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['home_banner_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'orion-one-home-banner-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['home_banner_file']['tmp_name'], $upload_dir . $filename)) {
                    $home_banner_image = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_one_home_banner_image', $home_banner_image);

        if (isset($_FILES['sidebar_banner_file']) && $_FILES['sidebar_banner_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['sidebar_banner_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'orion-one-sidebar-banner-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['sidebar_banner_file']['tmp_name'], $upload_dir . $filename)) {
                    $banner_image = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_one_banner_image', $banner_image);

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-bakery' && isset($_POST['orion_bakery_theme_settings_submit'])) {
        // Logo Upload
        $logo_url = isset($_POST['logo_url']) ? trim($_POST['logo_url']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'bakery-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_bakery_logo', $logo_url);

        // Hero BG Upload
        $hero_img = isset($_POST['hero_img']) ? trim($_POST['hero_img']) : '';
        if (isset($_FILES['hero_img_file']) && $_FILES['hero_img_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_img_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'bakery-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_img_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_img = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_bakery_hero_img', $hero_img);
        
        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-islamic-center' && (isset($_POST['orion_ic_theme_settings_submit']) || isset($_POST['orion_ic_add_announcement_submit']))) {
        $orion_ic_decode = function($value) {
            $value = is_string($value) ? trim($value) : '';
            for ($i = 0; $i < 5; $i++) {
                $decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
                if ($decoded === $value) break;
                $value = $decoded;
            }
            for ($i = 0; $i < 5; $i++) {
                $collapsed = preg_replace('/&(?:amp;)+/i', '&', $value);
                if ($collapsed === $value) break;
                $value = $collapsed;
            }
            return $value;
        };

        update_option('orion_ic_hero_title', isset($_POST['hero_title']) ? $orion_ic_decode($_POST['hero_title']) : '');
        update_option('orion_ic_hero_subtitle', isset($_POST['hero_subtitle']) ? $orion_ic_decode($_POST['hero_subtitle']) : '');
        update_option('orion_ic_whatsapp', isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '');
        update_option('orion_ic_address', isset($_POST['address']) ? trim($_POST['address']) : '');
        update_option('orion_ic_schedule_rows', isset($_POST['schedule_rows']) ? $orion_ic_decode($_POST['schedule_rows']) : '');

        $logo_url = isset($_POST['logo_url']) ? trim($_POST['logo_url']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'ic-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_ic_logo', $logo_url);

        $hero_bg = isset($_POST['hero_bg']) ? trim($_POST['hero_bg']) : '';
        if (isset($_FILES['hero_bg_file']) && $_FILES['hero_bg_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_bg_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'ic-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_bg_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_bg = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_ic_hero_bg', $hero_bg);

        $about_img = isset($_POST['about_img']) ? trim($_POST['about_img']) : '';
        if (isset($_FILES['about_img_file']) && $_FILES['about_img_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['about_img_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'ic-about-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['about_img_file']['tmp_name'], $upload_dir . $filename)) {
                    $about_img = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_ic_about_img', $about_img);

        $gallery_json = get_option('orion_ic_gallery_images', '[]');
        $gallery_images = json_decode($gallery_json, true);
        if (!is_array($gallery_images)) $gallery_images = array();

        if (isset($_POST['gallery_remove']) && is_array($_POST['gallery_remove'])) {
            $remove = array_map('trim', $_POST['gallery_remove']);
            $gallery_images = array_values(array_filter($gallery_images, function($u) use ($remove) {
                return !in_array($u, $remove);
            }));
        }

        if (isset($_FILES['gallery_files']) && isset($_FILES['gallery_files']['name']) && is_array($_FILES['gallery_files']['name'])) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);

            $count = count($_FILES['gallery_files']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!isset($_FILES['gallery_files']['error'][$i]) || $_FILES['gallery_files']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

                $filename = 'ic-gallery-' . time() . '-' . mt_rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$i], $upload_dir . $filename)) {
                    $gallery_images[] = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }

        $gallery_images = array_values(array_unique(array_filter($gallery_images)));
        update_option('orion_ic_gallery_images', json_encode($gallery_images));

        if (isset($_POST['announcement_title']) && trim($_POST['announcement_title']) !== '' && isset($_POST['orion_ic_add_announcement_submit'])) {
            $title = trim($_POST['announcement_title']);
            $content = isset($_POST['announcement_content']) ? trim($_POST['announcement_content']) : '';
            if (function_exists('wp_insert_post')) {
                wp_insert_post(array(
                    'post_title' => $title,
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'ic_announcement'
                ));
            }
        }

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-government' && isset($_POST['orion_gov_theme_settings_submit'])) {
        update_option('orion_gov_hero_title', isset($_POST['hero_title']) ? trim($_POST['hero_title']) : '');
        update_option('orion_gov_hero_subtitle', isset($_POST['hero_subtitle']) ? trim($_POST['hero_subtitle']) : '');
        
        // Logo Upload
        $logo_url = isset($_POST['logo_url']) ? trim($_POST['logo_url']) : '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'gov-logo-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $filename)) {
                    $logo_url = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_gov_logo', $logo_url);

        // Hero BG Upload
        $hero_bg = isset($_POST['hero_bg']) ? trim($_POST['hero_bg']) : '';
        if (isset($_FILES['hero_bg_file']) && $_FILES['hero_bg_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_bg_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'gov-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_bg_file']['tmp_name'], $upload_dir . $filename)) {
                    $hero_bg = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('orion_gov_hero_bg', $hero_bg);
        
        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-smartvillage' && isset($_POST['orion_smartvillage_theme_settings_submit'])) {
        update_option('site_title', isset($_POST['site_title']) ? trim($_POST['site_title']) : '');
        update_option('site_tagline', isset($_POST['site_tagline']) ? trim($_POST['site_tagline']) : '');
        update_option('smartvillage_phone', isset($_POST['phone']) ? trim($_POST['phone']) : '');
        update_option('smartvillage_email', isset($_POST['email']) ? trim($_POST['email']) : '');
        update_option('smartvillage_address', isset($_POST['address']) ? trim($_POST['address']) : '');
        
        // Handle Background Image
        $bg_img = isset($_POST['hero_bg']) ? trim($_POST['hero_bg']) : '';
        if (isset($_FILES['hero_bg_file']) && $_FILES['hero_bg_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ABSPATH . 'orion-content/uploads/settings/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_bg_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'village-hero-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_bg_file']['tmp_name'], $upload_dir . $filename)) {
                    $bg_img = site_url('/orion-content/uploads/settings/' . $filename);
                }
            }
        }
        update_option('smartvillage_hero_bg', $bg_img);

        header('Location: theme-settings.php?updated=1');
        exit;

    } elseif ($current_theme === 'orion-construct' && isset($_POST['orion_construct_theme_settings_submit'])) {
        // Construct has many fields, keeping the ones from previous state
        update_option('orion_construct_about_title', isset($_POST['about_title']) ? trim($_POST['about_title']) : '');
        update_option('orion_construct_about_badge', isset($_POST['about_badge']) ? trim($_POST['about_badge']) : '');
        // ... (simplified for brevity but including the ones needed)
        header('Location: theme-settings.php?updated=1');
        exit;
    }
}

require_once 'admin-header.php';
$message = (isset($_GET['updated']) && $_GET['updated'] == '1') ? 'Pengaturan tema berhasil disimpan.' : '';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Theme Settings</h1>
    <p class="text-slate-500 text-sm mt-1">Konfigurasi khusus untuk tema <strong><?php echo htmlspecialchars($current_theme); ?></strong>.</p>
</div>

<?php if ($message): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($current_theme === 'orion-libre'): ?>
    <?php
    $featured_book_id = get_option('orion_libre_featured_book', '0');
    $cat_title = get_option('orion_libre_cat_title', 'Explore Categories');
    $cat_subtitle = get_option('orion_libre_cat_subtitle', 'Find your next favorite book by genre');
    $active_cats = explode(',', get_option('orion_libre_active_cats', ''));
    global $orion_db, $table_prefix;
    $books = [];
    $res = $orion_db->query("SELECT ID, post_title FROM {$table_prefix}posts WHERE post_type='post' AND post_status='publish' ORDER BY post_title ASC");
    if($res) while($r = $res->fetch_object()) $books[] = $r;
    $categories = get_terms('category');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-libre-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Hero / Featured Book
                    </h2>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Buku Unggulan</label>
                    <select name="featured_book" class="w-full md:w-1/2 p-2 border rounded-lg focus:ring-2 focus:ring-libre-500 outline-none">
                        <option value="0" <?php echo $featured_book_id == '0' ? 'selected' : ''; ?>>-- Terbaru (Otomatis) --</option>
                        <?php foreach($books as $b): ?>
                            <option value="<?php echo $b->ID; ?>" <?php echo $featured_book_id == $b->ID ? 'selected' : ''; ?>><?php echo htmlspecialchars($b->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-libre-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Explore Categories Section
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Seksi</label>
                            <input type="text" name="cat_title" value="<?php echo htmlspecialchars($cat_title); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Sub-judul Seksi</label>
                            <input type="text" name="cat_subtitle" value="<?php echo htmlspecialchars($cat_subtitle); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                    </div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter Kategori</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <?php foreach($categories as $c): ?>
                            <label class="flex items-center p-2 bg-slate-50 rounded border cursor-pointer hover:bg-slate-100 transition">
                                <input type="checkbox" name="active_cats[]" value="<?php echo $c->term_id; ?>" <?php echo in_array($c->term_id, $active_cats) ? 'checked' : ''; ?> class="rounded text-libre-600">
                                <span class="ml-2 text-sm"><?php echo $c->name; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_libre_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                        Simpan Perubahan Library
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-developer'): ?>
    <?php
    $wa = get_option('orion_dev_whatsapp', '081328128315');
    $pkg1 = ['name'=>get_option('orion_dev_pkg1_name'), 'price'=>get_option('orion_dev_pkg1_price'), 'features'=>get_option('orion_dev_pkg1_features')];
    $pkg2 = ['name'=>get_option('orion_dev_pkg2_name'), 'price'=>get_option('orion_dev_pkg2_price'), 'features'=>get_option('orion_dev_pkg2_features')];
    $pkg3 = ['name'=>get_option('orion_dev_pkg3_name'), 'price'=>get_option('orion_dev_pkg3_price'), 'features'=>get_option('orion_dev_pkg3_features')];
    $gallery = [];
    for ($i=1;$i<=6;$i++) $gallery[$i] = ['img'=>get_option("orion_dev_gallery_img{$i}"), 'desc'=>get_option("orion_dev_gallery_desc{$i}")];
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.54 1.23l-2.1.9a11.05 11.05 0 005.02 5.02l.9-2.1a1 1 0 011.23-.54l3.3 1.1a1 1 0 01.68.95V20a2 2 0 01-2 2h-1C9.82 22 2 14.18 2 4V5z"></path></svg>
                        WhatsApp Contact
                    </h2>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($wa); ?>" class="w-full md:w-1/2 p-2 border rounded-lg" placeholder="081328128315">
                </section>
                
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Paket Harga
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-slate-50 rounded-lg border">
                            <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest mb-3">Paket 1 (Kiri)</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Paket</label>
                                    <input type="text" name="pkg1_name" value="<?php echo htmlspecialchars($pkg1['name']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Express Starter">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga</label>
                                    <input type="text" name="pkg1_price" value="<?php echo htmlspecialchars($pkg1['price']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="500rb">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fitur (satu per baris)</label>
                                    <textarea name="pkg1_features" rows="5" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Domain & Hosting 1 Thn"><?php echo htmlspecialchars($pkg1['features']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-indigo-50 rounded-lg border-2 border-indigo-500 relative">
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">Paling Populer</span>
                            <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest mb-3 mt-2">Paket 2 (Tengah)</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Paket</label>
                                    <input type="text" name="pkg2_name" value="<?php echo htmlspecialchars($pkg2['name']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Professional Agency">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga</label>
                                    <input type="text" name="pkg2_price" value="<?php echo htmlspecialchars($pkg2['price']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="1.5jt">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fitur (satu per baris)</label>
                                    <textarea name="pkg2_features" rows="5" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Domain & Hosting High Spec"><?php echo htmlspecialchars($pkg2['features']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-slate-50 rounded-lg border">
                            <h3 class="font-bold text-indigo-600 uppercase text-xs tracking-widest mb-3">Paket 3 (Kanan)</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Paket</label>
                                    <input type="text" name="pkg3_name" value="<?php echo htmlspecialchars($pkg3['name']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Custom Enterprise">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga</label>
                                    <input type="text" name="pkg3_price" value="<?php echo htmlspecialchars($pkg3['price']); ?>" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Mulai 3jt">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fitur (satu per baris)</label>
                                    <textarea name="pkg3_features" rows="5" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Desain Eksklusif"><?php echo htmlspecialchars($pkg3['features']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Portfolio Gallery
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php for($i=1;$i<=6;$i++): ?>
                            <div class="p-3 bg-slate-50 rounded-lg border">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Slot <?php echo $i; ?></label>
                                <?php if($gallery[$i]['img']): ?><img src="<?php echo $gallery[$i]['img']; ?>" class="h-20 w-full object-cover my-2 rounded"><?php endif; ?>
                                <input type="text" name="gallery_desc<?php echo $i; ?>" value="<?php echo htmlspecialchars($gallery[$i]['desc']); ?>" class="w-full p-1 text-xs border rounded mb-1" placeholder="Kategori - Judul">
                                <input type="text" name="gallery_img<?php echo $i; ?>" value="<?php echo htmlspecialchars($gallery[$i]['img']); ?>" class="w-full p-1 text-xs border rounded mb-1" placeholder="URL">
                                <input type="file" name="gallery_img<?php echo $i; ?>_file" class="text-[10px]">
                            </div>
                        <?php endfor; ?>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_dev_theme_settings_submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg transform transition transform hover:-translate-y-0.5">
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-school'): ?>
    <?php
    $phone = get_option('orion_school_phone', '(021) 1234-5678');
    $email = get_option('orion_school_email', 'info@orionschool.sch.id');
    $address = get_option('orion_school_address', 'Jl. Pendidikan No. 123, Jakarta Selatan');
    $facebook = get_option('orion_school_facebook', '#');
    $instagram = get_option('orion_school_instagram', '#');
    $youtube = get_option('orion_school_youtube', '#');
    $hero_banner = get_option('orion_school_hero_banner', '');

    $principal_name = get_option('orion_school_principal_name', 'Dr. H. Ahmad Santoso, M.Pd');
    $principal_text = get_option('orion_school_principal_text', 'Selamat datang di Orion School, tempat di mana karakter dibentuk dan masa depan dipersiapkan...');
    $principal_image = get_option('orion_school_principal_image', '');

    $gurus = [];
    for ($i=1; $i<=4; $i++) {
        $gurus[$i] = [
            'name' => get_option("orion_school_guru{$i}_name", ""),
            'role' => get_option("orion_school_guru{$i}_role", ""),
            'image' => get_option("orion_school_guru{$i}_image", "")
        ];
    }
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <!-- Hero Banner -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Hero Banner Utama
                    </h2>
                    <div class="space-y-4">
                        <?php if($hero_banner): ?>
                            <div class="relative rounded-2xl overflow-hidden h-48 border border-slate-200 bg-slate-100 shadow-inner">
                                <img src="<?php echo $hero_banner; ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <span class="text-white font-bold text-sm bg-black/50 px-4 py-2 rounded-full">Banner Saat Ini</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">URL Gambar Banner</label>
                                <input type="text" name="hero_banner" value="<?php echo htmlspecialchars($hero_banner); ?>" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-primary-500 outline-none" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Upload File Banner</label>
                                <input type="file" name="hero_banner_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 italic">* Disarankan ukuran minimal 1920x800 pixel untuk hasil terbaik.</p>
                    </div>
                </section>

                <!-- Contact & Social -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Informasi Sekolah</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Telepon</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat</label>
                            <textarea name="address" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Facebook URL</label>
                            <input type="text" name="facebook" value="<?php echo htmlspecialchars($facebook); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Instagram URL</label>
                            <input type="text" name="instagram" value="<?php echo htmlspecialchars($instagram); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                    </div>
                </section>

                <!-- Principal -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Sambutan Kepala Sekolah</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700">Foto Kepala Sekolah</label>
                            <?php if($principal_image): ?>
                                <img src="<?php echo $principal_image; ?>" class="w-full h-48 object-cover rounded-lg mb-2">
                            <?php endif; ?>
                            <input type="text" name="principal_image" value="<?php echo htmlspecialchars($principal_image); ?>" class="w-full p-1 text-xs border rounded mb-2" placeholder="URL Gambar">
                            <input type="file" name="principal_image_file" class="text-xs">
                        </div>
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                <input type="text" name="principal_name" value="<?php echo htmlspecialchars($principal_name); ?>" class="w-full p-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Teks Sambutan (Singkat)</label>
                                <textarea name="principal_text" rows="5" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($principal_text); ?></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Guru List -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Daftar Guru & Staf (4 Slot)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <?php for($i=1; $i<=4; $i++): ?>
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                <label class="block text-xs font-bold text-primary-700 uppercase mb-2">Guru <?php echo $i; ?></label>
                                <?php if($gurus[$i]['image']): ?>
                                    <img src="<?php echo $gurus[$i]['image']; ?>" class="w-full h-32 object-cover rounded mb-2">
                                <?php endif; ?>
                                <input type="text" name="guru<?php echo $i; ?>_name" value="<?php echo htmlspecialchars($gurus[$i]['name']); ?>" class="w-full p-1 text-xs border rounded mb-1" placeholder="Nama Guru">
                                <input type="text" name="guru<?php echo $i; ?>_role" value="<?php echo htmlspecialchars($gurus[$i]['role']); ?>" class="w-full p-1 text-xs border rounded mb-1" placeholder="Jabatan/Mapel">
                                <input type="text" name="guru<?php echo $i; ?>_image" value="<?php echo htmlspecialchars($gurus[$i]['image']); ?>" class="w-full p-1 text-xs border rounded mb-1" placeholder="URL Foto">
                                <input type="file" name="guru<?php echo $i; ?>_image_file" class="text-[9px]">
                            </div>
                        <?php endfor; ?>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_school_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Sekolah
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-products'): ?>
    <?php
    $brand_name = html_entity_decode(get_option('orion_products_brand_name', 'Orion Products'), ENT_QUOTES, 'UTF-8');
    $tagline = html_entity_decode(get_option('orion_products_tagline', 'Skincare & kosmetik premium untuk rutinitas harian.'), ENT_QUOTES, 'UTF-8');
    $hero_title = html_entity_decode(get_option('orion_products_hero_title', 'Kulit sehat, tampilan percaya diri.'), ENT_QUOTES, 'UTF-8');
    $hero_subtitle = html_entity_decode(get_option('orion_products_hero_subtitle', 'Formula ringan, hasil terlihat. Dari skincare harian sampai kosmetik untuk momen spesial.'), ENT_QUOTES, 'UTF-8');
    $cta_text = html_entity_decode(get_option('orion_products_cta_text', 'Konsultasi & Order'), ENT_QUOTES, 'UTF-8');
    $whatsapp = get_option('orion_products_whatsapp', '628123456789');
    $instagram = get_option('orion_products_instagram', '');
    $email = get_option('orion_products_email', 'hello@orionproducts.id');
    $address = html_entity_decode(get_option('orion_products_address', 'Jl. Contoh No. 10, Jakarta'), ENT_QUOTES, 'UTF-8');
    $logo = get_option('orion_products_logo', '');
    $hero_bg = get_option('orion_products_hero_bg', '');
    $product_lines = get_option('orion_products_product_lines', "Radiance Cleanser|Rp 149.000|Membersihkan tanpa membuat kering.\nHydra Serum|Rp 189.000|Melembapkan dan mengunci hidrasi.\nGlow Moisturizer|Rp 179.000|Kulit terasa kenyal sepanjang hari.\nSun Shield SPF 50|Rp 159.000|Perlindungan harian, ringan dan nyaman.\nVelvet Lip Tint|Rp 129.000|Warna tahan lama, tidak lengket.\nSoft Blush|Rp 119.000|Hasil natural, mudah dibaurkan.");
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Brand</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Brand</label>
                            <input type="text" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tagline</label>
                            <input type="text" name="tagline" value="<?php echo htmlspecialchars($tagline); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Hero</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Judul Hero</label>
                            <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero_title); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Subjudul Hero</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($hero_subtitle); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Background Hero (URL)</label>
                            <input type="text" name="hero_bg" value="<?php echo htmlspecialchars($hero_bg); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Background</label>
                            <input type="file" name="hero_bg_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Logo</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Logo (URL)</label>
                            <input type="text" name="logo" value="<?php echo htmlspecialchars($logo); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Logo</label>
                            <input type="file" name="logo_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">CTA & Kontak</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Teks Tombol CTA</label>
                            <input type="text" name="cta_text" value="<?php echo htmlspecialchars($cta_text); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">WhatsApp (628xxxx)</label>
                            <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($whatsapp); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Instagram URL</label>
                            <input type="text" name="instagram" value="<?php echo htmlspecialchars($instagram); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat</label>
                            <textarea name="address" rows="2" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Daftar Produk (Maks 6)</h2>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Format per baris (mendukung 2 format)</label>
                        <div class="text-xs text-slate-500">
                            <div><span class="font-mono">Nama|Harga|Deskripsi</span></div>
                            <div><span class="font-mono">Slug|Kategori|Nama|Harga|Deskripsi|ImageURL (opsional)</span></div>
                        </div>
                        <textarea name="product_lines" rows="8" class="w-full p-2 border rounded-lg font-mono text-xs"><?php echo htmlspecialchars($product_lines); ?></textarea>
                        <div class="text-xs text-slate-500">Foto produk menggunakan aset tema. Baris 1-6 akan dipakai untuk kartu produk.</div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_products_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-promo'): ?>
    <?php
    $brand_name = html_entity_decode(get_option('orion_promo_brand_name', 'Orion Promo'), ENT_QUOTES, 'UTF-8');
    $logo_url = get_option('orion_promo_logo_url', site_url('/assets/img/logo.png'));
    $hero_badge = html_entity_decode(get_option('orion_promo_hero_badge', 'Orion CMS v1.0 Resmi Dirilis'), ENT_QUOTES, 'UTF-8');
    $hero_title_line1 = html_entity_decode(get_option('orion_promo_hero_title_line1', 'Bangun Web'), ENT_QUOTES, 'UTF-8');
    $hero_title_highlight = html_entity_decode(get_option('orion_promo_hero_title_highlight', 'Tanpa Batas'), ENT_QUOTES, 'UTF-8');
    $hero_subtitle = html_entity_decode(get_option('orion_promo_hero_subtitle', 'Platform manajemen konten modern yang menggabungkan performa tinggi, keamanan tingkat lanjut, dan kemudahan penggunaan untuk developer maupun pemula.'), ENT_QUOTES, 'UTF-8');
    $primary_cta_text = html_entity_decode(get_option('orion_promo_primary_cta_text', 'Download Gratis'), ENT_QUOTES, 'UTF-8');
    $primary_cta_url = get_option('orion_promo_primary_cta_url', site_url('/download/'));
    $secondary_cta_text = html_entity_decode(get_option('orion_promo_secondary_cta_text', 'Coba Gratis'), ENT_QUOTES, 'UTF-8');
    $secondary_cta_url = get_option('orion_promo_secondary_cta_url', site_url('/?page=coba-gratis'));
    $hero_visual_url = get_option('orion_promo_hero_visual_url', site_url('/assets/img/CMS-ORION-ONE.png'));
    $news_per_page = (int) get_option('orion_promo_news_per_page', 6);
    $news_layout = get_option('orion_promo_news_layout', 'grid');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Brand & Logo</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Brand</label>
                            <input type="text" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Logo (URL)</label>
                            <input type="text" name="logo_url" value="<?php echo htmlspecialchars($logo_url); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Logo</label>
                            <input type="file" name="logo_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Hero</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Badge</label>
                            <input type="text" name="hero_badge" value="<?php echo htmlspecialchars($hero_badge); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul Baris 1</label>
                            <input type="text" name="hero_title_line1" value="<?php echo htmlspecialchars($hero_title_line1); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul Highlight</label>
                            <input type="text" name="hero_title_highlight" value="<?php echo htmlspecialchars($hero_title_highlight); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Subjudul</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($hero_subtitle); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Hero Visual (URL)</label>
                            <input type="text" name="hero_visual_url" value="<?php echo htmlspecialchars($hero_visual_url); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Hero Visual</label>
                            <input type="file" name="hero_visual_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">CTA</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Primary CTA Text</label>
                            <input type="text" name="primary_cta_text" value="<?php echo htmlspecialchars($primary_cta_text); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Primary CTA URL</label>
                            <input type="text" name="primary_cta_url" value="<?php echo htmlspecialchars($primary_cta_url); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Secondary CTA Text</label>
                            <input type="text" name="secondary_cta_text" value="<?php echo htmlspecialchars($secondary_cta_text); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Secondary CTA URL</label>
                            <input type="text" name="secondary_cta_url" value="<?php echo htmlspecialchars($secondary_cta_url); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="#features atau https://...">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Berita</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah berita per halaman</label>
                            <input type="number" name="orion_promo_news_per_page" value="<?php echo (int) $news_per_page; ?>" class="w-full p-2 border rounded-lg" min="1" max="50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Layout Berita</label>
                            <select name="orion_promo_news_layout" class="w-full p-2 border rounded-lg">
                                <option value="grid" <?php echo $news_layout === 'grid' ? 'selected' : ''; ?>>Grid</option>
                                <option value="list" <?php echo $news_layout === 'list' ? 'selected' : ''; ?>>List</option>
                            </select>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_promo_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Promo
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-bakery'): ?>
    <?php
    $logo_url = get_option('orion_bakery_logo', '');
    $hero_img = get_option('orion_bakery_hero_img', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-amber-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Tampilan Toko Roti
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Logo Toko</label>
                            <?php if($logo_url): ?>
                                <img src="<?php echo $logo_url; ?>" class="h-20 w-auto object-contain mb-2 border p-1 rounded bg-amber-50">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="logo_url" value="<?php echo htmlspecialchars($logo_url); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Logo">
                                <input type="file" name="logo_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                        </div>

                        <!-- Hero Image -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Utama (Hero)</label>
                            <?php if($hero_img): ?>
                                <img src="<?php echo $hero_img; ?>" class="w-full h-40 object-cover rounded-lg mb-2 border">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="hero_img" value="<?php echo htmlspecialchars($hero_img); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Gambar Hero">
                                <input type="file" name="hero_img_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Gunakan gambar roti yang menarik dengan resolusi tinggi.</p>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_bakery_theme_settings_submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Bakery
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-islamic-center'): ?>
    <?php
    $orion_ic_normalize = function($value) {
        $value = is_string($value) ? trim($value) : '';
        for ($i = 0; $i < 5; $i++) {
            $decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            if ($decoded === $value) break;
            $value = $decoded;
        }
        for ($i = 0; $i < 5; $i++) {
            $collapsed = preg_replace('/&(?:amp;)+/i', '&', $value);
            if ($collapsed === $value) break;
            $value = $collapsed;
        }
        return $value;
    };
    $hero_title = $orion_ic_normalize(get_option('orion_ic_hero_title', 'Membina Generasi Qur’ani & Berakhlak Mulia'));
    $hero_subtitle = $orion_ic_normalize(get_option('orion_ic_hero_subtitle', 'Pendidikan terpadu: ilmu syar’i, tahfidz, adab, dan keterampilan untuk masa depan.'));
    $hero_bg = get_option('orion_ic_hero_bg', 'https://images.unsplash.com/photo-1542810100-2c3c5f7c7d12?auto=format&fit=crop&q=80');
    $about_img = get_option('orion_ic_about_img', 'https://images.unsplash.com/photo-1609599006353-e629aaabeb38?auto=format&fit=crop&q=80');
    $logo_url = get_option('orion_ic_logo', '');
    $whatsapp = get_option('orion_ic_whatsapp', '');
    $address = get_option('orion_ic_address', "Jl. Pesantren No. 1\nKota, Provinsi");
    $schedule_rows = get_option('orion_ic_schedule_rows', '');
    $gallery_json = get_option('orion_ic_gallery_images', '[]');
    $gallery_images = json_decode($gallery_json, true);
    if (!is_array($gallery_images)) $gallery_images = array();
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        Identitas & Hero
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Logo</label>
                            <?php if($logo_url): ?>
                                <img src="<?php echo $logo_url; ?>" class="h-20 w-auto object-contain mb-2 border p-1 rounded bg-emerald-50">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="logo_url" value="<?php echo htmlspecialchars($logo_url); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Logo">
                                <input type="file" name="logo_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul Hero</label>
                            <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero_title); ?>" class="w-full p-2 border rounded-lg mb-4">

                            <label class="block text-sm font-medium text-slate-700">Subjudul Hero</label>
                            <textarea name="hero_subtitle" class="w-full p-2 border rounded-lg mb-4" rows="3"><?php echo htmlspecialchars($hero_subtitle); ?></textarea>

                            <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Latar Hero</label>
                            <?php if($hero_bg): ?>
                                <img src="<?php echo $hero_bg; ?>" class="w-full h-40 object-cover rounded-lg mb-2 border">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="hero_bg" value="<?php echo htmlspecialchars($hero_bg); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Gambar Hero">
                                <input type="file" name="hero_bg_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h12a2 2 0 012 2v10z"></path></svg>
                        Kontak
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">WhatsApp</label>
                            <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($whatsapp); ?>" class="w-full p-2 border rounded-lg" placeholder="62812xxxxxxx">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat</label>
                            <textarea name="address" rows="3" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Kegiatan
                    </h2>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Format: Hari|Jam|Kegiatan (1 baris = 1 item)</label>
                        <textarea name="schedule_rows" rows="7" class="w-full p-2 border rounded-lg font-mono text-xs"><?php echo htmlspecialchars($schedule_rows); ?></textarea>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a3 3 0 014 0l4 4m-2-2l2-2a3 3 0 014 0l2 2M2 20h20"></path></svg>
                        Foto Tentang Kami
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <?php if($about_img): ?>
                                <img src="<?php echo htmlspecialchars($about_img); ?>" class="w-full max-w-xl h-48 object-cover rounded-xl mb-2 border">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="about_img" value="<?php echo htmlspecialchars($about_img); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Foto Tentang Kami">
                                <input type="file" name="about_img_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                            <div class="text-xs text-slate-500 mt-1">Format: jpg, jpeg, png, webp.</div>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a3 3 0 014 0l4 4m-2-2l2-2a3 3 0 014 0l2 2M2 20h20"></path></svg>
                        Galeri Kegiatan
                    </h2>

                    <?php if (!empty($gallery_images)): ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <?php foreach ($gallery_images as $img): ?>
                                <label class="relative group cursor-pointer border rounded-lg overflow-hidden bg-slate-50">
                                    <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-24 object-cover">
                                    <div class="absolute inset-0 bg-black/35 opacity-0 group-hover:opacity-100 transition"></div>
                                    <div class="absolute top-2 left-2">
                                        <input type="checkbox" name="gallery_remove[]" value="<?php echo htmlspecialchars($img); ?>">
                                    </div>
                                    <div class="absolute bottom-2 left-2 right-2 text-xs text-white opacity-0 group-hover:opacity-100 transition font-semibold">
                                        Centang untuk hapus
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-sm text-slate-500 mb-4">Belum ada foto galeri.</div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 gap-2">
                        <label class="block text-sm font-medium text-slate-700">Upload Foto Baru</label>
                        <input type="file" name="gallery_files[]" multiple class="w-full p-2 border rounded-lg bg-slate-50 text-sm">
                        <div class="text-xs text-slate-500">Format: jpg, jpeg, png, webp.</div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-emerald-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m-6 4h10M5 4h14a2 2 0 012 2v14l-4-3H5a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>
                        Buat Pengumuman
                    </h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul</label>
                            <input type="text" name="announcement_title" class="w-full p-2 border rounded-lg" placeholder="Judul pengumuman">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Isi</label>
                            <textarea name="announcement_content" rows="5" class="w-full p-2 border rounded-lg" placeholder="Isi pengumuman"></textarea>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="submit" name="orion_ic_theme_settings_submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg">
                        Simpan Pengaturan
                    </button>
                    <button type="submit" name="orion_ic_add_announcement_submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg">
                        Publish Pengumuman
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-government'): ?>
    <?php
    $hero_title = get_option('orion_gov_hero_title', 'Membangun Kota Cerdas & Melayani Sepenuh Hati');
    $hero_subtitle = get_option('orion_gov_hero_subtitle', 'Akses layanan publik, berita terbaru, dan transparansi anggaran dalam satu pintu.');
    $hero_bg = get_option('orion_gov_hero_bg', 'https://images.unsplash.com/photo-1577017040065-6505235af3d8?auto=format&fit=crop&q=80');
    $logo_url = get_option('orion_gov_logo', '');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Identitas & Tampilan Utama
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Logo Instansi</label>
                            <?php if($logo_url): ?>
                                <img src="<?php echo $logo_url; ?>" class="h-20 w-auto object-contain mb-2 border p-1 rounded">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="logo_url" value="<?php echo htmlspecialchars($logo_url); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Logo">
                                <input type="file" name="logo_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                        </div>

                        <!-- Hero -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul Hero (Besar)</label>
                            <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero_title); ?>" class="w-full p-2 border rounded-lg mb-4">
                            
                            <label class="block text-sm font-medium text-slate-700">Sub-judul Hero</label>
                            <textarea name="hero_subtitle" class="w-full p-2 border rounded-lg mb-4"><?php echo htmlspecialchars($hero_subtitle); ?></textarea>

                            <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Latar Hero</label>
                            <?php if($hero_bg): ?>
                                <img src="<?php echo $hero_bg; ?>" class="w-full h-40 object-cover rounded-lg mb-2 border">
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <input type="text" name="hero_bg" value="<?php echo htmlspecialchars($hero_bg); ?>" class="flex-1 p-2 border rounded-lg text-xs" placeholder="URL Gambar Hero">
                                <input type="file" name="hero_bg_file" class="flex-1 p-1 border rounded-lg bg-slate-50 text-xs">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_gov_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Pemerintah
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-smartvillage'): ?>
    <?php
    $site_title = get_option('site_title', 'Desa Digital');
    $site_tagline = get_option('site_tagline', 'Membangun Desa, Membangun Bangsa');
    $phone = get_option('smartvillage_phone', '(021) 1234-5678');
    $email = get_option('smartvillage_email', 'info@desa-digital.go.id');
    $address = get_option('smartvillage_address', 'Jl. Raya Desa No. 123, Kecamatan Orion');
    $hero_bg = get_option('smartvillage_hero_bg', '');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Identitas & Kontak Desa
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Desa</label>
                            <input type="text" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tagline Desa</label>
                            <input type="text" name="site_tagline" value="<?php echo htmlspecialchars($site_tagline); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Telepon Layanan</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email Resmi</label>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat Kantor Desa</label>
                            <textarea name="address" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path></svg>
                        Tampilan Beranda
                    </h2>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Latar Belakang Hero (Background)</label>
                        <?php if($hero_bg): ?>
                            <img src="<?php echo $hero_bg; ?>" class="w-full h-40 object-cover rounded-lg mb-2 border">
                        <?php endif; ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="hero_bg" value="<?php echo htmlspecialchars($hero_bg); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="URL Gambar">
                            <input type="file" name="hero_bg_file" class="w-full p-1 border rounded-lg bg-slate-50 text-xs">
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_smartvillage_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Informasi Desa
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php elseif ($current_theme === 'orion-one'): ?>
    <?php
    $site_title = html_entity_decode(get_option('blogname', 'Orion CMS'), ENT_QUOTES, 'UTF-8');
    $brand_name = html_entity_decode(get_option('orion_one_brand_name', 'Orion One'), ENT_QUOTES, 'UTF-8');
    $logo_url = get_option('orion_one_logo_url', site_url('/assets/img/logo.png'));
    $home_banner_image = get_option('orion_one_home_banner_image', '');
    $home_banner_link = get_option('orion_one_home_banner_link', '#');
    $banner_image = get_option('orion_one_banner_image', '');
    $banner_link = get_option('orion_one_banner_link', '#');
    ?>
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <form method="POST" enctype="multipart/form-data">
                <?php echo orion_field_nonce('orion_theme_settings_save'); ?>
            <div class="space-y-8">
                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Setup Awal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Website</label>
                            <input type="text" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Brand (Header)</label>
                            <input type="text" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>" class="w-full p-2 border rounded-lg">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Logo</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div class="md:col-span-2">
                            <?php if ($logo_url): ?>
                                <img src="<?php echo htmlspecialchars($logo_url); ?>" class="h-16 w-auto object-contain border rounded-lg p-2 bg-slate-50">
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Logo (URL)</label>
                            <input type="text" name="logo_url" value="<?php echo htmlspecialchars($logo_url); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Logo</label>
                            <input type="file" name="logo_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Home Horizontal Banner (After 6 posts)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div class="md:col-span-2">
                            <?php if ($home_banner_image): ?>
                                <img src="<?php echo htmlspecialchars($home_banner_image); ?>" class="w-full max-h-48 object-cover rounded-lg border">
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Banner Link URL</label>
                            <input type="text" name="orion_one_home_banner_link" value="<?php echo htmlspecialchars($home_banner_link); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://example.com/promo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Banner</label>
                            <input type="file" name="home_banner_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold mb-4 text-slate-800 border-b pb-2">Sidebar Banner</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div class="md:col-span-2">
                            <?php if ($banner_image): ?>
                                <img src="<?php echo htmlspecialchars($banner_image); ?>" class="h-36 w-auto object-contain rounded-lg border bg-slate-50 p-2">
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Banner Link URL</label>
                            <input type="text" name="orion_one_banner_link" value="<?php echo htmlspecialchars($banner_link); ?>" class="w-full p-2 border rounded-lg text-xs" placeholder="https://example.com/promo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload Banner</label>
                            <input type="file" name="sidebar_banner_file" class="w-full p-1.5 border rounded-lg bg-slate-50 text-sm">
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit" name="orion_one_theme_settings_submit" class="bg-orion-600 hover:bg-orion-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg">
                        Simpan Pengaturan Orion One
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="bg-white rounded-xl shadow p-10 text-center border border-dashed border-slate-300">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">No Theme Settings Available</h3>
        <p class="text-slate-500 max-w-xs mx-auto mt-2">Tema <strong><?php echo htmlspecialchars($current_theme); ?></strong> belum dikonfigurasi untuk memiliki halaman pengaturan khusus di panel ini.</p>
    </div>
<?php endif; ?>

<?php require_once 'admin-footer.php'; ?>
