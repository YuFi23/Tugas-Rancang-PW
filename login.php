<?php
session_start();
include 'database.php'; // Menghubungkan ke database

// Inisialisasi variabel error
$login_error = '';
$signup_error = '';

// Cek jika form login dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Mengambil data dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan email
    $sql = "SELECT * FROM akun WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mengambil data user
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];  // Store role if needed
            $_SESSION['username'] = $user['username'];  // Add username to session
            $_SESSION['avatar'] = $user['avatar']; // Menyimpan avatar pengguna

            // Arahkan berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: manage.php"); // Arahkan ke halaman admin
            } elseif ($user['role'] == 'user') {
                header("Location: home.php"); // Arahkan ke halaman user
            } else {
                header("Location: home3.php"); // Arahkan ke halaman lainnya (misalnya 'manager')
            }
            exit();
        } else {
            $login_error = "Password salah!";
        }
    } else {
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
        $role = 'user'; // Anda bisa mengubahnya menjadi 'admin' atau 'manager' jika diperlukan

        // Query untuk menyimpan data pengguna baru
        $sql = "INSERT INTO akun (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            header("Location: login.php?status=registered"); // Arahkan ke login setelah registrasi
            exit();
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
    exit();
}

// Menutup koneksi
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
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

<!-- Form Login -->
<h2>Login</h2>
<?php if (!empty($login_error)): ?>
    <div style="color: red;"><?php echo $login_error; ?></div>
<?php endif; ?>
<form action="login.php" method="post">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

<hr>

<!-- Form Sign Up -->
<h2>Sign Up</h2>
<?php if (!empty($signup_error)): ?>
    <div style="color: red;"><?php echo $signup_error; ?></div>
<?php endif; ?>
<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
    <button type="submit" name="signup">Sign Up</button>
</form>

</body>
</html>

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