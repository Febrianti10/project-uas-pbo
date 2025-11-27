if ($action == 'update') {
    $id      = $_POST['id'];
    $kode    = $_POST['kode'];
    $tipe    = $_POST['tipe'];
    $status  = $_POST['status'];
    $catatan = $_POST['catatan'];

    $sql = "UPDATE kandang SET 
                kode='$kode',
                tipe='$tipe',
                status='$status',
                catatan='$catatan'
            WHERE id='$id'";
    mysqli_query($conn, $sql);

    header("Location: index.php?page=hewan");
    exit;
}
