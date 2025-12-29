<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="welcome-banner" style="background: linear-gradient(to right, #11998e, #38ef7d); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(56, 239, 125, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-book"></i> Mata Pelajaran</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Atur jadwal pelajaran dan guru pengampu.</p>
    </div>
    
    <a href="mapel_tambah.php" class="btn-add" style="background: white; color: #11998e; padding: 10px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;">
        <i class="fas fa-plus"></i> Tambah Mapel
    </a>
</div>

<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Guru Pengampu</th>
                <th>Jadwal</th>
                <th width="10%" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Query Join 3 Tabel: mapel, kelas, users (guru)
            $query = "SELECT mapel.*, kelas.nama_kelas, users.nama_lengkap, users.foto_profil 
                      FROM mapel 
                      JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                      JOIN users ON mapel.guru_id = users.id_user 
                      ORDER BY kelas.nama_kelas ASC, mapel.hari DESC";
            
            $result = mysqli_query($koneksi, $query);
            
            while($d = mysqli_fetch_array($result)){
                $foto_guru = $d['foto_profil'] ? "../uploads/profil/".$d['foto_profil'] : "../assets/img/default.jpg";
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><span class="code-badge"><?php echo $d['kode_mapel']; ?></span></td>
                <td>
                    <div style="font-weight: bold; color: #333;"><?php echo $d['nama_mapel']; ?></div>
                </td>
                <td>
                    <span style="background: #e3f2fd; color: #0d47a1; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        <?php echo $d['nama_kelas']; ?>
                    </span>
                </td>
                <td>
                    <div class="teacher-info">
                        <img src="<?php echo $foto_guru; ?>" alt="Foto Guru">
                        <span style="font-size: 13px; color: #555;"><?php echo $d['nama_lengkap']; ?></span>
                    </div>
                </td>
                <td>
                    <?php if($d['hari']) { ?>
                        <span class="badge-day"><i class="far fa-calendar-alt"></i> <?php echo $d['hari']; ?></span>
                        <div style="font-size: 12px; color: #666; margin-top: 3px;">
                            <i class="far fa-clock"></i> 
                            <?php echo substr($d['jam_mulai'],0,5) . " - " . substr($d['jam_selesai'],0,5); ?>
                        </div>
                    <?php } else { echo "-"; } ?>
                </td>
                <td style="text-align: center;">
                    <a href="mapel_edit.php?id=<?php echo $d['id_mapel']; ?>" class="btn-action-small btn-edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="mapel_hapus.php?id=<?php echo $d['id_mapel']; ?>" onclick="return confirm('Hapus mapel ini?')" class="btn-action-small btn-delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>