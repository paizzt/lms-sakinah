<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// TANGKAP ID MAPEL DARI URL
$id_mapel = isset($_GET['id']) ? $_GET['id'] : '';

// QUERY INFO MAPEL & KELAS (JOIN)
$query_info = "SELECT mapel.*, kelas.nama_kelas 
               FROM mapel 
               JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
               WHERE mapel.id_mapel='$id_mapel'";
$result_info = mysqli_query($koneksi, $query_info);
$mapel = mysqli_fetch_assoc($result_info);

// Cek jika data tidak ditemukan
if(!$mapel){
    echo "<div class='content-body' style='margin-top:-20px;'>
            <div class='alert alert-danger'>Data Mata Pelajaran tidak ditemukan!</div>
          </div>";
    include 'footer.php';
    exit();
}
?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-clipboard-list"></i> Rekap Absensi</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">
            Mata Pelajaran: <b><?php echo $mapel['nama_mapel']; ?></b> &bull; 
            Kelas: <b><?php echo $mapel['nama_kelas']; ?></b>
        </p>
    </div>

    <a href="absensi_rekap.php" class="btn-tambah" style="background: #555; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="modern-form-card" style="padding: 0; width: 100%; max-width: 100%; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 50px;">No</th>
                        <th style="padding: 15px; text-align: left;">Tanggal Pertemuan</th>
                        <th style="padding: 15px; text-align: center;">Hadir</th>
                        <th style="padding: 15px; text-align: center;">Sakit</th>
                        <th style="padding: 15px; text-align: center;">Izin</th>
                        <th style="padding: 15px; text-align: center;">Alpa</th>
                        <th style="padding: 15px; text-align: center;">Total Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Group by tanggal untuk melihat per pertemuan
                    $query = "SELECT tanggal, 
                                     SUM(CASE WHEN status='H' OR status='hadir' THEN 1 ELSE 0 END) as h,
                                     SUM(CASE WHEN status='S' OR status='sakit' THEN 1 ELSE 0 END) as s,
                                     SUM(CASE WHEN status='I' OR status='izin' THEN 1 ELSE 0 END) as i,
                                     SUM(CASE WHEN status='A' OR status='alpa' THEN 1 ELSE 0 END) as a,
                                     COUNT(*) as total
                              FROM absensi 
                              WHERE mapel_id='$id_mapel' 
                              GROUP BY tanggal 
                              ORDER BY tanggal DESC";
                    
                    $data = mysqli_query($koneksi, $query);
                    
                    if(mysqli_num_rows($data) > 0){
                        while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: 600;">
                            <i class="far fa-calendar-alt" style="color: #FF8C00; margin-right: 5px;"></i>
                            <?php echo date('d F Y', strtotime($d['tanggal'])); ?>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background:#d1f2eb; color:#0e6251; padding:4px 10px; border-radius:10px; font-weight:bold;"><?php echo $d['h']; ?></span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background:#d6eaf8; color:#154360; padding:4px 10px; border-radius:10px; font-weight:bold;"><?php echo $d['s']; ?></span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background:#fcf3cf; color:#7d6608; padding:4px 10px; border-radius:10px; font-weight:bold;"><?php echo $d['i']; ?></span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background:#fadbd8; color:#78281f; padding:4px 10px; border-radius:10px; font-weight:bold;"><?php echo $d['a']; ?></span>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #555; font-weight: bold;">
                            <?php echo $d['total']; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:#999;'>Belum ada data absensi untuk mata pelajaran ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>