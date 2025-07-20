<?php

class RateLimiter {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->createTable();
    }
    
    private function createTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS rate_limits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45),
            request_count INT DEFAULT 1,
            first_request DATETIME,
            last_request DATETIME,
            INDEX idx_ip_time (ip_address, last_request)
        )");
    }
    
    public function checkRateLimit($ip, $max_requests = 10, $time_window = 60) {
        $now = date('Y-m-d H:i:s');
        $cutoff = date('Y-m-d H:i:s', time() - $time_window);
        

        $stmt = $this->db->prepare("SELECT * FROM rate_limits WHERE ip_address = ? AND last_request > ?");
        $stmt->execute([$ip, $cutoff]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($record) {

            $new_count = $record['request_count'] + 1;
            $stmt = $this->db->prepare("UPDATE rate_limits SET request_count = ?, last_request = ? WHERE id = ?");
            $stmt->execute([$new_count, $now, $record['id']]);
            

            if ($new_count > $max_requests) {
                return false; // Limit aşıldı
            }
        } else {

            $stmt = $this->db->prepare("INSERT INTO rate_limits (ip_address, request_count, first_request, last_request) VALUES (?, 1, ?, ?)");
            $stmt->execute([$ip, $now, $now]);
        }
        
        return true; // Limit aşılmadı
    }
    
    public function getRateLimitInfo($ip) {
        $stmt = $this->db->prepare("SELECT * FROM rate_limits WHERE ip_address = ? ORDER BY last_request DESC LIMIT 1");
        $stmt->execute([$ip]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function resetRateLimit($ip) {
        $stmt = $this->db->prepare("DELETE FROM rate_limits WHERE ip_address = ?");
        $stmt->execute([$ip]);
    }
} 