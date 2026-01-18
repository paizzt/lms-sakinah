<?php 
// Matikan error PHP agar tampilan bersih
error_reporting(0);
ini_set('display_errors', 0);

include 'header.php'; 
include 'sidebar.php'; 

// AMBIL ID GURU
$id_guru = $_SESSION['id_user'];

// 1. QUERY DROPDOWN (UNTUK MODAL TAMBAH/EDIT)
$q_mapel_guru = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                        FROM mapel 
                                        JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                        WHERE mapel.guru_id = '$id_guru'
                                        ORDER BY mapel.nama_mapel ASC");

// 2. QUERY CARD (HANYA TUGAS DARI GURU INI)
$query_sql = "SELECT tugas.*, mapel.nama_mapel, kelas.nama_kelas 
              FROM tugas 
              JOIN mapel ON tugas.mapel_id = mapel.id_mapel 
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
              WHERE mapel.guru_id = '$id_guru'
              ORDER BY tugas.tgl_buat DESC";
$q_tugas = mysqli_query($koneksi, $query_sql);
$jml_tugas = mysqli_num_rows($q_tugas);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- 1. HEADER & PENCARIAN --- */
    .page-header-control {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .search-box {
        position: relative;
        flex-grow: 1;
        max-width: 400px;
    }
    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border-radius: 30px;
        border: 1px solid #eee;
        background: #f9f9f9;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
    }
    .search-box input:focus {
        background: #fff;
        border-color: #FF8C00;
        box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
    }
    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }

    .btn-create-new {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        padding: 12px 25px;
        font-weight: bold;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }
    .btn-create-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 140, 0, 0.4);
    }

    /* --- 2. GRID SYSTEM --- */
    .task-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        padding-bottom: 30px;
    }

    /* --- 3. MODERN TASK CARD --- */
    .task-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border-top: 4px solid #FF8C00; /* Aksen Oranye di atas */
    }

    .task-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 35px rgba(255, 140, 0, 0.15);
    }

    /* Bagian Atas Card */
    .card-content {
        padding: 25px;
    }

    /* Badge Mapel & Kelas */
    .badge-mapel {
        display: inline-block;
        background: #FFF3E0;
        color: #E65100;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    /* Judul Tugas */
    .task-title {
        font-size: 20px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0 0 15px 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Batasi 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Info Meta (Status & Waktu) */
    .task-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 13px;
        color: #7f8c8d;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }
    .task-meta i { margin-right: 5px; }
    .status-active { color: #27ae60; font-weight: 600; }
    .status-closed { color: #c0392b; font-weight: 600; }

    /* Tombol Aksi Pojok Kanan (Edit/Hapus) */
    .card-actions-corner {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        gap: 8px;
        opacity: 0.8;
        transition: 0.3s;
    }
    .task-card:hover .card-actions-corner { opacity: 1; }

    .btn-icon-corner {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 13px;
        background: #f0f2f5;
        color: #7f8c8d;
        transition: 0.2s;
    }
    .btn-icon-corner:hover { background: #FF8C00; color: white; }
    .btn-icon-corner.delete:hover { background: #e74c3c; color: white; }

    /* Tombol Utama (Lihat Detail) di Bawah */
    .btn-detail-full {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 18px;
        background: #FFF3E0; /* Oranye sangat muda */
        color: #E65100;
        font-weight: 800;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }

    .btn-detail-full:hover {
        background: linear-gradient(to right, #FF8C00, #F39C12);
        color: white;
        padding-left: 25px; /* Efek geser sedikit */
    }
    .btn-detail-full i { transition: 0.3s; }
    .btn-detail-full:hover i { transform: translateX(5px); }

    /* --- 4. STYLE MODAL (Popup) --- */
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
    .btn-submit-modal { width: 100%; background: linear-gradient(to right, #FF8C00, #F39C12); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
    .btn-submit-modal:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3); }
    
    .file-upload-box { position: relative; border: 2px dashed #e0e0e0; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa; cursor: pointer; transition: 0.3s; }
    .file-upload-box:hover { border-color: #FF8C00; background: #FFF3E0; }
    .file-upload-box i { font-size: 30px; color: #FF8C00; margin-bottom: 10px; display: block; }
    input[type="file"] { display: none; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 30px; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 28px; font-weight: 800;"><i class="fas fa-tasks"></i> Manajemen Tugas</h2>
                <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">Kelola tugas dan penilaian siswa Anda di sini.</p>
            </div>
            <div style="text-align: right;">
                <h1 style="margin: 0; font-size: 42px; font-weight: 800;"><?php echo $jml_tugas; ?></h1>
                <span style="font-size: 14px; opacity: 0.9; font-weight: 600;">Tugas Aktif</span>
            </div>
        </div>
    </div>

    <div class="page-header-control">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" onkeyup="filterCards()" placeholder="Cari berdasarkan judul tugas...">
        </div>
        
        <button onclick="bukaModalTambah()" class="btn-create-new">
            <i class="fas fa-plus-circle"></i> BUAT TUGAS BARU
        </button>
    </div>

    <div class="task-grid" id="taskContainer">
        
        <?php 
        if($jml_tugas > 0){
            while($d = mysqli_fetch_array($q_tugas)){
                $deadline_formatted = date('d M Y, H:i', strtotime($d['tgl_kumpul']));
                $tipe = isset($d['tipe']) ? $d['tipe'] : 'file';
                $url  = isset($d['file_url']) ? $d['file_url'] : '';
                
                // Cek Status (Visual)
                $sekarang = date('Y-m-d H:i:s');
                $is_active = ($d['tgl_kumpul'] > $sekarang);
                $status_html = $is_active ? '<span class="status-active"><i class="fas fa-check-circle"></i> Dibuka</span>' : '<span class="status-closed"><i class="fas fa-lock"></i> Ditutup</span>';
        ?>
        
        <div class="task-card">
            <div class="card-actions-corner">
                <button class="btn-icon-corner" title="Edit Tugas" onclick="bukaModalEdit('<?php echo $d['id_tugas']; ?>','<?php echo addslashes($d['judul_tugas']); ?>','<?php echo $d['mapel_id']; ?>','<?php echo date('Y-m-d\TH:i', strtotime($d['tgl_kumpul'])); ?>','<?php echo $tipe; ?>','<?php echo $url; ?>','<?php echo addslashes($d['deskripsi']); ?>')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn-icon-corner delete" title="Hapus Tugas" onclick="konfirmasiHapus('<?php echo $d['id_tugas']; ?>')">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <div class="card-content">
                <span class="badge-mapel"><?php echo $d['nama_mapel']; ?> | <?php echo $d['nama_kelas']; ?></span>
                <h3 class="task-title"><?php echo $d['judul_tugas']; ?></h3>
                
                <div class="task-meta">
                    <div><?php echo $status_html; ?></div>
                    <div><i class="far fa-clock"></i> Tenggat: <?php echo $deadline_formatted; ?></div>
                </div>
            </div>

            <a href="tugas_nilai.php?id=<?php echo $d['id_tugas']; ?>" class="btn-detail-full">
                LIHAT DETAIL & NILAI <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <?php 
            }
        } else {
            // Tampilan jika tidak ada tugas
            echo '<div style="grid-column: 1/-1; text-align:center; padding: 50px 20px; background: white; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                    <img src="../assets/img/no-data.svg" style="width: 150px; opacity: 0.5; margin-bottom: 20px;">
                    <h3 style="color: #555;">Belum Ada Tugas</h3>
                    <p style="color: #999;">Silakan buat tugas baru untuk kelas Anda.</p>
                  </div>';
        }
        ?>

    </div>

</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-plus-circle"></i> Buat Tugas Baru</h3><span class="close-btn" onclick="tutupModalTambah()">&times;</span></div>
        <div class="modal-body">
            <form action="tugas_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Judul Tugas</label><input type="text" name="judul" class="form-control-modal" placeholder="Contoh: Latihan Soal Bab 1" required></div>
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
                <div class="form-group"><label>Batas Waktu Pengumpulan</label><input type="datetime-local" name="deadline" class="form-control-modal" required></div>
                <div class="form-group"><label>Tipe Lampiran Soal</label>
                    <select id="tipeUpload" name="tipe" class="form-control-modal" onchange="toggleUploadInput('tambah')">
                        <option value="file">Upload File Dokumen</option><option value="link">Tautan / Link Eksternal</option>
                    </select>
                </div>
                <div id="boxFile_tambah" class="form-group">
                    <label>Upload File Soal (Opsional)</label><input type="file" name="file_tugas" id="fileInp" onchange="updateFileName('fileInp','fileNameDisp')"><label for="fileInp" class="file-upload-box"><i class="fas fa-cloud-upload-alt"></i><span id="fileNameDisp">Klik untuk cari file (PDF, DOCX, dll)...</span></label>
                </div>
                <div id="boxLink_tambah" class="form-group" style="display:none;">
                    <label>Link Soal</label><input type="text" name="link_tugas" class="form-control-modal" placeholder="https://ContohLinkSoal.com/...">
                </div>
                <div class="form-group"><label>Instruksi / Deskripsi Tugas</label><textarea name="deskripsi" class="form-control-modal" rows="4" placeholder="Tuliskan instruksi pengerjaan tugas di sini..."></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN TUGAS SEKARANG</button>
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
                        <?php mysqli_data_seek($q_mapel_guru, 0); while($m=mysqli_fetch_array($q_mapel_guru)){ echo "<option value='".$m['id_mapel']."'>".$m['nama_mapel']." - ".$m['nama_kelas']."</option>"; } ?>
                    </select>
                </div>
                <div class="form-group"><label>Batas Waktu</label><input type="datetime-local" name="deadline" id="edit_deadline" class="form-control-modal" required></div>
                <div class="form-group"><label>Tipe Lampiran</label>
                    <select id="edit_tipe" name="tipe" class="form-control-modal" onchange="toggleUploadInput('edit')">
                        <option value="file">Upload File</option><option value="link">Tautan / Link</option>
                    </select>
                </div>
                <div id="boxFile_edit" class="form-group">
                    <label>Ganti File Soal</label><input type="file" name="file_tugas" id="fileInpEdit" onchange="updateFileName('fileInpEdit','fileNameDispEdit')"><label for="fileInpEdit" class="file-upload-box"><i class="fas fa-sync"></i><span id="fileNameDispEdit">Upload file baru untuk mengganti...</span></label>
                </div>
                <div id="boxLink_edit" class="form-group" style="display:none;">
                    <label>Link Soal</label><input type="text" name="link_tugas" id="edit_link" class="form-control-modal">
                </div>
                <div class="form-group"><label>Instruksi</label><textarea name="deskripsi" id="edit_deskripsi" class="form-control-modal" rows="4"></textarea></div>
                <button type="submit" class="btn-submit-modal">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>
</div>

<script>
    // FILTER PENCARIAN KARTU
    function filterCards() {
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var container = document.getElementById("taskContainer");
        var cards = container.getElementsByClassName("task-card");

        for (var i = 0; i < cards.length; i++) {
            var title = cards[i].getElementsByClassName("task-title")[0];
            if (title) {
                var txtValue = title.textContent || title.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "flex"; // Pastikan display flex agar layout terjaga
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    }

    // TOGGLE INPUT FILE / LINK
    function toggleUploadInput(mode) {
        var tipe = (mode == 'tambah') ? document.getElementById('tipeUpload').value : document.getElementById('edit_tipe').value;
        if(tipe == 'file'){ document.getElementById('boxFile_' + mode).style.display = 'block'; document.getElementById('boxLink_' + mode).style.display = 'none'; } 
        else { document.getElementById('boxFile_' + mode).style.display = 'none'; document.getElementById('boxLink_' + mode).style.display = 'block'; }
    }
    function updateFileName(id, disp) { document.getElementById(disp).innerText = document.getElementById(id).files[0].name; }

    // MODAL FUNCTIONS
    function bukaModalTambah() { document.getElementById('modalTambah').style.display = "flex"; }
    function tutupModalTambah() { document.getElementById('modalTambah').style.display = "none"; }

    function bukaModalEdit(id, judul, mapel, deadline, tipe, url, desk) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_mapel').value = mapel;
        document.getElementById('edit_deadline').value = deadline;
        document.getElementById('edit_deskripsi').value = desk;
        document.getElementById('edit_tipe').value = tipe;
        if(tipe == 'link') document.getElementById('edit_link').value = url;
        toggleUploadInput('edit');
        document.getElementById('modalEdit').style.display = "flex";
    }
    function tutupModalEdit() { document.getElementById('modalEdit').style.display = "none"; }

    window.onclick = function(e) { if(e.target.className === 'modal-overlay') e.target.style.display = "none"; }

    // DELETE FUNCTION
    function konfirmasiHapus(id) {
        Swal.fire({ title: 'Hapus tugas ini?', text: "Semua data pengumpulan dan nilai siswa untuk tugas ini akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then((result) => {
            if (result.isConfirmed) { window.location.href = 'tugas_hapus.php?id=' + id; }
        })
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({ 
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>', 
            text: '<?php echo $_SESSION['notif_pesan']; ?>', 
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>', 
            confirmButtonColor: '#FF8C00',
            timer: 3000,
            timerProgressBar: true
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>