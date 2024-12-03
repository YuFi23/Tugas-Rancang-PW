-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2024 pada 13.47
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbtiket`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `konser`
--

CREATE TABLE `konser` (
  `id` int(11) NOT NULL,
  `nama_artis` varchar(100) NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `concert_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konser`
--

INSERT INTO `konser` (`id`, `nama_artis`, `tempat`, `tanggal`, `harga`, `gambar`, `stock`, `concert_name`) VALUES
(9, 'yerky', 'glora bungkarno', '2025-07-07', 0.00, '1732725851_WhatsApp Image 2024-02-26 at 19.23.54_4cc0cf80.jpg', 990, NULL),
(11, 'matia', 'blotongan', '2025-06-06', 0.00, '1733226745_WhatsApp Image 2024-02-26 at 19.23.54_4cc0cf80.jpg', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `konser`
--
ALTER TABLE `konser`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `konser`
--
ALTER TABLE `konser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
