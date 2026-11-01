<?php
include "koneksi.php";

$tabel = ambil_tabel($daftar_tabel);
$info = $daftar_tabel[$tabel];

$syarat = array();
foreach ($info["pk"] as $pk) {
    if (!isset($_GET[$pk])) {
        die("Kunci data tidak lengkap.");
    }
    $nilai_pk = mysqli_real_escape_string($koneksi, $_GET[$pk]);
    $syarat[] = "`" . $pk . "`='" . $nilai_pk . "'";
}
$where = implode(" AND ", $syarat);

$hasil = mysqli_query($koneksi, "SELECT * FROM `$tabel` WHERE $where");
if (!$hasil || mysqli_num_rows($hasil) == 0) {
    die("Data tidak ditemukan.");
}
$data = mysqli_fetch_assoc($hasil);
$pesan = "";

if (isset($_POST["update"])) {
    $perubahan = array();
    foreach ($info["kolom"] as $kolom) {
        if (in_array($kolom, $info["pk"])) {
            continue;
        }
        if (isset($info["otomatis"]) && in_array($kolom, $info["otomatis"])) {
            continue; // total transaksi dihitung dari detail
        }
        $nilai = isset($_POST[$kolom]) ? $_POST[$kolom] : "";
        $nilai = nilai_otomatis($koneksi, $tabel, $kolom, $nilai);
        $nilai = format_nilai_post($kolom, $nilai);
        $nilai = mysqli_real_escape_string($koneksi, $nilai);
        $perubahan[] = "`" . $kolom . "`='" . $nilai . "'";
    }

    $sql = "UPDATE `$tabel` SET " . implode(", ", $perubahan) . " WHERE $where";
    if (mysqli_query($koneksi, $sql)) {
        if ($tabel == "transaksi_detail") {
            hitung_total($koneksi, $_GET["transaksi_id"]);
        }
        header("Location: data.php?tabel=" . urlencode($tabel));
        exit;
    } else {
        $pesan = "Data gagal diubah: " . mysqli_error($koneksi);
        // tampilkan kembali isian terakhir pengguna
        foreach ($info["kolom"] as $kolom) {
            if (!in_array($kolom, $info["pk"]) && isset($_POST[$kolom])) {
                $data[$kolom] = $_POST[$kolom];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo aman($info["halaman"]); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="judul">Edit <?php echo aman($info["halaman"]); ?></h2>

    <?php if ($pesan != "") { ?><div class="pesan"><?php echo aman($pesan); ?></div><?php } ?>

    <form method="post" class="form">
        <?php foreach ($info["kolom"] as $kolom) {
            if (in_array($kolom, $info["auto"])) continue;
        ?>
            <div class="baris">
                <label><?php echo aman(label_kolom($kolom)); ?></label>
                <div class="isi">
                    <?php
                    if (isset($info["otomatis"]) && in_array($kolom, $info["otomatis"])) {
                        echo aman(number_format($data[$kolom], 0, ",", ".")) . " <small>(dihitung otomatis dari detail transaksi)</small>";
                    } elseif (in_array($kolom, $info["pk"])) {
                        echo aman($data[$kolom]);
                    } else {
                        tampil_input($koneksi, $tabel, $kolom, $data[$kolom]);
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
        <div class="tombol">
            <button type="submit" name="update" class="btn btn-hijau">Update</button>
            <a class="btn btn-merah" href="data.php?tabel=<?php echo $tabel; ?>">Batal</a>
        </div>
    </form>
</div>
<?php skrip_otomatis($tabel); ?>
</body>
</html>
