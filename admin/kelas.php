<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<?php
// --- 1. LOGIKA DATA ---

// Ambil Data Semester Aktif (Untuk kolom Tahun & Semester)
$q_sem = mysqli_query($koneksi, "SELECT * FROM semester WHERE status=1");
$d_sem = mysqli_fetch_assoc($q_sem);
$tahun_aktif = isset($d_sem['tahun_ajaran']) ? $d_sem['tahun_ajaran'] : '-';
$semester_aktif = isset($d_sem['semester']) ? $d_sem['semester'] : '-';

// Hitung Statistik
$q_total_kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
$jml_kelas = mysqli_num_rows($q_total_kelas);

$q_wali = mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas_id IS NOT NULL");
$jml_wali = mysqli_num_rows($q_wali);

$q_kosong = mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas_id IS NULL");
$jml_kosong = mysqli_num_rows($q_kosong);
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-left: 5px solid #FF8C00;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .stat-info h3 { margin: 0; font-size: 28px; color: #333; }
    .stat-info p { margin: 0; color: #888; font-size: 13px; font-weight: bold; }
    .stat-icon {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-chalkboard"></i> Manajemen Kelas</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Kelola data kelas dan wali kelas.</p>
            </div>
            <div>
                <a href="kelas_tambah.php" class="btn-tambah" style="background: white; color: #E65100; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-plus-circle"></i> Tambah Kelas
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #FF8C00;">
            <div class="stat-info"><h3><?php echo $jml_kelas; ?></h3><p>TOTAL KELAS</p></div>
            <div class="stat-icon" style="background: #FFF3E0; color: #FF8C00;"><i class="fas fa-school"></i></div>
        </div>
        <div class="stat-card" style="border-left-color: #27ae60;">
            <div class="stat-info"><h3><?php echo $jml_wali; ?></h3><p>WALI KELAS TERISI</p></div>
            <div class="stat-icon" style="background: #eafaf1; color: #27ae60;"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-card" style="border-left-color: #c0392b;">
            <div class="stat-info"><h3><?php echo $jml_kosong; ?></h3><p>KELAS KOSONG</p></div>
            <div class="stat-icon" style="background: #fdedec; color: #c0392b;"><i class="fas fa-exclamation-circle"></i></div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden; width: 100%; max-width: 100%;">
        
        <div style="padding: 20px; background: #fdfdfd; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #555;">Daftar Kelas Aktif</h4>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
                <input type="text" id="searchKelas" onkeyup="searchTable()" placeholder="Cari nama kelas..." style="padding: 8px 10px 8px 35px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 13px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="kelasTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; text-align: left; width: 50px;">No</th>
                        <th style="padding: 15px; text-align: left;">Kelas</th>
                        <th style="padding: 15px; text-align: left;">Wali Kelas</th>
                        <th style="padding: 15px; text-align: center;">Tahun</th>
                        <th style="padding: 15px; text-align: center;">Semester</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Query Join: Mengambil data kelas + nama wali kelas dari tabel users
                    $query = mysqli_query($koneksi, "SELECT kelas.*, users.nama_lengkap 
                                                     FROM kelas 
                                                     LEFT JOIN users ON kelas.wali_kelas_id = users.id_user 
                                                     ORDER BY kelas.nama_kelas ASC");
                    
                    if(mysqli_num_rows($query) > 0){
                        while($d = mysqli_fetch_array($query)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        
                        <td style="padding: 15px; font-weight: 600; color: #333;">
                            <?php echo $d['nama_kelas']; ?>
                        </td>
                        
                        <td style="padding: 15px;">
                            <?php 
                            if($d['nama_lengkap']){
                                echo "<div style='display:flex; align-items:center; gap:8px;'>
                                        <i class='fas fa-user-tie' style='color:#2980b9;'></i> 
                                        <span>".$d['nama_lengkap']."</span>
                                      </div>";
                            } else {
                                echo "<span style='background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;'>Belum Diatur</span>";
                            }
                            ?>
                        </td>

                        <td style="padding: 15px; text-align: center; color: #555;">
                            <?php echo $tahun_aktif; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #eafaf1; color: #27ae60; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                <?php echo $semester_aktif; ?>
                            </span>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <a href="kelas_edit.php?id=<?php echo $d['id_kelas']; ?>" class="btn-action edit" title="Edit" style="background: #FFF3E0; color: #E65100; padding: 8px 12px; border-radius: 6px; margin-right: 5px; display: inline-block;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="kelas_hapus.php?id=<?php echo $d['id_kelas']; ?>" onclick="return confirm('Yakin ingin menghapus kelas ini?')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px; display: inline-block;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; padding:20px; color:#999;'>Belum ada data kelas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div style="padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; color: #777; font-size: 13px;">
            <div>Menampilkan <?php echo mysqli_num_rows($query); ?> Data</div>
            <div style="display: flex; gap: 5px;">
                <button style="border: 1px solid #ddd; background: white; padding: 5px 10px; border-radius: 4px; color: #999; cursor: not-allowed;">Prev</button>
                <button style="border: 1px solid #FF8C00; background: #FF8C00; padding: 5px 10px; border-radius: 4px; color: white;">1</button>
                <button style="border: 1px solid #ddd; background: white; padding: 5px 10px; border-radius: 4px; color: #333;">Next</button>
            </div>
        </div>

    </div>

</div>

<script>
function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchKelas");
    filter = input.value.toUpperCase();
    table = document.getElementById("kelasTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // Kolom Kelas (index 1)
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>

<?php include 'footer.php'; ?>