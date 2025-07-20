<?php
class IPTools {
    public static function isBlocked($db, $ip) {
        $stmt = $db->prepare("SELECT * FROM blocked_ips WHERE ip_address = ?");
        $stmt->execute([$ip]);
        return $stmt->fetch() ? true : false;
    }
    public static function geoInfo($ip) {

        $json = @file_get_contents("http://ip-api.com/json/{$ip}");
        return $json ? json_decode($json, true) : null;
    }
    public static function reputation($ip) {

        return rand(0, 100);
    }
}
