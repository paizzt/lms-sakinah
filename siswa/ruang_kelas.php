<?php include 'header.php'; ?>

<?php
$id_mapel = $_GET['id'];
$id_siswa = $_SESSION['id_user'];

// Ambil Detail Mapel & Guru
$query_mapel = "SELECT mapel.*, users.nama_lengkap, users.foto_profil 
                FROM mapel 
                JOIN users ON mapel.guru_id = users.id_user 
                WHERE id_mapel='$id_mapel'";
$dm = mysqli_fetch_array(mysqli_query($koneksi, $query_mapel));

// Jika mapel tidak ditemukan
if(!$dm){
    echo "<script>alert('Mata pelajaran tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}
?>

<div class="welcome-banner" style="background: linear-gradient(to right, #4facfe, #00f2fe); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(0, 242, 254, 0.2); position: relative; overflow: hidden;">
    
    <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
        <img src="<?php echo $dm['foto_profil'] ? '../uploads/profil/'.$dm['foto_profil'] : '../assets/img/default.jpg'; ?>" 
             style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid white; object-fit: cover;">
        
        <div>
            <h4 style="margin: 0; opacity: 0.9; font-weight: normal;">Ruang Kelas</h4>
            <h1 style="margin: 5px 0; font-size: 28px;"><?php echo $dm['nama_mapel']; ?></h1>
            <div style="font-size: 14px; opacity: 0.9;">
                <i class="fas fa-chalkboard-teacher"></i> Pengajar: <b><?php echo $dm['nama_lengkap']; ?></b> 
                &nbsp;|&nbsp; 
                <span style="font-family: monospace; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px;">
                    Kode: <?php echo $dm['kode_mapel']; ?>
                </span>
            </div>
        </div>
    </div>
    
    <i class="fas fa-book-open" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; opacity: 0.1; transform: rotate(-15deg);"></i>
</div>

<?php 
$q_rps = mysqli_query($koneksi, "SELECT * FROM rps WHERE mapel_id='$id_mapel'");
if(mysqli_num_rows($q_rps) > 0){
    $rps = mysqli_fetch_array($q_rps);
?>
    <div style="background: #fff; padding: 20px; border-radius: 12px; border-left: 5px solid #4a00e0; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h4 style="margin: 0 0 5px 0; color: #333;"><i class="fas fa-book-reader" style="color: #4a00e0; margin-right: 10px;"></i> Rencana Pembelajaran Semester (RPS)</h4>
            <small style="color: #666;"><?php echo $rps['deskripsi'] ? $rps['deskripsi'] : "Panduan belajar semester ini."; ?></small>
        </div>
        <a href="../uploads/rps/<?php echo $rps['file_rps']; ?>" target="_blank" style="background: #4a00e0; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.3s;">
            <i class="fas fa-download"></i> Download File
        </a>
    </div>
<?php } ?>


<div style="display: flex; flex-wrap: wrap; gap: 30px;">
    
    <div style="flex: 1; min-width: 300px;">
        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; color: #444;">
            <i class="fas fa-file-alt" style="color: #FF8C00;"></i> Materi Belajar
        </h3>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php 
            $q_materi = mysqli_query($koneksi, "SELECT * FROM materi WHERE mapel_id='$id_mapel' ORDER BY tanggal_upload DESC");
            
            if(mysqli_num_rows($q_materi) == 0){
                echo "<div style='background: #fff; padding: 20px; border-radius: 10px; text-align: center; color: #888; border: 1px dashed #ccc;'>Belum ada materi yang diupload guru.</div>";
            }

            while($mat = mysqli_fetch_array($q_materi)){
                // Tentukan Ikon
                $icon = "fas fa-file"; 
                $bg_icon = "#eee";
                $color_icon = "#555";

                if($mat['link_materi'] != ""){
                    $icon = "fab fa-youtube"; $bg_icon = "#ffebee"; $color_icon = "#f44336"; // Merah
                } else if(strpos($mat['file_materi'], '.pdf')){
                    $icon = "fas fa-file-pdf"; $bg_icon = "#ffebee"; $color_icon = "#d32f2f"; // Merah PDF
                } else if(strpos($mat['file_materi'], '.doc')){
                    $icon = "fas fa-file-word"; $bg_icon = "#e3f2fd"; $color_icon = "#1976d2"; // Biru Word
                } else if(strpos($mat['file_materi'], '.ppt')){
                    $icon = "fas fa-file-powerpoint"; $bg_icon = "#fff3e0"; $color_icon = "#f57c00"; // Oranye PPT
                }
            ?>
            
            <div class="card-materi" style="background: white; padding: 15px; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); display: flex; gap: 15px; align-items: center; border: 1px solid #f9f9f9; transition: 0.3s;">
                
                <div style="width: 50px; height: 50px; background: <?php echo $bg_icon; ?>; color: <?php echo $color_icon; ?>; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="<?php echo $icon; ?>"></i>
                </div>

                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; font-size: 16px;">
                        <?php if($mat['link_materi'] != ""){ ?>
                            <a href="<?php echo $mat['link_materi']; ?>" target="_blank" style="text-decoration: none; color: #333;"><?php echo $mat['judul_materi']; ?></a>
                        <?php } else { ?>
                            <a href="../uploads/materi/<?php echo $mat['file_materi']; ?>" target="_blank" style="text-decoration: none; color: #333;"><?php echo $mat['judul_materi']; ?></a>
                        <?php } ?>
                    </h4>
                    <small style="color: #999; display: block;"><?php echo date('d M Y', strtotime($mat['tanggal_upload'])); ?></small>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;"><?php echo $mat['deskripsi']; ?></p>
                </div>

                <div>
                    <?php if($mat['link_materi'] != ""){ ?>
                        <a href="<?php echo $mat['link_materi']; ?>" target="_blank" class="btn-icon-small"><i class="fas fa-external-link-alt"></i></a>
                    <?php } else { ?>
                        <a href="../uploads/materi/<?php echo $mat['file_materi']; ?>" target="_blank" class="btn-icon-small"><i class="fas fa-download"></i></a>
                    <?php } ?>
                </div>

            </div>
            <?php } ?>
        </div>
    </div>


    <div style="flex: 1; min-width: 300px;">
        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; color: #444;">
            <i class="fas fa-clipboard-list" style="color: #11998e;"></i> Tugas & Evaluasi
        </h3>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php 
            $q_tugas = mysqli_query($koneksi, "SELECT * FROM tugas WHERE mapel_id='$id_mapel' ORDER BY deadline DESC");

            if(mysqli_num_rows($q_tugas) == 0){
                echo "<div style='background: #fff; padding: 20px; border-radius: 10px; text-align: center; color: #888; border: 1px dashed #ccc;'>Tidak ada tugas aktif saat ini.</div>";
            }

            while($t = mysqli_fetch_array($q_tugas)){
                // Cek Status Deadline
                $sekarang = date('Y-m-d H:i:s');
                $is_expired = ($sekarang > $t['deadline']);

                // Cek Apakah Siswa Sudah Mengumpulkan?
                $q_cek = mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE tugas_id='".$t['id_tugas']."' AND siswa_id='$id_siswa'");
                $data_kumpul = mysqli_fetch_array($q_cek);
                $sudah_kumpul = ($data_kumpul); // True jika ada data
            ?>

            <div class="card-tugas" style="background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #f0f0f0;">
                
                <div style="padding: 15px 20px; background: #fafafa; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: bold; color: #333;"><?php echo $t['judul_tugas']; ?></span>
                    <?php if($t['tipe'] == 'kuis'){ ?>
                        <span class="badge-status bg-warning">Kuis</span>
                    <?php } else { ?>
                        <span class="badge-status bg-info">Tugas</span>
                    <?php } ?>
                </div>

                <div style="padding: 20px;">
                    <p style="margin-top: 0; font-size: 14px; color: #555;"><?php echo $t['deskripsi']; ?></p>
                    
                    <?php if($t['file_tugas'] != "") { ?>
                        <a href="../uploads/tugas/<?php echo $t['file_tugas']; ?>" target="_blank" style="display: inline-block; background: #e3f2fd; color: #0d47a1; padding: 5px 10px; border-radius: 5px; font-size: 12px; margin-bottom: 10px; text-decoration: none;">
                            <i class="fas fa-paperclip"></i> Download Soal/Lampiran
                        </a>
                    <?php } ?>

                    <div style="margin: 10px 0; font-size: 13px; color: #777;">
                        <i class="far fa-clock"></i> Deadline: 
                        <b style="color: <?php echo $is_expired ? 'red' : 'green'; ?>;">
                            <?php echo date('d M Y, H:i', strtotime($t['deadline'])); ?>
                        </b>
                    </div>

                    <hr style="border: 0; border-top: 1px dashed #eee; margin: 15px 0;">

                    <?php if($sudah_kumpul) { ?>
                        
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; border: 1px solid #c8e6c9;">
                            <div style="color: #2e7d32; font-weight: bold; margin-bottom: 5px;">
                                <i class="fas fa-check-circle"></i> Sudah Dikumpulkan
                            </div>
                            <small style="color: #555;">Dikirim: <?php echo date('d M H:i', strtotime($data_kumpul['tanggal_kumpul'])); ?></small>
                            
                            <?php if($data_kumpul['file_siswa']) { ?>
                                <br><small>File: <a href="../uploads/tugas_siswa/<?php echo $data_kumpul['file_siswa']; ?>" target="_blank">Lihat File Saya</a></small>
                            <?php } ?>

                            <?php if($data_kumpul['nilai'] > 0) { ?>
                                <div style="margin-top: 10px; border-top: 1px solid #a5d6a7; padding-top: 10px;">
                                    <span style="font-size: 12px;">Nilai:</span>
                                    <span style="font-size: 20px; font-weight: bold; color: #2e7d32;"><?php echo $data_kumpul['nilai']; ?></span>
                                    
                                    <?php if($data_kumpul['komentar_guru']) { ?>
                                        <div style="font-style: italic; font-size: 13px; color: #555; margin-top: 5px;">
                                            "<?php echo $data_kumpul['komentar_guru']; ?>"
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <div style="margin-top: 5px; font-size: 12px; color: #888;">Menunggu penilaian guru.</div>
                            <?php } ?>
                        </div>

                    <?php } else { ?>
                        
                        <?php if($is_expired) { ?>
                            <div style="background: #ffebee; padding: 15px; border-radius: 8px; border: 1px solid #ffcdd2; color: #c62828; text-align: center;">
                                <i class="fas fa-times-circle"></i> Maaf, waktu pengumpulan sudah habis.
                            </div>
                        <?php } else { ?>
                            
                            <form action="kumpul_tugas_aksi.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="tugas_id" value="<?php echo $t['id_tugas']; ?>">
                                <input type="hidden" name="mapel_id" value="<?php echo $id_mapel; ?>">
                                
                                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Upload Jawaban Kamu:</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="file" name="file_siswa" class="form-control-modern" style="padding: 8px; font-size: 12px; background: #fff;" required>
                                    <button type="submit" class="btn-submit" style="width: auto; padding: 0 15px; font-size: 13px;">Kirim</button>
                                </div>
                                <small style="color: #999; font-size: 11px;">Format: PDF/Word/Gambar. Maks 5MB.</small>
                            </form>

                        <?php } ?>

                    <?php } ?>

                </div>
            </div>
            <?php } ?>
        </div>

    </div>

</div>

<style>
    .btn-icon-small {
        background: #f0f0f0; color: #555; width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.2s;
    }
    .btn-icon-small:hover { background: #333; color: white; }
    .card-materi:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
</style>

<?php include 'footer.php'; ?>