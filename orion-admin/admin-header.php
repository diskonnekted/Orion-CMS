<?php
// Check if user is logged in
if (!is_user_logged_in()) {
    header('Location: ' . site_url('/login.php'));
    exit;
}

$current_user = wp_get_current_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &lsaquo; Orion CMS</title>
    <link rel="icon" type="image/png" href="<?php echo site_url('/assets/img/favicon.png'); ?>">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        <?php
        $selected_scheme = orion_get_current_scheme();
        ?>

        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        slate: <?php echo json_encode($selected_scheme['slate']); ?>,
                        orion: <?php echo json_encode($selected_scheme['orion']); ?>
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar for Sidebar */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Sidebar Link Styles */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #94a3b8; /* slate-400 */
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            color: #f8fafc; /* slate-50 */
            background-color: rgba(30, 41, 59, 0.5); /* slate-800/50 */
        }
        .sidebar-link.active {
            color: <?php echo $selected_scheme['orion']['400']; ?>; /* blue-400 */
            background: linear-gradient(90deg, <?php echo $selected_scheme['orion']['500']; ?>1A 0%, <?php echo $selected_scheme['orion']['500']; ?>00 100%);
            border-left-color: <?php echo $selected_scheme['orion']['500']; ?>; /* blue-500 */
        }
        
        /* Submenu Link Styles */
        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem 0.5rem 3.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .submenu-link:hover {
            color: #f8fafc;
        }
        .submenu-link.active {
            color: #ffffff;
            font-weight: 500;
        }
    </style>
    <?php if (function_exists('do_action')) { do_action('orion_admin_head'); } ?>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col border-r border-slate-800 shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-900">
            <a href="<?php echo site_url('/orion-admin/index.php'); ?>" class="flex items-center gap-3">
                <img src="<?php echo get_option('site_logo', site_url('/assets/img/orion-light.png')); ?>" alt="<?php echo htmlspecialchars(get_option('blogname', 'Orion CMS')); ?>" class="h-12 w-auto object-contain max-w-[150px]">
                <span class="font-bold text-lg tracking-tight">V.01</span>
            </a>
        </div>
        
        <nav class="flex-1 py-6 space-y-1 overflow-y-auto scrollbar-hide">
            <!-- Dashboard -->
            <a href="<?php echo site_url('/orion-admin/index.php'); ?>" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Orion Libre Book Manager (Conditional) -->
            <?php if(get_option('template') === 'orion-libre'): ?>
            <a href="<?php echo site_url('/orion-content/themes/orion-libre/manage-books.php'); ?>" class="sidebar-link text-amber-400 hover:text-amber-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-medium">Manage Books</span>
            </a>
            <?php endif; ?>

            <!-- Orion Shop Product Manager (Conditional) -->
            <?php if(get_option('template') === 'orion-shop'): ?>
            <a href="<?php echo site_url('/product-manager.php'); ?>" class="sidebar-link text-emerald-400 hover:text-emerald-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="font-medium">Kelola Produk Toko</span>
            </a>
            <?php endif; ?>

            <!-- Orion Member Management (Conditional) -->
            <?php if(get_option('template') === 'orion-member'): ?>
            <a href="<?php echo site_url('/orion-content/themes/orion-member/manage-members.php'); ?>" class="sidebar-link text-blue-400 hover:text-blue-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Manajemen Anggota</span>
            </a>
            <?php endif; ?>

            <!-- Posts Dropdown -->
            <div x-data="{ open: <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post-new.php', 'categories.php'])) ? 'true' : 'false'; ?> }">
                <button @click="open = !open" class="w-full sidebar-link focus:outline-none justify-between group">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <span class="font-medium">Posts</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-collapse class="bg-slate-800/50 py-1">
                    <a href="<?php echo site_url('/orion-admin/posts.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'posts.php' ? 'active' : ''; ?>">All Posts</a>
                    <a href="<?php echo site_url('/orion-admin/post-new.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'post-new.php' ? 'active' : ''; ?>">Add New</a>
                    <a href="<?php echo site_url('/orion-admin/categories.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">Categories</a>
                </div>
            </div>

            <!-- Media -->
            <a href="<?php echo site_url('/orion-admin/media.php'); ?>" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'media.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Media</span>
            </a>

            <!-- Pages -->
            <a href="<?php echo site_url('/orion-admin/pages.php'); ?>" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'pages.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Pages</span>
            </a>

            <!-- Appearance Dropdown -->
            <?php if (current_user_can('administrator')): ?>
            <div x-data="{ open: <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['themes.php', 'nav-menus.php'])) ? 'true' : 'false'; ?> }">
                <button @click="open = !open" class="w-full sidebar-link focus:outline-none justify-between group">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        <span class="font-medium">Appearance</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-collapse class="bg-slate-800/50 py-1">
                    <a href="<?php echo site_url('/orion-admin/themes.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'themes.php' ? 'active' : ''; ?>">Themes</a>
                    <a href="<?php echo site_url('/orion-admin/nav-menus.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'nav-menus.php' ? 'active' : ''; ?>">Menus</a>
                    <?php
                    $current_theme = get_option('template', 'orion-default');
                    $theme_settings_path = ABSPATH . 'orion-content/themes/' . $current_theme . '/settings.php';
                    if (file_exists($theme_settings_path)):
                        $theme_name_display = ucwords(str_replace(['orion-', '-'], ['', ' '], $current_theme));
                    ?>
                    <a href="<?php echo site_url('/orion-admin/theme-settings.php'); ?>" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'theme-settings.php' ? 'active' : ''; ?>"><?php echo $theme_name_display; ?> Settings</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Plugins -->
            <a href="<?php echo site_url('/orion-admin/plugins.php'); ?>" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'plugins.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                <span class="font-medium">Plugins</span>
            </a>

            <!-- Orion Forms (Plugin) -->
            <?php 
            $active_plugins = get_option('active_plugins', array());
            if (in_array('orion-form/orion-form.php', $active_plugins)): 
            ?>
            <a href="<?php echo site_url('/orion-content/plugins/orion-form/admin/forms.php'); ?>" class="sidebar-link <?php echo strpos($_SERVER['PHP_SELF'], 'orion-form') !== false ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Orion Forms</span>
            </a>
            <?php endif; ?>

            <!-- Users -->
            <a href="users.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Users</span>
            </a>

            <!-- Settings -->
            <a href="settings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium">Settings</span>
            </a>
            <?php endif; ?>

            <!-- Manual Dropdown -->
            <div x-data="{ open: <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['documentation.php', 'about.php'])) ? 'true' : 'false'; ?> }">
                <button @click="open = !open" class="w-full sidebar-link focus:outline-none justify-between group">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="font-medium">Manual</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-collapse class="bg-slate-800/50 py-1">
                    <a href="documentation.php" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'documentation.php' ? 'active' : ''; ?>">Documentation</a>
                    <a href="about.php" class="submenu-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a>
                </div>
            </div>
        </nav>
        
        <div class="p-4 border-t border-slate-800 bg-slate-900">
            <div class="flex items-center gap-3">
                 <?php
                 $avatar_url = get_user_meta($current_user->ID, 'orion_avatar', true);
                 if (!$avatar_url) {
                     $avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($current_user->display_name) . '&background=random';
                 }
                 ?>
                 <img class="h-8 w-8 rounded-full object-cover border-2 border-slate-700" src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($current_user->display_name); ?>">
                 <div>
                     <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($current_user->display_name); ?></p>
                     <p class="text-xs text-slate-500"><?php echo !empty($current_user->roles) ? ucfirst($current_user->roles[0]) : 'User'; ?></p>
                 </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button class="text-slate-500 focus:outline-none md:hidden p-2 rounded-md hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <a href="<?php echo site_url(); ?>" target="_blank" class="ml-4 text-slate-600 hover:text-orion-600 flex items-center text-sm font-medium transition-colors group">
                    <div class="p-1.5 bg-slate-100 rounded-md group-hover:bg-orion-50 text-slate-500 group-hover:text-orion-600 mr-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    Lihat Website
                </a>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-slate-400 hover:text-slate-600 transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-slate-700">Howdy, <?php echo htmlspecialchars($current_user->display_name); ?></p>
                        <div class="flex justify-end gap-2 text-xs text-slate-500">
                            <a href="profile.php" class="hover:text-orion-600">Edit Profile</a>
                            <span>|</span>
                            <a href="<?php echo site_url('/login.php?action=logout'); ?>" class="hover:text-orion-600">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
