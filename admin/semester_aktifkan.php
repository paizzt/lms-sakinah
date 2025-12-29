<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// 1. Non-aktifkan semua semester
mysqli_query($koneksi, "UPDATE semester SET status=0");

// 2. Aktifkan semester yang dipilih
mysqli_query($koneksi, "UPDATE semester SET status=1 WHERE id_semester='$id'");

header("location:semester.php");
?>