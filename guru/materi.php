<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #4facfe, #00f2fe); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(0, 242, 254, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book-open"></i> Manajemen Materi</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Upload dan kelola bahan ajar untuk siswamu.</p>
    </div>
    
    <a href="materi_tambah.php" class="btn-add" style="background: white; color: #4facfe; padding: 10px 20px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Upload Materi Baru
    </a>
</div>

<div class="materi-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">

    <?php 
    // Query: Ambil materi milik guru yang sedang login
    // Join dengan tabel Mapel dan Kelas untuk info detail
    $id_guru = $_SESSION['id_user'];
    $query = "SELECT materi.*, mapel.nama_mapel, kelas.nama_kelas 
              FROM materi 
              JOIN mapel ON materi.mapel_id = mapel.id_mapel 
              JOIN kelas ON mapel.kelas_id = kelas.id_kelas
              WHERE mapel.guru_id='$id_guru' 
              ORDER BY materi.tanggal_upload DESC";
    
    $result = mysqli_query($koneksi, $query);

    if(mysqli_num_rows($result) == 0){
        echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 15px; color: #888;'>
                <img src='../assets/img/logo_sbs.png' height='60' style='opacity: 0.5; margin-bottom: 10px;'>
                <p>Belum ada materi yang diupload.</p>
              </div>";
    }

    while($d = mysqli_fetch_array($result)){
        // Tentukan Ikon berdasarkan tipe file/link
        $icon = "fas fa-file-alt"; // Default
        $warna_icon = "#888"; 
        
        if($d['link_materi'] != ""){
            $icon = "fab fa-youtube";
            $warna_icon = "#ff0000"; // Merah untuk Link/Youtube
        } else if($d['file_materi'] != ""){
            $ext = pathinfo($d['file_materi'], PATHINFO_EXTENSION);
            if($ext == 'pdf'){
                $icon = "fas fa-file-pdf";
                $warna_icon = "#dc3545"; // Merah PDF
            } else if($ext == 'doc' || $ext == 'docx'){
                $icon = "fas fa-file-word";
                $warna_icon = "#007bff"; // Biru Word
            } else if($ext == 'ppt' || $ext == 'pptx'){
                $icon = "fas fa-file-powerpoint";
                $warna_icon = "#fd7e14"; // Oranye PPT
            }
        }
    ?>

    <div class="card-materi" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; display: flex; flex-direction: column;">
        
        <div style="padding: 20px; background: #fcfcfc; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: flex-start;">
            <i class="<?php echo $icon; ?>" style="font-size: 40px; color: <?php echo $warna_icon; ?>;"></i>
            <span style="background: #e3f2fd; color: #0d47a1; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                <?php echo $d['nama_kelas']; ?>
            </span>
        </div>

        <div style="padding: 20px; flex: 1;">
            <small style="color: #999; font-size: 12px;"><i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($d['tanggal_upload'])); ?></small>
            <h4 style="margin: 5px 0 10px 0; color: #333; font-size: 18px; line-height: 1.4;">
                <?php echo $d['judul_materi']; ?>
            </h4>
            <span style="display: block; font-size: 13px; color: #666; font-weight: 500;">
                Mapel: <?php echo $d['nama_mapel']; ?>
            </span>
        </div>

        <div style="padding: 15px 20px; background: #fafafa; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between;">
            
            <?php if($d['link_materi'] != ""){ ?>
                <a href="<?php echo $d['link_materi']; ?>" target="_blank" style="text-decoration: none; color: #4facfe; font-weight: bold; font-size: 14px;">
                    <i class="fas fa-external-link-alt"></i> Buka Link
                </a>
            <?php } else if($d['file_materi'] != "") { ?>
                <a href="../uploads/materi/<?php echo $d['file_materi']; ?>" target="_blank" style="text-decoration: none; color: #4facfe; font-weight: bold; font-size: 14px;">
                    <i class="fas fa-download"></i> Download
                </a>
            <?php } else { echo "<span></span>"; } ?>

            <a href="materi_hapus.php?id=<?php echo $d['id_materi']; ?>" onclick="return confirm('Yakin ingin menghapus materi ini?')" style="text-decoration: none; color: #dc3545; font-size: 14px;" title="Hapus Materi">
                <i class="fas fa-trash"></i>
            </a>

        </div>

    </div>
    <?php } ?>

</div>

<style>
    .card-materi:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        transition: 0.3s;
    }
    .btn-add:hover {
        background: #f0f0f0 !important;
        transform: scale(1.05);
    }
</style>

<?php include 'footer.php'; ?>