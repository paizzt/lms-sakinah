<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// Set NULL kelas_id pada siswa yang ada di kelas ini agar tidak error
mysqli_query($koneksi, "UPDATE siswa_detail SET kelas_id=NULL WHERE kelas_id='$id'");

// Hapus kelas
mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas='$id'");

header("location:kelas.php");
?>