<?php include 'header.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book-reader"></i> RPS Pembelajaran</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Daftar Rencana Pembelajaran Semester untuk kelas Anda.</p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th>Guru Pengampu</th>
                <th>Status RPS</th>
                <th width="15%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // id_kelas_siswa sudah didefinisikan di header.php
            
            // Ambil semua mapel di kelas siswa + Info RPS
            $query = "SELECT mapel.*, users.nama_lengkap, rps.file_rps, rps.deskripsi 
                      FROM mapel 
                      JOIN users ON mapel.guru_id = users.id_user 
                      LEFT JOIN rps ON mapel.id_mapel = rps.mapel_id 
                      WHERE mapel.kelas_id='$id_kelas_siswa' 
                      ORDER BY mapel.nama_mapel ASC";
            
            $result = mysqli_query($koneksi, $query);

            if(mysqli_num_rows($result) == 0){
                echo "<tr><td colspan='5' style='text-align:center; padding:20px;'>Belum ada mata pelajaran di kelas ini.</td></tr>";
            }
            
            while($d = mysqli_fetch_array($result)){
                $ada_rps = ($d['file_rps'] != "");
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <div style="font-weight: bold; color: #333;"><?php echo $d['nama_mapel']; ?></div>
                    <small style="color: #888;"><?php echo $d['kode_mapel']; ?></small>
                </td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td>
                    <?php if($ada_rps) { ?>
                        <span class="badge-status bg-success">Tersedia</span>
                        <?php if($d['deskripsi']) { echo "<br><small style='color:#666; font-style:italic;'>".$d['deskripsi']."</small>"; } ?>
                    <?php } else { ?>
                        <span class="badge-status" style="background:#eee; color:#999;">Belum Upload</span>
                    <?php } ?>
                </td>
                <td style="text-align: center;">
                    <?php if($ada_rps) { ?>
                        <a href="../uploads/rps/<?php echo $d['file_rps']; ?>" target="_blank" class="btn-action-small btn-edit" style="width:auto; padding:5px 15px; text-decoration:none; background: #6a11cb; color: white;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    <?php } else { ?>
                        <span style="color: #ccc; font-size: 20px;">-</span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>