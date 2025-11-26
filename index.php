<?php

// session_start();

// $page = $_GET['page'] ?? 'dashboard';

// switch ($page) {
//     case 'dashboard':
//         include 'views/dashboard.php';
//         break;

//     case 'transaksi':
//         include 'views/transaksi.php';
//         break;

//     case 'layanan':
//         include 'views/layanan.php';
//         break;

//     case 'hewan':
//         include 'views/hewan.php';
//         break;

//     case 'pemilik':         
//         include 'views/pelanggan.php';
//         break;

//     case 'laporan':
//         include 'views/laporan.php';
//         break;

//     case 'login':
//         include 'views/login.php';
//         break;

//     case 'logout':
//         session_destroy();
//         header('Location: index.php?page=login');
//         exit;

//     default:
//         include 'views/404.php';   
//         break;

// index.php - Entry Point Gabungan (Frontend + Backend)

// ==========================================================
// LOGIKA ROUTER ASET STATIS (WAJIB UNTUK PHP BUILT-IN SERVER)
// ==========================================================
// 1. Ambil path dari URL yang diminta (misal: /public/dist/css/adminlte.css)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Hapus slash di awal untuk mencocokkan dengan path di file system
$cleanPath = ltrim($path, '/');

// 3. Periksa apakah path tersebut mengarah ke file yang benar-benar ada di disk (misalnya CSS/JS)
if (file_exists($cleanPath) && !is_dir($cleanPath)) {
    // Jika file ada (misalnya: public/dist/css/adminlte.css),
    // kembalikan FALSE. Built-in server PHP akan melayani file tersebut secara langsung
    // dan TIDAK melanjutkan ke script router MVC Anda.
    return false;
}

// require __DIR__ . '/vendor/autoload.php';
// Autoload untuk load class otomatis dari /models dan /controllers
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/models/' . $className . '.php',
        __DIR__ . '/controllers/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Mulai session untuk login
session_start();

// Cek apakah ada action (backend routing)
$action = $_GET['action'] ?? $_POST['action'] ?? null;
if ($action) {
    // Routing untuk backend (controllers)
    switch ($action) {
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
        case 'createTransaksi':
            $controller = new TransaksiController();
            $controller->create();
            break;
        case 'readTransaksi':
            $controller = new TransaksiController();
            $controller->read();
            break;
        case 'updateTransaksi':
            $controller = new TransaksiController();
            $controller->update();
            break;
        case 'deleteTransaksi':
            $controller = new TransaksiController();
            $controller->delete();
            break;
        case 'searchTransaksi':
            $controller = new TransaksiController();
            $controller->search();
            break;
        case 'checkoutTransaksi':
            $controller = new TransaksiController();
            $controller->checkout();
            break;
        default:
            echo json_encode(['error' => 'Action not found']);
            break;
    }
    exit; // Stop setelah handle backend, agar tidak include view
}

// Jika tidak ada action, lanjut ke frontend routing (page)
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
    case 'pemilik':
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
        include 'views/404.php';
        break;
}
?>


