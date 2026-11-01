
<?php
include "koneksi.php";

$cari = "";

$sql = "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $sql .= "
        WHERE pelanggan.nama LIKE '%$cari%'
        OR transaksi.keterangan LIKE '%$cari%'
    ";
}

$sql .= " ORDER BY transaksi.id DESC";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
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

    <h1>Data Transaksi</h1>
    <p>Daftar seluruh transaksi penjualan.</p>

    <div class="aksi">
        <form method="GET" action="transaksi.php">
            <input type="text" name="cari"
                   placeholder="Cari nama pelanggan atau keterangan..."
                   value="<?php echo htmlspecialchars($cari); ?>">
            <button type="submit">Cari</button>
            <a href="transaksi.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_transaksi.php" class="tombol-tambah">
            + Tambah Transaksi
        </a>
    </div>

    <div class="tabel-container">
        <table>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Pelanggan</th>
                <th>Keterangan</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['waktu_transaksi']); ?></td>
                <td><?php echo htmlspecialchars($data['nama_pelanggan']); ?></td>
                <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
                <td>Rp <?php echo number_format($data['total'], 0, ',', '.'); ?></td>
                <td>
                    <a href="edit_transaksi.php?id=<?php echo $data['id']; ?>"
                       class="tombol-edit">Edit</a>

                    <a href="hapus_transaksi.php?id=<?php echo $data['id']; ?>"
                       class="tombol-hapus"
                       onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
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
