<?php 
session_start();
if($_SESSION['role'] != "admin"){
    header("location:../login.php?pesan=belum_login");
}
include '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - LMS Sakinah</title>    
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <div class="wrapper">
        
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            
                <div class="top-navbar">
                <div class="header-left">
                    <h2>Halaman Administrator</h2>
                </div>
                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-info" style="text-align: right;">
                            <span><?php echo $_SESSION['nama_lengkap']; ?></span>
                            <small>Administrator</small>
                        </div>
                        <div class="user-avatar" style="background-color: #007bff;">
                            <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                        </div>
                    </div>

                    <button class="btn-menu-action" onclick="toggleMenu()">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="action-dropdown" id="actionMenu">
                        <ul class="menu-list">
                            <li><a href="profile.php" class="menu-link"><i class="fas fa-user-cog"></i> Akun</a></li>
                            <li><a href="#" class="menu-link"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <hr style="border:0; border-top:1px solid #eee; margin: 8px 0;">
                            <li><a href="../logout.php" class="menu-link" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <script>
                function toggleMenu() { document.getElementById("actionMenu").classList.toggle("active"); }
                window.onclick = function(event) {
                    if (!event.target.matches('.btn-menu-action') && !event.target.matches('.btn-menu-action i')) {
                        var dropdowns = document.getElementsByClassName("action-dropdown");
                        for (var i = 0; i < dropdowns.length; i++) {
                            if (dropdowns[i].classList.contains('active')) dropdowns[i].classList.remove('active');
                        }
                    }
                }
            </script>

            <div class="content-body">