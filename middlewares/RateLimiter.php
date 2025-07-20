<?php
class RateLimiter {
    public static function handle($db) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uri = $_SERVER['REQUEST_URI'];
        $now = time();
        $key = "rate_limit_{$ip}";
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'start' => $now];
        } else {
            if ($now - $_SESSION[$key]['start'] < 60) {
                $_SESSION[$key]['count']++;
                if ($_SESSION[$key]['count'] > 100) { // 100 istek/dk limiti
                    http_response_code(429);
                    echo "<h1>Too Many Requests</h1>";
                    exit;
                }
            } else {
                $_SESSION[$key] = ['count' => 1, 'start' => $now];
            }
        }
    }
}
