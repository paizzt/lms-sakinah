<?php 
session_start();
include 'config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($koneksi,"SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($login);

if($cek > 0){
    $data = mysqli_fetch_assoc($login);
    
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['username'] = $username;
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = "login";

    if($data['role'] == "admin"){
        header("location:admin/index.php");
    } else if($data['role'] == "guru"){
        header("location:guru/index.php");
    } else if($data['role'] == "siswa"){
        header("location:siswa/index.php");
    } else {
        header("location:login.php?pesan=gagal");
    }
} else {
    // PENTING: Mengirim sinyal gagal ke halaman login
    header("location:login.php?pesan=gagal");
}
?>