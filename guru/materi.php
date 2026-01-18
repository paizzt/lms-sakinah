<?php 
// Matikan error agar tabel aman
error_reporting(0);
ini_set('display_errors', 0);

include 'header.php'; 
include 'sidebar.php'; 

// AMBIL ID GURU DARI SESSION
$id_guru = $_SESSION['id_user'];

// 1. QUERY UNTUK DROPDOWN (HANYA MAPEL YG DIAJAR GURU INI)
$q_mapel_guru = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                        FROM mapel 
                                        JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                        WHERE mapel.guru_id = '$id_guru'
                                        ORDER BY mapel.nama_mapel ASC");

// 2. QUERY UTAMA TABEL (HANYA MATERI DARI GURU INI)
$query_sql = "SELECT materi.*, mapel.nama_mapel, kelas.nama_kelas 
              FROM materi 
              JOIN mapel ON materi.mapel_id = mapel.id_mapel 
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
              WHERE mapel.guru_id = '$id_guru'
              ORDER BY materi.tanggal_upload DESC";
$q_materi = mysqli_query($koneksi, $query_sql);
$jml_materi = mysqli_num_rows($q_materi);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* DataTables Orange */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important; }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 600px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; opacity: 0.8; }
    .modal-body { padding: 30px; background: #fdfdfd; overflow-y: auto; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
    
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; cursor: pointer; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    input[type="file"] { display: none; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book"></i> Materi Pembelajaran</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola bahan ajar untuk kelas Anda.</p>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_materi; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Materi</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari materi..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                </div>
            </div>
            <button onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-plus"></i> Tambah Materi
            </button>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="materiTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Judul Materi</th>
                        <th style="padding: 15px;">Mapel & Kelas</th>
                        <th style="padding: 15px; text-align: center;">Tanggal</th>
                        <th style="padding: 15px; text-align: center;">File / Link</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // While loop langsung (Tanpa IF Num Rows) agar DataTables aman
                    while($d = mysqli_fetch_array($q_materi)){
                        $tgl = date('d-m-Y', strtotime($d['tanggal_upload']));
                        
                        $tipe = isset($d['tipe']) ? $d['tipe'] : 'file';
                        $url  = isset($d['file_url']) ? $d['file_url'] : '#';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['judul']; ?>
                            <div style="font-size: 11px; color: #999; margin-top: 3px;"><?php echo substr($d['deskripsi'], 0, 40) . '...'; ?></div>
                        </td>

                        <td style="padding: 15px;">
                            <span style="display: block; font-weight: bold; color: #555;"><?php echo $d['nama_mapel']; ?></span>
                            <span style="background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;"><?php echo $d['nama_kelas']; ?></span>
                        </td>

                        <td style="padding: 15px; text-align: center; color: #555;"><?php echo $tgl; ?></td>

                        <td style="padding: 15px; text-align: center;">
                            <?php if($tipe == 'file') { ?>
                                <a href="../uploads/materi/<?php echo $url; ?>" target="_blank" style="text-decoration: none; background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $url; ?>" target="_blank" style="text-decoration: none; background: #ffebee; color: #c62828; padding: 6px 12px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                    <i class="fas fa-external-link-alt"></i> Buka Link
                                </a>
                            <?php } ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <button onclick="bukaModalLihat('<?php echo addslashes($d['judul']); ?>','<?php echo addslashes($d['nama_mapel']); ?>','<?php echo addslashes($d['nama_kelas']); ?>','<?php echo $tgl; ?>','<?php echo addslashes($d['deskripsi']); ?>')" class="btn-action" style="background: #e3f2fd; color: #1565c0; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;"><i class="fas fa-eye"></i></button>

                            <button onclick="bukaModalEdit('<?php echo $d['id_materi']; ?>','<?php echo addslashes($d['judul']); ?>','<?php echo $d['mapel_id']; ?>','<?php echo $tipe; ?>','<?php echo $url; ?>','<?php echo addslashes($d['deskripsi']); ?>')" class="btn-action edit" style="background: #FFF3E0; color: #E65100; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;"><i class="fas fa-edit"></i></button>

                            <button onclick="konfirmasiHapus('<?php echo $d['id_materi']; ?>')" class="btn-action delete" style="background: #ffebee; color: #c62828; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer;"><i class="fas fa-trash"></i></button>
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
        <div class="modal-header"><h3><i class="fas fa-plus-circle"></i> Tambah Materi</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="materi_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Judul Materi</label><input type="text" name="judul" class="form-control-modal" placeholder="Contoh: Pengantar Aljabar" required></div>
                
                <div class="form-group"><label>Mata Pelajaran (Kelas)</label>
                    <select name="mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php 
                        // Reset pointer data mapel
                        mysqli_data_seek($q_mapel_guru, 0); 
                        while($m=mysqli_fetch_array($q_mapel_guru)){ 
                            echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; 
                        } 
                        ?>
                    </select>
                </div>
                
                <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control-modal" rows="3"></textarea></div>

                <div class="form-group"><label>Tipe Materi</label>
                    <select id="tipeUpload" name="tipe" class="form-control-modal" onchange="toggleUploadInput('tambah')">
                        <option value="file">Upload Dokumen</option><option value="link">Tautan Luar</option>
                    </select>
                </div>

                <div id="boxFile_tambah" class="form-group">
                    <label>Pilih File</label><input type="file" name="file_materi" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')"><label for="fileInp" class="file-upload-box"><i class="fas fa-cloud-upload-alt"></i><span id="fileNameDisp">Klik cari file...</span></label>
                </div>

                <div id="boxLink_tambah" class="form-group" style="display: none;">
                    <label>URL / Link Materi</label><input type="text" name="link_materi" class="form-control-modal" placeholder="https://...">
                </div>

                <button type="submit" class="btn-submit-modal">SIMPAN MATERI</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Materi</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="materi_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_materi" id="edit_id">
                <div class="form-group"><label>Judul Materi</label><input type="text" name="judul" id="edit_judul" class="form-control-modal" required></div>
                
                <div class="form-group"><label>Mata Pelajaran</label>
                    <select name="mapel" id="edit_mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php mysqli_data_seek($q_mapel_guru, 0); while($m=mysqli_fetch_array($q_mapel_guru)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; } ?>
                    </select>
                </div>
                
                <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" id="edit_deskripsi" class="form-control-modal" rows="3"></textarea></div>

                <div class="form-group"><label>Tipe Materi</label>
                    <select id="edit_tipe" name="tipe" class="form-control-modal" onchange="toggleUploadInput('edit')">
                        <option value="file">Upload Dokumen</option><option value="link">Tautan Luar</option>
                    </select>
                </div>

                <div id="boxFile_edit" class="form-group">
                    <label>Ganti File</label><input type="file" name="file_materi" id="fileInpEdit" onchange="updateFileName('fileInpEdit','fileNameDispEdit')"><label for="fileInpEdit" class="file-upload-box"><i class="fas fa-sync"></i><span id="fileNameDispEdit">Upload baru...</span></label>
                </div>

                <div id="boxLink_edit" class="form-group" style="display: none;">
                    <label>URL / Link Materi</label><input type="text" name="link_materi" id="edit_link" class="form-control-modal">
                </div>

                <button type="submit" class="btn-submit-modal">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<div id="modalLihat" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-info-circle"></i> Detail Materi</h3><span class="close-btn" onclick="tutupModalLihat()">&times;</span></div>
        <div class="modal-body">
            <h3 id="view_judul" style="margin-top:0; color:#E65100;">-</h3>
            <div style="margin-bottom:15px; font-size:12px; color:#777;">
                <span id="view_mapel" style="font-weight:bold;">-</span> | <span id="view_kelas">-</span> | <span id="view_tgl">-</span>
            </div>
            <div style="background:#fafafa; padding:15px; border:1px dashed #ddd; border-radius:10px;">
                <p id="view_deskripsi" style="margin:0;">-</p>
            </div>
            <button onclick="tutupModalLihat()" class="btn-submit-modal" style="background:#555; margin-top:20px;">Tutup</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#materiTable').DataTable({ "dom": 'rtip', "pageLength": 10, "order": [] });
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

    function bukaModalEdit(id, judul, mapel, tipe, url, desk) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_mapel').value = mapel;
        document.getElementById('edit_deskripsi').value = desk;
        document.getElementById('edit_tipe').value = tipe;
        if(tipe == 'link') document.getElementById('edit_link').value = url;
        toggleUploadInput('edit');
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }

    function bukaModalLihat(judul, mapel, kelas, tgl, desk) {
        document.getElementById('view_judul').innerText = judul;
        document.getElementById('view_mapel').innerText = mapel;
        document.getElementById('view_kelas').innerText = kelas;
        document.getElementById('view_tgl').innerText = tgl;
        document.getElementById('view_deskripsi').innerText = desk;
        document.getElementById('modalLihat').style.display = "flex";
    }
    function tutupModalLihat() { document.getElementById('modalLihat').style.display = "none"; }

    window.onclick = function(e) { if(e.target.className === 'modal-overlay') e.target.style.display = "none"; }

    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus materi ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#c62828', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'materi_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', text: '<?php echo $_SESSION['notif_pesan']; ?>', icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', confirmButtonColor: '#FF8C00' });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>