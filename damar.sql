-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2026 at 02:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `damar`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` varchar(50) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
(1, 'DW-177885842828', 163, 1, 297500.00),
(2, 'DW-177885861499', 170, 1, 297500.00),
(3, 'DW-177885872284', 164, 1, 297500.00),
(4, 'DW-177977243614', 166, 1, 297500.00),
(5, 'DW-177977257878', 162, 2, 297500.00),
(6, 'DW-6a454d3d1edf1', 162, 2, 297500.00),
(7, 'DW-6a56f0f0bea18', 170, 1, 2665000.00),
(8, 'DW-6a56f0f0bea18', 160, 1, 1160900.00),
(9, 'DW-6a56f3c389bba', 171, 1, 2535000.00),
(10, 'DW-6a56f3c389bba', 169, 1, 600000.00),
(11, 'DW-6a57814e46871', 171, 1, 2535000.00),
(12, 'DW-6a57814e46871', 169, 1, 600000.00),
(13, 'DW-6a57832515f4e', 170, 1, 2665000.00),
(14, 'DW-6a57832515f4e', 169, 1, 600000.00),
(15, 'DW-6a5da26b442b4', 171, 1, 2535000.00),
(16, 'DW-6a5da26b442b4', 165, 1, 297500.00);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Mesin'),
(2, 'Sistem Pengereman'),
(3, 'Kelistrikan'),
(4, 'Breket'),
(5, 'Compresor'),
(6, 'Condensor'),
(7, 'Daun Kipas'),
(8, 'Evaporator'),
(9, 'Expansion'),
(10, 'Filter Cabin'),
(11, 'Filter Dryer'),
(12, 'Magnet Cluth'),
(13, 'Motor Blower'),
(14, 'Motor Fan'),
(15, 'Lubricant'),
(16, 'Selang High Pressure'),
(17, 'Thermostat'),
(18, 'Tutup Radiator'),
(19, 'Dryer'),
(20, 'Coolant'),
(21, 'Compresor'),
(22, 'Daun Kipas'),
(23, 'Magnet Cluth'),
(24, 'Condensor'),
(25, 'Evaporator'),
(26, 'Expansion'),
(27, 'Filter Cabin'),
(28, 'Filter Dryer'),
(29, 'Motor Blower'),
(30, 'Motor Fan'),
(31, 'Lubricant'),
(32, 'Selang High Pressure'),
(33, 'Thermostat'),
(34, 'Tutup Radiator'),
(35, 'Dryer'),
(36, 'Coolant');

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_area`
--

