<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 800px;">
        
        <div class="form-header">
            <h3><i class="fas fa-newspaper" style="color: #ff5e62; background: #ffebee;"></i> Tulis Berita Baru</h3>
        </div>

        <form action="pengumuman_aksi.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Judul Berita</label>
                <input type="text" name="judul" class="form-control-modern" placeholder="Contoh: Juara 1 Lomba Robotic Nasional" required>
            </div>

            <div class="form-group">
                <label>Target Pembaca</label>
                <select name="tujuan" class="form-control-modern">
                    <option value="semua">Semua (Publik)</option>
                    <option value="siswa">Hanya Siswa</option>
                    <option value="guru">Hanya Guru</option>
                </select>
            </div>

            <div class="form-group">
                <label>Isi Berita</label>
                <textarea name="isi" class="form-control-modern" rows="8" placeholder="Tuliskan detail informasi di sini..." required></textarea>
            </div>

            <div class="upload-area-wrapper">
                <span class="upload-title"><i class="fas fa-image"></i> Gambar Sampul / Banner</span>
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="file" name="gambar" class="form-control-modern" style="background: white;" accept="image/*">
                    <span class="text-hint">Format JPG/PNG. Disarankan rasio landscape (16:9).</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="pengumuman.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #ff9966, #ff5e62);">Terbitkan</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>