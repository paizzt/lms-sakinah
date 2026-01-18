<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id      = $_POST['id'];
$judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
$isi     = mysqli_real_escape_string($koneksi, $_POST['isi']);
$tujuan  = $_POST['tujuan'];

// Cek file lama
$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_lampiran FROM pengumuman WHERE id_pengumuman='$id'"));
$lampiran = $cek['file_lampiran'];

// Upload Baru
$filename = $_FILES['file_lampiran']['name'];
if($filename != ""){
    $rand = rand();
    $allowed = array('pdf','doc','docx','jpg','png','jpeg');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(in_array($ext, $allowed)){
        if($lampiran && file_exists("../uploads/pengumuman/".$lampiran)){
            unlink("../uploads/pengumuman/".$lampiran);
        }
        $lampiran = $rand.'_'.$filename;
        move_uploaded_file($_FILES['file_lampiran']['tmp_name'], '../uploads/pengumuman/'.$lampiran);
    }
}

$query = "UPDATE pengumuman SET 
            judul='$judul', 
            isi='$isi', 
            tujuan='$tujuan', 
            file_lampiran='$lampiran' 
          WHERE id_pengumuman='$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Pengumuman berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal update!';
}

header("location:pengumuman.php");
?>