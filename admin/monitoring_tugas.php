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
// --- 1. LOGIKA FILTER ---
$f_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : '';
$f_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$f_sem   = isset($_GET['semester']) ? $_GET['semester'] : '';
$f_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Bangun Query Filter
$where = " WHERE 1=1 ";
if($f_mapel != "") $where .= " AND tugas.mapel_id='$f_mapel'";
if($f_kelas != "") $where .= " AND mapel.kelas_id='$f_kelas'";
if($f_sem != "")   $where .= " AND kelas.semester='$f_sem'";
if($f_tahun != "") $where .= " AND kelas.tahun_ajaran='$f_tahun'";

// STATISTIK GLOBAL
$jml_tugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_tugas FROM tugas"));

// DATA UNTUK DROPDOWN FILTER
$q_opt_mapel = mysqli_query($koneksi, "SELECT * FROM mapel ORDER BY nama_mapel ASC");
$q_opt_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$q_opt_tahun = mysqli_query($koneksi, "SELECT DISTINCT tahun_ajaran FROM kelas WHERE tahun_ajaran != '' ORDER BY tahun_ajaran DESC");

// QUERY UTAMA (DENGAN FILTER)
$query_utama = "SELECT tugas.*, mapel.nama_mapel, kelas.nama_kelas, kelas.semester, kelas.tahun_ajaran
                FROM tugas 
                JOIN mapel ON tugas.mapel_id = mapel.id_mapel 
                JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                $where
                ORDER BY tugas.tgl_buat DESC";
$q_tugas = mysqli_query($koneksi, $query_utama);
?>

