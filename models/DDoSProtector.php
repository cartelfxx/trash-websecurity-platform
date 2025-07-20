<?php
class DDoSProtector {
    public static function check($db, $ip) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        $key = "ddos_{$ip}";
        $now = time();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'start' => $now];
        } else {
            if ($now - $_SESSION[$key]['start'] < 10) {
                $_SESSION[$key]['count']++;
                if ($_SESSION[$key]['count'] > 50) {

                    $stmt = $db->prepare("INSERT IGNORE INTO blocked_ips (ip_address, reason, blocked_at) VALUES (?, 'DDoS detected', NOW())");
                    $stmt->execute([$ip]);


                    require_once __DIR__ . '/Log.php';
                    Log::logEvent($db, null, $ip, '', '', 'ddos_block', null);


                    require __DIR__ . '/../views/403.php';
                    exit;
                }
            } else {

                $_SESSION[$key] = ['count' => 1, 'start' => $now];
            }
        }
    }
}
