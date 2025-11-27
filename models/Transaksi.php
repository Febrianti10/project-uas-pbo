<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Model Transaksi
 * CRUD untuk transaksi penitipan hewan
 * 
 */
class Transaksi {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * GENERATE NOMOR TRANSAKSI
     * Format: TRX-YYYYMMDD-XXX
     * 
     * @return string
     */
    public function generateNomorTransaksi() {
        $date = date('Ymd');
        $prefix = "TRX-{$date}-";
        
        // Cari nomor terakhir hari ini
        $sql = "SELECT nomor_transaksi FROM transaksi 
                WHERE nomor_transaksi LIKE :prefix 
                ORDER BY nomor_transaksi DESC LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['prefix' => $prefix . '%']);
        $last = $stmt->fetch();
        
        if ($last) {
            // Ambil 3 digit terakhir, tambah 1
            $lastNumber = (int)substr($last['nomor_transaksi'], -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * CREATE - Buat transaksi baru (Check-In)
     * 
     * @param array $data Data transaksi
     * @param array $detailLayanan Array layanan yang dipilih
     * @return int|false ID transaksi baru atau false
     */
    public function create($data, $detailLayanan = []) {
        try {
            $this->db->beginTransaction();
            
            // Generate nomor transaksi
            $nomorTransaksi = $this->generateNomorTransaksi();
            
            // Insert transaksi
            $sql = "INSERT INTO transaksi 
                    (nomor_transaksi, id_pelanggan, id_hewan, id_user, 
                     tanggal_masuk, jam_masuk, estimasi_tanggal_keluar, estimasi_jam_keluar,
                     durasi_hari, status, subtotal, diskon, total_biaya, 
                     metode_pembayaran, status_pembayaran) 
                    VALUES 
                    (:nomor_transaksi, :id_pelanggan, :id_hewan, :id_user,
                     :tanggal_masuk, :jam_masuk, :estimasi_tanggal_keluar, :estimasi_jam_keluar,
                     :durasi_hari, :status, :subtotal, :diskon, :total_biaya,
                     :metode_pembayaran, :status_pembayaran)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'nomor_transaksi' => $nomorTransaksi,
                'id_pelanggan' => $data['id_pelanggan'],
                'id_hewan' => $data['id_hewan'],
                'id_user' => $data['id_user'],
                'tanggal_masuk' => $data['tanggal_masuk'],
                'jam_masuk' => $data['jam_masuk'] ?? date('H:i:s'),
                'estimasi_tanggal_keluar' => $data['estimasi_tanggal_keluar'] ?? null,
                'estimasi_jam_keluar' => $data['estimasi_jam_keluar'] ?? null,
                'durasi_hari' => $data['durasi_hari'] ?? 0,
                'status' => 'sedang_dititipkan',
                'subtotal' => $data['subtotal'] ?? 0,
                'diskon' => $data['diskon'] ?? 0,
                'total_biaya' => $data['total_biaya'] ?? 0,
                'metode_pembayaran' => $data['metode_pembayaran'] ?? null,
                'status_pembayaran' => $data['status_pembayaran'] ?? 'belum_lunas'
            ]);
            
            $idTransaksi = $this->db->lastInsertId();
            
            // Insert detail layanan
            if (!empty($detailLayanan)) {
                $sqlDetail = "INSERT INTO detail_transaksi 
                              (id_transaksi, id_layanan, jumlah, harga_satuan, subtotal) 
                              VALUES (:id_transaksi, :id_layanan, :jumlah, :harga_satuan, :subtotal)";
                
                $stmtDetail = $this->db->prepare($sqlDetail);
                
                foreach ($detailLayanan as $detail) {
                    $stmtDetail->execute([
                        'id_transaksi' => $idTransaksi,
                        'id_layanan' => $detail['id_layanan'],
                        'jumlah' => $detail['jumlah'] ?? 1,
                        'harga_satuan' => $detail['harga_satuan'],
                        'subtotal' => $detail['subtotal']
                    ]);
                }
            }
            
            // Update status hewan jadi sedang_dititipkan
            $sqlHewan = "UPDATE hewan SET status = 'sedang_dititipkan' WHERE id_hewan = :id";
            $stmtHewan = $this->db->prepare($sqlHewan);
            $stmtHewan->execute(['id' => $data['id_hewan']]);
            
            $this->db->commit();
            return $idTransaksi;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error create transaksi: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * GET BY ID - Ambil transaksi lengkap dengan detail
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $sql = "SELECT t.*, 
                       p.nama_pelanggan, p.no_hp, p.alamat,
                       h.nama_hewan, h.jenis, h.ras, h.ukuran, h.warna,
                       u.nama_lengkap as nama_kasir
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                LEFT JOIN user u ON t.id_user = u.id_user
                WHERE t.id_transaksi = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $transaksi = $stmt->fetch();
        
        if ($transaksi) {
            // Ambil detail layanan
            $transaksi['detail_layanan'] = $this->getDetailLayanan($id);
        }
        
        return $transaksi;
    }
    
    /**
     * GET BY NOMOR - Ambil transaksi berdasarkan nomor transaksi
     * 
     * @param string $nomorTransaksi
     * @return array|false
     */
    public function getByNomor($nomorTransaksi) {
        $sql = "SELECT t.*, 
                       p.nama_pelanggan, p.no_hp, p.alamat,
                       h.nama_hewan, h.jenis, h.ras, h.ukuran, h.warna,
                       u.nama_lengkap as nama_kasir
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                LEFT JOIN user u ON t.id_user = u.id_user
                WHERE t.nomor_transaksi = :nomor";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nomor' => $nomorTransaksi]);
        $transaksi = $stmt->fetch();
        
        if ($transaksi) {
            $transaksi['detail_layanan'] = $this->getDetailLayanan($transaksi['id_transaksi']);
        }
        
        return $transaksi;
    }
    
    /**
     * GET DETAIL LAYANAN - Ambil detail layanan transaksi
     * 
     * @param int $idTransaksi
     * @return array
     */
    public function getDetailLayanan($idTransaksi) {
        $sql = "SELECT dt.*, l.kode_layanan, l.nama_layanan, l.kategori_layanan
                FROM detail_transaksi dt
                LEFT JOIN layanan l ON dt.id_layanan = l.id_layanan
                WHERE dt.id_transaksi = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idTransaksi]);
        return $stmt->fetchAll();
    }
    
    /**
     * SEARCH - Cari transaksi berdasarkan keyword
     * 
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        $sql = "SELECT t.*, 
                       p.nama_pelanggan,
                       h.nama_hewan
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                WHERE t.nomor_transaksi LIKE :keyword
                OR p.nama_pelanggan LIKE :keyword
                OR h.nama_hewan LIKE :keyword
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['keyword' => "%{$keyword}%"]);
        return $stmt->fetchAll();
    }
    
    /**
     * UPDATE CHECKOUT - Proses check-out & pembayaran
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCheckout($id, $data) {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE transaksi 
                    SET tanggal_keluar_aktual = :tanggal_keluar,
                        jam_keluar_aktual = :jam_keluar,
                        durasi_hari = :durasi_hari,
                        status = 'selesai',
                        diskon = :diskon,
                        total_biaya = :total_biaya,
                        metode_pembayaran = :metode_pembayaran,
                        status_pembayaran = 'lunas'
                    WHERE id_transaksi = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'tanggal_keluar' => $data['tanggal_keluar_aktual'],
                'jam_keluar' => $data['jam_keluar_aktual'] ?? date('H:i:s'),
                'durasi_hari' => $data['durasi_hari'],
                'diskon' => $data['diskon'] ?? 0,
                'total_biaya' => $data['total_biaya'],
                'metode_pembayaran' => $data['metode_pembayaran']
            ]);
            
            // Update status hewan jadi sudah_diambil
            $transaksi = $this->getById($id);
            $sqlHewan = "UPDATE hewan SET status = 'sudah_diambil' WHERE id_hewan = :id";
            $stmtHewan = $this->db->prepare($sqlHewan);
            $stmtHewan->execute(['id' => $transaksi['id_hewan']]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error checkout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * GET SEDANG DITITIPKAN - Ambil semua transaksi yang masih berlangsung
     * 
     * @return array
     */
    public function getSedangDititipkan() {
        $sql = "SELECT t.*, p.nama_pelanggan, h.nama_hewan
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                WHERE t.status = 'sedang_dititipkan'
                ORDER BY t.tanggal_masuk DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * GET LAPORAN HARIAN
     * 
     * @param string $tanggal (Y-m-d)
     * @return array
     */
    public function getLaporanHarian($tanggal) {
        $sql = "SELECT t.*, p.nama_pelanggan, h.nama_hewan
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                WHERE DATE(t.tanggal_masuk) = :tanggal
                ORDER BY t.tanggal_masuk DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tanggal' => $tanggal]);
        return $stmt->fetchAll();
    }
    
    /**
     * HITUNG TOTAL PENDAPATAN
     * 
     * @param string $tanggalMulai
     * @param string $tanggalAkhir
     * @return float
     */
    public function hitungPendapatan($tanggalMulai, $tanggalAkhir) {
        $sql = "SELECT SUM(total_biaya) as total 
                FROM transaksi 
                WHERE DATE(tanggal_masuk) BETWEEN :mulai AND :akhir
                AND status_pembayaran = 'lunas'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'mulai' => $tanggalMulai,
            'akhir' => $tanggalAkhir
        ]);
        
        $result = $stmt->fetch();
        return (float)($result['total'] ?? 0);
    }
}
