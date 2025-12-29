<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-center-wrapper">
    <div class="modern-form-card" style="max-width: 600px;">
        
        <div class="form-header">
            <h3><i class="fas fa-user-plus" style="color: #0072ff; background: #e3f2fd;"></i> Tambah User Baru</h3>
        </div>

        <form action="users_aksi.php" method="POST">
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control-modern" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control-modern" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control-modern" required>
            </div>
            
            <div class="form-group">
                <label>Email (Opsional)</label>
                <input type="email" name="email" class="form-control-modern">
            </div>

            <div class="form-group">
                <label>Role (Hak Akses)</label>
                <select name="role" class="form-control-modern" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Administrator</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="users.php" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(to right, #00c6ff, #0072ff);">Simpan User</button>
            </div>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>