<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?> - <?php echo get_bloginfo('description'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Merriweather', serif; }
    </style>
    <?php wp_head(); ?>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Logo / Branding -->
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <a href="<?php echo site_url(); ?>" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-xl group-hover:bg-emerald-400 transition-colors">
                        O
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold leading-none tracking-tight"><?php echo get_bloginfo('name'); ?></h1>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider font-medium"><?php echo get_bloginfo('description'); ?></p>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <nav>
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'flex space-x-6 text-sm font-medium',
                        'container'      => false,
                    ) );
                } else {
                    ?>
                    <ul class="flex space-x-6 text-sm font-medium">
                        <li><a href="<?php echo site_url(); ?>" class="hover:text-emerald-400 transition-colors">Home</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">World</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Technology</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Design</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Culture</a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>
            
            <style>
                /* Dynamic Menu Styling */
                .menu-item a {
                    transition: color 0.15s ease-in-out;
                }
                .menu-item a:hover {
                    color: #34d399; /* emerald-400 */
                }
            </style>
            
            <!-- Search Icon (Mock) -->
            <div class="hidden md:block ml-6">
                <button class="text-slate-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Trending Bar -->
    <div class="bg-slate-800 border-t border-slate-700 py-2">
        <div class="container mx-auto px-4 flex items-center overflow-x-auto whitespace-nowrap text-xs">
            <span class="text-emerald-400 font-bold mr-3 uppercase">Trending:</span>
            <a href="#" class="text-slate-300 hover:text-white mr-4">#ArtificialIntelligence</a>
            <a href="#" class="text-slate-300 hover:text-white mr-4">#ClimateChange</a>
            <a href="#" class="text-slate-300 hover:text-white mr-4">#SpaceX</a>
            <a href="#" class="text-slate-300 hover:text-white mr-4">#Web3</a>
        </div>
    </div>
</header>
