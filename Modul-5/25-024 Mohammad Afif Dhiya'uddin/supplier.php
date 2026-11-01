
<?php
include "koneksi.php";

// Mengambil data supplier
$query = mysqli_query($koneksi, "
    SELECT * FROM supplier
    ORDER BY id ASC
");

// Pencarian supplier
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $query = mysqli_query($koneksi, "
        SELECT * FROM supplier
        WHERE nama LIKE '%$cari%'
        ORDER BY id ASC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Supplier</title>
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

    <h1>Data Supplier</h1>
    <p>Daftar seluruh supplier yang tersedia.</p>

    <div class="aksi">

        <form method="GET" action="supplier.php">
            <input type="text" name="cari" placeholder="Cari nama supplier...">
            <button type="submit">Cari</button>
            <a href="supplier.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_supplier.php" class="tombol-tambah">
            + Tambah Supplier
        </a>

    </div>

    <div class="tabel-container">

        <table>
            <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                <td><?php echo htmlspecialchars($data['telp']); ?></td>
                <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                <td>
                    <a href="edit_supplier.php?id=<?php echo $data['id']; ?>" class="tombol-edit">Edit</a>

                    <a href="hapus_supplier.php?id=<?php echo $data['id']; ?>"
                       class="tombol-hapus"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
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
