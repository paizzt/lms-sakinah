<?php include 'header.php'; ?>

<?php
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM users WHERE id_user='$id_user'";
$d = mysqli_fetch_array(mysqli_query($koneksi, $query));
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #11998e, #38ef7d); color: white; padding: 20px 30px; border-radius: 15px; margin-bottom: 40px; box-shadow: 0 10px 20px rgba(56, 239, 125, 0.2);">
    <h2 style="margin: 0; font-size: 24px;">Profil Pengajar</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola informasi pribadi Anda di sini.</p>
</div>

<div class="profile-layout">
    
    <div class="profile-card-left">
        <style>.profile-card-left::before { background: linear-gradient(to right, #11998e, #38ef7d); }</style>

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
        <span class="profile-role">Guru Mata Pelajaran</span>

        <div class="profile-info-list">
            <div class="info-item">
                <i class="fas fa-envelope" style="color: #11998e;"></i> 
                <span><?php echo $d['email']; ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-user-circle" style="color: #11998e;"></i> 
                <span>@<?php echo $d['username']; ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-check-circle" style="color: #11998e;"></i> 
                <span>Status: <b>Aktif</b></span>
            </div>
        </div>
    </div>

    <div class="profile-content-right">
        
        <div class="profile-section-card">
            <div class="section-title">
                <i class="fas fa-user-edit" style="color: #11998e;"></i> Informasi Akun
            </div>
            
            <form action="profile_aksi.php" method="POST">
                <input type="hidden" name="act" value="update_bio">
                
                <div class="form-group">
                    <label>Nama Lengkap (Beserta Gelar)</label>
                    <input type="text" name="nama" class="form-control-modern" value="<?php echo $d['nama_lengkap']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control-modern" value="<?php echo $d['email']; ?>" required>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn-submit" style="width: auto; padding: 12px 30px; background: linear-gradient(to right, #11998e, #38ef7d);">Simpan Data</button>
                </div>
            </form>
        </div>

        <div class="profile-section-card">
            <div class="section-title">
                <i class="fas fa-lock" style="color: #11998e;"></i> Ganti Password
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
                        <label>Konfirmasi Password</label>
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