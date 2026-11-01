<?php
include "koneksi.php";

$tabel = ambil_tabel($daftar_tabel);
$info = $daftar_tabel[$tabel];
$pesan = "";

if (isset($_POST["simpan"])) {
    $kolom_simpan = array();
    $nilai_simpan = array();

    foreach ($info["kolom"] as $kolom) {
        if (in_array($kolom, $info["auto"])) {
            continue;
        }
        $nilai = isset($_POST[$kolom]) ? $_POST[$kolom] : "";
        if (isset($info["otomatis"]) && in_array($kolom, $info["otomatis"])) {
            $nilai = 0;
        }
        $nilai = nilai_otomatis($koneksi, $tabel, $kolom, $nilai);
        $nilai = format_nilai_post($kolom, $nilai);
        $kolom_simpan[] = "`" . $kolom . "`";
        $nilai_simpan[] = "'" . mysqli_real_escape_string($koneksi, $nilai) . "'";
    }

    $sql = "INSERT INTO `$tabel` (" . implode(", ", $kolom_simpan) . ")
            VALUES (" . implode(", ", $nilai_simpan) . ")";

    if (mysqli_query($koneksi, $sql)) {
        if ($tabel == "transaksi_detail") {
            hitung_total($koneksi, $_POST["transaksi_id"]);
        }
        header("Location: data.php?tabel=" . $tabel);
        exit;
    } else {
        $pesan = "Data gagal ditambahkan: " . mysqli_error($koneksi);
    }
} // <--- TAMBAHKAN KURUNG KURAWAL TUTUP INI UNTUK MENUTUP if (isset($_POST["simpan"]))
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah <?php echo aman($info["halaman"]); ?> Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="judul">Tambah <?php echo aman($info["halaman"]); ?> Baru</h2>

    <?php if ($pesan != "") { ?><div class="pesan"><?php echo aman($pesan); ?></div><?php } ?>

    <form method="post" class="form">
        <?php foreach ($info["kolom"] as $kolom) {
            if (in_array($kolom, $info["auto"])) continue;
            if (isset($info["otomatis"]) && in_array($kolom, $info["otomatis"])) continue;
            $isi = isset($_POST[$kolom]) ? $_POST[$kolom] : "";
        ?>
            <div class="baris">
                <label><?php echo aman(label_kolom($kolom)); ?></label>
                <div class="isi"><?php tampil_input($koneksi, $tabel, $kolom, $isi); ?></div>
            </div>
        <?php } ?>
        <div class="tombol">
            <button type="submit" name="simpan" class="btn btn-hijau">Simpan</button>
            <a class="btn btn-merah" href="data.php?tabel=<?php echo $tabel; ?>">Batal</a>
        </div>
    </form>
</div>
<?php skrip_otomatis($tabel); ?>
</body>
</html>