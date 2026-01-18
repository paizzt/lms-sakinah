<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
$isi     = mysqli_real_escape_string($koneksi, $_POST['isi']);
$tujuan  = $_POST['tujuan'];
$tanggal = date('Y-m-d H:i:s');
$lampiran = NULL;

// Upload File
$filename = $_FILES['file_lampiran']['name'];
if($filename != ""){
    $rand = rand();
    $allowed = array('pdf','doc','docx','jpg','png','jpeg');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(in_array($ext, $allowed)){
        $lampiran = $rand.'_'.$filename;
        if(!is_dir("../uploads/pengumuman")) mkdir("../uploads/pengumuman");
        move_uploaded_file($_FILES['file_lampiran']['tmp_name'], '../uploads/pengumuman/'.$lampiran);
    }
}

$query = "INSERT INTO pengumuman (judul, isi, tujuan, file_lampiran, tanggal) 
          VALUES ('$judul', '$isi', '$tujuan', '$lampiran', '$tanggal')";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Pengumuman berhasil diposting!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal memposting!';
}

header("location:pengumuman.php");
?>