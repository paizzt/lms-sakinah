<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #28a745, #20c997); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(40, 167, 69, 0.2);">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-star"></i> Rekap Nilai Akademik</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau hasil belajar dan evaluasi tugasmu di sini.</p>
</div>

<div class="nilai-container">

    <?php 
    // 1. Ambil Daftar Mata Pelajaran di Kelas Siswa
    $query_mapel = mysqli_query($koneksi, "SELECT * FROM mapel WHERE kelas_id='$id_kelas_siswa'");

    if(mysqli_num_rows($query_mapel) == 0){
        echo "<p style='text-align:center; color:#777;'>Belum ada mata pelajaran.</p>";
    }

    while($m = mysqli_fetch_array($query_mapel)){
        $id_mapel = $m['id_mapel'];

        // 2. Hitung Rata-rata Nilai per Mapel
        // Ambil semua tugas di mapel ini, lalu cek nilai siswa
        $q_avg = "SELECT AVG(pengumpulan.nilai) as rata_rata 
                  FROM tugas 
                  JOIN pengumpulan ON tugas.id_tugas = pengumpulan.tugas_id 
                  WHERE tugas.mapel_id='$id_mapel' AND pengumpulan.siswa_id='$id_siswa' AND pengumpulan.nilai > 0";
        $d_avg = mysqli_fetch_array(mysqli_query($koneksi, $q_avg));
        $rata_rata = round($d_avg['rata_rata'], 1); // Bulatkan 1 angka belakang koma
    ?>

    <div class="card-nilai" style="background: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 25px; overflow: hidden; border: 1px solid #eee;">
        
        <div class="card-header-nilai" style="padding: 20px; background: #fcfcfc; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; color: #444; font-size: 18px;"><?php echo $m['nama_mapel']; ?></h3>
                <span style="font-size: 13px; color: #888;">Kode: <?php echo $m['kode_mapel']; ?></span>
            </div>
            
            <div style="text-align: right;">
                <span style="display: block; font-size: 12px; color: #666;">Rata-rata</span>
                <span style="font-size: 22px; font-weight: bold; color: <?php echo ($rata_rata >= 75) ? '#28a745' : '#dc3545'; ?>;">
                    <?php echo ($rata_rata > 0) ? $rata_rata : '-'; ?>
                </span>
            </div>
        </div>

        <div style="padding: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 10px; color: #555;">Tugas / Kuis</th>
                        <th style="padding: 10px; color: #555;">Status</th>
                        <th style="padding: 10px; color: #555;">Nilai</th>
                        <th style="padding: 10px; color: #555;">Feedback Guru</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // 3. Ambil Daftar Tugas di Mapel ini
                    $q_tugas = mysqli_query($koneksi, "SELECT * FROM tugas WHERE mapel_id='$id_mapel' ORDER BY id_tugas DESC");
                    
                    if(mysqli_num_rows($q_tugas) == 0){
                        echo "<tr><td colspan='4' style='padding:15px; text-align:center; color:#999;'>Belum ada tugas di mapel ini.</td></tr>";
                    }

                    while($t = mysqli_fetch_array($q_tugas)){
                        // Cek apakah siswa sudah mengumpulkan
                        $q_kumpul = mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE tugas_id='".$t['id_tugas']."' AND siswa_id='$id_siswa'");
                        $kumpul = mysqli_fetch_array($q_kumpul);
                        $sudah_kumpul = mysqli_num_rows($q_kumpul) > 0;
                    ?>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 15px 10px;">
                            <b><?php echo $t['judul_tugas']; ?></b> <br>
                            <small style="color: #999;">Deadline: <?php echo date('d M, H:i', strtotime($t['deadline'])); ?></small>
                        </td>
                        
                        <td style="padding: 10px;">
                            <?php if(!$sudah_kumpul) { ?>
                                <span style="background: #ffeeba; color: #856404; padding: 5px 10px; border-radius: 20px; font-size: 12px;">Belum Dikerjakan</span>
                            <?php } else { ?>
                                <span style="background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 12px;">Sudah Dikumpul</span>
                            <?php } ?>
                        </td>

                        <td style="padding: 10px;">
                            <?php 
                            if($sudah_kumpul && $kumpul['nilai'] > 0){
                                echo "<b style='font-size: 16px; color: #333;'>".$kumpul['nilai']."</b>";
                            } else if($sudah_kumpul && $kumpul['nilai'] == 0){
                                echo "<span style='color:#999; font-size:12px;'>Menunggu Penilaian</span>";
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>

                        <td style="padding: 10px; font-size: 13px; color: #666; font-style: italic;">
                            <?php 
                            if($sudah_kumpul && $kumpul['komentar_guru'] != ""){
                                echo '"'.$kumpul['komentar_guru'].'"';
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
    <?php } ?>

</div>

<?php include 'footer.php'; ?>