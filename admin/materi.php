<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- LOGIKA DATA ---

// 1. Ambil Data Kelas (Untuk Filter)
$q_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

// 2. Ambil Data Mapel (Untuk Filter - Opsional jika kelas dipilih)
$q_mapel_list = mysqli_query($koneksi, "SELECT * FROM mapel ORDER BY nama_mapel ASC");

// 3. Logika Filter
$where_clause = "";
if(isset($_GET['kelas']) && $_GET['kelas'] != ""){
    $f_kelas = $_GET['kelas'];
    $where_clause .= " AND mapel.kelas_id = '$f_kelas'";
}
if(isset($_GET['mapel']) && $_GET['mapel'] != ""){
    $f_mapel = $_GET['mapel'];
    $where_clause .= " AND mapel.id_mapel = '$f_mapel'";
}

// 4. Hitung Total Materi
$jml_materi = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_materi FROM materi"));
?>

<style>
    /* Overlay Modal */
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
        max-width: 700px;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }

    @keyframes popUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-header {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { margin: 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .close-btn:hover { opacity: 1; transform: rotate(90deg); }

    .modal-body { padding: 30px; background: #fdfdfd; }

    /* Detail Grid */
    .detail-row {
        display: flex;
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .detail-label {
        width: 140px;
        font-weight: bold;
        color: #888;
        font-size: 13px;
        text-transform: uppercase;
    }
    .detail-value {
        flex: 1;
        font-weight: 600;
        color: #333;
    }
    .detail-desc {
        background: #FFF3E0;
        border: 1px solid #FFE0B2;
        padding: 15px;
        border-radius: 10px;
        color: #E65100;
        line-height: 1.6;
        margin-top: 15px;
        font-size: 14px;
    }

    .btn-download-modal {
        display: inline-block;
        background: #27ae60;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 8px;
        margin-top: 20px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-download-modal:hover { background: #219150; transform: translateY(-2px); }

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
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-file-alt"></i> Manajemen Materi</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola materi pelajaran yang diupload guru.</p>
            </div>
            <div style="text-align: right;">
                <h1 style="margin: 0; font-size: 35px;"><?php echo $jml_materi; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Materi</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; width: 100%; max-width: 100%; overflow: hidden;">
        
        <div style="padding: 20px; border-bottom: 1px solid #eee; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                
                <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <select name="kelas" onchange="this.form.submit()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; color: #555; cursor: pointer; min-width: 130px;">
                        <option value="">- Semua Kelas -</option>
                        <?php mysqli_data_seek($q_kelas, 0); while($k = mysqli_fetch_array($q_kelas)){ ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php if(isset($_GET['kelas']) && $_GET['kelas'] == $k['id_kelas']){ echo "selected"; } ?>>
                                <?php echo $k['nama_kelas']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select name="mapel" onchange="this.form.submit()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; color: #555; cursor: pointer; min-width: 150px;">
                        <option value="">- Semua Mapel -</option>
                        <?php mysqli_data_seek($q_mapel_list, 0); while($m = mysqli_fetch_array($q_mapel_list)){ ?>
                            <option value="<?php echo $m['id_mapel']; ?>" <?php if(isset($_GET['mapel']) && $_GET['mapel'] == $m['id_mapel']){ echo "selected"; } ?>>
                                <?php echo $m['nama_mapel']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>

                <div style="position: relative;">
                    <input type="text" id="searchMateri" onkeyup="searchTable()" placeholder="Cari Judul Materi..." style="padding: 10px 15px 10px 35px; border: 1px solid #FF8C00; border-radius: 5px; outline: none; width: 220px;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 13px; color: #FF8C00;"></i>
                </div>
            </div>

            <div>
                <a href="materi_tambah.php" class="btn-tambah" style="background: white; color: #333; border: 1px solid #333; text-decoration: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; font-size: 14px;" onmouseover="this.style.background='#333'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#333';">
                    <i class="fas fa-plus"></i> UPLOAD MATERI
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="materiTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: white; color: #333; border-bottom: 2px solid #333;">
                    <tr>
                        <th style="padding: 15px 20px; text-align: left; width: 50px; border-right: 1px solid #eee;">NO</th>
                        <th style="padding: 15px 20px; text-align: left; border-right: 1px solid #eee;">JUDUL MATERI</th>
                        <th style="padding: 15px 20px; text-align: left; border-right: 1px solid #eee;">MAPEL & KELAS</th>
                        <th style="padding: 15px 20px; text-align: center; border-right: 1px solid #eee;">TANGGAL</th>
                        <th style="padding: 15px 20px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Query Join: Materi -> Mapel -> Kelas & Users (Guru)
                    // Mengambil semua data yang dibutuhkan untuk tabel & modal
                    $query = mysqli_query($koneksi, "SELECT materi.*, mapel.nama_mapel, kelas.nama_kelas, users.nama_lengkap AS nama_guru 
                                                     FROM materi 
                                                     JOIN mapel ON materi.mapel_id = mapel.id_mapel 
                                                     LEFT JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                                     LEFT JOIN users ON mapel.guru_id = users.id_user 
                                                     WHERE 1=1 $where_clause
                                                     ORDER BY materi.tanggal_upload DESC");
                    
                    if(mysqli_num_rows($query) > 0){
                        while($d = mysqli_fetch_array($query)){
                            // Menentukan Jenis File (Link atau Upload)
                            $tipe_file = (!empty($d['link_materi'])) ? 'LINK' : 'FILE';
                            $icon_file = (!empty($d['link_materi'])) ? 'fa-link' : 'fa-file-pdf';
                            $warna_badge = (!empty($d['link_materi'])) ? '#e1f5fe' : '#fff3e0';
                            $text_badge = (!empty($d['link_materi'])) ? '#0288d1' : '#e65100';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px 20px; color: #777; border-right: 1px solid #eee;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px 20px; border-right: 1px solid #eee;">
                            <span style="font-weight: 600; color: #333; display: block; font-size: 15px;"><?php echo $d['judul_materi']; ?></span>
                            <small style="background: <?php echo $warna_badge; ?>; color: <?php echo $text_badge; ?>; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; margin-top: 5px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas <?php echo $icon_file; ?>"></i> <?php echo $tipe_file; ?>
                            </small>
                        </td>
                        
                        <td style="padding: 15px 20px; border-right: 1px solid #eee;">
                            <span style="display: block; font-weight: 500; color: #333;"><?php echo $d['nama_mapel']; ?></span>
                            <div style="margin-top: 4px; font-size: 12px; color: #777;">
                                <i class="fas fa-school" style="color: #FF8C00;"></i> <?php echo $d['nama_kelas']; ?> &bull; 
                                <i class="fas fa-user" style="color: #999;"></i> <?php echo substr($d['nama_guru'], 0, 15); ?>..
                            </div>
                        </td>

                        <td style="padding: 15px 20px; text-align: center; border-right: 1px solid #eee; color: #555; font-size: 13px;">
                            <?php echo date('d M Y', strtotime($d['tanggal_upload'])); ?>
                        </td>

                        <td style="padding: 15px 20px; text-align: center;">
                            <button class="btn-action detail" 
                                onclick="bukaModal(
                                    '<?php echo addslashes($d['judul_materi']); ?>',
                                    '<?php echo $d['nama_mapel']; ?>',
                                    '<?php echo $d['nama_kelas']; ?>',
                                    '<?php echo $d['nama_guru']; ?>',
                                    '<?php echo date('d F Y H:i', strtotime($d['tanggal_upload'])); ?>',
                                    `<?php echo addslashes(nl2br($d['deskripsi'])); ?>`,
                                    '<?php echo $d['file_materi']; ?>',
                                    '<?php echo $d['link_materi']; ?>'
                                )"
                                title="Lihat Detail" style="background: #e3f2fd; color: #1976d2; padding: 8px 12px; border: none; border-radius: 6px; margin-right: 5px; cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </button>

                            <a href="materi_edit.php?id=<?php echo $d['id_materi']; ?>" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border-radius: 6px; margin-right: 5px; display: inline-block;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="materi_hapus.php?id=<?php echo $d['id_materi']; ?>" onclick="return confirm('Yakin ingin menghapus materi ini? File yang diupload juga akan terhapus.')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px; display: inline-block;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding:40px; color:#999; font-style:italic;'>Tidak ada data materi ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="detailModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-book-reader"></i> Detail Materi</h3>
            <span class="close-btn" onclick="tutupModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="detail-row">
                <div class="detail-label">JUDUL MATERI</div>
                <div class="detail-value" id="d_judul">-</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">MATA PELAJARAN</div>
                <div class="detail-value" id="d_mapel">-</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">KELAS</div>
                <div class="detail-value" id="d_kelas">-</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">PENGUPLOAD</div>
                <div class="detail-value" id="d_guru">-</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">TANGGAL</div>
                <div class="detail-value" id="d_tanggal">-</div>
            </div>

            <div class="detail-desc" id="d_deskripsi">
                Tidak ada deskripsi.
            </div>

            <div id="area_download" style="text-align: center;">
                </div>
        </div>

        <button onclick="tutupModal()" class="btn-close-bottom">TUTUP DETAIL</button>
    </div>
</div>

<script>
    function bukaModal(judul, mapel, kelas, guru, tanggal, deskripsi, file, link) {
        document.getElementById('d_judul').innerText = judul;
        document.getElementById('d_mapel').innerText = mapel;
        document.getElementById('d_kelas').innerText = kelas;
        document.getElementById('d_guru').innerText = guru;
        document.getElementById('d_tanggal').innerText = tanggal;
        document.getElementById('d_deskripsi').innerHTML = deskripsi;

        // Logika Tombol Download / Link
        var area = document.getElementById('area_download');
        area.innerHTML = ""; // Reset

        if(file != "") {
            area.innerHTML = `<a href="../uploads/materi/${file}" target="_blank" class="btn-download-modal">
                                <i class="fas fa-file-download"></i> Download File Materi
                              </a>`;
        } else if (link != "") {
            area.innerHTML = `<a href="${link}" target="_blank" class="btn-download-modal" style="background: #3498db;">
                                <i class="fas fa-external-link-alt"></i> Buka Link Materi
                              </a>`;
        } else {
            area.innerHTML = `<span style="display:inline-block; margin-top:20px; color:#999; font-style:italic;">Tidak ada file lampiran.</span>`;
        }

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
        input = document.getElementById("searchMateri");
        filter = input.value.toUpperCase();
        table = document.getElementById("materiTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Kolom Judul
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