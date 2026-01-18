<?php 
include 'header.php'; 
include 'sidebar.php'; 

// Ambil ID User
$id_user = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$d = mysqli_fetch_assoc($query);

// Foto Profil
$foto = ($d['foto_profil'] && $d['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/avatar-default.svg";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* LAYOUT UTAMA */
    .profile-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 30px;
        margin-top: -20px;
        align-items: start;
    }

    .right-column {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .card-box {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* CARD KIRI (FOTO) */
    .profile-header-bg { height: 120px; background: linear-gradient(135deg, #FF8C00, #F39C12); }
    .profile-img-wrap { margin-top: -60px; position: relative; display: inline-block; left: 50%; transform: translateX(-50%); }
    .profile-img { width: 120px; height: 120px; border-radius: 50%; border: 5px solid white; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1); background: #fff; }
    .profile-info { padding: 20px 25px 30px 25px; text-align: center; }
    .profile-name { font-size: 20px; font-weight: 700; color: #333; margin: 10px 0 5px 0; }
    .profile-role { display: inline-block; background: #FFF3E0; color: #E65100; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; letter-spacing: 1px; }
    .profile-meta { margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; text-align: left; }
    .meta-item { margin-bottom: 15px; font-size: 14px; color: #666; display: flex; align-items: center; gap: 15px; }
    .meta-item i { color: #FF8C00; width: 20px; text-align: center; }

    /* CARD KANAN (EDIT) */
    .edit-card-body { padding: 35px; } /* Padding diperbesar agar lega */
    
    .card-header-title {
        font-size: 16px; font-weight: 700; color: #444;
        border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 30px;
        display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* FORM STYLING (JARAK DIPERLEBAR) */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 35px; /* Jarak Horizontal antar kolom input */
    }
    
    .form-group {
        margin-bottom: 25px; /* Jarak Vertikal antar baris */
    }

    .form-label {
        display: block; font-weight: 600; color: #555;
        margin-bottom: 12px; /* Jarak Label ke Input */
        font-size: 13px;
    }

    .form-control-custom {
        width: 100%; padding: 12px 15px;
        border: 1px solid #e0e0e0; border-radius: 10px;
        font-size: 14px; transition: 0.3s; background: #fafafa; color: #333;
        box-sizing: border-box; /* Pastikan padding tidak merusak lebar */
    }
    .form-control-custom:focus {
        border-color: #FF8C00; background: #fff; outline: none;
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
    }

    .upload-area { border: 2px dashed #ddd; padding: 15px; text-align: center; border-radius: 10px; cursor: pointer; transition: 0.3s; display: block; }
    .upload-area:hover { border-color: #FF8C00; background: #FFF3E0; }

    .btn-save-profile {
        background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none;
        padding: 12px 30px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s; float: right;
    }
    .btn-save-profile:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3); }

    @media (max-width: 900px) {
        .profile-container { grid-template-columns: 1fr; }
        .form-grid { grid-template-columns: 1fr; gap: 20px; }
    }
</style>

<div class="content-body">

    <form action="profil_update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_user" value="<?php echo $d['id_user']; ?>">

    <div class="profile-container">
        
        <div class="card-box">
            <div class="profile-header-bg"></div>
            <div class="profile-img-wrap">
                <img src="<?php echo $foto; ?>" class="profile-img" id="imgPreview">
            </div>
            
            <div class="profile-info">
                <div class="profile-name"><?php echo $d['nama_lengkap']; ?></div>
                <div class="profile-role"><?php echo strtoupper($d['role']); ?></div>
                <div class="profile-meta">
                    <div class="meta-item"><i class="fas fa-id-card"></i> ID: <?php echo $d['username']; ?></div>
                    <div class="meta-item"><i class="fas fa-envelope"></i> <?php echo $d['email']; ?></div>
                </div>
                <div style="margin-top: 20px; text-align: left;">
                    <label class="form-label" style="font-size: 12px;">Update Foto Profil</label>
                    <input type="file" name="foto" id="fileInput" style="display:none;" accept="image/*" onchange="previewImage()">
                    <label for="fileInput" class="upload-area">
                        <i class="fas fa-camera" style="color: #FF8C00;"></i> <span id="fileName" style="font-size:12px; color:#777;">Pilih Foto...</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="right-column">
            
            <div class="card-box">
                <div class="edit-card-body">
                    <div class="card-header-title"><i class="fas fa-user-edit" style="color: #FF8C00;"></i> Informasi Akun</div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control-custom" value="<?php echo $d['nama_lengkap']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Aktif</label>
                            <input type="email" name="email" class="form-control-custom" value="<?php echo $d['email']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control-custom" value="<?php echo $d['username']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="card-box">
                <div class="edit-card-body">
                    <div class="card-header-title"><i class="fas fa-lock" style="color: #c0392b;"></i> Ubah Password</div>

                    <div class="form-group">
                        <label class="form-label">Password Lama (Wajib jika ingin mengganti)</label>
                        <input type="password" name="pass_lama" class="form-control-custom" placeholder="Masukkan password saat ini...">
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="pass_baru" class="form-control-custom" placeholder="Password baru...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="pass_konf" class="form-control-custom" placeholder="Ulangi password baru...">
                        </div>
                    </div>

                    <div style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 20px;">
                        <button type="submit" class="btn-save-profile"><i class="fas fa-check-circle"></i> SIMPAN SEMUA PERUBAHAN</button>
                    </div>
                </div>
            </div>

        </div> 
    </div>
    </form>
</div>

<script>
    function previewImage() {
        const file = document.getElementById('fileInput').files[0];
        if (file) {
            document.getElementById('fileName').innerText = file.name;
            const reader = new FileReader();
            reader.onload = function(e) { document.getElementById('imgPreview').src = e.target.result; }
            reader.readAsDataURL(file);
        }
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonColor: '#FF8C00'
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>