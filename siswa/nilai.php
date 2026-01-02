<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- PERBAIKAN ERROR DI SINI ---
// Kita harus mendefinisikan $id_siswa dari session user yang login
$id_siswa = $_SESSION['id_user'];

// Ambil data siswa untuk menampilkan kelas/nama di atas (Opsional)
$q_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, siswa_detail.nis, kelas.nama_kelas 
                                   FROM users 
                                   JOIN siswa_detail ON users.id_user = siswa_detail.user_id 
                                   LEFT JOIN kelas ON siswa_detail.kelas_id = kelas.id_kelas
                                   WHERE users.id_user='$id_siswa'");
$d_siswa = mysqli_fetch_array($q_siswa);
?>

<div class="content-body" style="margin-top: -20px;">
    
    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-star"></i> Kartu Hasil Studi</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">
                    Nama: <b><?php echo $d_siswa['nama_lengkap']; ?></b> | 
                    Kelas: <b><?php echo isset($d_siswa['nama_kelas']) ? $d_siswa['nama_kelas'] : '-'; ?></b>
                </p>
            </div>
            <div style="font-size: 30px; opacity: 0.3;">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden;">
        <div class="form-header" style="padding: 20px; border-bottom: 1px solid #eee;">
            <h3 style="margin: 0; color: #333; font-size: 18px;">
                <i class="fas fa-list-alt" style="color: #FF8C00;"></i> Daftar Nilai Mata Pelajaran
            </h3>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">No</th>
                        <th style="padding: 15px; text-align: left;">Mata Pelajaran</th>
                        <th style="padding: 15px; text-align: center;">Tugas</th>
                        <th style="padding: 15px; text-align: center;">UH</th>
                        <th style="padding: 15px; text-align: center;">UTS</th>
                        <th style="padding: 15px; text-align: center;">UAS</th>
                        <th style="padding: 15px; text-align: center;">Rata-Rata</th>
                        <th style="padding: 15px; text-align: center;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Query mengambil nilai berdasarkan siswa yang login
                    // JOIN dengan tabel mapel untuk mengambil nama mata pelajaran
                    // Pastikan nama kolom 'siswa_id' dan 'mapel_id' sesuai dengan tabel 'nilai' Anda
                    $no = 1;
                    $query_nilai = mysqli_query($koneksi, "SELECT nilai.*, mapel.nama_mapel 
                                                           FROM nilai 
                                                           JOIN mapel ON nilai.mapel_id = mapel.id_mapel 
                                                           WHERE nilai.siswa_id='$id_siswa' 
                                                           ORDER BY mapel.nama_mapel ASC");

                    if(mysqli_num_rows($query_nilai) > 0){
                        while($n = mysqli_fetch_array($query_nilai)){
                            
                            // Hitung Rata-rata (Contoh sederhana: (Tugas+UH+UTS+UAS)/4)
                            // Sesuaikan rumus jika di database sudah ada kolom rata-rata
                            $tugas = $n['nilai_tugas'];
                            $uh    = $n['nilai_uh'];
                            $uts   = $n['nilai_uts'];
                            $uas   = $n['nilai_uas'];
                            
                            $rata  = ($tugas + $uh + $uts + $uas) / 4;
                            $rata  = number_format($rata, 1); // Ambil 1 desimal

                            // Tentukan Predikat
                            if($rata >= 90) $grade = 'A';
                            elseif($rata >= 80) $grade = 'B';
                            elseif($rata >= 70) $grade = 'C';
                            elseif($rata >= 60) $grade = 'D';
                            else $grade = 'E';

                            // Warna Badge Predikat
                            $badge_color = ($grade == 'A' || $grade == 'B') ? '#2ecc71' : (($grade == 'C') ? '#f1c40f' : '#e74c3c');
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; text-align: center; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: 500; color: #333;"><?php echo $n['nama_mapel']; ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $tugas; ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $uh; ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $uts; ?></td>
                        <td style="padding: 15px; text-align: center;"><?php echo $uas; ?></td>
                        <td style="padding: 15px; text-align: center; font-weight: bold; color: #333;"><?php echo $rata; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: <?php echo $badge_color; ?>; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold;">
                                <?php echo $grade; ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="8" style="padding: 30px; text-align: center; color: #999; font-style: italic;">
                            Belum ada data nilai yang diinputkan oleh guru.
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>