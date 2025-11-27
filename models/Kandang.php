<?php
require_once __DIR__ . '/../config/database.php';

class Kandang
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $sql = "SELECT 
                    k.id_kandang as id,
                    k.kode_kandang as kode,
                    k.tipe,
                    k.catatan,
                    k.status
                FROM kandang k 
                ORDER BY k.kode_kandang";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByType($type)
    {
        $sql = "SELECT COUNT(*) as total FROM kandang WHERE tipe = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}