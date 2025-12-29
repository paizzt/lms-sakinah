<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Sekolah - SMAIT As-Sakinah</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body style="background-color: #f9f9f9;">

    <nav class="landing-navbar">
        <div class="nav-brand">
            <a href="index.php" style="text-decoration:none; display:flex; align-items:center; gap:15px;">
                <img src="assets/img/logo_sbs.png" alt="Logo SBS" height="50">
                <div style="line-height: 1.2;">
                    <span style="display:block; font-size:12px; color:#555; font-weight:600;">KEMBALI KE</span>
                    <span style="display:block; color:#FF8C00; font-weight:800; font-size: 18px;">BERANDA</span>
                </div>
            </a>
        </div>
    </nav>

    <div style="background: #2c3e50; padding: 60px 20px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 42px;">Kabar Sekolah</h1>
        <p style="opacity: 0.8; margin-top: 10px;">Informasi terbaru, prestasi, dan kegiatan SMAIT As-Sakinah</p>
    </div>

    <div class="container" style="max-width: 1100px; margin: 50px auto; padding: 0 20px;">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            <?php 
            include 'config/koneksi.php';
            // Tampilkan berita untuk umum (semua)
            $query = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tujuan='semua' ORDER BY tanggal_dibuat DESC");
            
            if(mysqli_num_rows($query) == 0){
                echo "<p style='grid-column: 1/-1; text-align: center; color: #777;'>Belum ada berita terbaru.</p>";
            }

            while($d = mysqli_fetch_array($query)){
                // Potong isi berita biar tidak kepanjangan di card
                $isi_singkat = substr(strip_tags($d['isi']), 0, 100) . '...';
                $gambar = $d['gambar'] ? "uploads/berita/".$d['gambar'] : "assets/img/logo_sbs.png"; // Default image jika kosong
            ?>
            
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; display:flex; flex-direction:column;">
                
                <div style="height: 200px; overflow: hidden;">
                    <img src="<?php echo $gambar; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                </div>

                <div style="padding: 20px; flex: 1; display:flex; flex-direction:column;">
                    <small style="color: #999; display:block; margin-bottom: 10px;">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($d['tanggal_dibuat'])); ?>
                    </small>
                    
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333;">
                        <a href="berita_detail.php?id=<?php echo $d['id_pengumuman']; ?>" style="text-decoration: none; color: #333;"><?php echo $d['judul']; ?></a>
                    </h3>
                    
                    <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 20px; flex:1;">
                        <?php echo $isi_singkat; ?>
                    </p>

                    <a href="berita_detail.php?id=<?php echo $d['id_pengumuman']; ?>" style="text-decoration: none; color: #FF8C00; font-weight: bold; font-size: 14px;">
                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <?php } ?>
        </div>

    </div>

    <footer style="text-align: center; padding: 30px; background: #fff; border-top: 1px solid #eee; color: #888;">
        &copy; <?php echo date('Y'); ?> SMAIT As-Sakinah
    </footer>

</body>
</html>