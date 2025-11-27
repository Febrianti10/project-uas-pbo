<?php
class Layanan
{
    private $koneksi;

    public function __construct($db)
    {
        $this->koneksi = $db;
    }

    // Tambah layanan (baik paket atau tambahan)
    public function tambahLayanan($nama, $harga, $jenis)
    {
        $query = "INSERT INTO layanan (nama, harga, jenis) VALUES (?, ?, ?)";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("sds", $nama, $harga, $jenis);
        return $stmt->execute();
    }

    // Ambil semua layanan
    public function getAllLayanan()
    {
        $query = "SELECT * FROM layanan ORDER BY jenis ASC, id DESC";
        return $this->koneksi->query($query);
    }

    // Ambil layanan berdasarkan jenis
    public function getByJenis($jenis)
    {
        $query = "SELECT * FROM layanan WHERE jenis = ? ORDER BY id DESC";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("s", $jenis);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Ambil satu layanan
    public function getLayananById($id)
    {
        $query = "SELECT * FROM layanan WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update layanan
    public function updateLayanan($id, $nama, $harga, $jenis)
    {
        $query = "UPDATE layanan SET nama = ?, harga = ?, jenis = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("sdsi", $nama, $harga, $jenis, $id);
        return $stmt->execute();
    }

    // Hapus layanan
    public function deleteLayanan($id)
    {
        $query = "DELETE FROM layanan WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
