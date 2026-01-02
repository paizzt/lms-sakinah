<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F4A460); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
    <h2 style="margin: 0; font-size: 28px;">Halo, <?php echo $_SESSION['nama_lengkap']; ?>! </h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Selamat datang di Ruang Belajar Digital. Cek jadwal dan tugasmu hari ini.</p>
</div>

<?php 
$query_info = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tujuan IN ('semua', 'siswa') ORDER BY tanggal_dibuat DESC LIMIT 1");
if(mysqli_num_rows($query_info) > 0){
    $info = mysqli_fetch_array($query_info);
?>
    <div style="background-color: #fff; border-left: 5px solid #28a745; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; align-items: start; gap: 15px;">
        <div style="font-size: 24px; color: #28a745;"><i class="fas fa-bullhorn"></i></div>
        <div>
            <h4 style="margin: 0 0 5px 0; color: #333;">Pengumuman Terbaru: <?php echo $info['judul']; ?></h4>
            <p style="margin: 0; color: #666; font-size: 14px;"><?php echo $info['isi']; ?></p>
            <small style="color: #999; margin-top: 5px; display: block;">Diposting: <?php echo date('d M Y', strtotime($info['tanggal_dibuat'])); ?></small>
        </div>
    </div>
<?php } ?>

<h3 style="margin-bottom: 20px; border-left: 5px solid #FF8C00; padding-left: 10px; color: #444;">📚 Mata Pelajaran Saya</h3>

<div class="mapel-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
    
    <?php 
    // Query mengambil mapel siswa + info guru + jadwal
    // Diurutkan berdasarkan Hari dan Jam
    $query_mapel = "SELECT mapel.*, users.nama_lengkap 
                    FROM mapel 
                    JOIN users ON mapel.guru_id = users.id_user 
                    WHERE mapel.kelas_id='$id_kelas_siswa'
                    ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), jam_mulai ASC";
    
    $mapel = mysqli_query($koneksi, $query_mapel);
    
    if(mysqli_num_rows($mapel) == 0){
        echo "<p style='grid-column: 1/-1; text-align: center; color: #777;'>Belum ada mata pelajaran yang dijadwalkan.</p>";
    }
    
    while($m = mysqli_fetch_array($mapel)){
    ?>
    
    <div class="card-mapel" style="background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s; border: 1px solid #eee; display: flex; flex-direction: column;">
        
        <div style="background-color: #fcfcfc; padding: 20px; border-bottom: 1px solid #f0f0f0;">
            <h4 style="margin: 0; color: #FF8C00; font-size: 18px;"><?php echo $m['nama_mapel']; ?></h4>
            <div style="margin-top: 10px; font-size: 13px; color: #666; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-chalkboard-teacher"></i> <?php echo $m['nama_lengkap']; ?>
            </div>
        </div>

        <div style="padding: 20px; flex: 1;">
            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; font-size: 13px; color: #555;">
                <?php if($m['hari']) { ?>
                    <div style="margin-bottom: 5px;"><i class="far fa-calendar-alt" style="width: 20px;"></i> <b><?php echo $m['hari']; ?></b></div>
                    <div><i class="far fa-clock" style="width: 20px;"></i> <?php echo substr($m['jam_mulai'],0,5) . " - " . substr($m['jam_selesai'],0,5); ?></div>
                <?php } else { ?>
                    <span style="color: #999;"><i>Jadwal belum diatur</i></span>
                <?php } ?>
            </div>
        </div>

        <div style="padding: 20px; padding-top: 0;">
            <a href="ruang_kelas.php?id=<?php echo $m['id_mapel']; ?>" class="btn-masuk" style="display: block; width: 100%; text-align: center; background: #FF8C00; color: white; padding: 10px 0; border-radius: 50px; text-decoration: none; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 10px rgba(255, 140, 0, 0.2);">
                Masuk Kelas <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
            </a>
        </div>

    </div>
    <?php } ?>

</div>

<style>
    .card-mapel:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .btn-masuk:hover {
        background: #e67e00 !important;
        transform: scale(1.02);
    }
</style>

<?php include 'footer.php'; ?>