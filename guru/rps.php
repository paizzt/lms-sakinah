<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #FF8C00); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(74, 0, 224, 0.2);">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-tasks"></i> Kelola RPS</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Upload Rencana Pembelajaran Semester untuk mata pelajaran Anda.</p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Status RPS</th>
                <th>File</th>
                <th width="15%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $id_guru = $_SESSION['id_user'];
            
            // Ambil mapel guru ini + Join ke tabel RPS untuk cek apakah sudah ada
            $query = "SELECT mapel.*, kelas.nama_kelas, rps.file_rps, rps.id_rps 
                      FROM mapel 
                      JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                      LEFT JOIN rps ON mapel.id_mapel = rps.mapel_id 
                      WHERE mapel.guru_id='$id_guru' 
                      ORDER BY kelas.nama_kelas ASC";
            
            $result = mysqli_query($koneksi, $query);
            
            while($d = mysqli_fetch_array($result)){
                $sudah_upload = ($d['file_rps'] != "");
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <div style="font-weight: bold; color: #333;"><?php echo $d['nama_mapel']; ?></div>
                    <small style="color: #888;">Kode: <?php echo $d['kode_mapel']; ?></small>
                </td>
                <td><span class="badge-day"><?php echo $d['nama_kelas']; ?></span></td>
                <td>
                    <?php if($sudah_upload) { ?>
                        <span class="badge-status bg-success">Sudah Upload</span>
                    <?php } else { ?>
                        <span class="badge-status bg-danger">Belum Ada</span>
                    <?php } ?>
                </td>
                <td>
                    <?php if($sudah_upload) { ?>
                        <a href="../uploads/rps/<?php echo $d['file_rps']; ?>" target="_blank" style="color: #4a00e0; font-weight: bold; text-decoration: none;">
                            <i class="fas fa-file-pdf"></i> Lihat File
                        </a>
                    <?php } else { echo "-"; } ?>
                </td>
                <td style="text-align: center;">
                    <?php if($sudah_upload) { ?>
                        <a href="rps_hapus.php?id=<?php echo $d['id_rps']; ?>" onclick="return confirm('Hapus file RPS ini?')" class="btn-action-small btn-delete" title="Hapus RPS">
                            <i class="fas fa-trash"></i>
                        </a>
                    <?php } else { ?>
                        <a href="rps_tambah.php?id_mapel=<?php echo $d['id_mapel']; ?>" class="btn-action-small btn-edit" style="width: auto; padding: 0 10px; background: #e0e7ff; color: #4a00e0;" title="Upload RPS">
                            <i class="fas fa-upload"></i> Upload
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>