<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id = $_GET['id'];

// Hapus File Guru (Soal)
$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_url, tipe FROM tugas WHERE id_tugas='$id'"));
if($cek['tipe'] == 'file' && file_exists("../uploads/tugas_guru/".$cek['file_url'])){
    unlink("../uploads/tugas_guru/".$cek['file_url']);
}

// Hapus File Siswa (Jawaban)
$q_siswa = mysqli_query($koneksi, "SELECT file_tugas FROM pengumpulan_tugas WHERE tugas_id='$id'");
while($f = mysqli_fetch_array($q_siswa)){
    if(file_exists("../uploads/tugas_siswa/".$f['file_tugas'])){
        unlink("../uploads/tugas_siswa/".$f['file_tugas']);
    }
}

// Hapus Data DB
mysqli_query($koneksi, "DELETE FROM pengumpulan_tugas WHERE tugas_id='$id'");
$hapus = mysqli_query($koneksi, "DELETE FROM tugas WHERE id_tugas='$id'");

if($hapus){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Tugas berhasil dihapus!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menghapus data!';
}

header("location:monitoring_tugas.php");
?>