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
                <div class="row g-4">

                    <!-- ======================== -->
                    <!-- BAGIAN 1: DATA PEMILIK  -->
                    <!-- ======================== -->
                    <div class="col-lg-6">
                        <div class="card p-3">
                            <h6 class="mb-3 text-primary">Informasi Pemilik</h6>

                            <label class="form-label">Pilih Pemilik</label>
                            <select name="pemilik_id" class="form-select" id="pemilikSelect" required>
                                <option value="">-- Pilih Pemilik --</option>
                                <?php foreach ($pemilikList ?? [] as $p): ?>
                                    <option value="<?= $p['id'] ?>"
                                            data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                            data-hp="<?= htmlspecialchars($p['no_telp']) ?>"
                                            data-alamat="<?= htmlspecialchars($p['alamat'] ?? '') ?>">
                                        <?= $p['nama'] ?> (<?= $p['no_telp'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small>
                                <a href="index.php?page=pemilik&action=create">Tambah pemilik baru</a>
                            </small>

                    <!-- ====================== -->
                    <!-- BAGIAN 2: DATA HEWAN  -->
                    <!-- ====================== -->
                    <div class="col-lg-6">
                        <div class="card p-3">
                            <h6 class="mb-3 text-primary">Informasi Hewan</h6>

                            <label class="form-label">Pilih Hewan</label>
                            <select name="hewan_id" class="form-select" id="hewanSelect" required>
                                <option value="">-- Pilih Hewan --</option>
                                <?php foreach ($hewanList ?? [] as $h): ?>
                                    <option value="<?= $h['id'] ?>"
                                            data-nama="<?= htmlspecialchars($h['nama_hewan']) ?>"
                                            data-jenis="<?= htmlspecialchars($h['jenis']) ?>"
                                            data-ras="<?= htmlspecialchars($h['ras'] ?? '') ?>"
                                            data-ukuran="<?= htmlspecialchars($h['ukuran'] ?? '') ?>"
                                            data-warna="<?= htmlspecialchars($h['warna'] ?? '') ?>"
                                            data-catatan="<?= htmlspecialchars($h['catatan'] ?? '') ?>">
                                        <?= $h['nama_hewan'] ?> (<?= $h['jenis'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label class="form-label mt-3">Nama Hewan</label>
                            <input type="text" id="h_nama" class="form-control" readonly>

                            <label class="form-label mt-3">Jenis Hewan</label>
                            <input type="text" id="h_jenis" class="form-control" readonly>

                            <label class="form-label mt-3">Ras</label>
                            <input type="text" id="h_ras" class="form-control" readonly>

                            <label class="form-label mt-3">Ukuran</label>
                            <input type="text" id="h_ukuran" class="form-control" readonly>

                            <label class="form-label mt-3">Warna</label>
                            <input type="text" id="h_warna" class="form-control" readonly>

                            <label class="form-label mt-3">Catatan Khusus</label>
                            <textarea id="h_catatan" class="form-control" rows="2" readonly></textarea>
                        </div>
                    </div>

                    <!-- ====================== -->
                    <!-- BAGIAN 3: LAYANAN     -->
                    <!-- ====================== -->
                    <div class="col-12">
                        <div class="card p-3">
                            <h6 class="mb-3 text-primary">Layanan</h6>

                            <div class="row g-3">
                                <!-- Layanan Utama -->
                                <div class="col-lg-4">
                                    <label class="form-label">Layanan Utama</label>
                                    <select name="kode_paket" class="form-select" required>
                                        <option value="">-- Pilih Layanan Utama --</option>
                                        <?php foreach ($paketList ?? [] as $pk): ?>
                                            <option value="<?= $pk['kode_paket'] ?>">
                                                <?= $pk['nama_paket'] ?> - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Layanan Tambahan (boleh lebih dari satu) -->
                                <div class="col-lg-8">
                                    <label class="form-label">Layanan Tambahan</label>
                                    <div class="row g-2">
                                        <?php foreach ($layananTambahanList ?? [] as $lt): ?>
                                            <div class="col-md-4">
                                                <label class="form-check form-check-inline">
                                                    <input type="checkbox"
                                                           name="layanan_tambahan[]"
                                                           value="<?= $lt['kode'] ?>"
                                                           class="form-check-input">
                                                    <span class="form-check-label">
                                                        <?= htmlspecialchars($lt['nama_layanan']) ?>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (empty($layananTambahanList ?? [])): ?>
                                            <p class="text-muted small mb-0">
                                                Data layanan tambahan belum diisi.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ====================== -->
                    <!-- BAGIAN 4: DETAIL TITIP -->
                    <!-- ====================== -->
                    <div class="col-12">
                        <div class="card p-3">
                            <h6 class="mb-3 text-primary">Detail Penitipan</h6>

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label class="form-label">Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" class="form-control"
                                           value="<?= date('Y-m-d') ?>" required>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label">Lama Inap (hari)</label>
                                    <input type="number" name="lama_inap" class="form-control"
                                           min="1" value="1" required>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label">Nomor Kandang</label>
                                    <input type="text" name="no_kandang" class="form-control"
                                           placeholder="K-05" required>
                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- /.row -->

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary">
                        Simpan & Cetak Bukti
                    </button>
                </div>
            </form>

            <!-- Script kecil untuk autofill data pemilik & hewan -->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pemilikSelect = document.getElementById('pemilikSelect');
                const hewanSelect   = document.getElementById('hewanSelect');

                if (pemilikSelect) {
                    pemilikSelect.addEventListener('change', function () {
                        const opt = this.options[this.selectedIndex];
                        document.getElementById('p_nama').value   = opt.dataset.nama   || '';
                        document.getElementById('p_hp').value     = opt.dataset.hp     || '';
                        document.getElementById('p_alamat').value = opt.dataset.alamat || '';
                    });
                }

                if (hewanSelect) {
                    hewanSelect.addEventListener('change', function () {
                        const opt = this.options[this.selectedIndex];
                        document.getElementById('h_nama').value    = opt.dataset.nama    || '';
                        document.getElementById('h_jenis').value   = opt.dataset.jenis   || '';
                        document.getElementById('h_ras').value     = opt.dataset.ras     || '';
                        document.getElementById('h_ukuran').value  = opt.dataset.ukuran  || '';
                        document.getElementById('h_warna').value   = opt.dataset.warna   || '';
                        document.getElementById('h_catatan').value = opt.dataset.catatan || '';
                    });
                }
            });
            </script>

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
