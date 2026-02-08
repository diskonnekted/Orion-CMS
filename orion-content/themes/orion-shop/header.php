<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($post) ? $post->post_title . ' - ' : ''; ?>Orion Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        shop: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">
    <!-- Top Bar -->
    <div class="bg-gray-100 text-xs text-gray-500 py-1 border-b hidden md:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between">
            <div class="flex space-x-4">
                <a href="#" class="hover:text-shop-600">Download App</a>
                <a href="#" class="hover:text-shop-600">Tentang Kami</a>
                <a href="index.php?page=help-center" class="hover:text-shop-600">Bantuan</a>
            </div>
            <div class="flex space-x-4">
                <span>Bahasa: ID</span>
                <span>Mata Uang: IDR</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-8">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="text-3xl font-extrabold text-shop-600 tracking-tighter">Orion<span class="text-orange-500">Shop</span></a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-3xl hidden md:block">
                    <form action="index.php" method="GET" class="relative group">
                        <div class="flex">
                            <input type="text" name="s" placeholder="Cari produk, merek, atau kategori..." class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-shop-500 focus:border-shop-500 block w-full p-2.5 transition group-hover:bg-white group-hover:shadow-sm">
                            <button type="submit" class="p-2.5 px-6 ml-[-1px] text-sm font-medium text-white bg-shop-600 rounded-r-lg border border-shop-600 hover:bg-shop-700 focus:ring-4 focus:outline-none focus:ring-shop-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Icons -->
                <div class="flex items-center space-x-6 text-gray-600">
                    <a href="#" class="flex flex-col items-center hover:text-shop-600 transition group">
                        <svg class="w-6 h-6 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-xs font-medium hidden sm:block">Keranjang</span>
                    </a>
                    
                    <?php if (function_exists('is_user_logged_in') && is_user_logged_in()): ?>
                        <a href="product-manager.php" class="flex flex-col items-center hover:text-shop-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-xs font-medium hidden sm:block">Admin</span>
                        </a>
                    <?php else: ?>
                        <a href="#" class="flex flex-col items-center hover:text-shop-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-xs font-medium hidden sm:block">Masuk</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Search (Visible only on small screens) -->
    <div class="bg-white border-b p-4 md:hidden sticky top-16 z-40">
        <form action="index.php" method="GET">
            <input type="text" name="s" placeholder="Cari produk..." class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-shop-500 focus:border-shop-500 block p-2.5">
        </form>
    </div>

    <main class="min-h-screen">
