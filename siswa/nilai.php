<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_siswa = $_SESSION['id_user'];

// 1. AMBIL ID KELAS (Safety First)
$cek_siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT kelas_id FROM users WHERE id_user='$id_siswa'"));
$id_kelas  = $cek_siswa['kelas_id'];

if(empty($id_kelas)){
    echo "<script>window.location='index.php';</script>";
    exit();
}

// 2. AMBIL DAFTAR MAPEL
$q_mapel = mysqli_query($koneksi, "SELECT * FROM mapel WHERE kelas_id='$id_kelas' ORDER BY nama_mapel ASC");
?>

<style>
    /* STYLE ACCORDION CARD */
    .grade-card {
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        border: 1px solid #eee;
        overflow: hidden;
        transition: 0.3s;
    }
    .grade-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    /* Header Kartu (Yang Diklik) */
    .grade-header {
        padding: 20px 25px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-left: 5px solid #ddd; /* Default border abu */
        transition: 0.3s;
    }

    /* Warna Border Kiri Berdasarkan Rata-rata */
    .border-high { border-left-color: #27ae60 !important; } /* Hijau (Bagus) */
    .border-mid  { border-left-color: #f39c12 !important; } /* Kuning (Sedang) */
    .border-low  { border-left-color: #c62828 !important; } /* Merah (Kurang) */

    .grade-header:hover { background: #fdfdfd; }

    /* Isi Detail (Disembunyikan Default) */
    .grade-body {
        display: none; /* Hidden default */
        padding: 0 25px 25px 25px;
        background: #fdfdfd;
        border-top: 1px dashed #eee;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Typography */
    .mapel-name { font-size: 16px; font-weight: 800; color: #333; margin: 0; }
    .teacher-name { font-size: 12px; color: #777; margin-top: 3px; }
    
    .score-badge {
        font-size: 24px; font-weight: 800; color: #333;
        display: flex; flex-direction: column; align-items: flex-end;
    }
    .score-label { font-size: 10px; color: #999; font-weight: normal; text-transform: uppercase; }

    /* Table Detail */
    .table-grade { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .table-grade th { text-align: left; font-size: 12px; color: #999; padding: 10px; border-bottom: 2px solid #eee; }
    .table-grade td { padding: 12px 10px; border-bottom: 1px solid #f0f0f0; font-size: 14px; color: #555; vertical-align: top; }
    .table-grade tr:last-child td { border-bottom: none; }

    .nilai-final { font-weight: bold; color: #333; background: #eee; padding: 5px 10px; border-radius: 10px; font-size: 12px; }
    .catatan-guru { font-style: italic; color: #777; font-size: 12px; background: #fff8e1; padding: 5px 10px; border-radius: 5px; display: inline-block; margin-top: 5px; }
    
    .toggle-icon { transition: 0.3s; color: #ccc; margin-left: 15px; }
    .active .toggle-icon { transform: rotate(180deg); color: #FF8C00; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #FF8C00;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: #333;">Rekap Nilai</h2>
            <p style="margin: 5px 0 0 0; color: #777;">Pantau perkembangan akademik Anda di sini.</p>
        </div>
        <div style="text-align: right;">
            <span style="background: #e3f2fd; color: #1565c0; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                <i class="fas fa-chart-line"></i> Akademik
            </span>
        </div>
    </div>

    <div id="accordionGrade">
        <?php 
        if(mysqli_num_rows($q_mapel) > 0){
            while($m = mysqli_fetch_array($q_mapel)){
                $id_mapel = $m['id_mapel'];
                
                // --- 1. HITUNG RATA-RATA & AMBIL DETAIL TUGAS ---
                // Query mengambil semua tugas di mapel ini + nilai siswa (jika ada)
                $q_nilai = mysqli_query($koneksi, "SELECT t.judul_tugas, pt.nilai, pt.catatan_guru, pt.tgl_upload
                                                   FROM tugas t
                                                   LEFT JOIN pengumpulan_tugas pt ON t.id_tugas = pt.tugas_id AND pt.siswa_id = '$id_siswa'
                                                   WHERE t.mapel_id = '$id_mapel'
                                                   ORDER BY t.tgl_kumpul DESC");
                
                $total_nilai = 0;
                $count_tugas = 0;
                $detail_rows = ""; // Menyimpan HTML baris tabel untuk nanti

                while($n = mysqli_fetch_array($q_nilai)){
                    // Jika sudah dinilai (nilai tidak NULL dan tidak kosong)
                    $nilai_tampil = "-";
                    $catatan = "-";
                    
                    if(isset($n['nilai']) && $n['nilai'] != ""){
                        $total_nilai += $n['nilai'];
                        $count_tugas++;
                        $nilai_tampil = "<span class='nilai-final'>".$n['nilai']."</span>";
                        
                        if(!empty($n['catatan_guru'])){
                            $catatan = "<div class='catatan-guru'><i class='fas fa-comment-alt'></i> ".$n['catatan_guru']."</div>";
                        } else {
                            $catatan = "";
                        }
                    } else if(isset($n['tgl_upload'])){
                        $nilai_tampil = "<span style='color:#f39c12; font-size:11px; font-weight:bold;'>Menunggu</span>";
                    } else {
                        $nilai_tampil = "<span style='color:#c62828; font-size:11px; font-weight:bold;'>Belum Kumpul</span>";
                    }

                    $detail_rows .= "<tr>
                                        <td width='50%'><b>".$n['judul_tugas']."</b>$catatan</td>
                                        <td width='30%' align='right'>$nilai_tampil</td>
                                     </tr>";
                }

                // Hitung Rata-rata
                $rata2 = ($count_tugas > 0) ? round($total_nilai / $count_tugas, 1) : 0;
                
                // Tentukan Warna Border & Nilai
                $border_class = "border-mid"; // Default kuning
                $color_text = "#f39c12";

                if($count_tugas == 0) { 
                    $border_class = ""; // Abu jika belum ada nilai
                    $color_text = "#ccc";
                    $rata2_display = "-";
                } else {
                    if($rata2 >= 85) { $border_class = "border-high"; $color_text = "#27ae60"; }
                    else if($rata2 < 70) { $border_class = "border-low"; $color_text = "#c62828"; }
                    $rata2_display = $rata2;
                }
        ?>

        <div class="grade-card">
            <div class="grade-header <?php echo $border_class; ?>" onclick="toggleGrade(this)">
                <div>
                    <h3 class="mapel-name"><?php echo $m['nama_mapel']; ?></h3>
                    <?php 
                        $guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_lengkap FROM users WHERE id_user='".$m['guru_id']."'"));
                        $nama_guru = isset($guru['nama_lengkap']) ? $guru['nama_lengkap'] : 'Guru Pengampu';
                    ?>
                    <div class="teacher-name"><i class="fas fa-chalkboard-teacher"></i> <?php echo $nama_guru; ?></div>
                </div>
                
                <div style="display: flex; align-items: center;">
                    <div class="score-badge">
                        <span style="color: <?php echo $color_text; ?>"><?php echo $rata2_display; ?></span>
                        <span class="score-label">Rata-Rata</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="grade-body">
                <table class="table-grade">
                    <thead>
                        <tr>
                            <th>NAMA TUGAS</th>
                            <th style="text-align: right;">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($detail_rows != ""){
                            echo $detail_rows;
                        } else {
                            echo "<tr><td colspan='2' style='text-align:center; padding:20px; color:#ccc;'>Belum ada tugas di mapel ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php 
            }
        } else {
            echo "<div style='text-align:center; padding:50px;'>
                    <img src='../assets/img/no-data.svg' style='width:100px; opacity:0.5; margin-bottom:15px;'>
                    <p style='color:#999;'>Anda belum memiliki mata pelajaran.</p>
                  </div>";
        }
        ?>
    </div>

</div>

<script>
    function toggleGrade(element) {
        // Toggle class active untuk icon
        element.classList.toggle("active");
        
        // Cari elemen body (sibling berikutnya)
        var body = element.nextElementSibling;
        
        if (body.style.display === "block") {
            body.style.display = "none";
        } else {
            body.style.display = "block";
        }
    }
</script>

<?php include 'footer.php'; ?>