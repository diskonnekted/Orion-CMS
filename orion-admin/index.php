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
?>

<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
        <p class="text-slate-500 mt-1">Overview of your website performance.</p>
    </div>
    <div class="hidden sm:block">
        <span class="text-sm text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
            <?php echo date('l, d F Y'); ?>
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Posts -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Posts</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2"><?php echo $posts_total; ?></h3>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-500 flex items-center font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +<?php echo $posts_week; ?>
            </span>
            <span class="text-slate-400 ml-2">this week</span>
        </div>
    </div>

    <!-- Pages -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500">Pages</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2"><?php echo $pages_total; ?></h3>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-slate-400">Static content</span>
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500">Categories</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2"><?php echo $cats_total; ?></h3>
            </div>
            <div class="p-2 bg-violet-50 text-violet-600 rounded-lg group-hover:bg-violet-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-slate-400">Post organization</span>
        </div>
    </div>

    <!-- Users -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500">Users</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2"><?php echo $users_total; ?></h3>
            </div>
            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-500 flex items-center font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +<?php echo $users_week; ?>
            </span>
            <span class="text-slate-400 ml-2">this week</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800">Recent Activity</h2>
            <a href="#" class="text-sm font-medium text-orion-600 hover:text-orion-700 hover:underline">View All</a>
        </div>
        <div class="p-6">
            <ul class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                <?php if (empty($recent_activities)): ?>
                    <li class="pl-6 text-slate-500">No recent activity.</li>
                <?php else: ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <li class="relative pl-6">
                            <?php if ($activity['type'] == 'post'): ?>
                                <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-2 border-white bg-orion-500 ring-4 ring-orion-50"></span>
                                <div>
                                    <p class="text-sm text-slate-600"><span class="font-bold text-slate-900"><?php echo htmlspecialchars($activity['author']); ?></span> published a new post: <a href="<?php echo $activity['url']; ?>" class="text-orion-600 font-medium hover:underline"><?php echo htmlspecialchars($activity['title']); ?></a></p>
                                    <p class="text-xs text-slate-400 mt-1"><?php echo time_elapsed_string($activity['date']); ?></p>
                                </div>
                            <?php else: ?>
                                <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-2 border-white bg-amber-500 ring-4 ring-amber-50"></span>
                                <div>
                                    <p class="text-sm text-slate-600"><span class="font-bold text-slate-900">New User</span> registered: <?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-xs text-slate-400 mt-1"><?php echo time_elapsed_string($activity['date']); ?></p>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Quick Draft -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800">Quick Draft</h2>
        </div>
        <div class="p-6">
            <form>
                <div class="mb-4">
                    <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider" for="title">Title</label>
                    <input class="w-full rounded-lg border-slate-300 shadow-sm focus:border-orion-500 focus:ring-orion-500 sm:text-sm" id="title" type="text" placeholder="Post Title">
                </div>
                <div class="mb-4">
                    <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider" for="content">Content</label>
                    <textarea class="w-full rounded-lg border-slate-300 shadow-sm focus:border-orion-500 focus:ring-orion-500 sm:text-sm h-32 resize-none" id="content" placeholder="What's on your mind?"></textarea>
                </div>
                <button class="w-full bg-orion-600 hover:bg-orion-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orion-500" type="button">
                    Save Draft
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once( 'admin-footer.php' ); ?>