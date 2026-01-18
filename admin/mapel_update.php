<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id     = $_POST['id_mapel'];
$kode   = mysqli_real_escape_string($koneksi, $_POST['kode_mapel']);
$nama   = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
$kelas  = $_POST['kelas_id'];
$guru   = $_POST['guru_id'];
$hari   = $_POST['hari'];
$mulai  = $_POST['jam_mulai'];
$selesai= $_POST['jam_selesai'];

$query = "UPDATE mapel SET 
            kode_mapel='$kode', 
            nama_mapel='$nama', 
            guru_id='$guru', 
            kelas_id='$kelas', 
            hari='$hari', 
            jam_mulai='$mulai', 
            jam_selesai='$selesai' 
          WHERE id_mapel='$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Data Mapel berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal memperbarui data!';
}

header("location:mapel.php");
?>