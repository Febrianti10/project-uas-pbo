<?php
$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
include __DIR__ . '/template/header.php';

// ambil nama user dari session (opsional)
$namaUser = $_SESSION['user']['username'] ?? 'Kasir';
?>

<div class="d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center">
        <h1 class="fw-bold mb-3">
            Selamat Datang, <?= htmlspecialchars($namaUser); ?> 👋
        </h1>
        <p class="text-muted mb-1">
            Anda login sebagai <strong>kasir</strong>.
        </p>
        <p class="text-muted">
            Silakan gunakan menu di sebelah kiri untuk mengelola transaksi penitipan di SIP Hewan.
        </p>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>;
