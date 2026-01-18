<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id         = $_POST['id_rps'];
$mapel      = $_POST['mapel'];
$status     = $_POST['status'];
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

// Ambil data lama
$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_rps FROM rps WHERE id_rps='$id'"));
$nama_file = $cek['file_rps'];

// Cek Upload Baru
$filename = $_FILES['file_rps']['name'];
if($filename != ""){
    $rand = rand();
    $allowed = array('pdf','doc','docx');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(in_array($ext, $allowed)){
        // Hapus file lama
        if(file_exists("../uploads/rps/".$nama_file)){
            unlink("../uploads/rps/".$nama_file);
        }
        
        $nama_file = $rand.'_'.$filename;
        move_uploaded_file($_FILES['file_rps']['tmp_name'], '../uploads/rps/'.$nama_file);
    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Format file tidak valid!';
        header("location:rps.php");
        exit();
    }
}

$query = "UPDATE rps SET 
            mapel_id='$mapel', 
            status='$status', 
            keterangan='$keterangan',
            file_rps='$nama_file'
          WHERE id_rps='$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Data RPS berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal update data!';
}

header("location:rps.php");
?>