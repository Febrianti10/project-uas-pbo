<?php
$pageTitle  = 'Data Pelanggan';
$activeMenu = 'pemilik';           // supaya menu Data Pelanggan aktif
include __DIR__ . '/template/header.php';

/*
   Nanti controller bisa mengirimkan:
   $pelangganList = [
       ['kode' => 'PLG001', 'nama' => 'Budi', 'hp' => '0812...', 'alamat' => '...'],
       ...
   ];
*/
$pelangganList = $pelangganList ?? [];
?>

<h2 class="mb-3">Data Pelanggan</h2>

<!-- BARIS ATAS: judul daftar + tombol tambah -->
<div class="card shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="card-title mb-1">Daftar Pelanggan</h5>
            <p class="text-muted small mb-0"><br><br>
                Data pemilik hewan yang sudah terdaftar pada sistem.
            </p>
        </div>

        <div class="d-flex gap-2">
            <!-- (opsional) kotak pencarian -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-start-0"
                       placeholder="Cari nama / no. HP" autocomplete="off">
            </div>
        </div>
    </div>
</div>

<!-- TABEL DATA PELANGGAN -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Kode</th>
                        <th>Nama Pelanggan</th>
                        <th style="width: 150px;">No. HP</th>
                        <th>Alamat</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($pelangganList)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-people fs-3 mb-1"></i>
                                <span>Belum ada data pelanggan.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pelangganList as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['kode']); ?></td>
                            <td><?= htmlspecialchars($p['nama']); ?></td>
                            <td><?= htmlspecialchars($p['hp']); ?></td>
                            <td><?= htmlspecialchars($p['alamat']); ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<?php include __DIR__ . '/template/footer.php'; ?>
