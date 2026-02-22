<?php 
// Simple router logic for the promo theme
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle ?p=ID for posts
if (isset($_GET['p'])) {
    $page = 'single_post';
}

get_header(); 

// Router switch
switch ($page) {
    case 'single_post':
        global $orion_db, $table_prefix;
        $posts_table = $table_prefix . 'posts';
        $post_id = intval($_GET['p']);
        
        $sql = "SELECT * FROM $posts_table WHERE ID = $post_id AND post_status = 'publish'";
        $result = $orion_db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $post = $result->fetch_object();
            
            // Process content
            $content = $post->post_content;
            $content = htmlspecialchars_decode($content, ENT_QUOTES);
            
            // Apply Filters
            if (function_exists('apply_filters')) {
                $content = apply_filters('the_content', $content);
            } elseif (function_exists('orion_form_shortcode_parser')) {
                $content = orion_form_shortcode_parser($content);
            }
            
            // Render Post
            echo '<div class="container mx-auto px-6 py-20 min-h-screen">';
            echo '<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">';
            
            // Featured Image
            if (function_exists('get_the_post_thumbnail_url')) {
                $thumbnail_url = get_the_post_thumbnail_url($post->ID);
                if ($thumbnail_url) {
                    echo '<div class="mb-8 rounded-xl overflow-hidden shadow-sm aspect-video">';
                    echo '<img src="' . htmlspecialchars($thumbnail_url) . '" alt="' . htmlspecialchars($post->post_title) . '" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">';
                    echo '</div>';
                }
            }
            
            $single_title = strip_tags(htmlspecialchars_decode($post->post_title, ENT_QUOTES));
            echo '<h1 class="text-3xl font-bold text-slate-900 mb-2">' . $single_title . '</h1>';
            
            // Date for posts
            echo '<p class="text-sm text-slate-500 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">';
            echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
            echo date('d M Y', strtotime($post->post_date));
            echo '</p>';

            $latest_posts = get_posts(array(
                'numberposts' => 1,
                'post_type'   => 'post',
                'post_status' => 'publish',
                'orderby'     => 'post_date',
                'order'       => 'DESC'
            ));
            $is_latest = $latest_posts && isset($latest_posts[0]) && (int)$latest_posts[0]->ID === (int)$post->ID;

            if ($is_latest) {
                echo '<div class="mb-6 p-4 rounded-lg border border-slate-100 bg-slate-50">';
                echo '<p class="text-xs font-semibold uppercase tracking-wide text-brand-600 mb-2">Contoh Aksara Jawa</p>';
                echo '<p class="font-jawa mb-1">ꦲꦶꦤꦪꦸꦁ ꦏꦸꦛꦸ ꦲꦏ꧀ꦱꦫ ꦗꦮ</p>';
                echo '<p class="text-xs text-slate-500">Ini hanya contoh tampilan huruf Jawa dengan class <code>font-jawa</code>.</p>';
                echo '</div>';
            }

            echo '<div class="prose prose-slate max-w-none">' . $content . '</div>';

            // Attachments Section
            $attachments = get_post_meta($post->ID, '_attachments', true);
            if ($attachments) {
                $att_files = json_decode($attachments, true);
                if (!empty($att_files)) {
                    echo '<div class="mt-10 bg-slate-50 p-6 rounded-xl border border-slate-100">';
                    echo '<h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">';
                    echo '<svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>';
                    echo '<span>Lampiran Dokumen</span>';
                    echo '</h2>';
                    echo '<ul class="space-y-3">';
                    foreach ($att_files as $att) {
                        $name = isset($att['name']) ? $att['name'] : basename($att['url']);
                        echo '<li class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100 hover:border-brand-300 hover:shadow-sm transition">';
                        echo '<span class="font-medium text-slate-700 truncate mr-4">' . htmlspecialchars($name) . '</span>';
                        echo '<a href="' . htmlspecialchars($att['url']) . '" download class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 transition">';
                        echo '<span>Download</span>';
                        echo '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>';
                        echo '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
            }

            echo '</div>';
            echo '</div>';
            
        } else {
            // 404 Not Found
            echo '<div class="container mx-auto py-32 text-center">';
            echo '<h1 class="text-6xl font-bold text-slate-200 mb-4">404</h1>';
            echo '<p class="text-xl text-slate-600">Berita tidak ditemukan.</p>';
            echo '<a href="index.php" class="inline-block mt-6 text-brand-600 hover:text-brand-800 font-medium">&larr; Kembali ke Beranda</a>';
            echo '</div>';
        }
        break;

    case 'news':
        $paged = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;

        $posts_per_page = (int) get_option('orion_promo_news_per_page', 6);
        if ($posts_per_page < 1) {
            $posts_per_page = 6;
        }
        if ($posts_per_page > 20) {
            $posts_per_page = 20;
        }

        $news_layout = get_option('orion_promo_news_layout', 'grid');
        if (!in_array($news_layout, array('grid', 'featured_1_2'))) {
            $news_layout = 'grid';
        }

        $offset = ($paged - 1) * $posts_per_page;

        $args = array(
            'numberposts' => $posts_per_page,
            'offset' => $offset,
            'post_status' => 'publish',
            'post_type' => 'post',
            'orderby' => 'post_date',
            'order' => 'DESC'
        );

        $news_posts = get_posts($args);

        echo '<section class="py-20 bg-slate-50 min-h-screen">';
        echo '<div class="container mx-auto px-6">';
        echo '<div class="flex justify-between items-end mb-10">';
        echo '<div>';
        echo '<h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">Berita & Artikel</h1>';
        echo '<p class="text-slate-600">Kumpulan update, tips, dan berita terbaru dari Orion CMS.</p>';
        echo '</div>';
        echo '</div>';

        if ($news_posts) {
            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';
            $index = 0;
            foreach ($news_posts as $np) {
                $index++;
                $thumb = function_exists('get_the_post_thumbnail_url') ? get_the_post_thumbnail_url($np->ID) : '';
                $article_class = 'bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col h-full group';
                if ($news_layout === 'featured_1_2' && $index === 1) {
                    $article_class = 'md:col-span-2 lg:col-span-3 bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col h-full group';
                }
                echo '<article class="' . $article_class . '">';
                if ($thumb) {
                    echo '<div class="h-56 overflow-hidden relative">';
                    echo '<img src="' . htmlspecialchars($thumb) . '" alt="' . htmlspecialchars($np->post_title) . '" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">';
                    echo '<div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-brand-600 uppercase tracking-wide">News</div>';
                    echo '</div>';
                } else {
                    echo '<div class="h-56 bg-slate-100 flex items-center justify-center text-slate-400 relative">';
                    echo '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
                    echo '<div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-brand-600 uppercase tracking-wide">Article</div>';
                    echo '</div>';
                }
                echo '<div class="p-6 flex-grow flex flex-col">';
                echo '<div class="text-sm text-slate-500 mb-3 flex items-center">';
                echo '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
                echo date('d M Y', strtotime($np->post_date));
                echo '</div>';
                $loop_title = strip_tags(htmlspecialchars_decode($np->post_title, ENT_QUOTES));
                echo '<h2 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-600 transition">';
                echo '<a href="?p=' . $np->ID . '">' . $loop_title . '</a>';
                echo '</h2>';
                echo '<p class="text-slate-600 mb-4 line-clamp-3 flex-grow leading-relaxed">';
                echo wp_trim_words(htmlspecialchars_decode($np->post_content, ENT_QUOTES), 30);
                echo '</p>';
                echo '<a href="?p=' . $np->ID . '" class="text-brand-600 font-bold hover:text-brand-700 inline-flex items-center mt-auto uppercase text-sm tracking-wide">';
                echo 'Baca Selengkapnya';
                echo '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>';
                echo '</a>';
                echo '</div>';
                echo '</article>';
            }
            echo '</div>';

            global $orion_db, $table_prefix;
            $posts_table = $table_prefix . 'posts';
            $res_count = $orion_db->query("SELECT COUNT(*) as count FROM {$posts_table} WHERE post_type = 'post' AND post_status = 'publish'");
            $total_posts = ($res_count) ? $res_count->fetch_object()->count : 0;
            $total_pages = $posts_per_page > 0 ? ceil($total_posts / $posts_per_page) : 1;

            if ($total_pages > 1) {
                echo '<div class="mt-12 flex justify-center gap-2">';
                if ($paged > 1) {
                    echo '<a href="index.php?page=news&paged=' . ($paged - 1) . '" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 hover:bg-slate-50">Previous</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $paged) ? 'bg-brand-600 text-white border-brand-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50';
                    echo '<a href="index.php?page=news&paged=' . $i . '" class="px-4 py-2 border rounded-lg text-sm font-medium ' . $active . '">' . $i . '</a>';
                }
                if ($paged < $total_pages) {
                    echo '<a href="index.php?page=news&paged=' . ($paged + 1) . '" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 hover:bg-slate-50">Next</a>';
                }
                echo '</div>';
            }
        } else {
            echo '<div class="mt-10 bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center text-slate-500">';
            echo 'Belum ada berita yang dipublikasikan.';
            echo '</div>';
        }

        echo '</div>';
        echo '</section>';
        break;

    case 'download':
        // Load download page
        if (file_exists(get_template_directory() . '/download-content.php')) {
            include get_template_directory() . '/download-content.php';
        } else {
            echo '<div class="container mx-auto py-20 text-center">Error: Template download-content.php missing.</div>';
        }
        break;

    case 'documentation':
        // Load documentation page
        if (file_exists(get_template_directory() . '/documentation-content.php')) {
            include get_template_directory() . '/documentation-content.php';
        } else {
            echo '<div class="container mx-auto py-20 text-center">Error: Template documentation-content.php missing.</div>';
        }
        break;
        
    case 'home':
        // Load home page
        if (file_exists(get_template_directory() . '/home-content.php')) {
            include get_template_directory() . '/home-content.php';
        } else {
            // Fallback content if home-content.php is missing
            echo '<div class="container mx-auto py-20 text-center">Welcome to Orion Promo</div>';
        }
        break;

    default:
        // Try to find page in database
        global $orion_db, $table_prefix;
        $posts_table = $table_prefix . 'posts';
        
        // Check if input is numeric ID or Title
        $page_id = intval($page);
        if ($page_id > 0) {
            $sql = "SELECT * FROM $posts_table WHERE ID = $page_id AND post_type = 'page' AND post_status = 'publish'";
        } else {
            $page_title = $orion_db->real_escape_string($page);
            $sql = "SELECT * FROM $posts_table WHERE post_title = '$page_title' AND post_type = 'page' AND post_status = 'publish'";
        }
        
        $result = $orion_db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $post = $result->fetch_object();
            
            // Process content
            $content = $post->post_content;
            
            // Apply Filters (Standard Orion Way)
            if (function_exists('apply_filters')) {
                $content = apply_filters('the_content', $content);
            } elseif (function_exists('orion_form_shortcode_parser')) {
                // Fallback direct call
                $content = orion_form_shortcode_parser($content);
            }
            
            // Render Page
            echo '<div class="container mx-auto px-6 py-20 min-h-screen">';
            echo '<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">';
            echo '<h1 class="text-3xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">' . htmlspecialchars($post->post_title) . '</h1>';
            echo '<div class="prose prose-slate max-w-none">' . $content . '</div>';
            echo '</div>';
            echo '</div>';
            
        } else {
            // 404 Not Found
            echo '<div class="container mx-auto py-32 text-center">';
            echo '<h1 class="text-6xl font-bold text-slate-200 mb-4">404</h1>';
            echo '<p class="text-xl text-slate-600">Halaman tidak ditemukan.</p>';
            echo '<a href="index.php" class="inline-block mt-6 text-brand-600 hover:text-brand-800 font-medium">&larr; Kembali ke Beranda</a>';
            echo '</div>';
        }
        break;
}

get_footer(); 
?>
