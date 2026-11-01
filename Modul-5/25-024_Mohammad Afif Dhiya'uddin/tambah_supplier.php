
<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = mysqli_query($koneksi, "INSERT INTO supplier
        (nama, telp, alamat)
        VALUES ('$nama', '$telp', '$alamat')");

    if ($query) {
        echo "<script>alert('Supplier berhasil ditambahkan'); window.location='supplier.php';</script>";
    } else {
        echo "Gagal menambahkan supplier: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Tambah Supplier</h2>

    <form method="POST">
        <label>Nama Supplier</label>
        <input type="text" name="nama" required>

        <label>Telepon</label>
        <input type="text" name="telp" required>

        <label>Alamat</label>
        <textarea name="alamat" required></textarea>

        <button type="submit" name="simpan">Simpan</button>
        <a href="supplier.php">Kembali</a>
    </form>
</div>

</body>
</html>
