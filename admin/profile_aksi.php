<?php 
session_start();
include '../config/koneksi.php';

// Validasi Role Admin
if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id_user = $_SESSION['id_user'];
$act = $_POST['act'];

// --- 1. UPDATE INFO AKUN (Nama, Username, Email) ---
if($act == 'update_bio'){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Cek apakah username sudah dipakai orang lain?
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND id_user != '$id_user'");
    if(mysqli_num_rows($cek) > 0){
         echo "<script>alert('Username sudah digunakan user lain!'); window.location='profile.php';</script>";
         exit();
    }

    $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama', email='$email', username='$username' WHERE id_user='$id_user'");
    
    if($update){
        $_SESSION['nama_lengkap'] = $nama; // Update session
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
    }
}

// --- 2. GANTI PASSWORD ---
elseif($act == 'ganti_pass'){
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $konf_baru = $_POST['konf_baru'];

    // Ambil password lama dari DB
    $cek = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user='$id_user'");
    $d = mysqli_fetch_assoc($cek);

    if($pass_lama == $d['password']){
        if($pass_baru == $konf_baru){
            mysqli_query($koneksi, "UPDATE users SET password='$pass_baru' WHERE id_user='$id_user'");
            echo "<script>alert('Password berhasil diganti!'); window.location='profile.php';</script>";
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!'); window.location='profile.php';</script>";
        }
    } else {
        echo "<script>alert('Password lama salah!'); window.location='profile.php';</script>";
    }
}

// --- 3. UPDATE FOTO PROFIL ---
elseif($act == 'update_foto'){
    $rand = rand();
    $filename = $_FILES['foto_profil']['name'];
    
    if($filename != ""){
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid = array('jpg','jpeg','png');

        if(in_array(strtolower($ext), $valid)){
            $foto_baru = $rand.'_'.$filename;
            move_uploaded_file($_FILES['foto_profil']['tmp_name'], '../uploads/profil/'.$foto_baru);
            
            mysqli_query($koneksi, "UPDATE users SET foto_profil='$foto_baru' WHERE id_user='$id_user'");
            echo "<script>window.location='profile.php';</script>";
        } else {
            echo "<script>alert('Format file harus JPG/PNG'); window.location='profile.php';</script>";
        }
    } else {
        header("location:profile.php");
    }
}
?>