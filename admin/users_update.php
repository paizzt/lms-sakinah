<?php 
include '../config/koneksi.php';

// 1. TANGKAP ID (Modal menggunakan name="id")
$id_user = isset($_POST['id']) ? $_POST['id'] : '';

// Validasi ID User
if(empty($id_user)){
    // Jika ID kosong, kembalikan ke halaman users dengan pesan error
    header("location:users.php?pesan=id_kosong");
    exit();
}

// 2. TANGKAP DATA LAINNYA
$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$email    = mysqli_real_escape_string($koneksi, $_POST['email']);
$password = $_POST['password'];
$role     = $_POST['role'];

// Ambil Kelas & NIS (Gunakan Null Coalescing Operator ?? agar tidak error jika tidak ada)
$kelas_id = $_POST['kelas_id'] ?? 0;
$nis      = $_POST['nis'] ?? ''; // Jika form tidak punya NIS, default kosong

// 3. AMBIL DATA LAMA (Untuk Role Lama & Foto Lama)
$cek_lama = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$data_lama = mysqli_fetch_assoc($cek_lama);
$role_lama = $data_lama['role'];

// 4. LOGIKA UPDATE PASSWORD
$sql_password = "";
if(!empty($password)){
    // Jika password diisi, update passwordnya
    $sql_password = ", password='$password'";
}

// 5. LOGIKA UPLOAD FOTO
$sql_foto = "";
$filename = $_FILES['foto']['name'];

if($filename != ""){
    $rand = rand();
    $allowed = array('png','jpg','jpeg','gif');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(in_array($ext, $allowed)){
        // Hapus foto lama jika ada
        if($data_lama['foto_profil'] != "" && $data_lama['foto_profil'] != "default.jpg"){
            if(file_exists("../uploads/profil/".$data_lama['foto_profil'])){
                unlink("../uploads/profil/".$data_lama['foto_profil']);
            }
        }
        
        $nama_file = $rand.'_'.$filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/profil/'.$nama_file);
        $sql_foto = ", foto_profil='$nama_file'";
    }
}

// 6. UPDATE TABEL USERS (UTAMA)
$query_update = "UPDATE users SET 
                    nama_lengkap='$nama', 
                    username='$username', 
                    email='$email', 
                    role='$role'
                    $sql_password
                    $sql_foto
                 WHERE id_user='$id_user'";

if(mysqli_query($koneksi, $query_update)){
    
    // 7. HANDLING KHUSUS ROLE SISWA
    // Jika role berubah jadi SISWA, atau memang SISWA, update/insert ke tabel detail/users (kolom kelas_id)
    if($role == 'siswa'){
        // Update kolom kelas_id di tabel users
        mysqli_query($koneksi, "UPDATE users SET kelas_id='$kelas_id' WHERE id_user='$id_user'");

        // OPSI: Jika Anda menggunakan tabel terpisah 'siswa_detail', aktifkan blok ini:
        /*
        $cek_detail = mysqli_query($koneksi, "SELECT id FROM siswa_detail WHERE user_id='$id_user'");
        if(mysqli_num_rows($cek_detail) > 0){
            mysqli_query($koneksi, "UPDATE siswa_detail SET nis='$nis', kelas_id='$kelas_id' WHERE user_id='$id_user'");
        } else {
            mysqli_query($koneksi, "INSERT INTO siswa_detail (user_id, nis, kelas_id) VALUES ('$id_user', '$nis', '$kelas_id')");
        }
        */
    } else {
        // Jika bukan siswa, set kelas_id jadi 0 atau NULL agar bersih
        mysqli_query($koneksi, "UPDATE users SET kelas_id=NULL WHERE id_user='$id_user'");
    }

    // Redirect Sukses
    header("location:users.php?pesan=update_sukses");

} else {
    // Redirect Gagal
    header("location:users.php?pesan=gagal_database");
}
?>