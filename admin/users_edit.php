<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_user = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$d = mysqli_fetch_assoc($query);

// Cek apakah ada data detail siswa (meskipun sekarang bukan siswa, siapa tahu ada sisa data)
$q_detail = mysqli_query($koneksi, "SELECT * FROM siswa_detail WHERE user_id='$id_user'");
$detail = mysqli_fetch_assoc($q_detail);
$nis_value = isset($detail['nis']) ? $detail['nis'] : '';
$kelas_value = isset($detail['kelas_id']) ? $detail['kelas_id'] : '';
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
                <select name="role" id="roleSelect" class="form-control-modern" onchange="toggleSiswaForm()">
                    <option value="admin" <?php if($d['role']=='admin') echo 'selected'; ?>>Administrator</option>
                    <option value="guru" <?php if($d['role']=='guru') echo 'selected'; ?>>Guru</option>
                    <option value="siswa" <?php if($d['role']=='siswa') echo 'selected'; ?>>Siswa</option>
                </select>
                <small style="color: #888;">*Mengubah role akan menyesuaikan hak akses user ini.</small>
            </div>

            <div id="formSiswa" style="background: #fff8e1; padding: 20px; border-radius: 10px; border: 1px solid #ffe0b2; margin-bottom: 25px; display: <?php echo ($d['role']=='siswa') ? 'block' : 'none'; ?>;">
                <label style="font-weight: bold; color: #f39c12; margin-bottom: 15px; display: block;">
                    <i class="fas fa-graduation-cap"></i> Data Lengkap Siswa
                </label>
                
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>NIS (Nomor Induk Siswa)</label>
                        <input type="number" name="nis" class="form-control-modern" value="<?php echo $nis_value; ?>" placeholder="Wajib diisi untuk siswa">
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label>Penempatan Kelas</label>
                        <select name="kelas_id" class="form-control-modern" style="border-color: #ffe0b2;">
                            <option value="">-- Pilih Kelas --</option>
                            <?php 
                            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                            while($k = mysqli_fetch_array($kelas)){
                                $selected = ($kelas_value == $k['id_kelas']) ? "selected" : "";
                            ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php echo $selected; ?>>
                                <?php echo $k['nama_kelas']; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="users.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #4facfe, #00f2fe);">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

<script>
// Fungsi untuk menampilkan/menyembunyikan form siswa berdasarkan role
function toggleSiswaForm() {
    var role = document.getElementById("roleSelect").value;
    var formSiswa = document.getElementById("formSiswa");
    
    if (role === "siswa") {
        formSiswa.style.display = "block";
    } else {
        formSiswa.style.display = "none";
    }
}
</script>

<?php include 'footer.php'; ?>