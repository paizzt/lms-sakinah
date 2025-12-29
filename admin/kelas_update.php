<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id = $_POST['id_kelas'];
$nama = $_POST['nama_kelas'];
$wali = $_POST['wali_kelas'];

mysqli_query($koneksi, "UPDATE kelas SET nama_kelas='$nama', wali_kelas_id='$wali' WHERE id_kelas='$id'");

header("location:kelas.php");
?>