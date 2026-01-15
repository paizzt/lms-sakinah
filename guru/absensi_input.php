<?php include 'header.php'; 

// 1. TANGKAP KELAS DAN MAPEL DARI URL
$id_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$id_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : ''; 
$tanggal_hari_ini = date('Y-m-d');

// Cek jika data URL tidak lengkap
if(empty($id_kelas) || empty($id_mapel)){
    echo "<div style='padding: 20px; text-align: center;'>
            <h3 style='color: red;'>Data Tidak Lengkap!</h3>
            <p>Pastikan Anda membuka halaman ini melalui menu <b>Absensi Siswa</b> dan memilih Mata Pelajaran yang benar.</p>
            <a href='absensi.php' class='btn-submit' style='text-decoration:none; background:#333;'>Kembali</a>
          </div>";
    include 'footer.php'; 
    exit();
}

// Ambil info kelas & mapel
$info_k = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id_kelas='$id_kelas'"));
$info_m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_mapel FROM mapel WHERE id_mapel='$id_mapel'"));
$nama_mapel = isset($info_m['nama_mapel']) ? $info_m['nama_mapel'] : 'Mapel Tidak Ditemukan';
?>

<style>
    /* Animasi Masuk */
    .fade-in { animation: fadeIn 0.8s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Card Utama yang Lebih Pas */
    .attendance-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Shadow lembut */
        overflow: hidden;
        margin-bottom: 30px;
        border: 1px solid #f0f0f0;
    }

    /* Header Card */
    .card-header-custom {
        padding: 20px 30px;
        background: white;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    /* Date Picker Cantik */
    .date-picker-wrapper {
        background: #f9f9f9;
        padding: 5px 15px;
        border-radius: 50px;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }
    .date-picker-wrapper:hover { border-color: #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .date-input-custom {
        border: none;
        background: transparent;
        font-family: inherit;
        color: #555;
        font-weight: 600;
        outline: none;
        cursor: pointer;
    }

    /* Radio Button Style (Tombol Status) */
    .attendance-options input[type="radio"] { display: none; }
    
    .attendance-options label {
        display: inline-block;
        padding: 8px 16px; /* Padding pas */
        margin: 0 4px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        background: #f8f9fa;
        color: #777;
        border: 1px solid #eee;
        transition: all 0.2s ease;
    }

    .attendance-options label:hover { transform: translateY(-2px); background: #eee; }

    /* Warna Checked */
    .attendance-options input[value="hadir"]:checked + label {
        background: #e3f9e5; color: #1b5e20; border-color: #a5d6a7;
    }
    .attendance-options input[value="sakit"]:checked + label {
        background: #e3f2fd; color: #0d47a1; border-color: #90caf9;
    }
    .attendance-options input[value="izin"]:checked + label {
        background: #fff8e1; color: #f57f17; border-color: #ffe082;
    }
    .attendance-options input[value="alpa"]:checked + label {
        background: #ffebee; color: #b71c1c; border-color: #ef9a9a;
    }

    /* Tabel Styling */
    .table-custom th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        background: #fafafa;
        padding: 15px 25px;
        font-weight: 700;
        border-bottom: 1px solid #eee;
    }
    .table-custom td {
        padding: 15px 25px;
        vertical-align: middle;
        border-bottom: 1px solid #f9f9f9;
    }
    .table-custom tr:last-child td { border-bottom: none; }
    .table-custom tr:hover td { background-color: #fcfcfc; }

    /* Input Keterangan */
    .input-ket {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #eee;
        background: #fcfcfc;
        border-radius: 8px;
        font-size: 13px;
        transition: 0.3s;
    }
    .input-ket:focus { background: white; border-color: #bbb; outline: none; }

    /* Tombol Simpan Floating */
    .btn-save-area {
        padding: 20px 30px;
        background: #fcfcfc;
        border-top: 1px solid #eee;
        text-align: right;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        transition: 0.3s;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(118, 75, 162, 0.4); }

    /* Responsif */
    @media (max-width: 768px) {
        .card-header-custom { flex-direction: column; align-items: flex-start; }
        .date-picker-wrapper { width: 100%; justify-content: space-between; }
        .attendance-options { display: flex; flex-wrap: wrap; gap: 5px; }
        .attendance-options label { flex: 1; text-align: center; font-size: 11px; padding: 6px; }
    }
</style>

<div class="content-body fade-in" style="margin-top: -20px;"> 
    
    <div style="max-width: 1100px; margin: 0 auto;">
        
        <form action="absensi_aksi.php" method="POST">
            <input type="hidden" name="kelas_id" value="<?php echo $id_kelas; ?>">
            <input type="hidden" name="mapel_id" value="<?php echo $id_mapel; ?>">
            <input type="hidden" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini; ?>">

            <div class="attendance-card">
                
                <div class="card-header-custom">
                    <div>
                        <h4 style="margin: 0; color: #333; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-edit" style="color: #FF8C00;"></i> 
                            Input Absensi
                        </h4>
                        <p style="margin: 5px 0 0 0; color: #777; font-size: 14px;">
                            Kelas: <b><?php echo $info_k['nama_kelas']; ?></b> &bull; Mapel: <b><?php echo $nama_mapel; ?></b>
                        </p>
                    </div>

                    <div class="date-picker-wrapper">
                        <i class="far fa-calendar-alt" style="color: #888;"></i>
                        <form method="GET" id="formTanggal">
                            <input type="hidden" name="kelas" value="<?php echo $id_kelas; ?>">
                            <input type="hidden" name="mapel" value="<?php echo $id_mapel; ?>">
                            <input type="date" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini; ?>" class="date-input-custom" onchange="document.getElementById('formTanggal').submit();">
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="width: 30%;">NAMA SISWA</th>
                                <th style="width: 40%; text-align: center;">STATUS</th>
                                <th style="width: 30%;">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tgl_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal_hari_ini;

                            // Ambil siswa
                            $q_siswa = mysqli_query($koneksi, "SELECT users.nama_lengkap, users.id_user, siswa_detail.nis 
                                                            FROM siswa_detail 
                                                            JOIN users ON siswa_detail.user_id = users.id_user 
                                                            WHERE siswa_detail.kelas_id='$id_kelas' 
                                                            ORDER BY users.nama_lengkap ASC");

                            if(mysqli_num_rows($q_siswa) > 0) {
                                while($s = mysqli_fetch_array($q_siswa)){
                                    $id_siswa = $s['id_user'];
                                    
                                    // Cek data absensi yang sudah ada
                                    $q_cek = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND tanggal='$tgl_pilih' AND mapel_id='$id_mapel'");
                                    $data_absen = mysqli_fetch_assoc($q_cek);
                                    
                                    // Default status 'hadir'
                                    $status = isset($data_absen['status']) ? strtolower($data_absen['status']) : 'hadir';
                                    $ket = isset($data_absen['keterangan']) ? $data_absen['keterangan'] : '';
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #444; font-size: 14px;"><?php echo $s['nama_lengkap']; ?></div>
                                    <small style="color: #999; font-size: 11px;">NIS: <?php echo $s['nis']; ?></small>
                                    <input type="hidden" name="siswa_id[]" value="<?php echo $id_siswa; ?>">
                                </td>
                                
                                <td style="text-align: center;">
                                    <div class="attendance-options">
                                        <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="H_<?php echo $id_siswa; ?>" value="hadir" <?php if($status=='hadir') echo 'checked'; ?>> 
                                        <label for="H_<?php echo $id_siswa; ?>">Hadir</label>

                                        <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="S_<?php echo $id_siswa; ?>" value="sakit" <?php if($status=='sakit') echo 'checked'; ?>> 
                                        <label for="S_<?php echo $id_siswa; ?>">Sakit</label>

                                        <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="I_<?php echo $id_siswa; ?>" value="izin" <?php if($status=='izin') echo 'checked'; ?>> 
                                        <label for="I_<?php echo $id_siswa; ?>">Izin</label>

                                        <input type="radio" name="status[<?php echo $id_siswa; ?>]" id="A_<?php echo $id_siswa; ?>" value="alpa" <?php if($status=='alpa') echo 'checked'; ?>> 
                                        <label for="A_<?php echo $id_siswa; ?>">Alpa</label>
                                    </div>
                                </td>

                                <td>
                                    <input type="text" name="keterangan[<?php echo $id_siswa; ?>]" class="input-ket" placeholder="Tulis keterangan..." value="<?php echo $ket; ?>">
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align:center; padding: 40px; color: #999;'>Belum ada siswa di kelas ini.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="btn-save-area">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-check-circle"></i> SIMPAN PERUBAHAN
                    </button>
                </div>

            </div>
            </form>

    </div>
</div>

<script>
    // Script kecil agar form tanggal submit otomatis saat diganti (untuk handle form ganda)
    // Sebenarnya sudah dihandle onchange, tapi ini memastikan tidak bentrok
</script>

<?php include 'footer.php'; ?>