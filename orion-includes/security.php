<?php
/**
 * Orion Security Module
 * Provides protection against SQL Injection, XSS, Brute Force, and DDoS.
 */

/**
 * Resolve a directory for runtime artifacts that must not be web-accessible.
 *
 * Hosts that serve PHP through php-fpm behind nginx ignore .htaccess, so any
 * file written under the document root is downloadable by URL: the rate-limit
 * counters would leak client IPs and the security log would leak internals.
 * Prefer a sibling directory of the install, then the system temp dir.
 *
 * @return string Absolute directory path with a trailing separator.
 */
function orion_runtime_dir() {
    static $dir = null;
    if ($dir !== null) {
        return $dir;
    }

    $candidates = array();

    if (defined('ORION_RUNTIME_DIR') && ORION_RUNTIME_DIR) {
        $candidates[] = rtrim(ORION_RUNTIME_DIR, '/\\') . DIRECTORY_SEPARATOR;
    }

    $root = rtrim(ABSPATH, '/\\');
    $parent = dirname($root);
    if ($parent !== '' && $parent !== $root) {
        $candidates[] = $parent . DIRECTORY_SEPARATOR . 'orion-runtime' . DIRECTORY_SEPARATOR;
    }

    $candidates[] = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR
        . 'orion-runtime-' . substr(md5($root), 0, 12) . DIRECTORY_SEPARATOR;

    foreach ($candidates as $candidate) {
        if (!is_dir($candidate)) {
            @mkdir($candidate, 0700, true);
        }
        if (is_dir($candidate) && is_writable($candidate)) {
            return $dir = $candidate;
        }
    }

    // Last resort: the historical location, so brute-force protection still works.
    $fallback = rtrim(ABSPATH, '/\\') . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
    if (!is_dir($fallback)) {
        @mkdir($fallback, 0755, true);
    }
    return $dir = $fallback;
}

class Orion_Security {
    
    private $log_file;
    private $ban_file;
    
    public function __construct() {
        $runtime = orion_runtime_dir();
        $this->log_file = $runtime . 'security.log';
        $this->ban_file = $runtime . 'banned_ips.json';

        // .htaccess only helps on Apache. Writing "deny from all" into a
        // directory above the install would block every neighbouring site, so
        // only protect runtime dirs that sit inside the install itself.
        $runtime_real = @realpath($runtime);
        $root_real = @realpath(ABSPATH);
        if ($runtime_real && $root_real && strpos($runtime_real . DIRECTORY_SEPARATOR, $root_real . DIRECTORY_SEPARATOR) === 0) {
            $htaccess = $runtime . '.htaccess';
            if (!file_exists($htaccess)) {
                file_put_contents($htaccess, "Order Deny,Allow\nDeny from all\nRequire all denied");
            }
        }
    }

    /**
     * Initialize all security checks
     */
    public function init() {
        $this->set_security_headers();
        $this->check_ban();
        $this->ddos_protection(); // Simple rate limiting per IP
        $this->sanitize_request_globals();
    }

    /**
     * Set standard security headers
     */
    private function set_security_headers() {
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https:; img-src 'self' data: https:;");
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }

    /**
     * WAF: Sanitize Superglobals to prevent SQLi and XSS (only remove null bytes for safety)
     * NOTE: htmlspecialchars() should be applied at OUTPUT time, not INPUT time!
     */
    private function sanitize_request_globals() {
        $_GET     = $this->clean_input($_GET);
        $_POST    = $this->clean_input($_POST);
        $_REQUEST = $this->clean_input($_REQUEST);

        // Cookies are transmitted as opaque tokens, never rendered as HTML.
        // Entity encoding them corrupts signed values such as the auth cookie.
    }

