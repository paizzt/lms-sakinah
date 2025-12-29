<?php 
session_start();
include '../config/koneksi.php';

$id_tugas = $_POST['id_tugas'];
$id_siswa = $_SESSION['id_user'];

$rand = rand();
$filename = $_FILES['file_jawaban']['name'];
$nama_file = $rand.'_'.$filename;

// Upload ke folder tugas
move_uploaded_file($_FILES['file_jawaban']['tmp_name'], '../uploads/tugas/'.$nama_file);

// Simpan ke database pengumpulan
mysqli_query($koneksi, "INSERT INTO pengumpulan VALUES(NULL, '$id_tugas', '$id_siswa', '$nama_file', 0, '', NOW())");

header("location:kirim_tugas.php?id=".$id_tugas);
?>