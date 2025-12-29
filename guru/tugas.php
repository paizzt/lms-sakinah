<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #f83600, #f9d423); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(249, 212, 35, 0.3); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-clipboard-list"></i> Manajemen Tugas & Kuis</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Buat evaluasi dan pantau pengumpulan tugas siswa.</p>
    </div>
    
    <a href="tugas_tambah.php" class="btn-add" style="background: white; color: #d35400; padding: 10px 20px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Buat Tugas Baru
    </a>
</div>

<div class="materi-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">

    <?php 
    $id_guru = $_SESSION['id_user'];
    // Ambil tugas milik guru ini
    $query = "SELECT tugas.*, mapel.nama_mapel, mapel.kode_mapel, kelas.nama_kelas 
              FROM tugas 
              JOIN mapel ON tugas.mapel_id = mapel.id_mapel 
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas
              WHERE mapel.guru_id='$id_guru' 
              ORDER BY tugas.deadline DESC";
    
    $result = mysqli_query($koneksi, $query);

    if(mysqli_num_rows($result) == 0){
        echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 15px; color: #888;'>
                <i class='fas fa-clipboard-check' style='font-size: 50px; margin-bottom: 15px; opacity: 0.3;'></i>
                <p>Belum ada tugas atau kuis yang dibuat.</p>
              </div>";
    }

    while($d = mysqli_fetch_array($result)){
        // Cek Status Deadline
        $sekarang = date('Y-m-d H:i:s');
        $deadline = $d['deadline'];
        $is_expired = ($sekarang > $deadline);
        
        // Tentukan Badge & Ikon
        if($d['tipe'] == 'kuis'){
            $tipe_label = "Kuis Online";
            $tipe_bg = "bg-warning";
            $icon_utama = "fas fa-stopwatch";
            $warna_icon = "#f39c12";
        } else {
            $tipe_label = "Tugas Upload";
            $tipe_bg = "bg-info";
            $icon_utama = "fas fa-file-upload";
            $warna_icon = "#0984e3";
        }
    ?>

    <div class="card-materi" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; display: flex; flex-direction: column;">
        
        <div style="padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <span class="badge-status <?php echo $tipe_bg; ?>"><?php echo $tipe_label; ?></span>
            
            <?php if($is_expired) { ?>
                <span class="badge-status bg-danger">Ditutup</span>
            <?php } else { ?>
                <span class="badge-status bg-success">Aktif</span>
            <?php } ?>
        </div>

        <div style="padding: 20px; flex: 1; position: relative;">
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="width: 50px; height: 50px; background: #fdfdfd; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee;">
                    <i class="<?php echo $icon_utama; ?>" style="font-size: 24px; color: <?php echo $warna_icon; ?>;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 16px; color: #333; line-height: 1.4;"><?php echo $d['judul_tugas']; ?></h4>
                    <small style="color: #888;"><?php echo $d['nama_mapel']; ?> - <?php echo $d['nama_kelas']; ?></small>
                </div>
            </div>
            
            <div style="background: #fafafa; padding: 10px; border-radius: 8px; font-size: 13px;">
                <div style="margin-bottom: 5px; color: #555;">
                    <i class="far fa-clock <?php echo !$is_expired ? 'deadline-active' : ''; ?>"></i> Deadline:
                </div>
                <b style="color: <?php echo $is_expired ? '#d63031' : '#333'; ?>;">
                    <?php echo date('d M Y, H:i', strtotime($deadline)); ?>
                </b>
            </div>
        </div>

        <div style="padding: 15px 20px; border-top: 1px solid #f0f0f0; display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px;">
            
            <a href="tugas_nilai.php?id=<?php echo $d['id_tugas']; ?>" style="grid-column: span 2; background: #f0f0f0; color: #333; text-align: center; padding: 8px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s;" class="btn-hover-gray">
                <i class="fas fa-users"></i> Lihat Pengumpulan
            </a>

            <a href="tugas_hapus.php?id=<?php echo $d['id_tugas']; ?>" onclick="return confirm('Hapus tugas ini? Data pengumpulan siswa juga akan terhapus.')" style="background: #ffe5e5; color: #d63031; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; width: 40px;">
                <i class="fas fa-trash"></i>
            </a>

        </div>

    </div>
    <?php } ?>

</div>

<style>
    .card-materi:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; transition: 0.3s; }
    .btn-hover-gray:hover { background: #e0e0e0 !important; }
</style>

<?php include 'footer.php'; ?>