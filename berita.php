<?php 
// Cek session untuk menentukan header mana yang dipakai
session_start();
include 'config/koneksi.php';

// Tentukan Header berdasarkan role (jika login)
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'admin'){
        include 'admin/header.php';
    } elseif($_SESSION['role'] == 'guru'){
        include 'guru/header.php';
    } elseif($_SESSION['role'] == 'siswa'){
        include 'siswa/header.php';
    }
} else {
    // Jika belum login (tamu), buat header sederhana manual atau redirect login
    // Disini kita redirect ke login saja agar aman, atau Anda bisa buat header_public.php
    header("location:login.php");
    exit();
}
?>

<div class="content-body" style="margin-top: -20px;">
    
    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; text-align: center; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <h2 style="margin: 0; font-size: 28px; font-weight: 700;">Kabar Sekolah</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">Informasi terbaru, prestasi, dan kegiatan SMAIT As-Sakinah</p>
    </div>

    <div class="row">
        <?php 
        // Logika Filter Berita berdasarkan Role User
        $role = $_SESSION['role'];
        $where_clause = "";

        if($role == 'siswa'){
            // Siswa hanya lihat pengumuman untuk 'siswa' dan 'semua'
            $where_clause = "WHERE tujuan IN ('semua', 'siswa')";
        } elseif($role == 'guru'){
            // Guru hanya lihat pengumuman untuk 'guru' dan 'semua'
            $where_clause = "WHERE tujuan IN ('semua', 'guru')";
        }
        // Admin melihat semua (tidak perlu WHERE tambahan)

        // Query Database
        $query = mysqli_query($koneksi, "SELECT * FROM pengumuman $where_clause ORDER BY id_pengumuman DESC");
        
        if(mysqli_num_rows($query) > 0){
            while($d = mysqli_fetch_array($query)){
        ?>
            <div class="col-md-12" style="margin-bottom: 20px;">
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column;">
                    
                    <div style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                            <div>
                                <span style="background: #FFF3E0; color: #E65100; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                    <?php echo $d['tujuan']; ?>
                                </span>
                                <h3 style="margin: 10px 0 5px 0; color: #333; font-size: 20px;"><?php echo $d['judul']; ?></h3>
                                <small style="color: #888;">
                                    <i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($d['tanggal_dibuat'])); ?>
                                    &bull; <i class="far fa-clock"></i> <?php echo date('H:i', strtotime($d['tanggal_dibuat'])); ?> WIB
                                </small>
                            </div>
                        </div>

                        <p style="color: #555; line-height: 1.6; font-size: 15px;">
                            <?php echo nl2br($d['isi']); ?>
                        </p>

                        <?php 
                            // PERBAIKAN: Gunakan 'file_lampiran' bukan 'gambar'
                            if(!empty($d['file_lampiran'])){ 
                                $file = $d['file_lampiran'];
                                $ext = pathinfo($file, PATHINFO_EXTENSION);
                                
                                // Cek apakah lampiran adalah GAMBAR
                                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])){
                        ?>
                                    <div style="margin-top: 20px;">
                                        <img src="uploads/pengumuman/<?php echo $file; ?>" style="max-width: 100%; border-radius: 10px; border: 1px solid #eee;" alt="Lampiran Gambar">
                                    </div>
                        <?php 
                                } else { 
                                // Jika BUKAN gambar (PDF, Doc, dll) tampilkan tombol download
                        ?>
                                    <div style="margin-top: 20px;">
                                        <a href="uploads/pengumuman/<?php echo $file; ?>" target="_blank" style="display: inline-flex; align-items: center; background: #f8f9fa; border: 1px solid #ddd; padding: 10px 20px; border-radius: 8px; text-decoration: none; color: #333; font-weight: 500;">
                                            <i class="fas fa-file-download" style="margin-right: 10px; color: #FF8C00;"></i> 
                                            Download Lampiran (<?php echo strtoupper($ext); ?>)
                                        </a>
                                    </div>
                        <?php 
                                }
                            } 
                        ?>

                    </div>
                </div>
            </div>

        <?php 
            }
        } else {
        ?>
            <div class="col-12" style="text-align: center; padding: 50px;">
                <img src="assets/img/empty.svg" style="width: 150px; opacity: 0.5; margin-bottom: 20px;">
                <p style="color: #999; font-size: 16px;">Belum ada berita atau pengumuman terbaru.</p>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
// Include Footer sesuai role agar modal & script tetap jalan
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'admin'){
        include 'admin/footer.php';
    } elseif($_SESSION['role'] == 'guru'){
        include 'guru/footer.php';
    } elseif($_SESSION['role'] == 'siswa'){
        include 'siswa/footer.php';
    }
}
?>