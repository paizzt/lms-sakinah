<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_siswa = $_SESSION['id_user'];
$id_tugas = $_GET['id'];

// 1. AMBIL DETAIL TUGAS (SOAL DARI GURU)
$q_tugas = mysqli_query($koneksi, "SELECT t.*, m.nama_mapel, u.nama_lengkap AS nama_guru
                                   FROM tugas t
                                   JOIN mapel m ON t.mapel_id = m.id_mapel
                                   JOIN users u ON m.guru_id = u.id_user
                                   WHERE t.id_tugas='$id_tugas'");
$t = mysqli_fetch_array($q_tugas);

// 2. AMBIL DATA PENGUMPULAN SISWA (JAWABAN)
$q_submission = mysqli_query($koneksi, "SELECT * FROM pengumpulan_tugas WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'");
$s = mysqli_fetch_array($q_submission);

// Cek Status Deadline
$now = time();
$deadline = strtotime($t['tgl_kumpul']);
$is_late = ($now > $deadline);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* ... style lainnya ... */

    /* STYLING AREA UPLOAD YANG RAPI */
    .upload-container {
        margin-top: 20px;
    }
    
    .upload-label-text {
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        display: block;
        font-size: 14px;
    }

    .upload-area {
        display: flex;                 /* Menggunakan Flexbox agar rata tengah */
        flex-direction: column;        /* Susunan vertikal (Icon atas, teks bawah) */
        align-items: center;           /* Rata tengah horizontal */
        justify-content: center;       /* Rata tengah vertikal */
        border: 2px dashed #ccc;       /* Garis putus-putus */
        border-radius: 15px;           /* Sudut membulat */
        padding: 40px 20px;            /* Ruang lega di dalam */
        background: #fdfdfd;           /* Warna background cerah */
        cursor: pointer;               /* Kursor jadi tangan saat diarahkan */
        transition: all 0.3s ease;
        min-height: 150px;             /* Tinggi minimal agar tidak gepeng */
    }

    .upload-area:hover {
        border-color: #FF8C00;         /* Warna oranye saat hover */
        background: #FFF3E0;           /* Background oranye muda saat hover */
        transform: translateY(-2px);   /* Efek naik sedikit */
    }

    .upload-icon {
        font-size: 40px;
        color: #FF8C00;
        margin-bottom: 15px;
    }

    .upload-text-main {
        font-weight: 700;
        color: #555;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .upload-text-sub {
        font-size: 12px;
        color: #999;
    }

    /* Tombol Submit yang Cantik */
    .btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        border: none;
        border-radius: 30px;
        font-weight: 800;
        font-size: 14px;
        letter-spacing: 0.5px;
        cursor: pointer;
        margin-top: 20px;
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
        transition: 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 140, 0, 0.4);
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <a href="tugas.php?mapel=<?php echo $t['mapel_id']; ?>" style="display:inline-flex; align-items:center; gap:5px; text-decoration:none; color:#777; font-weight:bold; margin-bottom:20px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
    </a>

    <div class="layout-grid">
        
        <div class="card-box">
            <div class="task-header">
                <span class="task-badge"><?php echo $t['nama_mapel']; ?></span>
                <h2><?php echo $t['judul_tugas']; ?></h2>
                <div style="color: #777; font-size: 13px; margin-top: 10px;">
                    <i class="fas fa-user-tie"></i> Guru: <?php echo $t['nama_guru']; ?> &nbsp;|&nbsp;
                    <i class="far fa-calendar-alt"></i> Dibuat: <?php echo date('d M Y', strtotime($t['tgl_buat'])); ?>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <div style="line-height: 1.6; color: #444;">
                <h4 style="margin-top:0;">Instruksi:</h4>
                <?php echo nl2br($t['deskripsi']); ?>
            </div>

            <?php if(!empty($t['file_url'])) { ?>
                <div style="margin-top: 25px; background: #f0f8ff; padding: 15px; border-radius: 10px; border: 1px solid #dbeafe;">
                    <b style="color:#1565c0; font-size:13px;">Lampiran Soal:</b><br>
                    <?php if($t['tipe'] == 'file') { ?>
                        <a href="../uploads/tugas_guru/<?php echo $t['file_url']; ?>" target="_blank" style="text-decoration:none; color:#1e88e5; font-weight:bold; font-size:14px; display:flex; align-items:center; gap:5px; margin-top:5px;">
                            <i class="fas fa-download"></i> Download File Soal
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo $t['file_url']; ?>" target="_blank" style="text-decoration:none; color:#1e88e5; font-weight:bold; font-size:14px; display:flex; align-items:center; gap:5px; margin-top:5px;">
                            <i class="fas fa-external-link-alt"></i> Buka Link Soal
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="card-box" style="height: fit-content;">
            
            <div class="deadline-box">
                <span style="font-size:12px; color:#777;">Batas Waktu Pengumpulan</span>
                <span class="deadline-time" style="color: <?php echo $is_late ? '#c62828' : '#333'; ?>">
                    <?php echo date('d M Y, H:i', $deadline); ?>
                </span>
                <?php if($is_late){ echo "<span style='color:red; font-size:11px; font-weight:bold;'>Waktu Habis</span>"; } ?>
            </div>

            <?php if(isset($s['id_pengumpulan'])) { ?>
                
                <?php if(!empty($s['nilai'])) { ?>
                    <div class="status-box st-graded">
                        <i class="fas fa-check-circle"></i> SUDAH DINILAI
                    </div>
                    <div style="text-align:center; margin-bottom:20px;">
                        <span style="font-size:40px; font-weight:800; color:#1565c0;"><?php echo $s['nilai']; ?></span>
                        <div style="font-size:12px; color:#777;">Nilai Akhir</div>
                    </div>
                    <?php if(!empty($s['catatan_guru'])){ ?>
                        <div style="background:#fafafa; padding:10px; border-radius:8px; font-size:13px; color:#555; border:1px dashed #ccc;">
                            <b>Catatan Guru:</b><br> "<?php echo $s['catatan_guru']; ?>"
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="status-box st-submitted">
                        <i class="fas fa-check"></i> BERHASIL DIKIRIM
                    </div>
                    <div style="font-size:12px; color:#777; text-align:center;">
                        Dikirim pada: <?php echo date('d M Y, H:i', strtotime($s['tgl_upload'])); ?>
                    </div>
                    
                    <div class="file-preview">
                        <i class="fas fa-file-alt" style="color:#FF8C00;"></i>
                        <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            <?php echo !empty($s['file_tugas']) ? $s['file_tugas'] : "Mengumpulkan Link"; ?>
                        </div>
                    </div>

                    <?php if(!$is_late) { ?>
                        <button onclick="document.getElementById('formUpload').style.display='block'; this.style.display='none';" style="width:100%; margin-top:15px; padding:10px; border:1px solid #FF8C00; background:white; color:#FF8C00; border-radius:20px; cursor:pointer; font-weight:bold;">
                            <i class="fas fa-edit"></i> Edit Jawaban
                        </button>
                    <?php } ?>
                <?php } ?>

            <?php } ?>

            <div id="formUpload" style="display: <?php echo (isset($s['id_pengumpulan']) && empty($s['nilai'])) ? 'none' : 'block'; ?>">
                
                <?php if(isset($s['nilai'])) { 
                    // Jika sudah dinilai, form hilang total
                    echo ""; 
                } elseif($is_late && !isset($s['id_pengumpulan'])) { ?>
                    <div style="text-align:center; padding:20px; color:#c62828;">
                        <i class="fas fa-lock" style="font-size:40px; margin-bottom:10px;"></i><br>
                        <b>Maaf, Terlambat!</b><br>
                        <span style="font-size:12px;">Anda tidak bisa lagi mengirim tugas ini.</span>
                    </div>
                <?php } else { ?>
                    
                <form action="tugas_upload.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_tugas" value="<?php echo $id_tugas; ?>">
                    
                    <div class="upload-container">
                        <span class="upload-label-text">Upload Pekerjaan Anda:</span>
                        
                        <input type="file" name="file_jawaban" id="fileInp" style="display:none;" onchange="updateFileName()">
                        
                        <label for="fileInp" class="upload-area">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <span id="fileNameDisp" class="upload-text-main">Klik di sini untuk cari file...</span>
                            <span class="upload-text-sub">(Format: PDF, DOCX, ZIP, JPG - Max 5MB)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> KIRIM JAWABAN
                    </button>
                </form>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<script>
    function updateFileName() {
        var input = document.getElementById('fileInp');
        var disp = document.getElementById('fileNameDisp');
        if(input.files && input.files[0]){
            disp.innerText = input.files[0].name;
            document.querySelector('.upload-icon').style.color = '#FF8C00';
        }
    }

    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "Berhasil!" : "Gagal!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonColor: '#FF8C00'
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>