-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Des 2025 pada 11.36
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
-- Database: `db_lms_sakinah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `mapel_id`, `guru_id`, `kelas_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
(6, 1, 2, 1, 5, '2025-12-26', '', ''),
(7, 1, 2, 1, 6, '2025-12-26', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `wali_kelas_id`) VALUES
(1, 'X IPA 1', 2),
(2, 'X IPS 1', 3),
(4, 'IPA-3', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kode_mapel`, `nama_mapel`, `hari`, `jam_mulai`, `jam_selesai`, `guru_id`, `kelas_id`) VALUES
(1, 'MTK-01', 'Matematika Wajib', 'Senin', '07:30:00', '09:00:00', 2, 1),
(2, 'IND-01', 'Bahasa Indonesia', 'Senin', '09:15:00', '10:45:00', 3, 1),
(3, 'MTK-02', 'Matematika Wajib', 'Selasa', '07:30:00', '09:00:00', 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `judul_materi` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_materi` varchar(255) DEFAULT NULL,
  `link_materi` varchar(255) DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id_materi`, `mapel_id`, `judul_materi`, `deskripsi`, `file_materi`, `link_materi`, `tanggal_upload`) VALUES
(1, 1, 'Bab 1: Eksponen dan Logaritma', 'Materi dasar tentang sifat-sifat eksponen.', 'materi_dummy_eksponen.pdf', '', '2025-07-15 08:00:00'),
(2, 1, 'Video Pembahasan Soal Logaritma', 'Silakan tonton video ini agar lebih paham cara mengerjakan soal cerita.', '', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', '2025-07-20 09:00:00'),
(3, 2, 'Teks Laporan Hasil Observasi', 'Pengertian, struktur, dan kaidah kebahasaan teks LHO.', 'materi_dummy_lho.pdf', '', '2025-07-16 10:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumpulan`
--

CREATE TABLE `pengumpulan` (
  `id_pengumpulan` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `file_siswa` varchar(255) DEFAULT NULL,
  `nilai` int(11) DEFAULT 0,
  `komentar_guru` text DEFAULT NULL,
  `tanggal_kumpul` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumpulan`
--

INSERT INTO `pengumpulan` (`id_pengumpulan`, `tugas_id`, `siswa_id`, `file_siswa`, `nilai`, `komentar_guru`, `tanggal_kumpul`) VALUES
(1, 1, 5, '1126875362_files-folder.png', 100, '', '2025-12-26 19:03:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tujuan` enum('semua','guru','siswa') NOT NULL,
  `tanggal_dibuat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi`, `gambar`, `tujuan`, `tanggal_dibuat`) VALUES
(1, 'Libur Awal Puasa', 'Diberitahukan kepada seluruh siswa dan guru bahwa libur awal puasa dimulai tanggal 1-3 Ramadhan. Kegiatan belajar mengajar akan dimulai kembali pada hari ke-4 dengan jadwal khusus.', '', 'semua', '2025-03-01 08:00:00'),
(2, 'Jadwal Ujian Tengah Semester', 'Ujian Tengah Semester (UTS) akan dilaksanakan mulai tanggal 20 Oktober 2025. Harap siswa mempersiapkan diri dengan baik.', '', 'siswa', '2025-10-01 09:00:00'),
(3, 'Rapat Dewan Guru', 'Mohon kehadiran Bapak/Ibu guru di ruang rapat pada hari Sabtu pukul 10.00 WIB untuk membahas persiapan akreditasi.', '', 'guru', '2025-09-15 10:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rps`
--

CREATE TABLE `rps` (
  `id_rps` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_rps` varchar(255) NOT NULL,
  `tanggal_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rps`
--

INSERT INTO `rps` (`id_rps`, `mapel_id`, `deskripsi`, `file_rps`, `tanggal_upload`) VALUES
(1, 1, 'Silabus Matematika Semester Ganjil 2025/2026', 'rps_dummy_mtk.pdf', '2025-07-01 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `id_semester` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `semester`
--

INSERT INTO `semester` (`id_semester`, `tahun_ajaran`, `semester`, `status`) VALUES
(1, '2025/2026', 'Ganjil', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa_detail`
--

CREATE TABLE `siswa_detail` (
  `id_detail` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa_detail`
--

INSERT INTO `siswa_detail` (`id_detail`, `user_id`, `kelas_id`, `nis`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `provinsi`, `kota`) VALUES
(1, 5, 1, '2025001', NULL, 'Jakarta', '2008-05-10', 'Jl. Merpati No. 10', '081234567890', NULL, NULL),
(2, 6, 1, '2025002', NULL, 'Bandung', '2008-08-17', 'Jl. Kenari No. 5', '081298765432', NULL, NULL),
(4, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `judul_tugas` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `tipe` enum('tugas','kuis') DEFAULT 'tugas',
  `file_tugas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `mapel_id`, `judul_tugas`, `deskripsi`, `deadline`, `tipe`, `file_tugas`) VALUES
(1, 1, 'Latihan Soal Eksponen Hal 10', 'Kerjakan buku paket halaman 10 nomor 1 sampai 5. Upload jawaban dalam format PDF.', '2025-12-31 23:59:00', 'tugas', ''),
(2, 2, 'Kuis Kebahasaan Teks LHO', 'Kerjakan kuis berikut untuk menguji pemahaman Anda tentang verba dan nomina.', '2025-12-30 12:00:00', 'kuis', ''),
(3, 1, 'Tugas Kelompok (Sudah Lewat)', 'Tugas ini deadline-nya sudah lewat untuk testing sistem.', '2024-01-01 00:00:00', 'tugas', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `role`, `foto_profil`, `created_at`) VALUES
(1, 'admin', '123', 'Administrator Utama', 'admin@sekolah.sch.id', 'admin', 'default.jpg', '2025-12-26 15:22:52'),
(2, 'guru1', '123', 'Budi Santoso, S.Pd', 'budi@sekolah.sch.id', 'guru', 'default.jpg', '2025-12-26 15:22:52'),
(3, 'guru2', '123', 'Siti Aminah, M.Pd', 'siti@sekolah.sch.id', 'guru', 'default.jpg', '2025-12-26 15:22:52'),
(4, 'guru3', '123', 'Dodi Kusuma', 'dodi@sekolah.sch.id', 'guru', 'default.jpg', '2025-12-26 15:22:52'),
(5, 'siswa1', '123', 'Ahmad Fikri', 'fikri@gmail.com', 'siswa', 'default.jpg', '2025-12-26 15:22:52'),
(6, 'siswa2', '123', 'Dewi Sartika', 'dewi@gmail.com', 'siswa', 'default.jpg', '2025-12-26 15:22:52'),
(12, 'didi', '123', 'doditt', 'dodittt@gmail.com', 'siswa', 'default.jpg', '2025-12-26 17:55:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `wali_kelas_id` (`wali_kelas_id`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  ADD PRIMARY KEY (`id_pengumpulan`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`);

--
-- Indeks untuk tabel `rps`
--
ALTER TABLE `rps`
  ADD PRIMARY KEY (`id_rps`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id_semester`);

--
-- Indeks untuk tabel `siswa_detail`
--
ALTER TABLE `siswa_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  MODIFY `id_pengumpulan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rps`
--
ALTER TABLE `rps`
  MODIFY `id_rps` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `siswa_detail`
--
ALTER TABLE `siswa_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas_id`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `mapel_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `mapel_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  ADD CONSTRAINT `pengumpulan_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengumpulan_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa_detail`
--
ALTER TABLE `siswa_detail`
  ADD CONSTRAINT `siswa_detail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_detail_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
