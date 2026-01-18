<?php 
include 'header.php'; 
include 'sidebar.php'; 

// 1. AMBIL DATA KELAS SISWA (Sama seperti dashboard agar aman)
$id_siswa = $_SESSION['id_user'];
$cek_siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT kelas_id FROM users WHERE id_user='$id_siswa'"));
$id_kelas = $cek_siswa['kelas_id'];

// Jika belum punya kelas
if(empty($id_kelas)){
    echo "<script>alert('Anda belum masuk ke kelas manapun!'); window.location='index.php';</script>";
    exit();
}

// 2. AMBIL DAFTAR MAPEL SESUAI KELAS
// Kita JOIN dengan tabel users untuk mengambil nama guru
$query_mapel = "SELECT mapel.*, users.nama_lengkap AS nama_guru, users.foto_profil 
                FROM mapel 
                LEFT JOIN users ON mapel.guru_id = users.id_user 
                WHERE mapel.kelas_id = '$id_kelas' 
                ORDER BY mapel.nama_mapel ASC";
$q_mapel = mysqli_query($koneksi, $query_mapel);
$jml_mapel = mysqli_num_rows($q_mapel);

// Ambil Nama Kelas untuk Judul
$d_kelas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id_kelas='$id_kelas'"));
?>

<style>
    /* STYLE CARD MAPEL */
    .grid-mapel {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .card-mapel {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: 0.3s;
        border: 1px solid #eee;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-mapel:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 140, 0, 0.15);
        border-color: #FF8C00;
    }

    /* Bagian Atas Card */
    .mapel-header {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        padding: 20px;
        color: white;
        position: relative;
    }
    .mapel-icon {
        position: absolute;
        right: 15px;
        bottom: 10px;
        font-size: 50px;
        opacity: 0.2;
        color: white;
    }
    
    .mapel-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        position: relative;
    }
    .mapel-desc {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 5px;
        z-index: 2;
        position: relative;
    }

    /* Bagian Tengah (Guru) */
    .mapel-teacher {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px dashed #eee;
    }
    .teacher-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee;
    }
    .teacher-info div { font-size: 11px; color: #999; font-weight: bold; }
    .teacher-info h5 { margin: 0; font-size: 14px; color: #333; }

    /* Bagian Bawah (Tombol) */
    .mapel-actions {
        padding: 15px;
        background: #fdfdfd;
        display: flex;
        gap: 10px;
    }
    .btn-action-mapel {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
        transition: 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
    
    .btn-materi {
        background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb;
    }
    .btn-materi:hover { background: #1565c0; color: white; }

    .btn-tugas {
        background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2;
    }
    .btn-tugas:hover { background: #e65100; color: white; }

</style>

<div class="content-body" style="margin-top: -20px;">

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #FF8C00;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: #333;">Mata Pelajaran</h2>
            <p style="margin: 5px 0 0 0; color: #777;">Daftar pelajaran di kelas <b><?php echo $d_kelas['nama_kelas']; ?></b></p>
        </div>
        <div style="background: #f5f5f5; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; color: #555;">
            Total: <?php echo $jml_mapel; ?> Mapel
        </div>
    </div>

    <div class="grid-mapel">
        <?php 
        if($jml_mapel > 0){
            while($m = mysqli_fetch_array($q_mapel)){
                // Data Guru
                $foto_guru = ($m['foto_profil'] && $m['foto_profil'] != 'default.jpg') ? "../uploads/profil/".$m['foto_profil'] : "../assets/img/avatar-default.svg";
                $nama_guru = !empty($m['nama_guru']) ? $m['nama_guru'] : "Belum ditentukan";
                $deskripsi = !empty($m['deskripsi']) ? substr($m['deskripsi'], 0, 50)."..." : "Tidak ada deskripsi.";
        ?>
        <div class="card-mapel">
            
            <div class="mapel-header">
                <i class="fas fa-book-open mapel-icon"></i>
                <h3 class="mapel-title"><?php echo $m['nama_mapel']; ?></h3>
                <div class="mapel-desc"><?php echo $deskripsi; ?></div>
            </div>

            <div class="mapel-teacher">
                <img src="<?php echo $foto_guru; ?>" class="teacher-img">
                <div class="teacher-info">
                    <div>PENGAJAR</div>
                    <h5><?php echo $nama_guru; ?></h5>
                </div>
            </div>

            <div class="mapel-actions">
                <a href="materi.php?mapel=<?php echo $m['id_mapel']; ?>" class="btn-action-mapel btn-materi">
                    <i class="fas fa-file-alt"></i> Materi
                </a>
                
                <a href="tugas.php?mapel=<?php echo $m['id_mapel']; ?>" class="btn-action-mapel btn-tugas">
                    <i class="fas fa-tasks"></i> Tugas
                </a>
            </div>

        </div>
        <?php 
            }
        } else {
            echo "<div style='grid-column: 1/-1; text-align:center; padding:50px; background:white; border-radius:15px; color:#999;'>
                    <img src='../assets/img/no-data.svg' style='width:100px; opacity:0.5; margin-bottom:15px;'>
                    <br>Belum ada mata pelajaran untuk kelas ini.
                  </div>";
        }
        ?>
    </div>

</div>

<?php include 'footer.php'; ?>