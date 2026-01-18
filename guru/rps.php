<?php 
// Matikan error PHP agar tampilan bersih & DataTables aman
error_reporting(0);
ini_set('display_errors', 0);

include 'header.php'; 
include 'sidebar.php'; 

$id_guru = $_SESSION['id_user'];

// 1. AMBIL DATA MAPEL GURU (Untuk Dropdown Modal)
$q_mapel_guru = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                        FROM mapel 
                                        JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                        WHERE mapel.guru_id = '$id_guru'
                                        ORDER BY mapel.nama_mapel ASC");

// 2. AMBIL DATA RPS (Hanya milik Guru ini)
$query_sql = "SELECT rps.*, mapel.nama_mapel, kelas.nama_kelas, kelas.semester, kelas.tahun_ajaran
              FROM rps
              JOIN mapel ON rps.mapel_id = mapel.id_mapel
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas
              WHERE mapel.guru_id = '$id_guru'
              ORDER BY rps.tanggal_upload DESC";

$q_rps = mysqli_query($koneksi, $query_sql);
$jml_rps = mysqli_num_rows($q_rps);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* DataTables Theme Orange */
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
    .form-control-modal { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; }
    .form-control-modal:focus { border-color: #FF8C00; outline: none; box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1); }
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
    
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; cursor: pointer; transition: 0.3s; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    input[type="file"] { display: none; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-file-alt"></i> Kelola RPS</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Rencana Pembelajaran Semester mata pelajaran Anda.</p>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_rps; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Dokumen RPS</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari RPS..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                </div>
            </div>
            <button onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-cloud-upload-alt"></i> Upload RPS Baru
            </button>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="rpsTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Mata Pelajaran</th>
                        <th style="padding: 15px;">Kelas</th>
                        <th style="padding: 15px; text-align: center;">Semester</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px;">Keterangan</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Loop while langsung agar aman dari error DataTables
                    while($d = mysqli_fetch_array($q_rps)){
                        $status = ($d['status'] == 'Aktif') 
                            ? "<span style='background:#e8f5e9; color:#2e7d32; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>AKTIF</span>" 
                            : "<span style='background:#ffebee; color:#c62828; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>NON-AKTIF</span>";
                        
                        $file_link = $d['file_rps'];
                        $ket = !empty($d['keterangan']) ? $d['keterangan'] : '-';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 600; color: #333;"><?php echo $d['nama_mapel']; ?></td>
                        
                        <td style="padding: 15px;">
                            <span style="background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                <?php echo $d['nama_kelas']; ?>
                            </span>
                        </td>
                        
                        <td style="padding: 15px; text-align: center; color: #555;">
                            <?php echo $d['semester']; ?> <br>
                            <small style="font-size:10px; color:#999;"><?php echo $d['tahun_ajaran']; ?></small>
                        </td>
                        
                        <td style="padding: 15px; text-align: center;"><?php echo $status; ?></td>
                        
                        <td style="padding: 15px; font-size: 13px; color: #555;"><?php echo $ket; ?></td>
                        
                        <td style="padding: 15px; text-align: center;">
                            <a href="../uploads/rps/<?php echo $file_link; ?>" target="_blank" class="btn-action" style="text-decoration:none; background: #e3f2fd; color: #1565c0; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px; display:inline-block;">
                                <i class="fas fa-download"></i>
                            </a>

                            <button onclick="bukaModalEdit('<?php echo $d['id_rps']; ?>','<?php echo $d['mapel_id']; ?>','<?php echo $d['status']; ?>','<?php echo addslashes($d['keterangan']); ?>')" class="btn-action edit" style="background: #FFF3E0; color: #E65100; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer; margin-right: 3px;">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button onclick="konfirmasiHapus('<?php echo $d['id_rps']; ?>')" class="btn-action delete" style="background: #ffebee; color: #c62828; border: none; padding: 8px 10px; border-radius: 6px; cursor: pointer;">
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

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-cloud-upload-alt"></i> Upload RPS Baru</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="rps_aksi.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-group"><label>Mata Pelajaran & Kelas</label>
                    <select name="mapel" class="form-control-modal" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php 
                        mysqli_data_seek($q_mapel_guru, 0); 
                        while($m=mysqli_fetch_array($q_mapel_guru)){ 
                            echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; 
                        } 
                        ?>
                    </select>
                </div>
                
                <div class="form-group"><label>Status RPS</label>
                    <select name="status" class="form-control-modal">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>

                <div class="form-group"><label>File Dokumen (PDF/DOC)</label>
                    <input type="file" name="file_rps" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')" required>
                    <label for="fileInp" class="file-upload-box"><i class="fas fa-file-pdf"></i><span id="fileNameDisp">Klik untuk cari file...</span></label>
                </div>

                <div class="form-group"><label>Keterangan (Opsional)</label><textarea name="keterangan" class="form-control-modal" rows="3"></textarea></div>

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
                        <?php 
                        mysqli_data_seek($q_mapel_guru, 0); 
                        while($m=mysqli_fetch_array($q_mapel_guru)){ 
                            echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; 
                        } 
                        ?>
                    </select>
                </div>

                <div class="form-group"><label>Status RPS</label>
                    <select name="status" id="edit_status" class="form-control-modal">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
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
        var table = $('#rpsTable').DataTable({ "dom": 'rtip', "pageLength": 10, "order": [] });
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