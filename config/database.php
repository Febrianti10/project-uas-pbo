<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        // KREDENSIAL DATABASE
        $host = getenv('MYSQL_HOST') ?: 'localhost'; 
        $port = getenv('MYSQL_PORT') ?: '3306'; 
        $dbname = getenv('MYSQL_DATABASE') ?: 'sip_hewan'; // Database Anda: sip_hewan
        $user = getenv('MYSQL_USER') ?: 'root'; 
        $pass = getenv('MYSQL_PASSWORD') ?: ''; 
        $charset = 'utf8mb4';
        
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage()); 
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
