<?php 
$id_siswa_login = $_SESSION['id_user'];

// PERBAIKAN QUERY:
// Langsung JOIN dari tabel 'users' ke 'kelas', tidak perlu lewat 'siswa_detail'
$q_info_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, users.username, kelas.nama_kelas 
                                        FROM users 
                                        LEFT JOIN kelas ON users.kelas_id = kelas.id_kelas 
                                        WHERE users.id_user='$id_siswa_login'");
$d_siswa = mysqli_fetch_assoc($q_info_siswa);

// Cek jika belum masuk kelas
$nama_kelas = !empty($d_siswa['nama_kelas']) ? $d_siswa['nama_kelas'] : "Belum ada kelas";
?>

<div class="sidebar">
    <div class="sidebar-brand">
        <h2><i class="fas fa-user-graduate"></i> SAS SISWA</h2>
    </div>

    <div class="sidebar-profile" style="text-align: center; padding: 20px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px;">
        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 10px auto; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white;">
            <?php 
            // Ambil foto dari session atau database jika perlu
            // Disini pakai icon user saja agar ringan sesuai desain Anda
            ?>
            <i class="fas fa-user"></i>
        </div>
        <h4 style="margin: 0; color: white; font-size: 16px; font-weight: 600;"><?php echo $d_siswa['nama_lengkap']; ?></h4>
        <span style="display: inline-block; background: rgba(0,0,0,0.2); color: #ffd700; padding: 2px 10px; border-radius: 20px; font-size: 12px; margin-top: 5px;">
            <?php echo $nama_kelas; ?>
        </span>
    </div>
    
    <ul>
        <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>">
            <a href="index.php">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        
        <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'mapel.php' || basename($_SERVER['PHP_SELF']) == 'materi.php' || basename($_SERVER['PHP_SELF']) == 'tugas.php' || basename($_SERVER['PHP_SELF']) == 'tugas_detail.php') echo 'active'; ?>">
            <a href="mapel.php"> 
                <i class="fas fa-book"></i> <span>Mata Pelajaran</span>
            </a>
        </li>

        <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'absensi.php') echo 'active'; ?>">
            <a href="absensi.php"><i class="fas fa-user-check"></i> <span>Rekap Absensi</span></a>
        </li>

        <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'rps.php') echo 'active'; ?>">
            <a href="rps.php">
                <i class="fas fa-book-reader"></i> <span>RPS Pembelajaran</span>
            </a>
        </li>

        <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'nilai.php') echo 'active'; ?>">
            <a href="nilai.php">
                <i class="fas fa-star"></i> <span>Rekap Nilai</span>
            </a>
        </li>

        <li>
            <a href="../logout.php" style="color: #ffadad;">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        &copy; <?php echo date('Y'); ?> SMAIT As-Sakinah
    </div>
</div>