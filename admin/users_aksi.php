<?php 
session_start();
include '../config/koneksi.php';

// Validasi Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email    = mysqli_real_escape_string($koneksi, $_POST['email']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);
$role     = $_POST['role'];

// 1. Cek Username Kembar
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
if(mysqli_num_rows($cek) > 0){
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'Username sudah digunakan, silakan ganti!';
    header("location:users.php");
    exit();
}

// 2. Upload Foto (Jika Ada)
$rand = rand();
$allowed = array('png','jpg','jpeg');
$filename = $_FILES['foto']['name'];
$foto_nama = 'default.jpg'; // Default jika tidak upload

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(in_array($ext, $allowed)){
        $foto_nama = $rand.'_'.$filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/profil/'.$foto_nama);
    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Format foto harus JPG atau PNG!';
        header("location:users.php");
        exit();
    }
}

// 3. Simpan ke Database
$insert = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, email, username, password, role, foto_profil) 
                                  VALUES ('$nama', '$email', '$username', '$password', '$role', '$foto_nama')");

if($insert){
    // JIKA SISWA, BUAT DATA DUMMY DI SISWA_DETAIL (PENTING AGAR MUNCUL DI QUERY JOIN)
    if($role == 'siswa'){
        $last_id = mysqli_insert_id($koneksi);
        // Isi NIS sementara dengan "SISWA-[ID]" agar tidak error duplicate
        $nis_temp = "SISWA-".$last_id;
        mysqli_query($koneksi, "INSERT INTO siswa_detail (user_id, nis, kelas_id) VALUES ('$last_id', '$nis_temp', NULL)");
    }

    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'User baru berhasil ditambahkan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Terjadi kesalahan database!';
}

header("location:users.php");
?>