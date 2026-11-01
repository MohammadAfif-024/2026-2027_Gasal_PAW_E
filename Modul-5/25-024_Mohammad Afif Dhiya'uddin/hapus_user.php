
<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = $id");

if ($hapus) {
    echo "<script>
        alert('User berhasil dihapus');
        window.location='user.php';
    </script>";
} else {
    echo "Gagal menghapus user: " . mysqli_error($koneksi);
}
?>
