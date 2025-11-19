<?php
$pageTitle  = 'Transaksi Penitipan Hewan';
$activeMenu = 'transaksi';
include __DIR__ . '/template/header.php';

$tab = $_GET['tab'] ?? 'pendaftaran'; // default tab
?>

<div class="card shadow-sm">
    <div class="card-header border-0 pb-0">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'pendaftaran' ? 'active' : '' ?>"
                   href="index.php?page=transaksi&tab=pendaftaran">
                     Pendaftaran (Check-In)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'pengembalian' ? 'active' : '' ?>"
                   href="index.php?page=transaksi&tab=pengembalian">
                     Pengembalian (Check-Out)
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">

        <!-- ======================================================== -->
        <!-- ================ TAB 1 : PENDAFTARAN =================== -->
        <!-- ======================================================== -->
        <?php if ($tab === 'pendaftaran'): ?>

            <h5 class="mb-3">Form Pendaftaran Penitipan</h5>

            <form method="post" action="index.php?page=transaksi&action=save_checkin">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label">Pemilik</label>
                        <select name="pemilik_id" class="form-select" required>
                            <option value="">-- Pilih Pemilik --</option>
                            <?php foreach ($pemilikList ?? [] as $p): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= $p['nama'] ?> (<?= $p['no_telp'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small><a href="index.php?page=pemilik&action=create">Tambah pemilik baru</a></small>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Hewan</label>
                        <select name="hewan_id" class="form-select" required>
                            <option value="">-- Pilih Hewan --</option>
                            <?php foreach ($hewanList ?? [] as $h): ?>
                                <option value="<?= $h['id'] ?>">
                                    <?= $h['nama_hewan'] ?> (<?= $h['jenis'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Paket</label>
                        <select name="kode_paket" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            <?php foreach ($paketList ?? [] as $pk): ?>
                                <option value="<?= $pk['kode_paket'] ?>">
                                    <?= $pk['nama_paket'] ?> - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tgl_masuk" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Lama Inap (hari)</label>
                        <input type="number" name="lama_inap" class="form-control" min="1" value="1" required>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Nomor Kandang</label>
                        <input type="text" name="no_kandang" class="form-control" placeholder="K-05" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary">
                        Simpan & Cetak Bukti
                    </button>
                </div>
            </form>

        <!-- ======================================================== -->
        <!-- ================ TAB 2 : PENGEMBALIAN ================== -->
        <!-- ======================================================== -->
        <?php else: ?>

            <h5 class="mb-3">Proses Pengembalian Hewan & Pembayaran</h5>

            <form method="get" class="mb-4">
                <input type="hidden" name="page" value="transaksi">
                <input type="hidden" name="tab" value="pengembalian">

                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label">Nomor Form (no_form)</label>
                        <input type="text" name="no_form" class="form-control"
                               placeholder="Misal: F-20250101-001"
                               value="<?= htmlspecialchars($_GET['no_form'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-primary w-100">Cari</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($transaksi)): ?>

                <div class="p-3 mb-3 bg-body-secondary rounded">
                    <div><strong>No Form:</strong> <?= $transaksi['no_form'] ?></div>
                    <div><strong>Pemilik:</strong> <?= $transaksi['nama_pemilik'] ?></div>
                    <div><strong>Hewan:</strong> <?= $transaksi['nama_hewan'] ?></div>
                    <div><strong>Paket:</strong> <?= $transaksi['nama_paket'] ?></div>
                    <div><strong>Lama Inap:</strong> <?= $transaksi['lama_inap'] ?> hari</div>
                    <div><strong>Total Tagihan:</strong> Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></div>
                </div>

                <form method="post" action="index.php?page=transaksi&action=save_checkout">
                    <input type="hidden" name="no_form" value="<?= $transaksi['no_form'] ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Diskon (Rp)</label>
                            <input type="number" min="0" name="disc" class="form-control" value="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">DP (Rp)</label>
                            <input type="number" min="0" name="dp" class="form-control"
                                   value="<?= $transaksi['dp'] ?? 0 ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success">Simpan & Cetak Struk</button>
                    </div>
                </form>

            <?php elseif (isset($_GET['no_form'])): ?>

                <div class="alert alert-warning">
                    No form <strong><?= htmlspecialchars($_GET['no_form']) ?></strong> tidak ditemukan.
                </div>

            <?php else: ?>

                <p class="text-muted">Masukkan nomor formulir di atas untuk melakukan check-out.</p>

            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
