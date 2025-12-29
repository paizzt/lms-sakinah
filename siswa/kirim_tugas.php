<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_tugas = $_GET['id'];
$tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='$id_tugas'"));

// Cek apakah sudah pernah kumpul
$cek = mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'");
$sudah = mysqli_fetch_assoc($cek);
?>

<div class="table-container" style="max-width: 800px;">
    <h2><?php echo $tugas['judul_tugas']; ?></h2>
    <div style="background: #fff3cd; padding: 10px; margin-bottom: 20px;">
        <strong>Deadline:</strong> <?php echo date('d M Y, H:i', strtotime($tugas['deadline'])); ?>
    </div>
    
    <p><strong>Soal/Instruksi:</strong></p>
    <p><?php echo nl2br($tugas['deskripsi']); ?></p>
    
    <hr>

    <?php if($sudah) { ?>
        <div class="alert" style="background-color:#d4edda; color:#155724; border-color:#c3e6cb;">
            <h3>✅ Tugas Sudah Dikumpulkan</h3>
            <p>Tanggal kirim: <?php echo $sudah['tanggal_kumpul']; ?></p>
            <p>File Anda: <a href="../uploads/tugas/<?php echo $sudah['file_jawaban']; ?>" target="_blank">Lihat File</a></p>
            
            <?php if($sudah['nilai'] > 0){ ?>
                <h1>Nilai: <?php echo $sudah['nilai']; ?> / 100</h1>
                <p>Komentar Guru: <?php echo $sudah['komentar_guru']; ?></p>
            <?php } else { ?>
                <p><em>Menunggu dinilai oleh guru...</em></p>
            <?php } ?>
        </div>
    <?php } else { ?>
        <h3>Kirim Jawaban</h3>
        <form action="kirim_tugas_aksi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_tugas" value="<?php echo $id_tugas; ?>">
            <div class="form-group">
                <label>Upload File Jawaban (PDF/DOC/Gambar)</label>
                <input type="file" name="file_jawaban" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-green">Kirim Tugas</button>
        </form>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>