<?php 
session_start();
include '../config/koneksi.php';

// Validasi Guru
if($_SESSION['role'] != "guru"){ header("location:../index.php"); exit(); }

$mapel_id = $_POST['mapel_id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$link = $_POST['link_materi'];
$tanggal = date('Y-m-d H:i:s');

// Persiapan Upload File
$rand = rand();
$filename = $_FILES['file_materi']['name'];
$file_baru = "";

// Jika ada file yang diupload
if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_ext = array('pdf','doc','docx','ppt','pptx','xls','xlsx');

    // Cek ekstensi file
    if(in_array(strtolower($ext), $valid_ext)){
        $file_baru = $rand.'_'.$filename;
        // Upload ke folder uploads/materi
        move_uploaded_file($_FILES['file_materi']['tmp_name'], '../uploads/materi/'.$file_baru);
    } else {
        echo "<script>alert('Format file tidak didukung! Gunakan PDF, Word, atau PPT.'); window.location='materi_tambah.php';</script>";
        exit();
    }
}

// Query Insert
$query = "INSERT INTO materi (mapel_id, judul_materi, deskripsi, file_materi, link_materi, tanggal_upload) 
          VALUES ('$mapel_id', '$judul', '$deskripsi', '$file_baru', '$link', '$tanggal')";

if(mysqli_query($koneksi, $query)){
    echo "<script>alert('Materi berhasil diupload!'); window.location='materi.php';</script>";
} else {
    echo "<script>alert('Gagal mengupload materi.'); window.location='materi_tambah.php';</script>";
}
?>