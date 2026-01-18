<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$mapel      = $_POST['mapel'];
$status     = $_POST['status'];
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
$tanggal    = date('Y-m-d H:i:s');

// UPLOAD FILE
$rand = rand();
$allowed = array('pdf','doc','docx');
$filename = $_FILES['file_rps']['name'];

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(in_array($ext, $allowed)){
        $nama_file = $rand.'_'.$filename;
        // Pastikan folder ini ada: uploads/rps/
        if(!is_dir("../uploads/rps")) mkdir("../uploads/rps");
        
        move_uploaded_file($_FILES['file_rps']['tmp_name'], '../uploads/rps/'.$nama_file);
        
        $query = "INSERT INTO rps (mapel_id, file_rps, status, keterangan, tanggal_upload) 
                  VALUES ('$mapel', '$nama_file', '$status', '$keterangan', '$tanggal')";
                  
        if(mysqli_query($koneksi, $query)){
            $_SESSION['notif_status'] = 'sukses';
            $_SESSION['notif_pesan']  = 'RPS berhasil diupload!';
        } else {
            $_SESSION['notif_status'] = 'error';
            $_SESSION['notif_pesan']  = 'Gagal simpan database!';
        }
    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Format file harus PDF atau DOC/DOCX!';
    }
} else {
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'File wajib dipilih!';
}

header("location:rps.php");
?>