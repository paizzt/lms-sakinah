<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #c0392b); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-calendar-alt"></i> Tahun Ajaran & Semester</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9;">Atur tahun ajaran yang aktif saat ini.</p>
</div>

<div class="table-responsive">
    <div style="padding: 20px; border-bottom: 1px solid #eee; display:flex; justify-content: flex-end;">
        <a href="semester_tambah.php" class="btn-add" style="background: #FF8C00; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-plus"></i> Tambah Semester
        </a>
    </div>

    <table class="table-modern">
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun Ajaran</th>
                <th>Semester</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM semester ORDER BY id_semester DESC");
            while($d = mysqli_fetch_array($query)){
            ?>
            <tr style="<?php echo ($d['status']==1) ? 'background:#fdfeff;' : ''; ?>">
                <td><?php echo $no++; ?></td>
                <td><b><?php echo $d['tahun_ajaran']; ?></b></td>
                <td><?php echo $d['semester']; ?></td>
                <td>
                    <?php if($d['status'] == 1){ ?>
                        <span class="badge-status bg-success">AKTIF</span>
                    <?php } else { ?>
                        <span class="badge-status" style="background:#eee; color:#888;">Non-Aktif</span>
                    <?php } ?>
                </td>
                <td style="text-align: center;">
                    <?php if($d['status'] == 0){ ?>
                        <a href="semester_aktifkan.php?id=<?php echo $d['id_semester']; ?>" class="btn-action-small" style="background: #e8f5e9; color: #2e7d32; width: auto; padding: 0 10px; font-size: 12px; text-decoration: none;">
                            <i class="fas fa-check"></i> Set Aktif
                        </a>
                    <?php } ?>
                    
                    <a href="semester_hapus.php?id=<?php echo $d['id_semester']; ?>" onclick="return confirm('Hapus data ini?')" class="btn-action-small btn-delete">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>