<?php
class AuthController {
    private $db, $config_warning;
    
    public function __construct($db, $config_warning) {
        $this->db = $db;
        $this->config_warning = $config_warning;
    }
    
    public function handle($uri) {
        if (strpos($uri, '/logout') === 0) {
            $this->logout();
        } else {
            $this->login();
        }
    }
    
    public function login() {

        echo "<h1>Login</h1>";
        echo "<p>Login functionality will be implemented here.</p>";
        echo "<a href='/dashboard'>Back to Dashboard</a>";
    }
    
    public function logout() {

        session_destroy();
        header('Location: /dashboard');
        exit;
    }
} 