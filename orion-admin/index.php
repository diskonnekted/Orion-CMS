<?php
/**
 * Dashboard Administration Screen
 */

/** Load Orion Bootstrap */
require_once( dirname( dirname( __FILE__ ) ) . '/orion-load.php' );

require_once( 'admin-header.php' );

// Fetch Stats
global $orion_db, $table_prefix;

// 1. Posts Stats
$sql_posts_total = "SELECT COUNT(*) FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish'";
$posts_total = $orion_db->query($sql_posts_total)->fetch_row()[0];

$sql_posts_week = "SELECT COUNT(*) FROM {$table_prefix}posts WHERE post_type = 'post' AND post_status = 'publish' AND post_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$posts_week = $orion_db->query($sql_posts_week)->fetch_row()[0];

// 2. Pages Stats
$sql_pages_total = "SELECT COUNT(*) FROM {$table_prefix}posts WHERE post_type = 'page' AND post_status = 'publish'";
$pages_total = $orion_db->query($sql_pages_total)->fetch_row()[0];

// 3. Categories Stats
$sql_cats_total = "SELECT COUNT(*) FROM {$table_prefix}term_taxonomy WHERE taxonomy = 'category'";
$cats_total = $orion_db->query($sql_cats_total)->fetch_row()[0];

// 4. Users Stats
$sql_users_total = "SELECT COUNT(*) FROM {$table_prefix}users";
$users_total = $orion_db->query($sql_users_total)->fetch_row()[0];

$sql_users_week = "SELECT COUNT(*) FROM {$table_prefix}users WHERE user_registered >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$users_week = $orion_db->query($sql_users_week)->fetch_row()[0];

// Recent Activity
$recent_activities = [];

// Recent Posts
$sql_recent_posts = "SELECT p.ID, p.post_title, p.post_date, u.display_name 
                     FROM {$table_prefix}posts p 
                     LEFT JOIN {$table_prefix}users u ON p.post_author = u.ID 
                     WHERE p.post_type = 'post' AND p.post_status = 'publish' 
                     ORDER BY p.post_date DESC LIMIT 5";
$result_recent = $orion_db->query($sql_recent_posts);
if ($result_recent) {
    while($row = $result_recent->fetch_assoc()) {
        $recent_activities[] = [
            'type' => 'post',
            'title' => $row['post_title'],
            'author' => $row['display_name'],
            'date' => $row['post_date'],
            'url' => 'post.php?action=edit&post=' . $row['ID']
        ];
    }
}

// Recent Users
$sql_recent_users = "SELECT ID, display_name, user_registered FROM {$table_prefix}users ORDER BY user_registered DESC LIMIT 3";
$result_users = $orion_db->query($sql_recent_users);
if ($result_users) {
    while($row = $result_users->fetch_assoc()) {
        $recent_activities[] = [
            'type' => 'user',
            'title' => $row['display_name'],
            'author' => '',
            'date' => $row['user_registered'],
            'url' => 'user-edit.php?user_id=' . $row['ID']
        ];
    }
}

// Sort by date desc
usort($recent_activities, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
$recent_activities = array_slice($recent_activities, 0, 5);

// Time Ago Helper
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Generate Dummy Visitor Data for Chart
$chart_labels = [];
$chart_visitors = [];
$chart_pageviews = [];

for ($i = 29; $i >= 0; $i--) {
    $date = date('M d', strtotime("-$i days"));
    $chart_labels[] = $date;
    // Simulate some randomness but with a trend
    $base = 100 + (rand(0, 50));
    $weekend = (date('N', strtotime("-$i days")) >= 6) ? 0.7 : 1.0; // Lower on weekends
    
    $visitors = floor($base * $weekend * (1 + rand(-20, 30)/100));
    $pageviews = floor($visitors * (rand(15, 30)/10));
    
    $chart_visitors[] = $visitors;
    $chart_pageviews[] = $pageviews;
}
?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Animations -->
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }
</style>




