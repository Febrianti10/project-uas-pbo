<?php

class TransaksiController {
    private $transaksiModel;
    private $pelangganModel;
    private $hewanModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Transaksi.php';
        require_once __DIR__ . '/../models/Pelanggan.php';
        require_once __DIR__ . '/../models/Hewan.php';
        require_once __DIR__ . '/../models/Kandang.php';
        
        $this->transaksiModel = new Transaksi();
        $this->pelangganModel = new Pelanggan();
        $this->hewanModel = new Hewan();
    }

    public function create() {
        error_log("TransaksiController::create() called");
        error_log("POST data: " . print_r($_POST, true));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validasi data required
                if (empty($_POST['id_layanan']) || empty($_POST['id_kandang']) || empty($_POST['nama_hewan'])) {
                    throw new Exception("Data required tidak lengkap");
                }

                // 1. Handle Pelanggan (create new if doesn't exist)
                $id_pelanggan = $this->handlePelanggan($_POST);
                
                // 2. Handle Hewan (create new)
                $id_hewan = $this->handleHewan($_POST, $id_pelanggan);
                
                // 3. Prepare transaksi data
                $transaksiData = [
                    'id_pelanggan' => $id_pelanggan,
                    'id_hewan' => $id_hewan,
                    'id_kandang' => $_POST['id_kandang'],
                    'id_layanan' => $_POST['id_layanan'],
                    'tanggal_masuk' => $_POST['tanggal_masuk'] ?? date('Y-m-d'),
                    'durasi' => $_POST['durasi'] ?? 1,
                    'total_biaya' => $_POST['total_biaya'] ?? 0
                ];

                error_log("Transaksi data: " . print_r($transaksiData, true));

                // 4. Create transaksi
                if ($this->transaksiModel->create($transaksiData)) {
                    // Update status kandang menjadi terpakai
                    $kandangModel = new Kandang();
                    $kandangModel->updateStatus($transaksiData['id_kandang'], 'terpakai');
                    
                    // Update status hewan menjadi sedang_dititipkan
                    $this->hewanModel->updateStatus($id_hewan, 'sedang_dititipkan');
                    
                    // Redirect ke halaman sukses
                    header('Location: index.php?page=transaksi&status=success&tab=pendaftaran');
                    exit;
                } else {
                    throw new Exception("Gagal membuat transaksi");
                }

            } catch (Exception $e) {
                error_log("Error in create transaksi: " . $e->getMessage());
                header('Location: index.php?page=transaksi&status=error&message=' . urlencode($e->getMessage()) . '&tab=pendaftaran');
                exit;
            }
        } else {
            header('Location: index.php?page=transaksi&status=error&message=Invalid request method&tab=pendaftaran');
            exit;
        }
    }

    private function handlePelanggan($data) {
    $id_pelanggan = $data['id_pelanggan'] ?? null;
    
    // Jika id_pelanggan ada, berarti pelanggan sudah terdaftar
    if (!empty($id_pelanggan)) {
        return $id_pelanggan;
    }
    
    // Jika tidak ada id_pelanggan, buat pelanggan baru
    $pelangganData = [
        'nama_pelanggan' => $data['search_pemilik'] ?? '',
        'no_hp' => $data['no_hp'] ?? '',
        'alamat' => $data['alamat'] ?? ''
    ];

    $newPelangganId = $this->pelangganModel->create($pelangganData);
    if ($newPelangganId) {
        return $newPelangganId;
    } else {
        throw new Exception("Gagal membuat pelanggan baru");
    }
}

    private function handleHewan($data, $id_pelanggan) {
        $hewanData = [
            'id_pelanggan' => $id_pelanggan,
            'nama_hewan' => $data['nama_hewan'] ?? '',
            'jenis' => $data['jenis'] ?? '',
            'ras' => $data['ras'] ?? '',
            'ukuran' => $data['ukuran'] ?? '',
            'warna' => $data['warna'] ?? '',
            'catatan' => $data['catatan'] ?? '',
            'status' => 'tersedia' // Akan diupdate setelah transaksi berhasil
        ];

        // Create hewan dan langsung return ID-nya
        if ($this->hewanModel->create($hewanData)) {
            // Ambil last insert ID dari database connection
            require_once __DIR__ . '/../config/database.php';
            $db = getDB();
            return $db->lastInsertId();
        } else {
            throw new Exception("Gagal membuat data hewan");
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