<?php
    class Database {
        private $conn;
        private static $instance = null;
        
        private function __construct() {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $db = "nph_solar";
            
            $this->conn = new mysqli($servername, $username, $password, $db);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }
        
        public function getConnection() {
            return $this->conn;
        }
    }
?>