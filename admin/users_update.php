<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "admin"){ header("location:../index.php"); exit(); }

$id_user   = $_POST['id_user'];
$nama      = $_POST['nama'];
$username  = $_POST['username'];
$password  = $_POST['password'];
$email     = isset($_POST['email']) ? $_POST['email'] : '';
$role_baru = $_POST['role'];
$role_lama = $_POST['role_lama']; // Untuk tracking perubahan

// 1. UPDATE TABEL USERS
// Siapkan query dasar
$query_update = "UPDATE users SET nama_lengkap='$nama', username='$username', email='$email', role='$role_baru'";

// Jika password diisi, tambahkan ke query
if(!empty($password)){
    $query_update .= ", password='$password'";
}

// Tambahkan WHERE
$query_update .= " WHERE id_user='$id_user'";

// Eksekusi Update User
if(!mysqli_query($koneksi, $query_update)){
    echo "Gagal update user: " . mysqli_error($koneksi);
    exit();
}

// 2. LOGIKA PERUBAHAN ROLE (SISWA DETAIL)

if($role_baru == 'siswa'){
    // --- JIKA ROLE ADALAH SISWA ---
    $kelas_id = $_POST['kelas_id'];
    $nis      = $_POST['nis'];

    // Cek apakah sudah ada data di siswa_detail?
    $cek = mysqli_query($koneksi, "SELECT * FROM siswa_detail WHERE user_id='$id_user'");
    
    if(mysqli_num_rows($cek) > 0){
        // Jika sudah ada, UPDATE datanya
        // Pastikan kelas_id NULL jika kosong
        $kelas_sql = ($kelas_id == "") ? "NULL" : "'$kelas_id'";
        mysqli_query($koneksi, "UPDATE siswa_detail SET nis='$nis', kelas_id=$kelas_sql WHERE user_id='$id_user'");
    } else {
        // Jika belum ada (misal dari Guru berubah jadi Siswa), INSERT data baru
        // Generate NIS dummy jika kosong agar tidak error database
        if(empty($nis)) { $nis = date('Y') . rand(1000,9999); }
        $kelas_sql = ($kelas_id == "") ? "NULL" : "'$kelas_id'";
        
        mysqli_query($koneksi, "INSERT INTO siswa_detail (user_id, nis, kelas_id) VALUES ('$id_user', '$nis', $kelas_sql)");
    }

} else {
    // --- JIKA ROLE BUKAN SISWA (Admin/Guru) ---
    // Hapus data di siswa_detail jika ada (agar tidak jadi sampah data)
    mysqli_query($koneksi, "DELETE FROM siswa_detail WHERE user_id='$id_user'");
}

header("location:users.php?pesan=update");
?>