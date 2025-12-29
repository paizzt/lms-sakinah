<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #ff512f, #dd2476); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-clipboard-check"></i> Monitoring Tugas</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Pantau tugas dan kuis yang diberikan guru.</p>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Tugas</th>
                <th>Mapel / Kelas</th>
                <th>Guru</th>
                <th>Deadline</th>
                <th>Tipe</th>
                <th style="text-align: center;">File Soal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = "SELECT tugas.*, mapel.nama_mapel, users.nama_lengkap, kelas.nama_kelas
                      FROM tugas
                      JOIN mapel ON tugas.mapel_id = mapel.id_mapel
                      JOIN users ON mapel.guru_id = users.id_user
                      JOIN kelas ON mapel.kelas_id = kelas.id_kelas
                      ORDER BY tugas.deadline DESC";
            $result = mysqli_query($koneksi, $query);

            while($d = mysqli_fetch_array($result)){
                $is_expired = (date('Y-m-d H:i:s') > $d['deadline']);
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['judul_tugas']; ?></td>
                <td><?php echo $d['nama_mapel']; ?> - <b><?php echo $d['nama_kelas']; ?></b></td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td>
                    <span style="color: <?php echo $is_expired ? 'red':'green'; ?>;">
                        <?php echo date('d M Y', strtotime($d['deadline'])); ?>
                    </span>
                </td>
                <td>
                    <?php if($d['tipe']=='kuis') echo '<span class="badge-status bg-warning">Kuis</span>'; 
                          else echo '<span class="badge-status bg-info">Tugas</span>'; ?>
                </td>
                <td style="text-align: center;">
                    <?php if($d['file_tugas']){ ?>
                        <a href="../uploads/tugas/<?php echo $d['file_tugas']; ?>" target="_blank" class="btn-action-small btn-edit"><i class="fas fa-download"></i></a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>