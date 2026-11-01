<?php
    include "koneksi.php";

    $id = (int) $_GET['id'];

    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        die("Data pelanggan tidak ditemukan.");
    }

    if (isset($_POST['update'])) {
        $nama = $_POST['nama'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $telp = $_POST['telp'];
        $alamat = $_POST['alamat'];

        $update = mysqli_query($koneksi, "UPDATE pelanggan SET
            nama='$nama',
            jenis_kelamin='$jenis_kelamin',
            telp='$telp',
            alamat='$alamat'
            WHERE id=$id");

        if ($update) {
            echo "<script>
                alert('Data pelanggan berhasil diperbarui!');
                window.location='pelanggan.php';
            </script>";
        } else {
            echo "Gagal mengubah data: " . mysqli_error($koneksi);
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main" style="margin-left:0;">
    <h1>Edit Pelanggan</h1>
    <p>Ubah informasi pelanggan.</p>

    <div class="tabel-container">
        <form method="POST">

            <label>Nama Pelanggan</label><br>
            <input type="text" name="nama"
                value="<?php echo htmlspecialchars($data['nama']); ?>"
                required style="width:100%; margin:10px 0 20px;"><br>

            <label>Jenis Kelamin</label><br>
            <select name="jenis_kelamin" required style="width:100%; padding:10px; margin:10px 0 20px;">
                <option value="L" <?php if ($data['jenis_kelamin'] == 'L') echo 'selected'; ?>>
                    Laki-laki
                </option>
                <option value="P" <?php if ($data['jenis_kelamin'] == 'P') echo 'selected'; ?>>
                    Perempuan
                </option>
            </select><br>

            <label>Nomor Telepon</label><br>
            <input type="text" name="telp"
                value="<?php echo htmlspecialchars($data['telp']); ?>"
                required style="width:100%; margin:10px 0 20px;"><br>

            <label>Alamat</label><br>
            <textarea name="alamat" required
                style="width:100%; padding:10px; margin:10px 0 20px;"><?php
                echo htmlspecialchars($data['alamat']);
            ?></textarea><br>

            <button type="submit" name="update">Update</button>
            <a href="pelanggan.php" class="tombol-reset">Kembali</a>

        </form>
    </div>
</div>

</body>
</html>
