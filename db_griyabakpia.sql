-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 18 Bulan Mei 2025 pada 08.24
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_griyabakpia`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_karyawan`
--

CREATE TABLE `absensi_karyawan` (
  `id_absensi` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') NOT NULL,
  `lembur` tinyint(1) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `satuan`, `stok`) VALUES
(3, 'Tepung Terigu Segitiga Biru', 'Karung', 21),
(5, 'Telur', 'Krat', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi_kasir`
--

CREATE TABLE `detail_transaksi_kasir` (
  `id_detail_transaksi` int(50) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `qty` int(5) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `alamat_karyawan` varchar(50) NOT NULL,
  `nomor_telepon` int(11) NOT NULL,
  `peran_karyawan` enum('Manager','Kasir','Pembuat Bakpia') NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `foto_karyawan` varchar(100) NOT NULL,
  `status_tempat_tinggal` enum('Menetap','Tidak Menetap') NOT NULL,
  `status_gaji` enum('Sudah','Belum') NOT NULL,
  `status_karyawan` enum('Aktif','Nonaktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan`
--

CREATE TABLE `keuangan` (
  `id_keuangan` int(255) NOT NULL,
  `waktu` datetime NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `saldo` int(255) NOT NULL,
  `asal` int(255) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_bahan`
--

CREATE TABLE `log_bahan` (
  `id_log_bahan` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `jenis_aktivitas` enum('pembelian','pemakaian') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `harga_total` int(11) DEFAULT NULL,
  `updated_by` varchar(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_bahan`
--

INSERT INTO `log_bahan` (`id_log_bahan`, `id_bahan`, `tanggal`, `jenis_aktivitas`, `jumlah`, `harga_satuan`, `harga_total`, `updated_by`, `updated_at`, `keterangan`) VALUES
(3, 3, '2025-05-15 00:00:00', 'pembelian', 15, 200000, 3000000, NULL, '2025-05-17 08:09:49', 'Lunas lee....'),
(4, 3, '2025-05-17 00:00:00', 'pembelian', 6, 200000, 1200000, NULL, '2025-05-17 08:09:40', 'harga naik vro');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stok_outlet`
--

CREATE TABLE `log_stok_outlet` (
  `id_log_stok_outlet` int(11) NOT NULL,
  `id_produk_outlet` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `outlet`
--

CREATE TABLE `outlet` (
  `id_outlet` int(11) NOT NULL,
  `nama_outlet` varchar(50) NOT NULL,
  `jenis_outlet` enum('Sendiri','Luar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemilik`
--

CREATE TABLE `pemilik` (
  `id_pemilik` int(11) NOT NULL,
  `nama_pemilik` varchar(50) NOT NULL,
  `jenis_pemilik` enum('sendiri','penitip') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemilik`
--

INSERT INTO `pemilik` (`id_pemilik`, `nama_pemilik`, `jenis_pemilik`) VALUES
(1, 'Griya Bakpia', 'sendiri'),
(2, 'Peyek Jogja', 'penitip');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_pemilik` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `nama_produk` varchar(50) NOT NULL,
  `harga_default` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_pemilik`, `id_outlet`, `nama_produk`, `harga_default`) VALUES
(49, 1, 2, 'Bakpia Kacang Hijau Premium', 42000),
(50, 1, 2, 'Bakpia Kacang Hijau Basah', 35000),
(51, 1, 1, 'Bakpia Kacang Hijau Premium', 42000),
(52, 1, 1, 'Bakpia Kacang Hijau Basah', 35000),
(59, 1, 2, 'Bakpia Keju Premium', 42000),
(60, 1, 2, 'Bakpia Keju Basah', 30000),
(61, 1, 2, 'Bakpia Coklat Premium', 42000),
(64, 1, 2, 'Bakpia Coklat Basah', 30000),
(69, 1, 2, 'Bakpia Matcha', 45000),
(70, 1, 2, 'Bakpia Matcha Premium', 50000),
(71, 1, 2, 'Bakpia Tiramisu', 42000),
(72, 1, 2, 'Bakpia Tiramisu Premium', 50000),
(73, 1, 2, 'Bakpia HIjau Hijauan', 42000),
(74, 1, 2, 'Bakpia HIjau Hijauan Premium', 42000),
(75, 1, 1, 'Bakpia Keju Premium', 42000),
(76, 1, 1, 'Bakpia Keju Basah', 30000),
(77, 1, 1, 'Bakpia Coklat Premium', 42000),
(78, 1, 1, 'Bakpia Coklat Basah', 30000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_outlet`
--

CREATE TABLE `produk_outlet` (
  `id_produk_outlet` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_outlet` int(11) DEFAULT NULL,
  `harga_outlet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_gaji`
--

CREATE TABLE `transaksi_gaji` (
  `id_transaksi_gaji` int(50) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `jumlah_gaji` int(50) NOT NULL,
  `keterangan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_kasir`
--

CREATE TABLE `transaksi_kasir` (
  `id_transaksi_kasir` int(50) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `jumlah_kembalian` int(11) NOT NULL,
  `waktu_transaksi` date NOT NULL,
  `catatan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi_karyawan`
--
ALTER TABLE `absensi_karyawan`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `detail_transaksi_kasir`
--
ALTER TABLE `detail_transaksi_kasir`
  ADD PRIMARY KEY (`id_detail_transaksi`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indeks untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id_keuangan`);

--
-- Indeks untuk tabel `log_bahan`
--
ALTER TABLE `log_bahan`
  ADD PRIMARY KEY (`id_log_bahan`);

--
-- Indeks untuk tabel `log_stok_outlet`
--
ALTER TABLE `log_stok_outlet`
  ADD PRIMARY KEY (`id_log_stok_outlet`);

--
-- Indeks untuk tabel `outlet`
--
ALTER TABLE `outlet`
  ADD PRIMARY KEY (`id_outlet`);

--
-- Indeks untuk tabel `pemilik`
--
ALTER TABLE `pemilik`
  ADD PRIMARY KEY (`id_pemilik`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `produk_outlet`
--
ALTER TABLE `produk_outlet`
  ADD PRIMARY KEY (`id_produk_outlet`);

--
-- Indeks untuk tabel `transaksi_gaji`
--
ALTER TABLE `transaksi_gaji`
  ADD PRIMARY KEY (`id_transaksi_gaji`);

--
-- Indeks untuk tabel `transaksi_kasir`
--
ALTER TABLE `transaksi_kasir`
  ADD PRIMARY KEY (`id_transaksi_kasir`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi_karyawan`
--
ALTER TABLE `absensi_karyawan`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi_kasir`
--
ALTER TABLE `detail_transaksi_kasir`
  MODIFY `id_detail_transaksi` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id_keuangan` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_bahan`
--
ALTER TABLE `log_bahan`
  MODIFY `id_log_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `log_stok_outlet`
--
ALTER TABLE `log_stok_outlet`
  MODIFY `id_log_stok_outlet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `outlet`
--
ALTER TABLE `outlet`
  MODIFY `id_outlet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pemilik`
--
ALTER TABLE `pemilik`
  MODIFY `id_pemilik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `produk_outlet`
--
ALTER TABLE `produk_outlet`
  MODIFY `id_produk_outlet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_gaji`
--
ALTER TABLE `transaksi_gaji`
  MODIFY `id_transaksi_gaji` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_kasir`
--
ALTER TABLE `transaksi_kasir`
  MODIFY `id_transaksi_kasir` int(50) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