<style>
    /* Stats & Card */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #FF8C00; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    
    /* DataTables Custom */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important; }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 600px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .modal-body { padding: 30px; background: #fdfdfd; overflow-y: auto; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; box-sizing: border-box; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1); }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; margin-top: 10px; }
    
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; transition: 0.3s; cursor: pointer; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    .file-upload-box span { font-size: 13px; color: #777; font-weight: 600; }
    input[type="file"] { display: none; }

    /* Filter Select Style */
    .filter-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 12px; cursor: pointer; background: white; color: #555; min-width: 130px; transition: 0.3s; }
    .filter-select:hover { border-color: #FF8C00; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-tasks"></i> Manajemen Tugas</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Buat dan kelola tugas siswa.</p>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_tugas; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Tugas</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #f9f9f9; border-bottom: 1px solid #eee;">
            <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <span style="font-weight: bold; font-size: 12px; color: #777;"><i class="fas fa-filter"></i> Filter:</span>
                
                <select name="mapel" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Mapel --</option>
                    <?php mysqli_data_seek($q_opt_mapel, 0); while($m = mysqli_fetch_array($q_opt_mapel)){ $sel = ($f_mapel == $m['id_mapel']) ? 'selected' : ''; echo "<option value='".$m['id_mapel']."' $sel>".$m['nama_mapel']."</option>"; } ?>
                </select>

                <select name="kelas" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php mysqli_data_seek($q_opt_kelas, 0); while($k = mysqli_fetch_array($q_opt_kelas)){ $sel = ($f_kelas == $k['id_kelas']) ? 'selected' : ''; echo "<option value='".$k['id_kelas']."' $sel>".$k['nama_kelas']."</option>"; } ?>
                </select>

                <select name="semester" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Semester --</option>
                    <option value="Ganjil" <?php if($f_sem=='Ganjil') echo 'selected'; ?>>Ganjil</option>
                    <option value="Genap" <?php if($f_sem=='Genap') echo 'selected'; ?>>Genap</option>
                </select>

                <select name="tahun" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Tahun --</option>
                    <?php while($t = mysqli_fetch_array($q_opt_tahun)){ $sel = ($f_tahun == $t['tahun_ajaran']) ? 'selected' : ''; echo "<option value='".$t['tahun_ajaran']."' $sel>".$t['tahun_ajaran']."</option>"; } ?>
                </select>

                <?php if($f_mapel!="" || $f_kelas!="" || $f_sem!="" || $f_tahun!="") { ?>
                    <a href="monitoring_tugas.php" style="color: #c62828; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 10px;"><i class="fas fa-times-circle"></i> Reset</a>
                <?php } ?>
            </form>
        </div>

        <div style="padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari judul..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                </div>
            </div>
            <button onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Buat Tugas Baru
            </button>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="tugasTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Judul Tugas</th>
                        <th style="padding: 15px; text-align: center;">File / Link</th>
                        <th style="padding: 15px; text-align: center;">Tanggal</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($q_tugas) > 0){
                        while($d = mysqli_fetch_array($q_tugas)){
                            $deadline = date('d-m-Y H:i', strtotime($d['tgl_kumpul']));
                            $tipe = isset($d['tipe']) ? $d['tipe'] : 'file';
                            $url  = isset($d['file_url']) ? $d['file_url'] : '';

                            // Status Logic
                            $sekarang = date('Y-m-d H:i:s');
                            if($d['tgl_kumpul'] > $sekarang){
                                $status = "<span style='background:#e8f5e9; color:#2e7d32; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:11px;'>AKTIF</span>";
                            } else {
                                $status = "<span style='background:#ffebee; color:#c62828; padding:5px 10px; border-radius:15px; font-weight:bold; font-size:11px;'>DITUTUP</span>";
                            }
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['judul_tugas']; ?>
                            <div style="font-size: 11px; color: #999; margin-top: 3px;">
                                <i class="fas fa-book"></i> <?php echo $d['nama_mapel']; ?> | <i class="fas fa-users"></i> <?php echo $d['nama_kelas']; ?>
                            </div>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <?php if(!empty($url)) { ?>
                                <?php if($tipe == 'file') { ?>
                                    <a href="../uploads/tugas_guru/<?php echo $url; ?>" target="_blank" style="text-decoration: none; background: #e3f2fd; color: #1565c0; padding: 5px 10px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                                        <i class="fas fa-download"></i> File
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo $url; ?>" target="_blank" style="text-decoration: none; background: #fff3e0; color: #e65100; padding: 5px 10px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                                        <i class="fas fa-link"></i> Link
                                    </a>
                                <?php } ?>
                            <?php } else { echo "<span style='color:#ccc; font-size:11px;'>-</span>"; } ?>
                        </td>

                        <td style="padding: 15px; text-align: center; color: #555; font-size: 12px;">
                            <?php echo $deadline; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <?php echo $status; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <button onclick="bukaModalLihat('<?php echo addslashes($d['judul_tugas']); ?>','<?php echo addslashes($d['nama_mapel']); ?>','<?php echo addslashes($d['nama_kelas']); ?>','<?php echo $deadline; ?>','<?php echo addslashes($d['deskripsi']); ?>')" class="btn-action" style="background: #e3f2fd; color: #1565c0; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;"><i class="fas fa-eye"></i></button>
                            <button onclick="bukaModalEdit('<?php echo $d['id_tugas']; ?>','<?php echo addslashes($d['judul_tugas']); ?>','<?php echo $d['mapel_id']; ?>','<?php echo date('Y-m-d\TH:i', strtotime($d['tgl_kumpul'])); ?>','<?php echo $tipe; ?>','<?php echo $url; ?>','<?php echo addslashes($d['deskripsi']); ?>')" class="btn-action edit" style="background: #FFF3E0; color: #E65100; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;"><i class="fas fa-edit"></i></button>
                            <button onclick="konfirmasiHapus('<?php echo $d['id_tugas']; ?>')" class="btn-action delete" style="background: #ffebee; color: #c62828; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; padding:30px; color:#999;'>Belum ada tugas sesuai filter.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-plus-circle"></i> Buat Tugas Baru</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="tugas_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Judul Tugas</label><input type="text" name="judul" class="form-control-modal" required></div>
                <div class="form-group"><label>Mata Pelajaran (Kelas Otomatis)</label>
                    <select name="mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php mysqli_data_seek($q_opt_mapel, 0); while($m=mysqli_fetch_array($q_opt_mapel)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']."</option>"; } ?>
                    </select>
                </div>
                <div class="form-group"><label>Batas Waktu</label><input type="datetime-local" name="deadline" class="form-control-modal" required></div>
                <div class="form-group"><label>Tipe Lampiran</label>
                    <select id="tipeUpload" name="tipe" class="form-control-modal" onchange="toggleUploadInput('tambah')">
                        <option value="file">Upload File</option><option value="link">Tautan / Link</option>
                    </select>
                </div>
                <div id="boxFile_tambah" class="form-group">
                    <label>File (Opsional)</label><input type="file" name="file_tugas" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')"><label for="fileInp" class="file-upload-box"><i class="fas fa-cloud-upload-alt"></i><span id="fileNameDisp">Klik cari file...</span></label>
                </div>
                <div id="boxLink_tambah" class="form-group" style="display:none;"><label>Link Tugas</label><input type="text" name="link_tugas" class="form-control-modal" placeholder="https://..."></div>
                <div class="form-group"><label>Instruksi</label><textarea name="deskripsi" class="form-control-modal" rows="3"></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN TUGAS</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Tugas</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="tugas_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_tugas" id="edit_id">
                <div class="form-group"><label>Judul Tugas</label><input type="text" name="judul" id="edit_judul" class="form-control-modal" required></div>
                <div class="form-group"><label>Mata Pelajaran</label>
                    <select name="mapel" id="edit_mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php mysqli_data_seek($q_opt_mapel, 0); while($m=mysqli_fetch_array($q_opt_mapel)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']."</option>"; } ?>
                    </select>
                </div>
                <div class="form-group"><label>Batas Waktu</label><input type="datetime-local" name="deadline" id="edit_deadline" class="form-control-modal" required></div>
                <div class="form-group"><label>Tipe Lampiran</label>
                    <select id="edit_tipe" name="tipe" class="form-control-modal" onchange="toggleUploadInput('edit')">
                        <option value="file">Upload File</option><option value="link">Tautan / Link</option>
                    </select>
                </div>
                <div id="boxFile_edit" class="form-group">
                    <label>Ganti File</label><input type="file" name="file_tugas" id="fileInpEdit" onchange="updateFileName('fileInpEdit','fileNameDispEdit')"><label for="fileInpEdit" class="file-upload-box"><i class="fas fa-sync"></i><span id="fileNameDispEdit">Upload baru...</span></label>
                </div>
                <div id="boxLink_edit" class="form-group" style="display:none;"><label>Link Tugas</label><input type="text" name="link_tugas" id="edit_link" class="form-control-modal"></div>
                <div class="form-group"><label>Instruksi</label><textarea name="deskripsi" id="edit_deskripsi" class="form-control-modal" rows="3"></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<div id="modalLihat" class="modal-overlay">
    <div class="modal-box" style="max-width: 500px;">
        <div class="modal-header"><h3><i class="fas fa-info-circle"></i> Detail Tugas</h3><span class="close-btn" onclick="tutupModalLihat()">&times;</span></div>
        <div class="modal-body">
            <h3 id="view_judul" style="margin-top: 0; color: #E65100;">-</h3>
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <span id="view_mapel" style="background: #FFF3E0; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; color: #E65100;">-</span>
                <span id="view_kelas" style="background: #e3f2fd; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; color: #1565c0;">-</span>
            </div>
            <div style="background: #ffebee; padding: 10px; border-radius: 5px; color: #c62828; font-weight: bold; font-size: 12px; margin-bottom: 15px;">
                <i class="far fa-clock"></i> Deadline: <span id="view_deadline">-</span>
            </div>
            <div style="background: #fafafa; padding: 15px; border-radius: 10px; border: 1px dashed #ddd;">
                <label style="font-weight: bold; font-size: 12px; color: #999;">INSTRUKSI:</label>
                <p id="view_deskripsi" style="margin: 5px 0 0 0; color: #333; line-height: 1.6;">-</p>
            </div>
            <button onclick="tutupModalLihat()" class="btn-submit-modal" style="margin-top: 20px; background: #555;">Tutup</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#tugasTable').DataTable({ "dom": 'rtip', "pageLength": 10, "order": [] });
        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });

    function toggleUploadInput(mode) {
        var tipe = (mode == 'tambah') ? document.getElementById('tipeUpload').value : document.getElementById('edit_tipe').value;
        if(tipe == 'file'){ document.getElementById('boxFile_' + mode).style.display = 'block'; document.getElementById('boxLink_' + mode).style.display = 'none'; } 
        else { document.getElementById('boxFile_' + mode).style.display = 'none'; document.getElementById('boxLink_' + mode).style.display = 'block'; }
    }
    function updateFileName(id, disp) { document.getElementById(disp).innerText = document.getElementById(id).files[0].name; }

    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }
    
    function bukaModalLihat(judul, mapel, kelas, deadline, deskripsi) {
        document.getElementById('view_judul').innerText = judul;
        document.getElementById('view_mapel').innerText = mapel;
        document.getElementById('view_kelas').innerText = kelas;
        document.getElementById('view_deadline').innerText = deadline;
        document.getElementById('view_deskripsi').innerText = deskripsi;
        document.getElementById('modalLihat').style.display = "flex";
    }
    function tutupModalLihat() { document.getElementById('modalLihat').style.display = "none"; }

    function bukaModalEdit(id, judul, mapel, deadline, tipe, url, deskripsi) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_mapel').value = mapel;
        document.getElementById('edit_deadline').value = deadline;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_tipe').value = tipe;
        if(tipe == 'link') document.getElementById('edit_link').value = url;
        toggleUploadInput('edit');
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }

    window.onclick = function(e) { if(e.target.className === 'modal-overlay') e.target.style.display = "none"; }

    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus tugas ini?', text: "Data tidak bisa kembali!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#c62828', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'tugas_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', text: '<?php echo $_SESSION['notif_pesan']; ?>', icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', confirmButtonColor: '#FF8C00' });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>