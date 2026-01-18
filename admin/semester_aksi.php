<?php 
session_start();
include '../config/koneksi.php';

// Validasi Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$tahun = $_POST['tahun'];
$semester = $_POST['semester'];
$status = 0; 

// Cek apakah data tahun & semester tersebut sudah ada?
$cek = mysqli_query($koneksi, "SELECT * FROM semester WHERE tahun_ajaran='$tahun' AND semester='$semester'");

if(mysqli_num_rows($cek) > 0){
    // Kirim sinyal GAGAL
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'Data Semester tersebut sudah ada!';
    header("location:semester.php");
} else {
    // Simpan data
    $simpan = mysqli_query($koneksi, "INSERT INTO semester (tahun_ajaran, semester, status) VALUES ('$tahun', '$semester', '$status')");
    
    if($simpan) {
        // Kirim sinyal SUKSES
        $_SESSION['notif_status'] = 'sukses';
        $_SESSION['notif_pesan']  = 'Semester baru berhasil ditambahkan!';
    } else {
        // Kirim sinyal ERROR DATABASE
        $_SESSION['notif_status'] = 'error';
        $_SESSION['notif_pesan']  = 'Terjadi kesalahan sistem!';
    }
    header("location:semester.php");
}
?>