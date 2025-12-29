<div class="modal-backdrop" id="newsModal">
        <div class="modal-content">
            
            <div class="modal-header">
                <h3><i class="fas fa-newspaper" style="color: #FF8C00; margin-right: 10px;"></i> Kabar & Pengumuman</h3>
                <button class="btn-close-modal" onclick="toggleNewsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <?php 
                // Pastikan koneksi database tersedia (biasanya sudah include di header)
                // Sesuaikan query berdasarkan role user (siswa/guru/admin)
                // Disini kita ambil 'semua' + role user saat ini
                
                // Cek Role user untuk filter berita
                $role_user = isset($_SESSION['role']) ? $_SESSION['role'] : 'umum';
                
                // Query mengambil 5 berita terbaru
                $q_news = mysqli_query($koneksi, "SELECT * FROM pengumuman 
                                                  WHERE tujuan IN ('semua', '$role_user') 
                                                  ORDER BY tanggal_dibuat DESC LIMIT 5");
                
                if(mysqli_num_rows($q_news) == 0){
                    echo "<p style='text-align:center; color:#999; padding:20px;'>Tidak ada berita terbaru.</p>";
                }

                while($n = mysqli_fetch_array($q_news)){
                    $gambar = $n['gambar'] ? "../uploads/berita/".$n['gambar'] : "";
                ?>
                
                <div class="news-item">
                    <small class="news-date">
                        <i class="far fa-clock"></i> <?php echo date('d M Y', strtotime($n['tanggal_dibuat'])); ?>
                    </small>
                    
                    <div class="news-title" onclick="toggleNewsDetail(<?php echo $n['id_pengumuman']; ?>)">
                        <?php echo $n['judul']; ?>
                        <i class="fas fa-chevron-down" style="font-size: 12px; color: #ccc;"></i>
                    </div>

                    <div class="news-detail" id="detail-<?php echo $n['id_pengumuman']; ?>">
                        <?php if($gambar) { ?>
                            <img src="<?php echo $gambar; ?>" style="width: 100%; border-radius: 8px; margin-bottom: 10px;">
                        <?php } ?>
                        
                        <div style="text-align: justify;">
                            <?php echo nl2br($n['isi']); ?>
                        </div>
                    </div>
                </div>

                <?php } ?>
            </div>

        </div>
    </div>

    <script>
        // Fungsi Buka/Tutup Modal Utama
        function toggleNewsModal() {
            var modal = document.getElementById("newsModal");
            modal.classList.toggle("show");
        }

        // Fungsi Buka/Tutup Detail Berita (Accordion)
        function toggleNewsDetail(id) {
            var detail = document.getElementById("detail-" + id);
            
            // Tutup detail lain biar rapi (opsional)
            var allDetails = document.querySelectorAll('.news-detail');
            allDetails.forEach(function(el) {
                if (el !== detail) {
                    el.style.display = 'none';
                }
            });

            // Toggle yang diklik
            if (detail.style.display === "block") {
                detail.style.display = "none";
            } else {
                detail.style.display = "block";
            }
        }

        // Tutup modal jika klik di luar kotak (backdrop)
        window.onclick = function(event) {
            var modal = document.getElementById("newsModal");
            if (event.target == modal) {
                modal.classList.remove("show");
            }
        }
    </script>

</div> 
</div>
</body>
</html>