<?php 
// Matikan error PHP agar tidak merusak format JSON DataTables
error_reporting(0);
ini_set('display_errors', 0);

include 'header.php'; 
include 'sidebar.php'; 
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// --- 1. LOGIKA FILTER ---
$f_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : '';
$f_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$f_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Filter Query
$where = " WHERE 1=1 ";
if(!empty($f_mapel)) $where .= " AND rps.mapel_id='$f_mapel'";
if(!empty($f_kelas)) $where .= " AND mapel.kelas_id='$f_kelas'";
if(!empty($f_tahun)) $where .= " AND kelas.tahun_ajaran='$f_tahun'";

// Hitung Total Data
$q_stat = mysqli_query($koneksi, "SELECT id_rps FROM rps");
$jml_rps = mysqli_num_rows($q_stat);

// Ambil Data Dropdown
$q_opt_mapel = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas FROM mapel JOIN kelas ON mapel.kelas_id = kelas.id_kelas ORDER BY mapel.nama_mapel ASC");
$q_opt_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$q_opt_tahun = mysqli_query($koneksi, "SELECT DISTINCT tahun_ajaran FROM kelas WHERE tahun_ajaran != '' ORDER BY tahun_ajaran DESC");

// --- 2. QUERY UTAMA (KUNCI PERBAIKAN DISINI) ---
// Kita ambil data Kelas, Guru, Tahun DARI TABEL LAIN menggunakan JOIN
$query_sql = "SELECT 
                rps.*, 
                mapel.nama_mapel, 
                kelas.nama_kelas, 
                kelas.tahun_ajaran, 
                kelas.semester, 
                users.nama_lengkap AS nama_guru
              FROM rps
              JOIN mapel ON rps.mapel_id = mapel.id_mapel
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas
              LEFT JOIN users ON mapel.guru_id = users.id_user
              $where
              ORDER BY rps.tanggal_upload DESC";

$q_rps = mysqli_query($koneksi, $query_sql);
?>

