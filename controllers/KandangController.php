<?php
// Pastikan autoload sudah me-load model Kandang
// require_once __DIR__ . '/../models/Kandang.php'; 

class KandangController
{
    private $model;

    public function __construct()
    {
        // Asumsi class Kandang sudah dibuat di models/Kandang.php
        $this->model = new Kandang();
    }

    /**
     * Menangani POST request dari form 'Tambah Kandang'
     * index.php?action=storeKandang
     */
    public function store()
    {
        // Hanya proses jika request method adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        $dataKandang = [
            'kode'    => $_POST['kode'] ?? '',
            'tipe'    => $_POST['tipe'] ?? '',
            'catatan' => $_POST['catatan'] ?? ''
        ];

        if (empty($dataKandang['kode']) || empty($dataKandang['tipe'])) {
            header('Location: index.php?page=hewan&status=error&message=Kode dan Tipe Kandang harus diisi');
            exit;
        }

        if ($this->model->create($dataKandang)) {
            // Berhasil disimpan
            header('Location: index.php?page=hewan&status=success&message=Kandang berhasil ditambahkan!');
        } else {
            // Gagal (misalnya kode duplikat, dll.)
            header('Location: index.php?page=hewan&status=error&message=Gagal menambahkan kandang, coba kode lain.');
        }
        exit;
    }

    /**
     * Menghapus kandang
     * index.php?action=deleteKandang&id=X
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=hewan&status=error&message=ID Kandang tidak ditemukan.');
            exit;
        }

        if ($this->model->delete($id)) {
            header('Location: index.php?page=hewan&status=success&message=Kandang berhasil dihapus!');
        } else {
            header('Location: index.php?page=hewan&status=error&message=Gagal menghapus kandang.');
        }
        exit;
    }
    
    // Anda juga bisa menambahkan public function update() di sini
}