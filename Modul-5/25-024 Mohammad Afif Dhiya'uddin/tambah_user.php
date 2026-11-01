
<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    $query = mysqli_query($koneksi, "INSERT INTO user
        (username, password, nama, alamat, hp, level)
        VALUES ('$username', '$password', '$nama', '$alamat', '$hp', '$level')");

    if ($query) {
        echo "<script>alert('User berhasil ditambahkan'); window.location='user.php';</script>";
    } else {
        echo "Gagal menambahkan user: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Penjualan</h2>
    <a href="index.php">Dashboard</a>
    <a href="pelanggan.php">Data Pelanggan</a>
    <a href="barang.php">Data Barang</a>
    <a href="supplier.php">Data Supplier</a>
    <a href="transaksi.php">Data Transaksi</a>
    <a href="pembayaran.php">Data Pembayaran</a>
    <a href="user.php">Data User</a>
</div>

<div class="main">

    <h1>Tambah User</h1>
    <p>Masukkan informasi pengguna baru.</p>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" required>

        <label>Alamat</label>
        <textarea name="alamat" required></textarea>

        <label>Nomor HP</label>
        <input type="text" name="hp" required>

        <label>Level</label>
        <select name="level" required>
            <option value="">-- Pilih Level --</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="kasir">Kasir</option>
        </select>

        <button type="submit" name="simpan">Simpan</button>
        <a href="user.php">Kembali</a>
    </form>

</div>

</body>
</html>
