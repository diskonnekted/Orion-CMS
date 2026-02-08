<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orion Wall - Wallpaper Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orion: {
                            500: '#8b5cf6', // Violet for Wall theme
                            600: '#7c3aed',
                        }
                    }
                }
            }
        }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-900 text-slate-200'); // Dark theme for wallpapers ?>>

<nav class="bg-slate-800 shadow-lg mb-8 border-b border-slate-700">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="text-2xl font-bold text-orion-500">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-2">
                    <!-- Icon placeholder -->
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    Orion Wall
                </a>
            </div>
            <div class="hidden md:flex">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'flex space-x-6 list-none',
                        'fallback_cb' => false,
                        'link_class' => 'text-slate-300 hover:text-orion-500 transition'
                    ));
                } else {
                ?>
                <div class="flex space-x-6">
                    <a href="<?php echo site_url(); ?>" class="text-slate-300 hover:text-orion-500">Home</a>
                    <a href="#" class="text-slate-300 hover:text-orion-500">Albums</a>
                    <a href="#" class="text-slate-300 hover:text-orion-500">Popular</a>
                    <a href="#" class="text-slate-300 hover:text-orion-500">About</a>
                </div>
                <?php } ?>
            </div>
            <div>
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-4 py-2 bg-orion-600 text-white rounded hover:bg-orion-500 transition">Upload</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 min-h-screen">
