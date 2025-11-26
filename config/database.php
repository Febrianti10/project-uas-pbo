<?php
/**
 * Database Configuration
 * Sistem Penitipan Hewan
 * 
 * CARA PAKAI:
 * 1. Lokal (Laragon/XAMPP): Pakai config default
 * 2. Production (PlanetScale/Railway): Uncomment bagian production
 * 
 * @author h1101241034@student.untan.ac.id
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // ============================================
    // KONFIGURASI DATABASE
    // ============================================
    
    // UNTUK LOKAL (Laragon/XAMPP)
    private $host = 'localhost';
    private $dbname = 'db_penitipan_hewan';
    private $username = 'root';
    private $password = 'Sh3Belajar!SQL'; // Kosongkan untuk XAMPP/Laragon default
    
    // UNTUK PRODUCTION (uncomment saat deploy)
    // private $host = 'aws.connect.psdb.cloud'; // PlanetScale
    // private $dbname = 'penitipan_hewan';
    // private $username = 'your_username';
    // private $password = 'your_password';
    
    private $charset = 'utf8mb4';

    
    /**
     * Private constructor - Singleton Pattern
     */
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::ATTR_PERSISTENT         => false, // Matikan persistent connection untuk shared hosting
            ];
            
            // Tambahan untuk PlanetScale (uncomment saat deploy ke PlanetScale)
            // $options[PDO::MYSQL_ATTR_SSL_CA] = true;
            // $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            // Log error ke file (jangan tampilkan di production!)
            error_log("Database Connection Error: " . $e->getMessage());
            
            // Tampilkan pesan user-friendly
            die("Maaf, terjadi kesalahan koneksi database. Silakan hubungi administrator.");
        }
    }
    
    /**
     * Get Database Instance
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO Connection
     * 
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserializing
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * Test koneksi database
     * 
     * @return bool
     */
    public function testConnection() {
        try {
            $this->connection->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

/**
 * Helper function - Shortcut untuk get connection
 * 
 * @return PDO
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Helper function - Test koneksi
 * 
 * @return bool
 */
function isDBConnected() {
    return Database::getInstance()->testConnection();
}

?>

<?php
// /config/database.php
return [
    'host' => getenv('MYSQLHOST') ?: 'localhost', // Menggunakan MYSQL_HOST
    'port' => getenv('MYSQLPORT') ?: '3306', // Menggunakan MYSQL_PORT
    'dbname' => getenv('MYSQLDATABASE') ?: 'db_penitipan_hewan', // Menggunakan MYSQL_DATABASE
    'username' => getenv('MYSQLUSER') ?: 'root', // Menggunakan MYSQL_USER
    'password' => getenv('MYSQLPASSWORD') ?: '', // Menggunakan MYSQL_PASSWORD
    'charset' => 'utf8mb4'
];
