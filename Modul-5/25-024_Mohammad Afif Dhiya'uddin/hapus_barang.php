
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

// Memeriksa apakah barang digunakan dalam transaksi
$cek = mysqli_query($koneksi, "SELECT * FROM transaksi_detail WHERE barang_id = $id");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
        alert('Barang tidak dapat dihapus karena masih memiliki riwayat transaksi!');
        window.location='barang.php';
    </script>";

} else {

    $query = mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id");

    if ($query) {
        echo "<script>
            alert('Data barang berhasil dihapus!');
            window.location='barang.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data barang!');
            window.location='barang.php';
        </script>";
    }
}
?>
