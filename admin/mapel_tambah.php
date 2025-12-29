<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 800px;">
        
        <div class="form-header">
            <h3><i class="fas fa-book-open" style="color: #11998e; background: #e0f2f1;"></i> Tambah Mata Pelajaran</h3>
        </div>

        <form action="mapel_aksi.php" method="POST">
            
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Kode Mapel</label>
                    <input type="text" name="kode_mapel" class="form-control-modern" placeholder="Contoh: MTK-10" required>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" class="form-control-modern" placeholder="Contoh: Matematika Wajib" required>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control-modern" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php 
                        $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                        while($k = mysqli_fetch_array($kelas)){
                        ?>
                        <option value="<?php echo $k['id_kelas']; ?>"><?php echo $k['nama_kelas']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label>Guru Pengampu</label>
                    <select name="guru_id" class="form-control-modern" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php 
                        $guru = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru' ORDER BY nama_lengkap ASC");
                        while($g = mysqli_fetch_array($guru)){
                        ?>
                        <option value="<?php echo $g['id_user']; ?>"><?php echo $g['nama_lengkap']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 25px;">
                <label style="font-weight: bold; color: #555; display: block; margin-bottom: 15px;">Atur Jadwal Pelajaran:</label>
                
                <div style="display: flex; gap: 15px; align-items: flex-end;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Hari</label>
                        <select name="hari" class="form-control-modern">
                            <option value="">- Pilih -</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control-modern">
                    </div>

                    <div style="display: flex; align-items: center; padding-bottom: 10px; color: #999;">s/d</div>

                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control-modern">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="mapel.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #11998e, #38ef7d);">Simpan Mapel</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>