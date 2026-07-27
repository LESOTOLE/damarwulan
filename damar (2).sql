-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 03:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
(5, 'DW-177977257878', 162, 2, 297500.00);

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
(3, 3, 'awikawok', 'okeegayn@gmail.com', '$2y$10$.nO9SiqFV3fXWWN.vWH//.w6vykPRdTIzL0yJgfN1XRohlkNw97tS', '089601582562', 1, 'Jalan jalan aja', '2026-05-13 14:33:08'),
(4, 3, 'wakwaw', 'awokawok@gmail.com', '$2y$10$NNyL3H8OyDWm0WYcQJ4Teux.XH8863yWz9hXebBCOkD463HL2.ali', NULL, NULL, NULL, '2026-05-14 02:19:41');

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
(1, 1, NULL, 'DNS-W20', 'Busi Denso Iridium', 'Denso', 'Busi performa tinggi dengan bahan iridium untuk pembakaran mesin yang optimal dan efisiensi bahan bakar yang lebih baik.', 'default-part.jpg', 125000.00, 50, 100),
(2, 2, NULL, 'BOSCH-BPD1', 'Kampas Rem Depan Bosch', 'Bosch', 'Kampas rem cakram depan original Bosch, memberikan daya pengereman maksimal dan aman untuk perjalanan jauh.', 'default-part.jpg', 250000.00, 30, 800),
(129, 5, NULL, 'JK447200-2740', 'Compresor Only 15A Kijang Diesel', 'Cool Gear', 'Kompresor AC spesifik untuk Toyota Kijang Diesel. Produk berkualitas dari Cool Gear dengan garansi daya tahan tinggi.', 'default-part.jpg', 1950000.00, 1, 6500),
(130, 5, NULL, 'JK447160-5042', 'Compresor Assy New Rush, Veloz', 'Denso', 'Kompresor AC Assy (lengkap) original Denso untuk Toyota New Rush dan Veloz. Menjamin kabin mobil selalu sejuk.', 'default-part.jpg', 2035150.00, 5, 7000),
(131, 5, NULL, 'XI447280-2670', 'Compresor Only GN Avanza', 'Denso', 'Kompresor AC tipe Only untuk Toyota Grand New Avanza. Kualitas pabrikan Denso, presisi dan awet.', 'default-part.jpg', 3120000.00, 8, 6000),
(132, 5, NULL, '447220-5851', 'Compresor Only Avanza Lama', 'Denso', 'Kompresor AC pengganti untuk Toyota Avanza tipe lama (generasi pertama). Plug and play tanpa ubahan.', 'default-part.jpg', 2860000.00, 2, 6000),
(133, 5, NULL, 'XI447160-6093', 'Compresor Only Yaris New Kaki 3', 'Denso', 'Kompresor AC Yaris New model kaki 3. Asli Denso, putaran lebih halus dan tidak membebani mesin.', 'default-part.jpg', 2080000.00, 1, 6200),
(134, 5, NULL, 'JK447200-4355', 'Compresor Only Universal 17A', 'Denso', 'Kompresor AC Universal (10PA17C) 17A. Cocok untuk modifikasi atau dipasang di Isuzu Elf, L300, dan Accord Maestro.', 'default-part.jpg', 2730000.00, 0, 7500),
(135, 5, NULL, 'XI447160-3444', 'Compresor Only Suzuki Ertiga', 'Denso', 'Kompresor AC (Only) untuk Suzuki Ertiga. Mengatasi masalah AC kurang dingin dengan performa standarisasi Jepang.', 'default-part.jpg', 2600000.00, 1, 6500),
(136, 5, NULL, 'XI447110-3690', 'Compresor Only Daihatsu Grand Max', 'Denso', 'Kompresor AC untuk Daihatsu Grand Max. Tangguh untuk mobil komersial yang sering beroperasi di cuaca panas.', 'default-part.jpg', 2665000.00, 0, 6000),
(137, 5, NULL, 'XI447160-8444', 'Compresor W-Cluth Assy Mobilio/BRV', 'Denso', 'Kompresor AC lengkap dengan Magnetic Clutch untuk Honda Mobilio dan BRV. Jaminan dingin maksimal dan instalasi mudah.', 'default-part.jpg', 2990000.00, 10, 7200),
(138, 5, NULL, 'XI447160-8444', 'Compresor Only Mobilio/BRV', 'Denso', 'Kompresor AC bagian Only (tanpa clutch) untuk Honda Mobilio dan BRV. Alternatif hemat bagi yang clutch-nya masih bagus.', 'default-part.jpg', 2275000.00, 2, 6000),
(139, 5, NULL, 'XI447280-2340', 'Compresor Only Innova Bensin', 'Denso', 'Kompresor AC khusus Toyota Kijang Innova bermesin bensin. Mengembalikan kenyamanan berkendara layaknya mobil baru.', 'default-part.jpg', 2203500.00, 7, 6800),
(140, 5, NULL, 'XI447280-2321', 'Compresor Assy Innova Diesel', 'Denso', 'Kompresor AC Assy (lengkap) untuk Toyota Kijang Innova bermesin diesel. Kuat dan tahan lama.', 'default-part.jpg', 2275000.00, 4, 7500),
(141, 5, NULL, 'JK447200-1940', 'Compresor Assy Kijang Diesel', 'Denso', 'Kompresor AC lengkap untuk Toyota Kijang Diesel lama. Solusi terbaik untuk AC yang sering ngorok atau panas.', 'default-part.jpg', 2535000.00, 1, 7000),
(142, 7, NULL, NULL, 'Daun Kipas Extrafan Xtrail, Serena C26', 'Polos', 'Mobil: -. Part No: -', 'default-part.jpg', 255000.00, 10, 0),
(143, 7, NULL, NULL, 'Kipas Extrafan Soluna', 'Polos', 'Mobil: -. Part No: -', 'default-part.jpg', 255000.00, 7, 0),
(144, 12, NULL, NULL, 'Magnet Cluth Brio 1.5', 'Besnorm', 'Mobil: -. Part No: -', 'default-part.jpg', 375000.00, 1, 0),
(145, 12, NULL, 'DI437390-0130', 'Magnet Cluth All New Jazz', 'Cool Gear', 'Mobil: -. Part No: DI437390-0130', 'default-part.jpg', 575000.00, 1, 0),
(146, 12, NULL, 'XI247300-9480', 'Magnet Cluth Avanza New 6PK Agya Calya', 'Denso', 'Mobil: -. Part No: XI247300-9480', 'default-part.jpg', 1160900.00, 11, 0),
(147, 12, NULL, '447300-2410', 'Magnet Cluth Kijang LGX', 'Denso', 'Mobil: -. Part No: 447300-2410', 'default-part.jpg', 1218750.00, 0, 0),
(148, 12, NULL, 'JK247300-67903D', 'Magnet Cluth Avanza New 6Pk', 'Denso KW', 'Mobil: -. Part No: JK247300-67903D', 'default-part.jpg', 520000.00, 10, 0),
(149, 12, NULL, 'JK247300-67903D', 'Magnet Cluth Avanza New 4Pk', 'Denso KW', 'Mobil: -. Part No: JK247300-67903D', 'default-part.jpg', 520000.00, 9, 0),
(150, 12, NULL, 'JK247300-67903D', 'Magnet Cluth Xenia 1.0', 'Denso KW', 'Mobil: -. Part No: JK247300-67903D', 'default-part.jpg', 520000.00, 7, 0),
(151, 12, NULL, 'MC105-005', 'Magnet Cluth honda CRV 2.4 7PK', 'LAX', 'Mobil: -. Part No: MC105-005', 'default-part.jpg', 560000.00, 1, 0),
(152, 12, NULL, 'MC105-004', 'Magnet Cluth honda CRV 2.0 7PK', 'LAX', 'Mobil: -. Part No: MC105-004', 'default-part.jpg', 550000.00, 5, 0),
(153, 12, NULL, 'MC105-009', 'Magnet Clutch Honda Genio/Estilo 4PK', 'LAX', 'Magnetic Clutch 4PK untuk Honda Civic Genio dan Estilo. Menyambungkan putaran kompresor dengan sempurna tanpa selip.', 'default-part.jpg', 550000.00, 5, 1200),
(154, 12, NULL, 'MC105-003', 'Magnet Clutch Honda City 5PK', 'LAX', 'Magnetic Clutch 5PK untuk Honda City. Material plat gesek yang kuat dan koil gulungan presisi.', 'default-part.jpg', 570000.00, 6, 1250),
(155, 12, NULL, 'MC103-004', 'Magnet Clutch Nissan Livina 7PK', 'LAX', 'Magnetic Clutch 7PK khusus Nissan Grand Livina. Solusi masalah AC sering putus nyambung saat mesin panas.', 'default-part.jpg', 570000.00, 4, 1300),
(156, 12, NULL, 'MC103-002', 'Magnet Clutch Datsun Go 7PK', 'LAX', 'Magnetic Clutch 7PK untuk Datsun Go. Mengembalikan tarikan AC agar kembali normal dan dingin.', 'default-part.jpg', 550000.00, 2, 1100),
(157, 12, NULL, 'MC101-012', 'Magnet Clutch Toyota Yaris New 4PK', 'LAX', 'Magnetic Clutch 4PK untuk Toyota Yaris New. Tahan panas dan memiliki usia pakai (durability) yang panjang.', 'default-part.jpg', 570000.00, 2, 1200),
(158, 12, NULL, 'MC103-001', 'Magnet Clutch Nissan March 7PK', 'LAX', 'Magnetic Clutch 7PK untuk Nissan March. Menggantikan pulley lama yang sudah bunyi ngorok atau oblak.', 'default-part.jpg', 550000.00, 1, 1150),
(159, 12, NULL, 'MC104-005', 'Magnet Clutch Pajero Sport 5PK', 'LAX', 'Magnetic Clutch 5PK untuk Mitsubishi Pajero Sport. Komponen vital agar AC kembali bekerja secara otomatis.', 'default-part.jpg', 600000.00, 2, 1500),
(160, 12, NULL, 'MC104-002', 'Magnet Clutch Pajero Sport 1PK', 'LAX', 'Magnetic Clutch 1PK untuk Mitsubishi Pajero Sport (tipe tertentu). Tahan terhadap suhu ruang mesin yang ekstrim.', 'default-part.jpg', 600000.00, 2, 1500),
(161, 16, NULL, 'SHP-981A', 'Selang 1/2 High Pressure CRV Gen 1', 'Polos', 'Selang AC High Pressure ukuran 1/2 untuk Honda CRV Generasi 1. Tahan terhadap tekanan freon tinggi agar tidak mudah bocor.', 'default-part.jpg', 297500.00, 7, 500),
(162, 16, NULL, 'SHP-982B', 'Selang 1/2 High Pressure Grand Veloz', 'Polos', 'Selang AC tekanan tinggi untuk Toyota Grand Veloz. Menggunakan lapisan karet khusus yang tahan lama dan tidak mudah getas.', 'default-part.jpg', 297500.00, 10, 500),
(163, 16, NULL, 'SHP-983C', 'Selang 1/2 High Pressure HRV', 'Polos', 'Selang AC High Pressure untuk Honda HRV. Fitting pipa aluminium presisi untuk mencegah kebocoran freon.', 'default-part.jpg', 297500.00, 2, 550),
(164, 16, NULL, 'SHP-984D', 'Selang 1/2 High Pressure Avanza New', 'Polos', 'Selang AC 1/2 inchi High Pressure untuk All New Avanza. Pemasangan mudah dan langsung pas dengan nepel bawaan.', 'default-part.jpg', 297500.00, 2, 500),
(165, 16, NULL, 'SHP-985E', 'Selang 1/2 High Pressure Swift GX', 'Polos', 'Selang High Pressure sistem AC untuk Suzuki Swift GX. Kualitas setara original equipment.', 'default-part.jpg', 297500.00, 1, 450),
(166, 16, NULL, 'SHP-986F', 'Selang 1/2 High Pressure Innova', 'Polos', 'Selang tekanan tinggi AC khusus Toyota Kijang Innova. Menjaga sirkulasi refrigeran berjalan lancar menuju kondensor.', 'default-part.jpg', 297500.00, 3, 600),
(167, 16, NULL, 'SHP-987G', 'Selang 1/2 High Pressure Mobilio', 'Polos', 'Selang High Pressure untuk Honda Mobilio. Lentur namun sangat kuat menahan tekanan sistem pendingin mobil.', 'default-part.jpg', 297500.00, 1, 550),
(168, 16, NULL, 'SHP-988H', 'Selang 1/2 High Pressure Rush Terios', 'Polos', 'Selang AC High Pressure untuk duet Toyota Rush dan Daihatsu Terios. Solusi selang AC yang sering rembes.', 'default-part.jpg', 297500.00, 1, 550),
(169, 16, NULL, 'SHP-989I', 'Selang 1/2 High Pressure Ertiga', 'Polos', 'Selang AC ukuran 1/2 High Pressure untuk Suzuki Ertiga lama maupun baru.', 'default-part.jpg', 297500.00, 1, 500),
(170, 16, NULL, 'SHP-990J', 'Selang 1/2 High Pressure Jazz Lama', 'Polos', 'Selang AC bagian High Pressure untuk Honda Jazz GD3 (Lama). Tahan gesekan di ruang mesin yang sempit.', 'default-part.jpg', 297500.00, 1, 450),
(171, 16, NULL, 'SHP-991K', 'Selang 1/2 High Pressure Innova Single Blower', 'Polos', 'Selang khusus tekanan tinggi untuk Toyota Innova varian Single Blower (tipe J/Bisnis).', 'default-part.jpg', 297500.00, 1, 500);

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
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
