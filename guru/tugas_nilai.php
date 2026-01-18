<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_tugas = $_GET['id'];

// 1. AMBIL DETAIL TUGAS
$q_info = mysqli_query($koneksi, "SELECT tugas.*, mapel.nama_mapel, kelas.nama_kelas, kelas.id_kelas 
                                  FROM tugas 
                                  JOIN mapel ON tugas.mapel_id = mapel.id_mapel 
                                  JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                  WHERE id_tugas='$id_tugas'");
$info = mysqli_fetch_assoc($q_info);
$id_kelas = $info['id_kelas'];

// 2. AMBIL DAFTAR SISWA & DATA PENGUMPULAN (LEFT JOIN)
// Kita ambil SEMUA siswa di kelas tersebut, lalu gabungkan dengan tabel pengumpulan_tugas
// Agar siswa yang BELUM mengumpulkan tetap muncul di daftar.
$q_siswa = mysqli_query($koneksi, "SELECT users.id_user, users.nama_lengkap, users.foto_profil,
                                          pt.id_pengumpulan, pt.file_tugas, pt.tgl_upload, pt.nilai, pt.catatan_guru
                                   FROM users 
                                   LEFT JOIN pengumpulan_tugas pt ON users.id_user = pt.siswa_id AND pt.tugas_id = '$id_tugas'
                                   WHERE users.kelas_id = '$id_kelas' AND users.role = 'siswa'
                                   ORDER BY users.nama_lengkap ASC");
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Styling Card Info Tugas */
    .info-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px; border-left: 5px solid #FF8C00; }
    .info-title { font-size: 20px; font-weight: bold; color: #333; margin-bottom: 10px; }
    .info-meta { font-size: 13px; color: #666; display: flex; gap: 20px; flex-wrap: wrap; }
    .info-meta span { display: flex; align-items: center; gap: 5px; }
    
    /* Styling Table Input */
    .input-nilai { width: 70px; padding: 8px; border: 1px solid #ddd; border-radius: 5px; text-align: center; font-weight: bold; outline: none; transition: 0.3s; }
    .input-nilai:focus { border-color: #FF8C00; background: #fff8e1; }
    .input-ket { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-size: 12px; outline: none; transition: 0.3s; }
    .input-ket:focus { border-color: #FF8C00; }
    
    .btn-save-row { background: #27ae60; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; font-weight: bold; display: flex; align-items: center; gap: 5px; transition: 0.3s; }
    .btn-save-row:hover { background: #219150; transform: translateY(-2px); }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="info-card">
        <div style="display:flex; justify-content:space-between; align-items:start;">
            <div>
                <div class="info-title"><?php echo $info['judul_tugas']; ?></div>
                <div class="info-meta">
                    <span><i class="fas fa-book" style="color:#FF8C00;"></i> <?php echo $info['nama_mapel']; ?></span>
                    <span><i class="fas fa-users" style="color:#FF8C00;"></i> <?php echo $info['nama_kelas']; ?></span>
                    <span><i class="far fa-clock" style="color:#c62828;"></i> Deadline: <?php echo date('d M Y, H:i', strtotime($info['tgl_kumpul'])); ?></span>
                </div>
                <p style="margin-top: 15px; color: #555; font-size: 14px; line-height: 1.6; border-top: 1px solid #eee; padding-top: 10px;">
                    <?php echo nl2br($info['deskripsi']); ?>
                </p>
                <?php if($info['tipe'] == 'file' && !empty($info['file_url'])) { ?>
                    <a href="../uploads/tugas_guru/<?php echo $info['file_url']; ?>" target="_blank" style="display:inline-block; margin-top:10px; background:#e3f2fd; color:#1565c0; padding:5px 10px; border-radius:5px; text-decoration:none; font-size:12px; font-weight:bold;">
                        <i class="fas fa-download"></i> Download Soal
                    </a>
                <?php } elseif($info['tipe'] == 'link') { ?>
                    <a href="<?php echo $info['file_url']; ?>" target="_blank" style="display:inline-block; margin-top:10px; background:#fff3e0; color:#e65100; padding:5px 10px; border-radius:5px; text-decoration:none; font-size:12px; font-weight:bold;">
                        <i class="fas fa-link"></i> Buka Link Soal
                    </a>
                <?php } ?>
            </div>
            <a href="tugas.php" style="background:#eee; color:#333; padding:8px 15px; border-radius:20px; text-decoration:none; font-size:12px; font-weight:bold;"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee;">
            <h4 style="margin: 0; color: #555;">Daftar Pengumpulan & Penilaian</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Nama Siswa</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: center;">File Jawaban</th>
                        <th style="padding: 15px; text-align: center; width: 100px;">Nilai</th>
                        <th style="padding: 15px; width: 25%;">Keterangan</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($q_siswa) > 0){
                        while($s = mysqli_fetch_array($q_siswa)){
                            // LOGIKA STATUS
                            $status_kumpul = "";
                            $bg_status = "";
                            $file_link = "-";
                            $deadline = $info['tgl_kumpul'];

                            if(!empty($s['file_tugas'])){
                                // Sudah Kumpul
                                $file_link = '<a href="../uploads/tugas_siswa/'.$s['file_tugas'].'" target="_blank" style="text-decoration:none; background:#e3f2fd; color:#1565c0; padding:5px 10px; border-radius:15px; font-size:11px; font-weight:bold;"><i class="fas fa-download"></i> Unduh</a>';
                                
                                if($s['tgl_upload'] > $deadline){
                                    $status_kumpul = "Terlambat";
                                    $bg_status = "#ffebee; color:#c62828;"; // Merah
                                } else {
                                    $status_kumpul = "Tepat Waktu";
                                    $bg_status = "#e8f5e9; color:#2e7d32;"; // Hijau
                                }
                            } else {
                                // Belum Kumpul
                                $status_kumpul = "Belum Mengumpulkan";
                                $bg_status = "#f5f5f5; color:#999;"; // Abu
                            }
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <form action="tugas_nilai_aksi.php" method="POST">
                            <input type="hidden" name="id_tugas" value="<?php echo $id_tugas; ?>">
                            <input type="hidden" name="id_siswa" value="<?php echo $s['id_user']; ?>">
                            
                            <td style="padding: 15px; color: #777; text-align: center;"><?php echo $no++; ?></td>
                            
                            <td style="padding: 15px; font-weight: 600; color: #333;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php 
                                    $foto = ($s['foto_profil'] && $s['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$s['foto_profil'] : "../assets/img/avatar-default.svg"; 
                                    ?>
                                    <img src="<?php echo $foto; ?>" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                    <?php echo $s['nama_lengkap']; ?>
                                </div>
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                <span style="background: <?php echo $bg_status; ?> padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                    <?php echo $status_kumpul; ?>
                                </span>
                                <?php if(!empty($s['tgl_upload'])) { ?>
                                    <div style="font-size: 10px; color: #999; margin-top: 3px;">
                                        <?php echo date('d/m H:i', strtotime($s['tgl_upload'])); ?>
                                    </div>
                                <?php } ?>
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                <?php echo $file_link; ?>
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                <input type="number" name="nilai" class="input-nilai" value="<?php echo $s['nilai']; ?>" min="0" max="100" placeholder="0">
                            </td>

                            <td style="padding: 15px;">
                                <input type="text" name="catatan" class="input-ket" value="<?php echo $s['catatan_guru']; ?>" placeholder="Tulis masukan...">
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                <button type="submit" class="btn-save-row">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </td>
                        </form>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:#999;'>Tidak ada siswa di kelas ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    <?php if(isset($_SESSION['notif_status'])) { ?>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
        });
        Toast.fire({
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            title: '<?php echo $_SESSION['notif_pesan']; ?>'
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>