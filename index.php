<?php
session_start();

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'views/dashboard.php';
        break;

    case 'transaksi':
        include 'views/transaksi.php';
        break;

    case 'layanan':
        include 'views/layanan.php';
        break;

    case 'hewan':
        include 'views/hewan.php';
        break;

    case 'pemilik':          // <-- TAMBAHAN: Data Pelanggan
        include 'views/pelanggan.php';
        break;

    case 'laporan':
        include 'views/laporan.php';
        break;

    case 'login':
        include 'views/login.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit;

    default:
        include 'views/404.php';   // pastikan file ini ada (langkah 3)
        break;
}
