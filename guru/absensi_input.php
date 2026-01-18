<?php 
error_reporting(0);
ini_set('display_errors', 0);

include 'header.php'; 
include 'sidebar.php'; 

// VALIDASI: Jika tidak ada parameter mapel, tendang balik ke absensi.php
if(!isset($_GET['mapel']) || empty($_GET['mapel'])){
    echo "<script>window.location='absensi.php';</script>";
    exit();
}

$id_guru  = $_SESSION['id_user'];
$mapel_id = $_GET['mapel'];
$tanggal  = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d'); 

// AMBIL INFO KELAS & MAPEL
$q_info = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas, kelas.id_kelas 
                                  FROM mapel 
                                  JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                  WHERE id_mapel='$mapel_id'");

if(mysqli_num_rows($q_info) == 0){
    echo "<script>alert('Data tidak ditemukan!'); window.location='absensi.php';</script>";
    exit();
}
$info = mysqli_fetch_assoc($q_info);
$kelas_id = $info['id_kelas'];

// AMBIL SISWA
$query_siswa = "SELECT users.id_user, users.nama_lengkap, users.foto_profil, 
                       absensi.status, absensi.keterangan 
                FROM users 
                LEFT JOIN absensi ON users.id_user = absensi.siswa_id 
                     AND absensi.mapel_id = '$mapel_id' 
                     AND absensi.tanggal = '$tanggal'
                WHERE users.kelas_id = '$kelas_id' AND users.role = 'siswa'
                ORDER BY users.nama_lengkap ASC";
