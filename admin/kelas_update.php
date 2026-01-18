<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id_kelas   = $_POST['id_kelas'];
$nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
$wali_kelas = $_POST['wali_kelas'];
$tahun      = $_POST['tahun'];
$semester   = $_POST['semester'];

if(empty($wali_kelas)){ $wali_query = "NULL"; } else { $wali_query = "'$wali_kelas'"; }

// Update Database dengan Tahun & Semester
$query = "UPDATE kelas SET 
            nama_kelas='$nama_kelas', 
            wali_kelas_id=$wali_query, 
            tahun_ajaran='$tahun', 
            semester='$semester' 
          WHERE id_kelas='$id_kelas'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Data kelas berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal memperbarui data!';
}

header("location:kelas.php");
?>