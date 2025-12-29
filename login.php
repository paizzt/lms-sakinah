<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SAKINAH BOARDING SCHOOL</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <div class="login-wrapper">
        
        <div class="login-left">
            <div class="brand-header">
                <img src="assets/img/logo_sbs.png" alt="Logo SBS" height="50">
                <h3>SAKINAH BOARDING SCHOOL</h3>
            </div>

            <div class="brand-center">
                <img src="assets/img/logo_smart.png" alt="Logo Smart" height="120">

            </div>

            <div class="login-footer">
                <h4>Call Center</h4>
                <div class="footer-info">
                    <span><i class="fas fa-phone-alt"></i> 01234567</span>
                    <span><i class="fas fa-envelope"></i> sas@gmail.com</span>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-card">
                
                <div class="user-icon-box">
                    <i class="far fa-user"></i>
                </div>

                <h2 class="login-title">silahkan login</h2>

                <?php 
                if(isset($_GET['pesan'])){
                    if($_GET['pesan'] == "gagal"){
                        echo "<div class='alert'>Username atau Password salah!</div>";
                    } else if($_GET['pesan'] == "logout"){
                        echo "<div class='alert' style='color:green; background:#d4edda;'>Anda berhasil logout.</div>";
                    } else if($_GET['pesan'] == "belum_login"){
                        echo "<div class='alert'>Silakan login dulu.</div>";
                    }
                }
                ?>

                <form action="cek_login.php" method="POST">
                    
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock input-icon" style="font-size:16px;"></i> <input type="password" name="password" id="passInput" placeholder="Password" required>
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePassword()"></i>
                    </div>

                    <div class="extra-links">
                        <a href="register.php">register here..</a>
                        <a href="#">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login-modern">LOGIN</button>
                </form>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("passInput");
            var icon = document.querySelector(".toggle-password");
            
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>
</html>