<?php
include '../config/koneksi.php';

echo "<h3>Memperbaiki Database...</h3>";

// 1. Cek apakah kolom tahun_ajaran sudah ada?
$cek = mysqli_query($koneksi, "SHOW COLUMNS FROM kelas LIKE 'tahun_ajaran'");
if(mysqli_num_rows($cek) == 0){
    // Jika belum ada, tambahkan
    $sql1 = "ALTER TABLE kelas ADD COLUMN tahun_ajaran VARCHAR(20) DEFAULT '-'";
    if(mysqli_query($koneksi, $sql1)){
        echo " Berhasil menambahkan kolom <b>tahun_ajaran</b>.<br>";
    } else {
        echo " Gagal menambahkan kolom tahun_ajaran: " . mysqli_error($koneksi) . "<br>";
    }
} else {
    echo "ℹ Kolom <b>tahun_ajaran</b> sudah ada.<br>";
}

// 2. Cek apakah kolom semester sudah ada?
$cek2 = mysqli_query($koneksi, "SHOW COLUMNS FROM kelas LIKE 'semester'");
if(mysqli_num_rows($cek2) == 0){
    // Jika belum ada, tambahkan
    $sql2 = "ALTER TABLE kelas ADD COLUMN semester VARCHAR(20) DEFAULT '-'";
    if(mysqli_query($koneksi, $sql2)){
        echo " Berhasil menambahkan kolom <b>semester</b>.<br>";
    } else {
        echo " Gagal menambahkan kolom semester: " . mysqli_error($koneksi) . "<br>";
    }
} else {
    echo "ℹ Kolom <b>semester</b> sudah ada.<br>";
}

echo "<br><a href='kelas.php'>Kembali ke Manajemen Kelas</a>";
?>