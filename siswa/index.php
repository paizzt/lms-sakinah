<?php 
// Sertakan file koneksi dan struktur halaman
include 'header.php'; 
include 'sidebar.php'; 

// Pastikan user sudah login sebagai siswa
if($_SESSION['role'] != 'siswa'){
    echo "<script>window.location='../index.php';</script>";
    exit();
}

$id_siswa = $_SESSION['id_user'];

// --- PERBAIKAN UTAMA DI SINI ---
// Kita ambil data siswa terbaru dari database untuk mendapatkan kelas_id dan nama
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_siswa'");
$data_siswa = mysqli_fetch_array($query_user);

// Simpan ke variabel untuk dipakai di bawah
$nama_siswa = $data_siswa['nama_lengkap'];
$id_kelas   = $data_siswa['kelas_id'];

// Cek apakah siswa sudah punya kelas?
if(empty($id_kelas) || $id_kelas == 0){
    echo "<div class='content-body' style='margin-top: -20px;'>
            <div style='padding:40px; text-align:center; background:white; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.05);'>
                <img src='../assets/img/warning.svg' style='width:100px; opacity:0.7; margin-bottom:20px;'>
                <h3 style='color:#333;'>Anda Belum Masuk Kelas</h3>
                <p style='color:#777;'>Hubungi Guru atau Admin untuk memasukkan akun Anda ke dalam kelas.</p>
            </div>
          </div>";
    include 'footer.php';
    exit(); // Stop script sampai sini jika tidak punya kelas
}
// -------------------------------

// 1. HITUNG JUMLAH MAPEL (Sesuai Kelas Siswa)
$q_mapel = mysqli_query($koneksi, "SELECT * FROM mapel WHERE kelas_id='$id_kelas'");
$jml_mapel = mysqli_num_rows($q_mapel);

