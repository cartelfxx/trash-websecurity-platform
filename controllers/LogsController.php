<?php
class LogsController {
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
        $logs = $log->getAll();
        require __DIR__ . '/../views/logs.php';
    }
}
