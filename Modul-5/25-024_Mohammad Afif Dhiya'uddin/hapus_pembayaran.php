
<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID pembayaran tidak valid");
}

$hapus = mysqli_query($koneksi, "
    DELETE FROM pembayaran
    WHERE id = $id
");

if ($hapus && mysqli_affected_rows($koneksi) > 0) {
    echo "<script>
        alert('Pembayaran berhasil dihapus');
        window.location='pembayaran.php';
    </script>";
} elseif ($hapus) {
    echo "<script>
        alert('Data pembayaran tidak ditemukan');
        window.location='pembayaran.php';
    </script>";
} else {
    echo "Gagal menghapus pembayaran: " . mysqli_error($koneksi);
}
?>
