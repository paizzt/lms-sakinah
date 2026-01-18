<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$mapel_id = $_POST['mapel_id'];
$tanggal  = $_POST['tanggal'];
$status   = $_POST['status']; 
$ket      = $_POST['ket'];    

$sukses = 0;

if(!empty($status)){
    foreach($status as $id_siswa => $nilai_status){
        
        $keterangan_siswa = mysqli_real_escape_string($koneksi, $ket[$id_siswa]);

        // Cek apakah data sudah ada?
        $cek = mysqli_query($koneksi, "SELECT id_absensi FROM absensi WHERE mapel_id='$mapel_id' AND siswa_id='$id_siswa' AND tanggal='$tanggal'");
        
        if(mysqli_num_rows($cek) > 0){
            // UPDATE
            $update = mysqli_query($koneksi, "UPDATE absensi SET status='$nilai_status', keterangan='$keterangan_siswa' WHERE mapel_id='$mapel_id' AND siswa_id='$id_siswa' AND tanggal='$tanggal'");
            if($update) $sukses++;
        } else {
            // INSERT
            $insert = mysqli_query($koneksi, "INSERT INTO absensi (mapel_id, siswa_id, tanggal, status, keterangan) VALUES ('$mapel_id', '$id_siswa', '$tanggal', '$nilai_status', '$keterangan_siswa')");
            if($insert) $sukses++;
        }
    }
}

if($sukses > 0){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Absensi berhasil disimpan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menyimpan!';
}

// Redirect kembali ke halaman INPUT
header("location:absensi_input.php?mapel=$mapel_id&tgl=$tanggal");
?>