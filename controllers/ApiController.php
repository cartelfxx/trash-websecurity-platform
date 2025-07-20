<?php
class ApiController {
    private $db, $config_warning;
    
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    
    public function handle($uri) {
        header('Content-Type: application/json');
        
        if (strpos($uri, '/api/stats') === 0) {
            $this->getStats();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
        }
    }
    
    public function getStats() {
        require_once __DIR__ . '/../models/Log.php';
        $log = new Log($this->db);
        $stats = $log->getStats();
        echo json_encode($stats);
    }
} 