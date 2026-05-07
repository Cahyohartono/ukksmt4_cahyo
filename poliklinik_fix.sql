-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2026 pada 09.40
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
-- Database: `poliklinik_fix`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `telepon_admin` varchar(100) DEFAULT NULL,
  `alamat_admin` varchar(256) NOT NULL,
  `jenis_kelamin` enum('L','P','','') NOT NULL,
  `path_photo_admin` varchar(255) DEFAULT NULL,
  `id_user` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `nama_admin`, `telepon_admin`, `alamat_admin`, `jenis_kelamin`, `path_photo_admin`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'Cahyohartono Admin', '08984487537845', 'Alamat Cahyohartono', 'L', NULL, 'ADM0000001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Nama Admin 2', '08943555556', 'Alamat Admin 2', '', NULL, 'ADM0000002', '0000-00-00 00:00:00', '2026-05-05 02:33:14'),
(3, 'Nama Admin 3', '08303498875845', 'Alamat Admin 3', 'L', NULL, 'ADM0000003', '0000-00-00 00:00:00', '2026-05-05 02:35:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_dokter`
--

CREATE TABLE `tbl_dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(100) DEFAULT NULL,
  `telepon_dokter` varchar(100) DEFAULT NULL,
  `jenkel` enum('L','P') DEFAULT NULL,
  `path_photo_dokter` varchar(255) DEFAULT NULL,
  `id_user` varchar(12) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kasir`
--

CREATE TABLE `tbl_kasir` (
  `id_petugas` int(11) NOT NULL,
  `nama_kasir` varchar(100) DEFAULT NULL,
  `telepon_kasir` varchar(100) DEFAULT NULL,
  `jenkel` enum('L','P') DEFAULT NULL,
  `path_photo_kasir` varchar(255) DEFAULT NULL,
  `id_user` varchar(12) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pasien`
--

CREATE TABLE `tbl_pasien` (
  `id_pasien` int(11) NOT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `telepon_pasien` varchar(100) DEFAULT NULL,
  `jenkel` enum('L','P') DEFAULT NULL,
  `path_photo_pasien` varchar(255) DEFAULT NULL,
  `id_user` varchar(12) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tipe_user`
--

CREATE TABLE `tbl_tipe_user` (
  `id_tipe_user` int(11) NOT NULL,
  `tipe_user` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_tipe_user`
--

INSERT INTO `tbl_tipe_user` (`id_tipe_user`, `tipe_user`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2026-04-26 20:19:10', '2026-04-26 20:19:10'),
(2, 'Kasir', '2026-04-26 20:19:10', '2026-04-26 20:19:10'),
(3, 'Dokter', '2026-04-26 20:19:39', '2026-04-26 20:19:39'),
(4, 'Pasien', '2026-04-26 20:19:39', '2026-04-26 20:19:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_user` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id_user`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('ADM0000001', 'admin@poliklinik.com', '$2y$10$1buts2ivKLmdp2amN3Dob.aCaRNNdIco.QsJUTUb.O64QOBJ6kFdS', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('ADM0000002', 'admin2@poliklinik.com', '$2y$10$V/rRbZzSly7LNy97kuZKyuyqNAXgly2pvTbTnPi3cxdSQEB7Ya9Ri', 1, '0000-00-00 00:00:00', '2026-05-05 02:33:14'),
('ADM0000003', 'admin3@poliklinik.com', '$2y$10$iMDfqLWobRKw/XBxp2G34uZNI3FsKj5diNWt9CxK02GbVOfWQR9Ii', 1, '0000-00-00 00:00:00', '2026-05-05 02:35:22');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tbl_dokter`
--
ALTER TABLE `tbl_dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tbl_kasir`
--
ALTER TABLE `tbl_kasir`
  ADD PRIMARY KEY (`id_petugas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tbl_pasien`
--
ALTER TABLE `tbl_pasien`
  ADD PRIMARY KEY (`id_pasien`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tbl_tipe_user`
--
ALTER TABLE `tbl_tipe_user`
  ADD PRIMARY KEY (`id_tipe_user`);

--
-- Indeks untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD CONSTRAINT `tbl_admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tbl_dokter`
--
ALTER TABLE `tbl_dokter`
  ADD CONSTRAINT `tbl_dokter_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tbl_kasir`
--
ALTER TABLE `tbl_kasir`
  ADD CONSTRAINT `tbl_kasir_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tbl_pasien`
--
ALTER TABLE `tbl_pasien`
  ADD CONSTRAINT `tbl_pasien_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `tbl_tipe_user` (`id_tipe_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
