<?php
/**
 * Orion CMS Login Page
 */
require_once 'orion-load.php';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    wp_logout();
    header('Location: login.php?loggedout=true');
    exit;
}

// Redirect if already logged in
if (is_user_logged_in()) {
    header('Location: orion-admin/index.php');
    exit;
}

$error = '';

// Security: Check Brute Force
global $orion_security;
if (isset($orion_security) && !$orion_security->check_login_attempts($_SERVER['REMOTE_ADDR'])) {
    die("<h1>Too many failed login attempts. Please try again in 15 minutes.</h1>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wp-submit'])) {
    $username = isset($_POST['log']) ? trim($_POST['log']) : '';
    $password = isset($_POST['pwd']) ? $_POST['pwd'] : '';
    $remember = isset($_POST['rememberme']);
    
    $user = wp_signon(array(
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember
    ));
    
    if ($user instanceof WP_User) {
        // Security: Clear failed attempts
        if (isset($orion_security)) {
            $orion_security->clear_login_attempts($_SERVER['REMOTE_ADDR']);
        }
        
        header('Location: orion-admin/index.php');
        exit;
    } else {
        // Security: Log failed attempt
        if (isset($orion_security)) {
            $orion_security->log_failed_login($_SERVER['REMOTE_ADDR']);
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In &lsaquo; <?php echo htmlspecialchars(get_option('blogname', 'Orion CMS')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo site_url('/assets/img/favicon.png'); ?>">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        orion: {
                            500: '#3b82f6',
                            600: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6">
        <div class="text-center mb-8">
            <img src="<?php echo get_option('site_logo', site_url('/assets/img/orion-logo.png')); ?>" alt="Orion CMS" class="h-16 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-slate-800">Sign in to Orion</h1>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <form name="loginform" id="loginform" action="login.php" method="post">
                <div class="mb-5">
                    <label for="user_login" class="block text-sm font-medium text-slate-700 mb-1">Username or Email Address</label>
                    <input type="text" name="log" id="user_login" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orion-500 focus:border-orion-500 transition-shadow" value="" size="20" autocapitalize="off" autocomplete="username" required>
                </div>
                
                <div class="mb-5">
                    <div class="flex justify-between items-center mb-1">
                        <label for="user_pass" class="block text-sm font-medium text-slate-700">Password</label>
                    </div>
                    <input type="password" name="pwd" id="user_pass" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orion-500 focus:border-orion-500 transition-shadow" value="" size="20" autocomplete="current-password" required>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" class="h-4 w-4 text-orion-600 focus:ring-orion-500 border-gray-300 rounded">
                        <label for="rememberme" class="ml-2 block text-sm text-slate-600">Remember Me</label>
                    </div>
                    <a href="#" class="text-sm text-orion-600 hover:text-orion-500 font-medium">Forgot password?</a>
                </div>
                
                <div>
                    <button type="submit" name="wp-submit" id="wp-submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orion-600 hover:bg-orion-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orion-500 transition-colors">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center text-sm text-slate-500 mt-6">
            &larr; <a href="<?php echo site_url(); ?>" class="hover:text-slate-800 transition-colors">Back to <?php echo get_option('blogname', 'Orion Site'); ?></a>
        </p>
    </div>
</body>
</html>
