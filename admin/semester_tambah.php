<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 500px;">
        
        <div class="form-header">
            <h3><i class="fas fa-calendar-plus" style="color: #8e44ad; background: #f3e5f5;"></i> Tambah Tahun Ajaran</h3>
        </div>

        <form action="semester_aksi.php" method="POST">
            
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control-modern" placeholder="Contoh: 2025/2026" required>
                <span class="text-hint" style="font-size: 12px; color: #888;">Format: TAHUN/TAHUN (Misal: 2025/2026)</span>
            </div>

            <div class="form-group">
                <label>Semester</label>
                <select name="semester" class="form-control-modern" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div class="alert-info" style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; border: 1px solid #c8e6c9;">
                <i class="fas fa-info-circle"></i> <b>Catatan:</b> Semester baru akan disimpan dengan status <b>Non-Aktif</b>. Anda dapat mengaktifkannya nanti di halaman utama.
            </div>

            <div class="form-actions">
                <a href="semester.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #8e44ad, #c0392b);">Simpan Data</button>
            </div>

        </form>

    </div>
</div>

<?php include 'footer.php'; ?>