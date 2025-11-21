<?php
$pageTitle  = 'Data Layanan';
$activeMenu = 'layanan';
include __DIR__ . '/template/header.php';

/*
   NANTI:
   - Data ini idealnya diambil dari database (tabel `layanan` / `paket`).
   - Kode paket & harga yang sama juga akan dipakai di form pendaftaran,
     supaya total biaya bisa dihitung otomatis berdasarkan:
     harga_paket x lama_inap.
*/

// Paket utama: Daycare & Boarding
$layananUtama = [
    [
        'kode'   => 'P001',
        'nama'   => 'Paket Daycare (Tanpa Menginap) ≤ 5 kg',
        'harga'  => 50000,
        'satuan' => '/ hari',
        'detail' => [
            'Makan 2x',
            'Minum',
            'Kandang & pasir',
            'Tidak menginap',
        ],
    ],
    [
        'kode'   => 'P002',
        'nama'   => 'Paket Daycare (Tanpa Menginap) > 5 kg',
        'harga'  => 60000,
        'satuan' => '/ hari',
        'detail' => [
            'Makan 2x',
            'Minum',
            'Kandang & pasir',
            'Tidak menginap',
        ],
    ],
    [
        'kode'   => 'P003',
        'nama'   => 'Paket Boarding',
        'harga'  => 120000,
        'satuan' => '/ hari',
        'detail' => [
            'Makan',
            'Minum',
            'Kandang & pasir',
            'Menginap 24 jam',
        ],
    ],
    [
        'kode'   => 'P004',
        'nama'   => 'Paket Boarding > 5 kg',
        'harga'  => 120000,
        'satuan' => '/ hari',
        'detail' => [
            'Makan',
            'Minum',
            'Kandang & pasir',
            'Menginap 24 jam',
        ],
    ],
    [
        'kode'   => 'P005',
        'nama'   => 'Paket Boarding VIP',
        'harga'  => 250000,
        'satuan' => '/ hari',
        'detail' => [
            'Makan',
            'Minum',
            'Kandang & pasir',
            'Menginap 24 jam',
            'Grooming lengkap (potong kuku, rapih bulu, bersih telinga, mandi, pengeringan, sisir, parfum)',
        ],
    ],
];

// Layanan tambahan
$layananTambahan = [
    [
        'kode'   => 'G001',
        'nama'   => 'Grooming Dasar',
        'harga'  => 100000,
        'satuan' => '/ sesi',
        'detail' => [
            'Pemotongan kuku',
            'Perapihan bulu',
            'Pembersihan telinga',
            'Mandi & pengeringan',
            'Sisir & parfum',
        ],
    ],
    [
        'kode'   => 'G002',
        'nama'   => 'Grooming Lengkap',
        'harga'  => 170000,
        'satuan' => '/ sesi',
        'detail' => [
            'Termasuk grooming dasar',
            'Trimming / bentuk bulu',
        ],
    ],
    [
        'kode'   => 'L003',
        'nama'   => 'Vitamin / Suplemen',
        'harga'  => 50000,
        'satuan' => '/ sekali pemberian',
        'detail' => [
            'Pemberian vitamin / suplemen sesuai kebutuhan hewan',
        ],
    ],
    [
        'kode'   => 'L004',
        'nama'   => 'Vaksin',
        'harga'  => 260000,
        'satuan' => '/ dosis',
        'detail' => [
            'Kucing: Tricat Trio / Felocell 3 / Purevax',
            'Anjing: DHPPi / setara',
        ],
    ],
];
?>

<h2 class="mb-3">Data Layanan</h2>

<!-- PAKET PENITIPAN (DAYCARE & BOARDING) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Paket Penitipan (Daycare & Boarding)</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($layananUtama as $l): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($l['nama']); ?></h5>
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($l['kode']); ?></span>

                            <p class="mb-1 fw-semibold">
                                Rp <?= number_format($l['harga'], 0, ',', '.'); ?>
                                <span class="text-muted small"><?= htmlspecialchars($l['satuan']); ?></span>
                            </p>

                            <?php if (!empty($l['detail'])): ?>
                                <ul class="small text-muted mb-0 ps-3">
                                    <?php foreach ($l['detail'] as $item): ?>
                                        <li><?= htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- Tombol ini nanti bisa diarahkan ke form edit paket -->
                            <button class="btn btn-outline-primary btn-sm w-100 mt-3">
                                <i class="bi bi-pencil-square me-1"></i> Kelola Paket
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- LAYANAN TAMBAHAN -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="card-title mb-0">Layanan Tambahan</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($layananTambahan as $l): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($l['nama']); ?></h5>
                            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($l['kode']); ?></span>

                            <p class="mb-1 fw-semibold">
                                Rp <?= number_format($l['harga'], 0, ',', '.'); ?>
                                <span class="text-muted small"><?= htmlspecialchars($l['satuan']); ?></span>
                            </p>

                            <?php if (!empty($l['detail'])): ?>
                                <ul class="small text-muted mb-0 ps-3">
                                    <?php foreach ($l['detail'] as $item): ?>
                                        <li><?= htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <button class="btn btn-outline-secondary btn-sm w-100 mt-3">
                                <i class="bi bi-pencil-square me-1"></i> Kelola Layanan
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
