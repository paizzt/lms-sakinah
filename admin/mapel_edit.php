<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM mapel WHERE id_mapel='$id'");
$d = mysqli_fetch_array($query);
?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 800px;">
        
        <div class="form-header">
            <h3><i class="fas fa-edit" style="color: #f39c12; background: #fff8e1;"></i> Edit Mata Pelajaran</h3>
        </div>

        <form action="mapel_update.php" method="POST">
            <input type="hidden" name="id_mapel" value="<?php echo $d['id_mapel']; ?>">
            
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Kode Mapel</label>
                    <input type="text" name="kode_mapel" class="form-control-modern" value="<?php echo $d['kode_mapel']; ?>" required>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" class="form-control-modern" value="<?php echo $d['nama_mapel']; ?>" required>
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
                            // Cek jika ID kelas sama dengan data di database, tambahkan atribut 'selected'
                            $selected = ($k['id_kelas'] == $d['kelas_id']) ? "selected" : "";
                        ?>
                        <option value="<?php echo $k['id_kelas']; ?>" <?php echo $selected; ?>><?php echo $k['nama_kelas']; ?></option>
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
                            $selected = ($g['id_user'] == $d['guru_id']) ? "selected" : "";
                        ?>
                        <option value="<?php echo $g['id_user']; ?>" <?php echo $selected; ?>><?php echo $g['nama_lengkap']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 25px;">
                <label style="font-weight: bold; color: #555; display: block; margin-bottom: 15px;">Update Jadwal Pelajaran:</label>
                
                <div style="display: flex; gap: 15px; align-items: flex-end;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Hari</label>
                        <select name="hari" class="form-control-modern">
                            <option value="">- Pilih -</option>
                            <?php 
                            $hari = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
                            foreach($hari as $h){
                                $selected = ($h == $d['hari']) ? "selected" : "";
                                echo "<option value='$h' $selected>$h</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control-modern" value="<?php echo $d['jam_mulai']; ?>">
                    </div>

                    <div style="display: flex; align-items: center; padding-bottom: 10px; color: #999;">s/d</div>

                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control-modern" value="<?php echo $d['jam_selesai']; ?>">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="mapel.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #f39c12, #f1c40f);">Update Perubahan</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>