<?php
class SessionManager {
    private $config;
    private $user_role;

    public function __construct() {
        $this->config = require __DIR__ . '/../config/session_config.php';
        
        // Configure secure session settings
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        
        session_name($this->config['global_settings']['session_name']);
        session_start();
    }

    public function login($user_data) {
        // Prevent session fixation
        session_regenerate_id(true);

        // Set session data
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['user_type'] = $user_data['user_type'];
        $_SESSION['first_name'] = $user_data['first_name'];
        $_SESSION['loggedin'] = true;
        $_SESSION['login_time'] = time();
        
        // Set role-specific session duration
        $role_duration = $this->config['session_roles'][$user_data['user_type']]['duration'] ?? 3600;
        
        // Set secure cookie
        $cookie_params = [
            'expires' => time() + $role_duration,
            'path' => $this->config['global_settings']['cookie_path'],
            'domain' => $this->config['global_settings']['cookie_domain'],
            'secure' => $this->config['global_settings']['secure'],
            'httponly' => $this->config['global_settings']['httponly'],
            'samesite' => 'Strict'
        ];
        
        setcookie(
            session_name(), 
            session_id(), 
            $cookie_params
        );
    }

    public function checkAccess($requiredRoles = []) {
        // Check login status
        if (!$this->isLoggedIn()) {
            header("Location: /public/signin.php");
            exit();
        }

        // Get current user role
        $userRole = $_SESSION['user_type'];

        // Check role access
        if (!empty($requiredRoles) && !in_array($userRole, $requiredRoles)) {
            header("Location: forbidden.php");
            exit();
        }

        // Check session expiration
        $role_duration = $this->config['session_roles'][$userRole]['duration'] ?? 3600;
        if (time() - $_SESSION['login_time'] > $role_duration) {
            $this->logout();
            header("Location: signin.php?expired=true");
            exit();
        }

        // Periodically regenerate session ID
        $this->rotateSessionId();
    }

    private function rotateSessionId() {
        static $last_rotation = 0;
        $rotation_interval = 300; // Rotate every 5 minutes

        if (time() - $last_rotation > $rotation_interval) {
            session_regenerate_id(true);
            $last_rotation = time();
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;  //loggedin
    }

    public function logout() {
        // Clear session data
        $_SESSION = [];
        session_unset();
        session_destroy();

        // Delete session cookie
        $cookie_params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            [
                'expires' => time() - 42000,
                'path' => $cookie_params['path'],
                'domain' => $cookie_params['domain'],
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }

    public function getUserType() {
        return $_SESSION['user_type'] ?? null;
    }

    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}