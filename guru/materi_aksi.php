<?php 
session_start();
include '../config/koneksi.php';

// Pastikan Guru Login
if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
$mapel     = $_POST['mapel'];
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$tipe      = $_POST['tipe'];
$tanggal   = date('Y-m-d H:i:s');
$file_url  = "";

// LOGIKA UPLOAD
if($tipe == 'file'){
    $rand = rand();
    $allowed = array('pdf','doc','docx','ppt','pptx','xls','xlsx','png','jpg','jpeg');
    $filename = $_FILES['file_materi']['name'];
    
    if($filename != ""){
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext, $allowed)){
            $file_url = $rand.'_'.$filename;
            // Pastikan folder ini sama dengan Admin (Shared folder)
            move_uploaded_file($_FILES['file_materi']['tmp_name'], '../uploads/materi/'.$file_url);
        } else {
            $_SESSION['notif_status'] = 'gagal';
            $_SESSION['notif_pesan']  = 'Format file tidak diizinkan!';
            header("location:materi.php");
            exit();
        }
    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'File wajib diupload!';
        header("location:materi.php");
        exit();
    }
} else {
    $file_url = mysqli_real_escape_string($koneksi, $_POST['link_materi']);
}

$query = "INSERT INTO materi (mapel_id, judul, deskripsi, tipe, file_url, tanggal_upload) 
          VALUES ('$mapel', '$judul', '$deskripsi', '$tipe', '$file_url', '$tanggal')";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Materi berhasil ditambahkan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menyimpan data!';
}

header("location:materi.php");
?>