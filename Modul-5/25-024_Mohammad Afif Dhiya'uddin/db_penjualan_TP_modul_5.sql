CREATE DATABASE IF NOT EXISTS db_penjualan_tp5;
USE db_penjualan_tp5;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS supplier;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. TABEL PELANGGAN
-- Catatan: pada gambar id bertipe varchar(20), tetapi pada deskripsi id harus
-- "primary key, auto increment, not null". Auto increment hanya bisa untuk angka,
-- maka id dibuat INT (mengikuti deskripsi).
CREATE TABLE pelanggan (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

-- 2. TABEL SUPPLIER
CREATE TABLE supplier (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

-- 3. TABEL USER
CREATE TABLE `user` (
    id_user TINYINT(2) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150),
    hp VARCHAR(20),
    level TINYINT(1) NOT NULL
) ENGINE=InnoDB;

-- 4. TABEL BARANG
CREATE TABLE barang (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT(11) NOT NULL,
    stok INT(11) NOT NULL,
    supplier_id INT(11),
    CONSTRAINT fk_barang_supplier
        FOREIGN KEY (supplier_id) REFERENCES supplier(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 5. TABEL TRANSAKSI
CREATE TABLE transaksi (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT,
    total INT(11) NOT NULL,
    pelanggan_id INT(11) NOT NULL,
    CONSTRAINT fk_transaksi_pelanggan
        FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 6. TABEL TRANSAKSI_DETAIL (composite primary key)
CREATE TABLE transaksi_detail (
    transaksi_id INT(11) NOT NULL,
    barang_id INT(11) NOT NULL,
    harga INT(11) NOT NULL,
    qty INT(11) NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    CONSTRAINT fk_detail_transaksi
        FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_barang
        FOREIGN KEY (barang_id) REFERENCES barang(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. TABEL PEMBAYARAN
CREATE TABLE pembayaran (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME NOT NULL,
    total INT(11) NOT NULL,
    metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
    transaksi_id INT(11) NOT NULL,
    CONSTRAINT fk_pembayaran_transaksi
        FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- DATA MASTER: 10 PELANGGAN
INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
(1, 'Ahmad Fauzan', 'L', '081300000001', 'Gresik'),
(2, 'Dimas Saputra', 'L', '081300000002', 'Surabaya'),
(3, 'Siti Aisyah', 'P', '081300000003', 'Lamongan'),
(4, 'Budi Santoso', 'L', '081300000004', 'Sidoarjo'),
(5, 'Dewi Lestari', 'P', '081300000005', 'Mojokerto'),
(6, 'Rizky Pratama', 'L', '081300000006', 'Bangkalan'),
(7, 'Nadia Putri', 'P', '081300000007', 'Malang'),
(8, 'Fajar Hidayat', 'L', '081300000008', 'Pasuruan'),
(9, 'Nurul Azizah', 'P', '081300000009', 'Tuban'),
(10, 'Andi Wijaya', 'L', '081300000010', 'Jombang');

-- DATA MASTER: 10 SUPPLIER
INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Makmur', '081234560001', 'Surabaya'),
(2, 'CV Berkah Jaya', '081234560002', 'Gresik'),
(3, 'UD Mitra Usaha', '081234560003', 'Lamongan'),
(4, 'PT Sentosa Abadi', '081234560004', 'Sidoarjo'),
(5, 'CV Maju Bersama', '081234560005', 'Mojokerto'),
(6, 'UD Lancar Jaya', '081234560006', 'Bangkalan'),
(7, 'PT Karya Niaga', '081234560007', 'Malang'),
(8, 'CV Sinar Terang', '081234560008', 'Pasuruan'),
(9, 'UD Rezeki Lancar', '081234560009', 'Tuban'),
(10, 'PT Global Sejahtera', '081234560010', 'Jombang');

-- DATA MASTER: 10 USER
-- level (tinyint): 1=admin, 2=petugas, 3=kasir, 4=staff, 5=operator, 6=user
INSERT INTO `user` (id_user, username, password, nama, alamat, hp, level) VALUES
(1, 'admin', 'admin123', 'Administrator', 'Surabaya', '082100000001', 1),
(2, 'petugas01', '123456', 'Petugas Satu', 'Gresik', '082100000002', 2),
(3, 'petugas02', '123456', 'Petugas Dua', 'Lamongan', '082100000003', 2),
(4, 'kasir01', '123456', 'Kasir Satu', 'Sidoarjo', '082100000004', 3),
(5, 'kasir02', '123456', 'Kasir Dua', 'Mojokerto', '082100000005', 3),
(6, 'staff01', '123456', 'Staff Satu', 'Bangkalan', '082100000006', 4),
(7, 'staff02', '123456', 'Staff Dua', 'Malang', '082100000007', 4),
(8, 'operator01', '123456', 'Operator Satu', 'Pasuruan', '082100000008', 5),
(9, 'operator02', '123456', 'Operator Dua', 'Tuban', '082100000009', 5),
(10, 'user01', '123456', 'Pengguna Satu', 'Jombang', '082100000010', 6);

-- DATA MASTER: 10 BARANG
INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Buku Tulis', 5000, 100, 1),
(2, 'BRG002', 'Pulpen', 3000, 150, 2),
(3, 'BRG003', 'Pensil', 2500, 120, 3),
(4, 'BRG004', 'Penghapus', 2000, 90, 4),
(5, 'BRG005', 'Penggaris', 4000, 80, 5),
(6, 'BRG006', 'Spidol', 7000, 70, 6),
(7, 'BRG007', 'Map Plastik', 2500, 110, 7),
(8, 'BRG008', 'Buku Gambar', 6000, 60, 8),
(9, 'BRG009', 'Stabilo', 8000, 50, 9),
(10, 'BRG010', 'Kertas A4', 45000, 40, 10);

-- DATA TRANSAKSI: 10 TRANSAKSI (waktu_transaksi bertipe DATE sesuai gambar)
INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1, '2026-10-01', 'Pembelian buku tulis', 10000, 1),
(2, '2026-10-02', 'Pembelian pulpen', 9000, 2),
(3, '2026-10-03', 'Pembelian pensil', 7500, 3),
(4, '2026-10-04', 'Pembelian penghapus', 8000, 4),
(5, '2026-10-05', 'Pembelian penggaris', 12000, 5),
(6, '2026-10-06', 'Pembelian spidol', 14000, 6),
(7, '2026-10-07', 'Pembelian map plastik', 7500, 7),
(8, '2026-10-08', 'Pembelian buku gambar', 12000, 8),
(9, '2026-10-09', 'Pembelian stabilo', 16000, 9),
(10, '2026-10-10', 'Pembelian kertas A4', 45000, 10);

-- DATA TRANSAKSI_DETAIL: 10 DETAIL
INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 5000, 2),
(2, 2, 3000, 3),
(3, 3, 2500, 3),
(4, 4, 2000, 4),
(5, 5, 4000, 3),
(6, 6, 7000, 2),
(7, 7, 2500, 3),
(8, 8, 6000, 2),
(9, 9, 8000, 2),
(10, 10, 45000, 1);

-- DATA PEMBAYARAN: 10 PEMBAYARAN
INSERT INTO pembayaran (id, waktu_bayar, total, metode, transaksi_id) VALUES
(1, '2026-10-01 09:05:00', 10000, 'TUNAI', 1),
(2, '2026-10-02 10:05:00', 9000, 'TRANSFER', 2),
(3, '2026-10-03 11:05:00', 7500, 'EDC', 3),
(4, '2026-10-04 12:05:00', 8000, 'TUNAI', 4),
(5, '2026-10-05 13:05:00', 12000, 'TRANSFER', 5),
(6, '2026-10-06 14:05:00', 14000, 'EDC', 6),
(7, '2026-10-07 15:05:00', 7500, 'TUNAI', 7),
(8, '2026-10-08 09:35:00', 12000, 'TRANSFER', 8),
(9, '2026-10-09 10:35:00', 16000, 'EDC', 9),
(10, '2026-10-10 11:35:00', 45000, 'TUNAI', 10);
