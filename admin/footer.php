</div> 
        </div> 
    </div> 

    <div id="newsModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-bullhorn" style="color: #FF8C00;"></i> Pusat Informasi</h3>
                <button class="btn-close-modal" onclick="toggleNewsModal()">&times;</button>
            </div>
            <div class="modal-body">
                
                <?php 
                if(isset($koneksi)){
                    // PERBAIKAN: Hapus WHERE tujuan... agar Admin melihat SEMUA data
                    // Ganti 'tanggal_dibuat' jadi 'tanggal' sesuai database sebelumnya
                    $q_news = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5");
                    
                    if(mysqli_num_rows($q_news) > 0){
                        while($news = mysqli_fetch_array($q_news)){
                            // Logika warna badge status
                            $tujuan = $news['tujuan'];
                            $warna_badge = ($tujuan == 'Semua') ? '#7e57c2' : (($tujuan == 'Guru') ? '#2980b9' : '#27ae60');
                ?>
                        <div class="news-item">
                            <div class="news-title" onclick="toggleDetail('news_<?php echo $news['id_pengumuman']; ?>')">
                                <span><?php echo $news['judul']; ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: #ccc;"></i>
                            </div>
                            <small class="news-date">
                                <i class="far fa-clock"></i> <?php echo date('d F Y', strtotime($news['tanggal'])); ?> 
                                
                                <span style="margin-left:5px; font-size:10px; padding:2px 8px; border-radius:10px; color:white; background: <?php echo $warna_badge; ?>;">
                                    <?php echo strtoupper($news['tujuan']); ?>
                                </span>
                            </small>
                            
                            <div id="news_<?php echo $news['id_pengumuman']; ?>" class="news-detail">
                                <p><?php echo nl2br($news['isi']); ?></p>
                                
                                <?php if(!empty($news['file_lampiran'])){ ?>
                                    <div style="margin-top: 10px; padding: 10px; background: #eee; border-radius: 5px;">
                                        <i class="fas fa-paperclip"></i> 
                                        <a href="../uploads/pengumuman/<?php echo $news['file_lampiran']; ?>" target="_blank" style="text-decoration:none; color:#E65100; font-weight:bold;">Lihat Lampiran</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                <?php 
                        }
                    } else {
                        echo "<p style='text-align:center; color:#999; padding:20px;'>Belum ada pengumuman.</p>";
                    }
                }
                ?>

            </div>
            <div style="padding: 15px; background: #fcfcfc; border-top: 1px solid #eee; text-align: center;">
                <a href="pengumuman.php" style="color: #667eea; text-decoration: none; font-weight: bold; font-size: 14px;">
                    Kelola Semua Pengumuman <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Script Sidebar & Modal
        document.addEventListener("DOMContentLoaded", function() {
            var sidebar = document.querySelector('.sidebar');
            // Cek localStorage agar sidebar tetap pada posisi terakhir (buka/tutup) saat refresh
            var savedStatus = localStorage.getItem('sidebarStatus');
            if (savedStatus === 'active' && sidebar) sidebar.classList.add('active');
        });

        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            localStorage.setItem('sidebarStatus', sidebar.classList.contains('active') ? 'active' : 'closed');
        }

        function toggleNewsModal() { 
            document.getElementById("newsModal").classList.toggle("show"); 
        }

        function toggleDetail(id) {
            var detail = document.getElementById(id);
            // Efek toggle sederhana
            if (detail.style.display === "block") {
                detail.style.display = "none";
            } else {
                // Tutup detail lain jika ingin accordion (opsional, biarkan terbuka semua juga oke)
                detail.style.display = "block";
            }
        }

        // Tutup modal jika klik di luar area modal
        window.onclick = function(event) {
            var modal = document.getElementById("newsModal");
            if (event.target == modal) {
                modal.classList.remove("show");
            }
        }
    </script>
</body>
</html>