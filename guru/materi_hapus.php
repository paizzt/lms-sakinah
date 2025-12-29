<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// Ambil info file dulu untuk dihapus dari folder
$q = mysqli_query($koneksi, "SELECT file_materi FROM materi WHERE id_materi='$id'");
$d = mysqli_fetch_array($q);

if($d['file_materi'] != ""){
    unlink("../uploads/materi/".$d['file_materi']);
}

mysqli_query($koneksi, "DELETE FROM materi WHERE id_materi='$id'");
header("location:materi.php");
?>