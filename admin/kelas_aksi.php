<?php 
session_start();
include '../config/koneksi.php';

// Validasi Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

// 1. TANGKAP SEMUA INPUT (TERMASUK TAHUN & SEMESTER)
$nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
$wali_kelas = $_POST['wali_kelas'];
$tahun      = $_POST['tahun'];     // <-- Ini yang sebelumnya mungkin belum tertangkap
$semester   = $_POST['semester'];  // <-- Ini juga

// Logika Wali Kelas (NULL jika kosong)
if(empty($wali_kelas)){
    $wali_query = "NULL";
} else {
    $wali_query = "'$wali_kelas'";
}

// 2. CEK DUPLIKASI DATA (Nama + Tahun + Semester tidak boleh sama persis)
$cek = mysqli_query($koneksi, "SELECT * FROM kelas WHERE nama_kelas='$nama_kelas' AND tahun_ajaran='$tahun' AND semester='$semester'");

if(mysqli_num_rows($cek) > 0){
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'Kelas tersebut sudah ada di Tahun/Semester ini!';
    header("location:kelas.php");
    exit();
}

// 3. SIMPAN KE DATABASE (Pastikan kolom tahun_ajaran & semester ikut di-INSERT)
$query = "INSERT INTO kelas (nama_kelas, wali_kelas_id, tahun_ajaran, semester) 
          VALUES ('$nama_kelas', $wali_query, '$tahun', '$semester')";

$simpan = mysqli_query($koneksi, $query);

if($simpan){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Kelas baru berhasil ditambahkan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menyimpan data! Periksa struktur database.';
}

header("location:kelas.php");
?>