<?php
// views/cetak_bukti.php

$id_transaksi = $_GET['id_transaksi'];

// Ambil data transaksi
$transaksiData = $controller->transaksiModel->findById($id_transaksi);

// Ambil detail layanan
$detailLayananItems = $controller->detailTransaksiModel->findByTransaksi($id_transaksi);


// Pastikan data tersedia
if (empty($transaksiData) || empty($detailLayananItems)) {
    echo "<p>Error: Data transaksi untuk struk tidak tersedia.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran - <?= $transaksiData['kode_transaksi'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body { 
                margin: 0; 
                padding: 20px; 
                font-family: 'Courier New', Courier, monospace;
                font-size: 10pt;
            }
            .no-print { display: none !important; }
            .struk-container {
                width: 58mm; /* Lebar standar struk termal */
                margin: 0 auto;
            }
            .border-bottom-dotted { border-bottom: 1px dotted #000; }
        }
        .struk-container {
            width: 300px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="struk-container">
        <div class="text-center mb-3">
            <h5 class="fw-bold mb-0">PET CARE CENTER</h5>
            <small>Jl. Contoh No. 123, Kota Anda</small><br>
            <small>Telp: 0812-xxxx-xxxx</small>
        </div>
        
        <div class="mb-2">
            <p class="mb-0 small">No. Transaksi: **<?= htmlspecialchars($transaksiData['kode_transaksi']) ?>**</p>
            <p class="mb-0 small">Tanggal Masuk: <?= date('d/m/Y', strtotime($transaksiData['tanggal_masuk'])) ?></p>
            <p class="mb-0 small">Petugas: User Kasir</p>
            <hr class="my-1">
        </div>

        <h6 class="small fw-bold">INFO HEWAN</h6>
        <p class="mb-0 small">Nama Hewan: **<?= htmlspecialchars($transaksiData['nama_hewan']) ?>**</p>
        <p class="mb-0 small">Jenis/Ras: <?= htmlspecialchars($transaksiData['jenis_hewan']) ?> / <?= htmlspecialchars($transaksiData['ras_hewan']) ?></p>
        <p class="mb-0 small">Pemilik: <?= htmlspecialchars($transaksiData['nama_pelanggan']) ?></p>
        <p class="mb-0 small">Kandang: <?= htmlspecialchars($transaksiData['kode_kandang']) ?> (Tipe: <?= htmlspecialchars($transaksiData['tipe_kandang']) ?>)</p>
        <hr class="my-1">

        <h6 class="small fw-bold">RINCIAN BIAYA</h6>
        <?php foreach ($detailLayananItems as $item): ?>
            <div class="d-flex justify-content-between small">
                <span class="text-start me-2">
                    <?= htmlspecialchars($item['nama_layanan']) ?> 
                    (<?= $item['quantity'] ?> Hari)
                </span>
                <span class="text-end">
                    <?= number_format($item['subtotal'], 0, ',', '.') ?>
                </span>
            </div>
        <?php endforeach; ?>

        <hr class="my-1 border-bottom-dotted">

        <div class="d-flex justify-content-between fw-bold small">
            <span>TOTAL AKHIR</span>
            <span>Rp <?= number_format($transaksiData['total_biaya'], 0, ',', '.') ?></span>
        </div>

        <hr class="my-1 border-bottom-dotted">
        
        <div class="text-center mt-3 small">
            <p class="mb-0">**Terima kasih atas kunjungan Anda!**</p>
            <p class="mb-0">Hewan Anda akan mendapatkan perawatan terbaik.</p>
        </div>
        
    </div>

    <div class="text-center mt-4 no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
        <!-- Tombol ini bisa disesuaikan untuk kembali ke halaman transaksi -->
        <button class="btn btn-secondary" onclick="window.location.href='index.php?page=transaksi'">Kembali</button>
    </div>
</body>
</html>