<?php 
session_start();
include '../config/koneksi.php';

// Validasi Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

// Tangkap Data
$id = $_POST['id_mapel'];
$kode = $_POST['kode_mapel'];
$nama = $_POST['nama_mapel'];
$kelas = $_POST['kelas_id'];
$guru = $_POST['guru_id'];
$hari = $_POST['hari'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];

// Query Update
$query = "UPDATE mapel SET 
            kode_mapel='$kode', 
            nama_mapel='$nama', 
            kelas_id='$kelas', 
            guru_id='$guru', 
            hari='$hari', 
            jam_mulai='$jam_mulai', 
            jam_selesai='$jam_selesai' 
          WHERE id_mapel='$id'";

mysqli_query($koneksi, $query);

// Kembali ke halaman mapel
header("location:mapel.php");
?>