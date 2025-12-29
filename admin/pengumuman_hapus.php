<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// Ambil nama gambar dulu
$q = mysqli_query($koneksi, "SELECT gambar FROM pengumuman WHERE id_pengumuman='$id'");
$d = mysqli_fetch_array($q);

// Hapus file gambar jika ada
if($d['gambar'] != ""){
    $path = "../uploads/berita/".$d['gambar'];
    if(file_exists($path)){
        unlink($path);
    }
}

// Hapus data dari database
mysqli_query($koneksi, "DELETE FROM pengumuman WHERE id_pengumuman='$id'");

header("location:pengumuman.php");
?>