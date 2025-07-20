<?php
class BlockedController {
    private $db, $config_warning;
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    public function index() {
        require_once __DIR__ . '/../models/IpBlocker.php';
        $ipBlocker = new IpBlocker($this->db);
        $blocked = $ipBlocker->getBlocked();
        require __DIR__ . '/../views/blocked.php';
    }
    public function blockIp() {
        $ip = $_POST['ip'] ?? '';
        $reason = $_POST['reason'] ?? '';
        if ($ip) {
            require_once __DIR__ . '/../models/IpBlocker.php';
            $ipBlocker = new IpBlocker($this->db);
            $ipBlocker->block($ip, $reason);
        }
        header('Location: /blocked');
        exit;
    }
    public function unblockIp() {
        $ip = $_POST['ip'] ?? '';
        if ($ip) {
            require_once __DIR__ . '/../models/IpBlocker.php';
            $ipBlocker = new IpBlocker($this->db);
            $ipBlocker->unblock($ip);
        }
        header('Location: /blocked');
        exit;
    }
}
