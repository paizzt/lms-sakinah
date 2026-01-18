<?php 
error_reporting(0);
ini_set('display_errors', 0);
include 'header.php'; 
include 'sidebar.php'; 
?>

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
// FILTER
$f_tujuan = isset($_GET['tujuan']) ? $_GET['tujuan'] : '';
$where = " WHERE 1=1 ";
if($f_tujuan != "") $where .= " AND tujuan='$f_tujuan'";

// DATA
$q_pengumuman = mysqli_query($koneksi, "SELECT * FROM pengumuman $where ORDER BY tanggal DESC");
$jml_data = mysqli_num_rows($q_pengumuman);
?>

<style>
    /* ORANGE THEME */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important; }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 600px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .close-btn { cursor: pointer; font-size: 24px; opacity: 0.8; }
    .modal-body { padding: 30px; background: #fdfdfd; overflow-y: auto; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
    
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; cursor: pointer; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    input[type="file"] { display: none; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-bullhorn"></i> Pengumuman</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola informasi untuk guru dan siswa.</p>
            </div>
            <div><h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_data; ?></h1><span style="font-size: 12px; opacity: 0.8;">Total Info</span></div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #f9f9f9; border-bottom: 1px solid #eee;">
            <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                <span style="font-weight: bold; font-size: 12px; color: #777;">Filter Tujuan:</span>
                <select name="tujuan" onchange="this.form.submit()" style="padding: 8px 15px; border-radius: 20px; border: 1px solid #ddd; outline: none;">
                    <option value="">-- Semua --</option>
                    <option value="Semua" <?php if($f_tujuan=='Semua') echo 'selected'; ?>>Untuk Semua</option>
                    <option value="Guru" <?php if($f_tujuan=='Guru') echo 'selected'; ?>>Khusus Guru</option>
                    <option value="Siswa" <?php if($f_tujuan=='Siswa') echo 'selected'; ?>>Khusus Siswa</option>
                </select>
                <?php if($f_tujuan != "") { ?><a href="pengumuman.php" style="color:#c62828; font-size:12px; font-weight:bold; text-decoration:none;">Reset</a><?php } ?>
            </form>
        </div>

        <div style="padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border-radius: 8px; border: 1px solid #ddd;"><option value="10">10</option><option value="25">25</option></select>
                <input type="text" id="customSearch" placeholder="Cari info..." style="padding: 8px 15px; border-radius: 20px; border: 1px solid #ddd; outline: none;">
            </div>
            <button onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                <i class="fas fa-plus"></i> Buat Pengumuman
            </button>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="tabelPengumuman" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Judul Pengumuman</th>
                        <th style="padding: 15px;">Tujuan</th>
                        <th style="padding: 15px; text-align: center;">Tanggal Posting</th>
                        <th style="padding: 15px; text-align: center;">Lampiran</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php 
                    $no = 1;
                    // HAPUS pengecekan if(mysqli_num_rows > 0)
                    // Langsung saja looping while. Jika kosong, loop tidak jalan, tbody kosong.
                    // DataTables akan otomatis mengisi "No data available".
                    
                    while($d = mysqli_fetch_array($q_pengumuman)){
                        $tujuan = $d['tujuan'];
                        $badge = ($tujuan == 'Semua') ? '#7e57c2' : (($tujuan == 'Guru') ? '#2980b9' : '#27ae60');
                        $lampiran = $d['file_lampiran'] ? '<a href="../uploads/pengumuman/'.$d['file_lampiran'].'" target="_blank" style="color:#e65100; font-weight:bold;"><i class="fas fa-paperclip"></i> File</a>' : '-';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['judul']; ?>
                            <div style="font-size: 11px; color: #999; margin-top: 3px;"><?php echo substr($d['isi'], 0, 50) . '...'; ?></div>
                        </td>
                        <td style="padding: 15px;"><span style="background:<?php echo $badge; ?>; color:white; padding:3px 10px; border-radius:15px; font-size:11px; font-weight:bold;"><?php echo strtoupper($tujuan); ?></span></td>
                        <td style="padding: 15px; text-align: center; color: #555;"><?php echo date('d-m-Y H:i', strtotime($d['tanggal'])); ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $lampiran; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <button onclick="bukaModalLihat('<?php echo addslashes($d['judul']); ?>','<?php echo $tujuan; ?>','<?php echo date('d-m-Y', strtotime($d['tanggal'])); ?>','<?php echo addslashes(nl2br($d['isi'])); ?>')" class="btn-action" style="background:#e3f2fd; color:#1565c0; border:none; padding:8px 10px; border-radius:6px; cursor:pointer;"><i class="fas fa-eye"></i></button>
                            <button onclick="bukaModalEdit('<?php echo $d['id_pengumuman']; ?>','<?php echo addslashes($d['judul']); ?>','<?php echo $tujuan; ?>','<?php echo addslashes($d['isi']); ?>')" class="btn-action edit" style="background:#FFF3E0; color:#E65100; border:none; padding:8px 10px; border-radius:6px; cursor:pointer;"><i class="fas fa-edit"></i></button>
                            <button onclick="konfirmasiHapus('<?php echo $d['id_pengumuman']; ?>')" class="btn-action delete" style="background:#ffebee; color:#c62828; border:none; padding:8px 10px; border-radius:6px; cursor:pointer;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php } // Tutup kurung while ?>
                    
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-plus-circle"></i> Tambah Pengumuman</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="pengumuman_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Judul Pengumuman</label><input type="text" name="judul" class="form-control-modal" required></div>
                <div class="form-group"><label>Tujuan Informasi</label>
                    <select name="tujuan" class="form-control-modal" required>
                        <option value="Semua">Untuk Semua User</option>
                        <option value="Guru">Khusus Guru</option>
                        <option value="Siswa">Khusus Siswa</option>
                    </select>
                </div>
                <div class="form-group"><label>Isi Pengumuman</label><textarea name="isi" class="form-control-modal" rows="5" required></textarea></div>
                <div class="form-group"><label>Lampiran (Opsional - PDF/IMG)</label>
                    <input type="file" name="file_lampiran" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')">
                    <label for="fileInp" class="file-upload-box"><i class="fas fa-cloud-upload-alt"></i><span id="fileNameDisp">Klik cari file...</span></label>
                </div>
                <button type="submit" class="btn-submit-modal">SIMPAN PENGUMUMAN</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Pengumuman</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="pengumuman_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group"><label>Judul Pengumuman</label><input type="text" name="judul" id="edit_judul" class="form-control-modal" required></div>
                <div class="form-group"><label>Tujuan Informasi</label>
                    <select name="tujuan" id="edit_tujuan" class="form-control-modal" required>
                        <option value="Semua">Untuk Semua User</option>
                        <option value="Guru">Khusus Guru</option>
                        <option value="Siswa">Khusus Siswa</option>
                    </select>
                </div>
                <div class="form-group"><label>Isi Pengumuman</label><textarea name="isi" id="edit_isi" class="form-control-modal" rows="5" required></textarea></div>
                <div class="form-group"><label>Ganti Lampiran (Opsional)</label>
                    <input type="file" name="file_lampiran" id="fileInpEdit" onchange="updateFileName('fileInpEdit','fileNameDispEdit')">
                    <label for="fileInpEdit" class="file-upload-box"><i class="fas fa-sync"></i><span id="fileNameDispEdit">Upload baru...</span></label>
                </div>
                <button type="submit" class="btn-submit-modal">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<div id="modalLihat" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-info-circle"></i> Detail Info</h3><span class="close-btn" onclick="tutupModalLihat()">&times;</span></div>
        <div class="modal-body">
            <h2 id="view_judul" style="margin-top:0; color:#E65100;">-</h2>
            <div style="margin-bottom:15px; font-size:12px; color:#777;">
                <span id="view_tgl">-</span> | Untuk: <span id="view_tujuan" style="font-weight:bold;">-</span>
            </div>
            <div style="background:#fafafa; padding:15px; border:1px dashed #ddd; border-radius:10px; line-height:1.6;">
                <p id="view_isi">-</p>
            </div>
            <button onclick="tutupModalLihat()" class="btn-submit-modal" style="background:#555; margin-top:20px;">Tutup</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#tabelPengumuman').DataTable({ "dom": 'rtip', "pageLength": 10, "order": [] });
        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });

    function updateFileName(id, disp) { document.getElementById(disp).innerText = document.getElementById(id).files[0].name; }
    
    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }

    function bukaModalEdit(id, judul, tujuan, isi) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_tujuan').value = tujuan;
        document.getElementById('edit_isi').value = isi;
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }

    function bukaModalLihat(judul, tujuan, tgl, isi) {
        document.getElementById('view_judul').innerText = judul;
        document.getElementById('view_tujuan').innerText = tujuan;
        document.getElementById('view_tgl').innerText = tgl;
        document.getElementById('view_isi').innerHTML = isi;
        document.getElementById('modalLihat').style.display = "flex";
    }
    function tutupModalLihat() { document.getElementById('modalLihat').style.display = "none"; }

    window.onclick = function(e) { if(e.target.className === 'modal-overlay') e.target.style.display = "none"; }

    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus Info ini?', text: "Data akan hilang permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#c62828', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'pengumuman_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', text: '<?php echo $_SESSION['notif_pesan']; ?>', icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', confirmButtonColor: '#FF8C00' });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>