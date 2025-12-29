<?php 
// Mengaktifkan session php
session_start();

// Menghubungkan dengan koneksi
include 'config/koneksi.php';

// Menangkap data yang dikirim dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Menyeleksi data user dengan username dan password yang sesuai
// PENTING: Gunakan prepared statement untuk keamanan di produksi
$login = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);

if($cek > 0){
    $data = mysqli_fetch_assoc($login);

    // Buat session login dan username
    $_SESSION['username'] = $username;
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['status'] = "login";

    // Cek role user dan alihkan ke halaman dashboard masing-masing
    if($data['role'] == "admin"){
        // Alihkan ke halaman dashboard admin
        header("location:admin/index.php");

    } else if($data['role'] == "guru"){
        // Alihkan ke halaman dashboard guru
        header("location:guru/index.php");

    } else if($data['role'] == "siswa"){
        // Alihkan ke halaman dashboard siswa
        header("location:siswa/index.php");

    } else {
        // Jika role tidak dikenali
        header("location:index.php?pesan=gagal");
    }

} else {
    // Jika username/password tidak ditemukan
    header("location:index.php?pesan=gagal");
}
?>