
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data supplier tidak ditemukan");
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update = mysqli_query($koneksi, "UPDATE supplier SET
        nama='$nama',
        telp='$telp',
        alamat='$alamat'
        WHERE id=$id");

    if ($update) {
        echo "<script>alert('Supplier berhasil diubah'); window.location='supplier.php';</script>";
    } else {
        echo "Gagal mengubah supplier: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Edit Supplier</h2>

    <form method="POST">
        <label>Nama Supplier</label>
        <input type="text" name="nama"
               value="<?php echo htmlspecialchars($data['nama']); ?>" required>

        <label>Telepon</label>
        <input type="text" name="telp"
               value="<?php echo htmlspecialchars($data['telp']); ?>" required>

        <label>Alamat</label>
        <textarea name="alamat" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>

        <button type="submit" name="update">Update</button>
        <a href="supplier.php">Kembali</a>
    </form>
</div>

</body>
</html>
