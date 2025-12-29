<?php 
session_start();
include '../config/koneksi.php';

$tugas_id = $_POST['tugas_id'];
$mapel_id = $_POST['mapel_id'];
$siswa_id = $_SESSION['id_user'];
$tanggal = date('Y-m-d H:i:s');

// Upload File
$rand = rand();
$filename = $_FILES['file_siswa']['name'];
$file_baru = "";

if($filename != ""){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid = array('pdf','doc','docx','jpg','jpeg','png');
    
    if(in_array(strtolower($ext), $valid)){
        $file_baru = $rand.'_'.$filename;
        // Pastikan folder ini ada: uploads/tugas_siswa
        move_uploaded_file($_FILES['file_siswa']['tmp_name'], '../uploads/tugas_siswa/'.$file_baru);
        
        // Simpan ke DB
        mysqli_query($koneksi, "INSERT INTO pengumpulan (tugas_id, siswa_id, file_siswa, tanggal_kumpul) VALUES ('$tugas_id', '$siswa_id', '$file_baru', '$tanggal')");
        
        echo "<script>alert('Tugas berhasil dikirim!'); window.location='ruang_kelas.php?id=$mapel_id';</script>";
    } else {
        echo "<script>alert('Format file tidak didukung!'); window.location='ruang_kelas.php?id=$mapel_id';</script>";
    }
} else {
    header("location:ruang_kelas.php?id=$mapel_id");
}
?>