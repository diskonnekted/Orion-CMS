
<!-- Page Header -->
<section class="pt-32 pb-16 bg-gradient-to-br from-brand-50 to-white relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] -z-10"></div>
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 tracking-tight">
            Download Area
        </h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Koleksi lengkap tema dan plugin resmi Orion CMS untuk mempercepat pengembangan website Anda.
        </p>
    </div>
</section>

<!-- Core Download Section -->
<?php
$download_dir = ABSPATH . 'download/';
$core_file = 'orion-default.zip';
$core_path = $download_dir . $core_file;
$has_core = file_exists($core_path);

if ($has_core):
    $core_size_bytes = filesize($core_path);
    $core_size = ($core_size_bytes > 1024 * 1024) 
            ? round($core_size_bytes / (1024 * 1024), 2) . ' MB' 
            : round($core_size_bytes / 1024, 2) . ' KB';
?>
<section class="py-12 bg-white border-b border-slate-100">
    <div class="container mx-auto px-6">
        <div class="bg-brand-600 rounded-3xl p-8 md:p-12 shadow-2xl shadow-brand-500/20 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                 <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
                 </svg>
            </div>
            
            <div class="relative z-10 max-w-2xl text-center md:text-left">
                <div class="inline-block px-3 py-1 bg-brand-500 rounded-full text-xs font-semibold mb-4 border border-brand-400">
                    Latest Stable Release
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Orion CMS v1.0</h2>
                <p class="text-brand-100 text-lg mb-6 leading-relaxed">
                    Dapatkan versi terbaru Orion CMS dengan fitur lengkap, performa maksimal, dan keamanan terjamin. Paket instalasi mencakup inti sistem dan tema default.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4 text-sm text-brand-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        PHP 8.2+
                    </div>
                    <div class="hidden sm:block w-1 h-1 bg-brand-400 rounded-full"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        MariaDB 10.4+
                    </div>
                    <div class="hidden sm:block w-1 h-1 bg-brand-400 rounded-full"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Updated <?php echo date('M Y', filemtime($core_path)); ?>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 flex flex-col items-center gap-3 min-w-[200px]">
                <a href="<?php echo site_url('/download/' . $core_file); ?>" class="w-full px-8 py-4 bg-white text-brand-600 rounded-xl font-bold text-lg hover:bg-brand-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center flex items-center justify-center">
                    Download Orion
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
                <span class="text-brand-200 text-sm font-medium"><?php echo $core_size; ?> • ZIP Archive</span>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Themes & Plugins Grid -->
