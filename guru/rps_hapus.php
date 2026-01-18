<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_rps FROM rps WHERE id_rps='$id'"));
if(file_exists("../uploads/rps/".$cek['file_rps'])){
    unlink("../uploads/rps/".$cek['file_rps']);
}

$hapus = mysqli_query($koneksi, "DELETE FROM rps WHERE id_rps='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'RPS berhasil dihapus!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus data!';
}

header("location:rps.php");
?>