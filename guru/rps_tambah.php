<?php include 'header.php'; 
$id_mapel = $_GET['id_mapel'];
// Ambil nama mapel utk judul
$qm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel WHERE id_mapel='$id_mapel'"));
?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 600px;">
        
        <div class="form-header">
            <h3><i class="fas fa-upload" style="color: #4a00e0; background: #e0e7ff;"></i> Upload RPS</h3>
        </div>

        <form action="rps_aksi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="mapel_id" value="<?php echo $id_mapel; ?>">

            <div class="form-group">
                <label>Mata Pelajaran</label>
                <input type="text" class="form-control-modern" value="<?php echo $qm['nama_mapel']; ?>" readonly style="background: #f0f0f0;">
            </div>

            <div class="form-group">
                <label>Deskripsi (Opsional)</label>
                <textarea name="deskripsi" class="form-control-modern" rows="3" placeholder="Contoh: Silabus Semester Ganjil 2025"></textarea>
            </div>

            <div class="upload-area-wrapper">
                <span class="upload-title">File Dokumen RPS</span>
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="file" name="file_rps" class="form-control-modern" accept=".pdf, .doc, .docx" required>
                    <span class="text-hint">Format PDF atau Word. Maks 5MB.</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="rps.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #8e2de2, #4a00e0);">Simpan RPS</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>