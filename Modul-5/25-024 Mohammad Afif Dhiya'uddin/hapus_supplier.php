
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$cek = mysqli_query($koneksi, "SELECT * FROM barang WHERE supplier_id = $id");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('Supplier tidak dapat dihapus karena masih digunakan oleh barang!');
        window.location='supplier.php';
    </script>";
} else {
    $hapus = mysqli_query($koneksi, "DELETE FROM supplier WHERE id = $id");

    if ($hapus) {
        echo "<script>
            alert('Supplier berhasil dihapus');
            window.location='supplier.php';
        </script>";
    } else {
        echo "Gagal menghapus supplier: " . mysqli_error($koneksi);
    }
}
?>
