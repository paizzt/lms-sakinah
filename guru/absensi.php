<?php 
include 'header.php'; 
include 'sidebar.php'; 

$id_guru = $_SESSION['id_user'];

// QUERY: Ambil daftar kelas yang diajar guru ini
$q_mapel = mysqli_query($koneksi, "SELECT mapel.*, kelas.nama_kelas 
                                   FROM mapel 
                                   JOIN kelas ON mapel.kelas_id = kelas.id_kelas 
                                   WHERE mapel.guru_id = '$id_guru'
                                   ORDER BY kelas.nama_kelas ASC");
$jml_kelas = mysqli_num_rows($q_mapel);
?>

<style>
    /* STYLE CARD GRID (MODERN ORANGE) */
    .header-absensi {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-left: 5px solid #FF8C00;
    }
    
    .date-display {
        background: #FFF3E0;
        color: #E65100;
        padding: 8px 15px;
        font-weight: bold;
        border-radius: 20px;
        font-size: 14px;
    }

    .grid-kelas {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .card-kelas {
        background: white;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        color: #333;
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .card-kelas:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 140, 0, 0.15);
        border-color: #FF8C00;
    }

    .card-kelas h3 {
        margin: 10px 0 5px 0;
        font-size: 18px;
        font-weight: 800;
        color: #333;
    }

    .card-kelas p {
        margin: 0;
        color: #777;
        font-size: 14px;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="header-absensi">
        <div>
            <h2 style="margin:0; font-weight:800; color:#333;">Absensi Siswa</h2>
            <p style="margin:5px 0 0 0; color:#777;">Pilih kelas di bawah ini untuk mulai mengabsen.</p>
        </div>
        <div class="date-display">
            <i class="far fa-calendar-alt"></i> <?php echo date('d F Y'); ?>
        </div>
    </div>

    <div class="grid-kelas">
        <?php 
        if($jml_kelas > 0){
            while($d = mysqli_fetch_array($q_mapel)){
        ?>
            <a href="absensi_input.php?mapel=<?php echo $d['id_mapel']; ?>" class="card-kelas">
                <div class="icon-circle"><i class="fas fa-users"></i></div>
                <h3><?php echo $d['nama_kelas']; ?></h3>
                <p><?php echo $d['nama_mapel']; ?></p>
                <span style="margin-top:15px; font-size:12px; color:#FF8C00; font-weight:bold;">Klik untuk Absen <i class="fas fa-arrow-right"></i></span>
            </a>
        <?php 
            }
        } else {
            echo "<p style='grid-column:1/-1; text-align:center; padding:50px; color:#999;'>Anda belum memiliki jadwal kelas.</p>";
        }
        ?>
    </div>

</div>

<?php include 'footer.php'; ?>