$q_siswa = mysqli_query($koneksi, $query_siswa);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* CSS SAMA SEPERTI SEBELUMNYA (CARD MODERN TABLE) */
    .page-header-control {
        background: white; padding: 20px 25px; border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03); display: flex; justify-content: space-between;
        align-items: center; margin-bottom: 25px; border-left: 5px solid #FF8C00;
    }
    .header-info h2 { margin: 0; font-size: 22px; color: #333; font-weight: 800; }
    .header-info p { margin: 5px 0 0 0; color: #777; font-size: 14px; }
    .badge-kelas { background: #FFF3E0; color: #E65100; padding: 4px 10px; border-radius: 10px; font-weight: bold; font-size: 12px; }
    
    .date-control { display: flex; align-items: center; gap: 10px; background: #f9f9f9; padding: 8px 15px; border-radius: 30px; border: 1px solid #eee; }
    .date-control input { border: none; background: transparent; font-weight: bold; color: #555; outline: none; cursor: pointer; }

    .card-table { background: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); overflow: hidden; padding: 5px; }
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern thead { background: linear-gradient(to right, #FF8C00, #F39C12); color: white; }
    .table-modern th { padding: 15px; font-weight: 700; text-transform: uppercase; font-size: 12px; text-align: left; }
    .table-modern th.center { text-align: center; }
    .table-modern td { padding: 15px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; color: #555; }
    .table-modern tr:hover { background-color: #fff8e1; }

    .status-group { display: flex; justify-content: center; gap: 8px; }
    .radio-pill input[type="radio"] { display: none; }
    .radio-pill label { display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; border: 1px solid #e0e0e0; background: #fff; color: #999; }
    
    .radio-pill.hadir input:checked + label { background: #e8f5e9; color: #27ae60; border-color: #27ae60; }
    .radio-pill.sakit input:checked + label { background: #e3f2fd; color: #2980b9; border-color: #2980b9; }
    .radio-pill.izin input:checked + label  { background: #fcf3cf; color: #f39c12; border-color: #f39c12; }
    .radio-pill.alpa input:checked + label  { background: #ffebee; color: #c0392b; border-color: #c0392b; }

    .input-ket { width: 100%; padding: 8px 12px; border: 1px solid #eee; border-radius: 8px; outline: none; transition: 0.3s; font-size: 13px; }
    .input-ket:focus { border-color: #FF8C00; background: #fffdf9; }

    .btn-save-floating { margin-top: 25px; width: 100%; padding: 15px; background: linear-gradient(135deg, #FF8C00, #F39C12); color: white; border: none; border-radius: 50px; font-weight: 800; font-size: 16px; cursor: pointer; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.3); transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 10px; }
    .btn-save-floating:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(255, 140, 0, 0.4); }
    .btn-back { text-decoration: none; color: #777; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; margin-top: 20px; transition: 0.3s; }
    .btn-back:hover { color: #FF8C00; padding-left: 5px; }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="page-header-control">
        <div class="header-info">
            <h2>Input Absensi</h2>
            <p><span class="badge-kelas"><?php echo $info['nama_kelas']; ?></span> <?php echo $info['nama_mapel']; ?></p>
        </div>
        <form method="GET" id="formTanggal">
            <input type="hidden" name="mapel" value="<?php echo $mapel_id; ?>">
            <div class="date-control">
                <i class="far fa-calendar-alt" style="color: #FF8C00;"></i>
                <input type="date" name="tgl" value="<?php echo $tanggal; ?>" onchange="document.getElementById('formTanggal').submit()">
            </div>
        </form>
    </div>

    <form action="absensi_aksi.php" method="POST">
        <input type="hidden" name="mapel_id" value="<?php echo $mapel_id; ?>">
        <input type="hidden" name="tanggal" value="<?php echo $tanggal; ?>">

        <div class="card-table">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th width="30%">Nama Siswa</th>
                        <th width="40%" class="center">Status Kehadiran</th>
                        <th width="30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($q_siswa) > 0){
                        while($s = mysqli_fetch_array($q_siswa)){
                            $st = $s['status']; 
                            $h = ($st == 'H' || $st == '') ? 'checked' : '';
                            $skt = ($st == 'S') ? 'checked' : '';
                            $i = ($st == 'I') ? 'checked' : '';
                            $a = ($st == 'A') ? 'checked' : '';
                            
                            $foto = ($s['foto_profil'] && $s['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$s['foto_profil'] : "../assets/img/avatar-default.svg";
                    ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?php echo $foto; ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #eee;">
                                <span style="font-weight: 600; color: #333;"><?php echo $s['nama_lengkap']; ?></span>
                            </div>
                        </td>
                        <td class="center">
                            <div class="status-group">
                                <div class="radio-pill hadir">
                                    <input type="radio" id="h_<?php echo $s['id_user']; ?>" name="status[<?php echo $s['id_user']; ?>]" value="H" <?php echo $h; ?>>
                                    <label for="h_<?php echo $s['id_user']; ?>">Hadir</label>
                                </div>
                                <div class="radio-pill sakit">
                                    <input type="radio" id="s_<?php echo $s['id_user']; ?>" name="status[<?php echo $s['id_user']; ?>]" value="S" <?php echo $skt; ?>>
                                    <label for="s_<?php echo $s['id_user']; ?>">Sakit</label>
                                </div>
                                <div class="radio-pill izin">
                                    <input type="radio" id="i_<?php echo $s['id_user']; ?>" name="status[<?php echo $s['id_user']; ?>]" value="I" <?php echo $i; ?>>
                                    <label for="i_<?php echo $s['id_user']; ?>">Izin</label>
                                </div>
                                <div class="radio-pill alpa">
                                    <input type="radio" id="a_<?php echo $s['id_user']; ?>" name="status[<?php echo $s['id_user']; ?>]" value="A" <?php echo $a; ?>>
                                    <label for="a_<?php echo $s['id_user']; ?>">Alpa</label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="input-ket" name="ket[<?php echo $s['id_user']; ?>]" value="<?php echo $s['keterangan']; ?>" placeholder="Tulis catatan...">
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='3' style='text-align:center; padding:50px; color:#999;'>Belum ada siswa di kelas ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-save-floating">
            <i class="fas fa-save"></i> SIMPAN DATA ABSENSI
        </button>
    </form>
    
    <a href="absensi.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Pilih Kelas</a>
</div>

<script>
    <?php if(isset($_SESSION['notif_status'])) { ?>
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "Berhasil!" : "Gagal!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonColor: '#FF8C00',
            timer: 2000,
            timerProgressBar: true
        });
    <?php unset($_SESSION['notif_status']); unset($_SESSION['notif_pesan']); } ?>
</script>

<?php include 'footer.php'; ?>