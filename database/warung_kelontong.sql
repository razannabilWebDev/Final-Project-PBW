-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 01:58 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warung_kelontong`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `stok`, `harga_beli`, `harga_jual`, `tanggal_masuk`, `id_supplier`) VALUES
(1, 'Indomie Goreng', 'Makanan', 100, 2500, 3500, '2026-05-01', 1),
(2, 'Beras 5Kg', 'Sembako', 50, 60000, 70000, '2026-05-02', 2),
(3, 'Gula Pasir 1Kg', 'Sembako', 80, 12000, 15000, '2026-05-03', 3),
(4, 'Minyak Goreng 1L', 'Sembako', 70, 15000, 18000, '2026-05-04', 4),
(5, 'Teh Kotak', 'Minuman', 90, 3000, 5000, '2026-05-05', 5),
(6, 'Kopi Sachet', 'Minuman', 120, 1500, 2500, '2026-05-06', 6),
(7, 'Sabun Mandi', 'Kebutuhan', 60, 4000, 6000, '2026-05-07', 7),
(8, 'Shampoo', 'Kebutuhan', 40, 10000, 13000, '2026-05-08', 8),
(9, 'Pasta Gigi', 'Kebutuhan', 55, 7000, 10000, '2026-05-09', 9),
(10, 'Susu UHT', 'Minuman', 75, 5000, 7500, '2026-05-10', 10);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_barang`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 10, 35000),
(2, 2, 2, 1, 70000),
(3, 3, 3, 3, 45000),
(4, 4, 4, 3, 54000),
(5, 5, 5, 6, 30000),
(6, 6, 6, 20, 50000),
(7, 7, 7, 4, 24000),
(8, 8, 8, 4, 52000),
(9, 9, 9, 5, 50000),
(10, 10, 10, 10, 75000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `poin_member` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat`, `no_hp`, `poin_member`) VALUES
(1, 'Andi Saputra', 'Bandung', '081111111111', 10),
(2, 'Budi Santoso', 'Jakarta', '081111111112', 20),
(3, 'Citra Lestari', 'Bekasi', '081111111113', 15),
(4, 'Dewi Anggraini', 'Bogor', '081111111114', 5),
(5, 'Eko Prasetyo', 'Depok', '081111111115', 12),
(6, 'Fajar Hidayat', 'Cimahi', '081111111116', 30),
(7, 'Gina Marlina', 'Garut', '081111111117', 18),
(8, 'Hendra Wijaya', 'Tasikmalaya', '081111111118', 22),
(9, 'Intan Permata', 'Cirebon', '081111111119', 8),
(10, 'Joko Susilo', 'Sukabumi', '081111111120', 16);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `no_telepon`, `email`) VALUES
(1, 'PT Sumber Makmur', 'Bandung', '081234567001', 'sumbermakmur@gmail.com'),
(2, 'CV Jaya Abadi', 'Jakarta', '081234567002', 'jayaabadi@gmail.com'),
(3, 'PT Maju Bersama', 'Bekasi', '081234567003', 'majubersama@gmail.com'),
(4, 'CV Berkah Jaya', 'Bogor', '081234567004', 'berkahjaya@gmail.com'),
(5, 'PT Sejahtera', 'Depok', '081234567005', 'sejahtera@gmail.com'),
(6, 'CV Sentosa', 'Cimahi', '081234567006', 'sentosa@gmail.com'),
(7, 'PT Nusantara', 'Garut', '081234567007', 'nusantara@gmail.com'),
(8, 'CV Mitra Usaha', 'Tasikmalaya', '081234567008', 'mitrausaha@gmail.com'),
(9, 'PT Sinar Baru', 'Cirebon', '081234567009', 'sinarbaru@gmail.com'),
(10, 'CV Mandiri', 'Sukabumi', '081234567010', 'mandiri@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `id_pelanggan`, `total_harga`) VALUES
(1, '2026-05-01', 1, 35000),
(2, '2026-05-02', 2, 70000),
(3, '2026-05-03', 3, 45000),
(4, '2026-05-04', 4, 55000),
(5, '2026-05-05', 5, 30000),
(6, '2026-05-06', 6, 80000),
(7, '2026-05-07', 7, 25000),
(8, '2026-05-08', 8, 60000),
(9, '2026-05-09', 9, 50000),
(10, '2026-05-10', 10, 75000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('admin','kasir') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin1', 'admin123', 'admin'),
(2, 'admin2', 'admin123', 'admin'),
(3, 'kasir1', 'kasir123', 'kasir'),
(4, 'kasir2', 'kasir123', 'kasir'),
(5, 'kasir3', 'kasir123', 'kasir'),
(6, 'kasir4', 'kasir123', 'kasir'),
(7, 'kasir5', 'kasir123', 'kasir'),
(8, 'kasir6', 'kasir123', 'kasir'),
(9, 'staff1', 'staff123', 'kasir'),
(10, 'staff2', 'staff123', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
