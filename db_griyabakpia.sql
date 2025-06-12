-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 12 Jun 2025 pada 09.16
-- Versi server: 5.7.24
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
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `satuan`, `stok`) VALUES
(3, 'Tepung Terigu Segitiga Biru', 'Karung', 0),
(5, 'Telur Ayam', 'Krat', 0),
(6, 'Telur Sapi', 'Krat', 0),
(9, 'Garam Cina', 'Kilo', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembayaran_toko`
--

CREATE TABLE `detail_pembayaran_toko` (
  `id_detail_pembayaran_toko` int(11) NOT NULL,
  `id_pembayaran_toko` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah_terjual` int(11) NOT NULL,
  `jumlah_return` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_pembayaran_toko`
--

INSERT INTO `detail_pembayaran_toko` (`id_detail_pembayaran_toko`, `id_pembayaran_toko`, `id_produk`, `jumlah_terjual`, `jumlah_return`) VALUES
(15, 2, 79, 10, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_setor_toko`
--

CREATE TABLE `detail_setor_toko` (
  `id_detail_setor_toko` int(11) NOT NULL,
  `id_log_setor_toko` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah_produk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_setor_toko`
--

INSERT INTO `detail_setor_toko` (`id_detail_setor_toko`, `id_log_setor_toko`, `id_produk`, `jumlah_produk`) VALUES
(23, 16, 79, 20),
(24, 17, 79, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi_kasir`
--

CREATE TABLE `detail_transaksi_kasir` (
  `id_detail_transaksi` varchar(50) NOT NULL,
  `id_transaksi` varchar(50) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `qty` int(5) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_transaksi_kasir`
--

INSERT INTO `detail_transaksi_kasir` (`id_detail_transaksi`, `id_transaksi`, `id_produk`, `nama_produk`, `harga_satuan`, `qty`, `subtotal`) VALUES
('DTR684651e34d485', 'TR250609101547', 49, 'Bakpia Kacang Hijau Premium', 50000, 6, 300000),
('DTR684651e350e50', 'TR250609101547', 50, 'Bakpia Kacang Hijau Basah', 50000, 6, 300000),
('DTR684651e3641d3', 'TR250609101547', 52, 'Bakpia Kacang Hijau Basah', 50000, 6, 300000),
('DTR684651e367807', 'TR250609101547', 51, 'Bakpia Kacang Hijau Premium', 50000, 6, 300000),
('DTR6846525c9323e', 'TR250609101748', 49, 'Bakpia Kacang Hijau Premium', 50000, 5, 250000),
('DTR6846525c9f2a8', 'TR250609101748', 50, 'Bakpia Kacang Hijau Basah', 50000, 5, 250000),
('DTR6846525ca773b', 'TR250609101748', 52, 'Bakpia Kacang Hijau Basah', 50000, 5, 250000),
('DTR6846525cb229c', 'TR250609101748', 51, 'Bakpia Kacang Hijau Premium', 50000, 5, 250000),
('DTR6848e8bf8a66e', 'TR250611092359', 74, 'Bakpia HIjau Hijauan Premium', 42000, 10, 420000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `alamat_karyawan` varchar(50) NOT NULL,
  `nomor_telepon` varchar(50) NOT NULL,
  `kelamin_karyawan` enum('laki-laki','perempuan') NOT NULL,
  `peran_karyawan` enum('admin','manager','kasir','produksi') NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `foto_karyawan` varchar(100) NOT NULL,
  `status_tempat_tinggal` enum('Menetap','Tidak Menetap') NOT NULL,
  `status_karyawan` enum('Aktif','Nonaktif') NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `id_outlet`, `nama_karyawan`, `alamat_karyawan`, `nomor_telepon`, `kelamin_karyawan`, `peran_karyawan`, `tanggal_masuk`, `foto_karyawan`, `status_tempat_tinggal`, `status_karyawan`, `password`) VALUES
(8, 1, 'a', 'Magenlang, Jawa Tengah', '0812-2959-0343', 'laki-laki', 'admin', '2025-05-24', '1748062536__.jpeg', 'Menetap', 'Aktif', '$2y$10$hRi1qju2KOeEPcBZ0wYfhu/PN5e9Wl.ddWeDTds8Uokad764X9D1a'),
(9, 0, 'Farrel Hafizh Setyawan', 'Bantul, DIY', '0812-2540-3033', 'laki-laki', 'produksi', '2025-05-27', '1748314354_Photo_on_25-08-24_at_07_49.jpg', 'Menetap', 'Nonaktif', '$2y$10$IM5Vbldk2AjkfTUpNfplTu2iuJ0gJvUKPFfwrBSCcJMbWTG0FVSxW'),
(10, 0, 'Nasrul Amin', 'Bantul, DIY', '0812-2959-0020', 'laki-laki', 'produksi', '2025-05-31', '1748664082_DSC04352.JPG', 'Menetap', 'Aktif', '$2y$10$UDTrb9agZFk2cer/NC/2VOzgeNteWA8FCdhpqXNcQsgpyTcTp7WR.'),
(11, 0, 'Ramadhani', 'Bantul, DIY', '0812-0102-1021', 'laki-laki', 'kasir', '2025-05-31', '1748924786_DSC04991.JPG', 'Menetap', 'Aktif', '$2y$10$iHr5NJp9jK/Tnjbhc8vlYOU9qnbVzKfu2dqecN.jyodeO5vVbDo6O'),
(12, 0, 'Nadia Rahmawati', 'Sleman, DIY', '0813-9876-5432', 'perempuan', 'kasir', '2025-05-15', '1748062536__.jpeg', 'Menetap', 'Aktif', '$2y$10$trVD6XrZGt4HFkJPD32GJeodyMZcWzJaROenuZgWndahgtP91IzKu'),
(13, 0, 'Andi Saputra', 'Kulon Progo, DIY', '0812-3456-7890', 'laki-laki', 'produksi', '2025-05-18', '1748314354_Photo_on_25-08-24_at_07_49.jpg', 'Menetap', 'Aktif', '$2y$10$IM5Vbldk2AjkfTUpNfplTu2iuJ0gJvUKPFfwrBSCcJMbWTG0FVSxW'),
(14, 0, 'Siti Aisyah', 'Bantul, DIY', '0812-2233-4455', 'perempuan', 'kasir', '2025-05-20', '1748664082_DSC04352.JPG', 'Menetap', 'Aktif', '$2y$10$UDTrb9agZFk2cer/NC/2VOzgeNteWA8FCdhpqXNcQsgpyTcTp7WR.'),
(15, 0, 'Rizky Maulana', 'Sleman, DIY', '0812-9988-7766', 'laki-laki', 'produksi', '2025-05-22', '1748924786_DSC04991.JPG', 'Menetap', 'Aktif', '$2y$10$iHr5NJp9jK/Tnjbhc8vlYOU9qnbVzKfu2dqecN.jyodeO5vVbDo6O'),
(16, 0, 'Dewi Lestari', 'Gunungkidul, DIY', '0813-5566-7788', 'perempuan', 'kasir', '2025-05-23', '1748062536__.jpeg', 'Menetap', 'Aktif', '$2y$10$trVD6XrZGt4HFkJPD32GJeodyMZcWzJaROenuZgWndahgtP91IzKu'),
(17, 0, 'Fajar Pratama', 'Bantul, DIY', '0812-7777-8888', 'laki-laki', 'produksi', '2025-05-24', '1748314354_Photo_on_25-08-24_at_07_49.jpg', 'Menetap', 'Aktif', '$2y$10$IM5Vbldk2AjkfTUpNfplTu2iuJ0gJvUKPFfwrBSCcJMbWTG0FVSxW'),
(18, 0, 'Maya Sari', 'Sleman, DIY', '0812-1111-2222', 'perempuan', 'kasir', '2025-05-25', '1748664082_DSC04352.JPG', 'Menetap', 'Aktif', '$2y$10$UDTrb9agZFk2cer/NC/2VOzgeNteWA8FCdhpqXNcQsgpyTcTp7WR.'),
(19, 0, 'Agus Setiawan', 'Kulon Progo, DIY', '0812-3333-4444', 'laki-laki', 'produksi', '2025-05-26', '1748924786_DSC04991.JPG', 'Menetap', 'Aktif', '$2y$10$iHr5NJp9jK/Tnjbhc8vlYOU9qnbVzKfu2dqecN.jyodeO5vVbDo6O'),
(20, 0, 'Rina Wulandari', 'Gunungkidul, DIY', '0813-4455-6677', 'perempuan', 'kasir', '2025-05-27', '1748062536__.jpeg', 'Menetap', 'Nonaktif', '$2y$10$trVD6XrZGt4HFkJPD32GJeodyMZcWzJaROenuZgWndahgtP91IzKu'),
(21, 0, 'Budi Santoso', 'Bantul, DIY', '0812-6666-5555', 'laki-laki', 'produksi', '2025-05-28', '1748314354_Photo_on_25-08-24_at_07_49.jpg', 'Menetap', 'Aktif', '$2y$10$IM5Vbldk2AjkfTUpNfplTu2iuJ0gJvUKPFfwrBSCcJMbWTG0FVSxW'),
(22, 0, 'Lia Putri', 'Sleman, DIY', '0812-2233-5566', 'perempuan', 'kasir', '2025-05-29', '1748664082_DSC04352.JPG', 'Menetap', 'Aktif', '$2y$10$UDTrb9agZFk2cer/NC/2VOzgeNteWA8FCdhpqXNcQsgpyTcTp7WR.'),
(23, 0, 'Eko Wijaya', 'Kulon Progo, DIY', '0812-9988-3344', 'laki-laki', 'produksi', '2025-05-30', '1748924786_DSC04991.JPG', 'Menetap', 'Aktif', '$2y$10$iHr5NJp9jK/Tnjbhc8vlYOU9qnbVzKfu2dqecN.jyodeO5vVbDo6O'),
(24, 0, 'Sari Melati', 'Gunungkidul, DIY', '0813-1122-3344', 'perempuan', 'kasir', '2025-05-31', '1748062536__.jpeg', 'Menetap', 'Aktif', '$2y$10$trVD6XrZGt4HFkJPD32GJeodyMZcWzJaROenuZgWndahgtP91IzKu'),
(25, 0, 'Hendra Gunawan', 'Bantul, DIY', '0812-5566-7788', 'laki-laki', 'produksi', '2025-05-31', '1748314354_Photo_on_25-08-24_at_07_49.jpg', 'Menetap', 'Aktif', '$2y$10$IM5Vbldk2AjkfTUpNfplTu2iuJ0gJvUKPFfwrBSCcJMbWTG0FVSxW'),
(26, 0, 'Nina Kartika', 'Sleman, DIY', '0812-7788-9900', 'perempuan', 'kasir', '2025-05-31', '1748664082_DSC04352.JPG', 'Menetap', 'Aktif', '$2y$10$UDTrb9agZFk2cer/NC/2VOzgeNteWA8FCdhpqXNcQsgpyTcTp7WR.'),
(27, 0, 'Miftahul Hambari', 'Sumatra Rendang', '0812-2228-2828', 'laki-laki', 'kasir', '2025-06-05', '1749099816_DSC05029.JPG', 'Menetap', 'Aktif', '$2y$10$PTwovHgUJwUplft7BlZLe.uZiXJBuZGvQnuLHqbuWCPq1s74hb2Z.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan`
--

CREATE TABLE `keuangan` (
  `id_keuangan` int(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `saldo` int(255) NOT NULL,
  `nilai_mutasi` int(30) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keuangan`
--

INSERT INTO `keuangan` (`id_keuangan`, `tanggal`, `jenis`, `saldo`, `nilai_mutasi`, `asal`, `keterangan`) VALUES
(25, '2025-06-10 10:46:51', 'Pemasukan', 100000000, 100000000, 'Pemasukan Non-Operasional', 'Sumbangan Bakpia 25'),
(26, '2025-06-11 08:06:54', 'Pengeluaran', 95000000, 5000000, 'Pengeluaran Non-Operasional', 'beli tab'),
(27, '2025-06-11 09:23:59', 'Pemasukan', 95420000, 420000, 'kasir : Griya Bakpia Bantul', 'Transaksi kasir');

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
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_setor_toko`
--

CREATE TABLE `log_setor_toko` (
  `id_log_setor_toko` int(11) NOT NULL,
  `id_toko` int(11) NOT NULL,
  `tanggal_setor` datetime NOT NULL,
  `status_bayar` enum('belum','lunas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_setor_toko`
--

INSERT INTO `log_setor_toko` (`id_log_setor_toko`, `id_toko`, `tanggal_setor`, `status_bayar`) VALUES
(16, 2, '2025-06-02 08:05:02', 'lunas'),
(17, 2, '2025-06-02 08:35:37', 'belum');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stok_outlet`
--

CREATE TABLE `log_stok_outlet` (
  `id_log_stok_outlet` int(11) NOT NULL,
  `id_produk_outlet` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_stok_outlet`
--

INSERT INTO `log_stok_outlet` (`id_log_stok_outlet`, `id_produk_outlet`, `tanggal`, `jumlah`, `keterangan`) VALUES
(3, 10, '2025-06-02 03:19:38', 50, ' ghnumjo'),
(0, 16, '2025-06-12 09:01:01', 10, 'buat baru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stok_toko`
--

CREATE TABLE `log_stok_toko` (
  `id_log_stok_toko` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_toko` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `jenis_perubahan` enum('setor','terjual','return') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text,
  `sisa_stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_stok_toko`
--

INSERT INTO `log_stok_toko` (`id_log_stok_toko`, `id_produk`, `id_toko`, `tanggal`, `jenis_perubahan`, `jumlah`, `keterangan`, `sisa_stok`) VALUES
(24, 79, 2, '2025-06-02 01:00:09', 'setor', 20, NULL, 20),
(25, 79, 2, '2025-06-02 01:02:19', 'setor', 10, NULL, 30),
(26, 79, 2, '2025-06-02 01:05:02', 'setor', 20, NULL, 20),
(27, 79, 2, '2025-06-02 01:06:14', 'terjual', 10, NULL, 10),
(28, 79, 2, '2025-06-02 01:06:14', 'return', 0, NULL, 10),
(29, 79, 2, '2025-06-02 01:35:37', 'setor', 5, NULL, 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `outlet`
--

CREATE TABLE `outlet` (
  `id_outlet` int(11) NOT NULL,
  `nama_outlet` varchar(50) NOT NULL,
  `jenis_outlet` enum('Sendiri','Luar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `outlet`
--

INSERT INTO `outlet` (`id_outlet`, `nama_outlet`, `jenis_outlet`) VALUES
(1, 'Griya Bakpia Jogja', 'Sendiri'),
(2, 'Griya Bakpia Bantul', 'Sendiri');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_toko`
--

CREATE TABLE `pembayaran_toko` (
  `id_pembayaran_toko` int(11) NOT NULL,
  `id_log_setor_toko` int(11) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `jumlah_uang` int(11) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pembayaran_toko`
--

INSERT INTO `pembayaran_toko` (`id_pembayaran_toko`, `id_log_setor_toko`, `tanggal_bayar`, `jumlah_uang`, `keterangan`) VALUES
(2, 16, '2025-06-02 08:06:14', 300000, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemilik`
--

CREATE TABLE `pemilik` (
  `id_pemilik` int(11) NOT NULL,
  `nama_pemilik` varchar(50) NOT NULL,
  `jenis_pemilik` enum('sendiri','penitip') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pemilik`
--

INSERT INTO `pemilik` (`id_pemilik`, `nama_pemilik`, `jenis_pemilik`) VALUES
(1, 'Griya Bakpia', 'sendiri'),
(2, 'Peyek Jogja', 'penitip'),
(61, 'Nugrohoo', 'sendiri'),
(62, 'JAncook', 'sendiri');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_pemilik` int(11) NOT NULL,
  `nama_produk` varchar(50) NOT NULL,
  `harga_default` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_pemilik`, `nama_produk`, `harga_default`) VALUES
(49, 1, 'Bakpia Kacang Hijau Premium', 42000),
(50, 1, 'Bakpia Kacang Hijau Basah', 35000),
(51, 1, 'Bakpia Kacang Hijau Premium', 42000),
(52, 1, 'Bakpia Kacang Hijau Basah', 35000),
(59, 1, 'Bakpia Keju Premium', 42000),
(60, 1, 'Bakpia Keju Basah', 30000),
(61, 1, 'Bakpia Coklat Premium', 42000),
(64, 1, 'Bakpia Coklat Basah', 30000),
(69, 1, 'Bakpia Matcha', 45000),
(70, 1, 'Bakpia Matcha Premium', 50000),
(71, 1, 'Bakpia Tiramisu', 42000),
(72, 1, 'Bakpia Tiramisu Premium', 50000),
(73, 1, 'Bakpia HIjau Hijauan', 42000),
(74, 1, 'Bakpia HIjau Hijauan Premium', 42000),
(75, 1, 'Bakpia Keju Premium', 42000),
(76, 1, 'Bakpia Keju Basah', 30000),
(77, 1, 'Bakpia Coklat Premium', 42000),
(79, 61, 'Bakpiya Kontol', 20000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_outlet`
--

CREATE TABLE `produk_outlet` (
  `id_produk_outlet` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_outlet` int(11) DEFAULT NULL,
  `harga_outlet` int(11) DEFAULT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_outlet`
--

INSERT INTO `produk_outlet` (`id_produk_outlet`, `id_produk`, `id_outlet`, `harga_outlet`, `stok`) VALUES
(15, 52, 2, 42000, 0),
(16, 51, 2, 42000, 10),
(17, 60, 2, 42000, 0),
(18, 59, 2, 42000, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_toko`
--

CREATE TABLE `produk_toko` (
  `id_produk_toko` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_toko` int(11) NOT NULL,
  `harga_toko` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `jumlah_terjual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_toko`
--

INSERT INTO `produk_toko` (`id_produk_toko`, `id_produk`, `id_toko`, `harga_toko`, `stok`, `jumlah_terjual`) VALUES
(36, 79, 2, 30000, 15, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `titipan_toko`
--

CREATE TABLE `titipan_toko` (
  `id_titipan_toko` int(11) NOT NULL,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lunas` enum('true','false') NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `id_pemilik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `titipan_toko`
--

INSERT INTO `titipan_toko` (`id_titipan_toko`, `waktu`, `lunas`, `keterangan`, `id_pemilik`) VALUES
(37, '2025-06-09 10:15:26', 'true', 'setor 1', 3),
(38, '2025-06-09 10:16:29', 'true', 'setor 2', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `nama_toko` varchar(250) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id_toko`, `nama_toko`, `status`) VALUES
(1, 'toko oke', 'aktif'),
(2, 'toko pentol', 'aktif'),
(6, 'toko bb', 'aktif');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_kasir`
--

CREATE TABLE `transaksi_kasir` (
  `id_transaksi_kasir` varchar(50) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `total_diskon` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `jumlah_kembalian` int(11) NOT NULL,
  `waktu_transaksi` date NOT NULL,
  `catatan` text NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `metode_bayar` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi_kasir`
--

INSERT INTO `transaksi_kasir` (`id_transaksi_kasir`, `id_kasir`, `total_harga`, `diskon`, `total_diskon`, `jumlah_bayar`, `jumlah_kembalian`, `waktu_transaksi`, `catatan`, `id_outlet`, `metode_bayar`) VALUES
('TR250609101547', 1, 1200000, 0, 1200000, 1200000, 0, '2025-06-09', '', 1, 'cash'),
('TR250609101748', 1, 1000000, 0, 1000000, 1000000, 0, '2025-06-09', '', 1, 'cash'),
('TR250611092359', 8, 420000, 0, 420000, 500000, 80000, '2025-06-11', '', 2, 'cash');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `detail_pembayaran_toko`
--
ALTER TABLE `detail_pembayaran_toko`
  ADD PRIMARY KEY (`id_detail_pembayaran_toko`);

--
-- Indeks untuk tabel `detail_setor_toko`
--
ALTER TABLE `detail_setor_toko`
  ADD PRIMARY KEY (`id_detail_setor_toko`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
