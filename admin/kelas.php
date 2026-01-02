<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #FF8C00); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-chalkboard"></i> Manajemen Kelas</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola data kelas dan wali kelas.</p>
    </div>
    
    <a href="kelas_tambah.php" class="btn-add" style="background: white; color: #FF8C00; padding: 10px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Tambah Kelas
    </a>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
                <th width="15%" style="text-align: center;">Jumlah Siswa</th>
                <th width="15%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Query ambil kelas + nama wali kelas (join table users)
            $query = "SELECT kelas.*, users.nama_lengkap 
                      FROM kelas 
                      LEFT JOIN users ON kelas.wali_kelas_id = users.id_user 
                      ORDER BY kelas.nama_kelas ASC";
            $result = mysqli_query($koneksi, $query);
            
            while($d = mysqli_fetch_array($result)){
                $id_kelas = $d['id_kelas'];
                
                // Hitung jumlah siswa di kelas ini
                $q_jml = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa_detail WHERE kelas_id='$id_kelas'");
                $jml = mysqli_fetch_assoc($q_jml);
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <span style="font-weight: 700; color: #333; font-size: 15px;"><?php echo $d['nama_kelas']; ?></span>
                </td>
                <td>
                    <?php if($d['nama_lengkap']) { ?>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 30px; height: 30px; background: #e3f2fd; color: #0d47a1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <?php echo $d['nama_lengkap']; ?>
                        </div>
                    <?php } else { ?>
                        <span style="color: #999; font-style: italic;">Belum ditentukan</span>
                    <?php } ?>
                </td>
                <td style="text-align: center;">
                    <span style="background: #f0f0f0; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; color: #555;">
                        <?php echo $jml['total']; ?> Siswa
                    </span>
                </td>
                <td style="text-align: center;">
                    <a href="kelas_edit.php?id=<?php echo $d['id_kelas']; ?>" class="btn-action-small btn-edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="kelas_hapus.php?id=<?php echo $d['id_kelas']; ?>" onclick="return confirm('Hapus kelas ini? Data siswa di dalamnya akan kehilangan kelas.')" class="btn-action-small btn-delete" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>