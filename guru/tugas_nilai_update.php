<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id_tugas = $_POST['id_tugas'];
$id_siswa = $_POST['id_siswa'];
$nilai = $_POST['nilai'];
$komentar = $_POST['komentar'];

// Update tabel pengumpulan
$query = "UPDATE pengumpulan SET nilai='$nilai', komentar_guru='$komentar' 
          WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'";

if(mysqli_query($koneksi, $query)){
    // Redirect kembali ke halaman penilaian
    header("location:tugas_nilai.php?id=$id_tugas");
} else {
    echo "Gagal menyimpan nilai.";
}
?>