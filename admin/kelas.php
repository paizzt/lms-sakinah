<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<?php
// --- LOGIKA DATA ---
$jml_kelas  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas"));
$jml_wali   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas_id IS NOT NULL"));
$jml_kosong = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas_id IS NULL"));

$q_guru = mysqli_query($koneksi, "SELECT id_user, nama_lengkap FROM users WHERE role='guru' ORDER BY nama_lengkap ASC");

// Ambil Data Referensi Tahun/Semester dari Master Semester (untuk Dropdown)
$q_ref_sem = mysqli_query($koneksi, "SELECT * FROM semester ORDER BY id_semester DESC");
?>

<style>
    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #FF8C00; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    .stat-info h3 { margin: 0; font-size: 28px; color: #333; }
    .stat-info p { margin: 0; color: #888; font-size: 13px; font-weight: bold; }
    .stat-icon { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* Custom DataTables Pagination Orange */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important;
    }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .modal-body { padding: 30px; background: #fdfdfd; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; box-sizing: border-box; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1); }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; margin-top: 10px; }
    .btn-submit-modal:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3); }

    @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-chalkboard"></i> Manajemen Kelas</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola data kelas dan wali kelas.</p>
            </div>
            <div>
                <button onclick="bukaModalTambah()" class="btn-tambah" style="background: white; color: #E65100; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-plus-circle"></i> Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #FF8C00;"><div class="stat-info"><h3><?php echo $jml_kelas; ?></h3><p>TOTAL KELAS</p></div><div class="stat-icon" style="background: #FFF3E0; color: #FF8C00;"><i class="fas fa-school"></i></div></div>
        <div class="stat-card" style="border-left-color: #27ae60;"><div class="stat-info"><h3><?php echo $jml_wali; ?></h3><p>WALI KELAS TERISI</p></div><div class="stat-icon" style="background: #eafaf1; color: #27ae60;"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-card" style="border-left-color: #c0392b;"><div class="stat-info"><h3><?php echo $jml_kosong; ?></h3><p>KELAS KOSONG</p></div><div class="stat-icon" style="background: #fdedec; color: #c0392b;"><i class="fas fa-exclamation-circle"></i></div></div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h4 style="margin: 0; color: #555;">Daftar Kelas Aktif</h4>
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; cursor: pointer; background: white; color: #555;">
                    <option value="10">Tampil 10</option><option value="25">Tampil 25</option><option value="50">Tampil 50</option>
                </select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari kelas..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; width: 200px;">
                </div>
            </div>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="kelasTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 50px;">No</th>
                        <th style="padding: 15px; text-align: left;">Kelas</th>
                        <th style="padding: 15px; text-align: left;">Wali Kelas</th>
                        <th style="padding: 15px; text-align: center;">Tahun</th>
                        <th style="padding: 15px; text-align: center;">Semester</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT kelas.*, users.nama_lengkap 
                                                     FROM kelas 
                                                     LEFT JOIN users ON kelas.wali_kelas_id = users.id_user 
                                                     ORDER BY kelas.nama_kelas ASC");
                    while($d = mysqli_fetch_array($query)){
                        $th = !empty($d['tahun_ajaran']) ? $d['tahun_ajaran'] : '-';
                        $sm = !empty($d['semester']) ? $d['semester'] : '-';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: 600; color: #333;"><?php echo $d['nama_kelas']; ?></td>
                        <td style="padding: 15px;">
                            <?php 
                            if($d['nama_lengkap']) echo "<div style='display:flex; align-items:center; gap:8px;'><i class='fas fa-user-tie' style='color:#2980b9;'></i> <span>".$d['nama_lengkap']."</span></div>";
                            else echo "<span style='background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>Belum Diatur</span>";
                            ?>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #555;"><?php echo $th; ?></td>
                        <td style="padding: 15px; text-align: center;"><span style="background: #eafaf1; color: #27ae60; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;"><?php echo $sm; ?></span></td>
                        <td style="padding: 15px; text-align: center;">
                            <button onclick="bukaModalEdit(
                                '<?php echo $d['id_kelas']; ?>',
                                '<?php echo $d['nama_kelas']; ?>',
                                '<?php echo $d['wali_kelas_id']; ?>',
                                '<?php echo $th; ?>',
                                '<?php echo $sm; ?>'
                            )" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border: none; border-radius: 6px; margin-right: 5px; cursor: pointer;"><i class="fas fa-edit"></i></button>
                            <button onclick="konfirmasiHapus('<?php echo $d['id_kelas']; ?>')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-plus-square"></i> Tambah Kelas Baru</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="kelas_aksi.php" method="POST">
                <div class="form-group"><label>Nama Kelas</label><input type="text" name="nama_kelas" class="form-control-modal" placeholder="Contoh: X IPA 1" required></div>
                <div class="form-group"><label>Wali Kelas</label>
                    <select name="wali_kelas" class="form-control-modal"><option value="">-- Pilih Wali Kelas --</option><?php mysqli_data_seek($q_guru, 0); while($g=mysqli_fetch_array($q_guru)){ echo "<option value='".$g['id_user']."'>".$g['nama_lengkap']."</option>"; } ?></select>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Tahun Ajaran</label>
                        <select name="tahun" class="form-control-modal" required>
                            <option value="">-- Pilih --</option>
                            <?php 
                            // Mengambil referensi dari tabel Semester agar konsisten
                            // Menggunakan DISTINCT agar tahun yang sama tidak muncul dobel
                            $q_th = mysqli_query($koneksi, "SELECT DISTINCT tahun_ajaran FROM semester ORDER BY tahun_ajaran DESC");
                            while($t=mysqli_fetch_array($q_th)){
                                echo "<option value='".$t['tahun_ajaran']."'>".$t['tahun_ajaran']."</option>";
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Semester</label>
                        <select name="semester" class="form-control-modal" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN KELAS</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Data Kelas</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="kelas_update.php" method="POST">
                <input type="hidden" name="id_kelas" id="edit_id">
                <div class="form-group"><label>Nama Kelas</label><input type="text" name="nama_kelas" id="edit_nama" class="form-control-modal" required></div>
                <div class="form-group"><label>Wali Kelas</label>
                    <select name="wali_kelas" id="edit_wali" class="form-control-modal"><option value="">-- Pilih Wali Kelas --</option><?php mysqli_data_seek($q_guru, 0); while($g=mysqli_fetch_array($q_guru)){ echo "<option value='".$g['id_user']."'>".$g['nama_lengkap']."</option>"; } ?></select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Tahun Ajaran</label>
                        <select name="tahun" id="edit_tahun" class="form-control-modal" required>
                            <option value="">-- Pilih --</option>
                            <?php 
                            $q_th2 = mysqli_query($koneksi, "SELECT DISTINCT tahun_ajaran FROM semester ORDER BY tahun_ajaran DESC");
                            while($t2=mysqli_fetch_array($q_th2)){
                                echo "<option value='".$t2['tahun_ajaran']."'>".$t2['tahun_ajaran']."</option>";
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Semester</label>
                        <select name="semester" id="edit_semester" class="form-control-modal" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#kelasTable').DataTable({ "dom": 'rtip', "pageLength": 10 });
        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });

    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }
    
    function bukaModalEdit(id, nama, wali, tahun, semester) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_wali').value = wali;
        document.getElementById('edit_tahun').value = tahun;
        document.getElementById('edit_semester').value = semester;
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }
    
    window.onclick = function(e) { if(e.target == document.getElementById('modalTambah')) tutupModalTambah(); if(e.target == document.getElementById('modalEdit')) tutupModalEdit(); }

    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus kelas ini?', text: "Data tidak bisa kembali!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#c62828', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'kelas_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', text: '<?php echo $_SESSION['notif_pesan']; ?>', icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', confirmButtonColor: '#FF8C00' });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>