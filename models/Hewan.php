<?php
/**
 * Model Hewan
 * Mengelola data hewan di database
 */

class Hewan {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance();
    }
    
    /**
     * Get semua data hewan dengan informasi pelanggan
     */
    public function getAll() {
        $sql = "SELECT h.*, p.nama_pelanggan, p.no_hp 
                FROM hewan h
                INNER JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                ORDER BY h.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get hewan by ID
     */
    public function getById($id) {
        $sql = "SELECT h.*, p.nama_pelanggan, p.no_hp, p.alamat 
                FROM hewan h
                INNER JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                WHERE h.id_hewan = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get hewan berdasarkan pelanggan
     */
    public function getByPelanggan($id_pelanggan) {
        $sql = "SELECT * FROM hewan WHERE id_pelanggan = ? ORDER BY nama_hewan ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_pelanggan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get hewan yang tersedia (tidak sedang dititipkan)
     */
    public function getAvailable() {
        $sql = "SELECT h.*, p.nama_pelanggan 
                FROM hewan h
                INNER JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                WHERE h.status = 'tersedia'
                ORDER BY h.nama_hewan ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Insert hewan baru
     */
    public function insert($data) {
        $sql = "INSERT INTO hewan 
                (id_pelanggan, nama_hewan, jenis, ras, ukuran, warna, catatan_khusus, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_pelanggan'],
            $data['nama_hewan'],
            $data['jenis'],
            $data['ras'] ?? null,
            $data['ukuran'],
            $data['warna'] ?? null,
            $data['catatan_khusus'] ?? null,
            $data['status'] ?? 'tersedia'
        ]);
    }
    
    /**
     * Update data hewan
     */
    public function update($id, $data) {
        $sql = "UPDATE hewan 
                SET id_pelanggan = ?, 
                    nama_hewan = ?, 
                    jenis = ?, 
                    ras = ?, 
                    ukuran = ?, 
                    warna = ?, 
                    catatan_khusus = ?
                WHERE id_hewan = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_pelanggan'],
            $data['nama_hewan'],
            $data['jenis'],
            $data['ras'] ?? null,
            $data['ukuran'],
            $data['warna'] ?? null,
            $data['catatan_khusus'] ?? null,
            $id
        ]);
    }
    
    /**
     * Update status hewan
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE hewan SET status = ? WHERE id_hewan = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    /**
     * Delete hewan
     */
    public function delete($id) {
        $sql = "DELETE FROM hewan WHERE id_hewan = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Cek apakah hewan sedang dalam transaksi aktif
     */
    public function hasActiveTransaction($id) {
        $sql = "SELECT COUNT(*) as total 
                FROM transaksi 
                WHERE id_hewan = ? 
                AND status = 'sedang_dititipkan'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] > 0;
    }
}
?>
