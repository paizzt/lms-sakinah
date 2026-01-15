<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// AMBIL ID SISWA YANG LOGIN
$id_siswa = $_SESSION['id_user'];

// --- 1. HITUNG STATISTIK (LOGIKA PERBAIKAN: BACA H/HADIR) ---
$q_hadir = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND (status='H' OR status='hadir')");
$jml_hadir = mysqli_num_rows($q_hadir);

$q_izin = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND (status='I' OR status='izin')");
$jml_izin = mysqli_num_rows($q_izin);

$q_sakit = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND (status='S' OR status='sakit')");
$jml_sakit = mysqli_num_rows($q_sakit);

$q_alpa = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND (status='A' OR status='alpa')");
$jml_alpa = mysqli_num_rows($q_alpa);
?>

<style>
    /* Animasi Masuk Halaman */
    .fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Kartu Statistik Beranimasi */
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-left: 5px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-8px); /* Efek Melayang saat disentuh */
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .stat-card h3 { margin: 0; font-size: 28px; font-weight: 800; color: #333; }
    .stat-card small { font-weight: bold; font-size: 12px; letter-spacing: 1px; color: #888; text-transform: uppercase; }
    
    .icon-circle {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        transition: 0.3s;
    }
    .stat-card:hover .icon-circle { transform: scale(1.1) rotate(10deg); }

    /* Tabel Modern */
    .table-hover tbody tr:hover {
        background-color: #fafafa;
        transform: scale(1.005);
        transition: 0.2s;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: inline-flex; align-items: center; gap: 6px;
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner fade-in-up" style="background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 30px; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(255, 140, 0, 0.3); position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <h2 style="margin: 0; font-size: 28px; font-weight: 800;"><i class="fas fa-user-clock"></i> Riwayat Kehadiran</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.95; font-size: 15px;">Pantau terus kedisiplinan dan kehadiran Anda di sekolah.</p>
        </div>
        <i class="fas fa-calendar-check" style="position: absolute; right: 20px; bottom: -20px; font-size: 120px; opacity: 0.15; transform: rotate(-15deg);"></i>
    </div>

    <div class="fade-in-up" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 20px; margin-bottom: 30px; animation-delay: 0.2s;">
        
        <div class="stat-card" style="border-left-color: #2ecc71;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small>HADIR</small>
                    <h3><?php echo $jml_hadir; ?></h3>
                </div>
                <div class="icon-circle" style="background: #e8f8f5; color: #2ecc71;">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #f1c40f;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small>IZIN</small>
                    <h3><?php echo $jml_izin; ?></h3>
                </div>
                <div class="icon-circle" style="background: #fef9e7; color: #f1c40f;">
                    <i class="fas fa-info"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #3498db;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small>SAKIT</small>
                    <h3><?php echo $jml_sakit; ?></h3>
                </div>
                <div class="icon-circle" style="background: #eaf2f8; color: #3498db;">
                    <i class="fas fa-procedures"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #e74c3c;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small>ALPA</small>
                    <h3><?php echo $jml_alpa; ?></h3>
                </div>
                <div class="icon-circle" style="background: #fdedec; color: #e74c3c;">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="modern-form-card fade-in-up" style="padding: 0; width: 100%; max-width: 100%; overflow: hidden; animation-delay: 0.4s;">
        
        <div style="padding: 20px; border-bottom: 1px solid #eee; background: #fff; display: flex; align-items: center; justify-content: space-between;">
            <h4 style="margin: 0; color: #444; font-weight: 700;">
                <i class="fas fa-list-ul" style="color: #FF8C00; margin-right: 10px;"></i> Log Aktivitas Kehadiran
            </h4>
            <span style="font-size: 12px; color: #888; background: #f5f5f5; padding: 5px 10px; border-radius: 10px;">Terbaru</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #fff8f0; color: #d35400;">
                    <tr>
                        <th style="padding: 18px; text-align: left; width: 50px;">#</th>
                        <th style="padding: 18px; text-align: left;">TANGGAL</th>
                        <th style="padding: 18px; text-align: left;">MATA PELAJARAN</th>
                        <th style="padding: 18px; text-align: left;">GURU PENGAJAR</th>
                        <th style="padding: 18px; text-align: center;">STATUS</th>
                        <th style="padding: 18px; text-align: left;">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT absensi.*, mapel.nama_mapel, users.nama_lengkap AS nama_guru 
                                                     FROM absensi 
                                                     JOIN mapel ON absensi.mapel_id = mapel.id_mapel 
                                                     JOIN users ON absensi.guru_id = users.id_user 
                                                     WHERE absensi.siswa_id='$id_siswa' 
                                                     ORDER BY absensi.tanggal DESC");

                    if(mysqli_num_rows($query) > 0){
                        while($d = mysqli_fetch_array($query)){
                            
                            // LOGIKA PERBAIKAN: Baca H/hadir
                            $stt = strtolower($d['status']);

                            if($stt == 'h' || $stt == 'hadir') { 
                                $badge = 'background:#d1f2eb; color:#0e6251; border: 1px solid #a2d9ce;'; 
                                $icon='check'; 
                                $label = 'HADIR';
                            }
                            elseif($stt == 'i' || $stt == 'izin') { 
                                $badge = 'background:#fcf3cf; color:#9a7d0a; border: 1px solid #f9e79f;'; 
                                $icon='info'; 
                                $label = 'IZIN';
                            }
                            elseif($stt == 's' || $stt == 'sakit') { 
                                $badge = 'background:#d6eaf8; color:#1b4f72; border: 1px solid #a9cce3;'; 
                                $icon='procedures'; 
                                $label = 'SAKIT';
                            }
                            else { 
                                $badge = 'background:#fadbd8; color:#922b21; border: 1px solid #e6b0aa;'; 
                                $icon='times'; 
                                $label = 'ALPA';
                            }
                    ?>
                    <tr style="border-bottom: 1px solid #f9f9f9; transition: 0.2s;">
                        <td style="padding: 18px; color: #999; font-weight: bold;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 18px;">
                            <div style="font-weight: 700; color: #333;"><?php echo date('d', strtotime($d['tanggal'])); ?></div>
                            <div style="font-size: 12px; color: #888; text-transform: uppercase;"><?php echo date('M Y', strtotime($d['tanggal'])); ?></div>
                        </td>

                        <td style="padding: 18px; font-weight: 600; color: #444;">
                            <?php echo $d['nama_mapel']; ?>
                        </td>

                        <td style="padding: 18px; color: #666;">
                            <i class="fas fa-chalkboard-teacher" style="color: #FF8C00; margin-right: 5px; opacity: 0.7;"></i>
                            <?php echo $d['nama_guru']; ?>
                        </td>

                        <td style="padding: 18px; text-align: center;">
                            <span class="badge-status" style="<?php echo $badge; ?>">
                                <i class="fas fa-<?php echo $icon; ?>"></i> <?php echo $label; ?>
                            </span>
                        </td>

                        <td style="padding: 18px; color: #777; font-style: italic; font-size: 13px;">
                            <?php echo empty($d['keterangan']) ? '<span style="color:#ddd;">-</span>' : $d['keterangan']; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="6" style="padding: 50px; text-align: center; color: #aaa;">
                            <img src="../assets/img/empty.svg" style="width: 80px; opacity: 0.5; margin-bottom: 15px; display: block; margin: 0 auto;">
                            <h4 style="margin: 0; font-weight: normal;">Belum ada data absensi.</h4>
                            <p style="font-size: 13px;">Rajinlah masuk sekolah ya!</p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>