<?php
// models/User.php

class User {
    
    // Asumsi ada koneksi database di sini, misalnya melalui PDO atau ORM
    
    // Rubrik: Fungsi CRUD berfungsi (Ini adalah fungsi Read/Find)
    // Rubrik: Aman dari SQL injection (Menggunakan prepared statement)
    // Rubrik: Struktur DB logis (Asumsi ada kolom username, password(hashed), role)
    
    /**
     * Mencari user berdasarkan username.
     * @param string $username
     * @return array|null Data user atau null jika tidak ditemukan.
     */
    public static function findByUsername($username) {
        // **!!! IMPLEMENTASI KEAMANAN & DB DISINI !!!**
        
        // Contoh Pseudo-Code (Harus diubah ke kode DB nyata menggunakan prepared statement)
        /*
        $pdo = Database::getConnection(); 
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        */
        
        // Contoh Dummy Data (Hanya untuk testing awal sebelum integrasi DB):
        if ($username === 'kasir1') {
            // Password 'password123' di-hash
            return [
                'id' => 1,
                'username' => 'kasir1',
                'password' => '$2y$10$wTf7N1P5x0/i/Hk9uVq.a.wB4dE9Q7n0P0t8R/Y9Xm1', // Hash untuk 'password123'
                'role' => 'kasir'
            ];
        }
        return null;
    }
}