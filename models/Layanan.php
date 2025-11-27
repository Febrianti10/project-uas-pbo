<?php
require_once __DIR__ . '/../helper/helper.php';  
require_once __DIR__ . '/../config/database.php';

class Layanan
{
    private $db;

    public function __construct()
    {
        $this->db = getDB(); // === PDO
    }

    // Tambah layanan
    public function tambahLayanan($nama, $harga, $jenis)
    {
        $nama  = clean($nama);
        $jenis = clean($jenis);
        $harga = number_only($harga);

        $sql = "INSERT INTO layanan (nama, harga, jenis) 
                VALUES (:nama, :harga, :jenis)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nama'  => $nama,
            'harga' => $harga,
            'jenis' => $jenis
        ]);
    }

    // Ambil semua layanan
    public function getAllLayanan()
    {
        $sql = "SELECT * FROM layanan ORDER BY jenis ASC, id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Ambil layanan berdasarkan jenis
    public function getByJenis($jenis)
    {
        $jenis = clean($jenis);

        $sql = "SELECT * FROM layanan 
                WHERE jenis = :jenis 
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['jenis' => $jenis]);
        return $stmt->fetchAll();
    }

    // Ambil satu layanan
    public function getLayananById($id)
    {
        $sql = "SELECT * FROM layanan WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => intval($id)]);
        return $stmt->fetch();
    }

    // Update layanan
    public function updateLayanan($id, $nama, $harga, $jenis)
    {
        $sql = "UPDATE layanan 
                SET nama = :nama, 
                    harga = :harga, 
                    jenis = :jenis 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'    => intval($id),
            'nama'  => clean($nama),
            'harga' => number_only($harga),
            'jenis' => clean($jenis)
        ]);
    }

    // Hapus layanan
    public function deleteLayanan($id)
    {
        $sql = "DELETE FROM layanan WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => intval($id)
        ]);
    }
}
?>