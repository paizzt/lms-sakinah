<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #1d976c, #93f9b9); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-desktop"></i> Monitoring Materi</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau bahan ajar yang diupload oleh guru.</p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Materi</th>
                <th>Mapel & Kelas</th>
                <th>Guru Pengampu</th>
                <th>Tipe</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Join 3 tabel: Materi -> Mapel -> Users (Guru) -> Kelas
            $query = "SELECT materi.*, mapel.nama_mapel, users.nama_lengkap, kelas.nama_kelas
                      FROM materi
                      JOIN mapel ON materi.mapel_id = mapel.id_mapel
                      JOIN users ON mapel.guru_id = users.id_user
                      JOIN kelas ON mapel.kelas_id = kelas.id_kelas
                      ORDER BY materi.tanggal_upload DESC";
            $result = mysqli_query($koneksi, $query);

            while($d = mysqli_fetch_array($result)){
                $is_link = ($d['link_materi'] != "");
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <b><?php echo $d['judul_materi']; ?></b><br>
                    <small style="color: #888;"><?php echo date('d M Y', strtotime($d['tanggal_upload'])); ?></small>
                </td>
                <td>
                    <?php echo $d['nama_mapel']; ?> <br>
                    <span class="badge-status bg-info"><?php echo $d['nama_kelas']; ?></span>
                </td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td>
                    <?php if($is_link) { echo '<i class="fab fa-youtube" style="color:red;"></i> Link'; } 
                          else { echo '<i class="fas fa-file-alt" style="color:blue;"></i> File'; } ?>
                </td>
                <td style="text-align: center;">
                    <?php if($is_link){ ?>
                        <a href="<?php echo $d['link_materi']; ?>" target="_blank" class="btn-action-small btn-edit"><i class="fas fa-external-link-alt"></i></a>
                    <?php } else { ?>
                        <a href="../uploads/materi/<?php echo $d['file_materi']; ?>" target="_blank" class="btn-action-small btn-edit"><i class="fas fa-download"></i></a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>