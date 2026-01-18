<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Pengumuman - LMS Sakinah</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        
        .header-berita {
            text-align: center; margin-bottom: 40px; margin-top: 30px;
        }
        .header-berita h1 { color: #333; margin-bottom: 10px; }
        .header-berita p { color: #777; }

        .card-berita {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 25px;
            border-left: 5px solid #FF8C00; transition: 0.3s;
        }
        .card-berita:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        
        .berita-meta { font-size: 13px; color: #999; margin-bottom: 10px; display: flex; gap: 15px; align-items: center; }
        .badge-tujuan { padding: 3px 10px; border-radius: 10px; color: white; font-weight: bold; font-size: 11px; }
        
        .btn-back {
            display: inline-block; background: #333; color: white; padding: 10px 20px;
            text-decoration: none; border-radius: 20px; font-weight: bold; margin-bottom: 20px;
        }
        .btn-back:hover { background: #555; }
    </style>
</head>
<body>

<div class="container">
    <a href="javascript:history.back()" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="header-berita">
        <h1>Papan Pengumuman</h1>
        <p>Informasi terbaru seputar kegiatan sekolah dan akademik.</p>
    </div>

    <?php 
    // Ambil semua berita, urutkan dari terbaru
    $q = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal DESC");
    
    if(mysqli_num_rows($q) > 0){
        while($d = mysqli_fetch_array($q)){
            $tujuan = $d['tujuan'];
            $color = ($tujuan == 'Semua') ? '#7e57c2' : (($tujuan == 'Guru') ? '#2980b9' : '#27ae60');
    ?>
        <div class="card-berita">
            <div class="berita-meta">
                <span><i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($d['tanggal'])); ?></span>
                <span><i class="far fa-clock"></i> <?php echo date('H:i', strtotime($d['tanggal'])); ?> WIB</span>
                <span class="badge-tujuan" style="background: <?php echo $color; ?>;"><?php echo strtoupper($tujuan); ?></span>
            </div>
            
            <h2 style="margin: 0 0 10px 0; color: #333; font-size: 20px;"><?php echo $d['judul']; ?></h2>
            
            <div style="color: #555; line-height: 1.6;">
                <?php echo nl2br($d['isi']); ?>
            </div>

            <?php if(!empty($d['file_lampiran'])) { ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #eee;">
                    <a href="uploads/pengumuman/<?php echo $d['file_lampiran']; ?>" target="_blank" style="text-decoration: none; color: #E65100; font-weight: bold;">
                        <i class="fas fa-paperclip"></i> Download Lampiran
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php 
        }
    } else {
        echo "<center><p style='color:#999;'>Belum ada berita yang diterbitkan.</p></center>";
    }
    ?>

</div>

</body>
</html>