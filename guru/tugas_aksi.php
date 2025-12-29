<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$mapel_id = $_POST['mapel_id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$tipe = $_POST['tipe'];
$deadline = $_POST['deadline'];

// Upload File Soal (Jika ada)
$rand = rand();
$filename = $_FILES['file_tugas']['name'];
$file_baru = "";

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid = array('pdf','doc','docx','xls','xlsx','jpg','png');
    
    if(in_array(strtolower($ext), $valid)){
        $file_baru = $rand.'_'.$filename;
        move_uploaded_file($_FILES['file_tugas']['tmp_name'], '../uploads/tugas/'.$file_baru);
    }
}

// Simpan ke Database
// Pastikan folder uploads/tugas sudah dibuat
mysqli_query($koneksi, "INSERT INTO tugas (mapel_id, judul_tugas, deskripsi, deadline, tipe, file_tugas) 
                        VALUES ('$mapel_id', '$judul', '$deskripsi', '$deadline', '$tipe', '$file_baru')");

header("location:tugas.php");
?>