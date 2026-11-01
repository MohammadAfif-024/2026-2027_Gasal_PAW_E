<?php
    include "koneksi.php";

    $id = (int) $_GET['id'];

    $cek = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE pelanggan_id = $id");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Pelanggan tidak dapat dihapus karena masih memiliki riwayat transaksi!');
            window.location='pelanggan.php';
        </script>";
    } else {

        $query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $id");

        if ($query) {
            echo "<script>
                alert('Data pelanggan berhasil dihapus!');
                window.location='pelanggan.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data pelanggan!');
                window.location='pelanggan.php';
            </script>";
        }
    }
?>
