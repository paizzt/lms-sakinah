<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id        = $_POST['id_materi'];
$judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
$mapel     = $_POST['mapel'];
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$tipe      = $_POST['tipe'];

// Ambil data lama untuk cek file lama
$data_lama = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_url, tipe FROM materi WHERE id_materi='$id'"));
$file_url  = $data_lama['file_url'];

if($tipe == 'file'){
    // Cek apakah user upload file baru?
    $filename = $_FILES['file_materi']['name'];
    if($filename != ""){
        $rand = rand();
        $allowed = array('pdf','doc','docx','ppt','pptx','xls','xlsx','png','jpg','jpeg');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array($ext, $allowed)){
            // Hapus file lama jika ada (dan tipe lamanya juga file)
            if($data_lama['tipe'] == 'file' && file_exists("../uploads/materi/".$data_lama['file_url'])){
                unlink("../uploads/materi/".$data_lama['file_url']);
            }
            
            $file_url = $rand.'_'.$filename;
            move_uploaded_file($_FILES['file_materi']['tmp_name'], '../uploads/materi/'.$file_url);
        } else {
            $_SESSION['notif_status'] = 'gagal';
            $_SESSION['notif_pesan']  = 'Format file tidak valid!';
            header("location:materi.php");
            exit();
        }
    }
} else {
    // Jika ganti ke Link, update URL-nya
    $file_url = mysqli_real_escape_string($koneksi, $_POST['link_materi']);
}

$query = "UPDATE materi SET 
            mapel_id='$mapel', 
            judul='$judul', 
            deskripsi='$deskripsi', 
            tipe='$tipe', 
            file_url='$file_url' 
          WHERE id_materi='$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Materi berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal update data!';
}

header("location:materi.php");
?>