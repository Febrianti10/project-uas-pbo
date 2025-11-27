<?php
$paketList = $layananModel->getPaketPenitipan();   // layanan utama
$layananTambahanList = $layananModel->getLayananTambahan(); // layanan tambahan

include 'views/transaksi.php';
 
