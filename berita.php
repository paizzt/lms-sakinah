<?php 
// Hanya include koneksi, JANGAN include header/sidebar dashboard
include 'config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Berita & Pengumuman</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* RESET CSS SEDERHANA */
        * { box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f0f2f5; /* Warna background abu muda nyaman di mata */
            margin: 0; 
            padding: 0; 
        }

        /* TOP BAR (Hanya untuk tombol kembali) */
        .top-bar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; /* Agar tetap di atas saat scroll */
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        /* TOMBOL KEMBALI */
        .btn-back {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 20px;
            transition: 0.3s;
            background: #f5f5f5;
        }
        .btn-back:hover {
            background: #FF8C00;
            color: white;
        }

        /* CONTAINER BERITA */
        .container { 
            max-width: 800px; /* Lebar ideal untuk membaca */
            margin: 30px auto; 
            padding: 0 20px; 
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-title h1 { color: #333; margin: 0; font-size: 28px; font-weight: 800; }
        .page-title p { color: #777; margin: 5px 0 0 0; }

        /* KARTU BERITA */
        .card-berita {
            background: white; 
            border-radius: 15px; 
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03); 
            margin-bottom: 30px;
            border-top: 5px solid #FF8C00; /* Aksen Oranye di atas */
            transition: 0.3s;
        }
        .card-berita:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        
        /* META DATA (Tanggal & Badge) */
        .berita-meta { 
            font-size: 13px; 
            color: #999; 
            margin-bottom: 15px; 
            display: flex; 
            flex-wrap: wrap;
            gap: 15px; 
            align-items: center; 
        }
        .badge-tujuan { 
            padding: 4px 12px; 
            border-radius: 15px; 
            color: white; 
            font-weight: bold; 
            font-size: 11px; 
            text-transform: uppercase;
        }
        
        /* ISI BERITA */
        .berita-content {
            color: #444; 
            line-height: 1.8; /* Jarak antar baris lega */
            font-size: 15px;
        }

        /* LAMPIRAN */
        .attachment-box {
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 1px dashed #eee;
        }
        .btn-download {
            display: inline-flex; 
            align-items: center; 
            background: #fdfdfd; 
            border: 1px solid #ddd; 
            padding: 10px 20px; 
            border-radius: 8px; 
            text-decoration: none; 
            color: #333; 
            font-weight: 600;
            font-size: 13px;
            transition: 0.3s;
        }
        .btn-download:hover { border-color: #FF8C00; color: #FF8C00; background: #fff; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="javascript:history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="container">

        <div class="page-title">
            <h1>Papan Pengumuman</h1>
            <p>Arsip berita dan informasi sekolah</p>
        </div>

        <?php 
        // QUERY DATABASE
        $q = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal DESC");
        
        if(mysqli_num_rows($q) > 0){
            while($d = mysqli_fetch_array($q)){
                $tujuan = $d['tujuan'];
                // Warna Badge: Ungu (Semua), Biru (Guru), Hijau (Siswa)
                $color = ($tujuan == 'Semua') ? '#7e57c2' : (($tujuan == 'Guru') ? '#2980b9' : '#27ae60');
        ?>
            <div class="card-berita">
                <div class="berita-meta">
                    <span class="badge-tujuan" style="background: <?php echo $color; ?>;"><?php echo $tujuan; ?></span>
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($d['tanggal'])); ?></span>
                    <span><i class="far fa-clock"></i> <?php echo date('H:i', strtotime($d['tanggal'])); ?> WIB</span>
                </div>
                
                <h2 style="margin: 0 0 15px 0; color: #333; font-size: 22px; font-weight: 700;"><?php echo $d['judul']; ?></h2>
                
                <div class="berita-content">
                    <?php echo nl2br($d['isi']); ?>
                </div>

                <?php if(!empty($d['file_lampiran'])) { 
                    $file = $d['file_lampiran'];
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                ?>
                    <div class="attachment-box">
                        <?php if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])){ ?>
                            <img src="uploads/pengumuman/<?php echo $file; ?>" style="max-width: 100%; border-radius: 10px; border: 1px solid #eee;" alt="Lampiran">
                        <?php } else { ?>
                            <a href="uploads/pengumuman/<?php echo $file; ?>" target="_blank" class="btn-download">
                                <i class="fas fa-file-download" style="margin-right: 10px; color: #FF8C00;"></i> 
                                Download Lampiran (<?php echo strtoupper($ext); ?>)
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php 
            }
        } else {
            echo "<center><img src='assets/img/empty.svg' style='width: 150px; opacity: 0.5; margin-top:50px;'><p style='color:#999;'>Belum ada berita.</p></center>";
        }
        ?>

    </div>

</body>
</html>