<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #667eea, #764ba2); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-user-check"></i> Absensi Siswa</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Pilih Mata Pelajaran untuk mulai mencatat kehadiran.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">

    <?php 
    $id_guru = $_SESSION['id_user'];
    
    // PERBAIKAN: Ambil Mapel milik guru ini (bukan sekedar ambil semua kelas)
    // Agar kita bisa dapat id_mapel dan id_kelas sekaligus
    $query_mapel = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                           FROM mapel 
                                           JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                           WHERE mapel.guru_id='$id_guru' 
                                           ORDER BY kelas.nama_kelas ASC");
    
    if(mysqli_num_rows($query_mapel) == 0){
        echo "<p>Anda belum memiliki jadwal mata pelajaran.</p>";
    }

    while($m = mysqli_fetch_array($query_mapel)){
        $id_kelas = $m['kelas_id'];
        $id_mapel = $m['id_mapel'];
        
        // Hitung jumlah siswa di kelas ini
        $q_jml = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa_detail WHERE kelas_id='$id_kelas'");
        $jml = mysqli_fetch_assoc($q_jml);
    ?>

    <div class="card-kelas" style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; border: 1px solid #f0f0f0; transition: 0.3s;">
        
        <div style="width: 60px; height: 60px; background: #eef2ff; color: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 15px auto;">
            <i class="fas fa-book-reader"></i>
        </div>

        <h3 style="margin: 0; color: #333; font-size: 18px;"><?php echo $m['nama_mapel']; ?></h3>
        <span class="badge-status bg-info" style="margin-top:5px; display:inline-block;"><?php echo $m['nama_kelas']; ?></span>
        
        <p style="color: #888; font-size: 14px; margin-top: 10px;"><?php echo $jml['total']; ?> Siswa</p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <a href="absensi_input.php?kelas=<?php echo $id_kelas; ?>&mapel=<?php echo $id_mapel; ?>" style="display: block; padding: 12px; background: linear-gradient(to right, #667eea, #764ba2); color: white; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); transition: 0.3s;">
            Buka Presensi <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <?php } ?>

</div>

<style>
    .card-kelas:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
</style>

<?php include 'footer.php'; ?>