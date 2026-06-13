-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2026 at 08:29 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `id_barang` int NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `id_supplier` int DEFAULT NULL,
  `harga_beli` int DEFAULT NULL,
  `harga_jual` int DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `tanggal_ditambahkan` date DEFAULT NULL,
  `status_barang` enum('aktif','nonaktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `id_supplier`, `harga_beli`, `harga_jual`, `satuan`, `tanggal_ditambahkan`, `status_barang`) VALUES
(1, 'Indomie Goreng', 'Makanan', NULL, 2500, 3500, 'pcs', '2026-05-01', 'aktif'),
(2, 'Beras 5Kg', 'Sembako', NULL, 60000, 70000, 'karung', '2026-05-02', 'aktif'),
(3, 'Gula Pasir 1Kg', 'Sembako', NULL, 12000, 15000, 'pcs', '2026-05-03', 'aktif'),
(4, 'Minyak Goreng 1L', 'Sembako', NULL, 15000, 18000, 'botol', '2026-05-04', 'aktif'),
(5, 'Teh Kotak', 'Minuman', NULL, 3000, 5000, 'pcs', '2026-05-05', 'nonaktif'),
(6, 'Kopi Sachet', 'Minuman', NULL, 1500, 2500, 'pcs', '2026-05-06', 'aktif'),
(7, 'Sabun Mandi', 'Kebutuhan', NULL, 4000, 6000, 'pcs', '2026-05-07', 'aktif'),
(8, 'Shampoo', 'Kebutuhan', NULL, 10000, 13000, 'botol', '2026-05-08', 'aktif'),
(9, 'Pasta Gigi', 'Kebutuhan', NULL, 7000, 10000, 'pcs', '2026-05-09', 'aktif'),
(10, 'Susu UHT', 'Minuman', NULL, 5000, 7999, 'kotak', '2026-05-10', 'aktif'),
(16, 'susu kucing', 'Minuman', NULL, 10000, 15000, 'pcs', '2026-05-25', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_detail_pembelian` int NOT NULL,
  `id_pembelian` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_beli` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_detail_pembelian`, `id_pembelian`, `id_barang`, `jumlah`, `harga_beli`, `subtotal`) VALUES
(1, 1, 1, 100, 2500, 250000),
(2, 2, 2, 5, 60000, 300000),
(3, 3, 3, 10, 15000, 150000),
(4, 4, 4, 10, 20000, 200000),
(5, 5, 5, 60, 3000, 180000),
(6, 6, 6, 100, 2200, 220000),
(7, 7, 7, 45, 6000, 270000),
(8, 8, 8, 15, 13000, 195000),
(9, 9, 9, 31, 10000, 310000),
(10, 10, 10, 80, 5000, 400000),
(11, 11, 1, 12, 5000, 60000),
(12, 12, 1, 1, 2000, 2000),
(13, 13, 1, 1, 5000, 5000),
(14, 14, 1, 1, 5000, 5000),
(15, 15, 1, 1, 12, 12);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL,
  `id_transaksi` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_jual` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_barang`, `jumlah`, `harga_jual`, `subtotal`) VALUES
(1, 1, 1, 10, 3500, 35000),
(2, 2, 2, 1, 70000, 70000),
(3, 3, 3, 3, 15000, 45000),
(4, 4, 4, 3, 18000, 54000),
(5, 5, 5, 6, 5000, 30000),
(6, 6, 6, 20, 2500, 50000),
(7, 7, 7, 4, 6000, 24000),
(8, 8, 8, 4, 13000, 52000),
(9, 9, 9, 5, 10000, 50000),
(10, 10, 10, 10, 7500, 75000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL,
  `poin_member` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_supplier` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `total_pembelian` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `tanggal`, `id_supplier`, `id_user`, `total_pembelian`) VALUES
(1, '2026-05-19 19:10:39', 1, 1, 250000),
(2, '2026-05-19 19:10:39', 2, 1, 300000),
(3, '2026-05-19 19:10:39', 3, 1, 150000),
(4, '2026-05-19 19:10:39', 4, 2, 200000),
(5, '2026-05-19 19:10:39', 5, 2, 180000),
(6, '2026-05-19 19:10:39', 6, 1, 220000),
(7, '2026-05-19 19:10:39', 7, 2, 270000),
(8, '2026-05-19 19:10:39', 8, 1, 190000),
(9, '2026-05-19 19:10:39', 9, 2, 310000),
(10, '2026-05-19 19:10:39', 10, 1, 400000),
(11, '2026-05-25 17:24:46', 1, 1, 60000),
(12, '2026-05-25 17:30:21', 1, 1, 2000),
(13, '2026-05-25 17:40:08', 1, 1, 5000),
(14, '2026-05-25 17:41:42', 1, 1, 5000),
(15, '2026-05-25 17:41:50', 1, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id_stok` int NOT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah_stok` int DEFAULT NULL,
  `stok_minimum` int DEFAULT NULL,
  `terakhir_diupdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id_stok`, `id_barang`, `jumlah_stok`, `stok_minimum`, `terakhir_diupdate`) VALUES
(1, 1, 102, 10, '2026-05-19 19:10:39'),
(2, 2, 50, 5, '2026-05-19 19:10:39'),
(3, 3, 80, 10, '2026-05-19 19:10:39'),
(4, 4, 70, 10, '2026-05-19 19:10:39'),
(5, 5, 90, 15, '2026-05-19 19:10:39'),
(6, 6, 120, 20, '2026-05-19 19:10:39'),
(7, 7, 60, 10, '2026-05-19 19:10:39'),
(8, 8, 40, 5, '2026-05-19 19:10:39'),
(9, 9, 55, 5, '2026-05-19 19:10:39'),
(10, 10, 75, 10, '2026-05-19 19:10:39'),
(11, 16, 9, 5, '2026-05-25 20:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int NOT NULL,
  `nama_supplier` varchar(100) DEFAULT NULL,
  `alamat` text,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `no_telepon`, `email`) VALUES
(1, 'PT Sumber Makmur', 'Bandung', '081234567001', 'supplier1@gmail.com'),
(2, 'CV Jaya Abadi', 'Jakarta', '081234567002', 'supplier2@gmail.com'),
(3, 'PT Maju Bersama', 'Bekasi', '081234567003', 'supplier3@gmail.com'),
(4, 'CV Berkah Jaya', 'Bogor', '081234567004', 'supplier4@gmail.com'),
(5, 'PT Sejahtera', 'Depok', '081234567005', 'supplier5@gmail.com'),
(6, 'CV Sentosa', 'Cimahi', '081234567006', 'supplier6@gmail.com'),
(7, 'PT Nusantara', 'Garut', '081234567007', 'supplier7@gmail.com'),
(8, 'CV Mitra Usaha', 'Tasikmalaya', '081234567008', 'supplier8@gmail.com'),
(9, 'PT Sinar Baru', 'Cirebon', '081234567009', 'supplier9@gmail.com'),
(10, 'CV Mandiri', 'Sukabumi', '081234567010', 'supplier10@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `bayar` int DEFAULT NULL,
  `kembalian` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `id_pelanggan`, `id_user`, `total_harga`, `bayar`, `kembalian`) VALUES
(1, '2026-05-18 19:10:39', 1, 3, 35000, 50000, 15000),
(2, '2026-05-19 19:10:39', 2, 4, 70000, 100000, 30000),
(3, '2026-05-17 19:10:39', 3, 5, 45000, 50000, 5000),
(4, '2026-05-16 19:10:39', 4, 6, 54000, 60000, 6000),
(5, '2026-05-19 19:10:39', 5, 7, 30000, 50000, 20000),
(6, '2026-05-19 19:10:39', 6, 8, 50000, 100000, 50000),
(7, '2026-05-19 19:10:39', 7, 9, 24000, 50000, 26000),
(8, '2026-05-19 19:10:39', 8, 10, 52000, 100000, 48000),
(9, '2026-05-19 19:10:39', 9, 3, 50000, 100000, 50000),
(10, '2026-05-19 19:10:39', 10, 4, 75000, 100000, 25000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','kasir') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `username`, `password`, `role`) VALUES
(1, 'razannabilannadif@gmail.com', 'razan', '$2y$10$YGyeLtUWIdABNkBM/1.n/O3vMGxagCP0t19kPHwGf1Vklu0t2LsGK', 'admin'),
(2, 'admin2@gmail.com', 'admin2', 'admin123', 'admin'),
(3, '2410631170101@student.unsika.ac.id', 'nabil', '$2y$10$0l3Xj5cvoSalcc2N3xmTFOjl2RTT1vhfgeysJgslMEMJCpSchOoDa', 'kasir'),
(4, 'kasir2@gmail.com', 'kasir2', 'kasir123', 'kasir'),
(5, 'kasir3@gmail.com', 'kasir3', 'kasir123', 'kasir'),
(6, 'kasir4@gmail.com', 'kasir4', 'kasir123', 'kasir'),
(7, 'kasir5@gmail.com', 'kasir5', 'kasir123', 'kasir'),
(8, 'kasir6@gmail.com', 'kasir6', 'kasir123', 'kasir'),
(9, 'staff1@gmail.com', 'staff1', 'staff123', 'kasir'),
(10, 'staff2@gmail.com', 'staff2', 'staff123', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `fk_barang_supplier` (`id_supplier`);

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_detail_pembelian`),
  ADD KEY `id_pembelian` (`id_pembelian`),
  ADD KEY `id_barang` (`id_barang`);

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
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id_stok`),
  ADD KEY `id_barang` (`id_barang`);

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
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_user` (`id_user`);

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
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_detail_pembelian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id_stok` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
