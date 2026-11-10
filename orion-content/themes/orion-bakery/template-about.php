<?php
/**
 * Tentang Kami (about) template.
 * Rendered by index.php when ?page=about is requested.
 * NOTE: get_header() is already output by index.php before this file is included.
 */
?>
<div class="bg-bakery-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Hero / Intro -->
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-4 rounded-full bg-bakery-200 text-bakery-800 text-sm font-bold tracking-wide uppercase mb-4 shadow-sm transform rotate-[-2deg]">
                🌾 Sejak 1995
            </span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-bakery-950 mb-4">Tentang Kami</h1>
            <p class="text-xl text-bakery-700 max-w-3xl mx-auto font-light leading-relaxed">
                Orion Bakery adalah toko roti rumahan yang lahir dari kecintaan pada resep warisan keluarga.
                Kami percaya roti terbaik lahir dari bahan sederhana, kesabaran, dan sentuhan tangan yang hangat.
            </p>
        </div>

        <!-- Story + Image -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-white transform -rotate-1 hover:rotate-0 transition duration-500">
                <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&q=80" alt="Orion Bakery" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-3xl font-serif font-bold text-bakery-900 mb-4">Cerita Kami</h2>
                <div class="prose prose-bakery text-bakery-700 leading-relaxed space-y-4">
                    <p>
                        Sejak 1995, Orion Bakery telah memanggang roti dengan resep yang diwariskan turun-temurun.
                        Setiap pagi pukul 04.00, chef kami mulai menguleni adonan segar menggunakan tepung gandum pilihan
                        dan mentega berkualitas tinggi.
                    </p>
                    <p>
                        Kami tidak menggunakan pengawet. Semua produk dipanggang dalam jumlah terbatas agar selalu
                        tersaji dalam kondisi terbaik. Dari oven hingga tangan Anda, roti kami selalu hangat dan harum.
                    </p>
                    <p>
                        Komitmen kami sederhana: menyajikan roti yang jujur, lezat, dan mampu membawa kenangan akan rasa
                        yang paling familiar di rumah.
                    </p>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20 text-center">
            <div class="p-8 bg-white rounded-3xl shadow-sm border border-bakery-100">
                <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🌾</div>
                <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Bahan Premium</h3>
                <p class="text-bakery-600">Tepung gandum pilihan, mentega segar, dan tanpa pengawet apapun.</p>
            </div>
            <div class="p-8 bg-white rounded-3xl shadow-sm border border-bakery-100">
                <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🔥</div>
                <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Dipanggang Segar</h3>
                <p class="text-bakery-600">Dibuat setiap pagi dalam jumlah terbatas demi kesegaran maksimal.</p>
            </div>
            <div class="p-8 bg-white rounded-3xl shadow-sm border border-bakery-100">
                <div class="w-16 h-16 bg-bakery-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">❤️</div>
                <h3 class="text-xl font-serif font-bold text-bakery-900 mb-2">Dibuat dengan Cinta</h3>
                <p class="text-bakery-600">Setiap roti dibuat dengan penuh perhatian dan resep warisan keluarga.</p>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-bakery-900 rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-10 bg-pattern-bread"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-bakery-50 mb-6">Mau Coba Roti Kami?</h2>
                <p class="text-bakery-200 mb-8 max-w-2xl mx-auto">
                    Kunjungi toko kami atau pesan online. Roti hangat siap menemani hari Anda.
                </p>
                <a href="<?php echo site_url('/?page=shop'); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-bakery-500 hover:bg-bakery-400 text-bakery-950 font-bold rounded-full transition shadow-lg">
                    Lihat Katalog Roti
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
