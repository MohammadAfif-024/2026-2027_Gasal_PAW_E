
<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "
    SELECT transaksi.*, transaksi_detail.barang_id,
           transaksi_detail.qty
    FROM transaksi
    JOIN transaksi_detail
    ON transaksi.id = transaksi_detail.transaksi_id
    WHERE transaksi.id = $id
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi tidak ditemukan");
}

if (isset($_POST['update'])) {
    $pelanggan_id = (int) $_POST['pelanggan_id'];
    $barang_id = (int) $_POST['barang_id'];
    $jumlah = (int) $_POST['jumlah'];
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['waktu_transaksi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    if ($pelanggan_id <= 0 || $barang_id <= 0 || $jumlah <= 0 || $tanggal == "") {
        echo "<script>alert('Data transaksi belum lengkap atau tidak valid');</script>";
    } else {
        mysqli_begin_transaction($koneksi);

        $barang_lama_id = (int) $data['barang_id'];
        $jumlah_lama = (int) $data['qty'];

        // Mengembalikan stok barang lama
        $kembali = mysqli_query($koneksi, "
            UPDATE barang
            SET stok = stok + $jumlah_lama
            WHERE id = $barang_lama_id
        ");

        // Mengambil data barang yang dipilih
        $cek_barang = mysqli_query($koneksi, "
            SELECT * FROM barang
            WHERE id = $barang_id
            FOR UPDATE
        ");

        $barang_baru = mysqli_fetch_assoc($cek_barang);

        $cek_pelanggan = mysqli_query($koneksi, "
            SELECT id FROM pelanggan WHERE id = $pelanggan_id
        ");

        if (!$kembali || !$barang_baru || mysqli_num_rows($cek_pelanggan) == 0) {
            mysqli_rollback($koneksi);
            echo "<script>alert('Data barang atau pelanggan tidak valid');</script>";
        } elseif ($jumlah > $barang_baru['stok']) {
            mysqli_rollback($koneksi);
            echo "<script>alert('Stok barang tidak mencukupi');</script>";
        } else {
            $harga = $barang_baru['harga'];
            $total = $harga * $jumlah;

            $update_transaksi = mysqli_query($koneksi, "
                UPDATE transaksi SET
                waktu_transaksi = '$tanggal',
                keterangan = '$keterangan',
                total = $total,
                pelanggan_id = $pelanggan_id
                WHERE id = $id
            ");

            $update_detail = mysqli_query($koneksi, "
                UPDATE transaksi_detail SET
                barang_id = $barang_id,
                harga = $harga,
                qty = $jumlah
                WHERE transaksi_id = $id
            ");

            $update_stok = mysqli_query($koneksi, "
                UPDATE barang
                SET stok = stok - $jumlah
                WHERE id = $barang_id
            ");

            if ($update_transaksi && $update_detail && $update_stok) {
                mysqli_commit($koneksi);

                echo "<script>
                    alert('Transaksi berhasil diubah');
                    window.location='transaksi.php';
                </script>";
            } else {
                mysqli_rollback($koneksi);
                echo "Gagal mengubah transaksi: " . mysqli_error($koneksi);
            }
        }
    }
}

$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama ASC");
$barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
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
    <h1>Edit Transaksi</h1>
    <p>Ubah informasi transaksi penjualan.</p>

    <form method="POST">

        <label>Tanggal Transaksi</label>
        <input type="date" name="waktu_transaksi"
               value="<?php echo date('Y-m-d', strtotime($data['waktu_transaksi'])); ?>"
               required>

        <label>Pelanggan</label>
        <select name="pelanggan_id" required>
            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <option value="<?php echo $p['id']; ?>"
                    <?php if ($p['id'] == $data['pelanggan_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($p['nama']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Barang</label>
        <select name="barang_id" required>
            <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
                <option value="<?php echo $b['id']; ?>"
                    <?php if ($b['id'] == $data['barang_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($b['nama_barang']); ?>
                    - Stok: <?php echo $b['stok']; ?>
                    - Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?>
                </option>
            <?php } ?>
        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1"
               value="<?php echo $data['qty']; ?>" required>

        <label>Keterangan</label>
        <textarea name="keterangan"><?php echo htmlspecialchars($data['keterangan']); ?></textarea>

        <button type="submit" name="update">Update Transaksi</button>
        <a href="transaksi.php">Kembali</a>

    </form>
</div>

</body>
</html>
