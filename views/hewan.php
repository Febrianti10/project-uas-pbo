<?php
$pageTitle  = 'Data Hewan & Kandang';
$activeMenu = 'hewan';
include __DIR__ . '/template/header.php';

/*
 * ============================
 *  DATA HEWAN (dari transaksi)
 * ============================
 * Nanti controller bisa mengisi variabel:
 *   $totalHewan, $totalKucing, $totalAnjing, $hewanList
 */

// default kalau belum ada data dari controller
$totalHewan   = $totalHewan   ?? 0;
$totalKucing  = $totalKucing  ?? 0;
$totalAnjing  = $totalAnjing  ?? 0;
$hewanList    = $hewanList    ?? [];

/*
 * ============================
 *  DATA KANDANG
 * ============================
 * Nanti controller bisa mengisi:
 *   $totalkandangKecil, $totalkandangBesar, $kandangList
 *
 * Untuk sekarang kita kasih 1 data dummy supaya tampilan hidup.
 */

if (!isset($kandangList)) {
    $kandangList = [
        [
            'id'      => 1,
            'kode'    => 'KK01',
            'tipe'    => 'Kecil',
            'status'  => 'Kosong',
            'catatan' => 'Dekat jendela',
        ],
    ];
}

// kalau summary kandang belum di-set, hitung otomatis dari $kandangList
if (!isset($totalkandangKecil) || !isset($totalkandangBesar)) {
    $totalkandangKecil = 0;
    $totalkandangBesar = 0;
    foreach ($kandangList as $k) {
        if (strcasecmp($k['tipe'], 'Kecil') === 0) {
            $totalkandangKecil++;
        } elseif (strcasecmp($k['tipe'], 'Besar') === 0) {
            $totalkandangBesar++;
        }
    }
}
?>

<h2 class="mb-3">Data Hewan</h2>

<!-- RINGKASAN HEWAN -->
<div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Total Hewan Terdaftar</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalHewan; ?></span>
                </div>
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="bi bi-paw text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Kucing</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalKucing; ?></span>
                </div>
                <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="bi bi-cat text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Anjing</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalAnjing; ?></span>
                </div>
                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="bi bi-dog text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL DATA HEWAN -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Hewan</h5>
        <span class="text-muted small">
            Data hewan diperbarui otomatis dari transaksi penitipan.
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Hewan</th>
                        <th>Jenis</th>
                        <th>Ras</th>
                        <th>Pemilik</th>
                        <th>No. Telp Pemilik</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hewanList)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada data hewan karena belum ada transaksi penitipan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($hewanList as $h): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($h['nama']); ?></td>
                                <td><?= htmlspecialchars($h['jenis']); ?></td>
                                <td><?= htmlspecialchars($h['ras']); ?></td>
                                <td><?= htmlspecialchars($h['pemilik']); ?></td>
                                <td><?= htmlspecialchars($h['no_telp']); ?></td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($h['catatan'] ?? '-'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================= -->
<!--        DATA KANDANG       -->
<!-- ========================= -->

<h2 class="mb-3">Data Kandang</h2>

<!-- RINGKASAN KANDANG -->
<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Kandang Kecil (KK)</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalkandangKecil; ?></span>
                </div>
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="bi bi-house-heart text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Kandang Besar (KB)</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalkandangBesar; ?></span>
                </div>
                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="bi bi-building text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CARD DAFTAR KANDANG -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Kandang</h5>

        <!-- Tombol di UJUNG KANAN -->
        <button class="btn btn-primary btn-sm ms-auto"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#modalKandangBaru">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kandang
        </button>
    </div>

    <!-- MODAL TAMBAH KANDANG -->
    <div class="modal fade" id="modalKandangBaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content"
                  method="post"
                  action="index.php?page=hewan&action=store_kandang">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kandang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kandang</label>
                        <input type="text"
                               name="kode"
                               class="form-control"
                               placeholder="Contoh: KK01, KB02"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="tipe" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Kecil">Kecil (KK)</option>
                            <option value="Besar">Besar (KB)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Kosong">Kosong</option>
                            <option value="Terisi">Terisi</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Contoh: dekat jendela, khusus hewan besar, dll."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kandang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL KANDANG -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kandangList)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">
                                Belum ada data kandang.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($kandangList as $k): ?>
                            <?php
                            $badge = 'secondary';
                            if ($k['status'] === 'Kosong') $badge = 'success';
                            elseif ($k['status'] === 'Terisi') $badge = 'warning';
                            elseif ($k['status'] === 'Rusak') $badge = 'danger';
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($k['kode']); ?></td>
                                <td><?= htmlspecialchars($k['tipe']); ?></td>
                                <td>
                                    <span class="badge text-bg-<?= $badge; ?>">
                                        <?= htmlspecialchars($k['status']); ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars($k['catatan'] ?: '-'); ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="index.php?page=kandang&action=edit&id=<?= urlencode($k['id']); ?>"
                                           class="btn btn-outline-secondary me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="index.php?page=kandang&action=delete&id=<?= urlencode($k['id']); ?>"
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Hapus kandang <?= htmlspecialchars($k['kode']); ?> ?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'