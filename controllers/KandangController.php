<?php

// Memastikan Kandang Model di-load (melalui spl_autoload_register di index.php)

class KandangController
{
    private $model;

    public function __construct()
    {
        // Panggil Kandang Model
        $this->model = new Kandang();
    }

    /**
     * Menangani POST request untuk menyimpan data kandang baru.
     * Dipanggil via index.php?action=storeKandang
     */
    public function store()
    {
        // Proteksi dasar untuk memastikan data dikirim via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=hewan&status=error&message=Invalid request method.');
            exit;
        }
        
        $dataKandang = [
            'kode'    => trim($_POST['kode'] ?? ''),
            'tipe'    => $_POST['tipe'] ?? '',
            'catatan' => trim($_POST['catatan'] ?? null)
        ];

        // Validasi
        if (empty($dataKandang['kode']) || empty($dataKandang['tipe'])) {
            header('Location: index.php?page=hewan&status=error&message=Kode dan Tipe Kandang harus diisi.');
            exit;
        }

        // Simpan ke database
        if ($this->model->create($dataKandang)) {
            // Berhasil
            header('Location: index.php?page=hewan&status=success&message=Kandang ' . $dataKandang['kode'] . ' berhasil ditambahkan.');
        } else {
            // Gagal, kemungkinan kode duplikat
            header('Location: index.php?page=hewan&status=error&message=Gagal menambahkan kandang. Kode mungkin sudah ada atau terjadi kesalahan database.');
        }
        exit;
    }

    /**
     * Menghapus kandang berdasarkan ID.
     * Dipanggil via index.php?action=deleteKandang&id=X
     */
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=hewan&status=error&message=ID Kandang tidak valid.');
            exit;
        }

        if ($this->model->delete($id)) {
            header('Location: index.php?page=hewan&status=success&message=Kandang berhasil dihapus.');
        } else {
            header('Location: index.php?page=hewan&status=error&message=Gagal menghapus kandang. Mungkin kandang sedang terisi.');
        }
        exit;
    }
    
    // Anda bisa tambahkan public function update() dan public function read() di sini
}