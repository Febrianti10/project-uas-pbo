<?php
$pageTitle  = 'Data Hewan';
$activeMenu = 'hewan';
include __DIR__ . '/template/header.php';

/*
  Controller sebaiknya mengirimkan:
  $totalHewan
  $totalKucing
  $totalAnjing
  $hewanList = [
      [
          'id'         => 1,
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

<!-- Tabel Data Hewan -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Hewan</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalHewan">
            <i class="bi bi-plus-circle me-1"></i> Tambah Hewan
        </button>
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
                        <th style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($hewanList)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">
                            Belum ada data hewan. Tambahkan hewan baru.
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
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Hewan (sederhana, backend nanti yang bedakan tambah/edit) -->
<div class="modal fade" id="modalHewan" tabindex="-1" aria-labelledby="modalHewanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form action="index.php?page=hewan&action=store" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modalHewanLabel">Tambah Hewan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Hewan</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jenis</label>
              <select name="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Kucing">Kucing</option>
                <option value="Anjing">Anjing</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ras</label>
              <input type="text" name="ras" class="form-control" placeholder="Persia, Pomeranian, dll">
            </div>
            <div class="col-md-6">
              <label class="form-label">Pemilik</label>
              <input type="text" name="pemilik" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">No. Telp Pemilik</label>
              <input type="text" name="no_telp" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Catatan (opsional)</label>
              <textarea name="catatan" class="form-control" rows="2" placeholder="Alergi, kebiasaan khusus, dll."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>