<?php 
session_start();
include '../config/koneksi.php';

// Cek akses guru
if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id_pengumpulan = $_POST['id_pengumpulan'];
$id_tugas = $_POST['id_tugas'];
$nilai = $_POST['nilai'];
$komentar = $_POST['komentar'];

// Update database
mysqli_query($koneksi, "UPDATE pengumpulan SET nilai='$nilai', komentar_guru='$komentar' WHERE id_pengumpulan='$id_pengumpulan'");

// Kembali ke daftar pengumpulan tugas tersebut
header("location:tugas_lihat.php?id=$id_tugas");
?>