
<?php
include "koneksi.php";

// Pencarian pembayaran
$cari = "";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
}

$query = mysqli_query($koneksi, "
    SELECT 
        pembayaran.*,
        transaksi.id AS nomor_transaksi,
        pelanggan.nama AS nama_pelanggan
    FROM pembayaran
    LEFT JOIN transaksi 
        ON pembayaran.transaksi_id = transaksi.id
    LEFT JOIN pelanggan 
        ON transaksi.pelanggan_id = pelanggan.id
    WHERE 
        pelanggan.nama LIKE '%$cari%'
        OR transaksi.id LIKE '%$cari%'
        OR pembayaran.metode LIKE '%$cari%'
    ORDER BY pembayaran.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pembayaran</title>
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

    <h1>Data Pembayaran</h1>

    <div class="aksi">
        <form method="GET">
            <input 
                type="text" 
                name="cari" 
                placeholder="Cari nama pelanggan, transaksi, atau metode..."
                value="<?= htmlspecialchars($cari); ?>"
            >
            <button type="submit">Cari</button>
            <a href="pembayaran.php" class="tombol-reset">Reset</a>
        </form>

        <a href="tambah_pembayaran.php" class="tombol-tambah">
            + Tambah Pembayaran
        </a>
    </div>

    <div class="tabel-container">
        <table>
            <tr>
                <th>No</th>
                <th>Tanggal Pembayaran</th>
                <th>Nama Pelanggan</th>
                <th>ID Transaksi</th>
                <th>Total Pembayaran</th>
                <th>Metode</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>
                <td><?= $no++; ?></td>

                <td>
                    <?= htmlspecialchars($data['waktu_bayar']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_pelanggan'] ?? '-'); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nomor_transaksi'] ?? '-'); ?>
                </td>

                <td>
                    Rp <?= number_format($data['total'], 0, ',', '.'); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['metode']); ?>
                </td>

                <td class="aksi">
                    <a 
                        href="edit_pembayaran.php?id=<?= $data['id']; ?>" 
                        class="tombol-edit"
                    >
                        Edit
                    </a>

                    <a 
                        href="hapus_pembayaran.php?id=<?= $data['id']; ?>" 
                        class="tombol-hapus"
                        onclick="return confirm('Yakin ingin menghapus pembayaran ini?')"
                    >
                        Hapus
                    </a>
                </td>
            </tr>

            <?php
                }
            } else {
            ?>

            <tr>
                <td colspan="7" style="text-align:center;">
                    Data pembayaran tidak ditemukan.
                </td>
            </tr>

            <?php
            }
            ?>

        </table>
    </div>

</div>

</body>
</html>
