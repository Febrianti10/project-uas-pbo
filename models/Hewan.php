<?php
require_once __DIR__ . '/../config/database.php';

class Hewan
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Ambil semua data hewan
     */
    public function getAll()
    {
        $sql = "SELECT h.*, p.nama_pelanggan 
                FROM hewan h
                LEFT JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                ORDER BY h.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ambil data hewan berdasarkan ID
     */
    public function getById($id)
    {
        $sql = "SELECT h.*, 
                    p.nama_pelanggan, p.no_hp, p.alamat
                FROM hewan h
                LEFT JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                WHERE h.id_hewan = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $id]);
        return $stmt->fetch();
    }

    /**
     * Tambah hewan baru
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO hewan 
                    (id_pelanggan, nama_hewan, jenis, ras, ukuran, warna, keterangan, status)
                    VALUES 
                    (:id_pelanggan, :nama_hewan, :jenis, :ras, :ukuran, :warna, :keterangan, :status)";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                "id_pelanggan" => $data["id_pelanggan"],
                "nama_hewan" => $data["nama_hewan"],
                "jenis" => $data["jenis"],
                "ras" => $data["ras"],
                "ukuran" => $data["ukuran"],
                "warna" => $data["warna"],
                "keterangan" => $data["keterangan"] ?? null,
                "status" => "tersedia",
            ]);

        } catch (Exception $e) {
            error_log("Error create hewan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update data hewan
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE hewan SET 
                        id_pelanggan = :id_pelanggan,
                        nama_hewan = :nama_hewan,
                        jenis = :jenis,
                        ras = :ras,
                        ukuran = :ukuran,
                        warna = :warna,
                        keterangan = :keterangan
                    WHERE id_hewan = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                "id" => $id,
                "id_pelanggan" => $data["id_pelanggan"],
                "nama_hewan" => $data["nama_hewan"],
                "jenis" => $data["jenis"],
                "ras" => $data["ras"],
                "ukuran" => $data["ukuran"],
                "warna" => $data["warna"],
                "keterangan" => $data["keterangan"] ?? null,
            ]);

        } catch (Exception $e) {
            error_log("Error update hewan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hapus hewan (soft delete atau hard delete, sesuai kebutuhan)
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM hewan WHERE id_hewan = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(["id" => $id]);
        } catch (Exception $e) {
            error_log("Error delete hewan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update status hewan (tersedia, sedang_dititipkan, sudah_diambil)
     */
    public function updateStatus($id, $status)
    {
        $allowed = ["tersedia", "sedang_dititipkan", "sudah_diambil"];

        if (!in_array($status, $allowed)) {
            $status = "tersedia";
        }

        $sql = "UPDATE hewan SET status = :status WHERE id_hewan = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "id" => $id,
            "status" => $status
        ]);
    }

    /**
     * Cari hewan berdasarkan nama/jenis/ras
     */
    public function search($keyword)
    {
        $sql = "SELECT h.*, p.nama_pelanggan
                FROM hewan h
                LEFT JOIN pelanggan p ON h.id_pelanggan = p.id_pelanggan
                WHERE h.nama_hewan LIKE :key
                OR h.jenis LIKE :key
                OR h.ras LIKE :key
                ORDER BY h.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(["key" => "%{$keyword}%"]);
        return $stmt->fetchAll();
    }
}