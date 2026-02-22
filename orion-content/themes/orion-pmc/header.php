<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($post) ? $post->post_title . ' - ' : ''; ?><?php echo get_option('blogname', 'Orion PMC'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pmc: {
                            green: '#4b5320', // Army Green
                            darkgreen: '#353c1b',
                            khaki: '#c3b091', // Khaki
                            lightkhaki: '#e6dcc3',
                            dark: '#1a1a1a',
                            light: '#f5f5f0',
                            accent: '#8B0000' // Blood red accent (subtle)
                        }
                    },
                    fontFamily: {
                        stencil: ['"Black Ops One"', 'cursive'],
                        sans: ['"Roboto"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .font-stencil { font-family: 'Black Ops One', cursive; }
        .bg-pattern {
            background-color: #1a1a1a;
            background-image: radial-gradient(#2a2a2a 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-pmc-light font-sans text-pmc-dark antialiased">

<nav class="bg-pmc-dark text-white border-b-4 border-pmc-green sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <a href="index.php" class="flex-shrink-0 flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-pmc-khaki text-pmc-dark flex items-center justify-center rounded font-stencil text-xl group-hover:bg-pmc-green group-hover:text-white transition-colors">
                        O
                    </div>
                    <span class="font-stencil text-2xl tracking-wider group-hover:text-pmc-khaki transition-colors">ORION <span class="text-pmc-green">PMC</span></span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-gray-300 hover:text-pmc-khaki font-bold uppercase tracking-wide text-sm transition-colors">Home</a>
                <a href="capabilities.php" class="text-gray-300 hover:text-pmc-khaki font-bold uppercase tracking-wide text-sm transition-colors">Capabilities</a>
                <a href="index.php#about" class="text-gray-300 hover:text-pmc-khaki font-bold uppercase tracking-wide text-sm transition-colors">About</a>
                <a href="index.php#contact" class="bg-pmc-green hover:bg-pmc-darkgreen text-white px-5 py-2 rounded font-bold uppercase tracking-wide text-sm transition-colors clip-path-slant">
                    Contact Command
                </a>
            </div>
        </div>
    </div>
</nav>
