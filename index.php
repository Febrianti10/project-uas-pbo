<?php

// index.php - Entry Point Gabungan (Frontend + Backend)

// require __DIR__ . '/vendor/autoload.php';
// Autoload untuk load class otomatis dari /models dan /controllers
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/models/' . $className . '.php',
        __DIR__ . '/controllers/' . $className . '.php',
        __DIR__ . '/core/' . $className . '.php',
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
// DEBUG: Tampilkan action yang diterima
error_log("Action received: " . $action);
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
        // KANDANG ACTIONS
        case 'storeKandang':
            $controller = new KandangController();
            $controller->store();
            break;
        case 'updateKandang':
            $controller = new KandangController();
            $controller->update();
            break;
        case 'deleteKandang':
            $controller = new KandangController();
            $controller->delete();
            break;
        // TRANSAKSI ACTIONS     
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
        case 'cetakBukti':
            $controller = new TransaksiController();
            $controller->cetakBukti($_GET['id']);
            break;
        default:
            echo json_encode(['error' => 'Action not found']);
            break;
    }
    exit; // Stop setelah handle backend, agar tidak include view
}
// DEBUG: Tampilkan semua parameter
error_log("GET: " . print_r($_GET, true));
error_log("POST: " . print_r($_POST, true));
error_log("Action: " . $action);

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


