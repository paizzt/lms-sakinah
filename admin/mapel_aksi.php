<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$kode = $_POST['kode_mapel'];
$nama = $_POST['nama_mapel'];
$kelas = $_POST['kelas_id'];
$guru = $_POST['guru_id'];
$hari = $_POST['hari'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];

mysqli_query($koneksi, "INSERT INTO mapel (kode_mapel, nama_mapel, kelas_id, guru_id, hari, jam_mulai, jam_selesai) 
                        VALUES ('$kode', '$nama', '$kelas', '$guru', '$hari', '$jam_mulai', '$jam_selesai')");

header("location:mapel.php");
?>