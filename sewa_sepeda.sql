-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2023 at 03:21 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sewa_sepeda`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_prodi`
--

CREATE TABLE `data_prodi` (
  `id_prodi` int(11) NOT NULL,
  `nama_prodi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_prodi`
--

INSERT INTO `data_prodi` (`id_prodi`, `nama_prodi`) VALUES
(1, 'Informatika'),
(2, 'Sistem Informasi');

-- --------------------------------------------------------

--
-- Table structure for table `data_sepeda`
--

CREATE TABLE `data_sepeda` (
  `id_sepeda` int(11) NOT NULL,
  `merk_sepeda` varchar(225) NOT NULL,
  `jumlah_sepeda` int(11) NOT NULL,
  `kondisi_sepeda` varchar(225) NOT NULL,
  `gambar_sepeda` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_sepeda`
--

INSERT INTO `data_sepeda` (`id_sepeda`, `merk_sepeda`, `jumlah_sepeda`, `kondisi_sepeda`, `gambar_sepeda`) VALUES
(1, 'POLYGON', 41, 'LAYAK PAKAI', 'http://localhost/sepedaku/uploads/files/cegmds7u2wh85j4.jpeg'),
(2, 'PACIFIC', 50, 'LAYAK PAKAI', 'http://localhost/sepedaku/uploads/files/r5oy1b9itwv3cql.png');

-- --------------------------------------------------------

--
-- Table structure for table `denda_berlaku`
--

CREATE TABLE `denda_berlaku` (
  `id_db` int(11) NOT NULL,
  `denda` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denda_berlaku`
--

INSERT INTO `denda_berlaku` (`id_db`, `denda`) VALUES
(3, 0),
(4, 5000),
(5, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `pemgembalian`
--

CREATE TABLE `pemgembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_sewa` int(20) NOT NULL,
  `tgl_pengembalian` date NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `status_pengembalian` varchar(255) NOT NULL,
  `id_sepeda` int(11) NOT NULL,
  `nim` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemgembalian`
--

INSERT INTO `pemgembalian` (`id_pengembalian`, `id_sewa`, `tgl_pengembalian`, `keterangan`, `status_pengembalian`, `id_sepeda`, `nim`) VALUES
(11, 16297, '2023-05-15', 'OK', '1', 1, 1122);

--
-- Triggers `pemgembalian`
--
DELIMITER $$
CREATE TRIGGER `kembalispits` AFTER INSERT ON `pemgembalian` FOR EACH ROW BEGIN
UPDATE data_sepeda set data_sepeda.jumlah_sepeda = data_sepeda.jumlah_sepeda + new.status_pengembalian
WHERE data_sepeda.id_sepeda = new.id_sepeda;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id_sewa` int(11) NOT NULL,
  `nim` int(11) NOT NULL,
  `id_sepeda` int(11) NOT NULL,
  `ketersediaan` int(11) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `status` varchar(225) NOT NULL,
  `terlambat` int(20) NOT NULL,
  `pinalti` int(225) NOT NULL,
  `tgl_booking` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyewaan`
--

INSERT INTO `penyewaan` (`id_sewa`, `nim`, `id_sepeda`, `ketersediaan`, `tgl_mulai`, `tgl_akhir`, `status`, `terlambat`, `pinalti`, `tgl_booking`) VALUES
(16297, 1122, 1, 43, '2023-05-15', '2023-05-15', 'Pending', 1, 5000, '2023-05-16'),
(408529, 1234, 1, 50, '2023-05-14', '2023-05-20', '-1', 1, 0, '2023-05-14'),
(657184, 1010, 1, 45, '2023-05-16', '2023-05-18', '-1', 1, 0, '2023-05-16'),
(712935, 1122, 1, 42, '2023-05-16', '2023-05-16', 'Pending', 1, 0, '2023-05-16'),
(937524, 1122, 1, 43, '2023-05-17', '2023-05-20', 'Pending', 1, 0, '2023-05-16'),
(942061, 1133, 1, 43, '2023-05-15', '2023-05-16', '1', 1, 0, '2023-05-16');

--
-- Triggers `penyewaan`
--
DELIMITER $$
CREATE TRIGGER `ambil_sepeda` AFTER INSERT ON `penyewaan` FOR EACH ROW BEGIN
UPDATE data_sepeda set data_sepeda.jumlah_sepeda = data_sepeda.jumlah_sepeda - new.terlambat
WHERE data_sepeda.id_sepeda = new.id_sepeda;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ditolak` AFTER UPDATE ON `penyewaan` FOR EACH ROW BEGIN
UPDATE data_sepeda set data_sepeda.jumlah_sepeda = data_sepeda.jumlah_sepeda + new.status
WHERE data_sepeda.id_sepeda = new.id_sepeda;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `nim` int(20) NOT NULL,
  `users_name` varchar(225) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(225) NOT NULL,
  `image` varchar(255) NOT NULL,
  `nama_lengap` varchar(225) NOT NULL,
  `kontak` varchar(12) NOT NULL,
  `prodi` varchar(225) NOT NULL,
  `ktm` varchar(225) NOT NULL,
  `level` varchar(225) NOT NULL,
  `login_session_key` varchar(255) DEFAULT NULL,
  `email_status` varchar(255) DEFAULT NULL,
  `password_expire_date` datetime DEFAULT '2023-08-14 00:00:00',
  `password_reset_key` varchar(255) DEFAULT NULL,
  `account_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`nim`, `users_name`, `password`, `email`, `image`, `nama_lengap`, `kontak`, `prodi`, `ktm`, `level`, `login_session_key`, `email_status`, `password_expire_date`, `password_reset_key`, `account_status`) VALUES
(1010, '1010', '$2y$10$8UPRmzup8VC5vEbAXV3zd.rmiXccEfcMIt8URtfYFvm.7gpPVbvIO', 'rahmat@gmail.com', 'http://localhost/spits/uploads/files/h5mwyvf_j2dlanx.png', 'Rahmat Hidayat', '08224488204', 'Informatika', 'http://localhost/spits/uploads/files/3q48ytxhrim0sb9.png', 'User', NULL, NULL, '2023-08-14 00:00:00', NULL, 'Blocked'),
(1122, '1122', '$2y$10$FrzQ.jzelAL1dAyU0ENDEe7UcGi3o5GFw3TxFmZdHXbz5i0HYimsW', 'coba@gmail.com', 'http://localhost/spits/uploads/files/_a1t9qrd0486kcu.png', 'coba', '082244882045', 'Informatika', 'http://localhost/spits/uploads/files/mvd_275unl9eyc4.png', 'User', NULL, NULL, '2023-08-14 00:00:00', NULL, 'Active'),
(1133, '1133', '$2y$10$Zx4hGlY698tBiOPFNPsBdeUC.viZ9fZIW9pc/74k49dC.JNpSAFaO', 'kadir@gmail.com', 'http://localhost/spits/uploads/files/qrij8lv12ayuzn9.png', 'kadir', '08224455434', 'Informatika', 'http://localhost/spits/uploads/files/o36n0dyzmi2xtr1.png', 'User', NULL, NULL, '2023-08-14 00:00:00', NULL, 'Active'),
(12345, '12345', '$2y$10$n0o74i363F6tvWI8meTfn.u.ODc24Gv6HEQNecuApW4Pc/9o24Wue', 'admin@gmail.com', 'http://localhost/spits/uploads/files/xrdlp2fnia_s654.png', 'admin', '082244882045', 'Informatika', 'http://localhost/spits/uploads/files/s9o1yfc_v4z8ngr.png', 'Administrator', NULL, NULL, '2023-08-14 00:00:00', NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_prodi`
--
ALTER TABLE `data_prodi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indexes for table `data_sepeda`
--
ALTER TABLE `data_sepeda`
  ADD PRIMARY KEY (`id_sepeda`);

--
-- Indexes for table `denda_berlaku`
--
ALTER TABLE `denda_berlaku`
  ADD PRIMARY KEY (`id_db`);

--
-- Indexes for table `pemgembalian`
--
ALTER TABLE `pemgembalian`
  ADD PRIMARY KEY (`id_pengembalian`);

--
-- Indexes for table `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id_sewa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_prodi`
--
ALTER TABLE `data_prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_sepeda`
--
ALTER TABLE `data_sepeda`
  MODIFY `id_sepeda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `denda_berlaku`
--
ALTER TABLE `denda_berlaku`
  MODIFY `id_db` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pemgembalian`
--
ALTER TABLE `pemgembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=985615;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
