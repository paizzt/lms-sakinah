-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jan 2026 pada 09.25
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
(1, 1, 2, 1, 4, '2026-01-14', '', ''),
(2, 1, 2, 1, 5, '2026-01-14', '', ''),
(3, 1, 2, 1, 8, '2026-01-14', '', ''),
(4, 1, 2, 1, 4, '2026-01-15', 'sakit', ''),
(5, 1, 2, 1, 5, '2026-01-15', 'sakit', ''),
(6, 1, 2, 1, 8, '2026-01-15', 'sakit', ''),
(7, 1, 2, 1, 4, '2026-01-17', 'izin', 'asdas'),
(8, 1, 2, 1, 5, '2026-01-17', 'izin', ''),
(9, 1, 2, 1, 8, '2026-01-17', 'izin', ''),
(10, 4, 2, 2, 9, '2026-01-17', 'sakit', ''),
(11, 4, 2, 2, 6, '2026-01-17', 'sakit', ''),
(12, 1, 0, 0, 4, '2026-01-18', '', 'sasa'),
(13, 1, 0, 0, 5, '2026-01-18', '', 'sa'),
(14, 1, 0, 0, 9, '2026-01-18', '', 'sa'),
(15, 1, 0, 0, 6, '2026-01-18', '', 'sa'),
(16, 1, 0, 0, 8, '2026-01-18', '', 'as');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT '-',
  `semester` varchar(20) DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `wali_kelas_id`, `tahun_ajaran`, `semester`) VALUES
(1, 'X IPA 1', 2, '2022/2026', 'Genap'),
(2, 'X IPS 1', 3, '2025/2026', 'Genap'),
(3, 'XI IPA 1', 7, '2025/2026', 'Ganjil'),
(7, 'X IPA 1', NULL, '2025/2026', 'Ganjil');

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
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tipe` enum('file','link') NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `tanggal_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id_materi`, `mapel_id`, `judul`, `deskripsi`, `tipe`, `file_url`, `tanggal_upload`) VALUES
(3, 4, 's', 'sa', 'file', '1565635774_files-folder.png', '2026-01-17 20:21:49');

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
-- Struktur dari tabel `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id_pengumpulan` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `file_tugas` varchar(255) NOT NULL,
  `tgl_upload` datetime NOT NULL,
  `nilai` int(11) DEFAULT 0,
  `catatan_guru` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tujuan` enum('Semua','Guru','Siswa') NOT NULL DEFAULT 'Semua',
  `file_lampiran` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi`, `tujuan`, `file_lampiran`, `tanggal`) VALUES
(3, 'sa', 'as', 'Semua', '', '2026-01-17 19:55:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rps`
--

CREATE TABLE `rps` (
  `id_rps` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `file_rps` varchar(255) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `keterangan` text DEFAULT NULL,
  `tanggal_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '2025/2026', 'Ganjil', 1),
(4, '2022/2026', 'Genap', 0);

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
  `judul_tugas` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `tgl_kumpul` datetime NOT NULL,
  `tipe` enum('file','link') NOT NULL DEFAULT 'file',
  `file_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `mapel_id`, `judul_tugas`, `deskripsi`, `tgl_buat`, `tgl_kumpul`, `tipe`, `file_url`) VALUES
(1, 3, '32', '23', '2026-01-17 18:09:19', '2026-02-02 12:00:00', 'file', ''),
(3, 4, 'sa', '12', '2026-01-17 20:26:26', '2026-02-02 12:12:00', 'file', '96936645_files-folder.png');

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
  `kelas_id` int(11) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `role`, `kelas_id`, `foto_profil`, `created_at`) VALUES
(1, 'admin', '123', 'Administrator Utama', 'admin@sekolah.sch.id', 'admin', NULL, 'default.jpg', '2026-01-02 11:55:42'),
(2, 'guru1', '123', 'Budi Santoso, S.Pd', 'budi@sekolah.sch.id', 'guru', NULL, 'default.jpg', '2026-01-02 11:55:42'),
(3, 'guru2', '123', 'Siti Aminah, M.Pd', 'siti@sekolah.sch.id', 'guru', NULL, 'default.jpg', '2026-01-02 11:55:42'),
(4, 'siswa1', '123', 'Ahmad Fikri', 'fikri@gmail.com', 'siswa', 1, 'default.jpg', '2026-01-02 11:55:42'),
(5, 'siswa2', '123', 'Dewi Sartika', 'dewi@gmail.com', 'siswa', 1, 'default.jpg', '2026-01-02 11:55:42'),
(6, 'siswa3', '123', 'Rudi Hartono', 'rudi@gmail.com', 'siswa', 1, 'default.jpg', '2026-01-02 11:55:42'),
(7, 'guru3', '123', 'Dodi Kusuma, S.Kom', 'dodi@sekolah.sch.id', 'guru', NULL, 'default.jpg', '2026-01-02 11:55:42'),
(8, 'siswa4', '123', 'Sinta Nuriyah', 'sinta@gmail.com', 'siswa', 1, 'default.jpg', '2026-01-02 11:55:42'),
(9, 'siswa5', '123', 'Joko Anwar', 'joko@gmail.com', 'siswa', 1, 'default.jpg', '2026-01-02 11:55:42'),
(10, 'admin2', '123', 'Staff Tata Usaha', 'tu@sekolah.sch.id', 'admin', NULL, 'default.jpg', '2026-01-02 11:55:42');

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
-- Indeks untuk tabel `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id_pengumpulan`);

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
  ADD PRIMARY KEY (`id_tugas`);

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
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id_pengumpulan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rps`
--
ALTER TABLE `rps`
  MODIFY `id_rps` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `siswa_detail`
--
ALTER TABLE `siswa_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Ketidakleluasaan untuk tabel `siswa_detail`
--
ALTER TABLE `siswa_detail`
  ADD CONSTRAINT `siswa_detail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_detail_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
