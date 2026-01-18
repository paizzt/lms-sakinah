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
// --- 1. LOGIKA DATA & FILTER ---

// Ambil Data untuk Dropdown Filter & Modal
$q_kelas_all = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$q_guru_all  = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guru' ORDER BY nama_lengkap ASC");
$q_sem_all   = mysqli_query($koneksi, "SELECT * FROM semester ORDER BY id_semester DESC");

// Tangkap Filter dari URL
$filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filter_sem   = isset($_GET['semester']) ? $_GET['semester'] : '';

// Buat Query Where
$where = " WHERE 1=1 ";
if($filter_kelas != "") { $where .= " AND mapel.kelas_id = '$filter_kelas' "; }
// Catatan: Karena tabel mapel tidak ada kolom id_semester, filter semester ini sifatnya visual/opsional
// atau jika sistem Anda menganggap mapel selalu untuk semester aktif.

// Hitung Total Mapel
$jml_mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_mapel FROM mapel"));
?>

<style>
    /* Stats & Card */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #FF8C00; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    
    /* DataTables Custom Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important;
    }
    .dataTables_filter, .dataTables_length { display: none; } /* Hide default controls */
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background-color: #fff; width: 100%; max-width: 700px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
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
    .btn-submit-modal:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3); }

    /* Detail View Styling */
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .detail-item label { font-size: 11px; text-transform: uppercase; color: #999; font-weight: bold; display: block; margin-bottom: 5px; }
    .detail-item div { font-size: 15px; font-weight: 600; color: #333; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    .jadwal-badge { background: #FFF3E0; color: #E65100; padding: 10px; border-radius: 8px; text-align: center; font-weight: bold; border: 1px solid #FFE0B2; margin-top: 10px; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book-open"></i> Manajemen Mata Pelajaran</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Atur jadwal, pengajar, dan data mapel.</p>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 35px; text-align: right;"><?php echo $jml_mapel; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Mapel</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee;">
            
            <form method="GET" action="" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                
                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <select name="kelas" onchange="this.form.submit()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; cursor: pointer; background: white; color: #555;">
                        <option value="">-- Semua Kelas --</option>
                        <?php 
                        mysqli_data_seek($q_kelas_all, 0);
                        while($k = mysqli_fetch_array($q_kelas_all)){
                            $sel = ($filter_kelas == $k['id_kelas']) ? 'selected' : '';
                            echo "<option value='".$k['id_kelas']."' $sel>".$k['nama_kelas']."</option>";
                        } 
                        ?>
                    </select>

                    <select name="semester" onchange="this.form.submit()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px; cursor: pointer; background: white; color: #555;">
                        <option value="">-- Semua Semester --</option>
                        <?php 
                        mysqli_data_seek($q_sem_all, 0);
                        while($s = mysqli_fetch_array($q_sem_all)){
                            $sel = ($filter_sem == $s['id_semester']) ? 'selected' : '';
                            echo "<option value='".$s['id_semester']."' $sel>".$s['semester']." ".$s['tahun_ajaran']."</option>";
                        } 
                        ?>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <select id="customLength" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>

                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 10px; top: 12px; color: #aaa;"></i>
                        <input type="text" id="customSearch" placeholder="Cari mapel..." style="padding: 10px 10px 10px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                    </div>

                    <button type="button" onclick="bukaModalTambah()" class="btn-tambah" style="background: #27ae60; color: white; border: none; padding: 10px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-plus"></i> <span style="font-size: 13px;">Baru</span>
                    </button>
                </div>

            </form>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="mapelTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 40px;">No</th>
                        <th style="padding: 15px; text-align: left;">Kode</th>
                        <th style="padding: 15px; text-align: left;">Mata Pelajaran</th>
                        <th style="padding: 15px; text-align: left;">Kelas</th>
                        <th style="padding: 15px; text-align: left;">Pengajar</th>
                        <th style="padding: 15px; text-align: center;">Jadwal</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT mapel.*, users.nama_lengkap AS nama_guru, kelas.nama_kelas 
                                                     FROM mapel 
                                                     LEFT JOIN users ON mapel.guru_id = users.id_user 
                                                     LEFT JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                                     $where
                                                     ORDER BY mapel.nama_mapel ASC");
                    
                    while($d = mysqli_fetch_array($query)){
                        $jadwal = $d['hari'] . ", " . date('H:i', strtotime($d['jam_mulai'])) . "-" . date('H:i', strtotime($d['jam_selesai']));
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: bold; color: #555;"><?php echo $d['kode_mapel']; ?></td>
                        <td style="padding: 15px; font-weight: 600; color: #333;"><?php echo $d['nama_mapel']; ?></td>
                        <td style="padding: 15px;"><span style="background: #FFF3E0; color: #E65100; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo $d['nama_kelas']; ?></span></td>
                        <td style="padding: 15px;"><?php echo $d['nama_guru'] ? $d['nama_guru'] : '<i style="color:#ccc;">-</i>'; ?></td>
                        <td style="padding: 15px; text-align: center; font-size: 12px;"><?php echo $jadwal; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            
                            <button onclick="bukaModalDetail(
                                '<?php echo $d['kode_mapel']; ?>',
                                '<?php echo addslashes($d['nama_mapel']); ?>',
                                '<?php echo $d['nama_kelas']; ?>',
                                '<?php echo addslashes($d['nama_guru']); ?>',
                                '<?php echo $d['hari']; ?>',
                                '<?php echo $d['jam_mulai']; ?>',
                                '<?php echo $d['jam_selesai']; ?>'
                            )" class="btn-action" style="background: #e3f2fd; color: #1976d2; padding: 8px 10px; border: none; border-radius: 6px; margin-right: 3px; cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button onclick="bukaModalEdit(
                                '<?php echo $d['id_mapel']; ?>',
                                '<?php echo $d['kode_mapel']; ?>',
                                '<?php echo addslashes($d['nama_mapel']); ?>',
                                '<?php echo $d['guru_id']; ?>',
                                '<?php echo $d['kelas_id']; ?>',
                                '<?php echo $d['hari']; ?>',
                                '<?php echo $d['jam_mulai']; ?>',
                                '<?php echo $d['jam_selesai']; ?>'
                            )" class="btn-action edit" style="background: #FFF3E0; color: #E65100; padding: 8px 10px; border: none; border-radius: 6px; margin-right: 3px; cursor: pointer;">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button onclick="konfirmasiHapus('<?php echo $d['id_mapel']; ?>')" class="btn-action delete" style="background: #ffebee; color: #c62828; padding: 8px 10px; border: none; border-radius: 6px; cursor: pointer;">
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
        <div class="modal-header"><h3><i class="fas fa-plus-circle"></i> Tambah Mapel</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="mapel_aksi.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px;">
                    <div class="form-group"><label>Kode Mapel</label><input type="text" name="kode_mapel" class="form-control-modal" required></div>
                    <div class="form-group"><label>Nama Mata Pelajaran</label><input type="text" name="nama_mapel" class="form-control-modal" required></div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Kelas</label>
                        <select name="kelas_id" class="form-control-modal" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php mysqli_data_seek($q_kelas_all, 0); while($k=mysqli_fetch_array($q_kelas_all)){ echo "<option value='".$k['id_kelas']."'>".$k['nama_kelas']."</option>"; } ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Guru Pengajar</label>
                        <select name="guru_id" class="form-control-modal">
                            <option value="">- Pilih Guru -</option>
                            <?php mysqli_data_seek($q_guru_all, 0); while($g=mysqli_fetch_array($q_guru_all)){ echo "<option value='".$g['id_user']."'>".$g['nama_lengkap']."</option>"; } ?>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Hari</label>
                        <select name="hari" class="form-control-modal" required>
                            <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Jam Mulai</label><input type="time" name="jam_mulai" class="form-control-modal" required></div>
                    <div class="form-group"><label>Jam Selesai</label><input type="time" name="jam_selesai" class="form-control-modal" required></div>
                </div>
                
                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN MAPEL</button>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Mapel</h3><span class="close-btn" onclick="tutupModalEdit()">&times;</span></div>
        <div class="modal-body">
            <form action="mapel_update.php" method="POST">
                <input type="hidden" name="id_mapel" id="edit_id">
                
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px;">
                    <div class="form-group"><label>Kode Mapel</label><input type="text" name="kode_mapel" id="edit_kode" class="form-control-modal" required></div>
                    <div class="form-group"><label>Nama Mata Pelajaran</label><input type="text" name="nama_mapel" id="edit_nama" class="form-control-modal" required></div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Kelas</label>
                        <select name="kelas_id" id="edit_kelas" class="form-control-modal" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php mysqli_data_seek($q_kelas_all, 0); while($k=mysqli_fetch_array($q_kelas_all)){ echo "<option value='".$k['id_kelas']."'>".$k['nama_kelas']."</option>"; } ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Guru Pengajar</label>
                        <select name="guru_id" id="edit_guru" class="form-control-modal">
                            <option value="">- Pilih Guru -</option>
                            <?php mysqli_data_seek($q_guru_all, 0); while($g=mysqli_fetch_array($q_guru_all)){ echo "<option value='".$g['id_user']."'>".$g['nama_lengkap']."</option>"; } ?>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group"><label>Hari</label>
                        <select name="hari" id="edit_hari" class="form-control-modal" required>
                            <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Jam Mulai</label><input type="time" name="jam_mulai" id="edit_mulai" class="form-control-modal" required></div>
                    <div class="form-group"><label>Jam Selesai</label><input type="time" name="jam_selesai" id="edit_selesai" class="form-control-modal" required></div>
                </div>
                
                <button type="submit" class="btn-submit-modal"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<div id="modalDetail" class="modal-overlay">
    <div class="modal-box" style="max-width: 500px;">
        <div class="modal-header"><h3><i class="fas fa-info-circle"></i> Detail Mapel</h3><span class="close-btn" onclick="tutupModalDetail()">&times;</span></div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item"><label>Kode Mapel</label><div id="det_kode">-</div></div>
                <div class="detail-item"><label>Nama Mapel</label><div id="det_nama">-</div></div>
                <div class="detail-item"><label>Kelas</label><div id="det_kelas">-</div></div>
                <div class="detail-item"><label>Guru</label><div id="det_guru">-</div></div>
            </div>
            <div class="jadwal-badge">
                <i class="far fa-clock"></i> <span id="det_jadwal">-</span>
            </div>
            <button onclick="tutupModalDetail()" class="btn-submit-modal" style="margin-top:20px; background:#555;">Tutup</button>
        </div>
    </div>
</div>

<script>
    // --- DataTables Init ---
    $(document).ready(function() {
        var table = $('#mapelTable').DataTable({
            "dom": 'rtip',
            "pageLength": 10
        });
        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });

    // --- Modal Functions ---
    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }
    
    function bukaModalEdit(id, kode, nama, guru, kelas, hari, mulai, selesai) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_kode').value = kode;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_guru').value = guru;
        document.getElementById('edit_kelas').value = kelas;
        document.getElementById('edit_hari').value = hari;
        document.getElementById('edit_mulai').value = mulai;
        document.getElementById('edit_selesai').value = selesai;
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }

    function bukaModalDetail(kode, nama, kelas, guru, hari, mulai, selesai) {
        document.getElementById('det_kode').innerText = kode;
        document.getElementById('det_nama').innerText = nama;
        document.getElementById('det_kelas').innerText = kelas;
        document.getElementById('det_guru').innerText = guru;
        document.getElementById('det_jadwal').innerText = hari + ", " + mulai.substring(0,5) + " - " + selesai.substring(0,5);
        document.getElementById('modalDetail').style.display = "flex";
    }
    function tutupModalDetail() { document.getElementById('modalDetail').style.display = "none"; }

    window.onclick = function(e) {
        if(e.target == document.getElementById('modalTambah')) tutupModalTambah();
        if(e.target == document.getElementById('modalEdit')) tutupModalEdit();
        if(e.target == document.getElementById('modalDetail')) tutupModalDetail();
    }

    // --- SweetAlert Delete ---
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Mapel ini?',
            text: "Data materi, tugas, dan nilai terkait akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'mapel_hapus.php?id=' + id; }
        })
    }

    // --- Notifikasi PHP ---
    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonColor: '#FF8C00'
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>