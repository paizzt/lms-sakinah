</div> 
        </div> 
    </div> 

    <div id="newsModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-bullhorn" style="color: #FF8C00;"></i> Info Guru & Sekolah</h3>
                <button class="btn-close-modal" onclick="toggleNewsModal()">&times;</button>
            </div>
            <div class="modal-body">
                
                <?php 
                if(isset($koneksi)){
                    // PERBAIKAN 1: Filter untuk Guru ('semua' dan 'guru')
                    $q_news = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tujuan IN ('semua', 'guru') ORDER BY id_pengumuman DESC LIMIT 5");
                    
                    if(mysqli_num_rows($q_news) > 0){
                        while($news = mysqli_fetch_array($q_news)){
                ?>
                        <div class="news-item">
                            <div class="news-title" onclick="toggleDetail('news_<?php echo $news['id_pengumuman']; ?>')">
                                <span><?php echo $news['judul']; ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: #ccc;"></i>
                            </div>
                            <small class="news-date">
                                <i class="far fa-clock"></i> <?php echo date('d F Y', strtotime($news['tanggal_dibuat'])); ?> 
                                <span class="badge-target target-<?php echo $news['tujuan']; ?>" style="margin-left:5px; font-size:10px; padding:2px 6px;">
                                    <?php echo ucfirst($news['tujuan']); ?>
                                </span>
                            </small>
                            
                            <div id="news_<?php echo $news['id_pengumuman']; ?>" class="news-detail">
                                <p><?php echo nl2br($news['isi']); ?></p>
                                
                                <?php if(!empty($news['file_lampiran'])){ ?>
                                    <div style="margin-top: 10px; padding: 10px; background: #eee; border-radius: 5px;">
                                        <i class="fas fa-paperclip"></i> 
                                        <a href="../uploads/pengumuman/<?php echo $news['file_lampiran']; ?>" target="_blank">Lihat Lampiran</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                <?php 
                        }
                    } else {
                        echo "<p style='text-align:center; color:#999; padding:20px;'>Tidak ada pengumuman baru.</p>";
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
        document.addEventListener("DOMContentLoaded", function() {
            var sidebar = document.querySelector('.sidebar');
            var savedStatus = localStorage.getItem('sidebarStatus');
            if (savedStatus === 'active') sidebar.classList.add('active');
        });

        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            localStorage.setItem('sidebarStatus', sidebar.classList.contains('active') ? 'active' : 'closed');
        }

        function toggleNewsModal() { document.getElementById("newsModal").classList.toggle("show"); }

        function toggleDetail(id) {
            var detail = document.getElementById(id);
            detail.style.display = (detail.style.display === "block") ? "none" : "block";
        }

        window.onclick = function(event) {
            if (!event.target.matches('.btn-menu-action') && !event.target.matches('.btn-menu-action i')) {
                var dropdowns = document.getElementsByClassName("action-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('active')) dropdowns[i].classList.remove('active');
                }
            }
            var modal = document.getElementById("newsModal");
            if (event.target == modal) modal.classList.remove("show");
        }
    </script>
</body>
</html>