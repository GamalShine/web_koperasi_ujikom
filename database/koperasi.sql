-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jun 2025 pada 23.39
-- Versi server: 10.1.32-MariaDB
-- Versi PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `alamat`, `no_telepon`, `email`, `created_at`) VALUES
(1, 'Budi Santoso', 'Jl. Merdeka No. 10, Jakarta', '081234567890', 'budi@gmail.com', '2025-06-15 04:45:19'),
(2, 'Ani Wijaya', 'Jl. Sudirman No. 25, Bandung', '082345678901', 'ani@yahoo.com', '2025-06-15 04:45:19'),
(3, 'Citra Dewi', 'Jl. Gatot Subroto No. 5, Surabaya', '083456789012', 'citra@gmail.com', '2025-06-15 04:45:19'),
(4, 'Dodi Pratama', 'Jl. Thamrin No. 15, Medan', '084567890123', 'dodi@outlook.com', '2025-06-15 04:45:19'),
(5, 'Eka Putri', 'Jl. Diponegoro No. 30, Yogyakarta', '08567890123', 'eka@gmail.com', '2025-06-15 04:45:19'),
(7, 'Gamal', 'Jl. Habib Novel', '08312483485', NULL, '2025-06-15 11:24:44'),
(8, 'Subarta', 'Jl. Pengasinan 3 Timur', '091293719237', NULL, '2025-06-15 17:52:43'),
(9, 'Subarta', 'Jl. Pengasinan 3 Timur', '091293719237', NULL, '2025-06-15 17:55:19'),
(11, 'Subroto', 'Jl. Mashuri 2 Kelimpunan Jogja', '08424124124', NULL, '2025-06-15 18:29:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaction`
--

CREATE TABLE `detail_transaction` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_transaction`
--

INSERT INTO `detail_transaction` (`id_detail`, `id_transaksi`, `id_item`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 1, '3000000.00', '3000000.00'),
(2, 1, 7, 2, '320000.00', '640000.00'),
(3, 1, 10, 1, '280000.00', '280000.00'),
(4, 1, 8, 1, '380000.00', '380000.00'),
(5, 1, 9, 1, '650000.00', '650000.00'),
(6, 1, 4, 1, '2200000.00', '2200000.00'),
(8, 3, 3, 1, '3800000.00', '3800000.00'),
(9, 3, 6, 1, '4200000.00', '4200000.00'),
(10, 3, 5, 1, '3000000.00', '3000000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `nama_item` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id_item`, `nama_item`, `kategori`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `created_at`) VALUES
(1, 'Televisi LED 32 inch', 'Elektronik', '2500000.00', '3000000.00', 15, 'unit', '2025-06-15 04:45:19'),
(2, 'Kulkas 2 Pintu', 'Elektronik', '4500000.00', '5200000.00', 8, 'unit', '2025-06-15 04:45:19'),
(3, 'Mesin Cuci 8kg', 'Elektronik', '3200000.00', '3800000.00', 10, 'unit', '2025-06-15 04:45:19'),
(4, 'Sofa 3 Seat', 'Furniture', '1800000.00', '2200000.00', 12, 'set', '2025-06-15 04:45:19'),
(5, 'Meja Makan 6 Kursi', 'Furniture', '2500000.00', '3000000.00', 7, 'set', '2025-06-15 04:45:19'),
(6, 'Kasur Springbed', 'Furniture', '3500000.00', '4200000.00', 5, 'unit', '2025-06-15 04:45:19'),
(7, 'Panci Stainless', 'Dapur', '250000.00', '320000.00', 30, 'buah', '2025-06-15 04:45:19'),
(8, 'Blender', 'Dapur', '3000.00', '380000.00', 25, 'unit', '2025-06-15 04:45:19'),
(9, 'Kompor Gas', 'Dapur', '500000.00', '650000.00', 18, 'unit', '2025-06-15 04:45:19'),
(10, 'Piring Set', 'Dapur', '200000.00', '280000.00', 40, 'set', '2025-06-15 04:45:19'),
(12, 'Baju Atasan', 'Pakaian', '50000.00', '100000.00', 1000, 'buah', '2025-06-15 11:59:10'),
(13, 'Bay Blade', 'Lainnya', '10000.00', '20000.00', 20000, 'PCS', '2025-06-15 18:14:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','petugas') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `level`, `created_at`) VALUES
(1, 'Admin Utama', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-06-15 04:45:19'),
(2, 'Petugas 1', 'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', '2025-06-15 04:45:19'),
(3, 'Petugas 2', 'petugas2', '$2y$10$Qu/ZxtL0nG0qslJsmrM6a.H0D7tLIvciuvmwCMuglmRzoEmdxd7rG', 'petugas', '2025-06-15 04:45:19'),
(4, 'Irfan', 'Irfan', '$2y$10$34am9qLs9ZLFHDeAxeZnxOVILFYxPmufta5dxH9ozZIxxrn24OyXq', 'petugas', '2025-06-15 12:28:56'),
(5, 'Gamal', 'Gamal', '$2y$10$LuwD84I4NGv77X84aQtAx.hyZBUcPvrGfnRkNJ3nknlyyfdCTGpdW', 'admin', '2025-06-15 12:29:15'),
(6, 'Rio', 'Rio', '$2y$10$MqNskVW14c/s5nWTdxm1VOfE4QCrg46brQyV47FrVOkjVgzSn.C7u', 'admin', '2025-06-15 12:29:43'),
(7, 'Gofur', 'gofur', '$2y$10$jD7eWS3Qnn5Cb1WzvS/u1.N73AVqaTfSmNKTQF5g6BnrFKPzTVNFq', 'petugas', '2025-06-15 18:00:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id_sales` int(11) NOT NULL,
  `nama_sales` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`id_sales`, `nama_sales`, `alamat`, `no_telepon`, `email`, `created_at`) VALUES
(1, 'Fajar Hermawan', 'Jl. Pahlawan No. 12, Jakarta', '08678901234', 'fajar1@gmail.com', '2025-06-15 04:45:19'),
(2, 'Gita Nurul', 'Jl. Asia Afrika No. 8, Bandung', '087890123456', 'gita@yahoo.com', '2025-06-15 04:45:19'),
(3, 'Hendra Kurniawan', 'Jl. Pemuda No. 17, Surabaya', '088901234567', 'hendra@gmail.com', '2025-06-15 04:45:19'),
(4, 'Indah Permata', 'Jl. Veteran No. 22, Medan', '089012345678', 'indah@outlook.com', '2025-06-15 04:45:19'),
(5, 'Joko Susilo', 'Jl. Malioboro No. 5, Yogyakarta', '081123456789', 'joko@gmail.com', '2025-06-15 04:45:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction`
--

CREATE TABLE `transaction` (
  `id_transaksi` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `id_sales` int(11) DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status_pembayaran` enum('lunas','cicilan','belum lunas') NOT NULL,
  `catatan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaction`
--

INSERT INTO `transaction` (`id_transaksi`, `id_customer`, `id_petugas`, `id_sales`, `tanggal_transaksi`, `total_harga`, `status_pembayaran`, `catatan`, `created_at`) VALUES
(1, 1, 2, 1, '2023-01-15', '6800000.00', 'lunas', 'Pembelian tunai', '2025-06-15 04:45:19'),
(3, 3, 5, 3, '2023-02-05', '2000.00', '', 'Belum dibayar', '2025-06-15 04:45:19'),
(8, 2, 1, 2, '2025-06-15', '50000.00', 'lunas', 'Lunas', '2025-06-15 11:20:55'),
(9, 2, 5, 2, '2025-06-15', '50000.00', 'lunas', 'Lunas', '2025-06-15 11:21:35'),
(10, 2, 5, 2, '2025-06-15', '500000.00', '', 'JAJAN', '2025-06-15 11:21:55'),
(11, 1, 5, 1, '2025-06-15', '1000000.00', 'cicilan', 'OKE', '2025-06-15 11:41:13'),
(15, 9, 5, 5, '2025-06-15', '1000000.00', 'lunas', 'asdasd', '2025-06-15 18:50:30'),
(17, 1, 5, 1, '2025-06-15', '123123.00', '', 'gaada', '2025-06-15 19:03:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `detail_transaction`
--
ALTER TABLE `detail_transaction`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_item` (`id_item`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id_sales`);

--
-- Indeks untuk tabel `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_petugas` (`id_petugas`),
  ADD KEY `id_sales` (`id_sales`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `detail_transaction`
--
ALTER TABLE `detail_transaction`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id_sales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaction`
--
ALTER TABLE `detail_transaction`
  ADD CONSTRAINT `detail_transaction_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaction` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaction_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`);

--
-- Ketidakleluasaan untuk tabel `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`id_sales`) REFERENCES `sales` (`id_sales`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
