<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$judul = $_POST['judul'];
$isi = $_POST['isi'];
$tujuan = $_POST['tujuan'];
$tanggal = date('Y-m-d H:i:s');

// Upload Gambar
$rand = rand();
$filename = $_FILES['gambar']['name'];
$gambar_baru = "";

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_ext = array('jpg','jpeg','png');
    
    if(in_array(strtolower($ext), $valid_ext)){
        $gambar_baru = $rand.'_'.$filename;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/berita/'.$gambar_baru);
    }
}

mysqli_query($koneksi, "INSERT INTO pengumuman (judul, isi, gambar, tujuan, tanggal_dibuat) VALUES ('$judul', '$isi', '$gambar_baru', '$tujuan', '$tanggal')");

header("location:pengumuman.php");
?>