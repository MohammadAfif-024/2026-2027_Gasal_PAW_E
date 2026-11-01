
<?php
include "koneksi.php";

// Mengambil data user
$query = mysqli_query($koneksi, "
    SELECT * FROM user
    ORDER BY id_user ASC
");

// Pencarian user
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $query = mysqli_query($koneksi, "
        SELECT * FROM user
        WHERE username LIKE '%$cari%'
        OR nama LIKE '%$cari%'
        ORDER BY id_user ASC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
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

    <h1>Data User</h1>
    <p>Daftar seluruh pengguna sistem.</p>

    <div class="aksi">

        <form method="GET" action="user.php">
            <input type="text" name="cari" placeholder="Cari username atau nama...">
            <button type="submit">Cari</button>
            <a href="user.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_user.php" class="tombol-tambah">
            + Tambah User
        </a>

    </div>

    <div class="tabel-container">

        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>HP</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['username']); ?></td>
                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                <td><?php echo htmlspecialchars($data['hp']); ?></td>
                <td><?php echo htmlspecialchars($data['level']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $data['id_user']; ?>" class="tombol-edit">Edit</a>

                    <a href="hapus_user.php?id=<?php echo $data['id_user']; ?>"
                       class="tombol-hapus"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                       Hapus
                    </a>
                </td>
            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>
