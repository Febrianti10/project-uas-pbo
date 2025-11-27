<?php
$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
include __DIR__ . '/template/header.php';

/*
  Controller bisa mengisi variabel ini:

  $totalHewan
  $totalKucing
  $totalAnjing
  $totalPendapatanHariIni
  $totalKamar
  $kamarTerisi
  $kapasitasKucingMaks
  $kapasitasAnjingMaks
  $transaksiTerbaru = [
      [
        'no_form' => 'F-001',
        'pemilik' => 'Budi',
        'hewan'   => 'Mochi',
        'paket'   => 'B001 - Paket Harian + Grooming',
        'status'  => 'Lunas',
        'total'   => 300000
      ],
      ...
  ];
*/

// nilai default jika controller belum isi
$totalHewan             = $totalHewan             ?? 0;
$totalKucing            = $totalKucing            ?? 0;
$totalAnjing            = $totalAnjing            ?? 0;
$totalPendapatanHariIni = $totalPendapatanHariIni ?? 0;
$totalKamar             = $totalKamar             ?? 0;
$kamarTerisi           = $kamarTerisi           ?? 0;
$kapasitasKucingMaks    = $kapasitasKucingMaks    ?? 0;
$kapasitasAnjingMaks    = $kapasitasAnjingMaks    ?? 0;
$transaksiTerbaru       = $transaksiTerbaru       ?? [];

// hitungan turunan
$persenTerisi   = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;
$kamarKosong    = max($totalKamar - $kamarTerisi, 0);

$sisaSlotKucing = $kapasitasKucingMaks > 0
    ? max($kapasitasKucingMaks - $totalKucing, 0)
    : 0;

$sisaSlotAnjing = $kapasitasAnjingMaks > 0
    ? max($kapasitasAnjingMaks - $totalAnjing, 0)
    : 0;
?>

<h2 class="mb-3">Penitipan Hewan</h2>

<!-- ROW 1: STAT KECIL -->
<div class="row g-3 mb-3">
    <!-- Total Hewan Dititipkan -->
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Total Hewan Dititipkan</div>
                    <span class="display-6 fw-semibold mb-0" data-count="<?= (int)$totalHewan; ?>">0</span>
                    <div class="text-muted small mt-1">Keseluruhan</div>
                </div>
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;">
                    <i class="bi bi-house-heart text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kucing -->
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Kucing Dititipkan</div>
                    <span class="fs-2 fw-semibold mb-0" data-count="<?= (int)$totalKucing; ?>">0</span>
                    <div class="text-muted small mt-1">Saat ini</div>
                </div>
                <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;">
                    <i class="bi bi-cat text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Anjing -->
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase mb-1">Anjing Dititipkan</div>
                    <span class="fs-2 fw-semibold mb-0" data-count="<?= (int)$totalAnjing; ?>">0</span>
                    <div class="text-muted small mt-1">Saat ini</div>
                </div>
                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;">
                    <i class="bi bi-dog text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ROW 2: RINGKASAN + TRANSAKSI TERBARU -->
<div class="row g-3">
    <!-- Ringkasan Hari Ini -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pb-1">
                <h5 class="card-title mb-0">Ringkasan Hari Ini</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Hewan menginap saat ini</span>
                        <strong><?= (int)$totalHewan; ?></strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Total kamar</span>
                        <strong><?= (int)$totalKamar; ?></strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Kamar terisi</span>
                        <strong><?= (int)$kamarTerisi; ?></strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Kamar kosong</span>
                        <strong><?= (int)$kamarKosong; ?></strong>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Okupansi kamar</span>
                            <strong><?= $persenTerisi; ?>%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: <?= $persenTerisi; ?>%;"></div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <?= (int)$kamarTerisi; ?> kamar terisi dari <?= (int)$totalKamar; ?> kamar.
                        </small>
                    </li>
                </ul>

                <!-- Info untuk kasir: sisa slot hewan -->
                <div class="mt-3 p-2 rounded bg-body-secondary small">
                    <div class="d-flex justify-content-between">
                        <span>Sisa slot kucing</span>
                        <strong><?= (int)$sisaSlotKucing; ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Sisa slot anjing</span>
                        <strong><?= (int)$sisaSlotAnjing; ?></strong>
                    </div>
                    <div class="text-muted mt-1">
                        <i class="bi bi-info-circle me-1"></i>
                        Gunakan info ini sebelum menerima penitipan baru.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                <a href="index.php?page=transaksi&tab=pengembalian" class="small text-decoration-none">
                    Lihat semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Form</th>
                                <th>Pemilik</th>
                                <th>Hewan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transaksiTerbaru)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Belum ada transaksi terbaru.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transaksiTerbaru as $trx): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($trx['no_form']); ?></td>
                                        <td><?= htmlspecialchars($trx['pemilik']); ?></td>
                                        <td><?= htmlspecialchars($trx['hewan']); ?></td>
                                        <td><?= htmlspecialchars($trx['paket']); ?></td>
                                        <td>Rp <?= number_format($trx['total'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php
                                            $status = $trx['status'];
                                            $badgeClass = 'secondary';
                                            if ($status === 'Lunas') $badgeClass = 'success';
                                            elseif ($status === 'Menginep') $badgeClass = 'warning';
                                            elseif ($status === 'Batal') $badgeClass = 'danger';
                                            ?>
                                            <span class="badge text-bg-<?= $badgeClass; ?>">
                                                <?= htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Animasi angka count-up sederhana -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // angka biasa
        document.querySelectorAll('[data-count]').forEach(function(el) {
            const target = parseInt(el.dataset.count || '0');
            let current = 0;
            const step = Math.max(1, Math.ceil(target / 60));
            const interval = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(interval);
                }
                el.textContent = current.toLocaleString('id-ID');
            }, 16);
        });

        // angka uang
        document.querySelectorAll('[data-money]').forEach(function(el) {
            const target = parseInt(el.dataset.money || '0');
            let current = 0;
            const step = Math.max(1000, Math.ceil(target / 60));
            const interval = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(interval);
                }
                el.textContent = 'Rp ' + current.toLocaleString('id-ID');
            }, 16);
        });
    });
</script>

<?php include __DIR__ . '/template/footer.php'; ?>