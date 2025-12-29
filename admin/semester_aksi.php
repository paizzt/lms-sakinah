<?php 
session_start();
include '../config/koneksi.php';

// Validasi Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$tahun = $_POST['tahun_ajaran'];
$semester = $_POST['semester'];
$status = 0; // Default Non-Aktif saat baru ditambah

// Cek apakah data tahun & semester tersebut sudah ada?
$cek = mysqli_query($koneksi, "SELECT * FROM semester WHERE tahun_ajaran='$tahun' AND semester='$semester'");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Data Semester tersebut sudah ada!'); window.location='semester_tambah.php';</script>";
} else {
    // Simpan data
    mysqli_query($koneksi, "INSERT INTO semester (tahun_ajaran, semester, status) VALUES ('$tahun', '$semester', '$status')");
    echo "<script>alert('Semester berhasil ditambahkan!'); window.location='semester.php';</script>";
}
?>