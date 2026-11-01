
<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID transaksi tidak valid");
}

// Memeriksa pembayaran
$cek_pembayaran = mysqli_query($koneksi, "
    SELECT * FROM pembayaran
    WHERE transaksi_id = $id
");

if (mysqli_num_rows($cek_pembayaran) > 0) {
    echo "<script>
        alert('Transaksi tidak dapat dihapus karena sudah memiliki pembayaran!');
        window.location='transaksi.php';
    </script>";
    exit;
}

// Mengambil detail barang transaksi
$detail = mysqli_query($koneksi, "
    SELECT * FROM transaksi_detail
    WHERE transaksi_id = $id
");

mysqli_begin_transaction($koneksi);

$berhasil = true;

while ($data = mysqli_fetch_assoc($detail)) {
    $barang_id = (int) $data['barang_id'];
    $jumlah = (int) $data['qty'];

    $update_stok = mysqli_query($koneksi, "
        UPDATE barang
        SET stok = stok + $jumlah
        WHERE id = $barang_id
    ");

    if (!$update_stok) {
        $berhasil = false;
        break;
    }
}

// Menghapus detail transaksi
if ($berhasil) {
    $hapus_detail = mysqli_query($koneksi, "
        DELETE FROM transaksi_detail
        WHERE transaksi_id = $id
    ");

    if (!$hapus_detail) {
        $berhasil = false;
    }
}

if ($berhasil) {
    $hapus_transaksi = mysqli_query($koneksi, "
        DELETE FROM transaksi
        WHERE id = $id
    ");

    if (!$hapus_transaksi) {
        $berhasil = false;
    }
}

if ($berhasil) {
    mysqli_commit($koneksi);

    echo "<script>
        alert('Transaksi berhasil dihapus dan stok barang dikembalikan');
        window.location='transaksi.php';
    </script>";
} else {
    mysqli_rollback($koneksi);
    echo "Gagal menghapus transaksi: " . mysqli_error($koneksi);
}
?>
