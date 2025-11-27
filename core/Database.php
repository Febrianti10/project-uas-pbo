<?php
// File: /core/Database.php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Load konfigurasi dari /config/database.php
        $config = require_once __DIR__ . '/../config/database.php';
        
        try {
            // Buat DSN (Data Source Name)
            $port = $config['port'] ?? 3306;
            $dsn = "mysql:host={$config['host']};port={$port};dbname={$config['dbname']};charset={$config['charset']}";
            
            // Buat koneksi PDO
            $this->conn = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            echo "Database connected successfully!"; // untuk testing, nanti bisa dihapus
            
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Singleton pattern: pastikan cuma 1 instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Get koneksi PDO untuk dipakai di Model
    public function getConnection() {
        return $this->conn;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}