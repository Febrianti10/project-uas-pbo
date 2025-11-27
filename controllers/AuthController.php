<?php

class AuthController
{
    public function showLogin()
    {
        $errorMessage = $_GET['error'] ?? ''; // contoh sederhana
        include 'views/auth/login.php';
    }

    public function login()
    {
        // di sini cek username/password pakai OOP + PDO (teman backend)
        // kalau gagal:
        // header('Location: ?page=auth&action=showLogin&error=Login gagal');
        // exit;
    }

    public function logout()
    {
        // session_destroy() lalu redirect ke login
    }
}
 
