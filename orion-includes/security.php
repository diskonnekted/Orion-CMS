<?php
/**
 * Orion Security Module
 * Provides protection against SQL Injection, XSS, Brute Force, and DDoS.
 */

class Orion_Security {
    
    private $log_file;
    private $ban_file;
    
    public function __construct() {
        $this->log_file = ABSPATH . 'tmp/security.log';
        $this->ban_file = ABSPATH . 'tmp/banned_ips.json';
        
        // Ensure tmp directory exists
        if (!file_exists(dirname($this->log_file))) {
            mkdir(dirname($this->log_file), 0755, true);
        }
        
        // Protect tmp directory
        $htaccess = dirname($this->log_file) . '/.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Order Deny,Allow\nDeny from all");
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
        // header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline' 'unsafe-eval';"); // Commented out to prevent breaking existing scripts
    }

    /**
     * WAF: Sanitize Superglobals to prevent SQLi and XSS
     */
    private function sanitize_request_globals() {
        $_GET     = $this->clean_input($_GET);
        $_POST    = $this->clean_input($_POST);
        $_COOKIE  = $this->clean_input($_COOKIE);
        $_REQUEST = $this->clean_input($_REQUEST);
    }

    /**
     * Recursive cleaner
     */
    private function clean_input($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->clean_input($value);
            }
        } else {
            // Remove NULL bytes
            $data = str_replace(chr(0), '', $data);
            
            // Basic SQL Injection filters (if not using prepared statements everywhere)
            // Note: This is a fallback defense. Prepared statements are always better.
            $sql_patterns = array(
                '/union\s+select/i',
                '/union\s+all\s+select/i',
                '/information_schema/i',
                '/--\s/',
                '/\/\*/'
            );
            
            // XSS Filters
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            
            // Check for SQLi attempts and log/block
            foreach ($sql_patterns as $pattern) {
                if (preg_match($pattern, $data)) {
                    $this->log_event("Potential SQL Injection detected: " . $pattern);
                    // die("Security Violation Detected."); // Optional: block request immediately
                }
            }
        }
        return $data;
    }

    /**
     * DDoS Protection: Rate Limiting
     * Limit requests per minute per IP
     */
    private function ddos_protection() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $limit = 100; // Max requests per minute
        $window = 60; // Time window in seconds
        
        $file = ABSPATH . 'tmp/rate_limit_' . md5($ip) . '.txt';
        
        $current_time = time();
        $data = array('count' => 0, 'start_time' => $current_time);
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
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
        
        $file = ABSPATH . 'tmp/login_attempts_' . md5($ip) . '.txt';
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            
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
        $file = ABSPATH . 'tmp/login_attempts_' . md5($ip) . '.txt';
        
        $data = array('attempts' => 0, 'last_attempt' => time());
        
        if (file_exists($file)) {
            $existing = json_decode(file_get_contents($file), true);
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
        $file = ABSPATH . 'tmp/login_attempts_' . md5($ip) . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function check_ban() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (file_exists($this->ban_file)) {
            $banned = json_decode(file_get_contents($this->ban_file), true);
            if (in_array($ip, $banned)) {
                die("Your IP has been banned due to suspicious activity.");
            }
        }
    }

    private function log_event($message) {
        $entry = date('Y-m-d H:i:s') . " - [" . $_SERVER['REMOTE_ADDR'] . "] " . $message . PHP_EOL;
        file_put_contents($this->log_file, $entry, FILE_APPEND);
    }
}

// Instantiate and run immediately
global $orion_security;
$orion_security = new Orion_Security();
$orion_security->init();
