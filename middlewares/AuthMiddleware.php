<?php
class AuthMiddleware {
    public static function handle($db) {
        $public_routes = ['/login', '/api'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (!in_array($uri, $public_routes) && !isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
