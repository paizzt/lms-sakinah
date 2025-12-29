<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// Hitung jumlah data
$guru = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru'");
$siswa = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa'");
$admin = mysqli_query($koneksi, "SELECT * FROM users WHERE role='admin'");
?>

<h1>Dashboard Admin</h1>
<p>Selamat datang, <b><?php echo $_SESSION['nama_lengkap']; ?></b>.</p>

<div class="card-container">
    <div class="card">
        <h3><?php echo mysqli_num_rows($guru); ?></h3>
        <p>Total Guru</p>
    </div>
    <div class="card">
        <h3><?php echo mysqli_num_rows($siswa); ?></h3>
        <p>Total Siswa</p>
    </div>
    <div class="card">
        <h3><?php echo mysqli_num_rows($admin); ?></h3>
        <p>Total Admin</p>
    </div>
</div>

<?php include 'footer.php'; ?>