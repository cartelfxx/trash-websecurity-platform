<?php

class IpBlocker {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function isBlocked($ip) {
        $stmt = $this->db->prepare("SELECT * FROM blocked_ips WHERE ip_address = ?");
        $stmt->execute([$ip]);
        return $stmt->fetch() ? true : false;
    }
    public function getBlocked() {
        return $this->db->query("SELECT * FROM blocked_ips ORDER BY blocked_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function block($ip, $reason = '') {
        $stmt = $this->db->prepare("INSERT IGNORE INTO blocked_ips (ip_address, reason, blocked_at) VALUES (?, ?, NOW())");
        $stmt->execute([$ip, $reason]);
    }
    public function unblock($ip) {
        $stmt = $this->db->prepare("DELETE FROM blocked_ips WHERE ip_address = ?");
        $stmt->execute([$ip]);
    }
}
