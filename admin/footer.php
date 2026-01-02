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
        // 1. SAAT HALAMAN DIMUAT: Cek memori LocalStorage
        document.addEventListener("DOMContentLoaded", function() {
            var sidebar = document.querySelector('.sidebar');
            var savedStatus = localStorage.getItem('sidebarStatus');

            // Jika sebelumnya sidebar terbuka ('active'), maka buka lagi sekarang
            if (savedStatus === 'active') {
                sidebar.classList.add('active');
            }
        });

        // 2. FUNGSI KLIK TOMBOL: Buka/Tutup & Simpan ke Memori
        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            
            // Toggle class active
            sidebar.classList.toggle('active');

            // Cek kondisi sekarang dan simpan ke LocalStorage
            if (sidebar.classList.contains('active')) {
                localStorage.setItem('sidebarStatus', 'active'); // Simpan status 'terbuka'
            } else {
                localStorage.setItem('sidebarStatus', 'closed'); // Simpan status 'tertutup'
            }
        }

        // 3. Dropdown Menu Profil (Opsional, jika ada di header)
        // Menutup dropdown jika klik di luar area
        window.onclick = function(event) {
            if (!event.target.matches('.btn-menu-action') && !event.target.matches('.btn-menu-action i')) {
                var dropdowns = document.getElementsByClassName("action-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('active')) {
                        dropdowns[i].classList.remove('active');
                    }
                }
            }
        }
    
    </script>

</div> 
</div>

</body>
</html>