    /**
     * Recursive cleaner - removes null bytes and logs injection attempts.
     *
     * Data is deliberately NOT modified here: escaping belongs at output
     * time, otherwise values are corrupted between save and display.
     */
    private function clean_input($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->clean_input($value);
            }
            return $data;
        }

        $data = str_replace(chr(0), '', (string) $data);

        $sql_patterns = array(
            '/union\s+select/i',
            '/union\s+all\s+select/i',
            '/information_schema/i',
            '/--\s/',
            '/\/\*/'
        );

        foreach ($sql_patterns as $pattern) {
            if (preg_match($pattern, $data)) {
                $this->log_event("Potential SQL Injection detected: " . $pattern);
            }
        }

        return $data;
    }

    /**
     * DDoS Protection: Rate Limiting
     * Limit requests per minute per IP
     */
    private function get_client_ip() {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function ddos_protection() {
        $ip = $this->get_client_ip();
        $limit = 100; // Max requests per minute
        $window = 60; // Time window in seconds
        
        $file = orion_runtime_dir() . 'rate_limit_' . md5($ip) . '.txt';
        
        $current_time = time();
        $data = array('count' => 0, 'start_time' => $current_time);
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (!is_array($data)) { $data = array('attempts' => 0, 'last_attempt' => 0); }
            if (!$data) $data = array('count' => 0, 'start_time' => $current_time);
        }
        
        // Reset window if expired
        if ($current_time - $data['start_time'] > $window) {
            $data['start_time'] = $current_time;
            $data['count'] = 0;
        }
        
        $data['count']++;
        
        file_put_contents($file, json_encode($data));
        
        if ($data['count'] > $limit) {
            header("HTTP/1.1 429 Too Many Requests");
            die("<h1>429 Too Many Requests</h1><p>You have exceeded the rate limit. Please try again later.</p>");
        }
    }

    /**
     * Brute Force Protection for Login
     * @param string $username
     * @return bool True if allowed, False if blocked
     */
    public function check_login_attempts($ip) {
        $limit = 5; // Max failed attempts
        $window = 900; // 15 minutes lock
        
        $file = orion_runtime_dir() . 'login_attempts_' . md5($ip) . '.txt';
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (!is_array($data)) { $data = array('attempts' => 0, 'last_attempt' => 0); }
            
            // Check if locked out
            if ($data['attempts'] >= $limit) {
                if (time() - $data['last_attempt'] < $window) {
                    return false; // Still locked
                } else {
                    // Reset after window expires
                    $data['attempts'] = 0;
                }
            }
        } else {
            $data = array('attempts' => 0, 'last_attempt' => 0);
        }
        
        return true;
    }

    /**
     * Record a failed login attempt
     */
    public function log_failed_login($ip) {
        $file = orion_runtime_dir() . 'login_attempts_' . md5($ip) . '.txt';
        
        $data = array('attempts' => 0, 'last_attempt' => time());
        
        if (file_exists($file)) {
            $existing = json_decode(file_get_contents($file), true);
            if (!is_array($existing)) { $existing = array('attempts' => 0, 'last_attempt' => 0); }
            if ($existing) {
                $data['attempts'] = $existing['attempts'];
            }
        }
        
        $data['attempts']++;
        $data['last_attempt'] = time();
        
        file_put_contents($file, json_encode($data));
    }
    
    /**
     * Clear failed login attempts on success
     */
    public function clear_login_attempts($ip) {
        $file = orion_runtime_dir() . 'login_attempts_' . md5($ip) . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function check_ban() {
        $ip = $this->get_client_ip();
        if (file_exists($this->ban_file)) {
            $banned = json_decode(file_get_contents($this->ban_file), true);
            if (is_array($banned) && in_array($ip, $banned, true)) {
                die("Your IP has been banned due to suspicious activity.");
            }
        }
    }

    private function log_event($message) {
        $entry = date('Y-m-d H:i:s') . " - [" . $this->get_client_ip() . "] " . $message . PHP_EOL;
        file_put_contents($this->log_file, $entry, FILE_APPEND);
    }
}

// Instantiate and run immediately
global $orion_security;
$orion_security = new Orion_Security();
$orion_security->init();
