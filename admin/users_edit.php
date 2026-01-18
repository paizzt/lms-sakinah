<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <h2 style="margin: 0; font-size: 24px; font-weight: 700;">Edit User</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Perbarui data pengguna sistem.</p>
    </div>

    <div class="modern-form-card" style="padding: 30px; background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        
        <div style="margin-bottom: 20px;">
            <a href="users.php" class="btn-back" style="text-decoration: none; color: #555; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php 
        $id = $_GET['id'];
        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
        while($d = mysqli_fetch_array($query)){
        ?>

        <form action="users_update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $d['id_user']; ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" required="required" value="<?php echo $d['nama_lengkap']; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Username</label>
                <input type="text" class="form-control" name="username" required="required" value="<?php echo $d['username']; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <small style="color: #777; font-size: 12px;">* Isi hanya jika ingin mengganti password lama.</small>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Level / Role</label>
                <select class="form-control" name="role" required="required" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" onchange="toggleKelas(this.value)">
                    <option value="">-- Pilih Level --</option>
                    <option value="admin" <?php if($d['role']=="admin"){echo "selected";} ?>>Admin</option>
                    <option value="guru" <?php if($d['role']=="guru"){echo "selected";} ?>>Guru</option>
                    <option value="siswa" <?php if($d['role']=="siswa"){echo "selected";} ?>>Siswa</option>
                </select>
            </div>

            <div class="form-group" id="boxKelas" style="margin-bottom: 20px; display: <?php echo ($d['role'] == 'siswa') ? 'block' : 'none'; ?>;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #E65100;">Kelas Siswa</label>
                <select name="kelas_id" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #FF8C00; border-radius: 5px; background: #FFF3E0;">
                    <option value="0">-- Pilih Kelas --</option>
                    <?php 
                    $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                    while($k = mysqli_fetch_array($kelas)){
                        $sel = ($d['kelas_id'] == $k['id_kelas']) ? "selected" : "";
                    ?>
                        <option value="<?php echo $k['id_kelas']; ?>" <?php echo $sel; ?>><?php echo $k['nama_kelas']; ?></option>
                    <?php } ?>
                </select>
                <small style="color: #E65100;">* Wajib dipilih jika role adalah Siswa.</small>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Foto Profil</label>
                <input type="file" name="foto" style="margin-bottom: 10px;">
                <br>
                <?php if($d['foto_profil'] == "") { ?>
                    <img src="../assets/img/avatar-default.svg" style="width: 80px; border-radius: 50%;">
                <?php } else { ?>
                    <img src="../uploads/profil/<?php echo $d['foto_profil']; ?>" style="width: 80px; border-radius: 50%; object-fit: cover;">
                <?php } ?>
                <small style="display:block; color: #777;">* Abaikan jika tidak ingin mengganti foto.</small>
            </div>

            <button type="submit" class="btn-simpan" style="background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                <i class="fas fa-save"></i> SIMPAN PERUBAHAN
            </button>
        </form>

        <?php } ?>
    </div>
</div>

<script>
    // Script sederhana untuk Show/Hide Dropdown Kelas
    function toggleKelas(role) {
        var box = document.getElementById('boxKelas');
        if(role === 'siswa') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
</script>

<?php include 'footer.php'; ?>