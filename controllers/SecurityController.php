<?php
class SecurityController {
    private $db, $config_warning;
    
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    
    public function handle($uri) {
        $this->index();
    }
    
    public function index() {
        require_once __DIR__ . '/../models/IpBlocker.php';
        $ipBlocker = new IpBlocker($this->db);
        $blocked_ips = $ipBlocker->getBlocked();
        require __DIR__ . '/../views/blocked.php';
    }
} 