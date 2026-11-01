<?php
    include "koneksi.php";

    $jumlah_pelanggan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pelanggan"));
    $jumlah_barang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang"));
    $jumlah_supplier = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM supplier"));
    $jumlah_transaksi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM transaksi"));
    $jumlah_pembayaran = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pembayaran"));
    $jumlah_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM `user`"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Penjualan</h2>
    <a href="index.php">Dashboard</a>
    <a href="pelanggan.php">Data Pelanggan</a>
    <a href="barang.php">Data Barang</a>
    <a href="supplier.php">Data Supplier</a>
    <a href="transaksi.php">Data Transaksi</a>
    <a href="pembayaran.php">Data Pembayaran</a>
    <a href="user.php">Data User</a>
</div>

<div class="main">
    <h1>Dashboard</h1>
    <p>Selamat datang di Sistem Informasi Penjualan.</p>

    <div class="cards">
        <div class="card">
            <h3>Data Pelanggan</h3>
            <h2><?php echo $jumlah_pelanggan; ?></h2>
        </div>

        <div class="card">
            <h3>Data Barang</h3>
            <h2><?php echo $jumlah_barang; ?></h2>
        </div>

        <div class="card">
            <h3>Data Supplier</h3>
            <h2><?php echo $jumlah_supplier; ?></h2>
        </div>

        <div class="card">
            <h3>Data Transaksi</h3>
            <h2><?php echo $jumlah_transaksi; ?></h2>
        </div>

        <div class="card">
            <h3>Data Pembayaran</h3>
            <h2><?php echo $jumlah_pembayaran; ?></h2>
        </div>

        <div class="card">
            <h3>Data User</h3>
            <h2><?php echo $jumlah_user; ?></h2>
        </div>
    </div>
</div>

</body>
</html>