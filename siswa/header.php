<?php 
session_start();
// Cek validasi login siswa
if($_SESSION['role'] != "siswa"){
    header("location:../login.php?pesan=belum_login");
}
include '../config/koneksi.php';

$id_siswa = $_SESSION['id_user'];
// Ambil data kelas siswa
$query_kelas = mysqli_query($koneksi, "SELECT * FROM siswa_detail WHERE user_id='$id_siswa'");
$data_kelas = mysqli_fetch_assoc($query_kelas);

if(mysqli_num_rows($query_kelas) == 0){
    echo "<div style='padding:20px;'><h3>Maaf, Anda belum terdaftar di kelas manapun. Hubungi Admin.</h3> <a href='../logout.php'>Logout</a></div>";
    exit();
}
$id_kelas_siswa = $data_kelas['kelas_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - LMS Sakinah</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
                <div class="top-navbar">
                <div class="header-left">
                    <h2>Ruang Belajar Siswa</h2>
                </div>
                
                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-info" style="text-align: right;">
                            <span><?php echo $_SESSION['nama_lengkap']; ?></span>
                            <small>Siswa</small>
                        </div>
                        <div class="user-avatar" style="background-color: #28a745;">
                            <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                        </div>
                    </div>

                    <button class="btn-menu-action" onclick="toggleMenu()">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="action-dropdown" id="actionMenu">
                        <ul class="menu-list">
                            <li>
                                <a href="profile.php" class="menu-link">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="toggleNewsModal()" class="menu-link">
                                    <i class="fas fa-newspaper"></i> Berita Sekolah
                                </a>
                            </li>
                            <hr style="border:0; border-top:1px solid #eee; margin: 8px 0;">
                            <li>
                                <a href="../logout.php" class="menu-link" style="color: #dc3545;">
                                    <i class="fas fa-sign-out-alt"></i> Log out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <script>
                function toggleMenu() {
                    var menu = document.getElementById("actionMenu");
                    menu.classList.toggle("active");
                }
                
                // Menutup menu jika klik di luar
                window.onclick = function(event) {
                    if (!event.target.matches('.btn-menu-action') && !event.target.matches('.btn-menu-action i')) {
                        var dropdowns = document.getElementsByClassName("action-dropdown");
                        for (var i = 0; i < dropdowns.length; i++) {
                            var openDropdown = dropdowns[i];
                            if (openDropdown.classList.contains('active')) {
                                openDropdown.classList.remove('active');
                            }
                        }
                    }
                }
            </script>
            <div class="content-body">