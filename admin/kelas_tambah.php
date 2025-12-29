<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 600px;">
        
        <div class="form-header">
            <h3><i class="fas fa-school" style="color: #2575fc; background: #eef2ff;"></i> Tambah Kelas Baru</h3>
        </div>

        <form action="kelas_aksi.php" method="POST">
            
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control-modern" placeholder="Contoh: 10 IPA 1" required>
            </div>

            <div class="form-group">
                <label>Wali Kelas (Guru)</label>
                <select name="wali_kelas" class="form-control-modern" required>
                    <option value="">-- Pilih Guru Wali Kelas --</option>
                    <?php 
                    // Ambil user yang role-nya guru
                    $guru = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru' ORDER BY nama_lengkap ASC");
                    while($g = mysqli_fetch_array($guru)){
                    ?>
                    <option value="<?php echo $g['id_user']; ?>"><?php echo $g['nama_lengkap']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="kelas.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #6a11cb, #2575fc);">Simpan Kelas</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>