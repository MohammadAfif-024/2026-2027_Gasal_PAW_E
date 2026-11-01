<?php
include "koneksi.php";

$tabel = ambil_tabel($daftar_tabel);
$info = $daftar_tabel[$tabel];

$syarat = array();
foreach ($info["pk"] as $pk) {
    if (!isset($_GET[$pk])) {
        die("Kunci data tidak lengkap.");
    }
    $nilai = mysqli_real_escape_string($koneksi, $_GET[$pk]);
    $syarat[] = "`" . $pk . "`='" . $nilai . "'";
}
$where = implode(" AND ", $syarat);

if (mysqli_query($koneksi, "DELETE FROM `$tabel` WHERE $where")) {
    if ($tabel == "transaksi_detail") {
        hitung_total($koneksi, $_GET["transaksi_id"]);
    }
    // Rapikan nomor id: id berikutnya = id terbesar yang ada + 1
    if (count($info["auto"]) > 0) {
        mysqli_query($koneksi, "ALTER TABLE `$tabel` AUTO_INCREMENT = 1");
    }
    header("Location: data.php?tabel=" . urlencode($tabel));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gagal Menghapus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="judul">Data gagal dihapus</h2>
    <div class="pesan">
        Data masih digunakan oleh tabel lain, hapus dulu data yang terkait.<br>
        <?php echo aman(mysqli_error($koneksi)); ?>
    </div>
    <p><a class="btn btn-merah" href="data.php?tabel=<?php echo $tabel; ?>">Kembali</a></p>
</div>
</body>
</html>
