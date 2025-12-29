<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
$id_mapel = $_GET['id'];
$mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mapel WHERE id_mapel='$id_mapel'"));
?>

<h3>Rekap Absensi: <?php echo $mapel['nama_mapel']; ?></h3>
<a href="absensi.php" class="btn btn-red">Kembali</a>
<br><br>

<div class="table-container">
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Group by tanggal untuk melihat per pertemuan
            $query = "SELECT tanggal, 
                             SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as h,
                             SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as s,
                             SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as i,
                             SUM(CASE WHEN status='alpa' THEN 1 ELSE 0 END) as a
                      FROM absensi 
                      WHERE mapel_id='$id_mapel' 
                      GROUP BY tanggal 
                      ORDER BY tanggal DESC";
            
            $data = mysqli_query($koneksi, $query);
            while($d = mysqli_fetch_array($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo date('d-m-Y', strtotime($d['tanggal'])); ?></td>
                <td style="color:green; font-weight:bold;"><?php echo $d['h']; ?></td>
                <td><?php echo $d['s']; ?></td>
                <td><?php echo $d['i']; ?></td>
                <td style="color:red;"><?php echo $d['a']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>