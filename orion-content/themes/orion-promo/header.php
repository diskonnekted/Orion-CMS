<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?> - <?php echo get_bloginfo('description'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        <?php
        $selected_scheme = orion_get_current_scheme();
        $orion_colors = $selected_scheme['orion'];
        ?>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '<?php echo $orion_colors['50']; ?>',
                            100: '<?php echo $orion_colors['100']; ?>',
                            200: '<?php echo $orion_colors['200']; ?>',
                            300: '<?php echo $orion_colors['300']; ?>',
                            400: '<?php echo $orion_colors['400']; ?>',
                            500: '<?php echo $orion_colors['500']; ?>',
                            600: '<?php echo $orion_colors['600']; ?>',
                            700: '<?php echo $orion_colors['700']; ?>',
                            800: '<?php echo $orion_colors['800']; ?>',
                            900: '<?php echo $orion_colors['900']; ?>',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Javanese&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            padding-top: 80px;
        }
        @media (min-width: 768px) {
            body {
                padding-top: 88px;
            }
        }
        .font-jawa {
            font-family: 'Noto Sans Javanese', ui-serif, Georgia, 'Times New Roman', serif;
            font-size: 1.35rem;
            line-height: 1.9;
        }
        .prose {
            color: #0f172a;
            font-size: 1rem;
            line-height: 1.8;
        }
        .prose p {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4 {
            font-weight: 700;
            line-height: 1.3;
            color: #0f172a;
        }
        .prose h1 {
            font-size: clamp(1.875rem, 1.5rem + 1vw, 2.25rem);
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        .prose h2 {
            font-size: 1.5rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        .prose h3 {
            font-size: 1.25rem;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }
        .prose h4 {
            font-size: 1.125rem;
            margin-top: 1.75rem;
            margin-bottom: 0.5rem;
        }
        .prose h2 + p,
        .prose h3 + p,
        .prose h4 + p {
            margin-top: 0.5rem;
        }
        .prose ul,
        .prose ol {
            margin-top: 1rem;
            margin-bottom: 1rem;
            padding-left: 1.75rem;
        }
        .prose ul {
            list-style-type: disc;
        }
        .prose ol {
            list-style-type: decimal;
        }
        .prose li {
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .prose img {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .prose blockquote {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            padding-left: 1rem;
            border-left: 4px solid #e2e8f0;
            color: #475569;
            font-style: italic;
        }
        .prose strong {
            font-weight: 600;
            color: #0f172a;
        }
        .prose a {
            color: #2563eb;
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 2px;
        }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-slate-800 antialiased'); ?>>

<!-- Navigation -->
<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="<?php echo site_url(); ?>" class="text-2xl font-bold text-brand-700 flex items-center gap-2">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/CMS-ORION-ONE.png" alt="Orion CMS Logo" class="h-14 w-auto">
        </a>
        
        <div class="hidden md:flex space-x-8 items-center">
            <a href="<?php echo site_url(); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Beranda</a>
            <a href="<?php echo site_url('?page=download'); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Download</a>
            <a href="<?php echo site_url('?page=documentation'); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Dokumentasi</a>
            <a href="<?php echo site_url(); ?>#features" class="text-slate-600 hover:text-brand-600 font-medium transition">Fitur</a>
            <a href="<?php echo site_url('?page=news'); ?>" class="text-slate-600 hover:text-brand-600 font-medium transition">Berita</a>
            <a href="<?php echo site_url('/orion-admin/'); ?>" class="px-5 py-2 bg-brand-600 text-white rounded-full font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-500/30">
                Login Admin
            </a>
        </div>
        
        <!-- Mobile Menu Button (Simple implementation) -->
        <button class="md:hidden text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
</nav>
