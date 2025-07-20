<?php
class DashboardController {
    private $db, $config_warning;
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    
    public function handle($uri) {

        $this->index();
    }
    
    public function index() {
        require_once __DIR__ . '/../models/Log.php';
        $log = new Log($this->db);
        $stats = $log->getStats();
        require __DIR__ . '/../views/dashboard.php';
    }
}
