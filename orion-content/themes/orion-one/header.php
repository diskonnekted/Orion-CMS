<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orion CMS - News Site</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orion: {
                            500: '#3b82f6',
                            600: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50 text-gray-800'); ?>>

<nav class="bg-white shadow mb-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="text-2xl font-bold text-orion-600">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-white p-1 flex items-center justify-center shadow-sm">
                        <img src="<?php echo site_url('/assets/img/logo.png'); ?>" alt="Orion CMS" class="h-full w-full object-contain rounded-full">
                    </div>
                    <span class="tracking-tight">Orion One</span>
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
                        'link_class' => 'text-gray-600 hover:text-orion-600 transition'
                    ));
                } else {
                ?>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-orion-600">Berita</a>
                    <a href="#" class="text-gray-600 hover:text-orion-600">Teknologi</a>
                    <a href="#" class="text-gray-600 hover:text-orion-600">Olahraga</a>
                    <a href="#" class="text-gray-600 hover:text-orion-600">Tentang</a>
                </div>
                <?php } ?>
            </div>
            <div>
                <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-4 py-2 bg-orion-600 text-white rounded hover:bg-orion-500 transition">Login</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4">
