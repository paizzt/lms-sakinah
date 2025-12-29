<?php include 'header.php'; ?>

<?php 
$id_tugas = $_GET['id'];

// Ambil info tugas & kelas
$q_tugas = mysqli_query($koneksi, "SELECT tugas.*, mapel.nama_mapel, kelas.nama_kelas, kelas.id_kelas 
                                   FROM tugas 
                                   JOIN mapel ON tugas.mapel_id = mapel.id_mapel 
                                   JOIN kelas ON mapel.kelas_id = kelas.id_kelas
                                   WHERE id_tugas='$id_tugas'");
$t = mysqli_fetch_array($q_tugas);
$id_kelas = $t['id_kelas'];
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #f83600, #f9d423); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-marker"></i> Penilaian Tugas</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">
        <?php echo $t['judul_tugas']; ?> (<?php echo $t['nama_kelas']; ?>)
    </p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th>Status Pengumpulan</th>
                <th>File Jawaban</th>
                <th width="15%">Nilai</th>
                <th width="20%">Komentar Guru</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Ambil semua siswa di kelas ini (LEFT JOIN dengan tabel pengumpulan agar siswa yang belum kumpul tetap muncul)
            $query = "SELECT users.nama_lengkap, users.id_user, users.foto_profil,
                             pengumpulan.id_pengumpulan, pengumpulan.file_siswa, pengumpulan.tanggal_kumpul, pengumpulan.nilai, pengumpulan.komentar_guru
                      FROM siswa_detail
                      JOIN users ON siswa_detail.user_id = users.id_user
                      LEFT JOIN pengumpulan ON users.id_user = pengumpulan.siswa_id AND pengumpulan.tugas_id='$id_tugas'
                      WHERE siswa_detail.kelas_id='$id_kelas'
                      ORDER BY users.nama_lengkap ASC";
            
            $result = mysqli_query($koneksi, $query);

            while($d = mysqli_fetch_array($result)){
                $sudah_kumpul = ($d['id_pengumpulan'] != NULL);
                $telat = false;
                if($sudah_kumpul){
                    $telat = ($d['tanggal_kumpul'] > $t['deadline']);
                }
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="../uploads/profil/<?php echo $d['foto_profil'] ? $d['foto_profil'] : 'default.jpg'; ?>" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                        <?php echo $d['nama_lengkap']; ?>
                    </div>
                </td>
                <td>
                    <?php if($sudah_kumpul){ ?>
                        <div style="font-size: 12px; color: #2e7d32;">
                            <i class="fas fa-check-circle"></i> Dikirim<br>
                            <small><?php echo date('d/m H:i', strtotime($d['tanggal_kumpul'])); ?></small>
                            <?php if($telat) echo "<span style='color:red; font-weight:bold;'> (Terlambat)</span>"; ?>
                        </div>
                    <?php } else { ?>
                        <span class="badge-status bg-danger">Belum Mengumpulkan</span>
                    <?php } ?>
                </td>
                <td>
                    <?php if($sudah_kumpul && $d['file_siswa']){ ?>
                        <a href="../uploads/tugas_siswa/<?php echo $d['file_siswa']; ?>" target="_blank" style="color: #007bff; font-weight: bold; text-decoration: none;">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    <?php } else { echo "-"; } ?>
                </td>

                <form action="tugas_nilai_update.php" method="POST">
                    <input type="hidden" name="id_tugas" value="<?php echo $id_tugas; ?>">
                    <input type="hidden" name="id_siswa" value="<?php echo $d['id_user']; ?>">
                    
                    <td>
                        <input type="number" name="nilai" class="form-control-modern" style="padding: 5px; text-align: center;" placeholder="0-100" value="<?php echo $d['nilai']; ?>" <?php if(!$sudah_kumpul) echo "disabled"; ?>>
                    </td>
                    <td>
                        <input type="text" name="komentar" class="form-control-modern" style="padding: 5px;" placeholder="Bagus..." value="<?php echo $d['komentar_guru']; ?>" <?php if(!$sudah_kumpul) echo "disabled"; ?>>
                    </td>
                    <td>
                        <?php if($sudah_kumpul){ ?>
                            <button type="submit" class="btn-action-small" style="background: #e3f2fd; color: #0d47a1; width: 100%; border: none; cursor: pointer;">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        <?php } else { echo "-"; } ?>
                    </td>
                </form>

            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <a href="tugas.php" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas</a>
</div>

<?php include 'footer.php'; ?>