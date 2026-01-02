<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM users WHERE id_user='$id_user'";
$d = mysqli_fetch_array(mysqli_query($koneksi, $query));
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-chalkboard-teacher"></i> Profil Guru</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola informasi akun dan biodata pengajar.</p>
</div>

<div style="display: flex; gap: 30px; flex-wrap: wrap;">

    <div style="flex: 1; min-width: 300px;">
        <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
            
            <div style="position: relative; width: 150px; height: 150px; margin: 0 auto 20px auto;">
                <?php 
                    // Logika Foto Default SVG
                    $foto = ($d['foto_profil'] && $d['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/avatar-default.svg";
                ?>
                <img src="<?php echo $foto; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 5px solid #fff3e0;">
                
                <label for="uploadFoto" style="position: absolute; bottom: 0; right: 10px; background: #FF8C00; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.3s;">
                    <i class="fas fa-camera"></i>
                </label>
            </div>

            <h3 style="margin: 0; color: #333;"><?php echo $d['nama_lengkap']; ?></h3>
            <span style="display: block; color: #888; font-size: 14px; margin-top: 5px;">Guru Pengajar</span>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            
            <div style="text-align: left; font-size: 14px; color: #555;">
                <p><i class="fas fa-envelope" style="width: 25px; color: #FF8C00;"></i> <?php echo $d['email']; ?></p>
                <p><i class="fas fa-user" style="width: 25px; color: #FF8C00;"></i> <?php echo $d['username']; ?></p>
                <p><i class="fas fa-clock" style="width: 25px; color: #FF8C00;"></i> Login Terakhir: Sekarang</p>
            </div>

            <form action="profile_aksi.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="act" value="update_foto">
                <input type="file" name="foto_profil" id="uploadFoto" style="display: none;" onchange="this.form.submit()">
            </form>

        </div>
    </div>

    <div style="flex: 2; min-width: 300px;">
        
        <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="padding: 15px 30px; background: #fff8e1; border-bottom: 1px solid #ffe0b2; font-weight: bold; color: #e67e22;">
                <i class="fas fa-edit"></i> Edit Informasi Akun
            </div>
            
            <div style="padding: 30px;">
                <form action="profile_aksi.php" method="POST">
                    <input type="hidden" name="act" value="update_bio">
                    
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control-modern" value="<?php echo $d['nama_lengkap']; ?>" required>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control-modern" value="<?php echo $d['username']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control-modern" value="<?php echo $d['email']; ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #FF8C00, #F39C12); border:none; width:100%; padding:12px; color:white; border-radius:8px; cursor:pointer;">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <br>

        <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="padding: 15px 30px; background: #f9f9f9; border-bottom: 1px solid #eee; font-weight: bold; color: #444;">
                <i class="fas fa-lock"></i> Ganti Password
            </div>
            <div style="padding: 30px;">
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
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="konf_baru" class="form-control-modern" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" style="background: #333; border:none; width:100%; padding:12px; color:white; border-radius:8px; cursor:pointer;">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>