<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$kode   = mysqli_real_escape_string($koneksi, $_POST['kode_mapel']);
$nama   = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
$kelas  = $_POST['kelas_id'];
$guru   = $_POST['guru_id'];
$hari   = $_POST['hari'];
$mulai  = $_POST['jam_mulai'];
$selesai= $_POST['jam_selesai'];

// Cek kode mapel kembar di kelas yang sama
$cek = mysqli_query($koneksi, "SELECT * FROM mapel WHERE kode_mapel='$kode' AND kelas_id='$kelas'");
if(mysqli_num_rows($cek) > 0){
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'Kode Mapel sudah ada di kelas ini!';
    header("location:mapel.php");
    exit();
}

$query = "INSERT INTO mapel (kode_mapel, nama_mapel, guru_id, kelas_id, hari, jam_mulai, jam_selesai) 
          VALUES ('$kode', '$nama', '$guru', '$kelas', '$hari', '$mulai', '$selesai')";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Mata Pelajaran berhasil ditambahkan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menyimpan data!';
}

header("location:mapel.php");
?>