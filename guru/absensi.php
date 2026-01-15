<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_guru = $_SESSION['id_user'];
$tanggal_hari_ini = date('Y-m-d');

// Hitung total kelas yang diajar
$q_total = mysqli_query($koneksi, "SELECT count(*) as total FROM mapel WHERE guru_id='$id_guru'");
$d_total = mysqli_fetch_assoc($q_total);
$jml_kelas = $d_total['total'];
?>

<style>
    /* Animasi Fade In */
    .fade-in { animation: fadeIn 0.8s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Grid Layout */
    .class-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    /* Card Design */
    .class-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #eee;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .class-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.15);
        border-color: #dbeafe;
    }

    /* Header Card (Warna Kelas) */
    .card-top {
        padding: 20px;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .class-badge {
        background: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        color: #667eea;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Body Card */
    .card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .class-title {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: #333;
    }
    .mapel-title {
        margin: 5px 0 15px 0;
        font-size: 14px;
        color: #777;
        font-weight: 500;
    }

    /* Footer Card (Status & Tombol) */
    .card-footer {
        padding: 15px 20px;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Status Badge */
    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-done { background: #d1f2eb; color: #0e6251; }
    .status-pending { background: #fadbd8; color: #922b21; }

    /* Tombol Aksi */
    .btn-buka {
        background: #667eea;
        color: white;
        text-decoration: none;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    .btn-buka:hover { background: #5a67d8; transform: scale(1.05); }

</style>

<div class="content-body fade-in" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 30px; border-radius: 20px; margin-bottom: 35px; box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <h2 style="margin: 0; font-size: 28px; font-weight: 800;"><i class="fas fa-chalkboard-teacher"></i> Absensi Siswa</h2>
            <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 15px;">Pilih kelas di bawah ini untuk mulai mengisi daftar hadir hari ini.</p>
        </div>
        <i class="fas fa-users" style="position: absolute; right: 20px; bottom: -20px; font-size: 120px; opacity: 0.15; transform: rotate(-10deg);"></i>
    </div>

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h4 style="margin: 0; color: #444; font-weight: 700;">Daftar Kelas Ajar Anda</h4>
            <span style="font-size: 13px; color: #888;">Total: <?php echo $jml_kelas; ?> Kelas</span>
        </div>
        <div style="font-size: 13px; color: #666; background: white; padding: 5px 15px; border-radius: 20px; border: 1px solid #eee; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <i class="far fa-calendar-alt"></i> Hari ini: <b><?php echo date('d F Y'); ?></b>
        </div>
    </div>

    <div class="class-grid">
        
        <?php 
        // Query: Mengambil Mapel & Kelas yang diajar oleh Guru yang sedang login
        $query = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                         FROM mapel 
                                         JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                         WHERE mapel.guru_id='$id_guru' 
                                         ORDER BY kelas.nama_kelas ASC");
        
        if(mysqli_num_rows($query) > 0){
            while($d = mysqli_fetch_array($query)){
                
                // Cek Status Absen Hari Ini
                $id_mapel = $d['id_mapel'];
                $q_cek = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM absensi WHERE mapel_id='$id_mapel' AND tanggal='$tanggal_hari_ini'");
                $cek = mysqli_fetch_assoc($q_cek);
                $sudah_absen = ($cek['jumlah'] > 0);
        ?>
        
        <div class="class-card">
            <div class="card-top">
                <div class="class-badge"><i class="fas fa-hashtag"></i> <?php echo $d['kode_mapel'] ? $d['kode_mapel'] : 'MPL'; ?></div>
                <i class="fas fa-book-reader" style="color: #cbd5e0; font-size: 24px;"></i>
            </div>
            
            <div class="card-body">
                <h3 class="class-title"><?php echo $d['nama_kelas']; ?></h3>
                <p class="mapel-title"><?php echo $d['nama_mapel']; ?></p>
                
                <?php if($d['hari'] && $d['jam_mulai']) { ?>
                <div style="font-size: 12px; color: #888; display: flex; align-items: center; gap: 5px;">
                    <i class="far fa-clock"></i> <?php echo $d['hari']; ?>, <?php echo date('H:i', strtotime($d['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($d['jam_selesai'])); ?>
                </div>
                <?php } ?>
            </div>

            <div class="card-footer">
                <?php if($sudah_absen) { ?>
                    <div class="status-badge status-done" title="Absensi hari ini sudah diisi">
                        <i class="fas fa-check-circle"></i> Sudah Absen
                    </div>
                <?php } else { ?>
                    <div class="status-badge status-pending" title="Belum mengisi absensi hari ini">
                        <i class="fas fa-exclamation-circle"></i> Belum Absen
                    </div>
                <?php } ?>

                <a href="absensi_input.php?kelas=<?php echo $d['kelas_id']; ?>&mapel=<?php echo $d['id_mapel']; ?>" class="btn-buka">
                    Buka <i class="fas fa-arrow-right" style="font-size: 10px; margin-left: 3px;"></i>
                </a>
            </div>
        </div>

        <?php 
            }
        } else {
            echo "<div style='grid-column: 1/-1; text-align:center; padding: 50px; background: white; border-radius: 15px; color: #999;'>
                    <img src='../assets/img/empty.svg' style='width: 100px; opacity: 0.5; margin-bottom: 15px;'>
                    <p>Anda belum memiliki jadwal kelas mengajar.</p>
                  </div>";
        }
        ?>

    </div>

</div>

<?php include 'footer.php'; ?>