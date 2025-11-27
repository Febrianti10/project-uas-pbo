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

<!-- TAMPILKAN ALERT JIKA ADA STATUS -->
<?php if (isset($_GET['status'])): ?>
    <div class='alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show'>
        <?php if ($_GET['status'] === 'success'): ?>
            <strong>Sukses!</strong> Transaksi berhasil dibuat.
        <?php else: ?>
            <strong>Error!</strong> <?= htmlspecialchars($_GET['message'] ?? 'Terjadi kesalahan') ?>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

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
            <select name="id_pelanggan" class="form-select" id="selectPelanggan" required>
                <option value="">-- Pilih Pemilik --</option>
                <?php 
                $pelangganList = $pelangganModel->getAll();
                foreach ($pelangganList as $p): ?>
                    <option value="<?= $p['id'] ?>" 
                            data-hp="<?= $p['hp'] ?>" 
                            data-alamat="<?= htmlspecialchars($p['alamat']) ?>">
                        <?= htmlspecialchars($p['nama']) ?> (<?= $p['hp'] ?>)
                    </option>
                <?php endforeach; ?>
                <option value="new">+ Tambah Pemilik Baru</option>
            </select>
            <small class="text-muted">Pilih dari daftar pelanggan terdaftar</small>
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

        <div class="mb-3" id="newCustomerFields" style="display: none;">
            <label class="form-label">Nama Pemilik Baru <span class="text-danger">*</span></label>
            <input type="text" name="nama_pelanggan_baru" class="form-control"
                placeholder="Ketik nama pemilik baru">
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
                <select name="jenis" class="form-select" id="jenisHewanSelect" required> <!-- UBAH: jenis_hewan -> jenis -->
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
                        <option value="<?= $pk['id_layanan'] ?>" 
                                data-harga="<?= $pk['harga'] ?>" 
                                data-nama="<?= htmlspecialchars($pk['nama_layanan']) ?>">
                            <?= htmlspecialchars($pk['nama_layanan']) ?>
                            - Rp <?= number_format($pk['harga'], 0, ',', '.'); ?>/hari
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Pilih salah satu paket penitipan</div>
            </div>

            <!-- Info Paket yang Dipilih -->
            <div class="col-lg-8">
                <div class="alert alert-info mt-4">
                    <h6>Info Paket:</h6>
                    <div id="paketInfo">Pilih paket untuk melihat detail</div>
                </div>
            </div>
        </div>

        <!-- TOTAL -->
        <div class="mt-4 p-3 bg-light rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small text-muted">Total Estimasi Biaya</div>
                    <h3 id="totalHarga" class="fw-bold text-primary mb-0">Rp 0</h3>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block" id="detailPerhitungan">-</small>
                </div>
            </div>
            <input type="hidden" name="total_biaya" id="totalInput" value="0">
            <div class="small text-muted mt-1">
                Total = (harga paket × lama inap)
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
                    <input type="date" name="tanggal_masuk" class="form-control" <!-- UBAH: tgl_masuk -> tanggal_masuk -->
                        value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-lg-4">
                    <label class="form-label">Lama Inap (hari) <span class="text-danger">*</span></label>
                    <input type="number" name="durasi" class="form-control" <!-- UBAH: lama_inap -> durasi -->
                        min="1" value="1" required id="lamaInap">
                </div>

                <div class="col-lg-4">
                    <label class="form-label">Kandang <span class="text-danger">*</span></label>
                    <button type="button"
                        class="btn btn-outline-secondary text-start w-100 d-flex justify-content-between align-items-center"
                        id="btnPilihKandang">
                        <span id="kandangLabel">Pilih kandang yang tersedia</span>
                        <i class="bi bi-chevron-down ms-2 small"></i>
                    </button>

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
        // AUTO-FILL DATA PELANGGAN (SIMPLE VERSION)
        // =============================================
        const selectPelanggan = document.getElementById('selectPelanggan');
        const noHpInput = document.getElementById('p_hp');
        const alamatInput = document.getElementById('p_alamat');
        const newCustomerFields = document.getElementById('newCustomerFields');

        if (selectPelanggan) {
            selectPelanggan.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value === 'new') {
                    // Tampilkan field untuk pelanggan baru
                    newCustomerFields.style.display = 'block';
                    noHpInput.value = '';
                    alamatInput.value = '';
                    noHpInput.required = true;
                    alamatInput.required = true;
                } else if (selectedOption.value) {
                    // Auto-fill data pelanggan yang dipilih
                    newCustomerFields.style.display = 'none';
                    noHpInput.value = selectedOption.dataset.hp || '';
                    alamatInput.value = selectedOption.dataset.alamat || '';
                    noHpInput.required = true;
                    alamatInput.required = true;
                } else {
                    // Reset jika tidak ada yang dipilih
                    newCustomerFields.style.display = 'none';
                    noHpInput.value = '';
                    alamatInput.value = '';
                }
            });
        }

                // =============================================
                // KALKULASI TOTAL HARGA - DEBUG VERSION
                // =============================================
                const paketSelect = document.getElementById('paketSelect');
                const lamaInapInput = document.getElementById('lamaInap');
                const totalHargaElement = document.getElementById('totalHarga');
                const totalInput = document.getElementById('totalInput');

                function hitungTotal() {
                    console.log("=== KALKULASI TOTAL DIMULAI ===");
                    
                    let total = 0;

                    // Debug: Cek elemen
                    console.log("paketSelect:", paketSelect);
                    console.log("paketSelect value:", paketSelect ? paketSelect.value : 'null');
                    console.log("lamaInapInput value:", lamaInapInput ? lamaInapInput.value : 'null');

                    // Hitung harga paket
                    if (paketSelect && paketSelect.value) {
                        const selectedOption = paketSelect.options[paketSelect.selectedIndex];
                        console.log("selectedOption:", selectedOption);
                        
                        const hargaPaket = selectedOption ? parseInt(selectedOption.getAttribute('data-harga')) : 0;
                        const lamaInap = parseInt(lamaInapInput.value) || 1;
                        
                        console.log("hargaPaket dari data attribute:", hargaPaket);
                        console.log("lamaInap:", lamaInap);
                        
                        total = hargaPaket * lamaInap;
                        console.log("Total calculated:", total);
                    } else {
                        console.log("Paket tidak dipilih atau elemen tidak ditemukan");
                    }

                    // Update tampilan
                    if (totalHargaElement) {
                        totalHargaElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
                        console.log("Total display updated");
                    }
                    if (totalInput) {
                        totalInput.value = total;
                        console.log("Total input updated:", total);
                    }

                    console.log("=== KALKULASI TOTAL SELESAI ===");
                }

                // Event listeners untuk kalkulasi
                if (paketSelect) {
                    paketSelect.addEventListener('change', function() {
                        console.log("Paket berubah:", this.value);
                        hitungTotal();
                    });
                    console.log("Paket select event listener added");
                }

                if (lamaInapInput) {
                    lamaInapInput.addEventListener('input', function() {
                        console.log("Lama inap berubah:", this.value);
                        hitungTotal();
                    });
                    console.log("Lama inap event listener added");
                }

                // Hitung total awal saat page load
                console.log("Initial calculation on page load...");
                hitungTotal();

