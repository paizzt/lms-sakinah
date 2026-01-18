<?php 
session_start();
include '../config/koneksi.php';

if($_SESSION['role'] != "siswa"){ header("location:../index.php"); exit(); }

$id_siswa = $_SESSION['id_user'];
$id_tugas = $_POST['id_tugas'];
$tgl_upload = date('Y-m-d H:i:s');

// 1. PROSES UPLOAD FILE
$filename = $_FILES['file_jawaban']['name'];
$filesize = $_FILES['file_jawaban']['size'];
$filetmp  = $_FILES['file_jawaban']['tmp_name'];

if($filename != ""){
    // Cek Ekstensi
    $allowed = array('pdf','doc','docx','xls','xlsx','ppt','pptx','zip','rar','jpg','jpeg','png');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(!in_array(strtolower($ext), $allowed)){
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Format file tidak diizinkan!';
        header("location:tugas_detail.php?id=$id_tugas");
        exit();
    }

    // Cek Ukuran (Max 5MB = 5 * 1024 * 1024)
    if($filesize > 5242880){
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Ukuran file terlalu besar! (Max 5MB)';
        header("location:tugas_detail.php?id=$id_tugas");
        exit();
    }

    // Buat Nama Unik
    $nama_file_baru = rand() . '_' . str_replace(' ', '_', $filename);
    
    // Pastikan folder ada
    if(!is_dir("../uploads/tugas_siswa")) mkdir("../uploads/tugas_siswa");

    // Upload
    if(move_uploaded_file($filetmp, '../uploads/tugas_siswa/' . $nama_file_baru)){
        
        // 2. SIMPAN KE DATABASE
        // Cek dulu, ini INSERT baru atau UPDATE (Re-upload)?
        $cek = mysqli_query($koneksi, "SELECT * FROM pengumpulan_tugas WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'");
        
        if(mysqli_num_rows($cek) > 0){
            // UPDATE (Hapus file lama dulu biar bersih)
            $data_lama = mysqli_fetch_assoc($cek);
            if(file_exists("../uploads/tugas_siswa/".$data_lama['file_tugas'])){
                unlink("../uploads/tugas_siswa/".$data_lama['file_tugas']);
            }

            $query = "UPDATE pengumpulan_tugas SET file_tugas='$nama_file_baru', tgl_upload='$tgl_upload' 
                      WHERE tugas_id='$id_tugas' AND siswa_id='$id_siswa'";
        } else {
            // INSERT BARU
            $query = "INSERT INTO pengumpulan_tugas (tugas_id, siswa_id, file_tugas, tgl_upload, nilai) 
                      VALUES ('$id_tugas', '$id_siswa', '$nama_file_baru', '$tgl_upload', NULL)";
        }

        if(mysqli_query($koneksi, $query)){
            $_SESSION['notif_status'] = 'sukses';
            $_SESSION['notif_pesan']  = 'Tugas berhasil dikirim!';
        } else {
            $_SESSION['notif_status'] = 'gagal';
            $_SESSION['notif_pesan']  = 'Gagal menyimpan ke database!';
        }

    } else {
        $_SESSION['notif_status'] = 'gagal';
        $_SESSION['notif_pesan']  = 'Gagal mengupload file ke server!';
    }

} else {
    $_SESSION['notif_status'] = 'gagal';
    $_SESSION['notif_pesan']  = 'Anda belum memilih file!';
}

header("location:tugas_detail.php?id=$id_tugas");
?>