<?php include 'header.php'; ?>

<?php
// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data pengumuman berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE id_pengumuman='$id'");
$d = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan, kembalikan ke index
if(mysqli_num_rows($query) < 1){
    echo "<script>window.location='pengumuman.php';</script>";
}
?>

<div class="form-center-wrapper">
    <div class="modern-form-card">
        
        <div class="form-header">
            <h3><i class="fas fa-edit" style="color: #4facfe; background: #e3f2fd; padding: 10px; border-radius: 12px;"></i> Edit Pengumuman</h3>
        </div>

        <form action="pengumuman_update.php" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?php echo $d['id_pengumuman']; ?>">

            <div class="form-group">
                <label>Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control-modern" value="<?php echo $d['judul']; ?>" required placeholder="Contoh: Libur Hari Raya...">
            </div>

            <div class="form-group">
                <label>Target Audience (Siapa yang bisa melihat?)</label>
                <select name="tujuan" class="form-control-modern" required>
                    <option value="semua" <?php if($d['tujuan'] == 'semua') echo 'selected'; ?>>Semua Pengguna (Guru & Siswa)</option>
                    <option value="guru" <?php if($d['tujuan'] == 'guru') echo 'selected'; ?>>Hanya Guru</option>
                    <option value="siswa" <?php if($d['tujuan'] == 'siswa') echo 'selected'; ?>>Hanya Siswa</option>
                </select>
            </div>

            <div class="form-group">
                <label>Isi Pengumuman</label>
                <textarea name="isi" class="form-control-modern" rows="6" required placeholder="Tuliskan isi pengumuman secara detail..."><?php echo $d['isi']; ?></textarea>
            </div>

            <div class="form-group">
                <label>File Lampiran (Gambar/PDF)</label>
                
                <?php if(!empty($d['file_lampiran'])){ ?>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-file-alt" style="color: #667eea;"></i>
                        <span style="font-size: 14px; color: #555;">File saat ini: <b><?php echo $d['file_lampiran']; ?></b></span>
                    </div>
                <?php } ?>

                <input type="file" name="foto" class="form-control-modern" style="padding: 10px;">
                <span class="text-hint">Biarkan kosong jika tidak ingin mengubah file lampiran.</span>
            </div>

            <div class="form-actions">
                <a href="pengumuman.php" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

<?php include 'footer.php'; ?>