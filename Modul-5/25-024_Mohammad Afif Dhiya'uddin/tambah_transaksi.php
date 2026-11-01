
<?php
include "koneksi.php";

// Menyimpan transaksi
if (isset($_POST['simpan'])) {
    $pelanggan_id = (int) $_POST['pelanggan_id'];
    $barang_id = (int) $_POST['barang_id'];
    $jumlah = (int) $_POST['jumlah'];
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['waktu_transaksi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Mengambil harga dan stok barang
    $cek_barang = mysqli_query($koneksi, "
        SELECT * FROM barang WHERE id = $barang_id
    ");

    $barang = mysqli_fetch_assoc($cek_barang);

    if (!$barang) {
        echo "<script>alert('Barang tidak ditemukan');</script>";
    } elseif ($jumlah <= 0) {
        echo "<script>alert('Jumlah barang harus lebih dari 0');</script>";
    } elseif ($jumlah > $barang['stok']) {
        echo "<script>alert('Stok barang tidak mencukupi');</script>";
    } else {
        $harga = $barang['harga'];
        $total = $harga * $jumlah;

        // Menyimpan transaksi
        mysqli_begin_transaction($koneksi);

        $simpan_transaksi = mysqli_query($koneksi, "
            INSERT INTO transaksi
            (waktu_transaksi, keterangan, total, pelanggan_id)
            VALUES ('$tanggal', '$keterangan', '$total', '$pelanggan_id')
        ");

        if ($simpan_transaksi) {
            $id_transaksi = mysqli_insert_id($koneksi);

            // Menyimpan detail transaksi
            $simpan_detail = mysqli_query($koneksi, "
                INSERT INTO transaksi_detail
                (transaksi_id, barang_id, harga, qty)
                VALUES ('$id_transaksi', '$barang_id', '$harga', '$jumlah')
            ");

            // Mengurangi stok barang
            $update_stok = mysqli_query($koneksi, "
                UPDATE barang
                SET stok = stok - $jumlah
                WHERE id = $barang_id
            ");

            if ($simpan_detail && $update_stok) {
                mysqli_commit($koneksi);

                echo "<script>
                    alert('Transaksi berhasil ditambahkan');
                    window.location='transaksi.php';
                </script>";
            } else {
                mysqli_rollback($koneksi);
                echo "Gagal menyimpan detail transaksi: " . mysqli_error($koneksi);
            }

        } else {
            mysqli_rollback($koneksi);
            echo "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
        }
    }
}

// Mengambil data pelanggan
$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama ASC");

// Mengambil data barang
$barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
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

    <h1>Tambah Transaksi</h1>
    <p>Masukkan informasi transaksi penjualan.</p>

    <form method="POST">

        <label>Tanggal Transaksi</label>
        <input type="date" name="waktu_transaksi"
               value="<?php echo date('Y-m-d'); ?>" required>

        <label>Pelanggan</label>
        <select name="pelanggan_id" required>
            <option value="">-- Pilih Pelanggan --</option>

            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <option value="<?php echo $p['id']; ?>">
                    <?php echo htmlspecialchars($p['nama']); ?>
                </option>
            <?php } ?>

        </select>

        <label>Barang</label>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>

            <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
                <option value="<?php echo $b['id']; ?>">
                    <?php echo htmlspecialchars($b['nama_barang']); ?>
                    - Stok: <?php echo $b['stok']; ?>
                    - Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?>
                </option>
            <?php } ?>

        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" required>

        <label>Keterangan</label>
        <textarea name="keterangan" placeholder="Masukkan keterangan transaksi"></textarea>

        <button type="submit" name="simpan">Simpan Transaksi</button>
        <a href="transaksi.php">Kembali</a>

    </form>

</div>

</body>
</html>
