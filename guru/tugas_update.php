<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id        = $_POST['id_tugas'];
$judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
$mapel     = $_POST['mapel'];
$deadline  = $_POST['deadline'];
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$tipe      = $_POST['tipe'];

// Cek file lama
$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_url, tipe FROM tugas WHERE id_tugas='$id'"));
$file_url = $cek['file_url'];

if($tipe == 'file'){
    $filename = $_FILES['file_tugas']['name'];
    if($filename != ""){
        $rand = rand();
        $allowed = array('pdf','doc','docx','ppt','pptx','xls','xlsx','png','jpg','jpeg','zip','rar');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext, $allowed)){
            // Hapus file lama jika tipe file
            if($cek['tipe'] == 'file' && file_exists("../uploads/tugas_guru/".$cek['file_url'])){
                unlink("../uploads/tugas_guru/".$cek['file_url']);
            }
            $file_url = $rand.'_'.$filename;
            move_uploaded_file($_FILES['file_tugas']['tmp_name'], '../uploads/tugas_guru/'.$file_url);
        }
    }
} else {
    $file_url = mysqli_real_escape_string($koneksi, $_POST['link_tugas']);
}

$query = "UPDATE tugas SET 
            mapel_id='$mapel', judul_tugas='$judul', deskripsi='$deskripsi', tgl_kumpul='$deadline', tipe='$tipe', file_url='$file_url'
          WHERE id_tugas='$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Tugas berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal update tugas!';
}

header("location:tugas.php");
?>