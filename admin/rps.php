<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #2b5876, #4e4376); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-search"></i> Monitoring RPS</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau ketersediaan RPS dari setiap mata pelajaran.</p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mapel</th>
                <th>Kelas</th>
                <th>Guru Pengampu</th>
                <th>Status RPS</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Tampilkan semua mapel
            $query = "SELECT mapel.*, kelas.nama_kelas, users.nama_lengkap, rps.file_rps 
                      FROM mapel 
                      JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                      JOIN users ON mapel.guru_id = users.id_user
                      LEFT JOIN rps ON mapel.id_mapel = rps.mapel_id 
                      ORDER BY users.nama_lengkap ASC";
            
            $result = mysqli_query($koneksi, $query);
            
            while($d = mysqli_fetch_array($result)){
                $ada_rps = ($d['file_rps'] != "");
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><b><?php echo $d['nama_mapel']; ?></b></td>
                <td><?php echo $d['nama_kelas']; ?></td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td>
                    <?php if($ada_rps) { ?>
                        <span class="badge-status bg-success">Tersedia</span>
                    <?php } else { ?>
                        <span class="badge-status bg-danger">Kosong</span>
                    <?php } ?>
                </td>
                <td style="text-align: center;">
                    <?php if($ada_rps) { ?>
                        <a href="../uploads/rps/<?php echo $d['file_rps']; ?>" target="_blank" class="btn-action-small btn-edit" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>