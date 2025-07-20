<?php


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/dashboard' => 'DashboardController',
    '/logs' => 'LogsController',
    '/security' => 'SecurityController',
    '/login' => 'AuthController',
    '/logout' => 'AuthController',
    '/users' => 'UserController',
    '/api' => 'ApiController'
];

if ($uri === '/' || $uri === '/index.php') {
    header('Location: /dashboard');
    exit;
}

foreach ($routes as $route => $controllerName) {
    if (strpos($uri, $route) === 0) {
        require_once __DIR__ . "/controllers/{$controllerName}.php";
        

        $controller = new $controllerName($db, $config_warning);
        $controller->handle($uri);
        exit;
    }
}

http_response_code(404);
echo "<h1>404 Not Found</h1>";
