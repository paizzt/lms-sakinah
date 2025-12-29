<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - SMAIT As-Sakinah</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="auth-body">

    <div class="auth-container">
        
        <div class="auth-left">
            <div class="auth-caption">
                <h1>Bergabunglah Menjadi<br>Bagian dari Kami.</h1>
                <p>Mencetak generasi cerdas, berakhlak mulia, dan siap bersaing di era global.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-box">
                
                <div class="auth-header">
                    <img src="assets/img/logo_sbs.png" height="50" style="margin-bottom: 20px;">
                    <h2>Buat Akun Siswa</h2>
                    <p>Silakan lengkapi data diri Anda untuk mendaftar.</p>
                </div>

                <form action="register_aksi.php" method="POST">
                    
                    <div class="input-group-modern">
                        <input type="text" name="nama" placeholder="Nama Lengkap" required>
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="input-group-modern">
                        <input type="email" name="email" placeholder="Alamat Email" required>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="input-group-modern">
                        <input type="text" name="username" placeholder="Username (untuk Login)" required>
                        <i class="fas fa-id-badge"></i>
                    </div>

                    <div class="input-group-modern">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class="fas fa-lock"></i>
                    </div>

                    <button type="submit" class="btn-auth">Daftar Sekarang</button>
                </form>

                <div class="auth-footer">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>