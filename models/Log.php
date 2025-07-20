<?php

class Log {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function logVisit($ip, $user_agent, $uri) {
        $stmt = $this->db->prepare("INSERT INTO logs (ip_address, user_agent, uri, event_type, datetime) VALUES (?, ?, ?, 'visit', NOW())");
        $stmt->execute([$ip, $user_agent, $uri]);
    }
    
    public function getStats() {
        $stats = [];
        

        $stmt = $this->db->query("SELECT COUNT(*) as total_visits FROM logs WHERE event_type = 'visit'");
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_visits'];
        

        $stmt = $this->db->query("SELECT COUNT(*) as today_visits FROM logs WHERE event_type = 'visit' AND DATE(datetime) = CURDATE()");
        $stats['today'] = $stmt->fetch(PDO::FETCH_ASSOC)['today_visits'];
        

        $stmt = $this->db->query("SELECT COUNT(*) as blocked_count FROM blocked_ips");
        $stats['blocked'] = $stmt->fetch(PDO::FETCH_ASSOC)['blocked_count'];
        

        $stmt = $this->db->query("SELECT COUNT(DISTINCT ip_address) as unique_ips FROM logs WHERE event_type = 'visit'");
        $stats['unique_ips'] = $stmt->fetch(PDO::FETCH_ASSOC)['unique_ips'];
        

        $stmt = $this->db->query("SELECT DATE(datetime) as date, COUNT(*) as count FROM logs WHERE event_type = 'visit' AND datetime >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(datetime) ORDER BY date");
        $stats['weekly_visits'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM logs ORDER BY datetime DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function logEvent($db, $user_id, $ip, $ua, $uri, $event_type, $event_data) {
        $stmt = $db->prepare("INSERT INTO logs (user_id, ip_address, user_agent, uri, event_type, event_data, datetime) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $ip, $ua, $uri, $event_type, json_encode($event_data)]);
    }
}
