<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_siswa = $_SESSION['id_user'];

// 1. AMBIL ID KELAS DARI DATABASE (Agar Aman & Konsisten)
$cek_siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT kelas_id, nama_lengkap FROM users WHERE id_user='$id_siswa'"));
$id_kelas  = $cek_siswa['kelas_id'];

// Validasi jika belum masuk kelas
if(empty($id_kelas)){
    echo "<script>alert('Anda belum masuk kelas! Hubungi Admin.'); window.location='index.php';</script>";
    exit();
}

// 2. QUERY RPS SESUAI KELAS SISWA
// Hanya tampilkan RPS yang Statusnya 'Aktif'
$query = "SELECT rps.*, mapel.nama_mapel, users.nama_lengkap AS nama_guru 
          FROM rps
          JOIN mapel ON rps.mapel_id = mapel.id_mapel
          LEFT JOIN users ON mapel.guru_id = users.id_user
          WHERE mapel.kelas_id = '$id_kelas' AND rps.status = 'Aktif'
          ORDER BY mapel.nama_mapel ASC";

$q_rps = mysqli_query($koneksi, $query);
$jml_rps = mysqli_num_rows($q_rps);
?>

<style>
    /* STYLE CARD RPS */
    .rps-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        margin-bottom: 20px;
        border: 1px solid #f0f0f0;
        border-left: 5px solid #FF8C00; /* Aksen Orange */
        transition: 0.3s;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }
    .rps-card:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(255, 140, 0, 0.1);
        border-color: #FF8C00;
    }

    .rps-icon {
        width: 60px;
        height: 60px;
        background: #FFF3E0;
        color: #E65100;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .rps-info h3 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #333;
        font-weight: 800;
    }
    .rps-info p {
        margin: 0;
        color: #777;
        font-size: 13px;
        line-height: 1.5;
    }
    .guru-badge {
        display: inline-block;
        margin-top: 8px;
        background: #f5f5f5;
        color: #555;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: bold;
    }

    .btn-download-rps {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 5px 10px rgba(255, 140, 0, 0.2);
        transition: 0.2s;
        white-space: nowrap;
    }
    .btn-download-rps:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(255, 140, 0, 0.3);
    }

    /* Responsif untuk HP */
    @media (max-width: 768px) {
        .rps-card { flex-direction: column; align-items: flex-start; }
        .btn-download-rps { width: 100%; justify-content: center; margin-top: 15px; }
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: #333;">Rencana Pembelajaran (RPS)</h2>
            <p style="margin: 5px 0 0 0; color: #777;">Dokumen panduan belajar semester ini.</p>
        </div>
        <div style="text-align: right; display: none; @media (min-width: 768px){display:block;}">
            <span style="background: #FFF3E0; color: #E65100; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                <i class="fas fa-file-alt"></i> <?php echo $jml_rps; ?> Dokumen
            </span>
        </div>
    </div>

    <div class="rps-list">
        <?php 
        if($jml_rps > 0){
            while($d = mysqli_fetch_array($q_rps)){
                
                // Cek ekstensi file untuk ikon yang sesuai
                $ext = pathinfo($d['file_rps'], PATHINFO_EXTENSION);
                $icon_class = ($ext == 'pdf') ? 'fa-file-pdf' : 'fa-file-word';
                
                // Nama Guru
                $guru = !empty($d['nama_guru']) ? $d['nama_guru'] : "Guru Mapel";
                $ket  = !empty($d['keterangan']) ? $d['keterangan'] : "Silakan download dokumen RPS untuk mata pelajaran ini.";
        ?>
        <div class="rps-card">
            <div style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="rps-icon">
                    <i class="fas <?php echo $icon_class; ?>"></i>
                </div>
                <div class="rps-info">
                    <h3><?php echo $d['nama_mapel']; ?></h3>
                    <span class="guru-badge"><i class="fas fa-chalkboard-teacher"></i> <?php echo $guru; ?></span>
                    <p style="margin-top: 10px;"><?php echo $ket; ?></p>
                    <div style="margin-top: 5px; font-size: 11px; color: #999;">
                        <i class="far fa-clock"></i> Diupload: <?php echo date('d M Y', strtotime($d['tanggal_upload'])); ?>
                    </div>
                </div>
            </div>

            <a href="../uploads/rps/<?php echo $d['file_rps']; ?>" target="_blank" class="btn-download-rps">
                <i class="fas fa-cloud-download-alt"></i> DOWNLOAD
            </a>
        </div>
        <?php 
            }
        } else {
            echo "<div style='text-align:center; padding:50px; background:white; border-radius:15px; color:#999; box-shadow: 0 5px 15px rgba(0,0,0,0.03);'>
                    <img src='../assets/img/no-data.svg' style='width:120px; opacity:0.6; margin-bottom:20px;'>
                    <h3 style='color:#555;'>Belum Ada RPS</h3>
                    <p>Guru belum mengupload dokumen RPS untuk kelas Anda.</p>
                  </div>";
        }
        ?>
    </div>

</div>

<?php include 'footer.php'; ?>