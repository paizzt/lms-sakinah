-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jan 2026 pada 17.03
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
(3, 'XI IPA 1', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(20) DEFAULT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kode_mapel`, `nama_mapel`, `guru_id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(1, 'MTK-01', 'Matematika Wajib', 2, 1, 'Senin', '07:30:00', '09:00:00'),
(2, 'IND-01', 'Bahasa Indonesia', 3, 1, 'Selasa', '09:00:00', '10:30:00'),
(3, 'ING-01', 'Bahasa Inggris', 7, 1, 'Rabu', '08:00:00', '09:30:00'),
(4, 'IPA-01', 'Ilmu Pengetahuan Alam', 2, 2, 'Senin', '10:00:00', '11:30:00');

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
(1, 1, 'Bab 1: Eksponen dan Logaritma', 'Materi dasar tentang sifat-sifat eksponen.', 'materi_dummy.pdf', '', '2026-01-02 19:57:41'),
(2, 2, 'Teks Laporan Hasil Observasi', 'Pengertian, struktur, dan kaidah kebahasaan teks LHO.', '', 'https://youtube.com/watch?v=video_lho', '2026-01-02 19:57:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `nilai_tugas` int(11) DEFAULT 0,
  `nilai_uh` int(11) DEFAULT 0,
  `nilai_uts` int(11) DEFAULT 0,
  `nilai_uas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `siswa_id`, `mapel_id`, `nilai_tugas`, `nilai_uh`, `nilai_uts`, `nilai_uas`) VALUES
(1, 4, 1, 85, 90, 88, 92),
(2, 4, 2, 80, 85, 82, 88),
(3, 4, 3, 75, 78, 80, 85),
(4, 5, 1, 90, 92, 95, 94),
(5, 5, 2, 88, 85, 90, 89);

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
(1, 1, 4, '835815747_files-folder.png', 99, 'nda bagus', '2026-01-02 17:01:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `tujuan` enum('semua','guru','siswa') NOT NULL,
  `file_lampiran` varchar(255) DEFAULT NULL,
  `tanggal_dibuat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi`, `tujuan`, `file_lampiran`, `tanggal_dibuat`) VALUES
(1, 'Libur Awal Puasa', 'Kegiatan belajar mengajar diliburkan selama 3 hari awal puasa.', 'semua', NULL, '2026-03-01 08:00:00'),
(2, 'Jadwal UTS Semester Ganjil', 'UTS akan dilaksanakan mulai tanggal 20 Oktober.', 'siswa', NULL, '2026-10-01 09:00:00'),
(3, 'Rapat Dewan Guru', 'Mohon kehadiran Bapak/Ibu guru di ruang rapat.', 'guru', NULL, '2026-09-15 10:00:00');

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
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa_detail`
--

INSERT INTO `siswa_detail` (`id_detail`, `user_id`, `kelas_id`, `nis`, `tempat_lahir`, `tanggal_lahir`, `alamat`) VALUES
(1, 4, 1, '2025001', 'Jakarta', '2008-05-10', 'Jl. Merpati No. 10'),
(2, 5, 1, '2025002', 'Bandung', '2008-08-17', 'Jl. Kenari No. 5'),
(3, 6, 2, '2025003', 'Surabaya', '2008-01-20', 'Jl. Anggrek No. 3'),
(4, 8, 1, '2025004', 'Medan', '2008-11-05', 'Jl. Melati No. 8'),
(5, 9, 2, '2025005', 'Yogyakarta', '2008-03-15', 'Jl. Mawar No. 1');

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
(1, 1, 'Latihan Eksponen', 'Kerjakan Halaman 10.', '2026-12-31 23:59:00', 'tugas', NULL);

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
(1, 'admin', '123', 'Administrator Utama', 'admin@sekolah.sch.id', 'admin', 'default.jpg', '2026-01-02 11:55:42'),
(2, 'guru1', '123', 'Budi Santoso, S.Pd', 'budi@sekolah.sch.id', 'guru', 'default.jpg', '2026-01-02 11:55:42'),
(3, 'guru2', '123', 'Siti Aminah, M.Pd', 'siti@sekolah.sch.id', 'guru', 'default.jpg', '2026-01-02 11:55:42'),
(4, 'siswa1', '123', 'Ahmad Fikri', 'fikri@gmail.com', 'siswa', 'default.jpg', '2026-01-02 11:55:42'),
(5, 'siswa2', '123', 'Dewi Sartika', 'dewi@gmail.com', 'siswa', 'default.jpg', '2026-01-02 11:55:42'),
(6, 'siswa3', '123', 'Rudi Hartono', 'rudi@gmail.com', 'siswa', 'default.jpg', '2026-01-02 11:55:42'),
(7, 'guru3', '123', 'Dodi Kusuma, S.Kom', 'dodi@sekolah.sch.id', 'guru', 'default.jpg', '2026-01-02 11:55:42'),
(8, 'siswa4', '123', 'Sinta Nuriyah', 'sinta@gmail.com', 'siswa', 'default.jpg', '2026-01-02 11:55:42'),
(9, 'siswa5', '123', 'Joko Anwar', 'joko@gmail.com', 'siswa', 'default.jpg', '2026-01-02 11:55:42'),
(10, 'admin2', '123', 'Staff Tata Usaha', 'tu@sekolah.sch.id', 'admin', 'default.jpg', '2026-01-02 11:55:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`);

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
  ADD PRIMARY KEY (`id_materi`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `siswa_id` (`siswa_id`),
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
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

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
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE;

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
