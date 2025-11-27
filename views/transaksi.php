<?php
$pageTitle  = 'Transaksi Penitipan Hewan';
$activeMenu = 'transaksi';
include __DIR__ . '/template/header.php';

// Load data dari database
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Layanan.php';
require_once __DIR__ . '/../models/Kandang.php';
require_once __DIR__ . '/../models/Transaksi.php';

$pelangganModel = new Pelanggan();
$layananModel = new Layanan();
$kandangModel = new Kandang();
$transaksiModel = new Transaksi();

// Data paket utama dari database
$paketList = $layananModel->getAll();

// Data kandang yang tersedia
$kandangTersedia = $kandangModel->getAll();

// Data hewan yang sedang menginap (untuk tab pengembalian)
$hewanMenginap = $transaksiModel->getActiveTransactions();

// Default nilai dari backend
$hasilPencarian = $hasilPencarian ?? [];
$transaksi      = $transaksi      ?? null;

$tab = $_GET['tab'] ?? 'pendaftaran';
?>

<div class="row justify-content-center">
    <div class="col-12 col-xl-12">
        <div class="card shadow-sm">

            <div class="card-header border-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pendaftaran' ? 'active' : '' ?>"
                            href="index.php?page=transaksi&tab=pendaftaran">
                            <i class="bi bi-box-arrow-in-down me-2"></i>Pendaftaran (Check-In)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pengembalian' ? 'active' : '' ?>"
                            href="index.php?page=transaksi&tab=pengembalian">
                            <i class="bi bi-box-arrow-up me-2"></i>Pengembalian (Check-Out)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">

                <?php if ($tab === 'pendaftaran'): ?>

                    <!-- =======================================================
                         TAB 1 — PENDAFTARAN
                    ======================================================== -->
                    <h5 class="mb-3">Form Pendaftaran Penitipan</h5>

                    <form method="post" action="index.php?action=createTransaksi">

                        <div class="row g-4">

                            <!-- INFORMASI PEMILIK -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100 position-relative">
                                    <h6 class="mb-3 text-primary">Informasi Pemilik</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" id="search_pemilik" class="form-control"
                                            autocomplete="off" placeholder="Ketik nama pemilik..." required>
                                        <div id="suggest_pemilik"
                                            class="border rounded bg-white position-absolute w-100 shadow-sm d-none"
                                            style="z-index: 9999; max-height: 200px; overflow-y: auto;"></div>
                                        <input type="hidden" name="pemilik_id" id="pemilik_id">
                                        <small class="text-muted">Pilih dari daftar atau ketik nama baru</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                        <input type="text" name="no_hp" id="p_hp" class="form-control"
                                            placeholder="Contoh: 08123456789" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                        <textarea name="alamat" id="p_alamat" class="form-control"
                                            rows="2" placeholder="Alamat lengkap pemilik" required></textarea>
                                    </div>

                                    <div class="alert alert-info py-2">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            Jika pemilik sudah pernah transaksi, ketik nama lalu pilih dari daftar.
                                            Data akan terisi otomatis.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- INFORMASI HEWAN -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100">
                                    <h6 class="mb-3 text-primary">Informasi Hewan</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_hewan" class="form-control"
                                            placeholder="Contoh: Mochi, Blacky" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                                        <select name="jenis_hewan" class="form-select" id="jenisHewanSelect" required>
                                            <option value="">-- Pilih Hewan --</option>
                                            <option value="Kucing">Kucing</option>
                                            <option value="Anjing">Anjing</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ras</label>
                                        <input type="text" name="ras" class="form-control"
                                            placeholder="Contoh: Persia, Siberian Husky">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ukuran</label>
                                        <select name="ukuran" class="form-select" id="ukuranHewanSelect">
                                            <option value="">-- Pilih Ukuran --</option>
                                            <option value="Kecil">Kecil</option>
                                            <option value="Sedang">Sedang</option>
                                            <option value="Besar">Besar</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <input type="text" name="warna" class="form-control"
                                            placeholder="Contoh: Putih, Hitam-Putih">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan Khusus</label>
                                        <textarea name="catatan" class="form-control" rows="2"
                                            placeholder="Alergi, penyakit, kebiasaan khusus, dll."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- LAYANAN -->
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="mb-3 text-primary">Layanan</h6>

                                    <div class="row g-3">

                                        <!-- Paket Utama -->
                                        <div class="col-lg-4">
                                            <label class="form-label">Paket Utama <span class="text-danger">*</span></label>
                                            <select name="id_layanan" class="form-select" id="paketSelect" required>
                                                <option value="">-- Pilih Paket --</option>
                                                <?php foreach ($paketList as $pk): ?>
                                                    <option value="<?= $pk['id_layanan']; ?>"
                                                        data-harga="<?= $pk['harga']; ?>">
                                                        <?= htmlspecialchars($pk['nama_layanan']); ?>
                                                        - Rp <?= number_format($pk['harga'], 0, ',', '.'); ?>/hari
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Layanan Tambahan -->
                                        <div class="col-lg-8">
                                            <label class="form-label d-block">Layanan Tambahan</label>

                                            <!-- Tombol "pseudo dropdown" -->
                                            <button type="button"
                                                class="btn btn-outline-secondary text-start w-100 d-flex justify-content-between align-items-center"
                                                id="btnLayananTambahan">
                                                <span id="ltLabel">Pilih layanan tambahan (opsional)</span>
                                                <i class="bi bi-chevron-down ms-2 small"></i>
                                            </button>

                                            <!-- Panel yang dibuka/tutup manual -->
                                            <div id="panelLayananTambahan"
                                                class="border rounded p-2 mt-1 d-none"
                                                style="max-height:260px; overflow-y:auto;">

                                                <?php 
                                                // Layanan tambahan (bisa dari database atau static)
                                                $layananTambahanList = [
                                                    ['kode' => 'G001', 'nama_layanan' => 'Grooming Dasar',     'harga' => 100000, 'satuan' => '/ sesi'],
                                                    ['kode' => 'G002', 'nama_layanan' => 'Grooming Lengkap',   'harga' => 170000, 'satuan' => '/ sesi'],
                                                    ['kode' => 'L003', 'nama_layanan' => 'Vitamin / Suplemen', 'harga' => 50000,  'satuan' => '/ pemberian'],
                                                    ['kode' => 'L004', 'nama_layanan' => 'Vaksin',             'harga' => 260000, 'satuan' => '/ dosis'],
                                                ];
                                                ?>
                                                
                                                <?php foreach ($layananTambahanList as $lt): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input lt-checkbox"
                                                            type="checkbox"
                                                            name="layanan_tambahan[]"
                                                            value="<?= $lt['kode']; ?>"
                                                            data-harga="<?= $lt['harga']; ?>"
                                                            id="lt_<?= $lt['kode']; ?>">
                                                        <label class="form-check-label small" for="lt_<?= $lt['kode']; ?>">
                                                            <?= $lt['nama_layanan']; ?>
                                                            - Rp <?= number_format($lt['harga'], 0, ',', '.'); ?>
                                                            <?= $lt['satuan']; ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>

                                            <small class="text-muted d-block mt-1">
                                                Bisa pilih lebih dari satu layanan tambahan.
                                            </small>
                                        </div>

                                    </div>

                                    <!-- TOTAL -->
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-muted">Total Estimasi Biaya</div>
                                                <h3 id="totalHarga" class="fw-bold text-primary mb-0">Rp 0</h3>
                                            </div>
                                        </div>
                                        <input type="hidden" name="total" id="totalInput">
                                        <div class="small text-muted mt-1">
                                            Total = (harga paket × lama inap) + jumlah layanan tambahan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DETAIL PENITIPAN -->
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="mb-3 text-primary">Detail Penitipan</h6>

                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                            <input type="date" name="tgl_masuk" class="form-control"
                                                value="<?= date('Y-m-d') ?>" required>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Lama Inap (hari) <span class="text-danger">*</span></label>
                                            <input type="number" name="lama_inap" class="form-control"
                                                min="1" value="1" required id="lamaInap">
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Kandang <span class="text-danger">*</span></label>

                                            <!-- Tombol untuk memilih kandang -->
                                            <button type="button"
                                                class="btn btn-outline-secondary text-start w-100 d-flex justify-content-between align-items-center"
                                                id="btnPilihKandang">
                                                <span id="kandangLabel">Pilih kandang yang tersedia</span>
                                                <i class="bi bi-chevron-down ms-2 small"></i>
                                            </button>

                                            <!-- Panel daftar kandang tersedia -->
                                            <div id="panelKandang" class="border rounded p-2 mt-1 d-none"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <div class="text-center">
                                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <span class="text-muted">Memuat kandang tersedia...</span>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id_kandang" id="id_kandang">
                                            <small class="text-muted d-block mt-1" id="kandangInfo">
                                                Pilih kandang yang sesuai dengan jenis dan ukuran hewan
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.row -->

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan &amp; Cetak Bukti
                            </button>
                        </div>
                    </form>

                <?php else: ?>

                    <!-- =======================================================
                         TAB 2 — PENGEMBALIAN (CHECK-OUT)
                    ======================================================== -->
                    <h5 class="mb-3">Form Pengembalian Hewan</h5>

                    <!-- Pencarian Transaksi Aktif -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="mb-3 text-primary">Cari Hewan yang Sedang Menginap</h6>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Cari berdasarkan Nama Pemilik atau Hewan</label>
                                    <input type="text" id="searchCheckout" class="form-control"
                                        placeholder="Ketik nama pemilik atau hewan...">
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Kandang</label>
                                    <select id="filterKandang" class="form-select">
                                        <option value="">Semua Kandang</option>
                                        <option value="KK">Kandang Kecil (KK)</option>
                                        <option value="KB">Kandang Besar (KB)</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100" id="btnCariCheckout">
                                        <i class="bi bi-search me-2"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Hewan Menginap -->
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h6 class="mb-0 text-primary">Daftar Hewan yang Sedang Menginap</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. Transaksi</th>
                                            <th>Pemilik</th>
                                            <th>Hewan</th>
                                            <th>Kandang</th>
                                            <th>Tgl Masuk</th>
                                            <th>Lama Inap</th>
                                            <th>Total Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($hewanMenginap)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                                                    <p class="mt-3 mb-0">Tidak ada hewan yang sedang menginap</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($hewanMenginap as $hewan): ?>
                                                <tr>
                                                    <td class="fw-semibold"><?= htmlspecialchars($hewan['kode_transaksi']); ?></td>
                                                    <td><?= htmlspecialchars($hewan['nama_pelanggan']); ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php
                                                            $hewanIcon = $hewan['jenis_hewan'] === 'Kucing' ? 'bi-cat text-info' : 'bi-dog text-warning';
                                                            ?>
                                                            <i class="bi <?= $hewanIcon; ?> me-2"></i>
                                                            <?= htmlspecialchars($hewan['nama_hewan']); ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($hewan['kode_kandang']); ?></span>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($hewan['tanggal_masuk'])); ?></td>
                                                    <td><?= $hewan['durasi']; ?> hari</td>
                                                    <td class="fw-semibold text-primary">
                                                        Rp <?= number_format($hewan['total_biaya'], 0, ',', '.'); ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            onclick="prosesCheckout('<?= $hewan['id_transaksi']; ?>')">
                                                            <i class="bi bi-check-lg me-1"></i>Check-out
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Checkout -->
                    <div class="modal fade" id="modalCheckout" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Proses Check-out Hewan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="checkoutContent">
                                    <!-- Content akan diisi via JavaScript -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-primary" id="btnConfirmCheckout">
                                        <i class="bi bi-check-lg me-2"></i>Konfirmasi Check-out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // =============================================
        // AUTO-COMPLETE PEMILIK (Real dengan Database)
        // =============================================
        const searchInput = document.getElementById('search_pemilik');
        const suggestBox = document.getElementById('suggest_pemilik');
        const pemilikId = document.getElementById('pemilik_id');
        const noHpInput = document.getElementById('p_hp');
        const alamatInput = document.getElementById('p_alamat');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    suggestBox.classList.add('d-none');
                    return;
                }

                // AJAX request ke server untuk cari pelanggan
                fetch(`index.php?action=searchPelanggan&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestBox.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(pemilik => {
                                const item = document.createElement('div');
                                item.className = 'p-2 border-bottom cursor-pointer hover-bg-light';
                                item.style.cursor = 'pointer';
                                item.innerHTML = `
                                <div class="fw-semibold">${pemilik.nama}</div>
                                <small class="text-muted">${pemilik.hp} - ${pemilik.alamat}</small>
                            `;

                                item.addEventListener('click', function() {
                                    searchInput.value = pemilik.nama;
                                    pemilikId.value = pemilik.id;
                                    noHpInput.value = pemilik.hp;
                                    alamatInput.value = pemilik.alamat;
                                    suggestBox.classList.add('d-none');
                                });

                                suggestBox.appendChild(item);
                            });
                            suggestBox.classList.remove('d-none');
                        } else {
                            suggestBox.classList.add('d-none');
                            // Reset hidden ID karena pemilik baru
                            pemilikId.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        suggestBox.classList.add('d-none');
                    });
            });

            // Sembunyikan suggest box ketika klik di luar
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
                    suggestBox.classList.add('d-none');
                }
            });
        }

        // =============================================
        // PEMILIHAN KANDANG (Real dengan Database)
        // =============================================
        const btnPilihKandang = document.getElementById('btnPilihKandang');
        const panelKandang = document.getElementById('panelKandang');
        const kandangLabel = document.getElementById('kandangLabel');
        const idKandangInput = document.getElementById('id_kandang');
        const kandangInfo = document.getElementById('kandangInfo');
        const jenisHewanSelect = document.getElementById('jenisHewanSelect');
        const ukuranHewanSelect = document.getElementById('ukuranHewanSelect');

        if (btnPilihKandang) {
            function muatKandangTersedia() {
                const jenisHewan = jenisHewanSelect.value;
                const ukuranHewan = ukuranHewanSelect.value;

                // Tampilkan loading
                panelKandang.innerHTML = `
                <div class="text-center py-2">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted">Memuat kandang tersedia...</span>
                </div>
            `;
                panelKandang.classList.remove('d-none');

                // AJAX request untuk mengambil kandang tersedia
                fetch(`index.php?action=getKandangTersedia&jenis=${jenisHewan}&ukuran=${ukuranHewan}`)
                    .then(response => response.json())
                    .then(data => {
                        panelKandang.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(kandang => {
                                const item = document.createElement('div');
                                item.className = `p-2 border-bottom cursor-pointer ${kandang.status === 'tersedia' ? 'hover-bg-light' : 'bg-light text-muted'}`;
                                item.style.cursor = kandang.status === 'tersedia' ? 'pointer' : 'not-allowed';

                                let badgeClass = kandang.status === 'tersedia' ? 'bg-success' : 'bg-secondary';
                                let statusText = kandang.status === 'tersedia' ? 'Tersedia' : 'Terisi';

                                item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold">${kandang.kode}</span>
                                        <small class="text-muted ms-2">${kandang.tipe}</small>
                                    </div>
                                    <span class="badge ${badgeClass}">${statusText}</span>
                                </div>
                                ${kandang.catatan ? `<small class="text-muted">${kandang.catatan}</small>` : ''}
                            `;

                                if (kandang.status === 'tersedia') {
                                    item.addEventListener('click', function() {
                                        kandangLabel.textContent = `${kandang.kode} - ${kandang.tipe}`;
                                        idKandangInput.value = kandang.id;
                                        panelKandang.classList.add('d-none');
                                        kandangInfo.innerHTML = `<span class="text-success">✓ Kandang ${kandang.kode} dipilih</span>`;
                                    });
                                }

                                panelKandang.appendChild(item);
                            });
                        } else {
                            panelKandang.innerHTML = '<div class="text-center text-muted py-2">Tidak ada kandang tersedia</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        panelKandang.innerHTML = '<div class="text-center text-danger py-2">Gagal memuat kandang</div>';
                    });
            }

            btnPilihKandang.addEventListener('click', function() {
                if (!jenisHewanSelect.value) {
                    alert('Pilih jenis hewan terlebih dahulu');
                    return;
                }
                muatKandangTersedia();
            });

            // Update kandang ketika jenis/ukuran hewan berubah
            if (jenisHewanSelect) {
                jenisHewanSelect.addEventListener('change', function() {
                    idKandangInput.value = '';
                    kandangLabel.textContent = 'Pilih kandang yang tersedia';
                    kandangInfo.textContent = 'Pilih kandang yang sesuai dengan jenis dan ukuran hewan';
                });
            }

            if (ukuranHewanSelect) {
                ukuranHewanSelect.addEventListener('change', function() {
                    idKandangInput.value = '';
                    kandangLabel.textContent = 'Pilih kandang yang tersedia';
                    kandangInfo.textContent = 'Pilih kandang yang sesuai dengan jenis dan ukuran hewan';
                });
            }

            // Sembunyikan panel ketika klik di luar
            document.addEventListener('click', function(e) {
                if (btnPilihKandang && !btnPilihKandang.contains(e.target) && !panelKandang.contains(e.target)) {
                    panelKandang.classList.add('d-none');
                }
            });
        }

        // =============================================
        // KALKULASI TOTAL HARGA (Sama seperti sebelumnya)
        // =============================================
        const paketSelect = document.getElementById('paketSelect');
        const lamaInapInput = document.getElementById('lamaInap');
        const ltCheckboxes = document.querySelectorAll('.lt-checkbox');
        const totalHargaElement = document.getElementById('totalHarga');
        const totalInput = document.getElementById('totalInput');

        function hitungTotal() {
            let total = 0;

            // Hitung harga paket
            const selectedPaket = paketSelect ? paketSelect.options[paketSelect.selectedIndex] : null;
            const hargaPaket = selectedPaket ? parseInt(selectedPaket.getAttribute('data-harga')) : 0;
            const lamaInap = parseInt(lamaInapInput ? lamaInapInput.value : 1) || 1;

            total += hargaPaket * lamaInap;

            // Hitung layanan tambahan
            ltCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseInt(checkbox.getAttribute('data-harga'));
                }
            });

            // Update tampilan
            if (totalHargaElement) {
                totalHargaElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
            if (totalInput) {
                totalInput.value = total;
            }

            // Update label layanan tambahan
            const ltLabel = document.getElementById('ltLabel');
            if (ltLabel) {
                const selectedLayanan = document.querySelectorAll('.lt-checkbox:checked').length;
                if (selectedLayanan > 0) {
                    ltLabel.textContent = `${selectedLayanan} layanan tambahan dipilih`;
                } else {
                    ltLabel.textContent = 'Pilih layanan tambahan (opsional)';
                }
            }
        }

        // Event listeners untuk kalkulasi
        if (paketSelect) {
            paketSelect.addEventListener('change', hitungTotal);
        }
        if (lamaInapInput) {
            lamaInapInput.addEventListener('input', hitungTotal);
        }
        if (ltCheckboxes.length > 0) {
            ltCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', hitungTotal);
            });
        }

        // Hitung total awal
        hitungTotal();

        // =============================================
        // TOGGLE LAYANAN TAMBAHAN (Sama seperti sebelumnya)
        // =============================================
        const btnLayanan = document.getElementById('btnLayananTambahan');
        const panelLayanan = document.getElementById('panelLayananTambahan');

        if (btnLayanan) {
            btnLayanan.addEventListener('click', function() {
                panelLayanan.classList.toggle('d-none');
            });

            // Sembunyikan panel ketika klik di luar
            document.addEventListener('click', function(e) {
                if (!btnLayanan.contains(e.target) && !panelLayanan.contains(e.target)) {
                    panelLayanan.classList.add('d-none');
                }
            });
        }

    });
</script>

<?php include __DIR__ . '/template/footer.php'; ?>