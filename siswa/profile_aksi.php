<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "siswa"){ header("location:../index.php"); exit(); }

$id_user = $_SESSION['id_user'];
$act = $_POST['act'];

// --- 1. UPDATE BIODATA (Nama, Email, Lahir, Alamat) ---
if($act == "update_bio"){
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Data Tambahan Siswa
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    // Update Tabel USERS (Nama, Username, Email)
    $update_user = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama', username='$username', email='$email' WHERE id_user='$id_user'");

    // Update Tabel SISWA_DETAIL (Tempat Lahir, Tanggal Lahir, Alamat)
    // Cek dulu apakah data detail sudah ada?
    $cek_detail = mysqli_query($koneksi, "SELECT id_detail FROM siswa_detail WHERE user_id='$id_user'");
    
    if(mysqli_num_rows($cek_detail) > 0){
        // Jika ada, UPDATE
        $q_detail = "UPDATE siswa_detail SET tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', alamat='$alamat' WHERE user_id='$id_user'";
        mysqli_query($koneksi, $q_detail);
    } else {
        // Jika belum ada, INSERT (Jaga-jaga)
        $q_insert = "INSERT INTO siswa_detail (user_id, tempat_lahir, tanggal_lahir, alamat) VALUES ('$id_user', '$tempat_lahir', '$tanggal_lahir', '$alamat')";
        mysqli_query($koneksi, $q_insert);
    }
    
    // Update Session Nama (agar header langsung berubah)
    $_SESSION['nama_lengkap'] = $nama;

    echo "<script>alert('Biodata berhasil diperbarui!'); window.location='profile.php';</script>";
}

// --- 2. UPDATE FOTO PROFIL ---
elseif($act == "update_foto"){
    $rand = rand();
    $allowed =  array('png','jpg','jpeg','gif');
    $filename = $_FILES['foto_profil']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if($filename != ""){
        if(in_array($ext, $allowed) ) {
            // Hapus foto lama (opsional)
            $q_lama = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id_user='$id_user'");
            $d_lama = mysqli_fetch_assoc($q_lama);
            if($d_lama['foto_profil'] != "" && $d_lama['foto_profil'] != "default.jpg"){
                if(file_exists("../uploads/profil/".$d_lama['foto_profil'])){
                    unlink("../uploads/profil/".$d_lama['foto_profil']);
                }
            }

            // Upload foto baru
            $xx = $rand.'_'.$filename;
            move_uploaded_file($_FILES['foto_profil']['tmp_name'], '../uploads/profil/'.$xx);
            
            mysqli_query($koneksi, "UPDATE users SET foto_profil='$xx' WHERE id_user='$id_user'");
            echo "<script>alert('Foto profil berhasil diganti!'); window.location='profile.php';</script>";
        } else {
            echo "<script>alert('Format file tidak diizinkan! Gunakan JPG/PNG.'); window.location='profile.php';</script>";
        }
    }
}

// --- 3. GANTI PASSWORD ---
elseif($act == "ganti_pass"){
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $konf_baru = $_POST['konf_baru'];

    // Cek password lama
    $cek = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user='$id_user'");
    $d = mysqli_fetch_assoc($cek);

    if($d['password'] == $pass_lama){
        if($pass_baru == $konf_baru){
            mysqli_query($koneksi, "UPDATE users SET password='$pass_baru' WHERE id_user='$id_user'");
            echo "<script>alert('Password berhasil diubah! Silakan login ulang.'); window.location='../logout.php';</script>";
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!'); window.location='profile.php';</script>";
        }
    } else {
        echo "<script>alert('Password lama salah!'); window.location='profile.php';</script>";
    }
}
?>