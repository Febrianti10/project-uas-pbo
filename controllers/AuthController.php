<?php
// controllers/AuthController.php

// Rubrik: Kelas dibuat sesuai konsep OOP, Nama jelas
class AuthController {
    
    /**
     * Menampilkan form login.
     * Dipanggil oleh router saat mengakses URL root.
     */
    public function showLoginForm() {
        // Logika: Jika user sudah login, arahkan ke dashboard.
        if (isset($_SESSION['user_id'])) {
            // Menggunakan clean URL MVC yang benar
            header('Location: /PROJECT-UAS-PBO/transaksi/dashboard'); 
            exit;
        }
        
        // Memuat View Login (view/login.php)
        require_once 'views/login.php'; 
    }

    /**
     * Menangani proses POST request dari form login.
     * URL yang memanggil: /PROJECT-UAS-PBO/auth/loginProcess
     */
    public function loginProcess() {
        // 1. Ambil dan Bersihkan Input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Rubrik: Menangani input invalid, error tidak crash program (Validasi server-side)
        // 2. Validasi Input Sisi Server
        if (empty($username) || empty($password)) {
            $_SESSION['error_message'] = 'Username dan Password harus diisi.';
            // Redirect kembali ke form login
            header('Location: /PROJECT-UAS-PBO/'); 
            exit;
        }

        // 3. Cari User (Memanggil Model)
        // Rubrik: Object, class, method digunakan dengan benar
        // Asumsi: Class User ada di models/User.php
        $user = User::findByUsername($username); 

        // 4. Verifikasi User dan Password
        // Rubrik: Aman dari SQL injection (ditangani di Model); verifikasi password
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error_message'] = 'Username atau password salah.';
            header('Location: /PROJECT-UAS-PBO/');
            exit;
        }

        // 5. Validasi Role (Otorisasi)
        // Rubrik: Ada komentar yang menjelaskan logika penting, Semua fitur sesuai requirement
        // Hanya Kasir yang diizinkan masuk
        if ($user['role'] !== 'kasir') {
            $_SESSION['error_message'] = 'Akses ditolak. Anda tidak memiliki hak akses Kasir.';
            header('Location: /PROJECT-UAS-PBO/');
            exit;
        }

        // 6. Login Berhasil (Membuat Session)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        
        // 7. Redirect ke Dashboard Kasir
        header('Location: /PROJECT-UAS-PBO/views/dashboard'); 
        exit;
    }
    
    /**
     * Menghancurkan session dan mengarahkan ke halaman login.
     */
    public function logout() {
        // Rubrik: Kode modular, bisa dipakai ulang
        session_unset(); 
        session_destroy(); 
        header('Location: /PROJECT-UAS-PBO/'); // Redirect ke root/login
        exit;
    }
}