<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php 
$kelas_pilih = isset($_GET['kelas_id']) ? $_GET['kelas_id'] : '';
$tgl_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #4b6cb7, #182848); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-user-check"></i> Rekap Absensi</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Lihat laporan kehadiran siswa per kelas.</p>
</div>

<div class="modern-form-card" style="margin-bottom: 20px; padding: 20px;">
    <form method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
        
        <div style="flex: 1;">
            <label style="font-weight: bold; font-size: 13px;">Pilih Kelas</label>
            <select name="kelas_id" class="form-control-modern" onchange="this.form.submit()">
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

        <div style="flex: 1;">
            <label style="font-weight: bold; font-size: 13px;">Tanggal</label>
            <input type="date" name="tanggal" class="form-control-modern" value="<?php echo $tgl_pilih; ?>" onchange="this.form.submit()">
        </div>

        <a href="absensi_rekap.php" class="btn-cancel" style="padding: 10px 20px; font-size: 14px;">Reset</a>

    </form>
</div>

<?php if($kelas_pilih != "") { ?>
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th style="text-align: center;">Status Kehadiran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                // Ambil semua siswa di kelas ini
                $q_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, siswa_detail.nis, users.id_user 
                                                   FROM siswa_detail 
                                                   JOIN users ON siswa_detail.user_id = users.id_user 
                                                   WHERE siswa_detail.kelas_id='$kelas_pilih' 
                                                   ORDER BY users.nama_lengkap ASC");

                if(mysqli_num_rows($q_siswa) == 0){
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada siswa di kelas ini.</td></tr>";
                }

                while($s = mysqli_fetch_array($q_siswa)){
                    // Cek data absensi
                    $q_absen = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='".$s['id_user']."' AND tanggal='$tgl_pilih'");
                    $data = mysqli_fetch_array($q_absen);
                    
                    // Tentukan Badge Status
                    $status = isset($data['status']) ? $data['status'] : '-';
                    $badge = "<span style='color:#ccc;'>Belum Absen</span>";

                    if($status == 'H') $badge = "<span class='badge-status bg-success'>HADIR</span>";
                    if($status == 'S') $badge = "<span class='badge-status bg-warning'>SAKIT</span>";
                    if($status == 'I') $badge = "<span class='badge-status bg-info'>IZIN</span>";
                    if($status == 'A') $badge = "<span class='badge-status bg-danger'>ALPA</span>";
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $s['nama_lengkap']; ?></b></td>
                    <td><?php echo $s['nis']; ?></td>
                    <td style="text-align: center;"><?php echo $badge; ?></td>
                    <td><?php echo isset($data['keterangan']) ? $data['keterangan'] : '-'; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div style="text-align: center; padding: 50px; color: #999;">
        <i class="fas fa-arrow-up"></i> Silakan pilih Kelas terlebih dahulu.
    </div>
<?php } ?>

<?php include 'footer.php'; ?>