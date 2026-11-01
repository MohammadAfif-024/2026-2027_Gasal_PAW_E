<?php
    include "koneksi.php";

    // Mengambil data pelanggan
    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id ASC");

    // Pencarian pelanggan
    if (isset($_GET['cari'])) {
        $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

        $query = mysqli_query($koneksi, "SELECT * FROM pelanggan
            WHERE nama LIKE '%$cari%'
            OR telp LIKE '%$cari%'
            ORDER BY id ASC");
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
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

    <h1>Data Pelanggan</h1>
    <p>Daftar seluruh pelanggan yang tersimpan di database.</p>

    <div class="aksi">
        <form method="GET" action="pelanggan.php">
            <input type="text" name="cari" placeholder="Cari nama atau telepon...">
            <button type="submit">Cari</button>
            <a href="pelanggan.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_pelanggan.php" class="tombol-tambah">
            + Tambah Pelanggan
        </a>
    </div>

    <div class="tabel-container">
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
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
                <td><?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
                <td><?php echo htmlspecialchars($data['telp']); ?></td>
                <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                <td>
                    <a href="edit_pelanggan.php?id=<?php echo $data['id']; ?>" class="tombol-edit">Edit</a>

                    <a href="hapus_pelanggan.php?id=<?php echo $data['id']; ?>"
                       class="tombol-hapus"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
