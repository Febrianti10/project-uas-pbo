<?php
$pageTitle  = 'Data Layanan';
$activeMenu = 'layanan';
include __DIR__ . '/template/header.php';

/*
   NANTI:
   - Data ini idealnya diambil dari database (tabel `layanan` / `paket`).
   - Untuk frontend, kita buat array dulu supaya tampilan sudah kelihatan.
   - Form di modal sudah disiapkan (method POST) -> tinggal kamu hubungkan
     ke controller: index.php?page=layanan&action=update_paket / update_tambahan
*/

// Paket utama: Daycare & Boarding
$layananUtama = [
    [
        'kode'   => 'P001',
        'nama'   => 'Paket Daycare (Tanpa Menginap) ≤ 5 kg',
        'harga'  => 50000,
        'satuan' => '/ hari',
        'detail' => "Makan 2x\nMinum\nKandang & pasir\nTidak menginap",
    ],
    [
        'kode'   => 'P002',
        'nama'   => 'Paket Daycare (Tanpa Menginap) > 5 kg',
        'harga'  => 60000,
        'satuan' => '/ hari',
        'detail' => "Makan 2x\nMinum\nKandang & pasir\nTidak menginap",
    ],
    [
        'kode'   => 'P003',
        'nama'   => 'Paket Boarding',
        'harga'  => 120000,
        'satuan' => '/ hari',
        'detail' => "Makan\nMinum\nKandang & pasir\nMenginap 24 jam",
    ],
    [
        'kode'   => 'P004',
        'nama'   => 'Paket Boarding > 5 kg',
        'harga'  => 120000,
        'satuan' => '/ hari',
        'detail' => "Makan\nMinum\nKandang & pasir\nMenginap 24 jam",
    ],
    [
        'kode'   => 'P005',
        'nama'   => 'Paket Boarding VIP',
        'harga'  => 250000,
        'satuan' => '/ hari',
        'detail' => "Makan\nMinum\nKandang & pasir\nMenginap 24 jam\nGrooming lengkap (potong kuku, rapih bulu, bersih telinga, mandi, pengeringan, sisir, parfum)",
    ],
];

// Layanan tambahan
$layananTambahan = [
    [
        'kode'   => 'G001',
        'nama'   => 'Grooming Dasar',
        'harga'  => 100000,
        'satuan' => '/ sesi',
        'detail' => "Pemotongan kuku\nPerapihan bulu\nPembersihan telinga\nMandi & pengeringan\nSisir & parfum",
    ],
    [
        'kode'   => 'G002',
        'nama'   => 'Grooming Lengkap',
        'harga'  => 170000,
        'satuan' => '/ sesi',
        'detail' => "Termasuk grooming dasar\nTrimming / bentuk bulu",
    ],
    [
        'kode'   => 'L003',
        'nama'   => 'Vitamin / Suplemen',
        'harga'  => 50000,
        'satuan' => '/ sekali pemberian',
        'detail' => "Pemberian vitamin / suplemen sesuai kebutuhan hewan",
    ],
    [
        'kode'   => 'L004',
        'nama'   => 'Vaksin',
        'harga'  => 260000,
        'satuan' => '/ dosis',
        'detail' => "Kucing: Tricat Trio / Felocell 3 / Purevax\nAnjing: DHPPi / setara",
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
            <?php foreach ($layananUtama as $index => $l): ?>
                <?php
                    $modalId = 'modalEditPaket_' . htmlspecialchars($l['kode']);
                    $detailLines = explode("\n", $l['detail']);
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($l['nama']); ?></h5>
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($l['kode']); ?></span>

                            <p class="mb-1 fw-semibold">
                                Rp <?= number_format($l['harga'], 0, ',', '.'); ?>
                                <span class="text-muted small"><?= htmlspecialchars($l['satuan']); ?></span>
                            </p>

                            <?php if (!empty($detailLines)): ?>
                                <ul class="small text-muted mb-0 ps-3">
                                    <?php foreach ($detailLines as $item): ?>
                                        <li><?= htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- Tombol buka modal edit -->
                            <button type="button"
                                    class="btn btn-outline-primary btn-sm w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#<?= $modalId; ?>">
                                <i class="bi bi-pencil-square me-1"></i> Kelola Paket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODAL EDIT PAKET -->
                <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content"
                          method="post"
                          action="index.php?page=layanan&action=update_paket"
                          onsubmit="return confirm('Yakin ingin mengubah data paket \'<?= htmlspecialchars($l['kode']); ?>\'?');">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Paket: <?= htmlspecialchars($l['kode']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="kode_paket" value="<?= htmlspecialchars($l['kode']); ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Paket</label>
                            <input type="text"
                                   name="nama_paket"
                                   class="form-control"
                                   value="<?= htmlspecialchars($l['nama']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number"
                                   name="harga"
                                   class="form-control"
                                   min="0"
                                   value="<?= (int)$l['harga']; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text"
                                   name="satuan"
                                   class="form-control"
                                   value="<?= htmlspecialchars($l['satuan']); ?>"
                                   required>
                            <small class="text-muted">Contoh: / hari, / sesi, / dosis</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan / Detail Paket</label>
                            <textarea name="detail" class="form-control" rows="4"><?= htmlspecialchars($l['detail']); ?></textarea>
                            <small class="text-muted">Bisa isi beberapa baris, satu fasilitas per baris.</small>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                      </div>
                    </form>
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
            <?php foreach ($layananTambahan as $index => $l): ?>
                <?php
                    $modalId = 'modalEditTambah_' . htmlspecialchars($l['kode']);
                    $detailLines = explode("\n", $l['detail']);
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($l['nama']); ?></h5>
                            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($l['kode']); ?></span>

                            <p class="mb-1 fw-semibold">
                                Rp <?= number_format($l['harga'], 0, ',', '.'); ?>
                                <span class="text-muted small"><?= htmlspecialchars($l['satuan']); ?></span>
                            </p>

                            <?php if (!empty($detailLines)): ?>
                                <ul class="small text-muted mb-0 ps-3">
                                    <?php foreach ($detailLines as $item): ?>
                                        <li><?= htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#<?= $modalId; ?>">
                                <i class="bi bi-pencil-square me-1"></i> Kelola Layanan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODAL EDIT LAYANAN TAMBAHAN -->
                <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content"
                          method="post"
                          action="index.php?page=layanan&action=update_tambahan"
                          onsubmit="return confirm('Yakin ingin mengubah layanan \'<?= htmlspecialchars($l['kode']); ?>\'?');">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Layanan: <?= htmlspecialchars($l['kode']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="kode_layanan" value="<?= htmlspecialchars($l['kode']); ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text"
                                   name="nama_layanan"
                                   class="form-control"
                                   value="<?= htmlspecialchars($l['nama']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number"
                                   name="harga"
                                   class="form-control"
                                   min="0"
                                   value="<?= (int)$l['harga']; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text"
                                   name="satuan"
                                   class="form-control"
                                   value="<?= htmlspecialchars($l['satuan']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan / Detail</label>
                            <textarea name="detail" class="form-control" rows="4"><?= htmlspecialchars($l['detail']); ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
