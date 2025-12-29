<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$guru_id = $_SESSION['id_user'];
$kelas_id = $_POST['kelas_id'];
// TANGKAP MAPEL ID DARI POST
$mapel_id = $_POST['mapel_id']; 
$tanggal = $_POST['tanggal'];

$siswa_ids = $_POST['siswa_id']; 
$statuses = $_POST['status']; 
$keterangans = $_POST['keterangan']; 

// Validasi sederhana agar tidak error database
if(empty($mapel_id) || empty($kelas_id)){
    echo "<script>alert('Error: Data Mapel atau Kelas hilang!'); window.history.back();</script>";
    exit();
}

foreach($siswa_ids as $id_siswa){
    
    $status = $statuses[$id_siswa];
    $keterangan = $keterangans[$id_siswa];
    
    // Cek apakah data sudah ada (Filter berdasarkan Tanggal DAN MAPEL)
    // Siswa bisa hadir di mapel A tapi bolos di mapel B pada hari yang sama
    $cek = mysqli_query($koneksi, "SELECT id_absensi FROM absensi WHERE siswa_id='$id_siswa' AND tanggal='$tanggal' AND mapel_id='$mapel_id'");
    
    if(mysqli_num_rows($cek) > 0){
        // UPDATE
        $update = "UPDATE absensi SET status='$status', keterangan='$keterangan', guru_id='$guru_id' 
                   WHERE siswa_id='$id_siswa' AND tanggal='$tanggal' AND mapel_id='$mapel_id'";
        mysqli_query($koneksi, $update);
    } else {
        // INSERT (Wajib menyertakan mapel_id)
        $insert = "INSERT INTO absensi (mapel_id, kelas_id, siswa_id, guru_id, tanggal, status, keterangan) 
                   VALUES ('$mapel_id', '$kelas_id', '$id_siswa', '$guru_id', '$tanggal', '$status', '$keterangan')";
        
        if(!mysqli_query($koneksi, $insert)){
            // Debugging jika masih error
            echo "Gagal Insert: " . mysqli_error($koneksi);
            exit();
        }
    }
}

echo "<script>alert('Data absensi berhasil disimpan!'); window.location='absensi.php';</script>";
?>