<?php
include "koneksi.php";

$tabel = ambil_tabel($daftar_tabel);
$info = $daftar_tabel[$tabel];

$fk_tampil = array(
    "pelanggan_id" => "(SELECT nama FROM pelanggan WHERE id = t.pelanggan_id)",
    "supplier_id"  => "(SELECT nama FROM supplier WHERE id = t.supplier_id)",
    "barang_id"    => "(SELECT CONCAT(kode_barang, ' - ', nama_barang) FROM barang WHERE id = t.barang_id)",
    "transaksi_id" => "(SELECT CONCAT('Transaksi ', x.id, ' - ', p.nama) FROM transaksi x JOIN pelanggan p ON p.id = x.pelanggan_id WHERE x.id = t.transaksi_id)"
);
$tambahan = "";
foreach ($info["kolom"] as $kolom) {
    if (isset($fk_tampil[$kolom])) {
        $tambahan .= ", " . $fk_tampil[$kolom] . " AS `tampil_" . $kolom . "`";
    }
}
$hasil = mysqli_query($koneksi, "SELECT t.*" . $tambahan . " FROM `$tabel` t");
if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// kolom yang ditampilkan (kolom id otomatis disembunyikan di tabel master, diganti kolom No)
$tampil = array();
foreach ($info["kolom"] as $kolom) {
    if (!in_array($kolom, $info["sembunyi"])) {
        $tampil[] = $kolom;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo aman($info["halaman"]); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="menu">
        <a class="tab" href="index.php">&laquo; Dashboard</a>
        <?php foreach ($daftar_tabel as $kode => $i) { ?>
            <a class="tab <?php echo $kode == $tabel ? "aktif" : ""; ?>" href="data.php?tabel=<?php echo urlencode($kode); ?>"><?php echo aman(label_kolom($kode)); ?></a>
        <?php } ?>
    </div>

    <div class="judul-bar">
        <h2 class="judul"><?php echo aman($info["halaman"]); ?></h2>
        <a class="btn btn-hijau" href="tambah.php?tabel=<?php echo urlencode($tabel); ?>">Tambah Data</a>
    </div>

    <table class="tabel">
        <tr>
            <th>No</th>
            <?php foreach ($tampil as $kolom) { ?>
                <th><?php echo aman(label_kolom($kolom)); ?></th>
            <?php } ?>
            <th>Tindakan</th>
        </tr>

        <?php if (mysqli_num_rows($hasil) > 0) { $no = 1; ?>
            <?php while ($baris = mysqli_fetch_assoc($hasil)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <?php foreach ($tampil as $kolom) {
                        $nilai = isset($baris["tampil_" . $kolom]) ? $baris["tampil_" . $kolom] : $baris[$kolom];
                        if ($kolom == "level" && isset($level_opsi[$nilai])) {
                            $nilai = $level_opsi[$nilai];
                        }
                        if (in_array($kolom, array("harga", "total"))) {
                            $nilai = number_format($nilai, 0, ",", ".");
                        }
                    ?>
                        <td><?php echo aman($nilai); ?></td>
                    <?php } ?>
                    <td>
                        <?php
                        $parameter = "tabel=" . urlencode($tabel);
                        foreach ($info["pk"] as $pk) {
                            $parameter .= "&" . urlencode($pk) . "=" . urlencode($baris[$pk]);
                        }
                        ?>
                        <a class="btn btn-oranye" href="edit.php?<?php echo $parameter; ?>">Edit</a>
                        <a class="btn btn-merah" href="hapus.php?<?php echo $parameter; ?>"
                           onclick="return confirm('Anda yakin akan menghapus <?php echo aman($info["objek"]); ?> ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($tampil) + 2; ?>">Belum ada data.</td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
