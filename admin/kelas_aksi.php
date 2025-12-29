<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$nama = $_POST['nama_kelas'];
$wali = $_POST['wali_kelas'];

mysqli_query($koneksi, "INSERT INTO kelas (nama_kelas, wali_kelas_id) VALUES ('$nama', '$wali')");

header("location:kelas.php");
?>