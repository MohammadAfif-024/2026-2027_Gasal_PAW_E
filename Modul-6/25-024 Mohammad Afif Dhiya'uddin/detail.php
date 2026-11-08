<?php
// SOAL 2: Halaman MASTER-DETAIL
require_once "koneksi.php";
include "header.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// ---------- MASTER: ambil data transaksi ----------
$sql_master = "SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
               FROM transaksi
               JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
               WHERE transaksi.id = $id";
$query_master = mysqli_query($koneksi, $sql_master) or die("Query gagal: " . mysqli_error($koneksi));
$master = mysqli_fetch_assoc($query_master);

if (!$master) {
    echo "<p>Transaksi tidak ditemukan.</p>";
    echo "<a class='btn' href='transaksi.php'>Kembali</a>";
    include "footer.php";
    exit;
}

// ---------- DETAIL: ambil barang yang sudah ada di transaksi ini ----------
$sql_detail = "SELECT transaksi_detail.*, barang.nama_barang
               FROM transaksi_detail
               JOIN barang ON barang.id = transaksi_detail.barang_id
               WHERE transaksi_detail.transaksi_id = $id";
$query_detail = mysqli_query($koneksi, $sql_detail) or die("Query gagal: " . mysqli_error($koneksi));

// Simpan semua barang_id yang sudah dipakai ke dalam array
// (dipakai nanti untuk menonaktifkan option di dropdown)
$barang_terpakai = array();
$daftar_detail   = array();
while ($d = mysqli_fetch_assoc($query_detail)) {
    $barang_terpakai[] = $d['barang_id'];
    $daftar_detail[]   = $d;
}

// ---------- Ambil semua barang untuk dropdown ----------
$query_barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang")
    or die("Query gagal: " . mysqli_error($koneksi));
?>

<h2>Detail Transaksi #<?= $master['id'] ?></h2>

<p>
  Tanggal: <b><?= $master['waktu_transaksi'] ?></b><br>
  Pelanggan: <b><?= $master['nama_pelanggan'] ?></b><br>
  Keterangan: <?= htmlspecialchars((string) $master['keterangan']) ?>
</p>

<!-- Tabel DETAIL -->
<table>
  <tr>
    <th>Barang</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
  </tr>

  <?php foreach ($daftar_detail as $d) { ?>
  <tr>
    <td><?= $d['nama_barang'] ?></td>
    <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
    <td><?= $d['qty'] ?></td>
    <td>Rp <?= number_format($d['harga'] * $d['qty'], 0, ',', '.') ?></td>
  </tr>
  <?php } ?>

  <tr>
    <th colspan="3">TOTAL</th>
    <th>Rp <?= number_format($master['total'], 0, ',', '.') ?></th>
  </tr>
</table>

<!-- Form input DETAIL -->
<h3>Tambah Barang ke Transaksi</h3>
<form method="post" action="simpan_detail.php">
  <input type="hidden" name="transaksi_id" value="<?= $id ?>">

  Barang
  <select name="barang_id" required>
    <option value="">-- Pilih Barang --</option>
    <?php while ($b = mysqli_fetch_assoc($query_barang)) { ?>
      <?php
        // Kalau barang sudah ada di detail, option dinonaktifkan (disabled)
        $disabled = in_array($b['id'], $barang_terpakai) ? "disabled" : "";
      ?>
      <option value="<?= $b['id'] ?>" <?= $disabled ?>>
        <?= $b['nama_barang'] ?> - Rp <?= number_format($b['harga'], 0, ',', '.') ?>
      </option>
    <?php } ?>
  </select>

  Qty
  <input type="number" name="qty" min="1" value="1" required>

  <button type="submit">Simpan Detail</button>
  <a class="btn merah" href="transaksi.php">Kembali</a>
</form>
</div>
</body>
</html>