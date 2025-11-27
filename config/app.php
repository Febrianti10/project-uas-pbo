<?php
// Tentukan URL dasar aplikasi
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

// Jika Anda menggunakan Laragon di sub-folder (misalnya http://localhost/project-uas)
// tambahkan penanganan untuk sub-folder
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // Ganti 'project-uas' dengan nama folder proyek Anda di Laragon
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/nama-folder-laragon-anda/"; 
}

// Catatan: Pastikan file ini di-*include* atau di-*require* sebelum header.php