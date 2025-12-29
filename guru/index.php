<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #11998e, #38ef7d); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(56, 239, 125, 0.2);">
    <h1 style="margin: 0; font-size: 28px;">Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h1>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Selamat beraktivitas di E-Learning SMAIT As-Sakinah.</p>
</div>

<?php
$id_guru = $_SESSION['id_user'];
// Hitung Statistik
$jml_mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mapel WHERE guru_id='$id_guru'"));
$jml_materi = mysqli_num_rows(mysqli_query($koneksi, "SELECT materi.* FROM materi JOIN mapel ON materi.mapel_id=mapel.id_mapel WHERE mapel.guru_id='$id_guru'"));
$jml_tugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT tugas.* FROM tugas JOIN mapel ON tugas.mapel_id=mapel.id_mapel WHERE mapel.guru_id='$id_guru'"));
?>

<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
    <div style="flex: 1; min-width: 200px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 5px solid #11998e;">
        <div style="font-size: 30px; font-weight: bold; color: #333;"><?php echo $jml_mapel; ?></div>
        <div style="color: #888;">Mata Pelajaran</div>
    </div>
    <div style="flex: 1; min-width: 200px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 5px solid #38ef7d;">
        <div style="font-size: 30px; font-weight: bold; color: #333;"><?php echo $jml_materi; ?></div>
        <div style="color: #888;">Materi Diupload</div>
    </div>
    <div style="flex: 1; min-width: 200px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 5px solid #f9d423;">
        <div style="font-size: 30px; font-weight: bold; color: #333;"><?php echo $jml_tugas; ?></div>
        <div style="color: #888;">Tugas / Kuis</div>
    </div>
</div>

<div class="modern-form-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 20px; background: #fcfcfc; border-bottom: 1px solid #eee;">
        <h3 style="margin: 0; font-size: 18px; color: #333;"><i class="far fa-calendar-alt"></i> Jadwal Mengajar Anda</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Urutkan jadwal: Hari ini dulu, baru hari lain
                // Trik order by FIELD hari
                $hari_ini = date('N'); // 1 (Senin) - 7 (Minggu)
                // Mapping nama hari Inggris ke Indo utk sorting manual agak ribet di SQL murni tanpa fungsi FIELD
                // Kita tampilkan semua jadwal saja urut Hari Senin-Sabtu
                
                $q_jadwal = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                                    FROM mapel 
                                                    JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                                    WHERE mapel.guru_id='$id_guru' 
                                                    ORDER BY FIELD(mapel.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), mapel.jam_mulai ASC");
                
                if(mysqli_num_rows($q_jadwal) == 0){
                    echo "<tr><td colspan='4' style='text-align:center;'>Belum ada jadwal.</td></tr>";
                }

                while($j = mysqli_fetch_array($q_jadwal)){
                    // Highlight Hari Ini
                    $indo_days = ['Sunday'=>'Minggu', 'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 'Friday'=>'Jumat', 'Saturday'=>'Sabtu'];
                    $hari_skrg = $indo_days[date('l')];
                    $highlight = ($j['hari'] == $hari_skrg) ? "background:#e0f2f1;" : "";
                ?>
                <tr style="<?php echo $highlight; ?>">
                    <td>
                        <?php if($j['hari'] == $hari_skrg) echo "<span class='badge-status bg-success'>HARI INI</span> "; ?>
                        <b><?php echo $j['hari']; ?></b>
                    </td>
                    <td><?php echo substr($j['jam_mulai'],0,5) . " - " . substr($j['jam_selesai'],0,5); ?></td>
                    <td><?php echo $j['nama_mapel']; ?></td>
                    <td><span class="badge-status bg-info"><?php echo $j['nama_kelas']; ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>