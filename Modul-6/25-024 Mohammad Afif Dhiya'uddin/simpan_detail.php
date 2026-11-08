<?php
// SOAL 4: Simpan detail transaksi + otomatis update total di tabel transaksi
require_once "koneksi.php";

// Halaman ini hanya boleh dibuka lewat form (method POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: transaksi.php");
    exit;
}

$transaksi_id = isset($_POST['transaksi_id']) ? (int) $_POST['transaksi_id'] : 0;
$barang_id    = isset($_POST['barang_id'])    ? (int) $_POST['barang_id']    : 0;
$qty          = isset($_POST['qty'])          ? (int) $_POST['qty']          : 0;

// Validasi sederhana
if ($transaksi_id < 1 || $barang_id < 1 || $qty < 1) {
    header("Location: detail.php?id=$transaksi_id&tipe=gagal&pesan=" . urlencode("Data tidak valid."));
    exit;
}

// Pengecekan di sisi server: barang yang sama tidak boleh diinput 2x
$sql_cek = "SELECT * FROM transaksi_detail
            WHERE transaksi_id = $transaksi_id AND barang_id = $barang_id";
$query_cek = mysqli_query($koneksi, $sql_cek) or die("Query gagal: " . mysqli_error($koneksi));

if (mysqli_num_rows($query_cek) > 0) {
    header("Location: detail.php?id=$transaksi_id&tipe=gagal&pesan=" . urlencode("Barang sudah ada di transaksi ini."));
    exit;
}

// LANGKAH 1: ambil harga barang dari tabel barang
$query_barang = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id = $barang_id")
    or die("Query gagal: " . mysqli_error($koneksi));
$barang = mysqli_fetch_assoc($query_barang);

if (!$barang) {
    header("Location: detail.php?id=$transaksi_id&tipe=gagal&pesan=" . urlencode("Barang tidak ditemukan."));
    exit;
}
$harga = (int) $barang['harga'];

// LANGKAH 2: simpan ke tabel transaksi_detail
$sql_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
               VALUES ($transaksi_id, $barang_id, $harga, $qty)";
mysqli_query($koneksi, $sql_insert) or die("Query gagal: " . mysqli_error($koneksi));

// LANGKAH 3: hitung ulang total = jumlah (harga x qty) dari SEMUA detail
//            milik transaksi ini, lalu simpan ke kolom total di tabel transaksi
$sql_update = "UPDATE transaksi
               SET total = (SELECT SUM(harga * qty)
                            FROM transaksi_detail
                            WHERE transaksi_id = $transaksi_id)
               WHERE id = $transaksi_id";
mysqli_query($koneksi, $sql_update) or die("Query gagal: " . mysqli_error($koneksi));

header("Location: detail.php?id=$transaksi_id&tipe=sukses&pesan=" . urlencode("Detail tersimpan dan total transaksi diperbarui."));
exit;
