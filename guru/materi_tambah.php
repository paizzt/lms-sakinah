<?php include 'header.php'; ?>

<div class="form-center-wrapper">
    
    <div class="modern-form-card">
        
        <div class="form-header">
            <h3><i class="fas fa-cloud-upload-alt"></i> Upload Materi Baru</h3>
        </div>

        <form action="materi_aksi.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Mata Pelajaran & Kelas</label>
                <select name="mapel_id" class="form-control-modern" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php 
                    $id_guru = $_SESSION['id_user'];
                    $q_mapel = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas FROM mapel JOIN kelas ON mapel.kelas_id = kelas.id_kelas WHERE guru_id='$id_guru'");
                    while($mp = mysqli_fetch_array($q_mapel)){
                    ?>
                    <option value="<?php echo $mp['id_mapel']; ?>">
                        <?php echo $mp['nama_mapel'] . " - " . $mp['nama_kelas']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Judul Materi</label>
                <input type="text" name="judul" class="form-control-modern" placeholder="Contoh: Bab 1 - Pengantar Aljabar" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control-modern" rows="4" placeholder="Berikan keterangan singkat agar siswa paham isi materi ini..."></textarea>
            </div>

            <div class="upload-area-wrapper">
                <span class="upload-title">Pilih Jenis Materi:</span>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label><i class="fas fa-file-pdf"></i> Upload File (PDF/Word/PPT)</label>
                    <input type="file" name="file_materi" class="form-control-modern" style="background: white;">
                    <span class="text-hint">Maksimal ukuran file: 5MB.</span>
                </div>
                
                <div class="upload-divider">ATAU</div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label><i class="fab fa-youtube"></i> Link Video / Artikel</label>
                    <input type="url" name="link_materi" class="form-control-modern" placeholder="https://youtube.com/...">
                    <span class="text-hint">Masukkan link lengkap (diawali https://).</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="materi.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan & Upload</button>
            </div>

        </form>
    </div>

</div>

<?php include 'footer.php'; ?>