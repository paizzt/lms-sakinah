<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_siswa = $_SESSION['id_user'];

// --- PERBAIKAN: AMBIL ID KELAS DARI DATABASE ---
// Kita query ulang user untuk memastikan dapat kelas_id yang benar
$cek_siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT kelas_id FROM users WHERE id_user='$id_siswa'"));
$id_kelas  = $cek_siswa['kelas_id'];

// Validasi jika siswa belum punya kelas
if(empty($id_kelas)){
    echo "<div class='content-body' style='margin-top: -20px; text-align: center; padding: 50px;'>
            <h3>Anda belum masuk kelas!</h3>
            <p>Silakan hubungi Admin atau Guru.</p>
            <a href='index.php' style='background:#FF8C00; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Kembali</a>
          </div>";
    include 'footer.php';
    exit();
}
// -----------------------------------------------

// 1. CEK FILTER (Apakah dari Menu Mapel atau Dashboard?)
$where_mapel = "";
$judul_halaman = "Daftar Semua Tugas";
$sub_judul = "Berikut adalah tugas dari seluruh mata pelajaran.";

if(isset($_GET['mapel']) && !empty($_GET['mapel'])){
    $id_mapel = $_GET['mapel'];
    $where_mapel = " AND t.mapel_id='$id_mapel' ";
    
    // Ambil nama mapel untuk judul
    $d_mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama_mapel FROM mapel WHERE id_mapel='$id_mapel'"));
    $judul_halaman = "Tugas: " . $d_mapel['nama_mapel'];
    $sub_judul = "Daftar tugas khusus untuk mata pelajaran ini.";
}

// 2. QUERY TUGAS + STATUS PENGUMPULAN
// Kita gunakan LEFT JOIN ke tabel pengumpulan_tugas untuk cek apakah siswa sudah kumpul atau belum
$query = "SELECT t.*, m.nama_mapel, pt.nilai, pt.tgl_upload as tgl_kumpul_siswa, pt.id_pengumpulan
          FROM tugas t
          JOIN mapel m ON t.mapel_id = m.id_mapel
          LEFT JOIN pengumpulan_tugas pt ON t.id_tugas = pt.tugas_id AND pt.siswa_id = '$id_siswa'
          WHERE m.kelas_id = '$id_kelas' $where_mapel
          ORDER BY t.tgl_kumpul DESC"; // Urutkan deadline terbaru/terlama

$q_tugas = mysqli_query($koneksi, $query);
?>

<style>
    .task-list-card {
        background: white; border-radius: 15px; padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03); margin-bottom: 20px;
        position: relative; transition: 0.3s; border: 1px solid #f0f0f0;
    }
    .task-list-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #FF8C00; }

    .status-badge {
        position: absolute; top: 20px; right: 20px;
        padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold;
    }
    .st-pending { background: #ffebee; color: #c62828; }
    .st-done    { background: #e8f5e9; color: #27ae60; }
    .st-graded  { background: #e3f2fd; color: #1565c0; }

    .task-meta { font-size: 12px; color: #777; display: flex; gap: 15px; margin-top: 10px; }
    .task-meta i { margin-right: 5px; }

    .btn-kerjakan {
        display: inline-block; margin-top: 15px;
        background: linear-gradient(135deg, #FF8C00, #F39C12); color: white;
        padding: 10px 20px; border-radius: 30px; text-decoration: none;
        font-weight: bold; font-size: 13px; box-shadow: 0 5px 10px rgba(255, 140, 0, 0.2);
        transition: 0.2s;
    }
    .btn-kerjakan:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(255, 140, 0, 0.3); }

    .btn-lihat {
        display: inline-block; margin-top: 15px;
        background: #f5f5f5; color: #555;
        padding: 10px 20px; border-radius: 30px; text-decoration: none;
        font-weight: bold; font-size: 13px;
        transition: 0.2s;
    }
    .btn-lihat:hover { background: #e0e0e0; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: #333;"><?php echo $judul_halaman; ?></h2>
            <p style="margin: 5px 0 0 0; color: #777;"><?php echo $sub_judul; ?></p>
        </div>
        
        <?php if(isset($_GET['mapel'])) { ?>
            <a href="mapel.php" style="background: #f5f5f5; color: #555; padding: 10px 15px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 13px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        <?php } ?>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
        <?php 
        if(mysqli_num_rows($q_tugas) > 0){
            while($t = mysqli_fetch_array($q_tugas)){
                
                // LOGIKA STATUS
                $deadline = strtotime($t['tgl_kumpul']);
                $now = time();
                
                $status_label = "";
                $status_class = "";
                $tombol = "";

                if(!empty($t['tgl_kumpul_siswa'])){
                    // SUDAH MENGUMPULKAN
                    if(!empty($t['nilai'])){
                        $status_label = "Nilai: " . $t['nilai'];
                        $status_class = "st-graded";
                    } else {
                        $status_label = "Sudah Dikumpul";
                        $status_class = "st-done";
                    }
                    $tombol = '<a href="tugas_detail.php?id='.$t['id_tugas'].'" class="btn-lihat"><i class="fas fa-eye"></i> Lihat Detail</a>';
                } else {
                    // BELUM MENGUMPULKAN
                    if($now > $deadline){
                        $status_label = "Terlambat";
                        $status_class = "st-pending"; // Merah
                        $tombol = '<a href="tugas_detail.php?id='.$t['id_tugas'].'" class="btn-kerjakan" style="background:#555;">Lihat Soal</a>';
                    } else {
                        $status_label = "Belum Dikerjakan";
                        $status_class = "st-pending";
                        $tombol = '<a href="tugas_detail.php?id='.$t['id_tugas'].'" class="btn-kerjakan">Kerjakan Sekarang</a>';
                    }
                }
        ?>
        <div class="task-list-card">
            <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
            
            <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #333; padding-right: 80px;">
                <?php echo $t['judul_tugas']; ?>
            </h3>
            
            <div style="font-size: 13px; color: #FF8C00; font-weight: bold; margin-bottom: 10px;">
                <?php echo $t['nama_mapel']; ?>
            </div>

            <div class="task-meta">
                <span><i class="far fa-clock"></i> Deadline: <?php echo date('d M Y, H:i', $deadline); ?></span>
            </div>
            
            <?php echo $tombol; ?>
        </div>
        <?php 
            }
        } else {
            echo "<div style='grid-column: 1/-1; text-align:center; padding:50px; background:white; border-radius:15px; color:#999;'>
                    <img src='../assets/img/completed.svg' style='width:100px; opacity:0.6; margin-bottom:15px;'>
                    <br>Tidak ada tugas saat ini.
                  </div>";
        }
        ?>
    </div>

</div>
<?php include 'footer.php'; ?>