
<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "
    SELECT * FROM pembayaran WHERE id = $id
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pembayaran tidak ditemukan");
}

if (isset($_POST['update'])) {
    $transaksi_id = (int) $_POST['transaksi_id'];
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['waktu_bayar']);
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode']);

    $cek = mysqli_query($koneksi, "
        SELECT * FROM transaksi WHERE id = $transaksi_id
    ");

    $transaksi = mysqli_fetch_assoc($cek);

    if (!$transaksi) {
        echo "<script>alert('Transaksi tidak ditemukan');</script>";
    } else {
        $total = $transaksi['total'];

        $update = mysqli_query($koneksi, "
            UPDATE pembayaran SET
            waktu_bayar = '$tanggal',
            total = '$total',
            metode = '$metode',
            transaksi_id = '$transaksi_id'
            WHERE id = $id
        ");

        if ($update) {
            echo "<script>
                alert('Pembayaran berhasil diubah');
                window.location='pembayaran.php';
            </script>";
        } else {
            echo "Gagal mengubah pembayaran: " . mysqli_error($koneksi);
        }
    }
}

$transaksi = mysqli_query($koneksi, "
    SELECT * FROM transaksi ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran</title>
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

    <h1>Edit Pembayaran</h1>
    <p>Ubah informasi pembayaran.</p>

    <form method="POST">

        <label>Tanggal Pembayaran</label>
        <input type="date" name="waktu_bayar"
               value="<?php echo date('Y-m-d', strtotime($data['waktu_bayar'])); ?>"
               required>

        <label>Transaksi</label>
        <select name="transaksi_id" required>
            <?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>
                <option value="<?php echo $t['id']; ?>"
                    <?php if ($t['id'] == $data['transaksi_id']) echo 'selected'; ?>>
                    Transaksi #<?php echo $t['id']; ?>
                    - Rp <?php echo number_format($t['total'], 0, ',', '.'); ?>
                </option>
            <?php } ?>
        </select>

        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="Tunai" <?php if ($data['metode'] == 'Tunai') echo 'selected'; ?>>Tunai</option>
            <option value="transfer" <?php if ($data['metode'] == 'transfer') echo 'selected'; ?>>Transfer</option>
            <option value="EDC" <?php if ($data['metode'] == 'EDC') echo 'selected'; ?>>EDC</option>
        </select>

        <button type="submit" name="update">Update Pembayaran</button>
        <a href="pembayaran.php">Kembali</a>

    </form>

</div>

</body>
</html>
