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
            // Password cocok, simpan data pengguna dalam sesi
            $_SESSION['email'] = $user['email'];
            echo "<script>alert('Login berhasil! Selamat datang, " . $user['email'] . "');</script>";
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

        // Query untuk menyimpan data pengguna baru
        $sql = "INSERT INTO akun (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registrasi berhasil!');</script>";
        } else {
            $signup_error = "Terjadi kesalahan saat mendaftar: " . $conn->error;
        }
    }
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
        .back-button {
            background-color: #444;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-left: 40px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <a href="home.html">
        <button class="back-button">Back</button>
    </a>
    
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
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
                <div class="text-center mt-3">
                    <a href="#" onclick="toggleForms()">Don't have an account? Sign up now</a>
                </div>
            </form>
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
