<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$id_tugas = $_POST['id_tugas'];
$id_siswa = $_POST['id_siswa'];
$nilai    = $_POST['nilai'];
$catatan  = mysqli_real_escape_string($koneksi, $_POST['catatan']);

// Cek apakah siswa sudah punya record di pengumpulan_tugas?
$cek = mysqli_query($koneksi, "SELECT id_pengumpulan FROM pengumpulan_tugas WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'");

if(mysqli_num_rows($cek) > 0){
    // SKENARIO 1: SUDAH ADA RECORD (Entah sudah upload file atau belum)
    // Kita update nilainya
    $query = "UPDATE pengumpulan_tugas SET 
                nilai='$nilai', 
                catatan_guru='$catatan' 
              WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'";
} else {
    // SKENARIO 2: BELUM ADA RECORD (Siswa belum upload, tapi guru mau kasih nilai, misal 0)

    // Pastikan kolom file_tugas di database boleh kosong atau isi string kosong
    $tgl_sekarang = date('Y-m-d H:i:s');
    $query = "INSERT INTO pengumpulan_tugas (tugas_id, siswa_id, file_tugas, tgl_upload, nilai, catatan_guru) 
              VALUES ('$id_tugas', '$id_siswa', '', '$tgl_sekarang', '$nilai', '$catatan')";
}

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif_status'] = 'sukses';
    $_SESSION['notif_pesan']  = 'Nilai berhasil disimpan!';
} else {
    $_SESSION['notif_status'] = 'error';
    $_SESSION['notif_pesan']  = 'Gagal menyimpan nilai!';
}

// Redirect kembali ke halaman detail tugas tadi
header("location:tugas_nilai.php?id=".$id_tugas);
?>