<style>
    /* Style DataTables Orange */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important; }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Modal & Helper Styles */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 600px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .modal-body { padding: 30px; background: #fdfdfd; overflow-y: auto; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; box-sizing: border-box; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; transition: 0.3s; cursor: pointer; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    input[type="file"] { display: none; }
    .filter-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 12px; cursor: pointer; background: white; color: #555; min-width: 130px; transition: 0.3s; }
    .filter-select:hover { border-color: #FF8C00; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-file-alt"></i> Manajemen RPS</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Upload dan kelola Rencana Pembelajaran Semester.</p>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_rps; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Dokumen RPS</span>
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

                <select name="tahun" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Tahun Ajaran --</option>
                    <?php while($t = mysqli_fetch_array($q_opt_tahun)){ $sel = ($f_tahun == $t['tahun_ajaran']) ? 'selected' : ''; echo "<option value='".$t['tahun_ajaran']."' $sel>".$t['tahun_ajaran']."</option>"; } ?>
                </select>

                <?php if($f_mapel!="" || $f_kelas!="" || $f_tahun!="") { ?>
                    <a href="rps.php" style="color: #c62828; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 10px;"><i class="fas fa-times-circle"></i> Reset</a>
                <?php } ?>
            </form>
        </div>

        <div style="padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari RPS..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                </div>
            </div>
            <button onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-cloud-upload-alt"></i> Upload RPS
            </button>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="tabelRpsFix2" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Mata Pelajaran</th>
                        <th style="padding: 15px;">Kelas</th>
                        <th style="padding: 15px;">Guru Pengampu</th>
                        <th style="padding: 15px; text-align: center;">Tahun Ajaran</th>
                        <th style="padding: 15px; text-align: center;">Semester</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Langsung jalankan query loop
                    // Jika data kosong, loop tidak jalan, tbody jadi kosong bersih.
                    // DataTables akan otomatis mendeteksi ini dan menampilkan pesan "No data available" tanpa error.
                    
                    while($d = mysqli_fetch_array($q_rps)){
                        $status_val = isset($d['status']) ? $d['status'] : 'Non-Aktif';
                        $status = ($status_val == 'Aktif') 
                            ? "<span style='background:#e8f5e9; color:#2e7d32; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>AKTIF</span>" 
                            : "<span style='background:#ffebee; color:#c62828; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>NON-AKTIF</span>";
                        
                        $mapel = isset($d['nama_mapel']) ? $d['nama_mapel'] : '-';
                        $kelas = isset($d['nama_kelas']) ? $d['nama_kelas'] : '-';
                        $tahun = isset($d['tahun_ajaran']) ? $d['tahun_ajaran'] : '-';
                        $sem   = isset($d['semester']) ? $d['semester'] : '-';
                        $guru  = !empty($d['nama_guru']) ? $d['nama_guru'] : '<span style="color:#ccc;">-</span>';
                        $file  = isset($d['file_rps']) ? $d['file_rps'] : '';
                        $id    = isset($d['id_rps']) ? $d['id_rps'] : '';
                        $ket   = isset($d['keterangan']) ? addslashes($d['keterangan']) : '';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: 600; color: #333;"><?php echo $mapel; ?></td>
                        <td style="padding: 15px;"><span style="background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo $kelas; ?></span></td>
                        <td style="padding: 15px;"><?php echo $guru; ?></td>
                        <td style="padding: 15px; text-align: center; color: #555;"><?php echo $tahun; ?></td>
                        <td style="padding: 15px; text-align: center; color: #555;"><?php echo $sem; ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $status; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="../uploads/rps/<?php echo $file; ?>" target="_blank" class="btn-action" style="text-decoration:none; background: #e3f2fd; color: #1565c0; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px; display:inline-block;"><i class="fas fa-eye"></i></a>
                            <button onclick="bukaModalEdit('<?php echo $id; ?>','<?php echo $d['mapel_id']; ?>','<?php echo $status_val; ?>','<?php echo $ket; ?>')" class="btn-action edit" style="background: #FFF3E0; color: #E65100; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;"><i class="fas fa-edit"></i></button>
                            <button onclick="konfirmasiHapus('<?php echo $id; ?>')" class="btn-action delete" style="background: #ffebee; color: #c62828; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php } // Tutup While ?>
                    
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-cloud-upload-alt"></i> Upload RPS Baru</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="rps_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Mata Pelajaran</label>
                    <select name="mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php mysqli_data_seek($q_opt_mapel, 0); while($m=mysqli_fetch_array($q_opt_mapel)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; } ?>
                    </select>
                </div>
                <div class="form-group"><label>Status RPS</label>
                    <select name="status" class="form-control-modal"><option value="Aktif">Aktif</option><option value="Non-Aktif">Non-Aktif</option></select>
                </div>
                <div class="form-group"><label>File Dokumen (PDF/DOC)</label>
                    <input type="file" name="file_rps" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')" required>
                    <label for="fileInp" class="file-upload-box"><i class="fas fa-file-pdf"></i><span id="fileNameDisp">Klik untuk cari file...</span></label>
                </div>
                <div class="form-group"><label>Keterangan Tambahan</label><textarea name="keterangan" class="form-control-modal" rows="3"></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN RPS</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Data RPS</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="rps_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_rps" id="edit_id">
                <div class="form-group"><label>Mata Pelajaran</label>
                    <select name="mapel" id="edit_mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php mysqli_data_seek($q_opt_mapel, 0); while($m=mysqli_fetch_array($q_opt_mapel)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; } ?>
                    </select>
                </div>
                <div class="form-group"><label>Status RPS</label>
                    <select name="status" id="edit_status" class="form-control-modal"><option value="Aktif">Aktif</option><option value="Non-Aktif">Non-Aktif</option></select>
                </div>
                <div class="form-group"><label>Ganti File (Opsional)</label>
                    <input type="file" name="file_rps" id="fileInpEdit" onchange="updateFileName('fileInpEdit','fileNameDispEdit')">
                    <label for="fileInpEdit" class="file-upload-box"><i class="fas fa-sync"></i><span id="fileNameDispEdit">Upload baru untuk ganti...</span></label>
                </div>
                <div class="form-group"><label>Keterangan</label><textarea name="keterangan" id="edit_keterangan" class="form-control-modal" rows="3"></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ID TABEL SUDAH DIGANTI JADI 'tabelRpsFix2'
        var table = $('#tabelRpsFix2').DataTable({ 
            "dom": 'rtip', 
            "pageLength": 10, 
            "destroy": true, 
            "order": [] 
        });
        
        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });

    function updateFileName(id, disp) { document.getElementById(disp).innerText = document.getElementById(id).files[0].name; }

    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }
    
    function bukaModalEdit(id, mapel, status, ket) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_mapel').value = mapel;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_keterangan').value = ket;
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }
    
    window.onclick = function(e) { if(e.target.className === 'modal-overlay') e.target.style.display = "none"; }

    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus RPS ini?', text: "File dokumen akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#c62828', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'rps_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', text: '<?php echo $_SESSION['notif_pesan']; ?>', icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', confirmButtonColor: '#FF8C00' });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>  