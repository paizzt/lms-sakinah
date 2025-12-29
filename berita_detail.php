<?php include 'config/koneksi.php'; 
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE id_pengumuman='$id'");
$d = mysqli_fetch_assoc($query);

if(!$d){
    echo "Berita tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $d['judul']; ?> - SMAIT As-Sakinah</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body style="background-color: #f9f9f9;">

    <nav class="landing-navbar">
        <div class="nav-brand">
            <a href="berita.php" style="text-decoration:none; display:flex; align-items:center; gap:15px;">
                <i class="fas fa-arrow-left" style="color: #333; font-size: 20px;"></i>
                <div style="line-height: 1.2;">
                    <span style="display:block; font-size:12px; color:#555; font-weight:600;">KEMBALI KE</span>
                    <span style="display:block; color:#FF8C00; font-weight:800; font-size: 18px;">DAFTAR BERITA</span>
                </div>
            </a>
        </div>
    </nav>

    <div class="container" style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        
        <small style="color: #999; font-size: 14px;">
            <i class="far fa-calendar-alt"></i> <?php echo date('l, d F Y', strtotime($d['tanggal_dibuat'])); ?>
        </small>

        <h1 style="font-size: 32px; color: #2c3e50; margin-top: 10px;"><?php echo $d['judul']; ?></h1>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <?php if($d['gambar']) { ?>
            <div style="width: 100%; border-radius: 10px; overflow: hidden; margin-bottom: 30px;">
                <img src="uploads/berita/<?php echo $d['gambar']; ?>" style="width: 100%; display: block;">
            </div>
        <?php } ?>

        <div style="font-size: 18px; line-height: 1.8; color: #444; text-align: justify;">
            <?php echo nl2br($d['isi']); ?>
        </div>

    </div>

    <footer style="text-align: center; padding: 30px; color: #888;">
        &copy; <?php echo date('Y'); ?> SMAIT As-Sakinah
    </footer>

</body>
</html>