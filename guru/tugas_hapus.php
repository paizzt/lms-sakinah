<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// Hapus file soalnya dulu jika ada
$q = mysqli_query($koneksi, "SELECT file_tugas FROM tugas WHERE id_tugas='$id'");
$d = mysqli_fetch_array($q);
if($d['file_tugas'] != ""){
    unlink("../uploads/tugas/".$d['file_tugas']);
}

// Hapus data tugas (Pengumpulan siswa yang terkait akan ikut terhapus jika Anda set foreign key cascade, jika tidak, hapus manual)
mysqli_query($koneksi, "DELETE FROM pengumpulan WHERE tugas_id='$id'"); // Bersihkan pengumpulan dulu
mysqli_query($koneksi, "DELETE FROM tugas WHERE id_tugas='$id'"); // Baru hapus tugas

header("location:tugas.php");
?>