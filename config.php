<?php


$config_warning = false;
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    define('DB_HOST', $env['DB_HOST']);
    define('DB_USER', $env['DB_USER']);
    define('DB_PASS', $env['DB_PASS']);
    define('DB_NAME', $env['DB_NAME']);
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'security_platform');
    $config_warning = true;
}
try {
    $db = new PDO('mysql:host='.DB_HOST, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $db->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."`");
    $db->exec("USE `".DB_NAME."`");

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(64) UNIQUE,
        password VARCHAR(255),
        email VARCHAR(128),
        mfa_secret VARCHAR(255),
        role VARCHAR(32),
        status VARCHAR(16),
        created_at DATETIME,
        last_login DATETIME
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        ip_address VARCHAR(45),
        user_agent VARCHAR(255),
        uri VARCHAR(255),
        event_type VARCHAR(64),
        event_data TEXT,
        datetime DATETIME
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS security_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_type VARCHAR(64),
        ip_address VARCHAR(45),
        user_id INT,
        details TEXT,
        detected_at DATETIME
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS blocked_ips (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) UNIQUE,
        reason VARCHAR(255),
        blocked_at DATETIME,
        geo_info TEXT,
        reputation_score INT
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS ddos_attacks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45),
        attack_type VARCHAR(64),
        request_count INT,
        started_at DATETIME,
        mitigated_at DATETIME
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS brute_force_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45),
        username VARCHAR(64),
        attempt_count INT,
        last_attempt DATETIME,
        locked_until DATETIME
    )");
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