// =============================================
// PEMILIHAN KANDANG - DEBUG VERSION
// =============================================
const btnPilihKandang = document.getElementById('btnPilihKandang');
const panelKandang = document.getElementById('panelKandang');
const kandangLabel = document.getElementById('kandangLabel');
const idKandangInput = document.getElementById('id_kandang');
const kandangInfo = document.getElementById('kandangInfo');
const jenisHewanSelect = document.getElementById('jenisHewanSelect');
const ukuranHewanSelect = document.getElementById('ukuranHewanSelect');

// Debug: Cek elemen kandang
console.log("=== DEBUG KANDANG ===");
console.log("btnPilihKandang:", btnPilihKandang);
console.log("panelKandang:", panelKandang);
console.log("idKandangInput:", idKandangInput);
console.log("jenisHewanSelect:", jenisHewanSelect);
console.log("ukuranHewanSelect:", ukuranHewanSelect);
console.log("Kandang tersedia dari PHP:", <?= json_encode($kandangTersedia) ?>);

if (btnPilihKandang) {
    console.log("Button pilih kandang ditemukan, adding event listener...");
    
    btnPilihKandang.addEventListener('click', function() {
        console.log("Button pilih kandang diklik!");
        
        if (!jenisHewanSelect || !jenisHewanSelect.value) {
            alert('Pilih jenis hewan terlebih dahulu');
            return;
        }

        console.log("Jenis hewan:", jenisHewanSelect.value);
        console.log("Ukuran hewan:", ukuranHewanSelect ? ukuranHewanSelect.value : 'null');

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
        console.log("Panel kandang ditampilkan");

        // Filter kandang berdasarkan jenis dan ukuran hewan
        const jenisHewan = jenisHewanSelect.value;
        const ukuranHewan = ukuranHewanSelect ? ukuranHewanSelect.value : '';
        
        // Tentukan tipe kandang yang sesuai
        let tipeKandangYangCocok = ['Kecil', 'Besar']; // Default tampilkan semua
        
        if (jenisHewan === 'Anjing') {
            tipeKandangYangCocok = ['Besar']; // Anjing hanya kandang besar
        } else if (ukuranHewan === 'Besar') {
            tipeKandangYangCocok = ['Besar']; // Hewan besar hanya kandang besar
        }

        console.log("Tipe kandang yang cocok:", tipeKandangYangCocok);

        // Tunggu sebentar lalu tampilkan kandang
        setTimeout(() => {
            panelKandang.innerHTML = '';
            
            let kandangDitemukan = false;
            const kandangTersedia = <?= json_encode($kandangTersedia) ?>;
            
            console.log("Data kandang dari PHP:", kandangTersedia);
            
            kandangTersedia.forEach(kandang => {
                console.log("Processing kandang:", kandang);
                
                // Filter kandang
                if (kandang.status === 'tersedia' && tipeKandangYangCocok.includes(kandang.tipe)) {
                    kandangDitemukan = true;
                    console.log("Kandang cocok:", kandang.kode);
                    
                    const kandangItem = document.createElement('div');
                    kandangItem.className = 'p-2 border-bottom cursor-pointer hover-bg-light';
                    kandangItem.style.cursor = 'pointer';
                    kandangItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold">${kandang.kode}</span>
                                <small class="text-muted ms-2">${kandang.tipe}</small>
                            </div>
                            <span class="badge bg-success">Tersedia</span>
                        </div>
                        ${kandang.catatan ? `<small class="text-muted">${kandang.catatan}</small>` : ''}
                    `;
                    
                    kandangItem.addEventListener('click', function() {
                        console.log("Kandang dipilih:", kandang.id, kandang.kode);
                        kandangLabel.textContent = `${kandang.kode} - ${kandang.tipe}`;
                        idKandangInput.value = kandang.id;
                        panelKandang.classList.add('d-none');
                        kandangInfo.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> Kandang ${kandang.kode} dipilih</span>`;
                        
                        // Validasi form
                        validateKandang();
                    });
                    
                    panelKandang.appendChild(kandangItem);
                }
            });
            
            if (!kandangDitemukan) {
                console.log("Tidak ada kandang yang cocok ditemukan");
                panelKandang.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-inbox display-6 opacity-50"></i>
                        <p class="mt-2 mb-0">Tidak ada kandang tersedia</p>
                        <small>Untuk ${jenisHewan} ${ukuranHewan ? 'ukuran ' + ukuranHewan : ''}</small>
                    </div>
                `;
            } else {
                console.log(kandangDitemukan + " kandang ditemukan");
            }
            
        }, 300);
    });

    // Function untuk validasi kandang
    function validateKandang() {
        if (idKandangInput.value) {
            btnPilihKandang.classList.remove('btn-outline-secondary');
            btnPilihKandang.classList.add('btn-outline-success');
        } else {
            btnPilihKandang.classList.remove('btn-outline-success');
            btnPilihKandang.classList.add('btn-outline-secondary');
        }
    }

    // Update ketika jenis/ukuran hewan berubah
    if (jenisHewanSelect) {
        jenisHewanSelect.addEventListener('change', function() {
            console.log("Jenis hewan berubah:", this.value);
            resetKandangPilihan();
        });
    }
    
    if (ukuranHewanSelect) {
        ukuranHewanSelect.addEventListener('change', function() {
            console.log("Ukuran hewan berubah:", this.value);
            resetKandangPilihan();
        });
    }

    function resetKandangPilihan() {
        console.log("Reset pilihan kandang");
        idKandangInput.value = '';
        kandangLabel.textContent = 'Pilih kandang yang tersedia';
        kandangInfo.innerHTML = 'Pilih kandang yang sesuai dengan jenis dan ukuran hewan';
        panelKandang.classList.add('d-none');
        validateKandang();
    }

    // Sembunyikan panel ketika klik di luar
    document.addEventListener('click', function(e) {
        if (btnPilihKandang && !btnPilihKandang.contains(e.target) && !panelKandang.contains(e.target)) {
            panelKandang.classList.add('d-none');
            console.log("Panel kandang disembunyikan");
        }
    });

    // Initial validation
    validateKandang();
} else {
    console.error("Button pilih kandang TIDAK DITEMUKAN!");
}

        // =============================================
        // FORM VALIDATION
        // =============================================
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log("Form submission validation...");
                
                // Validasi kandang sudah dipilih
                if (!idKandangInput.value) {
                    e.preventDefault();
                    alert('Pilih kandang terlebih dahulu!');
                    btnPilihKandang.focus();
                    return false;
                }

                // Validasi pelanggan
                const selectedPelanggan = selectPelanggan.value;
                if (!selectedPelanggan) {
                    e.preventDefault();
                    alert('Pilih pemilik terlebih dahulu!');
                    selectPelanggan.focus();
                    return false;
                }

                // Jika pilih "new", pastikan nama diisi
                if (selectedPelanggan === 'new' && !document.querySelector('[name="nama_pelanggan_baru"]').value) {
                    e.preventDefault();
                    alert('Isi nama pemilik baru!');
                    document.querySelector('[name="nama_pelanggan_baru"]').focus();
                    return false;
                }

                console.log('Form validation passed, submitting...');
                return true;
            });
        }

        // Debug info
        console.log("Transaksi form initialized");
        console.log("Kandang tersedia:", <?= count($kandangTersedia) ?>);
        console.log("Paket layanan:", <?= count($paketList) ?>);
    });
</script>