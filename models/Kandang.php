<?php

// Pastikan class Database sudah di-load/didefinisikan sebelum ini
// Asumsi Anda punya class Database untuk koneksi

class Kandang
{
    private $db;
    private $table = 'kandang';

    public function __construct()
    {
        // Asumsi koneksi database dilakukan melalui class Database
        // Pastikan Anda memiliki file Database.php atau koneksi global
        global $db;
        $this->db = $db ?? (new Database())->getConnection();
    }

    /**
     * Menyimpan data kandang baru ke database.
     * @param array $data array asosiatif dengan keys 'kode', 'tipe', 'catatan'
     * @return bool True jika berhasil, False jika gagal.
     */
    public function create(array $data): bool
    {
        $kode    = $data['kode'] ?? '';
        $tipe    = $data['tipe'] ?? '';
        $catatan = $data['catatan'] ?? null;
        
        // Menyiapkan statement SQL
        $sql = "INSERT INTO {$this->table} (kode, tipe, catatan) VALUES (:kode, :tipe, :catatan)";
        
        try {
            $stmt = $this->db->prepare($sql);
            
            // Binding parameter
            $stmt->bindParam(':kode', $kode);
            $stmt->bindParam(':tipe', $tipe);
            $stmt->bindParam(':catatan', $catatan);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error, misalnya duplikasi kode
            error_log("Error creating kandang: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil semua data kandang.
     * @return array List semua kandang.
     */
    public function getAll(): array
    {
        $sql = "SELECT id_kandang AS id, kode, tipe, catatan, status FROM {$this->table} ORDER BY kode ASC";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching kandang list: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Menghapus kandang berdasarkan ID.
     * @param int $id ID kandang.
     * @return bool True jika berhasil dihapus.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id_kandang = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting kandang: " . $e->getMessage());
            return false;
        }
    }
}