-- =====================================================
-- DATABASE: db_penjualan_tp6
-- Cara pakai: phpMyAdmin > tab "Import" > pilih file ini > Kirim
-- =====================================================
DROP DATABASE IF EXISTS db_penjualan_tp6;
CREATE DATABASE db_penjualan_tp6;
USE db_penjualan_tp6;

-- ---------- 1. MEMBUAT TABEL (urutan: tabel induk dulu) ----------
CREATE TABLE supplier (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12),
  alamat TEXT
);

CREATE TABLE barang (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10) NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT NOT NULL,
  stok        INT NOT NULL,
  supplier_id INT NOT NULL,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

CREATE TABLE pelanggan (
  id            VARCHAR(20) PRIMARY KEY,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12),
  alamat        TEXT
);

CREATE TABLE transaksi (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE NOT NULL,
  keterangan      TEXT,
  total           INT NOT NULL DEFAULT 0,
  pelanggan_id    VARCHAR(20) NOT NULL,
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
);

-- Primary key gabungan: 1 barang hanya boleh muncul 1x di 1 transaksi
CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id    INT NOT NULL,
  harga        INT NOT NULL,
  qty          INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  FOREIGN KEY (barang_id)    REFERENCES barang(id)
);

CREATE TABLE pembayaran (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar  DATETIME NOT NULL,
  total        INT NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
);

CREATE TABLE user (
  id_user  TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  nama     VARCHAR(50),
  alamat   VARCHAR(150),
  hp       VARCHAR(20),
  level    TINYINT(1) NOT NULL
);

-- ---------- 2. DATA MASTER (minimal 5 data) ----------
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Pangan',   '031111111', 'Jl. Raya Darmo No. 1, Surabaya'),
('CV Makmur Jaya',     '031222222', 'Jl. Basuki Rahmat No. 5, Surabaya'),
('UD Berkah Abadi',    '031333333', 'Jl. Ahmad Yani No. 10, Surabaya'),
('PT Indo Distribusi', '031444444', 'Jl. Rungkut No. 7, Surabaya'),
('CV Sinar Terang',    '031555555', 'Jl. Diponegoro No. 3, Surabaya');

-- BRG007 sengaja TIDAK dipakai di transaksi manapun,
-- supaya bisa dites: barang yang belum dipakai boleh dihapus.
INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras 5kg',        65000,  50, 1),
('BRG002', 'Minyak Goreng 2L', 38000,  40, 1),
('BRG003', 'Gula Pasir 1kg',   17000,  60, 2),
('BRG004', 'Kopi Sachet',       2500, 200, 3),
('BRG005', 'Teh Celup',        12000,  80, 4),
('BRG006', 'Mie Instan',        3500, 300, 5),
('BRG007', 'Sabun Mandi',       4500, 100, 5);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Budi Santoso', 'L', '081111111', 'Jl. Mawar No. 1'),
('P002', 'Siti Aminah',  'P', '082222222', 'Jl. Melati No. 2'),
('P003', 'Andi Wijaya',  'L', '083333333', 'Jl. Kenanga No. 3'),
('P004', 'Dewi Lestari', 'P', '084444444', 'Jl. Anggrek No. 4'),
('P005', 'Rudi Hartono', 'L', '085555555', 'Jl. Dahlia No. 5');

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',  MD5('admin123'),  'Administrator', 'Surabaya', '081200000001', 1),
('kasir1', MD5('kasir123'),  'Kasir Satu',    'Surabaya', '081200000002', 2),
('kasir2', MD5('kasir123'),  'Kasir Dua',     'Sidoarjo', '081200000003', 2),
('gudang', MD5('gudang123'), 'Staf Gudang',   'Gresik',   '081200000004', 2),
('owner',  MD5('owner123'),  'Pemilik Toko',  'Surabaya', '081200000005', 1);

-- ---------- 3. DATA TRANSAKSI (10 transaksi) ----------
-- Kolom total diisi 0 dulu, nanti dihitung dari detailnya.
INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Belanja bulanan',  0, 'P001'),
('2026-10-03', 'Belanja harian',   0, 'P002'),
('2026-10-05', 'Beli teh',         0, 'P003'),
('2026-10-08', 'Stok warung',      0, 'P001'),
('2026-10-12', 'Belanja mingguan', 0, 'P004'),
('2026-10-15', 'Beli minyak',      0, 'P005'),
('2026-10-20', 'Belanja harian',   0, 'P002'),
('2026-10-25', 'Beli kopi',        0, 'P003'),
('2026-11-01', 'Belanja bulanan',  0, 'P004'),
('2026-11-05', 'Belanja mingguan', 0, 'P005');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 65000, 2),  (1, 2, 38000, 1),
(2, 3, 17000, 3),  (2, 4,  2500, 10),
(3, 5, 12000, 2),
(4, 6,  3500, 20), (4, 4,  2500, 5),
(5, 1, 65000, 1),  (5, 3, 17000, 2), (5, 5, 12000, 1),
(6, 2, 38000, 2),
(7, 6,  3500, 10), (7, 3, 17000, 1),
(8, 4,  2500, 20),
(9, 2, 38000, 1),  (9, 1, 65000, 1),
(10, 5, 12000, 3), (10, 6, 3500, 5);

-- Hitung total tiap transaksi = SUM(harga x qty) dari detailnya
UPDATE transaksi
SET total = (SELECT SUM(transaksi_detail.harga * transaksi_detail.qty)
             FROM transaksi_detail
             WHERE transaksi_detail.transaksi_id = transaksi.id);

-- Data pembayaran: 1 transaksi = 1 pembayaran
INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id)
SELECT CONCAT(waktu_transaksi, ' 10:00:00'), total,
       ELT(id % 3 + 1, 'TUNAI', 'TRANSFER', 'EDC'), id
FROM transaksi;
