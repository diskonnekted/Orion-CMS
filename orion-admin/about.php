<?php
/**
 * About Orion CMS Screen
 */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );
require_once( 'admin-header.php' );
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800">About Orion CMS</h1>
    <p class="text-slate-600 mt-2">Lightweight, performant, and modern Content Management System.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Info Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Introduction Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <img src="<?php echo get_option('site_logo', site_url('/assets/img/orion-light.png')); ?>" alt="Orion CMS Logo" class="h-16 w-auto object-contain">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Orion CMS <span class="text-sm font-normal text-slate-500 ml-2">Version 0.1</span></h2>
                        <p class="text-slate-500">Built for speed and simplicity.</p>
                    </div>
                </div>
                <div class="prose max-w-none text-slate-600">
                    <p class="mb-4">
                        Orion CMS is a modern content management system designed to be lightweight, fast, and easy to use. 
                        It strips away the bloat found in traditional CMS platforms while retaining the essential features needed to build powerful websites.
                    </p>
                    <p>
                        Whether you are a developer looking for a flexible framework or a content creator needing a simple interface, Orion CMS provides the tools you need without the overhead.
                    </p>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Key Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-slate-800">Modern Dashboard</h4>
                        <p class="text-sm text-slate-500 mt-1">Clean, responsive admin interface built with Tailwind CSS and Alpine.js.</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-slate-800">Role-Based Access</h4>
                        <p class="text-sm text-slate-500 mt-1">Granular control with Administrator and Operator roles for secure management.</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-slate-800">Extensible Architecture</h4>
                        <p class="text-sm text-slate-500 mt-1">Support for plugins and themes to extend functionality and design.</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-slate-800">Performance First</h4>
                        <p class="text-sm text-slate-500 mt-1">Optimized database queries and minimal overhead for lightning-fast loading.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info Column -->
    <div class="space-y-6">
        <!-- System Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">System Information</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Orion Version</span>
                    <span class="font-medium text-slate-700">0.1 (Alpha)</span>
                </li>
                <li class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">PHP Version</span>
                    <span class="font-medium text-slate-700"><?php echo phpversion(); ?></span>
                </li>
                <li class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Server Software</span>
                    <span class="font-medium text-slate-700"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                </li>
                <li class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Database</span>
                    <span class="font-medium text-slate-700">MySQL</span>
                </li>
                <li class="flex justify-between py-2">
                    <span class="text-slate-500">Max Upload Size</span>
                    <span class="font-medium text-slate-700"><?php echo ini_get('upload_max_filesize'); ?></span>
                </li>
            </ul>
        </div>

        <!-- Credits -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Credits</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-slate-800">Development Team</h4>
                    <ul class="mt-2 space-y-1 text-sm text-slate-500">
                        <li>Lead Developer: <span class="text-slate-700">Trae AI</span></li>
                        <li>UI/UX Design: <span class="text-slate-700">Orion Team</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-slate-800">Libraries & Resources</h4>
                    <ul class="mt-2 space-y-1 text-sm text-slate-500">
                        <li>Tailwind CSS</li>
                        <li>Alpine.js</li>
                        <li>Inter Font Family</li>
                        <li>Phosphor Icons / Heroicons</li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400">
                    &copy; <?php echo date('Y'); ?> Orion CMS. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>
