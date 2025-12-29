# 🎓 SASMART - LMS Sakinah Boarding School

**SASMART (Sakinah Smart Learning)** adalah sistem manajemen pembelajaran (Learning Management System) berbasis web yang dikembangkan untuk SMAIT As-Sakinah. Sistem ini memfasilitasi kegiatan belajar mengajar secara digital, mulai dari distribusi materi, pengumpulan tugas, absensi, hingga penilaian.

![SASMART Preview](assets/img/logo_sbs.png) 
*(bisa mengganti gambar ini dengan screenshot dashboard aplikasi)*

---

## 🚀 Fitur Utama

Sistem ini memiliki 3 hak akses pengguna (*Multi-User Role*):

### 👨‍💻 Administrator
* **Dashboard Statistik:** Melihat ringkasan jumlah guru, siswa, dan admin.
* **Manajemen User:** CRUD (Create, Read, Update, Delete) data Admin, Guru, dan Siswa.
* **Manajemen Akademik:** Mengelola Tahun Ajaran (Semester Aktif), Data Kelas, dan Mata Pelajaran.
* **Monitoring:** Memantau aktivitas guru (Materi, Tugas, RPS) dan Rekap Absensi.
* **Pengumuman:** Membuat berita atau informasi sekolah.

### 👩‍🏫 Guru (Pengajar)
* **Manajemen Materi:** Upload bahan ajar (PDF, Word, PPT) atau Link (YouTube/Artikel).
* **Manajemen Tugas/Kuis:** Membuat tugas dengan deadline otomatis.
* **Penilaian:** Memeriksa tugas siswa, memberikan nilai, dan feedback komentar.
* **Absensi Digital:** Input kehadiran siswa berdasarkan Mata Pelajaran dan Kelas.
* **Kelola RPS:** Upload Rencana Pembelajaran Semester.
* **Profil:** Mengelola biodata dan keamanan akun.

### 👨‍🎓 Siswa
* **Ruang Kelas Virtual:** Melihat daftar mata pelajaran yang diikuti.
* **Akses Materi:** Mengunduh materi pelajaran dari guru.
* **Kirim Tugas:** Mengupload jawaban tugas sebelum deadline berakhir.
* **Rekap Nilai:** Melihat hasil penilaian dan rata-rata nilai per mapel.
* **Info Akademik:** Melihat jadwal pelajaran, RPS, dan Pengumuman Sekolah.

---

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman:** PHP (Native),JS
* **Database:** MySQL (MariaDB)
* **Frontend:** HTML5, CSS3 (Custom Modern Style), JavaScript
* **Icons:** Font Awesome 5
* **Server:** Apache (via XAMPP/Laragon)

---

## 📂 Struktur Folder

```text
lms-sakinah/
├── admin/          # Halaman khusus Administrator
├── assets/         # CSS, Images, Fonts
├── config/         # Koneksi database (koneksi.php)
├── guru/           # Halaman khusus Guru
├── siswa/          # Halaman khusus Siswa
├── uploads/        # Folder penyimpanan file (Materi, Tugas, Profil, Berita)
├── index.php       # Halaman Landing Page
├── login.php       # Halaman Login
└── ...
Cara Instalasi (Localhost)
Ikuti langkah-langkah ini untuk menjalankan aplikasi di komputer Anda:

Download/Clone Repository Simpan folder lms-sakinah ke dalam folder htdocs (jika menggunakan XAMPP) atau www (jika menggunakan Laragon).

Buat Database

Buka phpMyAdmin (http://localhost/phpmyadmin).

Buat database baru dengan nama: db_lms_sakinah.

Import Database

Klik tab Import.

Pilih file db_lms_sakinah.sql yang disertakan dalam proyek ini.

Klik Go / Kirim.

Konfigurasi Koneksi (Opsional) Jika Anda menggunakan password database khusus, edit file config/koneksi.php:

PHP

$server = "localhost";
$user = "root";
$pass = ""; // Isi password database jika ada
$database = "db_lms_sakinah";
Jalankan Aplikasi Buka browser dan akses: http://localhost/lms-sakinah

Role	Username	Password
Administrator	admin	123
Guru	guru1	123
Siswa	siswa1	123