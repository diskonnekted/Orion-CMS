<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_option('blogname'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        libre: {
                            50: '#f9f7f2',
                            100: '#f0eadd',
                            200: '#e0d3b8',
                            300: '#cbb68d',
                            400: '#b89a66',
                            500: '#a3804d',
                            600: '#8c683f',
                            700: '#725336',
                            800: '#5e4530',
                            900: '#4e3b2b',
                        }
                    },
                    fontFamily: {
                        serif: ['Merriweather', 'serif'],
                        sans: ['Open Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-libre-50 text-libre-900 min-h-screen flex flex-col">
    <header class="bg-libre-800 text-libre-50 shadow-lg">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <a href="<?php echo site_url(); ?>" class="flex items-center gap-3">
                <img src="<?php echo site_url('/assets/img/orion-logo.png'); ?>" alt="Orion CMS" class="h-10 w-auto">
                <div>
                    <h1 class="text-2xl font-bold font-serif tracking-wide">Orion Libre</h1>
                    <p class="text-xs text-libre-300 uppercase tracking-widest">Digital Library</p>
                </div>
            </a>
            <nav>
                <ul class="flex space-x-6 text-sm font-semibold">
                    <li><a href="index.php" class="hover:text-libre-300 transition">Home</a></li>
                    <li><a href="#" class="hover:text-libre-300 transition">Collections</a></li>
                    <li><a href="#" class="hover:text-libre-300 transition">About</a></li>
                    <?php if(is_user_logged_in()): ?>
                        <li><a href="manage-books.php" class="bg-libre-600 hover:bg-libre-500 text-white px-3 py-1 rounded transition">Manage Books</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="flex-grow container mx-auto px-4 py-8">
