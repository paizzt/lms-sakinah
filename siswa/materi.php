<?php 
include 'header.php'; 
include 'sidebar.php'; 

// 1. CEK ID MAPEL
if(!isset($_GET['mapel']) || empty($_GET['mapel'])){
    echo "<script>window.location='mapel.php';</script>";
    exit();
}
$id_mapel = $_GET['mapel'];

// 2. AMBIL INFO MAPEL
$info_mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT mapel.*, users.nama_lengkap AS nama_guru 
                                                         FROM mapel 
                                                         LEFT JOIN users ON mapel.guru_id = users.id_user 
                                                         WHERE id_mapel='$id_mapel'"));

// 3. AMBIL DAFTAR MATERI
$q_materi = mysqli_query($koneksi, "SELECT * FROM materi WHERE mapel_id='$id_mapel' ORDER BY tanggal_upload DESC");
?>

<style>
    /* STYLE CARD MATERI */
    .materi-card {
        background: white; border-radius: 15px; padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03); margin-bottom: 20px;
        border-left: 5px solid #FF8C00; transition: 0.3s;
        display: flex; justify-content: space-between; align-items: center;
    }
    .materi-card:hover { transform: translateX(5px); box-shadow: 0 5px 20px rgba(0,0,0,0.08); }

    .materi-icon {
        width: 50px; height: 50px; background: #FFF3E0; color: #E65100;
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin-right: 20px;
    }
    .materi-content h4 { margin: 0 0 5px 0; color: #333; font-size: 16px; }
    .materi-content p { margin: 0; color: #777; font-size: 13px; }
    
    .btn-download {
        background: #e3f2fd; color: #1565c0; padding: 8px 15px;
        border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: bold;
        transition: 0.2s; white-space: nowrap;
    }
    .btn-download:hover { background: #1565c0; color: white; }

    .btn-link {
        background: #ffebee; color: #c62828; padding: 8px 15px;
        border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: bold;
        transition: 0.2s; white-space: nowrap;
    }
    .btn-link:hover { background: #c62828; color: white; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: #333;">Materi Pembelajaran</h2>
            <p style="margin: 5px 0 0 0; color: #777;">
                Mapel: <b><?php echo $info_mapel['nama_mapel']; ?></b> | Guru: <?php echo $info_mapel['nama_guru']; ?>
            </p>
        </div>
        <a href="mapel.php" style="background: #f5f5f5; color: #555; padding: 10px 15px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 13px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div>
        <?php 
        if(mysqli_num_rows($q_materi) > 0){
            while($d = mysqli_fetch_array($q_materi)){
                $tipe = $d['tipe']; // file / link
                $icon = ($tipe == 'file') ? '<i class="fas fa-file-pdf"></i>' : '<i class="fas fa-link"></i>';
                $tgl  = date('d M Y', strtotime($d['tanggal_upload']));
        ?>
        <div class="materi-card">
            <div style="display: flex; align-items: center;">
                <div class="materi-icon"><?php echo $icon; ?></div>
                <div class="materi-content">
                    <h4><?php echo $d['judul']; ?></h4>
                    <p>
                        <i class="far fa-clock"></i> <?php echo $tgl; ?> &nbsp;|&nbsp; 
                        <?php echo $d['deskripsi']; ?>
                    </p>
                </div>
            </div>
            
            <?php if($tipe == 'file') { ?>
                <a href="../uploads/materi/<?php echo $d['file_url']; ?>" target="_blank" class="btn-download">
                    <i class="fas fa-download"></i> Unduh File
                </a>
            <?php } else { ?>
                <a href="<?php echo $d['file_url']; ?>" target="_blank" class="btn-link">
                    <i class="fas fa-external-link-alt"></i> Buka Tautan
                </a>
            <?php } ?>
        </div>
        <?php 
            }
        } else {
            echo "<div style='text-align:center; padding:50px; background:white; border-radius:15px; color:#999;'>
                    <img src='../assets/img/no-data.svg' style='width:100px; opacity:0.5; margin-bottom:15px;'>
                    <br>Belum ada materi yang diupload guru.
                  </div>";
        }
        ?>
    </div>

</div>
<?php include 'footer.php'; ?>