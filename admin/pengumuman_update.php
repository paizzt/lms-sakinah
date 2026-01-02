<?php 
include '../config/koneksi.php';

$id     = $_POST['id'];
$judul  = $_POST['judul'];
$isi    = $_POST['isi'];
$tujuan = $_POST['tujuan'];

// Cek apakah user ingin mengubah file lampiran
$rand = rand();
$allowed =  array('png','jpg','jpeg','pdf','doc','docx');
$filename = $_FILES['foto']['name'];

if($filename == "") {
    // JIKA TIDAK UPLOAD FILE BARU -> Update data teks saja
    mysqli_query($koneksi, "UPDATE pengumuman SET judul='$judul', isi='$isi', tujuan='$tujuan' WHERE id_pengumuman='$id'");
    header("location:pengumuman.php?pesan=update");

} else {
    // JIKA UPLOAD FILE BARU
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(!in_array($ext, $allowed) ) {
        header("location:pengumuman.php?pesan=gagal_ekstensi");
    } else {
        // Ambil nama file lama untuk dihapus (opsional, agar hemat storage)
        $q_lama = mysqli_query($koneksi, "SELECT file_lampiran FROM pengumuman WHERE id_pengumuman='$id'");
        $d_lama = mysqli_fetch_assoc($q_lama);
        if($d_lama['file_lampiran'] != ""){
            unlink('../uploads/pengumuman/'.$d_lama['file_lampiran']);
        }

        // Upload file baru
        $xx = $rand.'_'.$filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/pengumuman/'.$xx);
        
        // Update database dengan nama file baru
        mysqli_query($koneksi, "UPDATE pengumuman SET judul='$judul', isi='$isi', tujuan='$tujuan', file_lampiran='$xx' WHERE id_pengumuman='$id'");
        header("location:pengumuman.php?pesan=update");
    }
}
?>