<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_url, tipe FROM materi WHERE id_materi='$id'"));

if($data['tipe'] == 'file'){
    if(file_exists("../uploads/materi/".$data['file_url'])){
        unlink("../uploads/materi/".$data['file_url']);
    }
}

$hapus = mysqli_query($koneksi, "DELETE FROM materi WHERE id_materi='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Materi berhasil dihapus!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus data!';
}

header("location:materi.php");
?>