<section class="py-16 bg-slate-50 min-h-[60vh]">
    <div class="container mx-auto px-6">
        
        <?php
        // Helper function to create zip recursively
        if (!function_exists('orion_create_zip_recursively')) {
            function orion_create_zip_recursively($source, $destination) {
                if (!extension_loaded('zip') || !file_exists($source)) {
                    return false;
                }

                $zip = new ZipArchive();
                if (!$zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                    return false;
                }

                $source = str_replace('\\', '/', realpath($source));

                if (is_dir($source) === true) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($files as $file) {
                        $file = str_replace('\\', '/', $file);

                        // Ignore "." and ".." folders
                        if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                            continue;

                        $file = realpath($file);
                        $file = str_replace('\\', '/', $file);

                        if (is_dir($file) === true) {
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        } else if (is_file($file) === true) {
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                    }
                } else if (is_file($source) === true) {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }

                return $zip->close();
            }
        }

        $items = [];
        $themes = [];
        $plugins = [];
        $themes_path = ABSPATH . 'orion-content/themes/';

        // 1. Scan Installed Themes
        if (is_dir($themes_path)) {
            $theme_dirs = scandir($themes_path);
            foreach ($theme_dirs as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                if (!is_dir($themes_path . $dir)) continue;

                $name = ucfirst(str_replace(['orion-', '-'], ['Orion ', ' '], $dir));
                $zip_filename = $dir . '.zip';
                $zip_path = $download_dir . $zip_filename;
                
                // Try to create ZIP if it doesn't exist
                if (!file_exists($zip_path)) {
                    orion_create_zip_recursively($themes_path . $dir, $zip_path);
                }
                
                $has_zip = file_exists($zip_path);
                
                // Check for screenshot
                $img_url = ''; 
                $is_icon = true;
                $category = 'theme'; // Default category
                
                $screenshot_files = array('screenshot.png', 'screenshot.jpg', 'screenshot.jpeg', 'screenshot.JPG', 'screenshot.JPEG');
                foreach ($screenshot_files as $sf) {
                    if (file_exists($themes_path . $dir . '/' . $sf)) {
                        $img_url = site_url('/orion-content/themes/' . $dir . '/' . $sf);
                        $is_icon = false;
                        break;
                    }
                }
                
                if ($is_icon) {
                    // Try any image in root
                    $root_files = scandir($themes_path . $dir);
                    foreach ($root_files as $rf) {
                        if ($rf == '.' || $rf == '..') continue;
                        if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $rf) && !preg_match('/(favicon|logo)\./i', $rf)) {
                            $img_url = site_url('/orion-content/themes/' . $dir . '/' . $rf);
                            $is_icon = false;
                            break;
                        }
                    }
                }

                // Determine category if icon
                if ($is_icon) {
                    if (strpos($dir, 'shop') !== false) $category = 'shop';
                    elseif (strpos($dir, 'school') !== false) $category = 'school';
                    elseif (strpos($dir, 'member') !== false) $category = 'community';
                    elseif (strpos($dir, 'portfolio') !== false) $category = 'design';
                    elseif (strpos($dir, 'magazine') !== false) $category = 'news';
                    elseif (strpos($dir, 'developer') !== false) $category = 'code';
                }

                $themes[$dir] = [
                    'name' => $name,
                    'type' => 'Theme',
                    'zip_file' => $zip_filename,
                    'has_zip' => $has_zip,
                    'size' => $has_zip ? filesize($zip_path) : 0,
                    'img_url' => $img_url,
                    'is_icon' => $is_icon,
                    'category' => $category,
                    'description' => 'Tema responsif dan modern untuk website Anda. Siap pakai dan mudah dikustomisasi.'
                ];
            }
        }

        // 1b. Scan Installed Plugins
        $plugins_path = ABSPATH . 'orion-content/plugins/';
        if (is_dir($plugins_path)) {
            $plugin_items = scandir($plugins_path);
            foreach ($plugin_items as $item_name) {
                if ($item_name === '.' || $item_name === '..') continue;
                if ($item_name === 'index.php') continue; // Skip silence file

                $full_path = $plugins_path . $item_name;
                $is_dir = is_dir($full_path);
                
                // Skip files that are not .php
                if (!$is_dir && strtolower(pathinfo($item_name, PATHINFO_EXTENSION)) !== 'php') continue;
                
                // Simple name derivation
                $slug = $is_dir ? $item_name : pathinfo($item_name, PATHINFO_FILENAME);
                $name = ucfirst(str_replace(['orion-', '-'], ['Orion ', ' '], $slug));
                
                $zip_filename = $slug . '.zip';
                $zip_path = $download_dir . $zip_filename;
                
                // Try to create ZIP if it doesn't exist
                if (!file_exists($zip_path)) {
                    orion_create_zip_recursively($full_path, $zip_path);
                }
                
                $has_zip = file_exists($zip_path);
                
                // Default image for plugins
                $img_url = '';
                $is_icon = true;
                $category = 'plugin';

                // Try to find screenshot or specific icon
                if ($is_dir) {
                    if (file_exists($full_path . '/screenshot.png')) {
                        $img_url = site_url('/orion-content/plugins/' . $item_name . '/screenshot.png');
                        $is_icon = false;
                    } elseif (file_exists($full_path . '/screenshot.jpg')) {
                        $img_url = site_url('/orion-content/plugins/' . $item_name . '/screenshot.jpg');
                        $is_icon = false;
                    }
                }

                // Determine category for icon
                if ($is_icon) {
                    if (strpos($slug, 'form') !== false) $category = 'form';
                    elseif (strpos($slug, 'shop') !== false) $category = 'shop';
                    elseif (strpos($slug, 'pdf') !== false) $category = 'pdf';
                    elseif (strpos($slug, 'ai') !== false) $category = 'ai';
                    elseif (strpos($slug, 'bbpress') !== false || strpos($slug, 'forum') !== false) $category = 'forum';
                    elseif (strpos($slug, 'editor') !== false) $category = 'editor';
                    elseif (strpos($slug, 'quran') !== false || strpos($slug, 'doa') !== false) $category = 'religion';
                    elseif (strpos($slug, 'donor') !== false || strpos($slug, 'donation') !== false) $category = 'finance';
                }

                $plugins[$slug] = [
                    'name' => $name,
                    'type' => 'Plugin',
                    'zip_file' => $zip_filename,
                    'has_zip' => $has_zip,
                    'size' => $has_zip ? filesize($zip_path) : 0,
                    'img_url' => $img_url,
                    'is_icon' => $is_icon,
                    'category' => $category,
                    'description' => 'Plugin fungsionalitas untuk sistem Orion CMS.'
                ];
            }
        }

        // 2. Scan Zips (Plugins & Uninstalled Themes)
        if (is_dir($download_dir)) {
            $files = glob($download_dir . '*.zip');
            if ($files) {
                foreach ($files as $file) {
                    $filename = basename($file);
                    
                    // Skip core
                    if ($filename === $core_file) continue;

                    $slug = str_replace('.zip', '', $filename);
                    
                    // Check if already processed (installed theme or plugin)
                    if (isset($themes[$slug]) || isset($plugins[$slug])) continue;
                    
                    // Determine type
                    $type = 'Resource';
                    if (strpos($filename, 'plugin') !== false) {
                        $type = 'Plugin';
                    } elseif (strpos($filename, 'theme') !== false || in_array($filename, ['orion-magazine.zip', 'orion-one.zip', 'orion-portfolio.zip', 'orion-smartvillage.zip'])) {
                        $type = 'Theme';
                    } else {
                        // Fallback intelligent guess based on prefix
                        if (strpos($filename, 'orion-') === 0 && strpos($filename, 'form') === false) {
                             $type = 'Theme';
                        } else {
                             $type = 'Plugin';
                        }
                    }

                    $name = ucfirst(str_replace(['orion-', '.zip', '-'], ['Orion ', '', ' '], $filename));

                    // Image mapping for zips without installed theme
                    $image_map = [
                        'orion-magazine.zip' => 'magazine.png',
                        'orion-one.zip' => 'one.png',
                        'orion-portfolio.zip' => 'portfolio.png',
                        'orion-smartvillage.zip' => 'smartvillage.png',
                        'orion-shop.zip' => 'shop.png',
                        'orion-wall.zip' => 'wall.png',
                        'orion-libre.zip' => 'libre.png',
                        'orion-school.zip' => 'school.png',
                        'orion-promo.zip' => 'promo.png',
                        'orion-developer.zip' => 'logo.png',
                        'orion-form.zip' => 'form-plugin.png',
                        'orion-pdf-reader.zip' => 'pdf-plugin.png',
                        'orion-shop-manager.zip' => 'shop-plugin.png',
                        'hello-orion.zip' => 'hello-plugin.png',
                        'classic-editor.1.6.7.zip' => 'form-plugin.png',
                        'ai.0.3.1.zip' => 'hello-plugin.png',
                    ];
                    
                    $img_url = '';
                    $is_icon_only = true;
                    $category = ($type === 'Theme') ? 'theme' : 'plugin';

                    if (isset($image_map[$filename])) {
                        $img_file = $image_map[$filename];
                        if (in_array($img_file, ['magazine.png', 'one.png', 'portfolio.png', 'smartvillage.png', 'shop.png', 'wall.png', 'libre.png', 'school.png', 'promo.png'])) {
                            $is_icon_only = false;
                            $img_url = site_url('/assets/img/' . $img_file);
                        }
                    } 
                    
                    if ($is_icon_only) {
                        if (strpos($filename, 'form') !== false) $category = 'form';
                        elseif (strpos($filename, 'shop') !== false) $category = 'shop';
                        elseif (strpos($filename, 'pdf') !== false) $category = 'pdf';
                        elseif (strpos($filename, 'ai') !== false) $category = 'ai';
                        elseif (strpos($filename, 'bbpress') !== false || strpos($filename, 'forum') !== false) $category = 'forum';
                        elseif (strpos($filename, 'editor') !== false) $category = 'editor';
                        elseif (strpos($filename, 'quran') !== false || strpos($filename, 'doa') !== false) $category = 'religion';
                        elseif (strpos($filename, 'donor') !== false || strpos($filename, 'donation') !== false) $category = 'finance';
                        elseif (strpos($filename, 'developer') !== false) $category = 'code';
                        elseif (strpos($filename, 'school') !== false) $category = 'school';
                    }

                    $item_data = [
                        'name' => $name,
                        'type' => $type,
                        'zip_file' => $filename,
                        'has_zip' => true,
                        'size' => filesize($file),
                        'img_url' => $img_url,
                        'is_icon' => $is_icon_only,
                        'category' => $category,
                        'description' => ($type === 'Plugin') ? 'Ekstensi fungsionalitas untuk meningkatkan kemampuan Orion CMS.' : 'Tema responsif dan modern untuk website Anda.'
                    ];
                    
                    if ($type === 'Theme') {
                        $themes[$slug] = $item_data;
                    } else {
                        $plugins[$slug] = $item_data;
                    }
                }
            }
        }
        
        // Function to render grid
        function render_download_grid($items) {
            $icons = [
                'shop'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>',
                'form'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>',
                'pdf'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                'ai'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                'forum'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>',
                'editor'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                'religion'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>',
                'finance'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                'code'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>',
                'school'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 12.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>',
                'community' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
                'design'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                'plugin'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>',
                'theme'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>',
            ];

            foreach ($items as $slug => $item) {
                $is_theme = ($item['type'] === 'Theme');
                $badge_color = $is_theme ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
                $is_icon = isset($item['is_icon']) ? $item['is_icon'] : false;
                $cat = isset($item['category']) ? $item['category'] : ($is_theme ? 'theme' : 'plugin');
                
                // Aesthetic background gradients
                $grad_class = $is_theme 
                    ? 'from-purple-500 to-indigo-600' 
                    : 'from-blue-500 to-brand-600';

                // Format size
                $size_str = '0 KB';
                if ($item['size'] > 0) {
                    $size_str = ($item['size'] > 1024 * 1024) 
                            ? round($item['size'] / (1024 * 1024), 2) . ' MB' 
                            : round($item['size'] / 1024, 2) . ' KB';
                }

                $svg_path = isset($icons[$cat]) ? $icons[$cat] : $icons[$is_theme ? 'theme' : 'plugin'];
            ?>
            <!-- Modern Download Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <!-- Image Container -->
                <div class="relative h-48 overflow-hidden bg-slate-100">
                    <?php if ($is_icon): ?>
                        <!-- Aesthetic Icon Background -->
                        <div class="w-full h-full bg-gradient-to-br <?php echo $grad_class; ?> flex items-center justify-center relative">
                            <!-- Large background icon (aesthetic) -->
                            <svg class="absolute w-48 h-48 opacity-10 transform -rotate-12 scale-125 pointer-events-none" fill="none" stroke="white" viewBox="0 0 24 24">
                                <?php echo $svg_path; ?>
                            </svg>
                            <!-- Main Icon Container -->
                            <div class="relative z-10 w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl p-5 shadow-inner border border-white/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-full h-full text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo $svg_path; ?>
                                </svg>
                            </div>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo $item['img_url']; ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    <?php endif; ?>

                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?php echo $badge_color; ?> shadow-sm">
                            <?php echo $item['type']; ?>
                        </span>
                    </div>
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
                
                <!-- Content -->
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            <?php echo $item['name']; ?>
                        </h3>
                    </div>
                    
                    <p class="text-slate-500 text-sm mb-6 line-clamp-2">
                        <?php echo $item['description']; ?>
                    </p>
                    
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center text-slate-400 text-sm font-medium">
                            <?php if ($item['has_zip']): ?>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                            <?php echo $size_str; ?>
                            <?php else: ?>
                            <span class="text-orange-500 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Coming Soon
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($item['has_zip']): ?>
                        <a href="<?php echo site_url('/download/' . $item['zip_file']); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700 transition shadow-md shadow-brand-500/20 group-hover:shadow-brand-500/40">
                            Download
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                        <?php else: ?>
                        <button disabled class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-400 text-sm font-semibold rounded-lg cursor-not-allowed">
                            Download
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            }
        }
        ?>

        <!-- Themes Section -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                <span class="w-2 h-8 bg-purple-500 rounded-full mr-3"></span>
                Themes
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php render_download_grid($themes); ?>
            </div>
        </div>

        <!-- Spacer & Plugin Section -->
        <?php if (!empty($plugins)): ?>
        <div class="border-t border-slate-200 my-12"></div>
        
        <div class="mb-12">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                <span class="w-2 h-8 bg-blue-500 rounded-full mr-3"></span>
                Plugins
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php render_download_grid($plugins); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
