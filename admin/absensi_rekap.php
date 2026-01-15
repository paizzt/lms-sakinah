<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php 
// 1. TANGKAP DATA FILTER
$kelas_pilih = isset($_GET['kelas_id']) ? $_GET['kelas_id'] : '';
$mapel_pilih = isset($_GET['mapel_id']) ? $_GET['mapel_id'] : ''; 
$tgl_pilih   = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-user-check"></i> Rekap Absensi Harian</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau kehadiran siswa per Mata Pelajaran & Hari.</p>
    </div>

    <div class="modern-form-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 200px;">
                <label style="font-weight: bold; font-size: 13px; color: #555;">Pilih Kelas</label>
                <select name="kelas_id" class="form-control-modern" onchange="this.form.submit()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php 
                    $k = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                    while($kls = mysqli_fetch_array($k)){
                        $sel = ($kls['id_kelas'] == $kelas_pilih) ? 'selected' : '';
                        echo "<option value='".$kls['id_kelas']."' $sel>".$kls['nama_kelas']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="font-weight: bold; font-size: 13px; color: #555;">Pilih Mapel</label>
                <select name="mapel_id" class="form-control-modern" onchange="this.form.submit()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">-- Pilih Mapel --</option>
                    <?php 
                    if($kelas_pilih != ""){
                        $m = mysqli_query($koneksi, "SELECT * FROM mapel WHERE kelas_id='$kelas_pilih' ORDER BY nama_mapel ASC");
                        while($mpl = mysqli_fetch_array($m)){
                            $sel = ($mpl['id_mapel'] == $mapel_pilih) ? 'selected' : '';
                            echo "<option value='".$mpl['id_mapel']."' $sel>".$mpl['nama_mapel']."</option>";
                        }
                    } else {
                        echo "<option value=''>Pilih Kelas Dulu</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="font-weight: bold; font-size: 13px; color: #555;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control-modern" value="<?php echo $tgl_pilih; ?>" onchange="this.form.submit()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

        </form>
    </div>

    <?php if($kelas_pilih != "" && $mapel_pilih != "") { ?>
        
        <div style="margin-bottom: 20px; text-align: right;">
            <a href="absensi_detail.php?id=<?php echo $mapel_pilih; ?>" class="btn-tambah" style="background: #2980b9; color: white; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fas fa-chart-bar"></i> LIHAT REKAP TOTAL
            </a>
        </div>

        <div class="modern-form-card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #FFF3E0; color: #E65100;">
                        <tr>
                            <th style="padding: 15px; width: 5%;">No</th>
                            <th style="padding: 15px;">Nama Siswa</th>
                            <th style="padding: 15px;">NIS</th>
                            <th style="padding: 15px; text-align: center;">Status (<?php echo date('d-m-Y', strtotime($tgl_pilih)); ?>)</th>
                            <th style="padding: 15px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        // Ambil Siswa di Kelas Tersebut
                        $q_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, users.id_user, siswa_detail.nis 
                                                        FROM siswa_detail 
                                                        JOIN users ON siswa_detail.user_id = users.id_user 
                                                        WHERE siswa_detail.kelas_id='$kelas_pilih' 
                                                        ORDER BY users.nama_lengkap ASC");
                        
                        if(mysqli_num_rows($q_siswa) == 0){ echo "<tr><td colspan='5' align='center' style='padding:20px;'>Tidak ada siswa.</td></tr>"; }

                        while($s = mysqli_fetch_array($q_siswa)){
                            // Query Absensi (Filter Mapel & Tanggal)
                            $q_absen = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='".$s['id_user']."' AND tanggal='$tgl_pilih' AND mapel_id='$mapel_pilih'");
                            $data = mysqli_fetch_array($q_absen);
                            
                            // --- LOGIKA UTAMA: BACA KATA PENUH ('hadir', 'sakit', dll) ---
                            $stt = isset($data['status']) ? strtolower($data['status']) : '';

                            if($stt == 'hadir' || $stt == 'h') {
                                $badge = "<span style='background:#d1f2eb; color:#0e6251; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:12px;'>HADIR</span>";
                            } elseif($stt == 'sakit' || $stt == 's') {
                                $badge = "<span style='background:#d6eaf8; color:#154360; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:12px;'>SAKIT</span>";
                            } elseif($stt == 'izin' || $stt == 'i') {
                                $badge = "<span style='background:#fcf3cf; color:#7d6608; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:12px;'>IZIN</span>";
                            } elseif($stt == 'alpa' || $stt == 'a') {
                                $badge = "<span style='background:#fadbd8; color:#78281f; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:12px;'>ALPA</span>";
                            } else {
                                $badge = "<span style='color:#ccc; font-style:italic;'>Belum Absen</span>";
                            }
                        ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                            <td style="padding: 15px; font-weight: 600; color: #333;"><?php echo $s['nama_lengkap']; ?></td>
                            <td style="padding: 15px; color: #555;"><?php echo $s['nis']; ?></td>
                            <td style="padding: 15px; text-align: center;"><?php echo $badge; ?></td>
                            <td style="padding: 15px; color: #777;"><?php echo isset($data['keterangan']) ? $data['keterangan'] : '-'; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php } else { ?>
        <div style="text-align: center; padding: 50px; background: white; border-radius: 10px;">
            <i class="fas fa-search" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #555;">Silakan Pilih Kelas & Mapel Terlebih Dahulu</h3>
        </div>
    <?php } ?>

</div>

<?php include 'footer.php'; ?>