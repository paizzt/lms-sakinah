<?php 
// 1. Panggil koneksi database
include 'config/koneksi.php';

// 2. Tangkap data dari form register.php
$nama = $_POST['nama'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// Set default role dan foto
$role = 'siswa'; 
$foto = 'default.jpg';

// 3. Validasi: Cek apakah Username sudah ada di database?
$cek_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
if(mysqli_num_rows($cek_username) > 0){
    // Jika username sudah ada, tolak dan kembalikan ke halaman register
    echo "<script>
            alert('Username sudah digunakan! Silakan ganti username lain.'); 
            window.location='register.php';
          </script>";
    exit();
}

// 4. Jika aman, Simpan ke Tabel USERS
// Catatan: Di sini password disimpan langsung (sesuai contoh sebelumnya). 
// Jika ingin lebih aman nanti bisa pakai password_hash($password, PASSWORD_DEFAULT);
$query_insert_user = "INSERT INTO users (nama_lengkap, email, username, password, role, foto_profil) 
                      VALUES ('$nama', '$email', '$username', '$password', '$role', '$foto')";

if(mysqli_query($koneksi, $query_insert_user)){
    
    // 5. Ambil ID User yang baru saja dibuat
    $id_user_baru = mysqli_insert_id($koneksi);

    // 6. Buat data kosong di tabel SISWA_DETAIL 
    // (Penting agar tidak error saat siswa login pertama kali)
    $query_detail = "INSERT INTO siswa_detail (user_id) VALUES ('$id_user_baru')";
    
    if(mysqli_query($koneksi, $query_detail)){
        echo "<script>
                alert('Pendaftaran Berhasil! Silakan Login.'); 
                window.location='login.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal membuat data detail siswa.'); 
                window.location='register.php';
              </script>";
    }

} else {
    echo "<script>
            alert('Terjadi kesalahan sistem. Gagal mendaftar.'); 
            window.location='register.php';
          </script>";
}
?>