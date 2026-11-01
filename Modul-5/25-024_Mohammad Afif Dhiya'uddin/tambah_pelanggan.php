<?php
    include "koneksi.php";

    if (isset($_POST['simpan'])) {
        $nama = $_POST['nama'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $telp = $_POST['telp'];
        $alamat = $_POST['alamat'];

        $query = mysqli_query($koneksi, "INSERT INTO pelanggan
            (nama, jenis_kelamin, telp, alamat)
            VALUES ('$nama', '$jenis_kelamin', '$telp', '$alamat')");

        if ($query) {
            echo "<script>
                alert('Data pelanggan berhasil ditambahkan!');
                window.location='pelanggan.php';
            </script>";
        } else {
            echo "Gagal menambahkan data: " . mysqli_error($koneksi);
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0;">
    <h1>Tambah Pelanggan</h1>
    <p>Masukkan informasi pelanggan baru.</p>

    <div class="tabel-container">
        <form method="POST">

            <label>Nama Pelanggan</label><br>
            <input type="text" name="nama" required style="width:100%; margin:10px 0 20px;"><br>

            <label>Jenis Kelamin</label><br>
            <select name="jenis_kelamin" required style="width:100%; padding:10px; margin:10px 0 20px;">
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select><br>

            <label>Nomor Telepon</label><br>
            <input type="text" name="telp" required style="width:100%; margin:10px 0 20px;"><br>

            <label>Alamat</label><br>
            <textarea name="alamat" required style="width:100%; padding:10px; margin:10px 0 20px;"></textarea><br>

            <button type="submit" name="simpan">Simpan</button>
            <a href="pelanggan.php" class="tombol-reset">Kembali</a>

        </form>
    </div>
</div>

</body>
</html>