// 2. HITUNG TUGAS BELUM DIKERJAKAN
// Logika: Ambil tugas di kelas ini, yang deadline-nya belum lewat, DAN id_tugasnya belum ada di tabel pengumpulan_tugas milik siswa ini
$sekarang = date('Y-m-d H:i:s');
$q_tugas_pending = mysqli_query($koneksi, "SELECT t.*, m.nama_mapel FROM tugas t
                                           JOIN mapel m ON t.mapel_id = m.id_mapel
                                           WHERE m.kelas_id = '$id_kelas' 
                                           AND t.tgl_kumpul >= '$sekarang'
                                           AND t.id_tugas NOT IN (SELECT tugas_id FROM pengumpulan_tugas WHERE siswa_id='$id_siswa')
                                           ORDER BY t.tgl_kumpul ASC LIMIT 5");
$jml_tugas = mysqli_num_rows($q_tugas_pending);

// 3. HITUNG PERSENTASE KEHADIRAN
$q_hadir = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND status='H'");
$jml_hadir = mysqli_num_rows($q_hadir);

// 4. PENGUMUMAN TERBARU
$q_pengumuman = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tujuan IN ('semua','siswa') ORDER BY tanggal DESC LIMIT 3");
?>

<style>
    /* CUSTOM CARD GRADIENT */
    .card-stat {
        border-radius: 15px;
        color: white;
        padding: 25px;
        position: relative;
        overflow: hidden;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border: none;
    }
    .card-stat:hover { transform: translateY(-5px); }
    
    .bg-orange { background: linear-gradient(135deg, #FF8C00, #F39C12); }
    .bg-blue   { background: linear-gradient(135deg, #3498db, #2980b9); }
    .bg-green  { background: linear-gradient(135deg, #2ecc71, #27ae60); }
    
    .card-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        opacity: 0.2;
    }

    /* CARD LIST TUGAS */
    .task-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: 0.2s;
    }
    .task-item:last-child { border-bottom: none; }
    .task-item:hover { background: #fffcf5; }
    
    .task-date {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 10px;
        background: #ffebee;
        color: #c62828;
        font-weight: bold;
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="margin: 0; color: #333; font-weight: 800;">Hai, <?php echo $nama_siswa; ?>! 👋</h2>
            <p style="margin: 5px 0 0 0; color: #777;">Selamat belajar! Jangan lupa cek tugas yang harus dikumpulkan hari ini.</p>
        </div>
        <div style="text-align: right;">
            <span style="background: #FFF3E0; color: #E65100; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                <i class="fas fa-user-graduate"></i> SISWA
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 30px;">
        
        <div class="card-stat bg-orange">
            <h3 style="margin: 0; font-size: 36px; font-weight: 800;"><?php echo $jml_mapel; ?></h3>
            <span style="font-size: 14px; opacity: 0.9;">Mata Pelajaran</span>
            <i class="fas fa-book card-icon-bg"></i>
        </div>

        <div class="card-stat bg-blue">
            <h3 style="margin: 0; font-size: 36px; font-weight: 800;"><?php echo $jml_tugas; ?></h3>
            <span style="font-size: 14px; opacity: 0.9;">Tugas Belum Selesai</span>
            <i class="fas fa-tasks card-icon-bg"></i>
        </div>

        <div class="card-stat bg-green">
            <h3 style="margin: 0; font-size: 36px; font-weight: 800;"><?php echo $jml_hadir; ?></h3>
            <span style="font-size: 14px; opacity: 0.9;">Total Kehadiran</span>
            <i class="fas fa-user-check card-icon-bg"></i>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        
        <div class="modern-form-card" style="padding: 0; overflow: hidden; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
            <div style="padding: 20px; background: #fff; border-bottom: 2px solid #f9f9f9; display:flex; justify-content:space-between; align-items:center;">
                <h4 style="margin:0; color:#333;"><i class="fas fa-exclamation-circle" style="color: #E65100;"></i> Deadline Terdekat</h4>
                <a href="tugas.php" style="font-size:12px; text-decoration:none; color:#E65100; font-weight:bold;">Lihat Semua</a>
            </div>
            
            <div style="padding: 0;">
                <?php 
                if($jml_tugas > 0){
                    while($t = mysqli_fetch_array($q_tugas_pending)){
                        
                        // Hitung Sisa waktu
                        $deadline = strtotime($t['tgl_kumpul']);
                        $now = time();
                        $diff = $deadline - $now;
                        
                        // Jika sudah lewat (negatif), jangan tampilkan di sini atau beri tanda merah
                        if($diff < 0) {
                            $sisa_waktu = "Lewat Deadline";
                            $bg_date = "#ffebee"; $color_date = "#c62828";
                        } else {
                            $days = floor($diff / (60 * 60 * 24));
                            $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
                            
                            $sisa_waktu = ($days > 0) ? "$days hari lagi" : "$hours jam lagi";
                            $bg_date = "#e3f2fd"; $color_date = "#1565c0";
                        }
                ?>
                <div class="task-item">
                    <div>
                        <div style="font-weight: bold; color: #333; margin-bottom: 3px;"><?php echo $t['judul_tugas']; ?></div>
                        <div style="font-size: 12px; color: #777;">
                            <i class="fas fa-book"></i> <?php echo $t['nama_mapel']; ?>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span class="task-date" style="background: <?php echo $bg_date; ?>; color: <?php echo $color_date; ?>;">
                            <i class="far fa-clock"></i> <?php echo $sisa_waktu; ?>
                        </span>
                        <div style="margin-top: 5px;">
                            <a href="tugas_detail.php?id=<?php echo $t['id_tugas']; ?>" style="font-size: 11px; text-decoration: none; color: #2980b9; font-weight: bold;">Kerjakan <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<div style='padding:40px; text-align:center;'>
                            <img src='../assets/img/completed.svg' style='width:80px; opacity:0.6; margin-bottom:15px; display: block; margin-left: auto; margin-right: auto;'>
                            <p style='color:#999; margin:0;'>Hore! Tidak ada tugas pending.</p>
                          </div>";
                }
                ?>
            </div>
        </div>

        <div class="modern-form-card" style="padding: 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
            <h4 style="margin:0 0 20px 0; color:#333; border-bottom:2px solid #f9f9f9; padding-bottom:15px;"><i class="fas fa-bullhorn" style="color: #2980b9;"></i> Papan Informasi</h4>
            
            <?php 
            if(mysqli_num_rows($q_pengumuman) > 0){
                while($p = mysqli_fetch_array($q_pengumuman)){
            ?>
            <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #eee;">
                <div style="font-size: 11px; color: #999; margin-bottom: 5px;">
                    <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($p['tanggal'])); ?>
                </div>
                <h5 style="margin: 0 0 5px 0; font-size: 14px; color: #333;">
                    <a href="#" style="text-decoration: none; color: #333;"><?php echo $p['judul']; ?></a>
                </h5>
                <p style="margin: 0; font-size: 12px; color: #666; line-height: 1.5;">
                    <?php echo substr($p['isi'], 0, 80); ?>...
                </p>
            </div>
            <?php 
                }
            } else {
                echo "<p style='color:#999; text-align:center;'>Belum ada pengumuman.</p>";
            }
            ?>
        </div>

    </div>

</div>

<?php include 'footer.php'; ?>