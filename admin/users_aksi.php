<?php 
session_start();
include '../config/koneksi.php';

// Cek akses admin
if($_SESSION['role'] != "admin"){
    header("location:../index.php");
    exit();
}

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password']; // Di real project, gunakan password_hash($password, PASSWORD_DEFAULT)
$role = $_POST['role'];

// Insert data ke database
mysqli_query($koneksi, "INSERT INTO users VALUES(NULL, '$username', '$password', '$nama', '$role', 'default.jpg', NULL)");

// Kembali ke halaman users
header("location:users.php");
?>