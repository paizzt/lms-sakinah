<?php 
// 1. Cek Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Cek Login Guru
if(!isset($_SESSION['role']) || $_SESSION['role'] != "guru"){
    $login_path = file_exists("../login.php") ? "../login.php" : "login.php";
    header("location:".$login_path."?pesan=belum_login");
    exit();
}

// 3. Cek Koneksi
if(!isset($koneksi)){
    include '../config/koneksi.php';
}

// LOGIKA FOTO PROFIL
$id_user = $_SESSION['id_user'];
$q_foto = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id_user='$id_user'");
$d_foto = mysqli_fetch_assoc($q_foto);
$foto_db = isset($d_foto['foto_profil']) ? $d_foto['foto_profil'] : '';

// Path Dinamis
$path_prefix = file_exists("../assets") ? "../" : "";

if($foto_db == "" || $foto_db == "default.jpg"){
    $foto_tampil = $path_prefix . "assets/img/avatar-default.svg";
} else {
    $foto_tampil = $path_prefix . "uploads/profil/" . $foto_db;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Panel - LMS Sakinah</title>    
    
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dashboard-body">
    
    <div class="wrapper">
        
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            
            <div class="top-navbar">
                <div class="header-left">
                    <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Halaman Guru</h2>
                </div>
                
                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-info" style="text-align: right;">
                            <span><?php echo $_SESSION['nama_lengkap']; ?></span>
                            <small>Guru Pengajar</small>
                        </div>
                        
                        <img src="<?php echo $foto_tampil; ?>" alt="Profil" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #eee; background: #fff;">
                    </div>

                    <button class="btn-menu-action" onclick="toggleMenu()">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="action-dropdown" id="actionMenu">
                        <ul class="menu-list">
                            <li><a href="profile.php" class="menu-link"><i class="fas fa-user-circle"></i> Profil Saya</a></li>
                            <li><a href="javascript:void(0);" onclick="toggleNewsModal()" class="menu-link"><i class="fas fa-newspaper"></i> Berita Sekolah</a></li>
                            <hr style="border:0; border-top:1px solid #eee; margin: 8px 0;">
                            <li><a href="<?php echo $path_prefix; ?>logout.php" class="menu-link" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <script>
                function toggleMenu() { document.getElementById("actionMenu").classList.toggle("active"); }
            </script>

            <div class="content-body">