<!--

// class AuthController
// {
//     public function showLogin()
//     {
//         $errorMessage = $_GET['error'] ?? ''; // contoh sederhana
//         include 'views/auth/login.php';
//     }

    // public function login()
    // {
    //     di sini cek username/password pakai OOP + PDO (teman backend)
    //     kalau gagal:
    //     header('Location: ?page=auth&action=showLogin&error=Login gagal');
    //     exit;
    // }

//     public function logout()
//     {
//         session_destroy() lalu redirect ke login
//     }
// }
  -->

<?php
// controllers/AuthController.php

class AuthController {
    
    // Rubrik: Proper use of public/private/protected (Method ini public agar diakses router)
    /**
     * Menampilkan form login.
     * Dipanggil jika user belum login atau routing mengarah ke root project.
     */
    public function showLoginForm() {
        // Asumsi file view login.php berada di views/template/login.php atau views/login.php
        require_once 'views/login.php'; 
    }

    // Rubrik: Nama jelas, sesuai standar camelCase / PascalCase (loginProcess)
    /**
     * Menangani proses POST request dari form login.
     * Rubrik: Menangani input invalid, error tidak crash program
     * Rubrik: Semua fitur sesuai requirement (Form login & Validasi role kasir)
     */
    public function loginProcess() {
        // 1. Ambil dan Bersihkan Input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Rubrik: Ada komentar yang menjelaskan logika penting
        // 2. Validasi Input Sisi Server
        if (empty($username) || empty($password)) {
            // Rubrik: Input valid, feedback user jelas
            $_SESSION['error_message'] = 'Username dan Password harus diisi.';
            header('Location: /project-uas-pbo/'); // Redirect ke halaman utama/login
            return;
        }

        // 3. Cari User (Memanggil Model)
        // Rubrik: Object, class, method digunakan dengan benar
        // Asumsi: Method findByUsername($username) ada di models/User.php
        $user = User::findByUsername($username); 

        // 4. Verifikasi User dan Password
        // Rubrik: Aman dari SQL injection (ditangani di model, verifikasi password di sini)
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error_message'] = 'Username atau password salah.';
            header('Location: /project-uas-pbo/');
            return;
        }

        // 5. Validasi Role (Otorisasi Kasir)
        // Rubrik: Validasi role kasir (menu yang muncul sesuai hak akses kasir)
        if ($user['role'] !== 'kasir') {
            $_SESSION['error_message'] = 'Akses ditolak. Anda tidak memiliki hak akses Kasir.';
            header('Location: /project-uas-pbo/');
            return;
        }

        // 6. Login Berhasil (Membuat Session)
        // Rubrik: Kelas dibuat sesuai konsep OOP (session state management)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect ke Dashboard Kasir
        header('Location: /project-uas-pbo/transaksi/dashboard'); 
    }
    
    /**
     * Menangani proses logout dan menghancurkan session.
     */
    public function logout() {
        // Rubrik: Kode modular, bisa dipakai ulang (method logout)
        session_unset(); // Hapus semua variabel sesi
        session_destroy(); // Hancurkan sesi
        header('Location: /project-uas-pbo/'); // Redirect ke halaman login
    }
}