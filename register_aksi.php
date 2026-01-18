<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Registrasi...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
    </style>
</head>
<body>

<?php 
// 2. Panggil koneksi database
include 'config/koneksi.php';

// 3. Tangkap data dari form
$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email    = mysqli_real_escape_string($koneksi, $_POST['email']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password']; // Password belum di-hash (sesuai request)

// Set default
$role = 'siswa'; 
$foto = 'default.jpg';

// 4. Validasi: Cek Username
$cek_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($cek_username) > 0){
    // JIKA GAGAL (USERNAME ADA)
    echo "<script>
        Swal.fire({
            title: 'Gagal Mendaftar!',
            text: 'Username sudah digunakan, silakan pilih username lain.',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        }).then((result) => {
            window.history.back();
        });
    </script>";
    exit();
}

// 5. Simpan ke Tabel USERS
$query_insert_user = "INSERT INTO users (nama_lengkap, email, username, password, role, foto_profil) 
                      VALUES ('$nama', '$email', '$username', '$password', '$role', '$foto')";

if(mysqli_query($koneksi, $query_insert_user)){
    
    // Ambil ID User baru
    $id_user_baru = mysqli_insert_id($koneksi);

    // Jika Anda masih menggunakan tabel siswa_detail, aktifkan baris ini:
    // mysqli_query($koneksi, "INSERT INTO siswa_detail (user_id) VALUES ('$id_user_baru')");

    // JIKA BERHASIL
    echo "<script>
        Swal.fire({
            title: 'Registrasi Berhasil!',
            text: 'Akun Anda telah dibuat. Silakan login untuk melanjutkan.',
            icon: 'success',
            confirmButtonColor: '#27ae60',
            confirmButtonText: 'Masuk Sekarang'
        }).then((result) => {
            window.location = 'login.php';
        });
    </script>";

} else {
    // JIKA ERROR SYSTEM
    echo "<script>
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            text: 'Gagal menyimpan data ke database.',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Kembali'
        }).then((result) => {
            window.history.back();
        });
    </script>";
}
?>

</body>
</html>