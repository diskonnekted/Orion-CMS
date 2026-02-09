<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($post) ? $post->post_title . ' - ' : ''; ?>Orion Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-brand-600">Orion<span class="text-slate-800">Member</span></a>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php" class="text-slate-600 hover:text-brand-600 font-medium">Beranda</a>
                    <a href="index.php#news" class="text-slate-600 hover:text-brand-600 font-medium">Berita</a>
                    <a href="index.php#consultation" class="text-slate-600 hover:text-brand-600 font-medium">Konsultasi</a>
                    <a href="register-member.php" class="bg-brand-600 text-white px-4 py-2 rounded-md hover:bg-brand-700 transition">Daftar Member</a>
                </div>
            </div>
        </div>
    </nav>
    <main>
