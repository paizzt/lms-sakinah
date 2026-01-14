<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- LOGIKA DATA ---
$q_kelas_filter = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

$q_sem = mysqli_query($koneksi, "SELECT * FROM semester WHERE status=1");
$d_sem = mysqli_fetch_assoc($q_sem);
$sem_aktif = isset($d_sem['semester']) ? $d_sem['semester'] . ' ' . $d_sem['tahun_ajaran'] : 'Semester Aktif';

$where_clause = "";
if(isset($_GET['kelas']) && $_GET['kelas'] != ""){
    $filter_kelas = $_GET['kelas'];
    $where_clause = "WHERE mapel.kelas_id = '$filter_kelas'";
}

$jml_mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_mapel FROM mapel"));
?>

<style>
    /* Overlay Gelap dengan Blur */
    .modal-overlay {
        display: none;
        position: fixed; 
        z-index: 9999; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.6); 
        backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Kotak Modal */
    .modal-box {
        background-color: #fff;
        width: 100%;
        max-width: 800px; /* Lebar ditambah sedikit agar lega */
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
    }

    @keyframes popUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Header Gradasi */
    .modal-header {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 5px 15px rgba(230, 81, 0, 0.3);
    }

    .modal-header h3 { margin: 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .close-btn:hover { opacity: 1; transform: rotate(90deg); }

    .modal-body { padding: 40px; background: #fdfdfd; } /* Padding body diperbesar */

    /* PERBAIKAN DI SINI: GRID LEBIH LEGA */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px; /* Jarak antar kolom diperlebar (Sebelumnya 25px) */
        margin-bottom: 30px;
    }

    .input-wrapper {
        position: relative;
    }
    
    .input-wrapper label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #888;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-wrapper label i { color: #FF8C00; margin-right: 5px; }

    .custom-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        background: #fff;
        border-radius: 10px;
        font-size: 15px;
        color: #333;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        transition: 0.3s;
        box-sizing: border-box; /* Penting agar padding tidak merusak lebar */
    }

    .custom-input:focus {
        border-color: #FF8C00;
        box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
        outline: none;
    }

    /* Divider */
    .modern-divider {
        display: flex;
        align-items: center;
        margin: 35px 0;
        color: #FF8C00;
        font-weight: bold;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .modern-divider::before, .modern-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
    .modern-divider::before { margin-right: 15px; }
    .modern-divider::after { margin-left: 15px; }

    /* Jadwal Grid - Juga Diberi Jarak Lebih */
    .jadwal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 30px; /* Jarak antar kotak jadwal */
    }
    .jadwal-box {
        background: #FFF3E0;
        border: 1px solid #FFE0B2;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
    }
    .jadwal-box small { display: block; color: #E65100; font-size: 11px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
    .jadwal-box span { display: block; font-size: 16px; font-weight: 700; color: #333; }

    .btn-close-bottom {
        display: block;
        width: 100%;
        background: #eee;
        color: #555;
        border: none;
        padding: 15px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        border-top: 1px solid #ddd;
    }
    .btn-close-bottom:hover { background: #e0e0e0; color: #333; }

    @media (max-width: 600px) {
        .form-grid, .jadwal-grid { grid-template-columns: 1fr; gap: 20px; }
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book-open"></i> Manajemen Mata Pelajaran</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Atur jadwal dan pengajar mata pelajaran.</p>
            </div>
            <div style="text-align: right;">
                <h1 style="margin: 0; font-size: 35px;"><?php echo $jml_mapel; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Mapel</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; width: 100%; max-width: 100%; overflow: hidden;">
        
        <div style="padding: 20px; border-bottom: 1px solid #eee; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                <form method="GET" action="" style="display: flex; gap: 10px;">
                    <select name="kelas" onchange="this.form.submit()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; color: #555; cursor: pointer; min-width: 150px;">
                        <option value="">-- Pilih Kelas --</option>
                        <?php mysqli_data_seek($q_kelas_filter, 0); while($k = mysqli_fetch_array($q_kelas_filter)){ ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php if(isset($_GET['kelas']) && $_GET['kelas'] == $k['id_kelas']){ echo "selected"; } ?>>
                                <?php echo $k['nama_kelas']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <select disabled style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; color: #777; background: #f9f9f9; min-width: 150px;">
                        <option selected><?php echo $sem_aktif; ?></option>
                    </select>
                </form>
                <div style="position: relative;">
                    <input type="text" id="searchMapel" onkeyup="searchTable()" placeholder="Cari Mata Pelajaran..." style="padding: 10px 15px 10px 35px; border: 1px solid #FF8C00; border-radius: 5px; outline: none; width: 250px;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 13px; color: #FF8C00;"></i>
                </div>
            </div>
            <div>
                <a href="mapel_tambah.php" class="btn-tambah" style="background: white; color: #333; border: 1px solid #333; text-decoration: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; font-size: 14px;" onmouseover="this.style.background='#333'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#333';">
                    <i class="fas fa-plus"></i> TAMBAH MAPEL
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="mapelTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: white; color: #333; border-bottom: 2px solid #333;">
                    <tr>
                        <th style="padding: 15px 20px; text-align: left; width: 50px; border-right: 1px solid #eee;">NO</th>
                        <th style="padding: 15px 20px; text-align: left; border-right: 1px solid #eee;">MATA PELAJARAN</th>
                        <th style="padding: 15px 20px; text-align: left; border-right: 1px solid #eee;">PENGAJAR</th>
                        <th style="padding: 15px 20px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT mapel.*, users.nama_lengkap AS nama_guru, kelas.nama_kelas 
                                                     FROM mapel 
                                                     LEFT JOIN users ON mapel.guru_id = users.id_user 
                                                     LEFT JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                                     $where_clause
                                                     ORDER BY mapel.nama_mapel ASC");
                    while($d = mysqli_fetch_array($query)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px 20px; color: #777; border-right: 1px solid #eee;"><?php echo $no++; ?></td>
                        <td style="padding: 15px 20px; border-right: 1px solid #eee;">
                            <span style="font-weight: 600; color: #333; display: block;"><?php echo $d['nama_mapel']; ?></span>
                            <?php if($d['nama_kelas']) { ?>
                                <small style="color: #FF8C00; background: #FFF3E0; padding: 2px 8px; border-radius: 4px; font-weight: bold; margin-top: 5px; display: inline-block;">
                                    <?php echo $d['nama_kelas']; ?>
                                </small>
                            <?php } ?>
                        </td>
                        <td style="padding: 15px 20px; border-right: 1px solid #eee;">
                            <?php echo $d['nama_guru'] ? "<i class='fas fa-user-tie' style='color:#2980b9;'></i> " . $d['nama_guru'] : "<span style='color:#bbb; font-style:italic;'>- Kosong -</span>"; ?>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <button class="btn-action detail" 
                                onclick="bukaModal(
                                    '<?php echo $d['kode_mapel']; ?>',
                                    '<?php echo $d['nama_mapel']; ?>',
                                    '<?php echo $d['nama_kelas']; ?>',
                                    '<?php echo $d['nama_guru'] ? $d['nama_guru'] : '-'; ?>',
                                    '<?php echo $d['hari'] ? $d['hari'] : '-'; ?>',
                                    '<?php echo $d['jam_mulai'] ? date('H:i', strtotime($d['jam_mulai'])) : '-'; ?>',
                                    '<?php echo $d['jam_selesai'] ? date('H:i', strtotime($d['jam_selesai'])) : '-'; ?>'
                                )"
                                title="Lihat Detail" style="background: #e3f2fd; color: #1976d2; padding: 8px 12px; border: none; border-radius: 6px; margin-right: 5px; cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="mapel_edit.php?id=<?php echo $d['id_mapel']; ?>" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border-radius: 6px; margin-right: 5px; display: inline-block;"><i class="fas fa-edit"></i></a>
                            <a href="mapel_hapus.php?id=<?php echo $d['id_mapel']; ?>" onclick="return confirm('Hapus?')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px; display: inline-block;"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="padding: 15px 20px; background: #fafafa; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; color: #777; font-size: 13px;">
            <div>Showing data</div>
            <div>
                <button style="border: 1px solid #ddd; background: white; padding: 6px 12px;">Prev</button>
                <button style="border: 1px solid #FF8C00; background: #FF8C00; padding: 6px 12px; color: white;">1</button>
                <button style="border: 1px solid #ddd; background: white; padding: 6px 12px;">Next</button>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-info-circle"></i> Detail Mata Pelajaran</h3>
            <span class="close-btn" onclick="tutupModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            
            <div class="form-grid">
                <div>
                    <div class="input-wrapper" style="margin-bottom: 25px;">
                        <label><i class="fas fa-hashtag"></i> Kode Mapel</label>
                        <input type="text" id="m_kode" class="custom-input" readonly>
                    </div>
                    <div class="input-wrapper">
                        <label><i class="fas fa-school"></i> Kelas</label>
                        <input type="text" id="m_kelas" class="custom-input" readonly>
                    </div>
                </div>

                <div>
                    <div class="input-wrapper" style="margin-bottom: 25px;">
                        <label><i class="fas fa-book"></i> Nama Mapel</label>
                        <input type="text" id="m_nama" class="custom-input" readonly>
                    </div>
                    <div class="input-wrapper">
                        <label><i class="fas fa-user-tie"></i> Guru Pengampu</label>
                        <input type="text" id="m_guru" class="custom-input" readonly>
                    </div>
                </div>
            </div>

            <div class="modern-divider">
                <i class="far fa-clock" style="margin-right: 8px;"></i> Jadwal Pelajaran
            </div>

            <div class="jadwal-grid">
                <div class="jadwal-box">
                    <small>HARI</small>
                    <span id="m_hari">-</span>
                </div>
                <div class="jadwal-box">
                    <small>JAM MULAI</small>
                    <span id="m_mulai">-</span>
                </div>
                <div class="jadwal-box">
                    <small>JAM SELESAI</small>
                    <span id="m_selesai">-</span>
                </div>
            </div>

        </div>
        
        <button onclick="tutupModal()" class="btn-close-bottom">TUTUP DETAIL</button>
    </div>
</div>

<script>
    function bukaModal(kode, nama, kelas, guru, hari, mulai, selesai) {
        document.getElementById('m_kode').value = kode;
        document.getElementById('m_nama').value = nama;
        document.getElementById('m_kelas').value = kelas;
        document.getElementById('m_guru').value = guru;
        
        document.getElementById('m_hari').innerText = hari;
        document.getElementById('m_mulai').innerText = mulai;
        document.getElementById('m_selesai').innerText = selesai;

        document.getElementById('detailModal').style.display = "flex";
    }

    function tutupModal() {
        document.getElementById('detailModal').style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('detailModal')) {
            tutupModal();
        }
    }
    
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchMapel");
        filter = input.value.toUpperCase();
        table = document.getElementById("mapelTable");
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