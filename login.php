<?php
session_start();
include 'database.php'; 
$login_error = '';
$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM akun WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];  
            $_SESSION['username'] = $user['username'];  
            $_SESSION['avatar'] = $user['avatar']; 

            echo "<script>
                    showLoading();
                    setTimeout(function() {
                        window.location.href = '" . ($user['role'] == 'admin' ? 'DataPelanggan.php' : 'home.php') . "';
                    }, 3000);
                  </script>";
            exit();
        } else {
            $login_error = "Password salah!";
        }
    } else {
        $login_error = "Email tidak ditemukan!";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $signup_error = "Password dan konfirmasi password tidak cocok!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $role = 'user'; 

        $sql = "INSERT INTO akun (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            header("Location: login.php?status=registered"); 
            exit();
        } else {
            $signup_error = "Terjadi kesalahan saat mendaftar: " . $conn->error;
        }
    }
}


if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php if (isset($_SESSION['username'])): ?>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <img src="<?php echo $_SESSION['avatar']; ?>" alt="Avatar" width="100" height="100">
        <p>You are logged in as <?php echo $_SESSION['role']; ?>.</p>
        
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="DataPelanggan.php">Go to Admin Dashboard</a>
        <?php elseif ($_SESSION['role'] == 'user'): ?>
            <a href="home.php">Go to User Dashboard</a>
        <?php endif; ?>
        
    </div>
<?php else: ?>
    
    <div class="form-container">
        <div id="login-form">
            <h2>Login</h2>
            <?php if (!empty($login_error)): ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <a href="#" class="toggle-link" onclick="toggleForms('signup')">Don't have an account? Sign Up</a>
        </div>

        <div id="signup-form" style="display: none;">
            <h2>Sign Up</h2>
            <?php if (!empty($signup_error)): ?>
                <div class="error-message"><?php echo $signup_error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
            <a href="#" class="toggle-link" onclick="toggleForms('login')">Already have an account? Login</a>
        </div>
    </div>
<?php endif; ?>

<div id="loading-screen">Loading... Please wait.</div>

<script>
    function toggleForms(form) {
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        if (form === 'signup') {
            loginForm.style.display = 'none';
            signupForm.style.display = 'block';
        } else if (form === 'login') {
            loginForm.style.display = 'block';
            signupForm.style.display = 'none';
        }
    }
   
    function showLoading() {
        document.getElementById('loading-screen').style.display = 'flex';
    }
</script>

</body>
</html>
