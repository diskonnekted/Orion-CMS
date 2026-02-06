<?php get_header(); ?>

<?php
// Handle Single View
$post_id = isset($_GET['p']) ? (int)$_GET['p'] : 0;
$single_post = null;

if ($post_id > 0) {
    $single_post = get_post($post_id);
}
?>

<?php if ($single_post): ?>
    <!-- SINGLE PROJECT VIEW -->
    <?php
    $thumb_url = get_the_post_thumbnail_url($single_post->ID);
    $categories = get_the_terms($single_post->ID, 'category');
    $gallery = get_post_meta($single_post->ID, '_gallery_images', true);
    $attachments = get_post_meta($single_post->ID, '_attachments', true);
    ?>

    <article class="bg-white min-h-screen">
        <!-- Project Hero -->
        <div class="relative h-96 w-full bg-slate-900 overflow-hidden">
            <?php if ($thumb_url): ?>
                <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($single_post->post_title); ?>" class="w-full h-full object-cover opacity-50">
            <?php else: ?>
                <div class="w-full h-full bg-gradient-to-r from-blue-900 to-slate-900 opacity-50"></div>
            <?php endif; ?>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center px-4">
                    <?php if ($categories): ?>
                        <div class="flex justify-center gap-2 mb-4">
                            <?php foreach($categories as $cat): ?>
                                <span class="bg-blue-600/90 text-white text-xs px-3 py-1 rounded-full font-medium uppercase tracking-wider backdrop-blur-sm"><?php echo htmlspecialchars($cat->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2"><?php echo htmlspecialchars($single_post->post_title); ?></h1>
                    <p class="text-slate-300"><?php echo date('F Y', strtotime($single_post->post_date)); ?></p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-20 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <!-- Content -->
                <div class="prose prose-lg prose-slate max-w-none mb-12">
                    <?php echo $single_post->post_content; ?>
                </div>

                <!-- Gallery Section -->
                <?php if ($gallery): 
                    $gallery_images = json_decode($gallery, true);
                    if (!empty($gallery_images)):
                ?>
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                        <span class="w-8 h-1 bg-blue-600 mr-3 rounded-full"></span> Project Gallery
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach($gallery_images as $img): ?>
                        <a href="<?php echo $img; ?>" target="_blank" class="block group relative aspect-video overflow-hidden rounded-xl shadow-sm hover:shadow-md transition">
                            <img src="<?php echo $img; ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition duration-300"></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>

                <!-- Attachments Section -->
                <?php if ($attachments): 
                    $att_files = json_decode($attachments, true);
                    if (!empty($att_files)):
                ?>
                <div class="mb-12 bg-slate-50 p-8 rounded-xl border border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Project Resources</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach($att_files as $att): ?>
                        <a href="<?php echo $att['url']; ?>" download class="flex items-center p-4 bg-white rounded-lg border border-slate-200 hover:border-blue-300 hover:shadow-md transition group">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-medium text-slate-800 truncate group-hover:text-blue-600 transition"><?php echo htmlspecialchars($att['name']); ?></p>
                                <p class="text-xs text-slate-400 mt-1">Download File</p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>

                <div class="border-t border-slate-100 pt-8 text-center">
                    <a href="index.php#projects" class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 shadow-sm text-base font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 transition">
                        <svg class="w-5 h-5 mr-2 -ml-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </article>

<?php else: ?>
    <!-- HOMEPAGE VIEW -->

    <!-- Hero Section -->
    <section id="home" class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Hi, I'm a Creative</span>
                            <span class="block text-blue-600 xl:inline">Developer & Designer</span>
                        </h1>
                        <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            I build accessible, pixel-perfect, and performant web experiences. I love solving complex problems with simple, elegant solutions.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="#projects" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition">
                                    View Work
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#contact" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg transition">
                                    Contact Me
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Workspace">
        </div>
    </section>

    <!-- Services / Skills Section -->
    <section id="about" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">What I Do</h2>
                <p class="mt-4 max-w-2xl text-xl text-slate-500 mx-auto">Combining technical expertise with design sensibility.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Web Development</h3>
                    <p class="text-slate-500">Building fast, responsive, and scalable websites using modern technologies like React, PHP, and Tailwind CSS.</p>
                </div>

                <!-- Service 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">UI/UX Design</h3>
                    <p class="text-slate-500">Creating intuitive and aesthetically pleasing user interfaces that provide great user experiences.</p>
                </div>

                <!-- Service 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Optimization</h3>
                    <p class="text-slate-500">Optimizing performance and SEO to ensure your website reaches its audience effectively.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section id="projects" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Featured Projects</h2>
                    <p class="mt-4 max-w-2xl text-xl text-slate-500">A selection of my recent work.</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
                    View All Projects
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Fetch Projects (Posts)
                // Assuming basic query for now
                global $orion_db, $table_prefix;
                $sql = "SELECT * FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 6";
                $result = $orion_db->query($sql);
                
                if ($result->num_rows > 0):
                    while($post = $result->fetch_object()):
                        $thumb_url = get_the_post_thumbnail_url($post->ID);
                        // Fallback image if no thumbnail
                        if (!$thumb_url) {
                            $thumb_url = 'https://via.placeholder.com/800x600?text=' . urlencode($post->post_title);
                        }
                        
                        $categories = get_the_terms($post->ID, 'category');
                        $cat_name = $categories ? $categories[0]->name : 'Project';
                ?>
                    <!-- Project Card -->
                    <article class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative aspect-video overflow-hidden bg-slate-200">
                            <img src="<?php echo $thumb_url; ?>" alt="<?php echo htmlspecialchars($post->post_title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            <div class="absolute bottom-4 left-4 text-white opacity-0 group-hover:opacity-100 transition duration-300">
                                <span class="bg-blue-600 text-xs px-2 py-1 rounded font-bold uppercase tracking-wide mb-2 inline-block"><?php echo htmlspecialchars($cat_name); ?></span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition">
                                <a href="index.php?p=<?php echo $post->ID; ?>">
                                    <?php echo htmlspecialchars($post->post_title); ?>
                                </a>
                            </h3>
                            <div class="text-slate-500 text-sm line-clamp-3 mb-4">
                                <?php echo strip_tags(substr($post->post_content, 0, 150)) . '...'; ?>
                            </div>
                            <a href="index.php?p=<?php echo $post->ID; ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                View Case Study
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </article>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="col-span-3 text-center py-12">
                        <p class="text-slate-500 text-lg">No projects found. Start adding posts to populate your portfolio!</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-12 text-center md:hidden">
                <a href="#" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
                    View All Projects
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-slate-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold sm:text-4xl mb-6">Let's work together</h2>
            <p class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto">
                Have a project in mind? I'm currently available for freelance work and open to new opportunities.
            </p>
            <a href="mailto:hello@example.com" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-full text-slate-900 bg-white hover:bg-slate-100 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                Say Hello
                <svg class="w-5 h-5 ml-2 -mr-1 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </a>
        </div>
    </section>

<?php endif; ?>

<?php get_footer(); ?>