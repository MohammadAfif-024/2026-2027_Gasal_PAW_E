<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "root";
$password = "";
$database = "db_penjualan_tp5";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

$daftar_tabel = array(
    "pelanggan" => array(
        "halaman" => "Data Master Pelanggan", "objek" => "pelanggan",
        "kolom" => array("id", "nama", "jenis_kelamin", "telp", "alamat"),
        "pk" => array("id"), "auto" => array("id"), "sembunyi" => array("id")
    ),
    "transaksi" => array(
        "halaman" => "Data Transaksi", "objek" => "transaksi",
        "kolom" => array("id", "waktu_transaksi", "keterangan", "total", "pelanggan_id"),
        "pk" => array("id"), "auto" => array("id"), "sembunyi" => array(), "otomatis" => array("total")
    ),
    "transaksi_detail" => array(
        "halaman" => "Data Detail Transaksi", "objek" => "detail transaksi",
        "kolom" => array("transaksi_id", "barang_id", "harga", "qty"),
        "pk" => array("transaksi_id", "barang_id"), "auto" => array(), "sembunyi" => array()
    ),
    "barang" => array(
        "halaman" => "Data Master Barang", "objek" => "barang",
        "kolom" => array("id", "kode_barang", "nama_barang", "harga", "stok", "supplier_id"),
        "pk" => array("id"), "auto" => array("id"), "sembunyi" => array("id")
    ),
    "supplier" => array(
        "halaman" => "Data Master Supplier", "objek" => "supplier",
        "kolom" => array("id", "nama", "telp", "alamat"),
        "pk" => array("id"), "auto" => array("id"), "sembunyi" => array("id")
    ),
    "user" => array(
        "halaman" => "Data Master User", "objek" => "user",
        "kolom" => array("id_user", "username", "password", "nama", "alamat", "hp", "level"),
        "pk" => array("id_user"), "auto" => array("id_user"), "sembunyi" => array("id_user")
    ),
    "pembayaran" => array(
        "halaman" => "Data Pembayaran", "objek" => "pembayaran",
        "kolom" => array("id", "waktu_bayar", "total", "metode", "transaksi_id"),
        "pk" => array("id"), "auto" => array("id"), "sembunyi" => array()
    )
);

$level_opsi = array(1 => "admin", 2 => "petugas", 3 => "kasir", 4 => "staff", 5 => "operator", 6 => "user");

$batas = array(
    "pelanggan.nama" => 100, "pelanggan.telp" => 12,
    "supplier.nama" => 100, "supplier.telp" => 12,
    "barang.kode_barang" => 10, "barang.nama_barang" => 100,
    "user.username" => 30, "user.password" => 35, "user.nama" => 50,
    "user.alamat" => 150, "user.hp" => 20
);

function aman($teks) {
    return htmlspecialchars((string)$teks);
}

function label_kolom($kolom) {
    return ucwords(str_replace("_", " ", $kolom));
}

function ambil_tabel($daftar_tabel) {
    $tabel = isset($_GET["tabel"]) ? $_GET["tabel"] : "";
    if (!isset($daftar_tabel[$tabel])) {
        die("Tabel tidak ditemukan.");
    }
    return $tabel;
}

function pilihan_fk($koneksi, $kolom) {
    if ($kolom == "pelanggan_id") {
        return mysqli_query($koneksi, "SELECT id AS nilai, nama AS teks FROM pelanggan ORDER BY id");
    } elseif ($kolom == "supplier_id") {
        return mysqli_query($koneksi, "SELECT id AS nilai, nama AS teks FROM supplier ORDER BY id");
    } elseif ($kolom == "transaksi_id") {
        return mysqli_query($koneksi, "SELECT id AS nilai, CONCAT('Transaksi ', id) AS teks, total AS harga FROM transaksi ORDER BY id");
    } elseif ($kolom == "barang_id") {
        return mysqli_query($koneksi, "SELECT id AS nilai, CONCAT(kode_barang, ' - ', nama_barang) AS teks, harga AS harga FROM barang ORDER BY id");
    }
    return false;
}

