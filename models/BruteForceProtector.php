<?php
class BruteForceProtector {
    public static function check($db, $ip, $uri) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (strpos($uri, '/login') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $key = "brute_{$ip}";
            $now = time();

            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = ['count' => 1, 'start' => $now];
            } else {
                if ($now - $_SESSION[$key]['start'] < 300) { // 5 dakika
                    $_SESSION[$key]['count']++;
                    if ($_SESSION[$key]['count'] > 10) {

                        $stmt = $db->prepare("INSERT IGNORE INTO blocked_ips (ip_address, reason, blocked_at) VALUES (?, 'Brute force detected', NOW())");
                        $stmt->execute([$ip]);

                        require_once __DIR__ . '/Log.php';
                        Log::logEvent($db, null, $ip, '', '', 'brute_block', null);

                        require __DIR__ . '/../views/403.php';
                        exit;
                    }
                } else {
                    $_SESSION[$key] = ['count' => 1, 'start' => $now];
                }
            }
        }
    }
}