<!-- Visitor Statistics Chart -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 overflow-hidden animate-fade-in-up delay-500">
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-slate-50/50">
        <div class="flex items-center gap-3 w-full md:w-auto md:flex-1 min-w-0">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="text-base font-semibold text-slate-800 flex items-center flex-wrap gap-2">
                    Traffic Overview
                    <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5 break-words leading-relaxed">Real-time visitor statistics for the last 30 days</p>
            </div>
        </div>
        <div class="flex gap-2 w-full md:w-auto flex-shrink-0">
            <button class="flex-1 md:flex-none inline-flex justify-center items-center px-2.5 py-1 rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
                <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5"></span> Visitors
            </button>
            <button class="flex-1 md:flex-none inline-flex justify-center items-center px-2.5 py-1 rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
                <span class="w-2 h-2 rounded-full bg-slate-400 mr-1.5"></span> Page Views
            </button>
        </div>
    </div>
    <div class="p-5 relative" style="height: 760px !important; min-height: 760px;">
        <canvas id="visitorChart" class="w-full h-full block"></canvas>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Posts -->
    <div class="bg-gradient-to-br from-white to-blue-50 backdrop-blur-sm rounded-xl shadow-sm border border-blue-100 p-5 relative overflow-hidden group hover:shadow-md hover:border-blue-200 hover:-translate-y-0.5 transition-all duration-300 animate-fade-in-up delay-100">
        <!-- Background Icon -->
        <div class="absolute -right-6 -bottom-6 text-blue-600 opacity-10 transform rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path stroke="none" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
        
        <div class="flex justify-between items-start z-10 relative mb-3">
            <div class="p-2.5 bg-white text-blue-600 rounded-lg shadow-sm border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <?php if($posts_week > 0): ?>
                <span class="flex items-center text-xs font-semibold text-emerald-600 bg-white/80 px-2 py-0.5 rounded-full border border-emerald-100 shadow-sm">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +<?php echo $posts_week; ?>
                </span>
            <?php endif; ?>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Total Posts</p>
            <h3 class="text-2xl font-semibold text-slate-700 tracking-tight group-hover:text-blue-600 transition-colors"><?php echo $posts_total; ?></h3>
        </div>
    </div>

    <!-- Pages -->
    <div class="bg-gradient-to-br from-white to-purple-50 backdrop-blur-sm rounded-xl shadow-sm border border-purple-100 p-5 relative overflow-hidden group hover:shadow-md hover:border-purple-200 hover:-translate-y-0.5 transition-all duration-300 animate-fade-in-up delay-200">
        <!-- Background Icon -->
        <div class="absolute -right-6 -bottom-6 text-purple-600 opacity-10 transform rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path stroke="none" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
        
        <div class="flex justify-between items-start z-10 relative mb-3">
            <div class="p-2.5 bg-white text-purple-600 rounded-lg shadow-sm border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Total Pages</p>
            <h3 class="text-2xl font-semibold text-slate-700 tracking-tight group-hover:text-purple-600 transition-colors"><?php echo $pages_total; ?></h3>
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-gradient-to-br from-white to-amber-50 backdrop-blur-sm rounded-xl shadow-sm border border-amber-100 p-5 relative overflow-hidden group hover:shadow-md hover:border-amber-200 hover:-translate-y-0.5 transition-all duration-300 animate-fade-in-up delay-300">
        <!-- Background Icon -->
        <div class="absolute -right-6 -bottom-6 text-amber-600 opacity-10 transform rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path stroke="none" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-amber-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
        
        <div class="flex justify-between items-start z-10 relative mb-3">
            <div class="p-2.5 bg-white text-amber-600 rounded-lg shadow-sm border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Categories</p>
            <h3 class="text-2xl font-semibold text-slate-700 tracking-tight group-hover:text-amber-600 transition-colors"><?php echo $cats_total; ?></h3>
        </div>
    </div>

    <!-- Users -->
    <div class="bg-gradient-to-br from-white to-rose-50 backdrop-blur-sm rounded-xl shadow-sm border border-rose-100 p-5 relative overflow-hidden group hover:shadow-md hover:border-rose-200 hover:-translate-y-0.5 transition-all duration-300 animate-fade-in-up delay-400">
        <!-- Background Icon -->
        <div class="absolute -right-6 -bottom-6 text-rose-600 opacity-10 transform rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path stroke="none" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-rose-400 to-rose-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
        
        <div class="flex justify-between items-start z-10 relative mb-3">
            <div class="p-2.5 bg-white text-rose-600 rounded-lg shadow-sm border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <?php if($users_week > 0): ?>
                <span class="flex items-center text-xs font-semibold text-emerald-600 bg-white/80 px-2 py-0.5 rounded-full border border-emerald-100 shadow-sm">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +<?php echo $users_week; ?>
                </span>
            <?php endif; ?>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Users</p>
            <h3 class="text-2xl font-semibold text-slate-700 tracking-tight group-hover:text-rose-600 transition-colors"><?php echo $users_total; ?></h3>
        </div>
    </div>
