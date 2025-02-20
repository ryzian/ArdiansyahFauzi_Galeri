-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Feb 2025 pada 18.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ardidb_galeri`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `album`
--

CREATE TABLE `album` (
  `albumid` int(11) NOT NULL,
  `namaalbum` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggalbuat` date NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `album`
--

INSERT INTO `album` (`albumid`, `namaalbum`, `deskripsi`, `tanggalbuat`, `userid`) VALUES
(1, 'paskibra', 'Paskibraka adalah singkatan dari Pasukan Pengibar Bendera Pusaka \r\n', '2025-02-18', 1),
(2, 'sekolah', 'SMKN 2 cimahi', '2025-02-16', 1),
(3, 'Healing', 'Enjoy your life\r\n', '2025-02-16', 1),
(4, 'paskibra', 'paskibra', '2025-02-18', 10),
(5, 'sekolah', 'smk2 cimahi\r\n', '2025-02-18', 10),
(6, 'masyarakat', 'umum', '2025-02-18', 10),
(8, 'masyarakat', 'sosial', '2025-02-18', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto`
--

CREATE TABLE `foto` (
  `fotoid` int(11) NOT NULL,
  `judulfoto` varchar(255) NOT NULL,
  `deskripsifoto` text NOT NULL,
  `tanggalupload` date NOT NULL,
  `tempatfile` varchar(255) NOT NULL,
  `albumid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `foto`
--

INSERT INTO `foto` (`fotoid`, `judulfoto`, `deskripsifoto`, `tanggalupload`, `tempatfile`, `albumid`, `userid`) VALUES
(21, 'limadya', 'limadya', '2025-02-19', 'PIXECT-20240418113443 (1).jpg', 1, 1),
(22, 'Taruna', 'Casis', '2025-02-19', 'snapedit_1709179647627.jpeg', 8, 1),
(23, 'asty', 'wanita', '2025-02-19', 'webcam-toy-photo16.jpg', 2, 1),
(24, 'aestetic', 'so fun', '2025-02-19', 'f08af5d7b3f96b2ea3ed721a0e6bac83.jpg', 3, 1),
(25, 'PFM', 'tes', '2025-02-19', 'IMG_2557.JPG', 1, 1),
(26, 'PFM', 'tes', '2025-02-19', 'IMG_2395.JPG', 2, 1),
(27, 'papadol', 'mantap casis', '2025-02-19', 'IMG_2673.JPG', 1, 1),
(28, 'parill', 'mantap', '2025-02-19', 'IMG_2853 - Salin.JPG', 1, 1),
(29, '17 day', 'keren', '2025-02-19', 'Gambar WhatsApp 2024-05-05 pukul 06.42.14_8d049deb.jpg', 1, 1),
(30, 'akmil', 'mantap', '2025-02-19', 'Akmil.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komenfoto`
--

CREATE TABLE `komenfoto` (
  `komenid` int(11) NOT NULL,
  `fotoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isikomen` text NOT NULL,
  `tanggalkomen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komenfoto`
--

INSERT INTO `komenfoto` (`komenid`, `fotoid`, `userid`, `isikomen`, `tanggalkomen`) VALUES
(39, 22, 11, 'keren gays', '2025-02-19 21:59:00'),
(40, 21, 11, 'waw', '2025-02-19 21:59:00'),
(41, 25, 9, 'ganteng', '2025-02-19 21:59:00'),
(42, 21, 12, 'mantap casis', '2025-02-19 22:03:00'),
(44, 27, 11, 'keren', '2025-02-19 22:03:00'),
(45, 21, 11, 'mantap casis', '2025-02-19 00:00:00'),
(46, 21, 11, 'waw', '2025-02-19 00:00:00'),
(47, 23, 11, 'mantap casis', '2025-02-19 00:00:00'),
(48, 22, 11, 'waw', '2025-02-19 00:00:00'),
(49, 21, 11, 'ganteng euy', '2025-02-19 00:00:00'),
(50, 22, 11, 'keren', '2025-02-19 00:00:00'),
(51, 22, 12, 'keren', '2025-02-19 00:00:00'),
(54, 22, 12, 'likeee', '2025-02-19 00:00:00'),
(57, 22, 12, 'ganteng euy', '2025-02-19 00:00:00'),
(58, 21, 12, 'ganteng euy', '2025-02-19 00:00:00'),
(59, 21, 11, 'Test komen', '2025-02-19 22:38:59'),
(62, 29, 12, 'keren', '2025-02-19 00:00:00'),
(63, 21, 12, 'ganteng euy', '2025-02-19 00:00:00'),
(64, 22, 12, 'c', '2025-02-19 00:00:00'),
(65, 28, 12, 'ganteng euy', '2025-02-19 00:00:00'),
(66, 23, 11, 'ss', '2025-02-19 23:08:53'),
(67, 23, 11, 'alhamdulilah', '2025-02-19 23:09:22'),
(69, 27, 11, 'gagah', '2025-02-19 23:11:20'),
(70, 23, 11, 'waw', '2025-02-19 23:11:55'),
(72, 23, 12, 'piw', '2025-02-19 23:14:22'),
(73, 28, 12, 'aww gantengngnya', '2025-02-19 23:14:29'),
(74, 23, 12, 'likeee', '2025-02-19 23:14:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likefoto`
--

CREATE TABLE `likefoto` (
  `likeid` int(11) NOT NULL,
  `fotoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tanggallike` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likefoto`
--

INSERT INTO `likefoto` (`likeid`, `fotoid`, `userid`, `tanggallike`) VALUES
(55, 24, 11, '2025-02-19'),
(56, 21, 11, '2025-02-19'),
(57, 21, 10, '2025-02-19'),
(58, 22, 10, '2025-02-19'),
(59, 23, 10, '2025-02-19'),
(60, 26, 10, '2025-02-19'),
(61, 28, 10, '2025-02-19'),
(62, 30, 10, '2025-02-19'),
(63, 26, 9, '2025-02-19'),
(64, 25, 9, '2025-02-19'),
(65, 27, 9, '2025-02-19'),
(66, 23, 9, '2025-02-19'),
(67, 21, 9, '2025-02-19'),
(68, 24, 9, '2025-02-19'),
(70, 22, 12, '2025-02-19'),
(71, 26, 12, '2025-02-19'),
(73, 24, 12, '2025-02-19'),
(74, 29, 12, '2025-02-19'),
(75, 22, 11, '2025-02-19'),
(76, 21, 12, '2025-02-19'),
(77, 23, 12, '2025-02-19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `roleid` int(11) NOT NULL,
  `namarole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`roleid`, `namarole`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `namalengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userid`, `roleid`, `username`, `password`, `email`, `namalengkap`, `alamat`) VALUES
(1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'ardiansyah@gmail.com', 'ardiansyah fauzi nurrahman', 'Bandung'),
(9, 2, 'popay', 'ab7a6f42140c284c007791b6e7cfc880', 'popay@gmail.com', 'popay anak pelaut', 'cijerah'),
(10, 2, 'dede', 'b4be1c568a6dc02dcaf2849852bdb13e', 'omdeds@gmail.com', 'dede adi samsudin', 'padasuka'),
(11, 2, 'ardi', '0264391c340e4d3cbba430cee7836eaf', 'ardi@gmail.com', 'ardiansyah fauzi', 'sangkuriang'),
(12, 2, 'pania', '9f9b5df7e07391424ed498ff938856b8', 'pania@gmail.com', 'pania nurlatifa', 'bobojong');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`albumid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`fotoid`),
  ADD KEY `albumid` (`albumid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `komenfoto`
--
ALTER TABLE `komenfoto`
  ADD PRIMARY KEY (`komenid`),
  ADD KEY `fotoid` (`fotoid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  ADD PRIMARY KEY (`likeid`),
  ADD KEY `fotoid` (`fotoid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleid`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `roleid` (`roleid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `album`
--
ALTER TABLE `album`
  MODIFY `albumid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `foto`
--
ALTER TABLE `foto`
  MODIFY `fotoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `komenfoto`
--
ALTER TABLE `komenfoto`
  MODIFY `komenid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  MODIFY `likeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foto_ibfk_2` FOREIGN KEY (`albumid`) REFERENCES `album` (`albumid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komenfoto`
--
ALTER TABLE `komenfoto`
  ADD CONSTRAINT `komenfoto_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `komenfoto_ibfk_2` FOREIGN KEY (`fotoid`) REFERENCES `foto` (`fotoid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  ADD CONSTRAINT `likefoto_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likefoto_ibfk_2` FOREIGN KEY (`fotoid`) REFERENCES `foto` (`fotoid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
