
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data barang tidak ditemukan.");
}

if (isset($_POST['update'])) {

    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier_id'];

    $update = mysqli_query($koneksi, "UPDATE barang SET
        kode_barang='$kode',
        nama_barang='$nama',
        harga='$harga',
        stok='$stok',
        supplier_id='$supplier'
        WHERE id=$id");

    if ($update) {
        echo "<script>
            alert('Data barang berhasil diperbarui!');
            window.location='barang.php';
        </script>";
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}

$supplier = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0;">

    <h1>Edit Barang</h1>
    <p>Ubah informasi barang.</p>

    <div class="tabel-container">

        <form method="POST">

            <label>Kode Barang</label><br>
            <input type="text" name="kode_barang"
                value="<?php echo htmlspecialchars($data['kode_barang']); ?>"
                required style="width:100%; margin:10px 0 20px;"><br>

            <label>Nama Barang</label><br>
            <input type="text" name="nama_barang"
                value="<?php echo htmlspecialchars($data['nama_barang']); ?>"
                required style="width:100%; margin:10px 0 20px;"><br>

            <label>Harga</label><br>
            <input type="number" name="harga"
                value="<?php echo $data['harga']; ?>"
                min="0" required style="width:100%; margin:10px 0 20px;"><br>

            <label>Stok</label><br>
            <input type="number" name="stok"
                value="<?php echo $data['stok']; ?>"
                min="0" required style="width:100%; margin:10px 0 20px;"><br>

            <label>Supplier</label><br>

            <select name="supplier_id" required
                style="width:100%; padding:10px; margin:10px 0 20px;">

                <?php while ($s = mysqli_fetch_assoc($supplier)) { ?>

                    <option value="<?php echo $s['id']; ?>"
                        <?php if ($data['supplier_id'] == $s['id']) echo 'selected'; ?>>

                        <?php echo htmlspecialchars($s['nama']); ?>

                    </option>

                <?php } ?>

            </select><br>

            <button type="submit" name="update">Update</button>
            <a href="barang.php" class="tombol-reset">Kembali</a>

        </form>

    </div>
</div>

</body>
</html>
