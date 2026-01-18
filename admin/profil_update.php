<?php 
session_start();
include '../config/koneksi.php';

// Cek Login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){ header("location:../index.php"); exit(); }

$id        = $_POST['id_user'];
$nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
$username  = mysqli_real_escape_string($koneksi, $_POST['username']);
$email     = mysqli_real_escape_string($koneksi, $_POST['email']);

// Inputan Password
$pass_lama = $_POST['pass_lama'];
$pass_baru = $_POST['pass_baru'];
$pass_konf = $_POST['pass_konf'];

// --- 1. LOGIKA UPDATE PASSWORD ---
$query_pass = "";

// Jika user mengisi password baru
if(!empty($pass_baru)){
    
    // Ambil password lama dari database
    $cek_db = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user='$id'");
    $data_db = mysqli_fetch_assoc($cek_db);
    $password_db = $data_db['password'];

    // Validasi 1: Apakah password lama benar?
    if($pass_lama == $password_db){
        
        // Validasi 2: Apakah password baru & konfirmasi sama?
        if($pass_baru == $pass_konf){
            $query_pass = ", password='$pass_baru'";
        } else {
            $_SESSION['notif_status'] = 'gagal';
            $_SESSION['notif_pesan']  = 'Konfirmasi password baru tidak cocok!';
            header("location:profil.php");
            exit();
        }

    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Password lama salah! Perubahan ditolak.';
        header("location:profil.php");
        exit();
    }
}

// --- 2. LOGIKA UPDATE FOTO ---
$query_foto = "";
$filename   = $_FILES['foto']['name'];

if($filename != ""){
    $rand = rand();
    $allowed = array('png','jpg','jpeg');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(in_array($ext, $allowed)){
        // Hapus foto lama
        $data_lama = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id_user='$id'"));
        if($data_lama['foto_profil'] != "default.jpg" && file_exists("../uploads/profil/".$data_lama['foto_profil'])){
            unlink("../uploads/profil/".$data_lama['foto_profil']);
        }

        $foto_baru = $rand.'_'.$filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/profil/'.$foto_baru);
        
        $query_foto = ", foto_profil='$foto_baru'";
        $_SESSION['foto_profil'] = $foto_baru; // Update session foto
    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Format foto harus PNG/JPG!';
        header("location:profil.php");
        exit();
    }
}

// --- 3. EKSEKUSI UPDATE DATABASE ---
$query = "UPDATE users SET 
            nama_lengkap='$nama', 
            username='$username', 
            email='$email' 
            $query_pass 
            $query_foto 
          WHERE id_user='$id'";

$update = mysqli_query($koneksi, $query);

if($update){
    $_SESSION['nama'] = $nama; // Update session nama
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Profil berhasil diperbarui!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal update database!';
}

header("location:profil.php");
?>