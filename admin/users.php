<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #FF8C00); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(0, 114, 255, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-users-cog"></i> Manajemen User</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola akun Admin, Guru, dan Siswa.</p>
    </div>
    
    <a href="users_tambah.php" class="btn-add" style="background: white; color: #FF8C00; padding: 10px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
    <div style="position: relative;">
        <input type="text" id="searchUser" placeholder="Cari user..." style="padding: 10px 15px 10px 40px; border-radius: 50px; border: 1px solid #ddd; width: 250px; outline: none;">
        <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
    </div>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Foto</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role (Hak Akses)</th>
                <th width="15%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php 
            $no = 1;
            // Urutkan dari yang terbaru (ID terbesar)
            $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC, nama_lengkap ASC");
            
            while($d = mysqli_fetch_array($query)){
                // Tentukan Warna Badge Role
                $badge_class = "";
                if($d['role'] == "admin") $badge_class = "role-admin";
                else if($d['role'] == "guru") $badge_class = "role-guru";
                else $badge_class = "role-siswa";

                // Tentukan Foto
                $foto = $d['foto_profil'] ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/default.jpg";
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <img src="<?php echo $foto; ?>" class="user-avatar-small" alt="Foto">
                </td>
                <td>
                    <div style="font-weight: 600; color: #333;"><?php echo $d['nama_lengkap']; ?></div>
                    <small style="color: #999;"><?php echo $d['email'] ? $d['email'] : '-'; ?></small>
                </td>
                <td><?php echo $d['username']; ?></td>
                <td>
                    <span class="role-badge <?php echo $badge_class; ?>">
                        <?php echo strtoupper($d['role']); ?>
                    </span>
                </td>
                <td style="text-align: center;">
                    <a href="users_edit.php?id=<?php echo $d['id_user']; ?>" class="btn-action-small btn-edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    
                    <?php if($d['id_user'] != $_SESSION['id_user']) { ?>
                    <a href="users_hapus.php?id=<?php echo $d['id_user']; ?>" onclick="return confirm('Yakin ingin menghapus user ini? Data terkait (nilai/tugas) mungkin akan hilang.')" class="btn-action-small btn-delete" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchUser').addEventListener('keyup', function() {
    var searchValue = this.value.toLowerCase();
    var rows = document.querySelectorAll('#userTableBody tr');

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if(text.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php include 'footer.php'; ?>