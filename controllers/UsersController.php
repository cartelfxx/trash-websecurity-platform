<?php
class UsersController {
    private $db, $config_warning;
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    public function index() {
        require __DIR__ . '/../views/users.php';
    }
}
