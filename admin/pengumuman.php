<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-bullhorn"></i> Kelola Pengumuman</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Buat dan kelola informasi untuk guru dan siswa.</p>
            </div>
            <div>
                <a href="pengumuman_tambah.php" class="btn-tambah" style="background: white; color: #E65100; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-plus-circle"></i> Buat Pengumuman Baru
                </a>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px; width: 10%;">Tanggal</th>
                        <th style="padding: 15px; width: 15%;">Tujuan</th>
                        <th style="padding: 15px; width: 25%;">Judul & Isi</th>
                        <th style="padding: 15px; width: 15%;">Lampiran</th>
                        <th style="padding: 15px; width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $data = mysqli_query($koneksi,"SELECT * FROM pengumuman ORDER BY id_pengumuman DESC");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; vertical-align: top; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; vertical-align: top; font-size: 13px;">
                            <i class="far fa-calendar-alt" style="color: #FF8C00;"></i> 
                            <?php echo date('d-m-Y', strtotime($d['tanggal_dibuat'])); ?>
                        </td>
                        <td style="padding: 15px; vertical-align: top;">
                            <?php 
                            $badge_color = ($d['tujuan'] == 'semua') ? '#2ecc71' : (($d['tujuan'] == 'guru') ? '#3498db' : '#f1c40f');
                            $text_color = ($d['tujuan'] == 'siswa') ? '#333' : '#fff';
                            ?>
                            <span style="background: <?php echo $badge_color; ?>; color: <?php echo $text_color; ?>; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                <?php echo $d['tujuan']; ?>
                            </span>
                        </td>
                        <td style="padding: 15px; vertical-align: top;">
                            <strong style="display: block; font-size: 15px; margin-bottom: 5px; color: #333;"><?php echo $d['judul']; ?></strong>
                            <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.5;">
                                <?php echo substr($d['isi'], 0, 100) . '...'; ?>
                            </p>
                        </td>
                        <td style="padding: 15px; vertical-align: top;">
                            <?php 
                            if($d['file_lampiran'] == ""){
                                echo "<span style='color: #ccc; font-style: italic; font-size: 13px;'>Tidak ada file</span>";
                            } else {
                                $file = $d['file_lampiran'];
                                $ext = pathinfo($file, PATHINFO_EXTENSION);

                                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])){
                                    // Jika Gambar
                                    echo "<img src='../uploads/pengumuman/$file' style='width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;'>";
                                } else {
                                    // Jika File Dokumen (PERBAIKAN KUTIP ADA DI SINI)
                                    echo "<a href='../uploads/pengumuman/$file' target='_blank' style='text-decoration: none; color: #E65100; font-size: 13px; font-weight: 500;'>
                                            <i class='fas fa-paperclip'></i> Download ".strtoupper($ext)."
                                          </a>";
                                }
                            }
                            ?>
                        </td>
                        <td style="padding: 15px; vertical-align: top; text-align: center;">
                            <a href="pengumuman_edit.php?id=<?php echo $d['id_pengumuman']; ?>" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border-radius: 6px; margin-right: 5px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="pengumuman_hapus.php?id=<?php echo $d['id_pengumuman']; ?>" class="btn-action delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>