<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// AMBIL ID SISWA YANG LOGIN
$id_siswa = $_SESSION['id_user'];

// --- 1. HITUNG STATISTIK KEHADIRAN ---
// Kita hitung jumlah masing-masing status untuk ditampilkan di kartu atas
$q_hadir = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND status='hadir'");
$jml_hadir = mysqli_num_rows($q_hadir);

$q_izin = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND status='izin'");
$jml_izin = mysqli_num_rows($q_izin);

$q_sakit = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND status='sakit'");
$jml_sakit = mysqli_num_rows($q_sakit);

$q_alpa = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND status='alpa'");
$jml_alpa = mysqli_num_rows($q_alpa);
?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-user-check"></i> Riwayat Kehadiran</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau catatan kehadiran Anda di kelas.</p>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">
                <i class="far fa-calendar-check"></i>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
        
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 4px solid #2ecc71; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <small style="color: #888; font-weight: bold; font-size: 11px;">HADIR</small>
                <h3 style="margin: 0; color: #333; font-size: 24px;"><?php echo $jml_hadir; ?></h3>
            </div>
            <div style="width: 40px; height: 40px; background: #e8f8f5; color: #2ecc71; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-check"></i>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 4px solid #f1c40f; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <small style="color: #888; font-weight: bold; font-size: 11px;">IZIN</small>
                <h3 style="margin: 0; color: #333; font-size: 24px;"><?php echo $jml_izin; ?></h3>
            </div>
            <div style="width: 40px; height: 40px; background: #fef9e7; color: #f1c40f; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-info"></i>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 4px solid #3498db; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <small style="color: #888; font-weight: bold; font-size: 11px;">SAKIT</small>
                <h3 style="margin: 0; color: #333; font-size: 24px;"><?php echo $jml_sakit; ?></h3>
            </div>
            <div style="width: 40px; height: 40px; background: #eaf2f8; color: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-procedures"></i>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 4px solid #e74c3c; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <small style="color: #888; font-weight: bold; font-size: 11px;">ALPA</small>
                <h3 style="margin: 0; color: #333; font-size: 24px;"><?php echo $jml_alpa; ?></h3>
            </div>
            <div style="width: 40px; height: 40px; background: #fdedec; color: #e74c3c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-times"></i>
            </div>
        </div>

    </div>

    <div class="modern-form-card" style="padding: 0; width: 100%; max-width: 100%; overflow: hidden;">
        
        <div style="padding: 20px; border-bottom: 1px solid #eee; background: #fff;">
            <h4 style="margin: 0; color: #555;"><i class="fas fa-list-alt" style="color: #FF8C00; margin-right: 8px;"></i> Log Kehadiran</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 50px;">No</th>
                        <th style="padding: 15px; text-align: left;">Tanggal</th>
                        <th style="padding: 15px; text-align: left;">Mata Pelajaran</th>
                        <th style="padding: 15px; text-align: left;">Guru Pengajar</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: left;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Query mengambil data absensi JOIN dengan Mapel dan Guru (Users)
                    $query = mysqli_query($koneksi, "SELECT absensi.*, mapel.nama_mapel, users.nama_lengkap AS nama_guru 
                                                     FROM absensi 
                                                     JOIN mapel ON absensi.mapel_id = mapel.id_mapel 
                                                     JOIN users ON absensi.guru_id = users.id_user 
                                                     WHERE absensi.siswa_id='$id_siswa' 
                                                     ORDER BY absensi.tanggal DESC");

                    if(mysqli_num_rows($query) > 0){
                        while($d = mysqli_fetch_array($query)){
                            // Warna Badge Status
                            if($d['status'] == 'hadir') { $badge = 'background:#d1f2eb; color:#0e6251;'; $icon='check-circle'; }
                            elseif($d['status'] == 'izin') { $badge = 'background:#fcf3cf; color:#7d6608;'; $icon='info-circle'; }
                            elseif($d['status'] == 'sakit') { $badge = 'background:#d6eaf8; color:#154360;'; $icon='procedures'; }
                            else { $badge = 'background:#fadbd8; color:#78281f;'; $icon='times-circle'; }
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 500;">
                            <?php echo date('d F Y', strtotime($d['tanggal'])); ?>
                        </td>

                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['nama_mapel']; ?>
                        </td>

                        <td style="padding: 15px; color: #555;">
                            <?php echo $d['nama_guru']; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <span style="<?php echo $badge; ?> padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-<?php echo $icon; ?>"></i> <?php echo $d['status']; ?>
                            </span>
                        </td>

                        <td style="padding: 15px; color: #777; font-style: italic;">
                            <?php echo empty($d['keterangan']) ? '-' : $d['keterangan']; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">
                            <img src="../assets/img/empty.svg" style="width: 60px; opacity: 0.5; margin-bottom: 10px; display: block; margin: 0 auto 10px auto;">
                            Belum ada data absensi untuk Anda.
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>