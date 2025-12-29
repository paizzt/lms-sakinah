<?php 
include '../config/koneksi.php';
$id = $_GET['id'];

// Cek status dulu, jangan hapus semester yang sedang Aktif agar sistem tidak error
$cek = mysqli_query($koneksi, "SELECT status FROM semester WHERE id_semester='$id'");
$d = mysqli_fetch_array($cek);

if($d['status'] == 1){
    echo "<script>alert('Gagal! Semester yang sedang AKTIF tidak boleh dihapus. Pindahkan status aktif ke semester lain terlebih dahulu.'); window.location='semester.php';</script>";
} else {
    mysqli_query($koneksi, "DELETE FROM semester WHERE id_semester='$id'");
    header("location:semester.php");
}
?>