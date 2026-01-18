<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; }
    /* DataTables Custom Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #FF8C00 !important; color: white !important; border: 1px solid #FF8C00 !important; border-radius: 5px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #ffebee !important; color: #FF8C00 !important; border: 1px solid #FF8C00 !important; }
    .dataTables_filter, .dataTables_length { display: none; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee !important; }

    /* Filter Styles */
    .filter-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 12px; cursor: pointer; background: white; color: #555; min-width: 150px; transition: 0.3s; }
    .filter-select:hover { border-color: #FF8C00; }
    .filter-date { padding: 7px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 12px; color: #555; cursor: pointer; }
    
    /* PRINT STYLE */
    @media print {
        .sidebar, .welcome-banner, .filter-section, .no-print, .dataTables_paginate, .dataTables_info, button { display: none !important; }
        .content-body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .modern-form-card { box-shadow: none !important; border: none !important; }
        table { width: 100% !important; border-collapse: collapse !important; font-size: 12px; }
        table th, table td { border: 1px solid #000 !important; padding: 8px !important; }
        thead { background-color: #ddd !important; color: #000 !important; -webkit-print-color-adjust: exact; }
        .only-print { display: block !important; margin-bottom: 20px; }
    }
</style>

<?php
// --- 1. LOGIKA FILTER ---
$f_kelas   = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$f_mapel   = isset($_GET['mapel']) ? $_GET['mapel'] : '';
$f_tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

// DATA DROPDOWN
$q_opt_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$q_opt_mapel = mysqli_query($koneksi, "SELECT * FROM mapel ORDER BY nama_mapel ASC");

// QUERY DATA UTAMA
// Logika: Ambil Siswa (Users) -> Left Join Absensi (Sesuai Filter)
// Filter Kelas Wajib ada di WHERE user, Filter Mapel/Tanggal ada di ON Join Absensi

$where_user = " WHERE users.role = 'siswa' ";
if($f_kelas != "") $where_user .= " AND users.kelas_id = '$f_kelas' ";

// Kondisi Join Absensi (Hanya ambil absensi yg sesuai mapel & tanggal yg dipilih)
$join_condition = " users.id_user = absensi.siswa_id ";
if($f_mapel != "")   $join_condition .= " AND absensi.mapel_id = '$f_mapel' ";
if($f_tanggal != "") $join_condition .= " AND absensi.tanggal = '$f_tanggal' ";

$query_sql = "SELECT users.nama_lengkap, users.id_user, kelas.nama_kelas, 
                     absensi.status, absensi.keterangan, absensi.tanggal,
                     mapel.nama_mapel
              FROM users
              JOIN kelas ON users.kelas_id = kelas.id_kelas
              LEFT JOIN absensi ON $join_condition
              LEFT JOIN mapel ON absensi.mapel_id = mapel.id_mapel
              $where_user
              ORDER BY kelas.nama_kelas ASC, users.nama_lengkap ASC";

$q_absen = mysqli_query($koneksi, $query_sql);
$total_data = mysqli_num_rows($q_absen);
?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner no-print" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-clipboard-list"></i> Rekap Absensi</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Laporan kehadiran siswa per mata pelajaran.</p>
            </div>
            <div style="text-align: right;">
                <h1 style="margin: 0; font-size: 35px;"><?php echo $total_data; ?></h1>
                <span style="font-size: 12px; opacity: 0.8;">Total Data Tampil</span>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div class="filter-section no-print" style="padding: 20px; background: #f9f9f9; border-bottom: 1px solid #eee;">
            <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <span style="font-weight: bold; font-size: 12px; color: #777;"><i class="fas fa-filter"></i> Filter:</span>
                
                <select name="kelas" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php 
                    mysqli_data_seek($q_opt_kelas, 0);
                    while($k = mysqli_fetch_array($q_opt_kelas)){
                        $sel = ($f_kelas == $k['id_kelas']) ? 'selected' : '';
                        echo "<option value='".$k['id_kelas']."' $sel>".$k['nama_kelas']."</option>";
                    }
                    ?>
                </select>

                <select name="mapel" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Mapel --</option>
                    <?php 
                    mysqli_data_seek($q_opt_mapel, 0);
                    while($m = mysqli_fetch_array($q_opt_mapel)){
                        $sel = ($f_mapel == $m['id_mapel']) ? 'selected' : '';
                        echo "<option value='".$m['id_mapel']."' $sel>".$m['nama_mapel']."</option>";
                    }
                    ?>
                </select>

                <input type="date" name="tanggal" class="filter-date" value="<?php echo $f_tanggal; ?>" onchange="this.form.submit()">

                <?php if($f_kelas!="" || $f_mapel!="" || $f_tanggal!="") { ?>
                    <a href="absensi_rekap.php" style="color: #c62828; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 10px; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-times-circle"></i> Reset
                    </a>
                <?php } ?>
            </form>
        </div>

        <div class="no-print" style="padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select id="customLength" style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                    <input type="text" id="customSearch" placeholder="Cari siswa..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; width: 200px;">
                </div>
            </div>
            
            <button onclick="window.print()" class="btn-tambah" style="background: #555; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 13px;">
                <i class="fas fa-print"></i> Cetak PDF
            </button>
        </div>

        <div style="display:none;" class="only-print">
            <h3 style="text-align:center; margin-bottom:5px;">Laporan Rekap Absensi</h3>
            <p style="text-align:center; margin-top:0;">LMS Sakinah</p>
            <hr>
        </div>

        <div class="table-responsive" style="padding: 0 0 10px 0;">
            <table class="table table-striped" id="absenTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Nama Siswa</th>
                        <th style="padding: 15px;">Kelas</th>
                        <th style="padding: 15px;">Mata Pelajaran</th>
                        <th style="padding: 15px; text-align: center;">Tanggal</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if($total_data > 0){
                        while($d = mysqli_fetch_array($q_absen)){
                            
                            // LOGIKA STATUS
                            // Pastikan konversi ke huruf besar agar cocok dengan IF
                            $status_kode = isset($d['status']) ? strtoupper($d['status']) : ''; 
                            
                            // Default Badge (Jika kosong / Belum Absen)
                            $badge = "<span style='color:#ccc; font-style:italic;'>Belum Absen</span>";

                            // Cek nilai status (H/S/I/A)
                            if($status_kode == 'H' || $status_kode == 'HADIR') {
                                $badge = "<span style='background:#d1f2eb; color:#0e6251; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>HADIR</span>";
                            } 
                            elseif($status_kode == 'S' || $status_kode == 'SAKIT') {
                                $badge = "<span style='background:#d6eaf8; color:#154360; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>SAKIT</span>";
                            } 
                            elseif($status_kode == 'I' || $status_kode == 'IZIN') {
                                $badge = "<span style='background:#fcf3cf; color:#7d6608; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>IZIN</span>";
                            } 
                            elseif($status_kode == 'A' || $status_kode == 'ALPA') {
                                $badge = "<span style='background:#fadbd8; color:#78281f; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;'>ALPA</span>";
                            }

                            // Tampilkan tanggal atau strip jika kosong
                            $tgl_tampil = !empty($d['tanggal']) ? date('d-m-Y', strtotime($d['tanggal'])) : '-';
                            // Tampilkan mapel atau strip jika kosong (karena left join)
                            $mapel_tampil = !empty($d['nama_mapel']) ? $d['nama_mapel'] : '-';
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777; text-align: center;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['nama_lengkap']; ?>
                        </td>

                        <td style="padding: 15px;">
                            <span style="background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                <?php echo $d['nama_kelas']; ?>
                            </span>
                        </td>

                        <td style="padding: 15px; color: #555;">
                            <?php echo $mapel_tampil; ?>
                        </td>

                        <td style="padding: 15px; text-align: center; color: #555; font-size: 12px;">
                            <?php echo $tgl_tampil; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <?php echo $badge; ?>
                        </td>

                        <td style="padding: 15px; color: #777; font-style: italic; font-size: 13px;">
                            <?php echo !empty($d['keterangan']) ? $d['keterangan'] : '-'; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center; padding:40px; color:#999;'>Tidak ada data siswa sesuai filter.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    // Inisialisasi DataTables
    $(document).ready(function() {
        var table = $('#absenTable').DataTable({
            "dom": 'rtip', 
            "pageLength": 10,
            "order": [], 
            "language": {
                "paginate": { "next": "Next >", "previous": "< Prev" },
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
        $('#customLength').on('change', function() { table.page.len(this.value).draw(); });
    });
</script>

<?php include 'footer.php'; ?>