function tampil_input($koneksi, $tabel, $kolom, $nilai = "") {
    global $level_opsi, $batas;
    $nilai = aman($nilai);
    $max = isset($batas[$tabel . "." . $kolom]) ? ' maxlength="' . $batas[$tabel . "." . $kolom] . '"' : "";
    $ph = ' placeholder="' . aman(label_kolom($kolom)) . '"';

    if ($kolom == "jenis_kelamin") {
        echo '<select class="form-control" name="jenis_kelamin" required>';
        echo '<option value="">Pilih</option>';
        echo '<option value="L"' . ($nilai == "L" ? " selected" : "") . '>L</option>';
        echo '<option value="P"' . ($nilai == "P" ? " selected" : "") . '>P</option>';
        echo '</select>';
    } elseif ($kolom == "metode") {
        echo '<select class="form-control" name="metode" required>';
        foreach (array("TUNAI", "TRANSFER", "EDC") as $opsi) {
            echo '<option value="' . $opsi . '"' . ($nilai == $opsi ? " selected" : "") . '>' . $opsi . '</option>';
        }
        echo '</select>';
    } elseif ($kolom == "level") {
        echo '<select class="form-control" name="level" required>';
        foreach ($level_opsi as $angka => $teks) {
            echo '<option value="' . $angka . '"' . ((string)$nilai === (string)$angka ? " selected" : "") . '>' . $teks . '</option>';
        }
        echo '</select>';
    } elseif (in_array($kolom, array("pelanggan_id", "supplier_id", "transaksi_id", "barang_id"))) {
        $pilihan = pilihan_fk($koneksi, $kolom);
        echo '<select class="form-control" name="' . $kolom . '" required>';
        echo '<option value="">Pilih ' . aman(label_kolom($kolom)) . '</option>';
        if ($pilihan) {
            while ($opsi = mysqli_fetch_assoc($pilihan)) {
                $selected = ((string)$opsi["nilai"] === (string)htmlspecialchars_decode($nilai)) ? " selected" : "";
                $dh = isset($opsi["harga"]) ? ' data-harga="' . aman($opsi["harga"]) . '"' : "";
                echo '<option value="' . aman($opsi["nilai"]) . '"' . $dh . $selected . '>' . aman($opsi["teks"]) . '</option>';
            }
        }
        echo '</select>';
    } elseif (in_array($kolom, array("alamat", "keterangan"))) {
        echo '<textarea class="form-control" name="' . $kolom . '" rows="3"' . $max . $ph . '>' . $nilai . '</textarea>';
    } elseif ($kolom == "waktu_transaksi") {
        echo '<input class="form-control" type="date" name="waktu_transaksi" value="' . substr($nilai, 0, 10) . '" required>';
    } elseif ($kolom == "waktu_bayar") {
        $v = str_replace(" ", "T", substr($nilai, 0, 16));
        echo '<input class="form-control" type="datetime-local" name="waktu_bayar" value="' . $v . '" required>';
    } elseif (($tabel == "transaksi_detail" && $kolom == "harga") || ($tabel == "pembayaran" && $kolom == "total")) {
        // diisi otomatis dari barang / transaksi yang dipilih
        echo '<input class="form-control" type="number" name="' . $kolom . '" value="' . $nilai . '" placeholder="Terisi otomatis" readonly style="background:#eee">';
    } elseif (in_array($kolom, array("harga", "total", "qty", "stok"))) {
        echo '<input class="form-control" type="number" step="1" min="0" name="' . $kolom . '" value="' . $nilai . '"' . $ph . ' required>';
    } else {
        echo '<input class="form-control" type="text" name="' . $kolom . '" value="' . $nilai . '"' . $max . $ph . ' required>';
    }
}

function format_nilai_post($kolom, $nilai) {
    if ($kolom == "waktu_bayar") {
        $nilai = str_replace("T", " ", $nilai);
        if (strlen($nilai) == 16) {
            $nilai .= ":00";
        }
    }
    return $nilai;
}

function nilai_otomatis($koneksi, $tabel, $kolom, $nilai) {
    if ($tabel == "transaksi_detail" && $kolom == "harga" && isset($_POST["barang_id"])) {
        $id = mysqli_real_escape_string($koneksi, $_POST["barang_id"]);
        $r = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id='$id'");
        if ($r && ($b = mysqli_fetch_assoc($r))) {
            return $b["harga"];
        }
    }
    if ($tabel == "pembayaran" && $kolom == "total" && isset($_POST["transaksi_id"])) {
        $id = mysqli_real_escape_string($koneksi, $_POST["transaksi_id"]);
        $r = mysqli_query($koneksi, "SELECT total FROM transaksi WHERE id='$id'");
        if ($r && ($t = mysqli_fetch_assoc($r))) {
            return $t["total"];
        }
    }
    return $nilai;
}

function hitung_total($koneksi, $transaksi_id) {
    $id = mysqli_real_escape_string($koneksi, $transaksi_id);
    mysqli_query($koneksi, "UPDATE transaksi SET total = (SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id='$id') WHERE id='$id'");
}

// JavaScript: isi harga / total otomatis saat memilih barang / transaksi
function skrip_otomatis($tabel) {
    if ($tabel == "transaksi_detail") {
        $pilih = "barang_id"; $isi = "harga";
    } elseif ($tabel == "pembayaran") {
        $pilih = "transaksi_id"; $isi = "total";
    } else {
        return;
    }
    echo '<script>
    (function () {
        var sel = document.querySelector(\'select[name="' . $pilih . '"]\');
        var target = document.querySelector(\'input[name="' . $isi . '"]\');
        if (!sel || !target) return;
        function isi() {
            var o = sel.options[sel.selectedIndex];
            target.value = (o && o.getAttribute("data-harga") !== null) ? o.getAttribute("data-harga") : "";
        }
        sel.addEventListener("change", isi);
    })();
    </script>';
}
?>
