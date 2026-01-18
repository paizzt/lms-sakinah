<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// STATISTIK USER
$q_all   = mysqli_query($koneksi, "SELECT * FROM users");
$q_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE role='admin'");
$q_guru  = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru'");
$q_siswa = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa'");

$jml_all   = mysqli_num_rows($q_all);
$jml_admin = mysqli_num_rows($q_admin);
$jml_guru  = mysqli_num_rows($q_guru);
$jml_siswa = mysqli_num_rows($q_siswa);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, div, table, th, td, input, select, textarea, button {
            font-family: 'Poppins', sans-serif;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
<style>
    /* GRID STATISTIK */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #FF8C00; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    .stat-info h3 { margin: 0; font-size: 28px; color: #333; }
    .stat-info p { margin: 0; color: #888; font-size: 13px; font-weight: bold; }
    .stat-icon { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    @media (max-width: 992px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

    /* MODAL STYLES */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 600px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .close-btn:hover { opacity: 1; transform: rotate(90deg); }

    .modal-body { padding: 30px; background: #fdfdfd; max-height: 70vh; overflow-y: auto; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; box-sizing: border-box; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1); }
    
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; margin-top: 10px; }
    .btn-submit-modal:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3); }

    /* INPUT FILE MODERN */
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; transition: 0.3s; cursor: pointer; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    .file-upload-box span { font-size: 13px; color: #777; font-weight: 600; }
    input[type="file"] { display: none; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-users-cog"></i> Manajemen User</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola data Administrator, Guru, dan Siswa.</p>
            </div>
            <div>
                <button onclick="bukaModal()" class="btn-tambah" style="background: white; color: #E65100; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-user-plus"></i> Tambah User Baru
                </button>
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
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h4 style="margin: 0; color: #555;">Daftar Pengguna Aktif</h4>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="filterRole" onchange="searchTable()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; cursor: pointer; background: white; color: #555;">
                    <option value="">Semua Role</option>
                    <option value="ADMIN">Admin</option>
                    <option value="GURU">Guru</option>
                    <option value="SISWA">Siswa</option>
                </select>

                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="searchUser" onkeyup="searchTable()" placeholder="Cari nama user..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; width: 200px;">
                </div>
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
                        $foto = ($d['foto_profil'] && $d['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/avatar-default.svg";
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="<?php echo $foto; ?>" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <span style="font-weight: 600; color: #333;"><?php echo $d['nama_lengkap']; ?></span>
                                    <br><small style="color: #999; font-size: 11px;"><?php echo $d['email']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; color: #555;"><?php echo $d['username']; ?></td>
                        <td style="padding: 15px;">
                            <?php 
                            if($d['role'] == "admin") echo "<span style='background: #fdedec; color: #c0392b; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>ADMIN</span>";
                            else if($d['role'] == "guru") echo "<span style='background: #eaf2f8; color: #2980b9; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>GURU</span>";
                            else echo "<span style='background: #eafaf1; color: #27ae60; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>SISWA</span>";
                            ?>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <button onclick="bukaModalEdit(
                                '<?php echo $d['id_user']; ?>',
                                '<?php echo addslashes($d['nama_lengkap']); ?>',
                                '<?php echo $d['username']; ?>',
                                '<?php echo $d['email']; ?>',
                                '<?php echo $d['role']; ?>'
                            )" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border: none; border-radius: 6px; margin-right: 5px; cursor: pointer;">
                                <i class="fas fa-user-edit"></i>
                            </button>

                            <button onclick="konfirmasiHapus('<?php echo $d['id_user']; ?>')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalUser" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Tambah User Baru</h3>
            <span class="close-btn" onclick="tutupModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form action="users_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control-modal" required autocomplete="off"></div>
                <div class="form-group"><label>Email (Opsional)</label><input type="email" name="email" class="form-control-modal" autocomplete="off"></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control-modal" required autocomplete="off"></div>
                    <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control-modal" required></div>
                </div>
                <div class="form-group"><label>Level Akses</label>
                    <select name="role" class="form-control-modal" required>
                        <option value="">-- Pilih --</option>
                        <option value="admin">Administrator</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>
                <div class="form-group"><label>Foto Profil</label><input type="file" name="foto" id="fileInput" accept="image/*" onchange="updateFileName()"><label for="fileInput" class="file-upload-box"><i class="fas fa-cloud-upload-alt"></i><span id="fileName">Klik untuk pilih foto...</span></label></div>
                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN USER</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEditUser" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Data User</h3>
            <span class="close-btn" onclick="tutupModalEdit()">&times;</span>
        </div>
        <div class="modal-body">
            <form action="users_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" id="edit_nama" class="form-control-modal" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" id="edit_email" class="form-control-modal"></div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Username</label><input type="text" name="username" id="edit_username" class="form-control-modal" required></div>
                    <div class="form-group"><label>Password (Baru)</label><input type="password" name="password" class="form-control-modal" placeholder="Kosongkan jika tidak ubah"></div>
                </div>

                <div class="form-group"><label>Level Akses</label>
                    <select name="role" id="edit_role" class="form-control-modal" required>
                        <option value="admin">Administrator</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>

                <div class="form-group"><label>Ganti Foto (Opsional)</label><input type="file" name="foto" id="fileInputEdit" accept="image/*" onchange="updateFileNameEdit()"><label for="fileInputEdit" class="file-upload-box"><i class="fas fa-camera"></i><span id="fileNameEdit">Upload baru untuk ganti...</span></label></div>

                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<script>
    // --- MODAL TAMBAH ---
    function bukaModal() { document.getElementById('modalUser').style.display = "flex"; }
    function tutupModal() { document.getElementById('modalUser').style.display = "none"; }
    function updateFileName() { document.getElementById('fileName').innerText = document.getElementById('fileInput').files[0].name; }

    // --- MODAL EDIT (LOGIKA BARU) ---
    function bukaModalEdit(id, nama, username, email, role) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('modalEditUser').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEditUser').style.display = "none"; }
    function updateFileNameEdit() { document.getElementById('fileNameEdit').innerText = document.getElementById('fileInputEdit').files[0].name; }

    window.onclick = function(event) {
        if (event.target == document.getElementById('modalUser')) tutupModal();
        if (event.target == document.getElementById('modalEditUser')) tutupModalEdit();
    }

    // --- SEARCH TABLE ---
    function searchTable() {
        var input = document.getElementById("searchUser").value.toUpperCase();
        var roleFilter = document.getElementById("filterRole").value.toUpperCase();
        var table = document.getElementById("userTable");
        var tr = table.getElementsByTagName("tr");

        for (var i = 0; i < tr.length; i++) {
            var tdName = tr[i].getElementsByTagName("td")[1];
            var tdRole = tr[i].getElementsByTagName("td")[3];
            if (tdName && tdRole) {
                var txtName = tdName.textContent || tdName.innerText;
                var txtRole = tdRole.textContent || tdRole.innerText;
                if (txtName.toUpperCase().indexOf(input) > -1 && (roleFilter === "" || txtRole.toUpperCase().indexOf(roleFilter) > -1)) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    // --- KONFIRMASI HAPUS (SWEETALERT) ---
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Yakin hapus user ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#ddd',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'users_hapus.php?id=' + id;
            }
        })
    }

    // --- NOTIFIKASI SUKSES/GAGAL ---
    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FF8C00'
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>