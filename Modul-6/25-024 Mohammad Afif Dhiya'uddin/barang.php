<?php
require_once "koneksi.php";
include "header.php";

// Ambil semua barang beserta nama supplier-nya
$sql = "SELECT barang.*, supplier.nama AS nama_supplier
        FROM barang
        JOIN supplier ON supplier.id = barang.supplier_id
        ORDER BY barang.id";
$query = mysqli_query($koneksi, $sql) or die("Query gagal: " . mysqli_error($koneksi));
?>

<h2>Data Barang</h2>

<table>
  <tr>
    <th>No</th>
    <th>Kode</th>
    <th>Nama Barang</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Supplier</th>
    <th>Aksi</th>
  </tr>

  <?php $no = 1; ?>
  <?php while ($row = mysqli_fetch_assoc($query)) { ?>
  <tr>
    <td><?= $no++ ?></td>
    <td><?= $row['kode_barang'] ?></td>
    <td><?= $row['nama_barang'] ?></td>
    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
    <td><?= $row['stok'] ?></td>
    <td><?= $row['nama_supplier'] ?></td>
    <td>
      <a class="btn merah"
         href="hapus_barang.php?id=<?= $row['id'] ?>"
         onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
    </td>
  </tr>
  <?php } ?>
</table>
</div>
</body>
</html>
