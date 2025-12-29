<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #ff9966, #ff5e62); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 94, 98, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-bullhorn"></i> Pengumuman & Berita</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola informasi penting untuk warga sekolah.</p>
    </div>
    
    <a href="pengumuman_tambah.php" class="btn-add" style="background: white; color: #ff5e62; padding: 10px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Buat Pengumuman
    </a>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Sampul</th>
                <th width="30%">Judul Berita</th>
                <th>Target</th>
                <th>Tanggal Upload</th>
                <th width="10%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal_dibuat DESC");
            
            while($d = mysqli_fetch_array($query)){
                // Tentukan Gambar
                $gambar = $d['gambar'] ? "../uploads/berita/".$d['gambar'] : "../assets/img/logo_sbs.png";
                
                // Tentukan Warna Badge Target
                $target_class = "target-semua";
                if($d['tujuan'] == 'siswa') $target_class = "target-siswa";
                if($d['tujuan'] == 'guru') $target_class = "target-guru";
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <img src="<?php echo $gambar; ?>" class="news-thumbnail" alt="Thumb">
                </td>
                <td>
                    <div style="font-weight: bold; color: #333; font-size: 15px; margin-bottom: 5px;">
                        <?php echo $d['judul']; ?>
                    </div>
                    <div style="color: #888; font-size: 13px;">
                        <?php echo substr(strip_tags($d['isi']), 0, 60) . '...'; ?>
                    </div>
                </td>
                <td>
                    <span class="badge-target <?php echo $target_class; ?>">
                        <?php echo strtoupper($d['tujuan']); ?>
                    </span>
                </td>
                <td>
                    <div style="font-size: 13px; color: #555;">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($d['tanggal_dibuat'])); ?>
                    </div>
                    <small style="color: #999;"><?php echo date('H:i', strtotime($d['tanggal_dibuat'])); ?> WIB</small>
                </td>
                <td style="text-align: center;">
                    <a href="pengumuman_edit.php?id=<?php echo $d['id_pengumuman']; ?>" class="btn-action-small btn-edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="pengumuman_hapus.php?id=<?php echo $d['id_pengumuman']; ?>" onclick="return confirm('Hapus berita ini?')" class="btn-action-small btn-delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>