</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-500">
    <!-- Quick Draft -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-fit overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <h2 class="text-base font-semibold text-slate-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orion-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Quick Draft
            </h2>
        </div>
        <div class="p-5">
            <form>
                <div class="mb-3">
                    <label class="block text-slate-700 text-sm font-medium mb-1.5" for="title">Title</label>
                    <input class="w-full rounded-lg border-slate-200 bg-slate-50/50 shadow-sm focus:border-orion-500 focus:ring-orion-500 focus:bg-white text-sm transition-all p-2.5" id="title" type="text" placeholder="Enter post title...">
                </div>
                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-medium mb-1.5" for="content">Content</label>
                    <textarea class="w-full rounded-lg border-slate-200 bg-slate-50/50 shadow-sm focus:border-orion-500 focus:ring-orion-500 focus:bg-white text-sm h-32 resize-none transition-all p-2.5" id="content" placeholder="What's on your mind?"></textarea>
                </div>
                <button class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-800 flex justify-center items-center group transform active:scale-95" type="button">
                    <span>Save Draft</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-base font-semibold text-slate-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Recent Activity
            </h2>
            <a href="posts.php" class="text-sm font-medium text-orion-600 hover:text-orion-700 hover:underline flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="p-5 flex-grow">
            <ul class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                <?php if (empty($recent_activities)): ?>
                    <li class="pl-6 text-slate-500 italic text-sm">No recent activity found.</li>
                <?php else: ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <li class="relative pl-8 group">
                            <?php if ($activity['type'] == 'post'): ?>
                                <span class="absolute -left-[11px] top-1 h-5 w-5 rounded-full border-4 border-white bg-orion-500 shadow-sm group-hover:scale-110 transition-transform duration-300"></span>
                                <div class="p-3 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-900"><?php echo htmlspecialchars($activity['author']); ?></span> published a new post</p>
                                        <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full whitespace-nowrap"><?php echo time_elapsed_string($activity['date']); ?></span>
                                    </div>
                                    <a href="<?php echo $activity['url']; ?>" class="block mt-1 text-sm font-semibold text-slate-800 hover:text-orion-600 transition-colors">
                                        <?php echo htmlspecialchars($activity['title']); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="absolute -left-[11px] top-1 h-5 w-5 rounded-full border-4 border-white bg-rose-500 shadow-sm group-hover:scale-110 transition-transform duration-300"></span>
                                <div class="p-3 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-900">New User</span> registered</p>
                                        <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full whitespace-nowrap"><?php echo time_elapsed_string($activity['date']); ?></span>
                                    </div>
                                    <p class="block mt-1 text-sm font-semibold text-slate-800">
                                        <?php echo htmlspecialchars($activity['title']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Initialize Chart.js -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitorChart').getContext('2d');
        
        // Gradient for Visitors
        let gradientVisitors = ctx.createLinearGradient(0, 0, 0, 760);
        gradientVisitors.addColorStop(0, 'rgba(59, 130, 246, 0.4)'); // orion-500 with more opacity
        gradientVisitors.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

        // Gradient for Page Views
        let gradientViews = ctx.createLinearGradient(0, 0, 0, 760);
        gradientViews.addColorStop(0, 'rgba(148, 163, 184, 0.3)'); // slate-400
        gradientViews.addColorStop(1, 'rgba(148, 163, 184, 0.05)');

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [
                    {
                        label: 'Unique Visitors',
                        data: <?php echo json_encode($chart_visitors); ?>,
                        borderColor: '#3b82f6', // orion-500
                        backgroundColor: gradientVisitors,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverBackgroundColor: '#3b82f6',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Page Views',
                        data: <?php echo json_encode($chart_pageviews); ?>,
                        borderColor: '#94a3b8', // slate-400
                        backgroundColor: gradientViews,
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#94a3b8',
                        pointHoverBackgroundColor: '#94a3b8',
                        pointHoverBorderColor: '#ffffff',
                        fill: true,
                        tension: 0.4,
                        hidden: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Hidden because we have custom legend buttons
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        boxPadding: 6,
                        titleFont: {
                            size: 14,
                            family: "'Inter', sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            family: "'Inter', sans-serif"
                        },
                        callbacks: {
                            labelTextColor: function(context) {
                                return '#475569';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            color: '#94a3b8',
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            color: '#94a3b8',
                            maxTicksLimit: 8,
                            padding: 10
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    });
</script>

<?php require_once( 'admin-footer.php' ); ?>
