</div> 
        </div> 
    </div> 

    <div id="newsModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-bullhorn" style="color: #FF8C00;"></i> Papan Pengumuman</h3>
                <button class="btn-close-modal" onclick="toggleNewsModal()">&times;</button>
            </div>
            <div class="modal-body">
                
                <?php 
                if(isset($koneksi)){
                    // FILTER: Hanya tampilkan pengumuman untuk 'semua' atau 'siswa'
                    $q_news = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tujuan IN ('semua', 'siswa') ORDER BY tanggal DESC LIMIT 5");
                    
                    if(mysqli_num_rows($q_news) > 0){
                        while($news = mysqli_fetch_array($q_news)){
                            $tujuan = $news['tujuan'];
                            // Warna badge: Ungu (Semua), Hijau (Siswa)
                            $warna_badge = ($tujuan == 'Semua') ? '#7e57c2' : '#27ae60';
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
                        echo "<div style='text-align:center; padding:30px; color:#999;'>
                                <img src='../assets/img/completed.svg' style='width:60px; opacity:0.5; margin-bottom:10px;'>
                                <p>Tidak ada pengumuman baru.</p>
                              </div>";
                    }
                }
                ?>

            </div>
            <div style="padding: 15px; background: #fcfcfc; border-top: 1px solid #eee; text-align: center;">
                <a href="../berita.php" style="color: #667eea; text-decoration: none; font-weight: bold; font-size: 14px;">
                    Lihat Semua Berita <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            var content = document.querySelector('.content-body');
            
            sidebar.classList.toggle('active');
            if(content) content.classList.toggle('active');

            // Simpan status sidebar
            localStorage.setItem('sidebarStatus', sidebar.classList.contains('active') ? 'active' : 'closed');
        }

        // Cek status sidebar saat load
        document.addEventListener("DOMContentLoaded", function() {
            var savedStatus = localStorage.getItem('sidebarStatus');
            var sidebar = document.querySelector('.sidebar');
            var content = document.querySelector('.content-body');
            
            if (savedStatus === 'active') {
                sidebar.classList.add('active');
                if(content) content.classList.add('active');
            }
        });

        // Toggle Modal Berita
        function toggleNewsModal() { 
            document.getElementById("newsModal").classList.toggle("show"); 
        }

        // Accordion Berita
        function toggleDetail(id) {
            var detail = document.getElementById(id);
            if (detail.style.display === "block") {
                detail.style.display = "none";
            } else {
                detail.style.display = "block";
            }
        }

        // Klik di luar modal untuk menutup
        window.onclick = function(event) {
            var modal = document.getElementById("newsModal");
            if (event.target == modal) {
                modal.classList.remove("show");
            }
        }
    </script>
</body>
</html>