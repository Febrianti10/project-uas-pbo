<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?? 'Dashboard'; ?></title>

    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">

    <!-- ICON Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS Global (SATU FILE SAJA) -->
    <link rel="stylesheet" href="assets/css/custom.css">

</head>

<body class="layout-fixed sidebar-expand-lg">

<div class="app-wrapper">

    <!-- NAVBAR -->
    <nav class="app-header navbar navbar-expand bg-white shadow-sm">
        <div class="container-fluid">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-person-circle"></i>
                    </a>
                </li>
            </ul>

        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="app-sidebar bg-body shadow">
        <div class="sidebar-brand">
            <a href="index.php" class="brand-link">
                <span class="brand-text fw-bold">PetCare Admin</span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-3">
                <ul class="nav flex-column">
                    
                    <li class="nav-item">
                        <a href="index.php?page=dashboard"
                           class="nav-link <?= ($activeMenu == 'dashboard') ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=hewan"
                           class="nav-link <?= ($activeMenu == 'hewan') ? 'active' : ''; ?>">
                            <i class="bi bi-paw me-2"></i> Data Hewan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=layanan"
                           class="nav-link <?= ($activeMenu == 'layanan') ? 'active' : ''; ?>">
                            <i class="bi bi-list-check me-2"></i> Layanan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=transaksi"
                           class="nav-link <?= ($activeMenu == 'transaksi') ? 'active' : ''; ?>">
                            <i class="bi bi-receipt me-2"></i> Transaksi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=laporan"
                           class="nav-link <?= ($activeMenu == 'laporan') ? 'active' : ''; ?>">
                            <i class="bi bi-graph-up-arrow me-2"></i> Laporan
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <a href="index.php?page=logout" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="app-main">
        <div class="app-content">
            <div class="container-fluid">
