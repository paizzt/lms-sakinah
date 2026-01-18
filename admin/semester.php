<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, div, table, th, td, input, select, textarea, button {
            font-family: 'Poppins', sans-serif;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

<style>
    /* Overlay Gelap */
    .modal-overlay {
        display: none;
        position: fixed; 
        z-index: 9999; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.6); 
        backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Kotak Modal */
    .modal-box {
        background-color: #fff;
        width: 100%;
        max-width: 500px; 
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }

    @keyframes popUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Header Orange */
    .modal-header {
        background: linear-gradient(135deg, #FF8C00, #F39C12);
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-btn { cursor: pointer; font-size: 24px; transition: 0.3s; opacity: 0.8; }
    .close-btn:hover { opacity: 1; transform: rotate(90deg); }

    .modal-body { padding: 30px; background: #fdfdfd; }

    /* Form Styles */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
    .form-control-modal {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control-modal:focus {
        border-color: #FF8C00;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
    }

    .btn-submit-modal {
        width: 100%;
        background: linear-gradient(to right, #FF8C00, #F39C12);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 15px;
        transition: 0.3s;
        margin-top: 10px;
    }
    .btn-submit-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
    }
</style>

<div class="content-body" style="margin-top: -20px;">

    <div class="welcome-banner" style="background: linear-gradient(to right, #FF8C00, #F39C12); color: white; padding: 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(255, 140, 0, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 24px;"><i class="fas fa-calendar-alt"></i> Tahun Ajaran & Semester</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Atur semester aktif untuk sistem akademik.</p>
            </div>
            <div>
                <button onclick="bukaModal()" class="btn-tambah" style="background: white; color: #E65100; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-plus-circle"></i> Tambah Semester
                </button>
            </div>
        </div>
    </div>

    <div class="modern-form-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #FFF3E0; color: #E65100;">
                    <tr>
                        <th style="padding: 15px; width: 5%;">No</th>
                        <th style="padding: 15px;">Tahun Ajaran</th>
                        <th style="padding: 15px;">Semester</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $data = mysqli_query($koneksi,"SELECT * FROM semester ORDER BY id_semester DESC");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; color: #777;"><?php echo $no++; ?></td>
                        <td style="padding: 15px; font-weight: bold; color: #333;"><?php echo $d['tahun_ajaran']; ?></td>
                        <td style="padding: 15px;"><?php echo $d['semester']; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <?php 
                            if($d['status'] == 1){
                                echo "<span style='background:#d1f2eb; color:#0e6251; padding:6px 15px; border-radius:20px; font-size:12px; font-weight:bold; border:1px solid #a9dfbf;'><i class='fas fa-check-circle'></i> AKTIF</span>";
                            } else {
                                echo "<span style='background:#f2f3f4; color:#7f8c8d; padding:6px 15px; border-radius:20px; font-size:12px; font-weight:bold;'>Tidak Aktif</span>";
                            }
                            ?>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <?php if($d['status'] == 0){ ?>
                                <a href="semester_aktifkan.php?id=<?php echo $d['id_semester']; ?>" class="btn-action" title="Aktifkan" style="background: #e8f8f5; color: #2ecc71; padding: 8px 12px; border-radius: 6px; margin-right: 5px; text-decoration: none; font-weight: bold; font-size: 13px;">
                                    <i class="fas fa-power-off"></i> Aktifkan
                                </a>
                                <a href="semester_hapus.php?id=<?php echo $d['id_semester']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn-action delete" title="Hapus" style="background: #ffebee; color: #c62828; padding: 8px 12px; border-radius: 6px;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php } else { ?>
                                <span style="color: #ccc; font-size: 13px; font-style: italic;">Sedang Aktif</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalSemester" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-plus-square"></i> Tambah Semester Baru</h3>
            <span class="close-btn" onclick="tutupModal()">&times;</span>
        </div>
        <div class="modal-body">
            
            <form action="semester_aksi.php" method="POST">
                
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input type="text" name="tahun" class="form-control-modal" placeholder="Contoh: 2025/2026" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label>Semester</label>
                    <select name="semester" class="form-control-modal" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>

                <input type="hidden" name="status" value="0">

                <button type="submit" class="btn-submit-modal">
                    <i class="fas fa-save"></i> SIMPAN DATA
                </button>

            </form>

        </div>
    </div>
</div>

<script>
    // 1. Logic Buka Tutup Modal
    function bukaModal() {
        document.getElementById('modalSemester').style.display = "flex";
    }

    function tutupModal() {
        document.getElementById('modalSemester').style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById('modalSemester');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // 2. Logic Menerima Notifikasi PHP dan Menampilkan SweetAlert
    <?php if(isset($_SESSION['notif_status'])) { ?>
        
        Swal.fire({
            title: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "BERHASIL!" : "GAGAL!"; ?>',
            text: '<?php echo $_SESSION['notif_pesan']; ?>',
            icon: '<?php echo ($_SESSION['notif_status'] == 'sukses') ? "success" : "error"; ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FF8C00', // Warna Orange Konsisten
            background: '#fff',
            backdrop: `rgba(0,0,0,0.4)`
        });

    <?php 
        // Hapus session setelah ditampilkan agar tidak muncul lagi saat refresh
        unset($_SESSION['notif_status']);
        unset($_SESSION['notif_pesan']);
    } 
    ?>
</script>

<?php include 'footer.php'; ?>