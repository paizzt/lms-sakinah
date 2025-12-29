<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_user = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$d = mysqli_fetch_assoc($query);

// Ambil data detail siswa jika role-nya siswa
$detail = null;
if($d['role'] == 'siswa'){
    $q_detail = mysqli_query($koneksi, "SELECT * FROM siswa_detail WHERE user_id='$id_user'");
    $detail = mysqli_fetch_assoc($q_detail);
}
?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 800px;">
        
        <div class="form-header">
            <h3><i class="fas fa-user-edit" style="color: #4facfe; background: #e3f2fd;"></i> Edit Data User</h3>
        </div>

        <form action="users_update.php" method="POST">
            <input type="hidden" name="id_user" value="<?php echo $d['id_user']; ?>">
            <input type="hidden" name="role_lama" value="<?php echo $d['role']; ?>">

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control-modern" value="<?php echo $d['nama_lengkap']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control-modern" value="<?php echo $d['username']; ?>" required>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control-modern" value="<?php echo isset($d['email']) ? $d['email'] : ''; ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Password <small style="color: #999; font-weight: normal;">(Kosongkan jika tidak ingin mengubah)</small></label>
                    <input type="password" name="password" class="form-control-modern" placeholder="Buat password baru...">
                </div>
            </div>

            <div class="form-group">
                <label>Role (Hak Akses)</label>
                <select name="role" class="form-control-modern" disabled style="background-color: #f0f0f0; cursor: not-allowed;">
                    <option value="admin" <?php if($d['role']=='admin') echo 'selected'; ?>>Administrator</option>
                    <option value="guru" <?php if($d['role']=='guru') echo 'selected'; ?>>Guru</option>
                    <option value="siswa" <?php if($d['role']=='siswa') echo 'selected'; ?>>Siswa</option>
                </select>
                <small style="color: #d63031;">*Role user tidak dapat diubah dari menu edit.</small>
            </div>

            <?php if($d['role'] == 'siswa') { ?>
            <div style="background: #fff8e1; padding: 20px; border-radius: 10px; border: 1px solid #ffe0b2; margin-bottom: 25px;">
                <label style="font-weight: bold; color: #f39c12; margin-bottom: 10px; display: block;">
                    <i class="fas fa-graduation-cap"></i> Penempatan Kelas
                </label>
                
                <select name="kelas_id" class="form-control-modern" style="border-color: #ffe0b2;">
                    <option value="">-- Belum Masuk Kelas --</option>
                    <?php 
                    $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                    while($k = mysqli_fetch_array($kelas)){
                        // Cek apakah siswa ini ada di kelas tersebut
                        $selected = ($detail && $detail['kelas_id'] == $k['id_kelas']) ? "selected" : "";
                    ?>
                    <option value="<?php echo $k['id_kelas']; ?>" <?php echo $selected; ?>>
                        <?php echo $k['nama_kelas']; ?>
                    </option>
                    <?php } ?>
                </select>
                <span class="text-hint">Pindahkan siswa ke kelas lain jika diperlukan.</span>
            </div>
            <?php } ?>

            <div class="form-actions">
                <a href="users.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #4facfe, #00f2fe);">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>