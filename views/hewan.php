<?php
$pageTitle  = 'Data Hewan';
$activeMenu = 'hewan';
include __DIR__ . '/template/header.php';

/*
  Controller sebaiknya mengirimkan (DARI TRANSAKSI PENITIPAN, BUKAN INPUT MANUAL):

  $totalHewan   = total distinct hewan yang pernah dititipkan
  $totalKucing  = total distinct hewan jenis "Kucing"
  $totalAnjing  = total distinct hewan jenis "Anjing"

  $hewanList = [
      [
          'id'         => 1,                // id hewan (bisa dari tabel hewan atau dari transaksi)
          'nama'       => 'Mochi',
          'jenis'      => 'Kucing',
          'ras'        => 'Persia',
          'pemilik'    => 'Budi',
          'no_telp'    => '0812xxxx',
          'catatan'    => 'Alergi seafood'
      ],
      ...
  ];
*/

$totalHewan  = $totalHewan  ?? 0;
$totalKucing = $totalKucing ?? 0;
$totalAnjing = $totalAnjing ?? 0;
$hewanList   = $hewanList   ?? [];
$totalkandangKecil  = $totalkandangKecil ?? 0;  
$totalkandangBesar = $totalkandangBesar  ?? 0;
?>

<h2 class="mb-3">Data Hewan</h2>

<!-- Ringkasan kecil -->
<div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Total Hewan Terdaftar</div>
                    <span class="fs-3 fw-semibold"><?= (int)$totalHewan; ?></span>
                </div>
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
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
                <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
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
                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="bi bi-dog text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3 mt-4">Data Kandang</h5>

<div class="row g-3 mb-3">

    <!-- Kandang Kecil -->
    <div class="col-lg-6 col-md-6">
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

    <!-- Kandang Besar -->
    <div class="col-lg-6 col-md-6">
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

<!-- Tabel Data Hewan -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Hewan</h5>
        <span class="text-muted small">
            Data hewan diperbarui otomatis dari transaksi penitipan.
        </span>
        <!-- Tidak ada tombol Tambah Hewan di sini -->
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
                        <!-- Kalau memang tidak boleh diedit/hapus, kolom Aksi bisa dihapus -->
                        <!-- <th style="width: 90px;">Aksi</th> -->
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
                            <td class="small text-muted"><?= htmlspecialchars($h['catatan'] ?? '-'); ?></td>
                            <!-- Kalau mau read-only, blok Aksi ini dihapus saja -->
                            <!--
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="index.php?page=hewan&action=edit&id=<?= urlencode($h['id']); ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="index.php?page=hewan&action=delete&id=<?= urlencode($h['id']); ?>"
                                       class="btn btn-outline-danger"
                                       onclick="return confirm('Yakin menghapus data hewan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                            -->
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
