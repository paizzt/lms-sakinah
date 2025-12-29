<?php include 'header.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card">
        
        <div class="form-header">
            <h3><i class="fas fa-edit" style="color: #d35400; background: #ffe0b2;"></i> Buat Tugas / Kuis Baru</h3>
        </div>

        <form action="tugas_aksi.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Mata Pelajaran & Kelas</label>
                <select name="mapel_id" class="form-control-modern" required>
                    <option value="">-- Pilih Target Kelas --</option>
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

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Judul Tugas</label>
                    <input type="text" name="judul" class="form-control-modern" placeholder="Contoh: Latihan Soal Bab 2" required>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Tipe Evaluasi</label>
                    <select name="tipe" class="form-control-modern">
                        <option value="tugas">Tugas (Upload File)</option>
                        <option value="kuis">Kuis / Ujian</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi / Instruksi Soal</label>
                <textarea name="deskripsi" class="form-control-modern" rows="4" placeholder="Tuliskan instruksi pengerjaan di sini..."></textarea>
            </div>

            <div class="form-group">
                <label style="color: #d35400;"><i class="fas fa-clock"></i> Batas Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" class="form-control-modern" required style="border-color: #ffe0b2;">
            </div>

            <div class="upload-area-wrapper">
                <span class="upload-title">File Soal / Pendukung (Opsional):</span>
                <div class="form-group" style="margin-bottom: 0;">
                    <label><i class="fas fa-paperclip"></i> Upload Dokumen</label>
                    <input type="file" name="file_tugas" class="form-control-modern" style="background: white;">
                    <span class="text-hint">Jika ada file soal (PDF/Doc) yang perlu diunduh siswa.</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="tugas.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #f83600, #f9d423); box-shadow: 0 5px 15px rgba(248, 54, 0, 0.3);">Terbitkan Tugas</button>
            </div>

        </form>
    </div>
</div>

<?php include 'footer.php'; ?>