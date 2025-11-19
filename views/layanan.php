<?php
$pageTitle  = 'Data Layanan';
$activeMenu = 'layanan';
include __DIR__ . '/template/header.php';

/* 
   Data bisa diambil dari database oleh controller, 
   tapi untuk saat ini kita buat array agar tampilan langsung terlihat.
*/

$layananUtama = [
    [
        'nama' => 'Penitipan Peliharaan (Boarding)',
        'kode' => 'UTM001',
        'deskripsi' => 'Layanan inti penitipan hewan selama 24 jam (anjing/kucing).',
    ],
    [
        'nama' => 'Penjaga Hewan di Rumah (Pet Sitting)',
        'kode' => 'UTM002',
        'deskripsi' => 'Pet sitter datang ke rumah pemilik untuk merawat hewan di lingkungan mereka sendiri.',
    ],
    [
        'nama' => 'Penitipan Harian (Day Care)',
        'kode' => 'UTM003',
        'deskripsi' => 'Hewan dititipkan hanya pada siang hari dan dijemput sore hari.',
    ],
];

$layananTambahan = [
    [
        'nama' => 'Grooming Hewan (Pet Grooming)',
        'kode' => 'ADD001',
        'deskripsi' => 'Mandi, potong kuku, potong bulu, blow dry, dan perawatan kebersihan lainnya.',
    ],
    [
        'nama' => 'Dog Walking',
        'kode' => 'ADD002',
        'deskripsi' => 'Mengajak anjing berjalan-jalan atau olahraga.',
    ],
    [
        'nama' => 'Pet Taxi / Transportation',
        'kode' => 'ADD003',
        'deskripsi' => 'Layanan antar jemput hewan dari rumah ke fasilitas atau tujuan lain.',
    ],
];
?>

<h2 class="mb-3">Data Layanan</h2>

<!-- LAYANAN UTAMA -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Layanan Utama Penitipan</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($layananUtama as $l): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold"><?= htmlspecialchars($l['nama']); ?></h5>
                        <span class="badge bg-primary"><?= htmlspecialchars($l['kode']); ?></span>
                        <p class="text-muted small mt-2"><?= htmlspecialchars($l['deskripsi']); ?></p>
                        <button class="btn btn-outline-primary btn-sm w-100 mt-2">
                            <i class="bi bi-pencil-square me-1"></i> Kelola Layanan
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
                        <h5 class="fw-semibold"><?= htmlspecialchars($l['nama']); ?></h5>
                        <span class="badge bg-secondary"><?= htmlspecialchars($l['kode']); ?></span>
                        <p class="text-muted small mt-2"><?= htmlspecialchars($l['deskripsi']); ?></p>
                        <button class="btn btn-outline-secondary btn-sm w-100 mt-2">
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
