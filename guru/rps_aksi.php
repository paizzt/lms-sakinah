<?php 
session_start();
include '../config/koneksi.php';
if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$mapel_id = $_POST['mapel_id'];
$deskripsi = $_POST['deskripsi'];
$tanggal = date('Y-m-d H:i:s');

// Upload File
$rand = rand();
$filename = $_FILES['file_rps']['name'];

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $file_baru = $rand.'_'.$filename;
    move_uploaded_file($_FILES['file_rps']['tmp_name'], '../uploads/rps/'.$file_baru);
    
    // Simpan ke DB
    mysqli_query($koneksi, "INSERT INTO rps (mapel_id, deskripsi, file_rps, tanggal_upload) VALUES ('$mapel_id', '$deskripsi', '$file_baru', '$tanggal')");
    
    echo "<script>alert('RPS Berhasil diupload!'); window.location='rps.php';</script>";
} else {
    header("location:rps.php");
}
?>