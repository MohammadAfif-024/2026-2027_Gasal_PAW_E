<?php
include "koneksi.php";

// Mengambil data barang dan nama supplier
$query = mysqli_query($koneksi, "
    SELECT barang.*, supplier.nama AS nama_supplier
    FROM barang
    LEFT JOIN supplier ON barang.supplier_id = supplier.id
    ORDER BY barang.id ASC
");

// Pencarian barang
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $query = mysqli_query($koneksi, "
        SELECT barang.*, supplier.nama AS nama_supplier
        FROM barang
        LEFT JOIN supplier ON barang.supplier_id = supplier.id
        WHERE barang.nama_barang LIKE '%$cari%'
        OR barang.kode_barang LIKE '%$cari%'
        ORDER BY barang.id ASC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>  
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

    <h1>Data Barang</h1>
    <p>Daftar seluruh barang yang tersedia.</p>

    <div class="aksi">

        <form method="GET" action="barang.php">
            <input type="text" name="cari" placeholder="Cari kode atau nama barang...">
            <button type="submit">Cari</button>
            <a href="barang.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_barang.php" class="tombol-tambah">
            + Tambah Barang
        </a>

    </div>

    <div class="tabel-container">

        <table>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Supplier</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['kode_barang']); ?></td>
                <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
                <td>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $data['stok']; ?></td>
                <td><?php echo htmlspecialchars($data['nama_supplier']); ?></td>
                <td>
                    <a href="edit_barang.php?id=<?php echo $data['id']; ?>" class="tombol-edit">Edit</a>

                    <a href="hapus_barang.php?id=<?php echo $data['id']; ?>"
                       class="tombol-hapus"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
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