CREATE TABLE `ongkir_area` (
  `id_area` int(11) NOT NULL,
  `nama_area` varchar(100) NOT NULL,
  `harga_dasar` decimal(10,2) NOT NULL,
  `batas_berat_gram` int(11) NOT NULL,
  `harga_per_kg_tambahan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ongkir_area`
--

INSERT INTO `ongkir_area` (`id_area`, `nama_area`, `harga_dasar`, `batas_berat_gram`, `harga_per_kg_tambahan`) VALUES
(1, 'Jabodetabek', 15000.00, 2000, 5000.00),
(2, 'Jawa Barat & Banten', 20000.00, 2000, 7000.00),
(3, 'Jawa Tengah, DIY, & Jawa Timur', 30000.00, 1000, 10000.00),
(4, 'Luar Pulau Jawa', 50000.00, 1000, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `id_peran` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `id_peran`, `nama`, `email`, `kata_sandi`, `no_telepon`, `id_area`, `alamat`, `dibuat_pada`) VALUES
(1, 1, 'Owner AutoParts', 'owner@damar.com', '$2y$10$fWbEF01yKOlywksjDhpzQehryudm4ZWFNzXZoes6aqRxzyH0lsIOa', '081100000001', NULL, NULL, '2026-05-13 13:39:23'),
(2, 2, 'Admin System', 'admin@damar.com', '$2y$10$NF8vdKdGUcPex8Xtwjce8OdWtLwKuT6el6u865WTNzN1tZTRnTaBW', '081100000002', NULL, NULL, '2026-05-13 13:39:24'),
(3, 3, 'awikawok', 'okeegayn@gmail.com', '$2y$10$.nO9SiqFV3fXWWN.vWH//.w6vykPRdTIzL0yJgfN1XRohlkNw97tS', '089601582562', 4, 'Jalan jalan aja', '2026-05-13 14:33:08'),
(4, 3, 'wakwaw', 'awokawok@gmail.com', '$2y$10$NNyL3H8OyDWm0WYcQJ4Teux.XH8863yWz9hXebBCOkD463HL2.ali', NULL, NULL, NULL, '2026-05-14 02:19:41'),
(6, 3, 'akmal fauzan', '2212510453@budiluhur.ac.id', '$2y$10$Hf2xmoNhOgWPagpSmfq6PuWfYtUdqTQ0Hgbe5iu.yOHH4oircKH6O', NULL, NULL, NULL, '2026-07-15 13:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `id_peran` int(11) NOT NULL,
  `nama_peran` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`id_peran`, `nama_peran`) VALUES
(2, 'admin'),
(3, 'pelanggan'),
(1, 'pemilik');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_model` int(11) DEFAULT NULL,
  `nomor_suku_cadang` varchar(100) DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default-part.jpg',
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `berat_gram` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `id_model`, `nomor_suku_cadang`, `nama_produk`, `merk`, `deskripsi`, `gambar`, `harga`, `stok`, `berat_gram`) VALUES
(142, 7, NULL, NULL, 'Daun Kipas Extrafan Xtrail, Serena C26', 'Polos', 'Mobil: -. Part No: -', '1784520898_Screenshot 2026-07-20 111447.png', 255000.00, 10, 0),
(143, 7, NULL, NULL, 'Kipas Extrafan Soluna', 'Polos', 'Mobil: -. Part No: -', '1784520815_Screenshot 2026-07-20 111326.png', 255000.00, 7, 0),
(145, 12, NULL, 'DI437390-0130', 'Magnet Cluth Nissan Livina 7PK', 'LAX', 'Mobil: -. Part No: DI437390-0130', '1783395786_Screenshot 2026-07-07 104232.png', 575000.00, 1, 0),
(146, 12, NULL, 'XI247300-9480', 'Magnet Cluth Avanza New 4PK', 'Denso', 'Mobil: -. Part No: XI247300-9480', '1783395558_Screenshot 2026-07-07 103810.png', 520000.00, 11, 0),
(147, 5, NULL, '447300-2410', 'Compresor APV (JK447280-0590) ', 'COOL GEAR', 'Mobil: -. Part No: 447300-2410', '1783350951_Screenshot 2026-07-06 221521.png', 1218750.00, 0, 0),
(148, 12, NULL, 'JK247300-67903D', 'Magnet Cluth 6PK (C0208-058) ', 'YARUKI', 'Mobil: -. Part No: JK247300-67903D', '1783350899_Screenshot 2026-07-06 221421.png', 520000.00, 10, 0),
(149, 12, NULL, 'JK247300-67903D', 'Magnet Cluth Mitsubish Pajero Sport 1PK (MC104-002) ', 'LAX', 'Mobil: -. Part No: JK247300-67903D', '1783350816_Screenshot 2026-07-06 221318.png', 650000.00, 9, 0),
(150, 4, NULL, 'JK247300-67903D', 'Bracket INOVA BENSIN LAMA', 'OEM', 'Mobil: -. Part No: JK247300-67903D', '1783350730_Screenshot 2026-07-06 221146.png', 450000.00, 7, 0),
(151, 4, NULL, 'MC105-005', 'Bracket L300 LAMA', 'POLOS', 'Mobil: -. Part No: MC105-005', '1783350673_Screenshot 2026-07-06 221052.png', 450000.00, 1, 0),
(152, 10, NULL, 'MC105-004', 'Filter cabin Mitsubisi Mirage (145520-3700) ', 'DENSO', 'Mobil: -. Part No: MC105-004', '1783350506_Screenshot 2026-07-06 220809.png', 38000.00, 5, 0),
(153, 22, NULL, 'MC105-009', 'Extrapen Extril', 'OEM', 'Magnetic Clutch 4PK untuk Honda Civic Genio dan Estilo. Menyambungkan putaran kompresor dengan sempurna tanpa selip.', '1783350461_Screenshot 2026-07-06 220718.png', 550000.00, 5, 1200),
(154, 10, NULL, 'MC105-003', 'Filter cabin etios (DI145520-3800) ', 'DENSO', 'Magnetic Clutch 5PK untuk Honda City. Material plat gesek yang kuat dan koil gulungan presisi.', '1783350414_Screenshot 2026-07-06 220624.png', 570000.00, 6, 1250),
(155, 10, NULL, 'MC103-004', 'Filter cabin pajero sport (DI145520-4950) ', 'DENSO', 'Magnetic Clutch 7PK khusus Nissan Grand Livina. Solusi masalah AC sering putus nyambung saat mesin panas.', '1783350338_Screenshot 2026-07-06 220520.png', 58000.00, 4, 1300),
(156, 5, NULL, 'MC103-002', 'Compresor Ford Fiesta', 'DENSO', 'Magnetic Clutch 7PK untuk Datsun Go. Mengembalikan tarikan AC agar kembali normal dan dingin.', '1783350218_Screenshot 2026-07-06 220216.png', 550000.00, 2, 1100),
(157, 35, NULL, 'MC101-012', 'Drieyer CX7 (PSB8969) ', 'DENSO KW', 'Magnetic Clutch 4PK untuk Toyota Yaris New. Tahan panas dan memiliki usia pakai (durability) yang panjang.', '1783350078_Screenshot 2026-07-06 220048.png', 570000.00, 2, 1200),
(158, 27, NULL, 'MC103-001', 'Filter cabin avanza, xenia (145520-2500) ', 'DENSO', 'Magnetic Clutch 7PK untuk Nissan March. Menggantikan pulley lama yang sudah bunyi ngorok atau oblak.', '1783350005_Screenshot 2026-07-06 215947.png', 70000.00, 1, 1150),
(159, 11, NULL, 'MC104-005', 'Drieyer Ertiga (JBC-0406) ', 'STAL', 'Magnetic Clutch 5PK untuk Mitsubishi Pajero Sport. Komponen vital agar AC kembali bekerja secara otomatis.', '1783349960_Screenshot 2026-07-06 215855.png', 600000.00, 2, 1500),
(160, 12, NULL, 'MC104-002', 'Magnet Cluth 6PK (C0208-058) ', 'YARUKI', 'Magnetic Clutch 1PK untuk Mitsubishi Pajero Sport (tipe tertentu). Tahan terhadap suhu ruang mesin yang ekstrim.', '1783349813_Screenshot 2026-07-06 215633.png', 1160900.00, 1, 1500),
(161, 26, NULL, 'SHP-981A', 'Expansion valve avanza/xenia (DI261411-0240', 'DENSO', 'Selang AC High Pressure ukuran 1/2 untuk Honda CRV Generasi 1. Tahan terhadap tekanan freon tinggi agar tidak mudah bocor.', '1783349712_Screenshot 2026-07-06 215435.png', 297500.00, 7, 500),
(162, 5, NULL, 'SHP-982B', 'Compresor Assy Agya, Ayla, Cayla (XI447280-2032) ', 'DENSO', 'Selang AC tekanan tinggi untuk Toyota Grand Veloz. Menggunakan lapisan karet khusus yang tahan lama dan tidak mudah getas.', '1783349632_Screenshot 2026-07-06 215328.png', 2113000.00, 8, 500),
(163, 16, NULL, 'SHP-983C', 'HR-V (MEL) 0925', 'Polos', 'Selang AC High Pressure untuk Honda HRV. Fitting pipa aluminium presisi untuk mencegah kebocoran freon.', '1783349578_Screenshot 2026-07-06 215225.png', 297500.00, 2, 550),
(164, 16, NULL, 'SHP-984D', 'Innova Singel Blower (MEL) 0925', 'Polos', 'Selang AC 1/2 inchi High Pressure untuk All New Avanza. Pemasangan mudah dan langsung pas dengan nepel bawaan.', '1783349287_Screenshot 2026-07-06 214742.png', 297500.00, 2, 500),
(165, 16, NULL, 'SHP-985E', 'Ertiga (MEL) 0925', 'Polos', 'Selang High Pressure sistem AC untuk Suzuki Swift GX. Kualitas setara original equipment.', '1783349234_Screenshot 2026-07-06 214647.png', 297500.00, 0, 450),
(166, 6, NULL, 'SHP-986F', 'Condensor Universal (14X18X20-R134A) ', 'Polos', 'Selang tekanan tinggi AC khusus Toyota Kijang Innova. Menjaga sirkulasi refrigeran berjalan lancar menuju kondensor.', '1783349126_Screenshot 2026-07-06 214509.png', 297500.00, 3, 600),
(167, 5, NULL, 'SHP-987G', 'Compresor Ertiga (XI437230-0060) ', 'COOL GEAR', 'Selang High Pressure untuk Honda Mobilio. Lentur namun sangat kuat menahan tekanan sistem pendingin mobil.', '1783349062_Screenshot 2026-07-06 214402.png', 2035150.00, 1, 550),
(168, 23, NULL, 'SHP-988H', 'Magnet Cluth Honda City 5PK (MC105-003) ', 'POKKA', 'Selang AC High Pressure untuk duet Toyota Rush dan Daihatsu Terios. Solusi selang AC yang sering rembes.', '1783349010_Screenshot 2026-07-06 214307.png', 570000.00, 1, 550),
(169, 12, NULL, 'SHP-989I', 'Magnet Cluth PW Golf (7237) ', 'POKKA', 'Selang AC ukuran 1/2 High Pressure untuk Suzuki Ertiga lama maupun baru.', '1783348850_Screenshot 2026-07-06 214028.png', 600000.00, -2, 500),
(170, 21, NULL, 'SHP-990J', 'Compresor Only Grand Max (XI447110-3690) ', 'DENSO', 'Selang AC bagian High Pressure untuk Honda Jazz GD3 (Lama). Tahan gesekan di ruang mesin yang sempit.', '1783348781_Screenshot 2026-07-06 213915.png', 2665000.00, -1, 450),
(171, 21, NULL, 'SHP-991K', 'Compresor Only/Assy Kijang Diesel (JK447200-1940) ', 'DENSO', 'Selang khusus tekanan tinggi untuk Toyota Innova varian Single Blower (tipe J/Bisnis).', '1783348696_Screenshot 2026-07-06 213733.png', 2535000.00, 2, 500);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(50) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `id_area` int(11) DEFAULT NULL,
  `tgl_transaksi` datetime NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `ongkir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status_transaksi` enum('pending','dibayar','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  `metode_bayar` varchar(50) DEFAULT NULL,
  `alamat_pengiriman` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `nomor_resi` varchar(50) DEFAULT NULL,
  `xendit_invoice_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_pengguna`, `id_area`, `tgl_transaksi`, `total_bayar`, `ongkir`, `status_transaksi`, `metode_bayar`, `alamat_pengiriman`, `catatan`, `nomor_resi`, `xendit_invoice_url`) VALUES
('DW-177885842828', 3, NULL, '2026-05-15 22:20:28', 297500.00, 0.00, 'dikirim', 'Xendit', 'Jalan jalan aja', '', 'awokawok11', 'https://checkout-staging.xendit.co/web/6a0739bd0168694c2c2f7e42'),
('DW-177885861499', 3, NULL, '2026-05-15 22:23:34', 297500.00, 0.00, 'dikirim', 'Xendit', 'Jalan jalan aja', '', 'jnejnejnejn1111', 'https://checkout-staging.xendit.co/web/6a073a77b30934f497ea47ee'),
('DW-177885872284', 3, NULL, '2026-05-15 22:25:22', 297500.00, 0.00, 'dikirim', 'Xendit', 'Jalan jalan aja', '', 'jnejnejne1111', 'https://checkout-staging.xendit.co/web/6a073ae30168694c2c2f7fc6'),
('DW-177977243614', 3, NULL, '2026-05-26 12:13:56', 297500.00, 0.00, 'pending', 'Xendit', 'Jalan jalan aja', '', NULL, 'https://checkout-staging.xendit.co/web/6a152c16d14bf94c48d6edb9'),
('DW-177977257878', 3, NULL, '2026-05-26 12:16:18', 595000.00, 0.00, 'dikirim', 'QR_CODE', 'Jalan jalan aja', '', 'kurirmandiri123', 'https://checkout-staging.xendit.co/web/6a152ca3cfc0f819cd7f8987'),
('DW-6a454d3d1edf1', 3, NULL, '2026-07-02 00:24:13', 645000.00, 50000.00, 'selesai', 'QR_CODE', 'Jalan jalan aja', '', '123', 'https://checkout-staging.xendit.co/web/6a454d3f71a9a0c319ad3bf3'),
('DW-6a56f0f0bea18', 5, NULL, '2026-07-15 09:31:12', 3840900.00, 15000.00, 'dibayar', 'QR_CODE', 'jl kayu gede', '', NULL, 'https://checkout-staging.xendit.co/web/6a56f0f1013ccf3668785d65'),
('DW-6a56f3c389bba', 5, NULL, '2026-07-15 09:43:15', 3150000.00, 15000.00, 'dibayar', 'QR_CODE', 'jl kayu gede', '', NULL, 'https://checkout-staging.xendit.co/web/6a56f3c4013ccf366878616a'),
('DW-6a57814e46871', 5, NULL, '2026-07-15 19:47:10', 3150000.00, 15000.00, 'dibayar', 'QR_CODE', 'jl kayu gede', '', NULL, 'https://checkout-staging.xendit.co/web/6a57814f5914e06151ef780f'),
('DW-6a57832515f4e', 5, NULL, '2026-07-15 19:55:01', 3280000.00, 15000.00, 'selesai', 'QR_CODE', 'jl kayu gede', '', 'Kurir Toko', 'https://checkout-staging.xendit.co/web/6a578326013ccf36687966b1'),
('DW-6a5da26b442b4', 5, NULL, '2026-07-20 11:22:03', 2847500.00, 15000.00, 'selesai', 'QR_CODE', 'jl kayu gede', '', '1233', 'https://checkout-staging.xendit.co/web/6a5da26b21141908733b62b9'),
('INV-1778682807-3', 3, NULL, '2026-05-13 21:33:27', 125000.00, 0.00, 'pending', NULL, NULL, NULL, NULL, ''),
('INV-1778682935-3', 3, NULL, '2026-05-13 21:35:36', 125000.00, 0.00, 'pending', NULL, NULL, NULL, NULL, ''),
('INV-1778682979-3', 3, NULL, '2026-05-13 21:36:21', 125000.00, 0.00, 'dikirim', 'Xendit', NULL, NULL, NULL, 'https://checkout-staging.xendit.co/web/6a048c64b30934f497e6bfd5'),
('INV-1778683187-3', 3, NULL, '2026-05-13 21:39:48', 125000.00, 0.00, 'selesai', 'Xendit', NULL, NULL, NULL, 'https://checkout-staging.xendit.co/web/6a048d33b30934f497e6c0ac');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `ongkir_area`
--
ALTER TABLE `ongkir_area`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_peran` (`id_peran`);

--
-- Indexes for table `peran`
--
ALTER TABLE `peran`
  ADD PRIMARY KEY (`id_peran`),
  ADD UNIQUE KEY `nama_peran` (`nama_peran`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `fk_produk_model_kendaraan` (`id_model`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_transaksi_ongkir_area` (`id_area`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ongkir_area`
--
ALTER TABLE `ongkir_area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `id_peran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_peran`) REFERENCES `peran` (`id_peran`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_ongkir_area` FOREIGN KEY (`id_area`) REFERENCES `ongkir_area` (`id_area`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
