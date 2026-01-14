<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- LOGIKA STATISTIK ---
$q_all   = mysqli_query($koneksi, "SELECT * FROM users");
$q_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE role='admin'");
$q_guru  = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru'");
$q_siswa = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa'");

$jml_all   = mysqli_num_rows($q_all);
$jml_admin = mysqli_num_rows($q_admin);
$jml_guru  = mysqli_num_rows($q_guru);
$jml_siswa = mysqli_num_rows($q_siswa);
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-left: 5px solid #FF8C00;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .stat-info h3 { margin: 0; font-size: 28px; color: #333; }
    .stat-info p { margin: 0; color: #888; font-size: 13px; font-weight: bold; }
    .stat-icon {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    @media (max-width: 992px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-users-cog"></i> Manajemen User</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola data Administrator, Guru, dan Siswa.</p>
            </div>
            <div>
                <a href="users_tambah.php" class="btn-tambah" style="background: white; color: #E65100; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-user-plus"></i> Tambah User Baru
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #FF8C00;">
            <div class="stat-info"><h3><?php echo $jml_all; ?></h3><p>TOTAL USER</p></div>
            <div class="stat-icon" style="background: #FFF3E0; color: #FF8C00;"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-card" style="border-left-color: #c0392b;">
            <div class="stat-info"><h3><?php echo $jml_admin; ?></h3><p>TOTAL ADMIN</p></div>
            <div class="stat-icon" style="background: #fdedec; color: #c0392b;"><i class="fas fa-user-shield"></i></div>
        </div>
        <div class="stat-card" style="border-left-color: #2980b9;">
            <div class="stat-info"><h3><?php echo $jml_guru; ?></h3><p>TOTAL GURU</p></div>
            <div class="stat-icon" style="background: #eaf2f8; color: #2980b9;"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>
        <div class="stat-card" style="border-left-color: #27ae60;">
            <div class="stat-info"><h3><?php echo $jml_siswa; ?></h3><p>TOTAL SISWA</p></div>
            <div class="stat-icon" style="background: #eafaf1; color: #27ae60;"><i class="fas fa-user-graduate"></i></div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #555;">Daftar Pengguna Aktif</h4>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                <input type="text" id="searchUser" onkeyup="searchTable()" placeholder="Cari nama user..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="userTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">No</th>
                        <th style="padding: 15px; text-align: left;">Nama Lengkap</th>
                        <th style="padding: 15px; text-align: left;">Username</th>
                        <th style="padding: 15px; text-align: left;">Role</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php 
                                    $foto = ($d['foto_profil'] && $d['foto_profil'] != 'default.jpg') 
                                            ? "../uploads/profil/".$d['foto_profil'] 
                                            : "../assets/img/avatar-default.svg";
                                ?>
                                <img src="<?php echo $foto; ?>" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <span style="font-weight: 600; color: #333;"><?php echo $d['nama_lengkap']; ?></span>
                                    <br>
                                    <small style="color: #999; font-size: 11px;"><?php echo $d['email']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; color: #555;"><?php echo $d['username']; ?></td>
                        <td style="padding: 15px;">
                            <?php 
                            if($d['role'] == "admin"){
                                echo "<span style='background: #fdedec; color: #c0392b; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>ADMIN</span>";
                            } else if($d['role'] == "guru"){
                                echo "<span style='background: #eaf2f8; color: #2980b9; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>GURU</span>";
                            } else {
                                echo "<span style='background: #eafaf1; color: #27ae60; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>SISWA</span>";
                            }
                            ?>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="users_edit.php?id=<?php echo $d['id_user']; ?>" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border-radius: 6px; margin-right: 5px; display: inline-block;">
                                <i class="fas fa-user-edit"></i>
                            </a>
                            <a href="users_hapus.php?id=<?php echo $d['id_user']; ?>" onclick="return confirm('Yakin ingin menghapus user ini?')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px; display: inline-block;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchUser");
    filter = input.value.toUpperCase();
    table = document.getElementById("userTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>

<?php include 'footer.php'; ?>