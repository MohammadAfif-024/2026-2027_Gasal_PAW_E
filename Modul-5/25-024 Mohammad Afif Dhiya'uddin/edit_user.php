
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data user tidak ditemukan");
}

if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    $update = mysqli_query($koneksi, "UPDATE user SET
        username='$username',
        password='$password',
        nama='$nama',
        alamat='$alamat',
        hp='$hp',
        level='$level'
        WHERE id_user=$id");

    if ($update) {
        echo "<script>alert('User berhasil diubah'); window.location='user.php';</script>";
    } else {
        echo "Gagal mengubah user: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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

    <h1>Edit User</h1>
    <p>Ubah informasi pengguna.</p>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username"
               value="<?php echo htmlspecialchars($data['username']); ?>" required>

        <label>Password</label>
        <input type="password" name="password"
               value="<?php echo htmlspecialchars($data['password']); ?>" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama"
               value="<?php echo htmlspecialchars($data['nama']); ?>" required>

        <label>Alamat</label>
        <textarea name="alamat" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>

        <label>Nomor HP</label>
        <input type="text" name="hp"
               value="<?php echo htmlspecialchars($data['hp']); ?>" required>

        <label>Level</label>
        <select name="level" required>
            <option value="admin" <?php if ($data['level'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($data['level'] == 'user') echo 'selected'; ?>>User</option>
        </select>

        <button type="submit" name="update">Update</button>
        <a href="user.php">Kembali</a>
    </form>

</div>

</body>
</html>
