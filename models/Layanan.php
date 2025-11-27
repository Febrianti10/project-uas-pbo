<?php
require_once __DIR__ . '/../config/database.php';

class Layanan
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM layanan ORDER BY nama";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM layanan WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}