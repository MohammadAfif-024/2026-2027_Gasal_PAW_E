<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Penjualan</title>
  <style>
    body   { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
    nav    { background: #2c3e50; padding: 12px 24px; }
    nav a  { color: #fff; margin-right: 20px; text-decoration: none; font-weight: bold; }
    .isi   { max-width: 900px; margin: 24px auto; background: #fff; padding: 24px; border-radius: 8px; }
    table  { width: 100%; border-collapse: collapse; margin: 16px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th     { background: #ecf0f1; }
    input, select, textarea { padding: 6px; margin: 4px 0 12px; width: 100%; box-sizing: border-box; }
    button, .btn { background: #2980b9; color: #fff; border: 0; padding: 8px 14px;
                   border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
    .merah  { background: #c0392b; }
    .sukses { background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 12px; }
    .gagal  { background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 12px; }
  </style>
</head>
<body>

<nav>
  <a href="barang.php">Data Barang</a>
  <a href="transaksi.php">Transaksi</a>
</nav>

<div class="isi">
<?php
// Menampilkan notifikasi yang dikirim lewat URL (?pesan=...&tipe=...)
if (isset($_GET['pesan'])) {
    $kelas = (isset($_GET['tipe']) && $_GET['tipe'] == 'gagal') ? 'gagal' : 'sukses';
    echo "<div class='" . $kelas . "'>" . htmlspecialchars($_GET['pesan']) . "</div>";
}
