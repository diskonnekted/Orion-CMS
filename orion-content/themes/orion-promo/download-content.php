
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
                        PHP 7.4+
                    </div>
                    <div class="hidden sm:block w-1 h-1 bg-brand-400 rounded-full"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        MySQL 5.7+
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
                $img_url = site_url('/assets/img/orion-logo.png'); // Default
                if (file_exists($themes_path . $dir . '/screenshot.png')) {
                    $img_url = site_url('/orion-content/themes/' . $dir . '/screenshot.png');
                } elseif (file_exists($themes_path . $dir . '/screenshot.jpg')) {
                    $img_url = site_url('/orion-content/themes/' . $dir . '/screenshot.jpg');
                }

                $themes[$dir] = [
                    'name' => $name,
                    'type' => 'Theme',
                    'zip_file' => $zip_filename,
                    'has_zip' => $has_zip,
                    'size' => $has_zip ? filesize($zip_path) : 0,
                    'img_url' => $img_url,
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
                $img_url = site_url('/assets/img/orion-logo.png');
                // Try to find screenshot in plugin folder if it is a dir
                if ($is_dir) {
                        if (file_exists($full_path . '/screenshot.png')) {
                        $img_url = site_url('/orion-content/plugins/' . $item_name . '/screenshot.png');
                    } elseif (file_exists($full_path . '/assets/img/logo.png')) {
                            $img_url = site_url('/orion-content/plugins/' . $item_name . '/assets/img/logo.png');
                    }
                }

                $plugins[$slug] = [
                    'name' => $name,
                    'type' => 'Plugin',
                    'zip_file' => $zip_filename,
                    'has_zip' => $has_zip,
                    'size' => $has_zip ? filesize($zip_path) : 0,
                    'img_url' => $img_url,
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
                        'orion-magazine.zip' => 'magazine.PNG',
                        'orion-one.zip' => 'one.PNG',
                        'orion-portfolio.zip' => 'portfolio.PNG',
                        'orion-smartvillage.zip' => 'smartvillage.PNG',
                    ];
                    
                    $img_file = isset($image_map[$filename]) ? $image_map[$filename] : 'orion-logo.png';
                    $img_url = site_url('/assets/img/' . $img_file); // Fallback to assets

                    $item_data = [
                        'name' => $name,
                        'type' => $type,
                        'zip_file' => $filename,
                        'has_zip' => true,
                        'size' => filesize($file),
                        'img_url' => $img_url,
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
            foreach ($items as $slug => $item) {
                $badge_color = ($item['type'] === 'Theme') ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
                
                // Format size
                $size_str = '0 KB';
                if ($item['size'] > 0) {
                    $size_str = ($item['size'] > 1024 * 1024) 
                            ? round($item['size'] / (1024 * 1024), 2) . ' MB' 
                            : round($item['size'] / 1024, 2) . ' KB';
                }
            ?>
            <!-- Modern Download Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <!-- Image Container -->
                <div class="relative h-48 overflow-hidden bg-slate-100">
                    <img src="<?php echo $item['img_url']; ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
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
