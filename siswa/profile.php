<?php include 'header.php'; ?>

<?php
$id_user = $_SESSION['id_user'];
// Join tabel users, siswa_detail, dan kelas
$query = "SELECT users.*, siswa_detail.*, kelas.nama_kelas 
          FROM users 
          JOIN siswa_detail ON users.id_user = siswa_detail.user_id 
          LEFT JOIN kelas ON siswa_detail.kelas_id = kelas.id_kelas 
          WHERE users.id_user='$id_user'";
$d = mysqli_fetch_array(mysqli_query($koneksi, $query));
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #f83600, #f9d423); color: white; padding: 20px 30px; border-radius: 15px; margin-bottom: 40px; box-shadow: 0 10px 20px rgba(249, 212, 35, 0.2);">
    <h2 style="margin: 0; font-size: 24px;">Pengaturan Profil</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola informasi pribadi dan keamanan akun Anda.</p>
</div>

<div class="profile-layout">
    
    <div class="profile-card-left">
        <style>.profile-card-left::before { background: linear-gradient(to right, #ff9966, #ff5e62); }</style>
        
        <div class="profile-img-wrap">
            <?php $foto = $d['foto_profil'] ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/default.jpg"; ?>
            <img src="<?php echo $foto; ?>" class="profile-img">
            
            <form action="profile_aksi.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="act" value="update_foto">
                <label for="fileFoto" class="btn-upload-foto" title="Ganti Foto">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" name="foto_profil" id="fileFoto" style="display: none;" onchange="this.form.submit()">
            </form>
        </div>

        <h3 class="profile-name"><?php echo $d['nama_lengkap']; ?></h3>
        <span class="profile-role">Siswa - <?php echo $d['nama_kelas'] ? $d['nama_kelas'] : 'Belum ada kelas'; ?></span>

        <div class="profile-info-list">
            <div class="info-item">
                <i class="fas fa-id-card" style="color: #ff5e62;"></i> 
                <span>NIS: <b><?php echo $d['nis'] ? $d['nis'] : '-'; ?></b></span>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope" style="color: #ff5e62;"></i> 
                <span><?php echo $d['email']; ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-user-circle" style="color: #ff5e62;"></i> 
                <span>@<?php echo $d['username']; ?></span>
            </div>
        </div>
    </div>

    <div class="profile-content-right">
        
        <div class="profile-section-card">
            <div class="section-title">
                <i class="fas fa-user-edit" style="color: #ff9966;"></i> Edit Biodata Diri
            </div>
            
            <form action="profile_aksi.php" method="POST">
                <input type="hidden" name="act" value="update_bio">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control-modern" value="<?php echo $d['nama_lengkap']; ?>" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control-modern" value="<?php echo $d['tempat_lahir']; ?>">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control-modern" value="<?php echo $d['tanggal_lahir']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control-modern" value="<?php echo $d['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat Domisili</label>
                    <textarea name="alamat" class="form-control-modern" rows="3"><?php echo $d['alamat']; ?></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn-submit" style="width: auto; padding: 12px 30px; background: linear-gradient(to right, #ff9966, #ff5e62);">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <div class="profile-section-card">
            <div class="section-title">
                <i class="fas fa-lock" style="color: #ff9966;"></i> Keamanan Akun
            </div>
            
            <form action="profile_aksi.php" method="POST">
                <input type="hidden" name="act" value="ganti_pass">
                
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="pass_lama" class="form-control-modern" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Password Baru</label>
                        <input type="password" name="pass_baru" class="form-control-modern" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Ulangi Password Baru</label>
                        <input type="password" name="konf_baru" class="form-control-modern" required>
                    </div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn-submit" style="width: auto; padding: 12px 30px; background: #333;">Update Password</button>
                </div>
            </form>
        </div>

    </div>

</div>

<?php include 'footer.php'; ?>