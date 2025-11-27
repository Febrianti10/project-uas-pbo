<?php
// config/BaseConfig.php

$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
$base_path = '/'; // Default untuk Railway (produksi), di mana root = public/

if ($is_local) {
    // 1. Tentukan URL Penuh
    $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // 2. Tentukan nama folder utama di Laragon (Misalnya: 'penitipan-hewan')
    // Ganti 'nama-folder-laragon' dengan nama folder proyek Anda di Laragon!
    $folder_name = 'nama-folder-laragon'; 
    
    // 3. Jika URL mengandung nama folder Laragon (contoh: /penitipan-hewan/index.php)
    // dan memastikan path ke public/ juga ada
    if (strpos($url_path, $folder_name) !== false) {
        $base_path = '/' . trim($folder_name, '/') . '/public/';
    } else {
        // Jika Laragon menggunakan Virtual Host (misalnya: http://penitipan.test)
        $base_path = '/public/';
    }
} else {
    // Lingkungan Railway (produksi), server sudah diarahkan ke folder 'public'
    $base_path = '/'; 
}

// Variabel inilah yang akan digunakan di header.php
define('BASE_PATH', $base_path);
?>