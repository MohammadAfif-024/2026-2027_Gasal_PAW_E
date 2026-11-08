<?php
require_once "koneksi.php";

// Jika form "Tambah Transaksi" disubmit -> simpan data MASTER
if (isset($_POST['simpan'])) {
    $tanggal    = mysqli_real_escape_string($koneksi, $_POST['waktu_transaksi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $pelanggan  = mysqli_real_escape_string($koneksi, $_POST['pelanggan_id']);

    // total diisi 0 dulu, nanti otomatis dihitung saat detail diinput
    $sql = "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
            VALUES ('$tanggal', '$keterangan', 0, '$pelanggan')";
    mysqli_query($koneksi, $sql) or die("Query gagal: " . mysqli_error($koneksi));

    // Pindah ke halaman detail untuk transaksi yang baru dibuat
    $id_baru = mysqli_insert_id($koneksi);
    header("Location: detail.php?id=" . $id_baru);
    exit;
}

include "header.php";

$query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama")
    or die("Query gagal: " . mysqli_error($koneksi));

$sql_transaksi = "SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
                  FROM transaksi
                  JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
                  ORDER BY transaksi.id DESC";
$query_transaksi = mysqli_query($koneksi, $sql_transaksi)
    or die("Query gagal: " . mysqli_error($koneksi));
?>

<h2>Data Transaksi (Master)</h2>

<h3>Tambah Transaksi Baru</h3>
<form method="post">
  Tanggal
  <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>

  Pelanggan
  <select name="pelanggan_id" required>
    <option value="">-- Pilih Pelanggan --</option>
    <?php while ($p = mysqli_fetch_assoc($query_pelanggan)) { ?>
      <option value="<?= $p['id'] ?>"><?= $p['nama'] ?></option>
    <?php } ?>
  </select>

  Keterangan
  <textarea name="keterangan" rows="2"></textarea>

  <button type="submit" name="simpan">Simpan &amp; Isi Detail</button>
</form>

<h3>Daftar Transaksi</h3>
<table>
  <tr>
    <th>ID</th>
    <th>Tanggal</th>
    <th>Pelanggan</th>
    <th>Keterangan</th>
    <th>Total</th>
    <th>Aksi</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($query_transaksi)) { ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['waktu_transaksi'] ?></td>
    <td><?= $row['nama_pelanggan'] ?></td>
    <td><?= htmlspecialchars((string) $row['keterangan']) ?></td>
    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
    <td><a class="btn" href="detail.php?id=<?= $row['id'] ?>">Detail</a></td>
  </tr>
  <?php } ?>
</table>
</div>
</body>
</html>

