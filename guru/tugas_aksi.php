<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
$mapel     = $_POST['mapel'];
$deadline  = $_POST['deadline'];
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$tipe      = $_POST['tipe'];
$tgl_buat  = date('Y-m-d H:i:s');
$file_url  = "";

if($tipe == 'file'){
    $filename = $_FILES['file_tugas']['name'];
    if($filename != ""){
        $rand = rand();
        $allowed = array('pdf','doc','docx','ppt','pptx','xls','xlsx','png','jpg','jpeg','zip','rar');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext, $allowed)){
            $file_url = $rand.'_'.$filename;
            // Pastikan folder ini sama dengan Admin (Shared folder)
            move_uploaded_file($_FILES['file_tugas']['tmp_name'], '../uploads/tugas_guru/'.$file_url);
        }
    }
} else {
    $file_url = mysqli_real_escape_string($koneksi, $_POST['link_tugas']);
}

$query = "INSERT INTO tugas (mapel_id, judul_tugas, deskripsi, tgl_buat, tgl_kumpul, tipe, file_url) 
          VALUES ('$mapel', '$judul', '$deskripsi', '$tgl_buat', '$deadline', '$tipe', '$file_url')";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Tugas berhasil dibuat!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal membuat tugas!';
}

header("location:tugas.php");
?>