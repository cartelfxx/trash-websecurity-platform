<?php


require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Log.php';
require_once __DIR__ . '/models/IpBlocker.php';
require_once __DIR__ . '/models/RateLimiter.php';


$ipBlocker = new IpBlocker($db);
$log = new Log($db);
$rateLimiter = new RateLimiter($db);


$log->logVisit($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '', $_SERVER['REQUEST_URI']);


if (!$rateLimiter->checkRateLimit($_SERVER['REMOTE_ADDR'], 10, 60)) {

    $ipBlocker->block($_SERVER['REMOTE_ADDR'], 'Rate limit aşıldı - Çok fazla istek');
    require __DIR__ . '/views/403.php';
    exit;
}


if ($ipBlocker->isBlocked($_SERVER['REMOTE_ADDR'])) {
    require __DIR__ . '/views/403.php';
    exit;
}


require_once __DIR__ . '/routes.php';
