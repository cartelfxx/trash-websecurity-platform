<?php
class SecurityMiddleware {
    public static function handle($db) {
        require_once __DIR__ . '/../models/IPTools.php';
        require_once __DIR__ . '/../models/DDoSProtector.php';
        require_once __DIR__ . '/../models/BruteForceProtector.php';

        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $uri = $_SERVER['REQUEST_URI'];


        if (IPTools::isBlocked($db, $ip)) {
            require __DIR__ . '/../views/403.php';
            exit;
        }


        DDoSProtector::check($db, $ip);


        BruteForceProtector::check($db, $ip, $uri);


        require_once __DIR__ . '/../models/Log.php';
        Log::logEvent($db, null, $ip, $ua, $uri, 'visit', null);
    }
}
