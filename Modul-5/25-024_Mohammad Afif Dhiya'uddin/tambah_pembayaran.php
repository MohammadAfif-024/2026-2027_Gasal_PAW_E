
<?php
include "koneksi.php";

$pesan = "";

if (isset($_POST['simpan'])) {

    $transaksi_id = (int) $_POST['transaksi_id'];
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['waktu_bayar']);
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode']);

    // Periksa apakah transaksi tersedia dan belum memiliki pembayaran
    $cek = mysqli_query($koneksi, "
        SELECT transaksi.*
        FROM transaksi
        LEFT JOIN pembayaran
            ON transaksi.id = pembayaran.transaksi_id
        WHERE transaksi.id = $transaksi_id
        AND pembayaran.id IS NULL
    ");

    $data_transaksi = mysqli_fetch_assoc($cek);

    if (!$data_transaksi) {

        $pesan = "Transaksi tidak ditemukan atau sudah memiliki pembayaran.";

    } else {

        $total = $data_transaksi['total'];

        $simpan = mysqli_query($koneksi, "
            INSERT INTO pembayaran
            (waktu_bayar, total, metode, transaksi_id)
            VALUES
            ('$tanggal', '$total', '$metode', '$transaksi_id')
        ");

        if ($simpan) {

            echo "<script>
                alert('Pembayaran berhasil ditambahkan');
                window.location='pembayaran.php';
            </script>";

            exit;

        } else {

            $pesan = "Gagal menambahkan pembayaran: " . mysqli_error($koneksi);

        }
    }
}

// Ambil transaksi yang belum memiliki pembayaran
$transaksi = mysqli_query($koneksi, "
    SELECT
        transaksi.id,
        transaksi.total,
        pelanggan.nama AS nama_pelanggan
    FROM transaksi
    JOIN pelanggan
        ON transaksi.pelanggan_id = pelanggan.id
    LEFT JOIN pembayaran
        ON transaksi.id = pembayaran.transaksi_id
    WHERE pembayaran.id IS NULL
    ORDER BY transaksi.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pembayaran</title>
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

    <h1>Tambah Pembayaran</h1>
    <p>Masukkan informasi pembayaran transaksi.</p>

    <?php if ($pesan != "") { ?>
        <p style="color:red;">
            <?= htmlspecialchars($pesan); ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label>Tanggal Pembayaran</label>
        <input
            type="date"
            name="waktu_bayar"
            value="<?= date('Y-m-d'); ?>"
            required
        >

        <label>Transaksi</label>
        <select name="transaksi_id" required>
            <option value="">-- Pilih Transaksi --</option>

            <?php if (mysqli_num_rows($transaksi) > 0) { ?>

                <?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>

                    <option value="<?= $t['id']; ?>">
                        Transaksi #<?= $t['id']; ?>
                        - <?= htmlspecialchars($t['nama_pelanggan']); ?>
                        - Rp <?= number_format($t['total'], 0, ',', '.'); ?>
                    </option>

                <?php } ?>

            <?php } ?>

        </select>

        <?php if (mysqli_num_rows($transaksi) == 0) { ?>
            <p style="color:green;">
                Semua transaksi sudah memiliki pembayaran.
            </p>
        <?php } ?>

        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="">-- Pilih Metode --</option>
            <option value="Tunai">Tunai</option>
            <option value="transfer">Transfer</option>
            <option value="EDC">EDC</option>
        </select>

        <button type="submit" name="simpan">
            Simpan Pembayaran
        </button>

        <a href="pembayaran.php">Kembali</a>

    </form>

</div>

</body>
</html>
