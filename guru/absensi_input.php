<?php include 'header.php'; 

// 1. TANGKAP KELAS DAN MAPEL DARI URL
$id_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$id_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : ''; // Wajib menangkap ini
$tanggal_hari_ini = date('Y-m-d');

// Cek jika data URL tidak lengkap
if(empty($id_kelas) || empty($id_mapel)){
    echo "<div style='padding: 20px; text-align: center;'>
            <h3 style='color: red;'>Data Tidak Lengkap!</h3>
            <p>Pastikan Anda membuka halaman ini melalui menu <b>Absensi Siswa</b> dan memilih Mata Pelajaran yang benar.</p>
            <a href='absensi.php' class='btn-submit' style='text-decoration:none; background:#333;'>Kembali</a>
          </div>";
    include 'footer.php'; 
    exit(); // Stop loading halaman
}

// Ambil info kelas
$q_kelas_info = mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id_kelas='$id_kelas'");
$info_k = mysqli_fetch_assoc($q_kelas_info);

// Ambil info mapel (Agar judul jelas)
$q_mapel_info = mysqli_query($koneksi, "SELECT nama_mapel FROM mapel WHERE id_mapel='$id_mapel'");
$info_m = mysqli_fetch_assoc($q_mapel_info);

// PENCEGAHAN ERROR: Jika mapel ID salah dan tidak ketemu di DB
$nama_mapel = isset($info_m['nama_mapel']) ? $info_m['nama_mapel'] : 'Mapel Tidak Ditemukan';
?>

<div class="form-center-wrapper" style="display: block;"> 
    <div class="modern-form-card" style="max-width: 1000px; margin: 0 auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f5f5f5; padding-bottom: 20px;">
            <div>
                <h3 style="margin: 0; color: #333;">Input Absensi: <?php echo $info_k['nama_kelas']; ?></h3>
                <h4 style="margin: 5px 0 0 0; color: #667eea;"><?php echo $nama_mapel; ?></h4>
                <small style="color: #888;">Silakan tandai status kehadiran siswa.</small>
            </div>
            
            <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="hidden" name="kelas" value="<?php echo $id_kelas; ?>">
                <input type="hidden" name="mapel" value="<?php echo $id_mapel; ?>">
                
                <label style="font-weight: bold; color: #555;">Tanggal:</label>
                <input type="date" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini; ?>" class="form-control-modern" style="padding: 8px;" onchange="this.form.submit()">
            </form>
        </div>

        <form action="absensi_aksi.php" method="POST">
            <input type="hidden" name="kelas_id" value="<?php echo $id_kelas; ?>">
            <input type="hidden" name="mapel_id" value="<?php echo $id_mapel; ?>">
            <input type="hidden" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini; ?>">

            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px; text-align: left; color: #555; border-bottom: 2px solid #eee;">Siswa</th>
                        <th style="padding: 15px; text-align: center; color: #555; border-bottom: 2px solid #eee; width: 300px;">Status Kehadiran</th>
                        <th style="padding: 15px; text-align: left; color: #555; border-bottom: 2px solid #eee;">Keterangan (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tgl_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini;

                    // Ambil siswa
                    $q_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, users.id_user, siswa_detail.nis 
                                                       FROM siswa_detail 
                                                       JOIN users ON siswa_detail.user_id = users.id_user 
                                                       WHERE siswa_detail.kelas_id='$id_kelas' 
                                                       ORDER BY users.nama_lengkap ASC");

                    while($s = mysqli_fetch_array($q_siswa)){
                        $id_siswa = $s['id_user'];

                        // Cek data absensi (Filter juga berdasarkan MAPEL agar tidak tertukar)
                        $q_cek = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND tanggal='$tgl_pilih' AND mapel_id='$id_mapel'");
                        $data_absen = mysqli_fetch_assoc($q_cek);
                        
                        $status = isset($data_absen['status']) ? $data_absen['status'] : 'H';
                        $ket = isset($data_absen['keterangan']) ? $data_absen['keterangan'] : '';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px;">
                            <div style="font-weight: bold; color: #333;"><?php echo $s['nama_lengkap']; ?></div>
                            <small style="color: #888;">NIS: <?php echo $s['nis']; ?></small>
                            <input type="hidden" name="siswa_id[]" value="<?php echo $id_siswa; ?>">
                        </td>
                        
                        <td style="padding: 15px; text-align: center;">
                            <div class="attendance-options">
                                <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="H_<?php echo $id_siswa; ?>" value="H" <?php if($status=='H') echo 'checked'; ?>> <label for="H_<?php echo $id_siswa; ?>">Hadir</label>
                                <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="S_<?php echo $id_siswa; ?>" value="S" <?php if($status=='S') echo 'checked'; ?>> <label for="S_<?php echo $id_siswa; ?>">Sakit</label>
                                <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="I_<?php echo $id_siswa; ?>" value="I" <?php if($status=='I') echo 'checked'; ?>> <label for="I_<?php echo $id_siswa; ?>">Izin</label>
                                <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="A_<?php echo $id_siswa; ?>" value="A" <?php if($status=='A') echo 'checked'; ?>> <label for="A_<?php echo $id_siswa; ?>">Alpa</label>
                            </div>
                        </td>

                        <td style="padding: 15px;">
                            <input type="text" name="keterangan[<?php echo $id_siswa; ?>]" class="form-control-modern" placeholder="..." value="<?php echo $ket; ?>" style="padding: 8px; font-size: 13px;">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" class="btn-submit" style="width: 200px; background: linear-gradient(to right, #667eea, #764ba2); color:white; border:none; padding:10px; cursor:pointer;">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>

        </form>
    </div>
</div>

<?php include 'footer.php'; ?>