<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_lampiran FROM pengumuman WHERE id_pengumuman='$id'"));
if($cek['file_lampiran'] && file_exists("../uploads/pengumuman/".$cek['file_lampiran'])){
    unlink("../uploads/pengumuman/".$cek['file_lampiran']);
}

$hapus = mysqli_query($koneksi, "DELETE FROM pengumuman WHERE id_pengumuman='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Pengumuman berhasil dihapus!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus!';
}

header("location:pengumuman.php");
?>