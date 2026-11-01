
<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier_id'];

    $query = mysqli_query($koneksi, "INSERT INTO barang
        (kode_barang, nama_barang, harga, stok, supplier_id)
        VALUES ('$kode', '$nama', '$harga', '$stok', '$supplier')");

    if ($query) {
        echo "<script>
            alert('Data barang berhasil ditambahkan!');
            window.location='barang.php';
        </script>";
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

$supplier = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0;">

    <h1>Tambah Barang</h1>
    <p>Masukkan informasi barang baru.</p>

    <div class="tabel-container">

        <form method="POST">

            <label>Kode Barang</label><br>
            <input type="text" name="kode_barang" required
                style="width:100%; margin:10px 0 20px;"><br>

            <label>Nama Barang</label><br>
            <input type="text" name="nama_barang" required
                style="width:100%; margin:10px 0 20px;"><br>

            <label>Harga</label><br>
            <input type="number" name="harga" min="0" required
                style="width:100%; margin:10px 0 20px;"><br>

            <label>Stok</label><br>
            <input type="number" name="stok" min="0" required
                style="width:100%; margin:10px 0 20px;"><br>

            <label>Supplier</label><br>

            <select name="supplier_id" required
                style="width:100%; padding:10px; margin:10px 0 20px;">

                <option value="">-- Pilih Supplier --</option>

                <?php while ($s = mysqli_fetch_assoc($supplier)) { ?>
                    <option value="<?php echo $s['id']; ?>">
                        <?php echo htmlspecialchars($s['nama']); ?>
                    </option>
                <?php } ?>

            </select><br>

            <button type="submit" name="simpan">Simpan</button>
            <a href="barang.php" class="tombol-reset">Kembali</a>

        </form>

    </div>
</div>

</body>
</html>
