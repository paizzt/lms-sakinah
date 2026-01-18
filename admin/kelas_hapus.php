<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

// Hapus Kelas
$hapus = mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Kelas berhasil dihapus!';
} else {
    // Biasanya gagal jika ada relasi Foreign Key (misal ada siswa di kelas tersebut)
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus! Pastikan kelas kosong dari siswa/mapel.';
}

header("location:kelas.php");
?>