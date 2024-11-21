<?php
session_start();
include 'database.php'; // Menghubungkan ke database

// Cek jika form login dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Mengambil data dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan email
    $sql = "SELECT * FROM akun WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mengambil data user
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];  // Store role if needed
            $_SESSION['username'] = $user['username'];  // Add username to session
            $_SESSION['avatar'] = $user['avatar']; // Menyimpan peran pengguna (user/admin)
            echo "<script>alert('Login berhasil! Selamat datang, " . $user['email'] . "');</script>";
            header("Location: login.php"); // Arahkan kembali ke halaman login setelah login
        } else {
            // Password tidak cocok
            $login_error = "Password salah!";
        }
    } else {
        // email tidak ditemukan
        $login_error = "Email tidak ditemukan!";
    }
}

// Cek jika form signup dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Mengambil data dari form signup
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifikasi apakah password dan konfirmasi password cocok
    if ($password != $confirm_password) {
        $signup_error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Tentukan peran default (misalnya 'user')
        $role = 'user'; // Anda bisa mengubahnya sesuai dengan kebutuhan

        // Query untuk menyimpan data pengguna baru
        $sql = "INSERT INTO akun (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registrasi berhasil!');</script>";
            header("Location: login.php"); // Arahkan ke halaman login setelah registrasi
        } else {
            $signup_error = "Terjadi kesalahan saat mendaftar: " . $conn->error;
        }
    }
}

// Fungsi Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php"); // Arahkan ke halaman login setelah logout
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Encore Shield</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/background\ home.png');
            background-size: cover;
            background-position: center;
        }
        .login-container, .signup-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .btn-bck {
            display: inline-block;
            padding: 3px 15px;
            font-size: 24px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #04AA6D;
            border: none;
            border-radius: 15px;
            box-shadow: 0 9px #999;
        }

        .btn-bck:hover {background-color: #3e8e41}

        .btn-bck:active {
            background-color: #3e8e41;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }
    </style>
</head>
<body>
                <a href="home.php">
                <button class="btn btn-bck">Home</button>
                 </a>
                 <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <?php if (isset($_SESSION['email'])) { ?>
            <!-- Jika pengguna sudah login -->
            <div class="login-container text-center">
                <h2 class="mb-4">Hallo selamat datang!! <br><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></h2>
                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <img src="https://lh3.googleusercontent.com/proxy/9rLje0-3FNznCiW_PB26zLjadDVYYEDc6WyBUIcYBKXfbLZN8VMuPw_lBCo2FRl6ap4JPSUJGCqL8Q6FFb3oNEOY2JGJPKfGq_LdtfcP6nnp3dCWqZwQ27aW8_hbp3Zxcy9_rWFxryXb" alt="Loading...">
                </div>
            </div>
            <script>
                // Tampilkan loading setelah login berhasil
                document.addEventListener('DOMContentLoaded', function () {
                    var loadingElement = document.getElementById('loading');
                    loadingElement.style.display = 'block'; // Menampilkan loading
                    setTimeout(function () {
                        loadingElement.style.display = 'none'; // Menghilangkan loading setelah 3 detik
                        window.location.href = 'home.php'; // Redirect ke halaman Home
                    }, 2000); 
                });
            </script>
        <?php } else { ?>
            <!-- Jika pengguna belum login, tampilkan form login -->
            <div class="login-container">
                <h2 class="text-center mb-4">Welcome To Encore Shield</h2>
                <?php if (isset($login_error)) { ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php } ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                </form>
            </div>
        <?php } ?>
    </div>

            
            <div class="signup-container d-none">
                <h2 class="text-center mb-4">Welcome To Encore Shield</h2>
                <?php if (isset($signup_error)) { ?>
                    <div class="alert alert-danger"><?php echo $signup_error; ?></div>
                <?php } ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="signup">Sign Up</button>
                    <div class="text-center mt-3">
                        <a href="#" onclick="toggleForms()">Already have an account? Sign in now</a>
                    </div>
                </form>
            </div>
        </div>

    <script>
        function toggleForms() {
            var loginContainer = document.querySelector('.login-container');
            var signupContainer = document.querySelector('.signup-container');

            loginContainer.classList.toggle('d-none');
            signupContainer.classList.toggle('d-none');
        }
    </script>
</body>
</html>