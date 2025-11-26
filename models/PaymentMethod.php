<?php
class PaymentMethod
{
    private $koneksi;

    public function __construct($db)
    {
        $this->koneksi = $db;
    }

    public function tambahPaymentMethod($nama)
    {
        $query = "INSERT INTO payment_method (nama) VALUES (?)";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("s", $nama);
        return $stmt->execute();
    }

    public function getAllPaymentMethod()
    {
        $query = "SELECT * FROM payment_method ORDER BY id DESC";
        return $this->koneksi->query($query);
    }

    public function getPaymentMethodById($id)
    {
        $query = "SELECT * FROM payment_method WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePaymentMethod($id, $nama)
    {
        $query = "UPDATE payment_method SET nama = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("si", $nama, $id);
        return $stmt->execute();
    }

    public function deletePaymentMethod($id)
    {
        $query = "DELETE FROM payment_method WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
