<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_tugas = $_GET['id'];
$info_tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='$id_tugas'"));
?>

<h3>Pengumpulan: <?php echo $info_tugas['judul_tugas']; ?></h3>
<a href="tugas.php" class="btn btn-red">Kembali</a>
<br><br>

<div class="table-container">
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th>Tanggal Kirim</th>
                <th>File Jawaban</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Ambil data pengumpulan join dengan data siswa (users)
            $query = "SELECT pengumpulan.*, users.nama_lengkap 
                      FROM pengumpulan 
                      JOIN users ON pengumpulan.siswa_id = users.id_user 
                      WHERE pengumpulan.tugas_id='$id_tugas'";
            
            $data = mysqli_query($koneksi, $query);
            
            if(mysqli_num_rows($data) == 0){
                echo "<tr><td colspan='6' align='center'>Belum ada siswa yang mengumpulkan.</td></tr>";
            }

            while($d = mysqli_fetch_array($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td><?php echo $d['tanggal_kumpul']; ?></td>
                <td>
                    <a href="../uploads/tugas/<?php echo $d['file_jawaban']; ?>" target="_blank" class="btn btn-green btn-sm">Download</a>
                </td>
                <td>
                    <?php 
                    if($d['nilai'] == 0) { echo "-"; } 
                    else { echo "<b>" . $d['nilai'] . "</b>"; }
                    ?>
                </td>
                <td>
                    <a href="tugas_nilai.php?id=<?php echo $d['id_pengumpulan']; ?>" class="btn btn-blue">Beri Nilai</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>