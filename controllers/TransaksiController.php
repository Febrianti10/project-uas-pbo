<?php
// controllers/TransaksiController.php
class TransaksiController {
    private $transaksiModel;

    public function __construct() {
        $this->transaksiModel = new Transaksi();
    }

    public function create() {
        // Validasi server-side
        $idPelanggan = $_POST['id_pelanggan'] ?? '';
        $idHewan = $_POST['id_hewan'] ?? '';
        $tanggalMasuk = $_POST['tanggal_masuk'] ?? '';
        $durasiHari = $_POST['durasi_hari'] ?? '';
        $subtotal = $_POST['subtotal'] ?? '';
        $detailLayanan = json_decode($_POST['detail_layanan'] ?? '[]', true); // Array dari frontend

        if (empty($idPelanggan) || empty($idHewan) || empty($tanggalMasuk) || empty($durasiHari) || !is_numeric($subtotal)) {
            echo json_encode(['error' => 'Data tidak lengkap atau invalid']);
            return;
        }

        if ($durasiHari <= 0 || $subtotal < 0) {
            echo json_encode(['error' => 'Durasi hari dan subtotal harus positif']);
            return;
        }

        // Asumsi id_user dari session (kasir yang login)
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Anda harus login']);
            return;
        }

        $data = [
            'id_pelanggan' => $idPelanggan,
            'id_hewan' => $idHewan,
            'id_user' => $_SESSION['user_id'],
            'tanggal_masuk' => $tanggalMasuk,
            'durasi_hari' => $durasiHari,
            'subtotal' => $subtotal,
            'total_biaya' => $subtotal, // Asumsi tanpa diskon dulu
        ];

        $idTransaksi = $this->transaksiModel->create($data, $detailLayanan);
        if ($idTransaksi) {
            echo json_encode(['success' => 'Transaksi berhasil dibuat', 'id_transaksi' => $idTransaksi]);
        } else {
            echo json_encode(['error' => 'Gagal membuat transaksi']);
        }
    }

    public function read() {
        $id = $_GET['id'] ?? null;
        $nomor = $_GET['nomor'] ?? null;

        if ($id) {
            $data = $this->transaksiModel->getById($id);
        } elseif ($nomor) {
            $data = $this->transaksiModel->getByNomor($nomor);
        } else {
            $data = $this->transaksiModel->getSedangDititipkan(); // Default: yang sedang dititipkan
        }

        echo json_encode($data ?: ['error' => 'Data tidak ditemukan']);
    }

    public function update() {
        // Untuk update checkout, gunakan method checkout di bawah
        echo json_encode(['error' => 'Update umum belum diimplementasi, gunakan checkout untuk selesai']);
    }

    public function delete() {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['error' => 'ID transaksi diperlukan']);
            return;
        }

        // Asumsi hanya bisa delete jika belum selesai (opsional, sesuai bisnis logic)
        $transaksi = $this->transaksiModel->getById($id);
        if (!$transaksi || $transaksi['status'] !== 'sedang_dititipkan') {
            echo json_encode(['error' => 'Transaksi tidak bisa dihapus']);
            return;
        }

        // Model Transaksi tidak punya delete, jadi tambahkan jika perlu, atau skip
        echo json_encode(['error' => 'Delete belum diimplementasi di model']);
    }

    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        if (empty($keyword)) {
            echo json_encode(['error' => 'Keyword diperlukan']);
            return;
        }

        $data = $this->transaksiModel->search($keyword);
        echo json_encode($data);
    }

    public function checkout() {
        $id = $_POST['id'] ?? '';
        $tanggalKeluar = $_POST['tanggal_keluar_aktual'] ?? '';
        $durasiHari = $_POST['durasi_hari'] ?? '';
        $totalBiaya = $_POST['total_biaya'] ?? '';
        $metodePembayaran = $_POST['metode_pembayaran'] ?? '';

        if (empty($id) || empty($tanggalKeluar) || !is_numeric($durasiHari) || !is_numeric($totalBiaya)) {
            echo json_encode(['error' => 'Data checkout tidak lengkap']);
            return;
        }

        $data = [
            'tanggal_keluar_aktual' => $tanggalKeluar,
            'durasi_hari' => $durasiHari,
            'total_biaya' => $totalBiaya,
            'metode_pembayaran' => $metodePembayaran,
        ];

        if ($this->transaksiModel->updateCheckout($id, $data)) {
            echo json_encode(['success' => 'Checkout berhasil']);
        } else {
            echo json_encode(['error' => 'Gagal checkout']);
        }
    }

    public function cetakBukti($id_transaksi){
    require_once 'models/Transaksi.php';

    $transaksiModel = new Transaksi();

    // Ambil data transaksi lengkap
    $dataTransaksi = $transaksiModel->getById($id_transaksi);

    if (!$dataTransaksi) {
        echo "Transaksi tidak ditemukan!";
        return;
    }

    // Data hewan sudah ada di hasil query (JOIN)
    $dataHewan = [
        'nama' => $dataTransaksi['nama_hewan'],
        'jenis' => $dataTransaksi['jenis'],
        'ras' => $dataTransaksi['ras'],
        'ukuran' => $dataTransaksi['ukuran'],
        'warna' => $dataTransaksi['warna'],
    ];

    // Detail layanan menggunakan tabel detail_layanan
    $dataLayanan = $dataTransaksi['detail_layanan'] ?? [];

    include "views/cetak_bukti.php";
    }

}
?>