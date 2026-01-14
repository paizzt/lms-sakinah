<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- QUERY DATABASE ---

// 1. Total SEMUA User (Baru)
$all_user = mysqli_query($koneksi, "SELECT * FROM users");
$jml_total_user = mysqli_num_rows($all_user);

// 2. Data Per Role
$guru = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru'");
$siswa = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa'");
$admin = mysqli_query($koneksi, "SELECT * FROM users WHERE role='admin'");

// 3. Data Akademik
$q_kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
$jml_kelas = mysqli_num_rows($q_kelas);

$q_materi = mysqli_query($koneksi, "SELECT * FROM materi");
$jml_materi = mysqli_num_rows($q_materi);

$q_mapel = mysqli_query($koneksi, "SELECT * FROM mapel");
$jml_mapel = mysqli_num_rows($q_mapel);
?>

<style>
    .card-container {
        display: grid;
        /* Kunci utama: Membagi menjadi 3 kolom sama besar */
        grid-template-columns: repeat(3, 1fr); 
        gap: 20px; /* Jarak antar kartu */
        margin-top: 20px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        text-align: center;
        border-bottom: 4px solid #FF8C00; /* Aksen Orange */
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .card h3 {
        font-size: 36px;
        margin: 0 0 10px 0;
        color: #333;
    }

    .card p {
        margin: 0;
        color: #777;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
    }

    /* Agar responsif di HP (menjadi 1 kolom) */
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-body" style="margin-top: -20px;">
    
    <div style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 20px;">
        <h1 style="margin:0; font-size: 24px;">Dashboard Admin</h1>
        <p style="margin:5px 0 0 0;">Selamat datang, <b><?php echo $_SESSION['nama_lengkap']; ?></b>.</p>
    </div>

    <div class="card-container">
        
        <div class="card">
            <h3><?php echo $jml_total_user; ?></h3>
            <p><i class="fas fa-users" style="color: #FF8C00;"></i> Total Pengguna</p>
        </div>

        <div class="card">
            <h3><?php echo mysqli_num_rows($guru); ?></h3>
            <p><i class="fas fa-chalkboard-teacher" style="color: #2980b9;"></i> Total Guru</p>
        </div>

        <div class="card">
            <h3><?php echo mysqli_num_rows($siswa); ?></h3>
            <p><i class="fas fa-user-graduate" style="color: #27ae60;"></i> Total Siswa</p>
        </div>

        <div class="card">
            <h3><?php echo mysqli_num_rows($admin); ?></h3>
            <p><i class="fas fa-user-shield" style="color: #c0392b;"></i> Total Admin</p>
        </div>

        <div class="card">
            <h3><?php echo $jml_kelas; ?></h3>
            <p><i class="fas fa-school" style="color: #8e44ad;"></i> Total Kelas</p>
        </div>

        <div class="card">
            <h3><?php echo $jml_materi; ?></h3>
            <p><i class="fas fa-book" style="color: #d35400;"></i> Total Materi</p>
        </div>

        <div class="card">
            <h3><?php echo $jml_mapel; ?></h3>
            <p><i class="fas fa-calendar-alt" style="color: #7f8c8d;"></i> Total Mapel</p>
        </div>

    </div>

</div>

<?php include 'footer.php'; ?>