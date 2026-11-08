<?php
// SOAL 3: Hapus barang dengan pengecekan data terkait
require_once "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// LANGKAH 1: cek apakah barang ini sudah dipakai di tabel transaksi_detail
$sql_cek = "SELECT COUNT(*) AS jumlah
            FROM transaksi_detail
            WHERE barang_id = $id";
$query_cek = mysqli_query($koneksi, $sql_cek) or die("Query gagal: " . mysqli_error($koneksi));
$data_cek  = mysqli_fetch_assoc($query_cek);

// LANGKAH 2: kalau sudah dipakai -> JANGAN dihapus, tampilkan notifikasi
if ($data_cek['jumlah'] > 0) {
    $pesan = "Barang tidak dapat dihapus karena masih digunakan pada transaksi.";
    header("Location: barang.php?tipe=gagal&pesan=" . urlencode($pesan));
    exit;
}

// LANGKAH 3: kalau belum dipakai -> hapus seperti biasa
mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id")
    or die("Query gagal: " . mysqli_error($koneksi));

header("Location: barang.php?tipe=sukses&pesan=" . urlencode("Barang berhasil dihapus."));
exit;
