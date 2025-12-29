<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id_user = $_POST['id_user'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$role_lama = $_POST['role_lama'];

// 1. Update Tabel Users
if($password == ""){
    // Jika password kosong, jangan update password
    $query = "UPDATE users SET nama_lengkap='$nama', username='$username' WHERE id_user='$id_user'";
} else {
    // Jika password diisi
    $query = "UPDATE users SET nama_lengkap='$nama', username='$username', password='$password' WHERE id_user='$id_user'";
}

mysqli_query($koneksi, $query);

// 2. Update Tabel Siswa_Detail (Jika user adalah siswa)
if($role_lama == 'siswa'){
    $kelas_id = $_POST['kelas_id'];
    
    // Pastikan nilai kelas_id valid (tidak string kosong)
    if($kelas_id == ""){
        $update_kelas = "UPDATE siswa_detail SET kelas_id=NULL WHERE user_id='$id_user'";
    } else {
        $update_kelas = "UPDATE siswa_detail SET kelas_id='$kelas_id' WHERE user_id='$id_user'";
    }
    
    mysqli_query($koneksi, $update_kelas);
}

header("location:users.php");
?>