<?php
$pageTitle  = 'Data Pelanggan';
$activeMenu = 'pemilik';
include __DIR__ . '/template/header.php';

/*
   Nanti data ini berasal dari database.
   Untuk sekarang, kita buat dummy agar tampilan muncul.
*/
$pelanggan = [
    ['kode' => 'C001', 'nama' => 'Budi',   'telp' => '08123456789', 'alamat' => 'Jl. Merpati 21'],
    ['kode' => 'C002', 'nama' => 'Santi',  'telp' => '08567891234', 'alamat' => 'Jl. Kenanga 5'],
];
?>

<h2 class="mb-3">Data Pelanggan</h2>

<!-- Tombol Tambah -->
<div class="mb-3">
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalTambahPelanggan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan
    </button>
</div>

<!-- TABEL DATA PELANGGAN -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($pelanggan)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Belum ada data pelanggan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pelanggan as $p): ?>
                        <?php $modalId = 'modalEdit_' . $p['kode']; ?>
                        <tr>
                            <td><?= $p['kode']; ?></td>
                            <td><?= $p['nama']; ?></td>
                            <td><?= $p['telp']; ?></td>
                            <td><?= $p['alamat']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?= $modalId; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <a href="index.php?page=pemilik&delete=<?= $p['kode']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form class="modal-content"
                                      method="post"
                                      action="index.php?page=pemilik&action=update"
                                      onsubmit="return confirm('Simpan perubahan pelanggan?');">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Pelanggan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="kode" value="<?= $p['kode']; ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control"
                                                   value="<?= $p['nama']; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="text" name="telp" class="form-control"
                                                   value="<?= $p['telp']; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="2"><?= $p['alamat']; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>

                                </form>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ==========================
     MODAL TAMBAH PELANGGAN
============================= -->
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content"
              method="post"
              action="index.php?page=pemilik&action=insert"
              onsubmit="return confirm('Tambah pelanggan baru?');">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
