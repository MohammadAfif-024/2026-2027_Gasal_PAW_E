<?php
include "koneksi.php";

$jumlah = array();
foreach ($daftar_tabel as $nama_tabel => $info) {
    $hasil = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$nama_tabel`");
    $baris = mysqli_fetch_array($hasil);
    $jumlah[$nama_tabel] = $baris["total"]; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="judul">Sistem Informasi Penjualan</h2>
    <p>Praktikum PAW Modul 5 - Database</p>

    <table class="tabel">
        <tr>
            <th>No</th>
            <th>Menu Data</th>
            <th>Jumlah Data</th>
        </tr>
        <?php $no = 1; foreach ($daftar_tabel as $nama_tabel => $info) { ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><a href="data.php?tabel=<?php echo $nama_tabel; ?>"><?php echo aman($info["halaman"]); ?></a></td>
                <td><?php echo $jumlah[$nama_tabel]; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
