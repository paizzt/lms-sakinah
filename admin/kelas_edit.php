<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas='$id'");
$d = mysqli_fetch_array($query);
?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 600px;">
        
        <div class="form-header">
            <h3><i class="fas fa-edit" style="color: #f39c12; background: #fff8e1;"></i> Edit Data Kelas</h3>
        </div>

        <form action="kelas_update.php" method="POST">
            <input type="hidden" name="id_kelas" value="<?php echo $d['id_kelas']; ?>">
            
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control-modern" value="<?php echo $d['nama_kelas']; ?>" required>
            </div>

            <div class="form-group">
                <label>Wali Kelas (Guru)</label>
                <select name="wali_kelas" class="form-control-modern" required>
                    <option value="">-- Pilih Guru Wali Kelas --</option>
                    <?php 
                    $guru = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru' ORDER BY nama_lengkap ASC");
                    while($g = mysqli_fetch_array($guru)){
                        $selected = ($g['id_user'] == $d['wali_kelas_id']) ? "selected" : "";
                    ?>
                    <option value="<?php echo $g['id_user']; ?>" <?php echo $selected; ?>><?php echo $g['nama_lengkap']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="kelas.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #f39c12, #f1c40f);">Update Kelas</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>