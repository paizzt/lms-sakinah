<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM mapel WHERE id_mapel='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Mata Pelajaran berhasil dihapus!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus data!';
}

header("location:mapel.php");
?>